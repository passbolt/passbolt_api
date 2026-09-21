<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         5.16.0
 */
namespace Passbolt\OfflineMode\Service\Items;

use App\Error\Exception\ValidationException;
use App\Utility\UserAccessControl;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Validation\Validation;
use Passbolt\OfflineMode\Model\Entity\OfflineItem;
use Passbolt\OfflineMode\Model\Table\OfflineItemsTable;

class OfflineItemsAddService
{
    use LocatorAwareTrait;

    private OfflineItemsTable $offlineItemsTable;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->offlineItemsTable = $this->fetchTable('Passbolt/OfflineMode.OfflineItems');
    }

    /**
     * @param \App\Utility\UserAccessControl $uac The caller.
     * @param string $foreignModel Object type (`Resource` for phase 1).
     * @param string $foreignKey The target object id.
     * @return \Passbolt\OfflineMode\Model\Entity\OfflineItem
     * @throws \Cake\Http\Exception\BadRequestException Invalid uuid or unknown foreign model.
     * @throws \Cake\Http\Exception\NotFoundException Resource missing, soft-deleted, or no read access.
     * @throws \Cake\Http\Exception\ForbiddenException Target item is not a v5 object.
     * @throws \App\Error\Exception\ValidationException The user is at the `max_items` limit.
     */
    public function add(UserAccessControl $uac, string $foreignModel, string $foreignKey): OfflineItem
    {
        $foreignModel = ucfirst(strtolower($foreignModel));
        if (!in_array($foreignModel, OfflineItemsTable::ALLOWED_FOREIGN_MODELS, true)) {
            throw new BadRequestException(__(
                'The offline item object type should be one of the following: {0}.',
                implode(', ', OfflineItemsTable::ALLOWED_FOREIGN_MODELS),
            ));
        }
        if (!Validation::uuid($foreignKey)) {
            throw new BadRequestException(__('The resource identifier should be a valid UUID.'));
        }

        /** @var \Passbolt\OfflineMode\Model\Table\OfflineItemsTable $OfflineItems */
        $OfflineItems = $this->fetchTable('Passbolt/OfflineMode.OfflineItems');

        $entity = $this->offlineItemsTable->newEntity(
            [
                'user_id' => $uac->getId(),
                'foreign_model' => $foreignModel,
                'foreign_key' => $foreignKey,
                'created_by' => $uac->getId(),
            ],
            [
                'accessibleFields' => [
                    'user_id' => true,
                    'foreign_model' => true,
                    'foreign_key' => true,
                    'created_by' => true,
                ],
            ],
        );

        if (!$this->offlineItemsTable->save($entity)) {
            $errors = $entity->getErrors();
            if (
                isset($errors['user_id']['offline_item_unique'])
                || isset($errors['foreign_model']['offline_item_unique'])
                || isset($errors['foreign_key']['offline_item_unique'])
            ) {
                /** @var \Passbolt\OfflineMode\Model\Entity\OfflineItem $existing */
                $existing = $OfflineItems->find()
                    ->where([
                        'user_id' => $uac->getId(),
                        'foreign_model' => $foreignModel,
                        'foreign_key' => $foreignKey,
                    ])
                    ->firstOrFail();

                return $existing;
            }
            $this->handleValidationErrors($entity);
        }

        return $entity;
    }

    /**
     * Map known buildRules / validation errors to HTTP exceptions.
     *
     * @param \Passbolt\OfflineMode\Model\Entity\OfflineItem $entity Entity to inspect.
     * @return void
     * @throws \Cake\Http\Exception\NotFoundException Resource missing, soft-deleted, or no access.
     * @throws \Cake\Http\Exception\ForbiddenException Target item is not a v5 object.
     * @throws \App\Error\Exception\ValidationException Any other unhandled validation error.
     */
    private function handleValidationErrors(OfflineItem $entity): void
    {
        $errors = $entity->getErrors();
        if (empty($errors)) {
            return;
        }

        if (
            isset($errors['foreign_key']['resource_exists'])
            || isset($errors['foreign_key']['resource_is_not_soft_deleted'])
            || isset($errors['foreign_key']['has_resource_access'])
        ) {
            throw new NotFoundException(__('The resource does not exist.'));
        }

        if (isset($errors['foreign_key']['offline_item_is_v5'])) {
            throw new ForbiddenException(__('Offline mode is only available for v5 items.'));
        }

        throw new ValidationException(
            __('Could not validate offline item data.'),
            $entity,
            $this->offlineItemsTable,
        );
    }
}

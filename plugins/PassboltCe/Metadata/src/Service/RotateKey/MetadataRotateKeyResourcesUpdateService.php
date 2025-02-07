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
 * @since         4.11.0
 */
namespace Passbolt\Metadata\Service\RotateKey;

use App\Error\Exception\CustomValidationException;
use App\Utility\UserAccessControl;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\Exception\InternalErrorException;
use Cake\I18n\FrozenTime;
use Cake\ORM\Exception\PersistenceFailedException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\TableRegistry;
use Passbolt\Metadata\Model\Dto\MetadataResourceDto;
use Passbolt\Metadata\Model\Validation\MetadataResourcesBatchRotateKeyValidationService;

class MetadataRotateKeyResourcesUpdateService
{
    use EventDispatcherTrait;
    use LocatorAwareTrait;
    use MetadataRotateKeyUpdateServiceTrait;

    /**
     * @param \App\Utility\UserAccessControl $uac UAC.
     * @param array $requestData Request data.
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException If data is invalid.
     * @throws \App\Error\Exception\CustomValidationException If data is invalid.
     * @throws \Cake\Http\Exception\NotFoundException If one or more resources are not found.
     */
    public function updateMany(UserAccessControl $uac, array $requestData): void
    {
        $uac->assertIsAdmin();
        $this->assertRequestData($requestData);

        $metadataBatchValidationService = new MetadataResourcesBatchRotateKeyValidationService();
        $data = $metadataBatchValidationService->validateMany($requestData);
        $this->updateData($uac, $data, $metadataBatchValidationService->getEntities());
    }

    /**
     * @param \App\Utility\UserAccessControl $uac User access control.
     * @param array $data Data to update
     * @param \App\Model\Entity\Resource[] $resources Resource entities
     * @return void
     */
    protected function updateData(UserAccessControl $uac, array $data, array $resources): void
    {
        /** @var \App\Model\Table\ResourcesTable $resourcesTable */
        $resourcesTable = TableRegistry::getTableLocator()->get('Resources');

        $entities = [];
        foreach ($data as $i => $values) {
            $resource = $resources[$values['id']];

            $this->assertConflict($values, $resource);

            $values = array_merge($values, [
                'modified' => FrozenTime::now(),
                'modified_by' => $uac->getId(),
            ]);
            $entity = $resourcesTable->patchEntity($resource, $values, [
                'accessibleFields' => [
                    'name' => true,
                    'username' => true,
                    'uri' => true,
                    'description' => true,
                    'metadata_key_id' => true,
                    'metadata_key_type' => true,
                    'metadata' => true,
                    'modified' => true,
                    'modified_by' => true,
                ],
                'validate' => 'v5',
            ]);
            foreach (MetadataResourceDto::V4_META_PROPS as $prop) {
                $entity->set($prop, null);
            }
            /** @var \Cake\ORM\RulesChecker $rules */
            $rules = $resourcesTable->rulesChecker();
            $resourcesTable->buildRulesV5($rules);

            if ($entity->getErrors()) {
                $errors = [$i => $entity->getErrors()];
                throw new CustomValidationException(__('The resource metadata key data could not be updated.'), $errors); // phpcs:ignore
            }

            $entities[$i] = $entity;
        }

        try {
            $resourcesTable->saveManyOrFail($entities);
        } catch (PersistenceFailedException $exception) { // @phpstan-ignore-line
            $this->handleSaveManyValidationException(
                $exception,
                $entities,
                __('The resource metadata key data could not be updated.')
            );
        } catch (\Exception $exception) {
            throw new InternalErrorException(
                __('The resource metadata key data could not be updated.'),
                null,
                $exception
            );
        }
    }
}

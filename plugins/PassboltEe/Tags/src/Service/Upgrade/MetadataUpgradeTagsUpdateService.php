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
 * @since         5.1.0
 */
namespace Passbolt\Tags\Service\Upgrade;

use App\Error\Exception\CustomValidationException;
use App\Utility\UserAccessControl;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\Exception\PersistenceFailedException;
use Cake\ORM\TableRegistry;
use Exception;
use Passbolt\Metadata\Model\Rule\IsSharedMetadataKeyUniqueActiveRule;
use Passbolt\Metadata\Model\Rule\IsV4ToV5UpgradeAllowedRule;
use Passbolt\Metadata\Service\RotateKey\AbstractMetadataRotateKeyUpdateService;
use Passbolt\Tags\Model\Dto\MetadataTagDto;
use Passbolt\Tags\Model\Validation\MetadataTagsBatchUpgradeValidationService;

class MetadataUpgradeTagsUpdateService extends AbstractMetadataRotateKeyUpdateService
{
    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->metadataBatchValidationService = new MetadataTagsBatchUpgradeValidationService();
    }

    /**
     * @inheritDoc
     */
    protected function updateData(UserAccessControl $uac, array $data, array $entitiesToUpdate): void
    {
        /** @var \Passbolt\Tags\Model\Table\TagsTable $tagsTable */
        $tagsTable = TableRegistry::getTableLocator()->get('Passbolt/Tags.Tags');

        $entities = [];
        foreach ($data as $i => $values) {
            $tag = $entitiesToUpdate[$values['id']];

            $entity = $tagsTable->patchEntity($tag, $values, [
                'accessibleFields' => [
                    'slug' => true,
                    'metadata_key_id' => true,
                    'metadata_key_type' => true,
                    'metadata' => true,
                ],
                'validate' => 'v5',
            ]);
            foreach (MetadataTagDto::V4_META_PROPS as $prop) {
                $entity->set($prop, null);
            }
            /** @var \Cake\ORM\RulesChecker $rules */
            $rules = $tagsTable->rulesChecker();
            $tagsTable->buildRulesV5($rules);

            if ($entity->getErrors()) {
                $errors = [$i => $entity->getErrors()];
                throw new CustomValidationException(__('The tag metadata key data could not be updated.'), $errors); // phpcs:ignore
            }

            $entities[$i] = $entity;
        }

        try {
            $tagsTable->saveManyOrFail($entities, [
                IsV4ToV5UpgradeAllowedRule::SKIP_RULE_OPTION => true,
                IsSharedMetadataKeyUniqueActiveRule::SKIP_RULE_OPTION => false,
            ]);
        } catch (PersistenceFailedException $exception) { // @phpstan-ignore-line
            $this->handleSaveManyValidationException(
                $exception,
                $entities,
                __('The tag metadata key data could not be updated.')
            );
        } catch (Exception $exception) {
            throw new InternalErrorException(
                __('The tag metadata key data could not be updated.'),
                null,
                $exception
            );
        }
    }
}

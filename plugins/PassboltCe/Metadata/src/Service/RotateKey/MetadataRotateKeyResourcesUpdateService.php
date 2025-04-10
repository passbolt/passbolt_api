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
use Cake\Http\Exception\InternalErrorException;
use Cake\I18n\DateTime;
use Cake\ORM\Exception\PersistenceFailedException;
use Cake\ORM\TableRegistry;
use Exception;
use Passbolt\Metadata\Model\Dto\MetadataResourceDto;
use Passbolt\Metadata\Model\Rule\IsSharedMetadataKeyUniqueActiveRule;
use Passbolt\Metadata\Model\Rule\IsV4ToV5UpgradeAllowedRule;
use Passbolt\Metadata\Model\Validation\MetadataResourcesBatchRotateKeyValidationService;

class MetadataRotateKeyResourcesUpdateService extends AbstractMetadataRotateKeyUpdateService
{
    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->metadataBatchValidationService = new MetadataResourcesBatchRotateKeyValidationService();
    }

    /**
     * @inheritDoc
     */
    protected function updateData(UserAccessControl $uac, array $data, array $entitiesToUpdate): void
    {
        /** @var \App\Model\Table\ResourcesTable $resourcesTable */
        $resourcesTable = TableRegistry::getTableLocator()->get('Resources');

        $entities = [];
        foreach ($data as $i => $values) {
            $resource = $entitiesToUpdate[$values['id']];

            $this->assertConflict($values, $resource);

            $values = array_merge($values, [
                'modified' => DateTime::now(),
                'modified_by' => $uac->getId(),
            ]);
            $entity = $resourcesTable->patchEntity($resource, $values, [
                'accessibleFields' => [
                    'name' => true,
                    'username' => true,
                    'uri' => true,
                    'description' => true,
                    'resource_type_id' => true, // required for upgrade
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
            $resourcesTable->saveManyOrFail($entities, [
                IsV4ToV5UpgradeAllowedRule::SKIP_RULE_OPTION => true,
                IsSharedMetadataKeyUniqueActiveRule::SKIP_RULE_OPTION => false,
            ]);
        } catch (PersistenceFailedException $exception) { // @phpstan-ignore-line
            $this->handleSaveManyValidationException(
                $exception,
                $entities,
                __('The resource metadata key data could not be updated.')
            );
        } catch (Exception $exception) {
            throw new InternalErrorException(
                __('The resource metadata key data could not be updated.'),
                null,
                $exception
            );
        }
    }
}

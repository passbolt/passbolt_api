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
 * @since         4.12.0
 */
namespace Passbolt\Metadata\Service\RotateKey;

use App\Error\Exception\CustomValidationException;
use App\Utility\UserAccessControl;
use Cake\Http\Exception\InternalErrorException;
use Cake\I18n\DateTime;
use Cake\ORM\Exception\PersistenceFailedException;
use Cake\ORM\TableRegistry;
use Passbolt\Metadata\Model\Dto\MetadataFolderDto;
use Passbolt\Metadata\Model\Rule\IsSharedMetadataKeyUniqueActiveRule;
use Passbolt\Metadata\Model\Rule\IsV4ToV5UpgradeAllowedRule;
use Passbolt\Metadata\Model\Validation\MetadataFoldersBatchRotateKeyValidationService;

class MetadataRotateKeyFoldersUpdateService extends AbstractMetadataRotateKeyUpdateService
{
    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->metadataBatchValidationService = new MetadataFoldersBatchRotateKeyValidationService();
    }

    /**
     * @inheritDoc
     */
    protected function updateData(UserAccessControl $uac, array $data, array $entitiesToUpdate): void
    {
        /** @var \Passbolt\Folders\Model\Table\FoldersTable $foldersTable */
        $foldersTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.Folders');

        $entities = [];
        foreach ($data as $i => $values) {
            $folder = $entitiesToUpdate[$values['id']];

            $this->assertConflict($values, $folder);

            $values = array_merge($values, [
                'modified' => \Cake\I18n\DateTime::now(),
                'modified_by' => $uac->getId(),
            ]);
            $entity = $foldersTable->patchEntity($folder, $values, [
                'accessibleFields' => [
                    'name' => true,
                    'metadata_key_id' => true,
                    'metadata_key_type' => true,
                    'metadata' => true,
                    'modified' => true,
                    'modified_by' => true,
                ],
                'validate' => 'v5',
            ]);
            foreach (MetadataFolderDto::V4_META_PROPS as $prop) {
                $entity->set($prop, null);
            }
            /** @var \Cake\ORM\RulesChecker $rules */
            $rules = $foldersTable->rulesChecker();
            $foldersTable->buildRulesV5($rules);

            if ($entity->getErrors()) {
                $errors = [$i => $entity->getErrors()];
                throw new CustomValidationException(__('The folder metadata key data could not be updated.'), $errors); // phpcs:ignore
            }

            $entities[$i] = $entity;
        }

        try {
            $foldersTable->saveManyOrFail($entities, [
                IsV4ToV5UpgradeAllowedRule::SKIP_RULE_OPTION => true,
                IsSharedMetadataKeyUniqueActiveRule::SKIP_RULE_OPTION => false,
            ]);
        } catch (PersistenceFailedException $exception) { // @phpstan-ignore-line
            $this->handleSaveManyValidationException(
                $exception,
                $entities,
                __('The folder metadata key data could not be updated.')
            );
        } catch (\Exception $exception) {
            throw new InternalErrorException(
                __('The folder metadata key data could not be updated.'),
                null,
                $exception
            );
        }
    }
}

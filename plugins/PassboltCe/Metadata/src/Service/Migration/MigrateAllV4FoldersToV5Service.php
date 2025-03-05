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
 * @since         4.10.0
 */
namespace Passbolt\Metadata\Service\Migration;

use App\Service\OpenPGP\OpenPGPCommonServerOperationsTrait;
use App\Service\OpenPGP\OpenPGPCommonUserOperationsTrait;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Folders\Model\Entity\Folder;
use Passbolt\Folders\Model\Table\FoldersTable;
use Passbolt\Metadata\Model\Dto\MetadataFolderDto;
use Passbolt\Metadata\Service\OpenPGP\OpenPGPCommonMetadataOperationsTrait;
use Passbolt\Metadata\Utility\MetadataSettingsAwareTrait;

class MigrateAllV4FoldersToV5Service implements V4ToV5MigrationServiceInterface
{
    use LocatorAwareTrait;
    use OpenPGPCommonMetadataOperationsTrait;
    use OpenPGPCommonUserOperationsTrait;
    use OpenPGPCommonServerOperationsTrait;
    use MetadataSettingsAwareTrait;

    private FoldersTable $Folders;

    /**
     * Result of migration.
     *
     * @var array
     */
    private array $result = [
        'success' => true,
        'migrated' => [],
        'errors' => [],
    ];

    /**
     * Constructor.
     */
    public function __construct()
    {
        /** @phpstan-ignore-next-line */
        $this->Folders = $this->fetchTable('Passbolt/Folders.Folders');
    }

    /**
     * @inheritDoc
     */
    public function getHumanReadableName(): string
    {
        return 'folder';
    }

    /**
     * Migrates all V4 folders to V5.
     *
     * @return array Result
     * @throws \Cake\Http\Exception\BadRequestException If V5 folder creation/modification is allowed.
     */
    public function migrate(): array
    {
        $this->assertV5FolderCreationEnabled();

        /** @var \Passbolt\Folders\Model\Entity\Folder[] $folders */
        $folders = $this->Folders
            ->find()
            ->contain(['Permissions.Users.Gpgkeys'])
            ->where(['name IS NOT NULL'])
            ->all()
            ->toArray();

        if (empty($folders)) {
            $this->addError(['error_message' => __('No folders to migrate.')]);

            return $this->getResult();
        }

        foreach ($folders as $folder) {
            $dto = MetadataFolderDto::fromArray($folder->toArray());

            try {
                if ($dto->isV5()) {
                    $msg = __('Folder ID "{0}" is already V5', $folder->id);
                    throw new InternalErrorException($msg);
                }
                if (count($folder->permissions) === 0) {
                    $msg = __('No permission found for folder ID {0}', $folder->id);
                    throw new InternalErrorException($msg);
                }

                if (count($folder->permissions) === 1) {
                    $this->migratePersonal($dto, $folder);
                } else {
                    $this->migrateShared($dto, $folder);
                }
                $this->addMigrated($folder);
            } catch (\Exception $e) {
                // Continue with next resource if any error
                $error = ['folder_id' => $folder->id, 'error_message' => $e->getMessage()];
                if (Configure::read('debug')) {
                    $error['trace'] = $e->getTraceAsString();
                }
                $this->addError($error);
            }
        }

        return $this->getResult();
    }

    /**
     * Returns migration result.
     *
     * @return array
     */
    public function getResult(): array
    {
        $this->result['success'] = empty($this->result['errors']);

        return $this->result;
    }

    /**
     * @param \Passbolt\Metadata\Model\Dto\MetadataFolderDto $dto DTO.
     * @param \Passbolt\Folders\Model\Entity\Folder $folder Folder entity.
     * @return void
     */
    private function migratePersonal(MetadataFolderDto $dto, Folder $folder): void
    {
        $metadataArray = $dto->getClearTextMetadata();

        /** @var \App\Model\Entity\Permission $permission */
        $permission = $folder->get('permissions')[0];
        $user = $permission->user;
        if (is_null($user)) {
            $msg = __('No user provided.') . ' ';
            $msg .= __('The metadata could not be encrypted for permission id: {0}.', $permission->id);
            throw new InternalErrorException($msg);
        }
        if (is_null($user->gpgkey)) {
            $msg = __('No OpenPGP key found for the user.') . ' ';
            $msg .= __('The metadata could not be encrypted with the user id: {0}.', $user->id);
            throw new InternalErrorException($msg);
        }

        try {
            $gpg = OpenPGPBackendFactory::get();
            $gpg->clearKeys();
            $gpg = $this->setSignKeyWithServerKey($gpg);
            $gpg = $this->setEncryptKeyWithUserKey($gpg, $user->gpgkey);
            $metadataClearText = json_encode($metadataArray, JSON_THROW_ON_ERROR);
            $metadataEncrypted = $gpg->encrypt($metadataClearText, true);
        } catch (\Exception $exception) {
            $msg = $exception->getMessage() . ' ';
            $msg .= __('The metadata could not be encrypted with the user id: {0}.', $user->id);
            throw new InternalErrorException($msg, 500, $exception);
        }

        $this->updateFolder($folder, [
            'name' => null,
            'metadata' => $metadataEncrypted,
            'metadata_key_id' => $user->gpgkey->id,
            'metadata_key_type' => 'user_key',
        ]);
    }

    /**
     * @param \Passbolt\Metadata\Model\Dto\MetadataFolderDto $dto DTO.
     * @param \Passbolt\Folders\Model\Entity\Folder $folder Entity.
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When there is no metadata key record.
     */
    private function migrateShared(MetadataFolderDto $dto, Folder $folder): void
    {
        $metadataArray = $dto->getClearTextMetadata();
        $metadataKey = $this->getMetadataKeyForEncryption();

        try {
            $gpg = OpenPGPBackendFactory::get();
            $gpg->clearKeys();
            $gpg = $this->setSignKeyWithServerKey($gpg);
            $gpg = $this->setEncryptKeyWithMetadataKey($gpg, $metadataKey);
            $metadataClearText = json_encode($metadataArray, JSON_THROW_ON_ERROR);
            $metadataEncrypted = $gpg->encrypt($metadataClearText, true);
        } catch (\Exception $exception) {
            $msg = $exception->getMessage() . ' ';
            $msg .= __('The metadata could not be encrypted with the metadata key id: {0}.', $metadataKey->id);
            throw new InternalErrorException($msg, 500, $exception);
        }

        $this->updateFolder($folder, [
            'name' => null,
            'metadata' => $metadataEncrypted,
            'metadata_key_id' => $metadataKey->id,
            'metadata_key_type' => 'shared_key',
            //TODO support nullable resource.modified_by to allow server side modification
            //'modified_by' => null,
        ]);
    }

    /**
     * Updates entity with given data.
     *
     * @param \Passbolt\Folders\Model\Entity\Folder $folder Folder entity to update.
     * @param array $data Data to update.
     * @return void
     */
    private function updateFolder(Folder $folder, array $data): void
    {
        try {
            $folder = $this->Folders->patchEntity($folder, $data, [
                'accessibleFields' => [
                    'name' => true,
                    'metadata' => true,
                    'metadata_key_id' => true,
                    'metadata_key_type' => true,
                ],
                'validate' => 'v5',
            ]);
            $this->Folders->saveOrFail($folder, ['checkRules' => false]);
        } catch (\Exception $exception) {
            $msg = __('Unable to migrate folder ID: {0} to V5.', $folder->id);
            $msg .= ' ' . $exception->getMessage();
            throw new InternalErrorException($msg, 500, $exception);
        }
    }

    /**
     * @return \Passbolt\Metadata\Model\Entity\MetadataKey
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When there is no metadata key record.
     */
    private function getMetadataKeyForEncryption()
    {
        /** @var \Passbolt\Metadata\Model\Table\MetadataKeysTable $metadataKeysTable */
        $metadataKeysTable = $this->fetchTable('Passbolt/Metadata.MetadataKeys');

        /** @var \Passbolt\Metadata\Model\Entity\MetadataKey $metadataKey */
        $metadataKey = $metadataKeysTable->getLatestActiveKey();

        return $metadataKey;
    }

    /**
     * Append a new error to the result.
     *
     * @param array $error Error.
     * @return void
     */
    private function addError(array $error): void
    {
        $this->result['errors'][] = $error;
    }

    /**
     * @param \Passbolt\Folders\Model\Entity\Folder $folder entity
     * @return void
     */
    private function addMigrated(Folder $folder): void
    {
        $this->result['migrated'][] = $folder->id;
    }
}

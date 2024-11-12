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
 * @since         2.13.0
 */

namespace Passbolt\Folders\Service\Folders;

use App\Error\Exception\ValidationException;
use App\Model\Entity\Permission;
use App\Model\Table\PermissionsTable;
use App\Utility\UserAccessControl;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Passbolt\Folders\Model\Entity\Folder;
use Passbolt\Metadata\Model\Dto\MetadataFolderDto;
use Passbolt\Metadata\Model\Dto\MetadataTypesSettingsDto;
use Passbolt\Metadata\Utility\Folders\FolderSaveV5AwareTrait;
use Passbolt\Metadata\Utility\MetadataSettingsAwareTrait;

class FoldersUpdateService
{
    use EventDispatcherTrait;
    use FolderSaveV5AwareTrait;
    use MetadataSettingsAwareTrait;

    public const FOLDERS_UPDATE_FOLDER_EVENT = 'folders.folder.update';

    /**
     * @var \Passbolt\Folders\Model\Table\FoldersTable
     */
    public $foldersTable;

    /**
     * @var \App\Model\Table\PermissionsTable
     */
    private $permissionsTable;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->foldersTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.Folders');
        $this->permissionsTable = TableRegistry::getTableLocator()->get('Permissions');
    }

    /**
     * Update a folder for the current user.
     *
     * @param \App\Utility\UserAccessControl $uac The current user
     * @param string $id The folder to update
     * @param \Passbolt\Metadata\Model\Dto\MetadataFolderDto $folderDto The folder DTO.
     * @return \Passbolt\Folders\Model\Entity\Folder
     * @throws \App\Error\Exception\ValidationException When validation is triggered
     * @throws \Exception If an unexpected error occurred
     */
    public function update(UserAccessControl $uac, string $id, MetadataFolderDto $folderDto)
    {
        $this->assertCreationAllowedByMetadataSettings($folderDto->isV5(), MetadataTypesSettingsDto::ENTITY_FOLDER);

        $folder = $this->getFolder($uac, $id);

        $this->foldersTable->getConnection()->transactional(function () use (&$folder, $uac, $folderDto) {
            $this->updateFolderMeta($uac, $folder, $folderDto);
        });

        $this->dispatchEvent(self::FOLDERS_UPDATE_FOLDER_EVENT, [
            'uac' => $uac,
            'folder' => $folder,
            'isV5' => $folderDto->isV5(),
        ]);

        return $folder;
    }

    /**
     * Retrieve the folder.
     *
     * @param \App\Utility\UserAccessControl $uac UserAccessControl updating the resource
     * @param string $folderId The folder identifier to retrieve.
     * @return \Passbolt\Folders\Model\Entity\Folder
     * @throws \Cake\Http\Exception\NotFoundException If the folder does not exist.
     */
    private function getFolder(UserAccessControl $uac, string $folderId)
    {
        /** @var \App\Model\Entity\Permission|null $permission */
        $permission = $this->permissionsTable
            ->findHighestByAcoAndAro(PermissionsTable::FOLDER_ACO, $folderId, $uac->getId())
            ->first();

        if (empty($permission)) {
            throw new NotFoundException(__('The folder does not exist.'));
        } elseif ($permission->type < Permission::UPDATE) {
            throw new ForbiddenException(__('You are not allowed to update this folder.'));
        }

        return $this->foldersTable->get($folderId);
    }

    /**
     * Update folder meta.
     *
     * @param \App\Utility\UserAccessControl $uac The current user
     * @param \Passbolt\Folders\Model\Entity\Folder $folder The folder to update.
     * @param \Passbolt\Metadata\Model\Dto\MetadataFolderDto $folderDto The folder dto.
     * @return \Cake\Datasource\EntityInterface|\Passbolt\Folders\Model\Entity\Folder
     */
    private function updateFolderMeta(UserAccessControl $uac, Folder $folder, MetadataFolderDto $folderDto)
    {
        $this->patchEntity($uac, $folder, $folderDto);
        $this->handleValidationErrors($folder);
        $this->foldersTable->save($folder);
        $this->handleValidationErrors($folder);

        return $folder;
    }

    /**
     * Patch the folder entity.
     *
     * @param \App\Utility\UserAccessControl $uac UAC.
     * @param \Passbolt\Folders\Model\Entity\Folder $folder The folder entity to update.
     * @param \Passbolt\Metadata\Model\Dto\MetadataFolderDto $folderDto The folder DTO.
     * @return \Cake\Datasource\EntityInterface|\Passbolt\Folders\Model\Entity\Folder
     */
    private function patchEntity(UserAccessControl $uac, Folder $folder, MetadataFolderDto $folderDto)
    {
        $data = $folderDto->toArray();
        $data = array_merge($data, [
            'modified_by' => $uac->getId(),
        ]);

        $options = $this->getOptionsForFolderSave($folderDto);
        $options['accessibleFields'] = array_merge($options['accessibleFields'], [
            'modified_by' => true,
        ]);

        return $this->foldersTable->patchEntity($folder, $data, $options);
    }

    /**
     * Handle folder validation errors.
     *
     * @param \Passbolt\Folders\Model\Entity\Folder $folder The folder
     * @return void
     * @throws \App\Error\Exception\ValidationException If the provided data does not validate.
     */
    private function handleValidationErrors(Folder $folder)
    {
        $errors = $folder->getErrors();
        if (!empty($errors)) {
            throw new ValidationException(__('Could not validate folder data.'), $folder, $this->foldersTable);
        }
    }
}

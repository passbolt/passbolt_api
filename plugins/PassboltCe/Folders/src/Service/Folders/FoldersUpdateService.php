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

class FoldersUpdateService
{
    use EventDispatcherTrait;

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
     * @param array|null $data The folder data
     * @return \Passbolt\Folders\Model\Entity\Folder
     * @throws \Exception If an unexpected error occurred
     */
    public function update(UserAccessControl $uac, string $id, ?array $data = [])
    {
        $folder = $this->getFolder($uac, $id);
        $meta = $this->extractDataFolderMeta($data);

        if (empty($meta)) {
            return $folder;
        }

        $this->foldersTable->getConnection()->transactional(function () use (&$folder, $uac, $meta) {
            $this->updateFolderMeta($uac, $folder, $meta);
        });

        $this->dispatchEvent(self::FOLDERS_UPDATE_FOLDER_EVENT, [
            'uac' => $uac,
            'folder' => $folder,
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
     * Extract the resource meta data from the request data
     *
     * @param array $data The request data
     * @return array
     */
    private function extractDataFolderMeta(array $data)
    {
        $meta = [];

        if (array_key_exists('name', $data)) {
            $meta['name'] = $data['name'];
        }

        return $meta;
    }

    /**
     * Update folder meta.
     *
     * @param \App\Utility\UserAccessControl $uac The current user
     * @param \Passbolt\Folders\Model\Entity\Folder $folder The folder to update.
     * @param array $data The folder meta to updated
     * @return \Cake\Datasource\EntityInterface|\Passbolt\Folders\Model\Entity\Folder
     */
    private function updateFolderMeta(UserAccessControl $uac, Folder $folder, array $data)
    {
        $this->patchEntity($folder, $data);
        $this->handleValidationErrors($folder);
        $this->foldersTable->save($folder);
        $this->handleValidationErrors($folder);

        return $folder;
    }

    /**
     * Patch the folder entity.
     *
     * @param \Passbolt\Folders\Model\Entity\Folder $folder The folder entity to update.
     * @param array $data The folder data.
     * @return \Cake\Datasource\EntityInterface|\Passbolt\Folders\Model\Entity\Folder
     */
    private function patchEntity($folder, $data)
    {
        $accessibleFields = [
            'name' => true,
        ];

        return $this->foldersTable->patchEntity($folder, $data, ['accessibleFields' => $accessibleFields]);
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

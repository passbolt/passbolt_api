<?php
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
 * @since         2.14.0
 */

namespace Passbolt\Folders\Service;

use App\Error\Exception\ValidationException;
use App\Model\Entity\Permission;
use App\Model\Table\PermissionsTable;
use App\Service\Permissions\UserHasPermissionService;
use App\Utility\UserAccessControl;
use Cake\Http\Exception\ForbiddenException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\Folders\Model\Entity\Folder;
use Passbolt\Folders\Model\Table\FoldersTable;

class FoldersUpdateService
{
    /**
     * @var FoldersTable
     */
    private $foldersTable;

    /**
     * @var FoldersMoveService
     */
    private $foldersMoveService;

    /**
     * @var FoldersRelationsTable
     */
    private $foldersRelationsTable;

    /**
     * @var PermissionsTable
     */
    private $permissionsTable;

    /**
     * @var UserHasPermissionService
     */
    private $userHasPermissionService;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->foldersTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.Folders');
        $this->foldersRelationsTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.FoldersRelations');
        $this->permissionsTable = TableRegistry::getTableLocator()->get('Permissions');
        $this->foldersMoveService = new FoldersMoveService();
        $this->userHasPermissionService = new UserHasPermissionService();
    }

    public function update(UserAccessControl $uac, string $id, array $data = [])
    {
        if (empty($data)) {
            return;
        }

        $userId = $uac->userId();
        $folder = $this->foldersTable->get($id);

        $this->foldersTable->getConnection()->transactional(function () use (&$folder, $uac, $userId, $folder, $data) {
            if (isset($data['name'])) {
                 $this->updateFolder($userId, $folder, $data['name']);
            }
            if (isset($data['folder_parent_id'])) {
                $this->foldersMoveService->move($uac, $folder->id, $data['folder_parent_id']);
            }
        });

        return $folder;
    }

    private function updateFolder(string $userId, Folder $folder, string $name)
    {
        if ($folder->name === $name) {
            return;
        }

        $isAllowed = $this->userHasPermissionService->check(PermissionsTable::FOLDER_ACO, $folder->id, $userId, Permission::UPDATE);
        if (!$isAllowed) {
            throw new ForbiddenException(__('You are not allowed to update this folder.'));
        }

        $data = ['name' => $name];
        $this->patchEntity($folder, $data);
        $this->handleValidationErrors($folder);
        $this->foldersTable->save($folder);
        $this->handleValidationErrors($folder);

        return $folder;
    }

    private function patchEntity($folder, $data)
    {
        $accessibleFields = [
            'name' => true
        ];

        return $this->foldersTable->patchEntity($folder, $data, ['accessibleFields' => $accessibleFields]);
    }

    private function handleValidationErrors($folder)
    {
        $errors = $folder->getErrors();
        if (!empty($errors)) {
            throw new ValidationException(__('Could not validate the folder data.'), $folder, $this->foldersTable);
        }
    }
}


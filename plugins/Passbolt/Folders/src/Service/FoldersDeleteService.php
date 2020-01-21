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

class FoldersDeleteService
{
    /**
     * @var FoldersTable
     */
    private $foldersTable;

    /**
     * @var FoldersRelationDeleteService
     */
    private $foldersRelationsDeleteService;

    /**
     * @var FoldersPermissionsDeleteService
     */
    private $foldersPermissionsDeleteService;

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
        $this->userHasPermissionService = new UserHasPermissionService();
        $this->foldersRelationsDeleteService = new FoldersRelationsDeleteService();
        $this->foldersPermissionsDeleteService = new FoldersPermissionsDeleteService();
    }

    public function delete(UserAccessControl $uac, string $id, bool $cascade = false)
    {
        $userId = $uac->userId();
        $isAllowed = $this->userHasPermissionService->check(PermissionsTable::FOLDER_ACO, $id, $userId, Permission::UPDATE);
        if (!$isAllowed) {
            throw new ForbiddenException(__('You are not allowed to delete this folder.'));
        }

        $folder = $this->foldersTable->get($id);
        $this->foldersTable->getConnection()->transactional(function () use (&$folder, $uac, $folder) {
            $this->deletePermissions($uac, $folder);
            $this->deleteFoldersRelations($uac, $folder);
            $this->deleteFolder($uac, $folder);
        });
    }

    private function deletePermissions($uac, $folder)
    {
        $this->foldersPermissionsDeleteService->delete($uac, $folder->id);
    }

    private function deleteFoldersRelations($uac, $folder) {
        $this->foldersRelationsDeleteService->delete($uac, $folder->id);
    }

    private function deleteFolder($uac, $folder) {

        $this->foldersTable->delete($folder);
        $this->handleValidationErrors($folder);
        $this->foldersPermissionsDeleteService->delete($uac, $folder->id);
    }

    private function handleValidationErrors($folder)
    {
        $errors = $folder->getErrors();
        if (!empty($errors)) {
            throw new ValidationException(__('Could not delete the folder.'), $folder, $this->foldersTable);
        }
    }
}


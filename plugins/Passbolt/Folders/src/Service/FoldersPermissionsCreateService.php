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
use App\Utility\UserAccessControl;
use Cake\ORM\TableRegistry;

class FoldersPermissionsCreateService
{
    /**
     * @var PermissionsTable
     */
    private $permissionsTable;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->permissionsTable = TableRegistry::getTableLocator()->get('Permissions');
    }

    /**
     * Create a folder permission
     *
     * @param UserAccessControl $uac The current user.
     * @param string $folderId The folder to create a permission for
     * @param int $permissionType The permission type
     * @return Permission
     * @throws \Exception
     */
    public function create(UserAccessControl $uac, string $folderId, int $permissionType)
    {
        $permission = null;
        $userId = $uac->userId();

        $this->permissionsTable->getConnection()->transactional(function () use (&$permission, $folderId, $userId, $permissionType) {
            $permission = $this->createPermission($folderId, $userId, $permissionType);
        });

        return $permission;
    }

    /**
     * Creat and save the permission in database.
     *
     * @param string $folderId The folder to create the permission for.
     * @param string $userId The user to create the permission for.
     * @param int $permissionType The permission type.
     * @return void
     */
    private function createPermission(string $folderId, string $userId, int $permissionType)
    {
        $permission = $this->buildPermissionEntity($folderId, $userId, $permissionType);
        $this->handlePermissionValidationErrors($permission);
        $this->permissionsTable->save($permission);
        $this->handlePermissionValidationErrors($permission);
    }

    /**
     * Build the permission entity.
     *
     * @param string $folderId The folder to create the permission for.
     * @param string $userId The user to create the permission for.
     * @param int $permissionType The permission type.
     * @return Permission
     */
    private function buildPermissionEntity(string $folderId, string $userId, int $permissionType)
    {
        $data = [
            'aco' => PermissionsTable::FOLDER_ACO,
            'aco_foreign_key' => $folderId,
            'aro' => PermissionsTable::USER_ARO,
            'aro_foreign_key' => $userId,
            'type' => $permissionType,
            'created_by' => $userId,
            'modified_by' => $userId,
        ];
        $accessibleFields = [
            'aco' => true,
            'aco_foreign_key' => true,
            'aro' => true,
            'aro_foreign_key' => true,
            'type' => true,
            'created_by' => true,
            'modified_by' => true,
        ];

        return $this->permissionsTable->newEntity($data, ['accessibleFields' => $accessibleFields]);
    }

    /**
     * Handle permission validation errors.
     *
     * @param Permission $permission The permission
     * @return void
     */
    private function handlePermissionValidationErrors(Permission $permission)
    {
        $errors = $permission->getErrors();
        if (!empty($errors)) {
            throw new ValidationException(__('Could not validate the folder permission data.'), $permission, $this->permissionsTable);
        }
    }
}

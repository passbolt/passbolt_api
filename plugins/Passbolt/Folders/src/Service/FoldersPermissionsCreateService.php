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

    public function create(UserAccessControl $uac, string $folderId, string $permissionType)
    {
        $permission = null;
        $userId = $uac->userId();

        $this->permissionsTable->getConnection()->transactional(function () use (&$permission, $folderId, $userId, $permissionType) {
            $permission = $this->createPermission($folderId, $userId, $permissionType);
        });

        return $permission;
    }

    private function createPermission(string $folderId, string $userId, string $permissionType)
    {
        $permission = $this->buildPermissionEntity($folderId, $userId, $permissionType);
        $this->handlePermissionValidationErrors($permission);
        $this->permissionsTable->save($permission);
        $this->handlePermissionValidationErrors($permission);
    }

    private function buildPermissionEntity($folderId, $userId, $permissionType)
    {
        $data = [
            'aco' => PermissionsTable::FOLDER_ACO,
            'aco_foreign_key' => $folderId,
            'aro' => PermissionsTable::USER_ARO,
            'aro_foreign_key' => $userId,
            'type' => $permissionType,
            'created_by' => $userId,
            'modified_by' => $userId
        ];
        $accessibleFields = [
            'aco' => true,
            'aco_foreign_key' => true,
            'aro' => true,
            'aro_foreign_key' => true,
            'type' => true,
            'created_by' => true,
            'modified_by' => true
        ];

        return $this->permissionsTable->newEntity($data, ['accessibleFields' => $accessibleFields]);
    }

    private function handlePermissionValidationErrors($permission)
    {
        $errors = $permission->getErrors();
        if (!empty($errors)) {
            throw new ValidationException(__('Could not validate the folder permission data.'), $permission, $this->permissionsTable);
        }
    }
}

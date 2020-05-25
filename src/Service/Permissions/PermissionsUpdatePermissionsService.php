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
 * @since         2.13.0
 */

namespace App\Service\Permissions;

use App\Error\Exception\CustomValidationException;
use App\Error\Exception\ValidationException;
use App\Model\Entity\Permission;
use App\Model\Table\PermissionsTable;
use App\Utility\UserAccessControl;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class PermissionsUpdatePermissionsService
{
    /**
     * @var PermissionsAcoHasOwnerService
     */
    private $permissionsAcoHasOwnerService;

    /**
     * @var PermissionsCreateService
     */
    private $permissionsCreateService;

    /**
     * @var PermissionsTable
     */
    private $permissionsTable;

    /**
     * @param PermissionsTable $permissionsTable PermissionsTable instance
     */
    public function __construct(PermissionsTable $permissionsTable = null)
    {
        $this->permissionsAcoHasOwnerService = new PermissionsAcoHasOwnerService();
        $this->permissionsCreateService = new PermissionsCreateService();
        $this->permissionsTable = $permissionsTable;
        if (is_null($this->permissionsTable)) {
            $this->permissionsTable = TableRegistry::getTableLocator()->get('Permissions');
        }
    }

    /**
     * Update an entity permissions.
     *
     * @param UserAccessControl $uac The operator.
     * @param string $aco The type of entity
     * @param string $acoForeignkey The target entity id
     * @param array $data The permissions to update
     * @return array
     * [
     *   added => <array> List of added permissions
     *   deleted => <array> List of deleted permissions
     *   updated => <array> List of updated permissions
     * ]
     * @throws \Exception If something unexpected occurred
     */
    public function updatePermissions(UserAccessControl $uac, string $aco, string $acoForeignkey, array $data = [])
    {
        $addedPermissions = [];
        $updatedPermissions = [];
        $deletedPermissions = [];

        foreach ($data as $rowIndex => $row) {
            $permissionId = Hash::get($row, 'id', null);

            // A new permission is provided when no id is found in the raw data.
            if (is_null($permissionId)) {
                $permission = $this->addPermission($uac, $rowIndex, $aco, $acoForeignkey, $row);
                $addedPermissions[] = $permission;
            } else {
                // If a property delete is found and set to true, then delete the permission.
                // Otherwise update it.
                $permission = $this->getPermission($rowIndex, $acoForeignkey, $permissionId);
                $delete = Hash::get($row, 'delete');
                if ($delete) {
                    $permission = $this->deletePermission($permission);
                    $deletedPermissions[] = $permission;
                } else {
                    $permission = $this->updatePermission($uac, $rowIndex, $permission, $row);
                    $updatedPermissions[] = $permission;
                }
            }
        }

        $this->assertAtLeastOneOwnerPermission($acoForeignkey);

        return [
            'added' => $addedPermissions,
            'removed' => $deletedPermissions,
            'updated' => $updatedPermissions,
        ];
    }

    /**
     * Add a permission to an entity.
     *
     * @param UserAccessControl $uac The operator
     * @param int $rowIndexRef The row index in the request data
     * @param string $aco The type of entity
     * @param string $acoForeignkey The target entity id
     * @param array $data The permission data
     * @return Permission
     * @throws \Exception
     */
    private function addPermission(UserAccessControl $uac, int $rowIndexRef, string $aco, string $acoForeignkey, array $data)
    {
        $permissionData = [
            'aco' => $aco,
            'aco_foreign_key' => $acoForeignkey,
            'aro' => Hash::get($data, 'aro', ''),
            'aro_foreign_key' => Hash::get($data, 'aro_foreign_key', ''),
            'type' => Hash::get($data, 'type', ''),
        ];

        try {
            return $this->permissionsCreateService->create($uac, $permissionData);
        } catch (ValidationException $e) {
            $errors = [$rowIndexRef => $e->getErrors()];
            $this->handleValidationErrors($errors);
        }
    }

    /**
     * Handle permissions validation errors.
     *
     * @param array $errors The list of errors
     * @return void
     * @throws CustomValidationException If the provided data does not validate.
     */
    private function handleValidationErrors(array $errors = [])
    {
        if (!empty($errors)) {
            throw new CustomValidationException(__('Could not validate permissions data.'), $errors, $this->permissionsTable);
        }
    }

    /**
     * Retrieve a permission.
     *
     * @param int $rowIndexRef The row index in the request data
     * @param string $acoForeignkey The target entity id
     * @param string $permissionId The permission identifier to retrieve.
     * @return Permission
     * @throws ValidationException If the permission does not exist.
     */
    private function getPermission(int $rowIndexRef, string $acoForeignkey, string $permissionId)
    {
        $permission = $this->permissionsTable->findByIdAndAcoForeignKey($permissionId, $acoForeignkey)->first();

        if (!$permission) {
            $errors = [$rowIndexRef => ['id' => ['exists' => 'The permission does not exist.']]];
            $this->handleValidationErrors($errors);
        }

        return $permission;
    }

    /**
     * Delete a permission.
     *
     * @param Permission $permission The target permission
     * @return Permission
     * @throws Exception
     */
    private function deletePermission(Permission $permission)
    {
        $this->permissionsTable->delete($permission);

        return $permission;
    }

    /**
     * Update a permission.
     *
     * @param UserAccessControl $uac The operator
     * @param int $rowIndexRef The row index in the request data
     * @param Permission $permission The target permission to update
     * @param array $data The permission data.
     * @return Permission
     */
    private function updatePermission(UserAccessControl $uac, int $rowIndexRef, Permission $permission, array $data)
    {
        // If the permission is similar to the original, nothing to do.
        $updatedPermissionType = Hash::get($data, 'type');
        if ($permission->type === $updatedPermissionType) {
            return $permission;
        }

        $data['modified_by'] = $uac->userId();

        $patchEntityOptions = ['accessibleFields' => ['type' => true, 'modified_by' => true]];
        $permission = $this->permissionsTable->patchEntity($permission, $data, $patchEntityOptions);
        if ($permission->hasErrors()) {
            $errors = [$rowIndexRef => $permission->getErrors()];
            $this->handleValidationErrors($errors);
        }

        $this->permissionsTable->save($permission);
        if ($permission->hasErrors()) {
            $errors = [$rowIndexRef => $permission->getErrors()];
            $this->handleValidationErrors($errors);
        }

        return $permission;
    }

    /**
     * Assert that the entity has at least one owner.
     *
     * @param string $acoForeignKey The target entity
     * @return void
     * @throws ValidationException If the entity has no owner
     * @throws \Exception If something unexpected occurred
     */
    private function assertAtLeastOneOwnerPermission(string $acoForeignKey)
    {
        $hasOwner = $this->permissionsAcoHasOwnerService->check($acoForeignKey);
        if (!$hasOwner) {
            $errors = ['at_least_one_owner' => 'At least one owner permission must be provided.'];
            $this->handleValidationErrors($errors);
        }
    }
}

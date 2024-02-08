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

namespace App\Service\Permissions;

use App\Error\Exception\CustomValidationException;
use App\Error\Exception\ValidationException;
use App\Model\Dto\EntitiesChangesDto;
use App\Model\Entity\Permission;
use App\Model\Table\PermissionsTable;
use App\Utility\UserAccessControl;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class PermissionsUpdatePermissionsService
{
    /**
     * @var \App\Service\Permissions\PermissionsAcoHasOwnerService
     */
    private $permissionsAcoHasOwnerService;

    /**
     * @var \App\Service\Permissions\PermissionsCreateService
     */
    private $permissionsCreateService;

    /**
     * @var \App\Model\Table\PermissionsTable
     */
    private $permissionsTable;

    /**
     * @param \App\Model\Table\PermissionsTable|null $permissionsTable PermissionsTable instance
     */
    public function __construct(?PermissionsTable $permissionsTable = null)
    {
        $this->permissionsAcoHasOwnerService = new PermissionsAcoHasOwnerService();
        $this->permissionsCreateService = new PermissionsCreateService();
        $this->permissionsTable = $permissionsTable ?? TableRegistry::getTableLocator()->get('Permissions');
    }

    /**
     * Update an entity permissions.
     *
     * @param \App\Utility\UserAccessControl $uac The operator.
     * @param string $aco The type of entity
     * @param string $acoForeignkey The target entity id
     * @param array|null $data The permissions to update
     * @return \App\Model\Dto\EntitiesChangesDto
     * @throws \Exception If something unexpected occurred
     */
    public function updatePermissions(
        UserAccessControl $uac,
        string $aco,
        string $acoForeignkey,
        ?array $data = []
    ): EntitiesChangesDto {
        $entitiesChanges = new EntitiesChangesDto();

        foreach ($data as $rowIndex => $row) {
            $permissionId = Hash::get($row, 'id', null);

            // A new permission is provided when no id is found in the raw data.
            if (is_null($permissionId)) {
                $permission = $this->addPermission($uac, $rowIndex, $aco, $acoForeignkey, $row);
                $entitiesChanges->pushAddedEntity($permission);
            } else {
                // If a property delete is found and set to true, then delete the permission.
                // Otherwise update it.
                $permission = $this->getPermission($rowIndex, $acoForeignkey, $permissionId);
                $delete = Hash::get($row, 'delete');
                if ($delete) {
                    $permission = $this->deletePermission($permission);
                    $entitiesChanges->pushDeletedEntity($permission);
                } else {
                    $permission = $this->updatePermission($uac, $rowIndex, $permission, $row);
                    $entitiesChanges->pushUpdatedEntity($permission);
                }
            }
        }

        $this->assertAtLeastOneOwnerPermission($acoForeignkey);

        return $entitiesChanges;
    }

    /**
     * Add a permission to an entity.
     *
     * @param \App\Utility\UserAccessControl $uac The operator
     * @param int $rowIndexRef The row index in the request data
     * @param string $aco The type of entity
     * @param string $acoForeignkey The target entity id
     * @param array $data The permission data
     * @return \App\Model\Entity\Permission|null
     * @throws \Exception
     */
    private function addPermission(
        UserAccessControl $uac,
        int $rowIndexRef,
        string $aco,
        string $acoForeignkey,
        array $data
    ): ?Permission {
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

        return null;
    }

    /**
     * Handle permissions validation errors.
     *
     * @param array $errors The list of errors
     * @return void
     * @throws \App\Error\Exception\CustomValidationException If the provided data does not validate.
     */
    private function handleValidationErrors(array $errors = []): void
    {
        if (!empty($errors)) {
            $msg = __('Could not validate permissions data.');
            throw new CustomValidationException($msg, $errors, $this->permissionsTable);
        }
    }

    /**
     * Retrieve a permission.
     *
     * @param int $rowIndexRef The row index in the request data
     * @param string $acoForeignkey The target entity id
     * @param string $permissionId The permission identifier to retrieve.
     * @return \App\Model\Entity\Permission|null
     * @throws \App\Error\Exception\ValidationException If the permission does not exist.
     */
    private function getPermission(int $rowIndexRef, string $acoForeignkey, string $permissionId): ?Permission
    {
        /** @var \App\Model\Entity\Permission|null $permission */
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
     * @param \App\Model\Entity\Permission $permission The target permission
     * @return \App\Model\Entity\Permission
     */
    private function deletePermission(Permission $permission): Permission
    {
        $this->permissionsTable->delete($permission);

        return $permission;
    }

    /**
     * Update a permission.
     *
     * @param \App\Utility\UserAccessControl $uac The operator
     * @param int $rowIndexRef The row index in the request data
     * @param \App\Model\Entity\Permission $permission The target permission to update
     * @param array $data The permission data.
     * @return \App\Model\Entity\Permission
     */
    private function updatePermission(
        UserAccessControl $uac,
        int $rowIndexRef,
        Permission $permission,
        array $data
    ): Permission {
        // If the permission is similar to the original, nothing to do.
        $updatedPermissionType = Hash::get($data, 'type');
        if ($permission->type === $updatedPermissionType) {
            return $permission;
        }

        $data['modified_by'] = $uac->getId();

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
     * @throws \App\Error\Exception\ValidationException If the entity has no owner
     * @throws \Exception If something unexpected occurred
     */
    private function assertAtLeastOneOwnerPermission(string $acoForeignKey): void
    {
        $hasOwner = $this->permissionsAcoHasOwnerService->check($acoForeignKey);
        if (!$hasOwner) {
            $errors = ['at_least_one_owner' => 'At least one owner permission must be provided.'];
            $this->handleValidationErrors($errors);
        }
    }
}

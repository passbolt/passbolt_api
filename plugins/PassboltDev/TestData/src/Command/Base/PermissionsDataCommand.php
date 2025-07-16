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
 * @since         2.0.0
 */
namespace Passbolt\TestData\Command\Base;

use App\Utility\UuidFactory;
use Passbolt\TestData\Lib\DataCommand;
use Passbolt\TestData\Lib\PermissionMatrix;

class PermissionsDataCommand extends DataCommand
{
    public $entityName = 'Permissions';

    /**
     * Get the permissions data
     *
     * @return array
     */
    public function getData(): array
    {
        $permissions = [];
        $permissions = array_merge($permissions, $this->getUsersResourcesPermissions());
        $permissions = array_merge($permissions, $this->getGroupsResourcesPermissions());

        return $permissions;
    }

    /**
     * Get the resource user permissions association data
     *
     * @return array
     */
    private function getUsersResourcesPermissions(): array
    {
        $permissions = [];
        $permissionsMatrix = PermissionMatrix::getUsersResourcesPermissions();
        foreach ($permissionsMatrix as $resourceAlias => $usersExpectedPermissions) {
            foreach ($usersExpectedPermissions as $userAlias => $expectedPermissionType) {
                if ($expectedPermissionType == 0) {
                    continue;
                }
                $acoId = UuidFactory::uuid('resource.id.' . $resourceAlias);
                $aroId = UuidFactory::uuid('user.id.' . $userAlias);
                $permissions[] = [
                    'id' => UuidFactory::uuid("permission.id.$acoId-$aroId"),
                    'aco' => 'Resource',
                    'aco_foreign_key' => $acoId,
                    'aro' => 'User',
                    'aro_foreign_key' => $aroId,
                    'type' => $expectedPermissionType,
                    'created_by' => UuidFactory::uuid('user.id.admin'),
                    'modified_by' => UuidFactory::uuid('user.id.admin'),
                ];
            }
        }

        return $permissions;
    }

    /**
     * Get the groups resources association data
     *
     * @return array
     */
    private function getGroupsResourcesPermissions(): array
    {
        $permissions = [];
        $permissionsMatrix = PermissionMatrix::getGroupsResourcesPermissions();
        foreach ($permissionsMatrix as $resourceAlias => $usersExpectedPermissions) {
            foreach ($usersExpectedPermissions as $groupAlias => $expectedPermissionType) {
                if ($expectedPermissionType == 0) {
                    continue;
                }
                $acoId = UuidFactory::uuid('resource.id.' . $resourceAlias);
                $aroId = UuidFactory::uuid('group.id.' . $groupAlias);
                $permissions[] = [
                    'id' => UuidFactory::uuid("permission.id.$acoId-$aroId"),
                    'aco' => 'Resource',
                    'aco_foreign_key' => $acoId,
                    'aro' => 'Group',
                    'aro_foreign_key' => $aroId,
                    'type' => $expectedPermissionType,
                    'created_by' => UuidFactory::uuid('user.id.admin'),
                    'modified_by' => UuidFactory::uuid('user.id.admin'),
                ];
            }
        }

        return $permissions;
    }
}

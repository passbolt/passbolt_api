<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */

namespace PassboltTestData\Shell\Task\Base;

use App\Utility\Common;
use PassboltTestData\Lib\DataTask;
use PassboltTestData\Lib\PermissionMatrix;

class PermissionsDataTask extends DataTask
{
    public $entityName = 'Permissions';

    protected function _getData()
    {
        $permissions = [];
        $permissions = array_merge($permissions, $this->getUsersResourcesPermissions());
        $permissions = array_merge($permissions, $this->getGroupsResourcesPermissions());
        return $permissions;
    }

    private function getUsersResourcesPermissions()
    {
        $permissions = [];
        $permissionsMatrix = PermissionMatrix::getUsersResourcesPermissions();
        foreach ($permissionsMatrix as $resourceAlias => $usersExpectedPermissions) {
            foreach ($usersExpectedPermissions as $userAlias => $expectedPermissionType) {
                if ($expectedPermissionType == 0) {
                    continue;
                }
                $acoId = Common::uuid('resource.id.' . $resourceAlias);
                $aroId = Common::uuid('user.id.' . $userAlias);
                $permissions[] = [
                    'id' => Common::uuid("permission.id.$acoId-$aroId"),
                    'aco' => 'Resource',
                    'aco_foreign_key' => $acoId,
                    'aro' => 'User',
                    'aro_foreign_key' => $aroId,
                    'type' => $expectedPermissionType,
                    'created_by' => Common::uuid('user.id.admin'),
                    'modified_by' => Common::uuid('user.id.admin')
                ];
            }
        }
        return $permissions;
    }

    private function getGroupsResourcesPermissions()
    {
        $permissions = [];
        $permissionsMatrix = PermissionMatrix::getGroupsResourcesPermissions();
        foreach ($permissionsMatrix as $resourceAlias => $usersExpectedPermissions) {
            foreach ($usersExpectedPermissions as $groupAlias => $expectedPermissionType) {
                if ($expectedPermissionType == 0) {
                    continue;
                }
                $acoId = Common::uuid('resource.id.' . $resourceAlias);
                $aroId = Common::uuid('group.id.' . $groupAlias);
                $permissions[] = [
                    'id' => Common::uuid("permission.id.$acoId-$aroId"),
                    'aco' => 'Resource',
                    'aco_foreign_key' => $acoId,
                    'aro' => 'Group',
                    'aro_foreign_key' => $aroId,
                    'type' => $expectedPermissionType,
                    'created_by' => Common::uuid('user.id.admin'),
                    'modified_by' => Common::uuid('user.id.admin')
                ];
            }
        }
        return $permissions;
    }
}

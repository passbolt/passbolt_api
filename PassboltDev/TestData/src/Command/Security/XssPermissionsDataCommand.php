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
 * @since         2.0.0
 */
namespace PassboltTestData\Command\Security;

use App\Model\Entity\Permission;
use App\Utility\UuidFactory;
use PassboltTestData\Command\Base\PermissionsDataCommand;

class XssPermissionsDataCommand extends PermissionsDataCommand
{
    protected $_truncate = false;

    /**
     * Get the permissions data
     *
     * @return array
     */
    public function getData()
    {
        $resourcesTask = new XssResourcesDataCommand();
        $resources = $resourcesTask->getData();

        // All the Xss resources as accessible by all the Xss users
        $usersTask = new XssUsersDataCommand();
        $users = $usersTask->getData();
        foreach ($users as $user) {
            foreach ($resources as $resource) {
                $acoId = $resource['id'];
                $aroId = $user['id'];
                $permissions[] = [
                    'id' => UuidFactory::uuid("permission.id.$acoId-$aroId"),
                    'aco' => 'Resource',
                    'aco_foreign_key' => $acoId,
                    'aro' => 'User',
                    'aro_foreign_key' => $aroId,
                    'type' => Permission::OWNER,
                    'created_by' => UuidFactory::uuid('user.id.admin'),
                    'modified_by' => UuidFactory::uuid('user.id.admin')
                ];
            }
        }

        // All the Xss resources as accessible by all the Xss groups
        $groupsTask = new XssGroupsDataCommand();
        $groups = $groupsTask->getData();
        foreach ($groups as $group) {
            foreach ($resources as $resource) {
                $acoId = $resource['id'];
                $aroId = $group['id'];
                $permissions[] = [
                    'id' => UuidFactory::uuid("permission.id.$acoId-$aroId"),
                    'aco' => 'Resource',
                    'aco_foreign_key' => $acoId,
                    'aro' => 'Group',
                    'aro_foreign_key' => $aroId,
                    'type' => Permission::OWNER,
                    'created_by' => UuidFactory::uuid('user.id.admin'),
                    'modified_by' => UuidFactory::uuid('user.id.admin')
                ];
            }
        }

        return $permissions;
    }
}

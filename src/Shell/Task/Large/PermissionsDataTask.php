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
namespace PassboltTestData\Shell\Task\Large;

use App\Model\Entity\Role;
use App\Utility\UuidFactory;
use PassboltTestData\Lib\DataTask;
use PassboltTestData\Lib\PermissionMatrix;
use Cake\Core\Configure;

class PermissionsDataTask extends DataTask
{
    public $entityName = 'Permissions';

    /**
     * Get the permissions data
     *
     * @return array
     */
    public function getData()
    {
        $permissions = [];
        $permissions = array_merge($permissions, $this->getPermissionsScenarioForEachUser());
        $permissions = array_merge($permissions, $this->getPermissionsScenarioForGroupAllUsers());

        return $permissions;
    }

    private function getPermissionsScenarioForEachUser()
    {
        $this->loadModel('Users');
        $this->loadModel('Resources');
        $permissions = [];

        $max = Configure::read('PassboltTestData.scenarios.large.install.count.resources_foreach_user');
        $users = $this->Users->findIndex(Role::USER);
        foreach ($users as $user) {
            for ($i = 0; $i < $max; $i++) {
                $aroId = $user->id;
                $acoId = UuidFactory::uuid("resource.id.resource_{$i}_for_each_user_{$aroId}");
                $permissions[] = [
                    'id' => UuidFactory::uuid("permission.id.$acoId-$aroId"),
                    'aco' => 'Resource',
                    'aco_foreign_key' => $acoId,
                    'aro' => 'User',
                    'aro_foreign_key' => $aroId,
                    'type' => 15,
                    'created' => date('Y-m-d H:i:s'),
                    'modified' => date('Y-m-d H:i:s'),
                    'created_by' => $aroId,
                    'modified_by' => $aroId
                ];
            }
        }

        return $permissions;
    }

    private function getPermissionsScenarioForGroupAllUsers()
    {
        $this->loadModel('Users');
        $this->loadModel('Resources');
        $permissions = [];

        $max = Configure::read('PassboltTestData.scenarios.large.install.count.resources_for_group_all_users');
        for ($i = 0; $i < $max; $i++) {
            $acoId = UuidFactory::uuid("resource.id.resource_{$i}_group_all_users");
            $aroId = UuidFactory::uuid('group.id.all_users');
            $permissions[] = [
                'id' => UuidFactory::uuid("permission.id.$acoId-$aroId"),
                'aco' => 'Resource',
                'aco_foreign_key' => $acoId,
                'aro' => 'Group',
                'aro_foreign_key' => $aroId,
                'type' => 15,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
                'created_by' => $aroId,
                'modified_by' => $aroId
            ];
        }

        return $permissions;
    }
}

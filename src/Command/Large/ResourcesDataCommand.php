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
namespace PassboltTestData\Command\Large;

use App\Model\Entity\Role;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use PassboltTestData\Lib\DataCommand;

class ResourcesDataCommand extends DataCommand
{
    public $entityName = 'Resources';

    /**
     * Get the resources data
     *
     * @return array
     */
    public function getData()
    {
        $resources = [];
        $resources = array_merge($resources, $this->getResourcesScenarioForEachUser());
        $resources = array_merge($resources, $this->getResourcesScenarioForGroupAllUsers());

        return $resources;
    }

    public function getResourcesScenarioForEachUser()
    {
        $this->loadModel('Users');

        // Resources by person
        $max = Configure::read('PassboltTestData.scenarios.large.install.count.resources_foreach_user');
        $users = $this->Users->findIndex(Role::USER);
        foreach ($users as $user) {
            for ($i = 0; $i < $max; $i++) {
                $userId = $user->id;
                $username = $user->username;
                $resources[] = [
                    'id' => UuidFactory::uuid("resource.id.resource_{$i}_for_each_user_{$userId}"),
                    'name' => "Resource for $username $i",
                    'username' => "username_$i",
                    'uri' => 'http://www.passbolt.com/',
                    'description' => 'The password manager your team was waiting for.',
                    'deleted' => 0,
                    'created_by' => $userId,
                    'modified_by' => $userId,
                    'created' => date('Y-m-d H:i:s', strtotime('-2 days')),
                    'modified' => date('Y-m-d H:i:s', strtotime('-1 days')),
                    'resource_type_id' => UuidFactory::uuid('resource-types.id.password-string')
                ];
            }
        }

        return $resources;
    }

    public function getResourcesScenarioForGroupAllUsers()
    {
        $max = Configure::read('PassboltTestData.scenarios.large.install.count.resources_for_group_all_users');
        for ($i = 0; $i < $max; $i++) {
            $resources[] = [
                'id' => UuidFactory::uuid("resource.id.resource_{$i}_group_all_users"),
                'name' => "Resource $i",
                'username' => "username_$i",
                'uri' => 'http://www.passbolt.com/',
                'description' => 'The password manager your team was waiting for.',
                'deleted' => 0,
                'created_by' => UuidFactory::uuid('user.id.user_1'),
                'modified_by' => UuidFactory::uuid('user.id.user_1'),
                'created' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'modified' => date('Y-m-d H:i:s', strtotime('-1 days')),
                'resource_type_id' => UuidFactory::uuid('resource-types.id.password-string')
            ];
        }

        return $resources;
    }
}

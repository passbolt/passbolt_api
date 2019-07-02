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
namespace PassboltTestData\Shell\Task\Base;

use App\Utility\UuidFactory;
use PassboltTestData\Lib\DataTask;

class GroupsUsersDataTask extends DataTask
{
    public $entityName = 'GroupsUsers';

    /**
     * Get groups users settings
     *
     * @return array
     */
    protected function getGroupsUsersSettings()
    {
        return [
            'accounting' => [
                'managers' => ['frances'],
                'users' => ['frances', 'grace']
            ],
            'board' => [
                'managers' => ['hedy'],
                'users' => ['hedy']
            ],
            'creative' => [
                'managers' => ['irene'],
                'users' => ['irene']
            ],
            'developer' => [
                'managers' => ['irene'],
                'users' => ['irene']
            ],
            'ergonom' => [
                'managers' => ['irene'],
                'users' => ['irene']
            ],
            'freelancer' => [
                'managers' => ['jean'],
                'users' => ['jean', 'kathleen', 'lynne', 'marlyn', 'nancy']
            ],
            'human_resource' => [
                'managers' => ['ping', 'thelma'],
                'users' => ['ping', 'thelma', 'ursula', 'wang']
            ],
            'it_support' => [
                'managers' => ['ping', 'ursula'],
                'users' => ['ping', 'thelma', 'ursula', 'wang']
            ],
            'leadership_team' => [
                'managers' => ['admin'],
                'users' => ['admin']
            ],
            'management' => [
                'managers' => ['admin'],
                'users' => ['admin']
            ],
            'marketing' => [
                'managers' => ['admin'],
                'users' => ['admin']
            ],
            'network' => [
                'managers' => ['admin'],
                'users' => ['admin']
            ],
            'operations' => [
                'managers' => ['admin'],
                'users' => ['admin']
            ],
            'procurement' => [
                'managers' => ['admin'],
                'users' => ['admin']
            ],
            'quality_assurance' => [
                'managers' => ['admin'],
                'users' => ['admin']
            ],
            'resource_planning' => [
                'managers' => ['admin'],
                'users' => ['admin']
            ],
            'sales' => [
                'managers' => ['admin'],
                'users' => ['admin']
            ],
            'traffic' => [
                'managers' => ['admin'],
                'users' => ['admin']
            ],
        ];
    }

    /**
     * Get the groups users association data
     *
     * @return array
     */
    public function getData()
    {
        $groupsUsers = [];
        $settings = $this->getGroupsUsersSettings();

        foreach ($settings as $groupAlias => $groupSettings) {
            // managers
            foreach ($groupSettings['managers'] as $managerAlias) {
                $groupsUsers[] = [
                    'id' => UuidFactory::uuid("group_user.id.$groupAlias-$managerAlias"),
                    'group_id' => UuidFactory::uuid("group.id.$groupAlias"),
                    'user_id' => UuidFactory::uuid("user.id.$managerAlias"),
                    'is_admin' => 1,
                    'created_by' => UuidFactory::uuid('user.id.admin'),
                    'modified_by' => UuidFactory::uuid('user.id.admin')
                ];
            }
            // members
            foreach ($groupSettings['users'] as $userAlias) {
                if (in_array($userAlias, $groupSettings['managers'])) {
                    continue;
                }
                $groupsUsers[] = [
                    'id' => UuidFactory::uuid("group_user.id.$groupAlias-$userAlias"),
                    'group_id' => UuidFactory::uuid("group.id.$groupAlias"),
                    'user_id' => UuidFactory::uuid("user.id.$userAlias"),
                    'is_admin' => 0,
                    'created_by' => UuidFactory::uuid('user.id.admin'),
                    'modified_by' => UuidFactory::uuid('user.id.admin')
                ];
            }
        }

        return $groupsUsers;
    }
}

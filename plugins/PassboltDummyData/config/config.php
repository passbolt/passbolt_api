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

return [
    'PassboltDummyData' => [
        'scenarios' => [
            'default' => [
                'install' => [
                    'shellTasks' => [
                        'PassboltData.RolesData',
                        'PassboltDummyData.UsersData',
                        'PassboltDummyData.ProfilesData',
                        'PassboltDummyData.GroupsData',
                    ]
                ],
                'fixturize' => [
                    'shellTasks' => [
                        'PassboltData.RolesData',
                        'PassboltDummyData.UsersData',
                        'PassboltDummyData.ProfilesData',
                        'PassboltDummyData.GroupsData',
                    ]
                ]
            ],
            'large' => [
                'install' => [
                    'count' => 300,
                    'shellTasks' => [
                        'PassboltData.RolesData',
                        'PassboltDummyData.LargeUsersData',
                    ]
                ],
                'fixturize' => [
                    'shellTasks' => [
                        'PassboltDummyData.LargeUsersData',
                    ]
                ]
            ],
            'security' => [
                'install' => [
                    'shellTasks' => [
                    ]
                ]
            ]
        ]
    ]
];

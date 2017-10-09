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
    'PassboltTestData' => [
        'scenarios' => [
            'default' => [
                'install' => [
                    'shellTasks' => [
                        'PassboltTestData.Base/RolesData',
                        'PassboltTestData.Base/UsersData',
                        'PassboltTestData.Base/ProfilesData',
                        'PassboltTestData.Base/GpgkeysData',
                        'PassboltTestData.Base/GroupsData',
                        'PassboltTestData.Base/GroupsUsersData',
                        'PassboltTestData.Base/ResourcesData',
                        'PassboltTestData.Base/FavoritesData',
                        'PassboltTestData.Base/PermissionsData',
                        'PassboltTestData.Base/SecretsData',
                    ]
                ],
                'fixturize' => [
                    'shellTasks' => [
                        'PassboltTestData.Base/RolesData',
                        'PassboltTestData.Base/UsersData',
                        'PassboltTestData.Base/ProfilesData',
                        'PassboltTestData.Base/GpgkeysData',
                        'PassboltTestData.Base/GroupsData',
                        'PassboltTestData.Base/GroupsUsersData',
                        'PassboltTestData.Base/ResourcesData',
                        'PassboltTestData.Base/FavoritesData',
                        'PassboltTestData.Base/PermissionsData',
                        'PassboltTestData.Base/SecretsData',
                    ]
                ]
            ],
            'large' => [
                'install' => [
                    'count' => 300,
                    'shellTasks' => [
                        'PassboltTestData.Base/RolesData',
                        'PassboltTestData.Large/UsersData',
                    ]
                ],
                'fixturize' => [
                    'shellTasks' => [
                        'PassboltTestData.Large/UsersData',
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

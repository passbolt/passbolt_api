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
                        'PassboltTestData.Base/AuthenticationTokensData',
                        'PassboltTestData.Base/RolesData',
                        'PassboltTestData.Base/UsersData',
                        'PassboltTestData.Security/XssUsersData',
                        'PassboltTestData.Base/ProfilesData',
                        'PassboltTestData.Security/XssProfilesData',
                        'PassboltTestData.Base/AvatarsData',
                        'PassboltTestData.Base/GpgkeysData',
                        'PassboltTestData.Base/GroupsData',
                        'PassboltTestData.Security/XssGroupsData',
                        'PassboltTestData.Base/GroupsUsersData',
                        'PassboltTestData.Security/XssGroupsUsersData',
                        'PassboltTestData.Base/ResourcesData',
                        'PassboltTestData.Security/XssResourcesData',
                        'PassboltTestData.Base/PermissionsData',
                        'PassboltTestData.Security/XssPermissionsData',
                        'PassboltTestData.Base/FavoritesData',
                        'PassboltTestData.Base/CommentsData',
                        'PassboltTestData.Security/XssCommentsData',
                        'PassboltTestData.Base/SecretsData',
                        'PassboltTestData.Base/EmailQueueData',
                    ]
                ],
                'fixturize' => [
                    'shellTasks' => [
                        'PassboltTestData.Base/AuthenticationTokensData',
                        'PassboltTestData.Base/RolesData',
                        'PassboltTestData.Base/UsersData',
                        'PassboltTestData.Base/ProfilesData',
                        'PassboltTestData.Base/AvatarsData',
                        'PassboltTestData.Base/GpgkeysData',
                        'PassboltTestData.Base/GroupsData',
                        'PassboltTestData.Base/GroupsUsersData',
                        'PassboltTestData.Base/ResourcesData',
                        'PassboltTestData.Base/PermissionsData',
                        'PassboltTestData.Base/FavoritesData',
                        'PassboltTestData.Base/CommentsData',
                        'PassboltTestData.Base/SecretsData',
                        'PassboltTestData.Base/EmailQueueData',
                    ]
                ]
            ],
            'alt0' => [
                'fixturize' => [
                    'shellTasks' => [
                        'PassboltTestData.Alt0/GroupsUsersData',
                        'PassboltTestData.Alt0/PermissionsData',
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
            ]
        ]
    ]
];

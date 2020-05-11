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

return [
    'PassboltTestData' => [
        /*
         * Save strategy can improve significantly the performance of the import.
         * - default: Save the entity one by one using the cake table model
         * - saveMany: Save the entities by batch
         * - sqlInfile: Save the entities using the sql LOAD DATA INFILE primitive.
         *   This strategy can significantly improve the performance.
         *   This strategy requires the mysql user to have some privilegies to access the LOAD DATA INFILE primitive.
         */
        'saveStrategy' => 'default',
        'scenarios' => [
            'default' => [
                'install' => [
                    'shellTasks' => [
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
                        'PassboltTestData.Pro/AccountSettingsData',
                        'PassboltTestData.Pro/TagsData',
                        'PassboltTestData.Pro/ResourcesTagsData',
                        'PassboltTestData.Pro/PermissionsData',
                        'PassboltTestData.Pro/FoldersData',
                        'PassboltTestData.Pro/FoldersRelationsData',
                    ]
                ],
                'fixturize' => [
                    'shellTasks' => [
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
                'install' => [
                    'shellTasks' => [
                        'PassboltTestData.Base/GroupsData',
                        'PassboltTestData.Base/UsersData',
                        'PassboltTestData.Base/ProfilesData',
                        'PassboltTestData.Base/GpgkeysData',
                        'PassboltTestData.Base/RolesData',
                        'PassboltTestData.Alt0/GroupsUsersData',
                        'PassboltTestData.Alt0/PermissionsData',
                        'PassboltTestData.Base/ResourcesData',
                        'PassboltTestData.Alt0/SecretsData',
                    ]
                ],
                'fixturize' => [
                    'shellTasks' => [
                        'PassboltTestData.Base/GroupsData',
                        'PassboltTestData.Base/UsersData',
                        'PassboltTestData.Base/ProfilesData',
                        'PassboltTestData.Base/GpgkeysData',
                        'PassboltTestData.Base/RolesData',
                        'PassboltTestData.Alt0/GroupsUsersData',
                        'PassboltTestData.Alt0/PermissionsData',
                        'PassboltTestData.Base/ResourcesData',
                        'PassboltTestData.Alt0/SecretsData',
                    ]
                ]
            ],
            'large' => [
                'install' => [
                    'count' => [
                        'chunk_size' => 1000,
                        'users' => 50,
                        'groups' => 100,
                        'resources_for_group_all_users' => 500,
                        'resources_foreach_user' => 50,
                        'tags_personal' => 1,
                        'tags_shared' => 2
                    ],
                    'shellTasks' => [
                        'PassboltTestData.Base/RolesData',
                        'PassboltTestData.Large/UsersData',
                        'PassboltTestData.Large/ProfilesData',
                        'PassboltTestData.Base/AvatarsData',
                        'PassboltTestData.Base/GpgkeysData',
                        'PassboltTestData.Large/GroupsData',
                        'PassboltTestData.Large/GroupsUsersData',
                        'PassboltTestData.Large/ResourcesData',
                        'PassboltTestData.Large/PermissionsData',
                        'PassboltTestData.Large/FavoritesData',
                        'PassboltTestData.Large/CommentsData',
                        'PassboltTestData.Large/SecretsData',
                        'PassboltTestData.Large/TagsData',
                        'PassboltTestData.Large/ResourcesTagsData'
                    ]
                ]
            ]
        ]
    ]
];

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
                        \PassboltTestData\Command\Base\RolesDataCommand::class,
                        \PassboltTestData\Command\Base\ResourceTypesDataCommand::class,
                        \PassboltTestData\Command\Base\UsersDataCommand::class,
                        \PassboltTestData\Command\Security\XssUsersDataCommand::class,
                        \PassboltTestData\Command\Base\ProfilesDataCommand::class,
                        \PassboltTestData\Command\Security\XssProfilesDataCommand::class,
                        \PassboltTestData\Command\Base\AvatarsDataCommand::class,
                        \PassboltTestData\Command\Base\GpgkeysDataCommand::class,
                        \PassboltTestData\Command\Base\GroupsDataCommand::class,
                        \PassboltTestData\Command\Security\XssGroupsDataCommand::class,
                        \PassboltTestData\Command\Base\GroupsUsersDataCommand::class,
                        \PassboltTestData\Command\Security\XssGroupsUsersDataCommand::class,
                        \PassboltTestData\Command\Base\ResourcesDataCommand::class,
                        \PassboltTestData\Command\Security\XssResourcesDataCommand::class,
                        \PassboltTestData\Command\Base\PermissionsDataCommand::class,
                        \PassboltTestData\Command\Security\XssPermissionsDataCommand::class,
                        \PassboltTestData\Command\Base\FavoritesDataCommand::class,
                        \PassboltTestData\Command\Base\CommentsDataCommand::class,
                        \PassboltTestData\Command\Security\XssCommentsDataCommand::class,
                        \PassboltTestData\Command\Base\SecretsDataCommand::class,
                        \PassboltTestData\Command\Base\EmailQueueDataCommand::class,
                        \PassboltTestData\Command\Pro\AccountSettingsDataCommand::class,
                        \PassboltTestData\Command\Pro\TagsDataCommand::class,
                        \PassboltTestData\Command\Pro\ResourcesTagsDataCommand::class,
                        \PassboltTestData\Command\Pro\PermissionsDataCommand::class,
                        \PassboltTestData\Command\Pro\FoldersDataCommand::class,
                        \PassboltTestData\Command\Pro\FoldersRelationsDataCommand::class,
                    ]
                ],
                'fixturize' => [
                    'shellTasks' => [
                        \PassboltTestData\Command\Base\RolesDataCommand::class,
                        \PassboltTestData\Command\Base\UsersDataCommand::class,
                        \PassboltTestData\Command\Base\ProfilesDataCommand::class,
                        \PassboltTestData\Command\Base\AvatarsDataCommand::class,
                        \PassboltTestData\Command\Base\GpgkeysDataCommand::class,
                        \PassboltTestData\Command\Base\GroupsDataCommand::class,
                        \PassboltTestData\Command\Base\GroupsUsersDataCommand::class,
                        \PassboltTestData\Command\Base\ResourcesDataCommand::class,
                        \PassboltTestData\Command\Base\PermissionsDataCommand::class,
                        \PassboltTestData\Command\Base\FavoritesDataCommand::class,
                        \PassboltTestData\Command\Base\CommentsDataCommand::class,
                        \PassboltTestData\Command\Base\SecretsDataCommand::class,
                        \PassboltTestData\Command\Base\EmailQueueDataCommand::class,
                    ]
                ]
            ],
            'alt0' => [
                'install' => [
                    'shellTasks' => [
                        \PassboltTestData\Command\Base\ResourceTypesDataCommand::class,
                        \PassboltTestData\Command\Base\GroupsDataCommand::class,
                        \PassboltTestData\Command\Base\UsersDataCommand::class,
                        \PassboltTestData\Command\Base\ProfilesDataCommand::class,
                        \PassboltTestData\Command\Base\GpgkeysDataCommand::class,
                        \PassboltTestData\Command\Base\RolesDataCommand::class,
                        \PassboltTestData\Command\Alt0\GroupsUsersDataCommand::class,
                        \PassboltTestData\Command\Alt0\PermissionsDataCommand::class,
                        \PassboltTestData\Command\Base\ResourcesDataCommand::class,
                        \PassboltTestData\Command\Alt0\SecretsDataCommand::class,
                    ]
                ],
                'fixturize' => [
                    'shellTasks' => [
                        \PassboltTestData\Command\Base\GroupsDataCommand::class,
                        \PassboltTestData\Command\Base\UsersDataCommand::class,
                        \PassboltTestData\Command\Base\ProfilesDataCommand::class,
                        \PassboltTestData\Command\Base\GpgkeysDataCommand::class,
                        \PassboltTestData\Command\Base\RolesDataCommand::class,
                        \PassboltTestData\Command\Alt0\GroupsUsersDataCommand::class,
                        \PassboltTestData\Command\Alt0\PermissionsDataCommand::class,
                        \PassboltTestData\Command\Base\ResourcesDataCommand::class,
                        \PassboltTestData\Command\Alt0\SecretsDataCommand::class,
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
                        \PassboltTestData\Command\Base\ResourceTypesDataCommand::class,
                        \PassboltTestData\Command\Base\RolesDataCommand::class,
                        \PassboltTestData\Command\Large\UsersDataCommand::class,
                        \PassboltTestData\Command\Large\ProfilesDataCommand::class,
                        \PassboltTestData\Command\Base\AvatarsDataCommand::class,
                        \PassboltTestData\Command\Base\GpgkeysDataCommand::class,
                        \PassboltTestData\Command\Large\GroupsDataCommand::class,
                        \PassboltTestData\Command\Large\GroupsUsersDataCommand::class,
                        \PassboltTestData\Command\Large\ResourcesDataCommand::class,
                        \PassboltTestData\Command\Large\PermissionsDataCommand::class,
                        \PassboltTestData\Command\Large\FavoritesDataCommand::class,
                        \PassboltTestData\Command\Large\CommentsDataCommand::class,
                        \PassboltTestData\Command\Large\SecretsDataCommand::class,
                        \PassboltTestData\Command\Large\TagsDataCommand::class,
                        \PassboltTestData\Command\Large\ResourcesTagsDataCommand::class,
                    ]
                ]
            ]
        ]
    ]
];

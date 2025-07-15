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
    'passbolt' => [
        'plugins' => [
            'testData' => [
                'version' => '1.0.0',
            ],
        ],
    ],
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
                        \Passbolt\TestData\Command\Base\RolesDataCommand::class,
                        \Passbolt\TestData\Command\Base\ResourceTypesDataCommand::class,
                        \Passbolt\TestData\Command\Base\UsersDataCommand::class,
                        \Passbolt\TestData\Command\Security\XssUsersDataCommand::class,
                        \Passbolt\TestData\Command\Base\ProfilesDataCommand::class,
                        \Passbolt\TestData\Command\Security\XssProfilesDataCommand::class,
                        \Passbolt\TestData\Command\Base\AvatarsDataCommand::class,
                        \Passbolt\TestData\Command\Base\GpgkeysDataCommand::class,
                        \Passbolt\TestData\Command\Base\GroupsDataCommand::class,
                        \Passbolt\TestData\Command\Security\XssGroupsDataCommand::class,
                        \Passbolt\TestData\Command\Base\GroupsUsersDataCommand::class,
                        \Passbolt\TestData\Command\Security\XssGroupsUsersDataCommand::class,
                        \Passbolt\TestData\Command\Base\ResourcesDataCommand::class,
                        \Passbolt\TestData\Command\Security\XssResourcesDataCommand::class,
                        \Passbolt\TestData\Command\Base\PermissionsDataCommand::class,
                        \Passbolt\TestData\Command\Security\XssPermissionsDataCommand::class,
                        \Passbolt\TestData\Command\Base\FavoritesDataCommand::class,
                        \Passbolt\TestData\Command\Base\CommentsDataCommand::class,
                        \Passbolt\TestData\Command\Security\XssCommentsDataCommand::class,
                        \Passbolt\TestData\Command\Base\SecretsDataCommand::class,
                        \Passbolt\TestData\Command\Base\EmailQueueDataCommand::class,
                        \Passbolt\TestData\Command\Pro\AccountSettingsDataCommand::class,
                        \Passbolt\TestData\Command\Pro\TagsDataCommand::class,
                        \Passbolt\TestData\Command\Pro\ResourcesTagsDataCommand::class,
                        \Passbolt\TestData\Command\Base\FoldersPermissionsDataCommand::class,
                        \Passbolt\TestData\Command\Base\FoldersDataCommand::class,
                        \Passbolt\TestData\Command\Base\FoldersRelationsDataCommand::class,
                    ]
                ],
                'fixturize' => [
                    'shellTasks' => [
                        \Passbolt\TestData\Command\Base\RolesDataCommand::class,
                        \Passbolt\TestData\Command\Base\UsersDataCommand::class,
                        \Passbolt\TestData\Command\Base\ProfilesDataCommand::class,
                        \Passbolt\TestData\Command\Base\AvatarsDataCommand::class,
                        \Passbolt\TestData\Command\Base\GpgkeysDataCommand::class,
                        \Passbolt\TestData\Command\Base\GroupsDataCommand::class,
                        \Passbolt\TestData\Command\Base\GroupsUsersDataCommand::class,
                        \Passbolt\TestData\Command\Base\ResourcesDataCommand::class,
                        \Passbolt\TestData\Command\Base\PermissionsDataCommand::class,
                        \Passbolt\TestData\Command\Base\FavoritesDataCommand::class,
                        \Passbolt\TestData\Command\Base\CommentsDataCommand::class,
                        \Passbolt\TestData\Command\Base\SecretsDataCommand::class,
                        \Passbolt\TestData\Command\Base\EmailQueueDataCommand::class,
                    ]
                ]
            ],
            'alt0' => [
                'install' => [
                    'shellTasks' => [
                        \Passbolt\TestData\Command\Base\ResourceTypesDataCommand::class,
                        \Passbolt\TestData\Command\Base\GroupsDataCommand::class,
                        \Passbolt\TestData\Command\Base\UsersDataCommand::class,
                        \Passbolt\TestData\Command\Base\ProfilesDataCommand::class,
                        \Passbolt\TestData\Command\Base\GpgkeysDataCommand::class,
                        \Passbolt\TestData\Command\Base\RolesDataCommand::class,
                        \Passbolt\TestData\Command\Alt0\GroupsUsersDataCommand::class,
                        \Passbolt\TestData\Command\Alt0\PermissionsDataCommand::class,
                        \Passbolt\TestData\Command\Base\ResourcesDataCommand::class,
                        \Passbolt\TestData\Command\Alt0\SecretsDataCommand::class,
                    ]
                ],
                'fixturize' => [
                    'shellTasks' => [
                        \Passbolt\TestData\Command\Base\GroupsDataCommand::class,
                        \Passbolt\TestData\Command\Base\UsersDataCommand::class,
                        \Passbolt\TestData\Command\Base\ProfilesDataCommand::class,
                        \Passbolt\TestData\Command\Base\GpgkeysDataCommand::class,
                        \Passbolt\TestData\Command\Base\RolesDataCommand::class,
                        \Passbolt\TestData\Command\Alt0\GroupsUsersDataCommand::class,
                        \Passbolt\TestData\Command\Alt0\PermissionsDataCommand::class,
                        \Passbolt\TestData\Command\Base\ResourcesDataCommand::class,
                        \Passbolt\TestData\Command\Alt0\SecretsDataCommand::class,
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
                        \Passbolt\TestData\Command\Base\ResourceTypesDataCommand::class,
                        \Passbolt\TestData\Command\Base\RolesDataCommand::class,
                        \Passbolt\TestData\Command\Large\UsersDataCommand::class,
                        \Passbolt\TestData\Command\Large\ProfilesDataCommand::class,
                        \Passbolt\TestData\Command\Base\AvatarsDataCommand::class,
                        \Passbolt\TestData\Command\Base\GpgkeysDataCommand::class,
                        \Passbolt\TestData\Command\Large\GroupsDataCommand::class,
                        \Passbolt\TestData\Command\Large\GroupsUsersDataCommand::class,
                        \Passbolt\TestData\Command\Large\ResourcesDataCommand::class,
                        \Passbolt\TestData\Command\Large\PermissionsDataCommand::class,
                        \Passbolt\TestData\Command\Large\FavoritesDataCommand::class,
                        \Passbolt\TestData\Command\Large\CommentsDataCommand::class,
                        \Passbolt\TestData\Command\Large\SecretsDataCommand::class,
                        \Passbolt\TestData\Command\Large\TagsDataCommand::class,
                        \Passbolt\TestData\Command\Large\ResourcesTagsDataCommand::class,
                    ]
                ]
            ]
        ]
    ]
];

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
use Passbolt\TestData\Command\Base\AvatarsDataCommand;
use Passbolt\TestData\Command\Base\CommentsDataCommand;
use Passbolt\TestData\Command\Base\EmailQueueDataCommand;
use Passbolt\TestData\Command\Base\FavoritesDataCommand;
use Passbolt\TestData\Command\Base\FoldersDataCommand;
use Passbolt\TestData\Command\Base\FoldersPermissionsDataCommand;
use Passbolt\TestData\Command\Base\FoldersRelationsDataCommand;
use Passbolt\TestData\Command\Base\GpgkeysDataCommand;
use Passbolt\TestData\Command\Base\GroupsDataCommand;
use Passbolt\TestData\Command\Base\GroupsUsersDataCommand;
use Passbolt\TestData\Command\Base\PermissionsDataCommand;
use Passbolt\TestData\Command\Base\ProfilesDataCommand;
use Passbolt\TestData\Command\Base\ResourcesDataCommand;
use Passbolt\TestData\Command\Base\ResourceTypesDataCommand;
use Passbolt\TestData\Command\Base\RolesDataCommand;
use Passbolt\TestData\Command\Base\SecretsDataCommand;
use Passbolt\TestData\Command\Base\UsersDataCommand as BaseUsersDataCommand;
use Passbolt\TestData\Command\Pro\AccountSettingsDataCommand;
use Passbolt\TestData\Command\Pro\ResourcesTagsDataCommand;
use Passbolt\TestData\Command\Pro\TagsDataCommand;
use Passbolt\TestData\Command\Security\XssCommentsDataCommand;
use Passbolt\TestData\Command\Security\XssGroupsDataCommand;
use Passbolt\TestData\Command\Security\XssGroupsUsersDataCommand;
use Passbolt\TestData\Command\Security\XssPermissionsDataCommand;
use Passbolt\TestData\Command\Security\XssProfilesDataCommand;
use Passbolt\TestData\Command\Security\XssResourcesDataCommand;
use Passbolt\TestData\Command\Security\XssUsersDataCommand;
use Passbolt\TestData\Command\Large\UsersDataCommand as LargeUsersDataCommand;

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
                        RolesDataCommand::class,
                        ResourceTypesDataCommand::class,
                        BaseUsersDataCommand::class,
                        XssUsersDataCommand::class,
                        ProfilesDataCommand::class,
                        XssProfilesDataCommand::class,
                        AvatarsDataCommand::class,
                        GpgkeysDataCommand::class,
                        GroupsDataCommand::class,
                        XssGroupsDataCommand::class,
                        GroupsUsersDataCommand::class,
                        XssGroupsUsersDataCommand::class,
                        ResourcesDataCommand::class,
                        XssResourcesDataCommand::class,
                        PermissionsDataCommand::class,
                        XssPermissionsDataCommand::class,
                        FavoritesDataCommand::class,
                        CommentsDataCommand::class,
                        XssCommentsDataCommand::class,
                        SecretsDataCommand::class,
                        EmailQueueDataCommand::class,
                        AccountSettingsDataCommand::class,
                        TagsDataCommand::class,
                        ResourcesTagsDataCommand::class,
                        FoldersPermissionsDataCommand::class,
                        FoldersDataCommand::class,
                        FoldersRelationsDataCommand::class,
                    ],
                ],
                'fixturize' => [
                    'shellTasks' => [
                        RolesDataCommand::class,
                        UsersDataCommand::class,
                        ProfilesDataCommand::class,
                        AvatarsDataCommand::class,
                        GpgkeysDataCommand::class,
                        GroupsDataCommand::class,
                        GroupsUsersDataCommand::class,
                        ResourcesDataCommand::class,
                        PermissionsDataCommand::class,
                        FavoritesDataCommand::class,
                        CommentsDataCommand::class,
                        SecretsDataCommand::class,
                        EmailQueueDataCommand::class,
                    ],
                ],
            ],
            'alt0' => [
                'install' => [
                    'shellTasks' => [
                        ResourceTypesDataCommand::class,
                        GroupsDataCommand::class,
                        UsersDataCommand::class,
                        ProfilesDataCommand::class,
                        GpgkeysDataCommand::class,
                        RolesDataCommand::class,
                        \Passbolt\TestData\Command\Alt0\GroupsUsersDataCommand::class,
                        \Passbolt\TestData\Command\Alt0\PermissionsDataCommand::class,
                        ResourcesDataCommand::class,
                        \Passbolt\TestData\Command\Alt0\SecretsDataCommand::class,
                    ],
                ],
                'fixturize' => [
                    'shellTasks' => [
                        GroupsDataCommand::class,
                        UsersDataCommand::class,
                        ProfilesDataCommand::class,
                        GpgkeysDataCommand::class,
                        RolesDataCommand::class,
                        \Passbolt\TestData\Command\Alt0\GroupsUsersDataCommand::class,
                        \Passbolt\TestData\Command\Alt0\PermissionsDataCommand::class,
                        ResourcesDataCommand::class,
                        \Passbolt\TestData\Command\Alt0\SecretsDataCommand::class,
                    ],
                ],
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
                        'tags_shared' => 2,
                    ],
                    'shellTasks' => [
                        ResourceTypesDataCommand::class,
                        RolesDataCommand::class,
                        LargeUsersDataCommand::class,
                        \Passbolt\TestData\Command\Large\ProfilesDataCommand::class,
                        AvatarsDataCommand::class,
                        GpgkeysDataCommand::class,
                        \Passbolt\TestData\Command\Large\GroupsDataCommand::class,
                        \Passbolt\TestData\Command\Large\GroupsUsersDataCommand::class,
                        \Passbolt\TestData\Command\Large\ResourcesDataCommand::class,
                        \Passbolt\TestData\Command\Large\PermissionsDataCommand::class,
                        \Passbolt\TestData\Command\Large\FavoritesDataCommand::class,
                        \Passbolt\TestData\Command\Large\CommentsDataCommand::class,
                        \Passbolt\TestData\Command\Large\SecretsDataCommand::class,
                        \Passbolt\TestData\Command\Large\TagsDataCommand::class,
                        \Passbolt\TestData\Command\Large\ResourcesTagsDataCommand::class,
                    ],
                ],
            ],
        ],
    ],
];

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

use Passbolt\TestData\Command\Alt0\GroupsUsersDataCommand as Alt0GroupsUsersDataCommand;
use Passbolt\TestData\Command\Alt0\PermissionsDataCommand as Alt0PermissionsDataCommand;
use Passbolt\TestData\Command\Alt0\SecretsDataCommand as Alt0SecretsDataCommand;
use Passbolt\TestData\Command\Base\AvatarsDataCommand;
use Passbolt\TestData\Command\Base\CommentsDataCommand as BaseCommentsDataCommand;
use Passbolt\TestData\Command\Base\EmailQueueDataCommand;
use Passbolt\TestData\Command\Base\FavoritesDataCommand as BaseFavoritesDataCommand;
use Passbolt\TestData\Command\Base\FoldersDataCommand;
use Passbolt\TestData\Command\Base\FoldersPermissionsDataCommand;
use Passbolt\TestData\Command\Base\FoldersRelationsDataCommand;
use Passbolt\TestData\Command\Base\GpgkeysDataCommand;
use Passbolt\TestData\Command\Base\GroupsDataCommand as BaseGroupsDataCommand;
use Passbolt\TestData\Command\Base\GroupsUsersDataCommand as BaseGroupsUsersDataCommand;
use Passbolt\TestData\Command\Base\PermissionsDataCommand as BasePermissionsDataCommand;
use Passbolt\TestData\Command\Base\ProfilesDataCommand as BaseProfilesDataCommand;
use Passbolt\TestData\Command\Base\ResourcesDataCommand as BaseResourcesDataCommand;
use Passbolt\TestData\Command\Base\ResourceTypesDataCommand;
use Passbolt\TestData\Command\Base\RolesDataCommand;
use Passbolt\TestData\Command\Base\SecretsDataCommand as BaseSecretsDataCommand;
use Passbolt\TestData\Command\Base\UsersDataCommand as BaseUsersDataCommand;
use Passbolt\TestData\Command\Large\CommentsDataCommand as LargeCommentsDataCommand;
use Passbolt\TestData\Command\Large\FavoritesDataCommand as LargeFavoritesDataCommand;
use Passbolt\TestData\Command\Large\GroupsDataCommand as LargeGroupsDataCommand;
use Passbolt\TestData\Command\Large\GroupsUsersDataCommand as LargeGroupsUsersDataCommand;
use Passbolt\TestData\Command\Large\PermissionsDataCommand as LargePermissionsDataCommand;
use Passbolt\TestData\Command\Large\ProfilesDataCommand as LargeProfilesDataCommand;
use Passbolt\TestData\Command\Large\ResourcesDataCommand as LargeResourcesDataCommand;
use Passbolt\TestData\Command\Large\ResourcesTagsDataCommand as LargeResourcesTagsDataCommand;
use Passbolt\TestData\Command\Large\SecretsDataCommand as LargeSecretsDataCommand;
use Passbolt\TestData\Command\Large\TagsDataCommand as LargeTagsDataCommand;
use Passbolt\TestData\Command\Large\UsersDataCommand as LargeUsersDataCommand;
use Passbolt\TestData\Command\Pro\AccountSettingsDataCommand;
use Passbolt\TestData\Command\Pro\ResourcesTagsDataCommand as ProResourcesTagsDataCommand;
use Passbolt\TestData\Command\Pro\TagsDataCommand as ProTagsDataCommand;
use Passbolt\TestData\Command\Security\XssCommentsDataCommand;
use Passbolt\TestData\Command\Security\XssGroupsDataCommand;
use Passbolt\TestData\Command\Security\XssGroupsUsersDataCommand;
use Passbolt\TestData\Command\Security\XssPermissionsDataCommand;
use Passbolt\TestData\Command\Security\XssProfilesDataCommand;
use Passbolt\TestData\Command\Security\XssResourcesDataCommand;
use Passbolt\TestData\Command\Security\XssUsersDataCommand;
use Passbolt\TestData\Scenario\Default\TestDataDefaultResourceTypesScenario;
use Passbolt\TestData\Scenario\Default\TestDataDefaultRolesScenario;
use Passbolt\TestData\Scenario\Demo\TestDataDemoCommentsScenario;
use Passbolt\TestData\Scenario\Demo\TestDataDemoFavoritesScenario;
use Passbolt\TestData\Scenario\Demo\TestDataDemoFoldersPermissionsScenario;
use Passbolt\TestData\Scenario\Demo\TestDataDemoFoldersRelationsScenario;
use Passbolt\TestData\Scenario\Demo\TestDataDemoFoldersScenario;
use Passbolt\TestData\Scenario\Demo\TestDataDemoGpgKeysScenario;
use Passbolt\TestData\Scenario\Demo\TestDataDemoGroupsScenario;
use Passbolt\TestData\Scenario\Demo\TestDataDemoGroupsUsersScenario;
use Passbolt\TestData\Scenario\Demo\TestDataDemoPermissionScenario;
use Passbolt\TestData\Scenario\Demo\TestDataDemoResourcesScenario;
use Passbolt\TestData\Scenario\Demo\TestDataDemoSecretsScenario;
use Passbolt\TestData\Scenario\Demo\TestDataDemoUsersScenario;
use Passbolt\TestData\Scenario\Security\TestDataSecurityXssCommentsScenario;
use Passbolt\TestData\Scenario\Security\TestDataSecurityXssGroupsScenario;
use Passbolt\TestData\Scenario\Security\TestDataSecurityXssGroupsUsersScenario;
use Passbolt\TestData\Scenario\Security\TestDataSecurityXssPermissionsScenario;
use Passbolt\TestData\Scenario\Security\TestDataSecurityXssResourcesScenario;
use Passbolt\TestData\Scenario\Security\TestDataSecurityXssUsersScenario;

return [
    'passbolt' => [
        'plugins' => [
            'testData' => [
                'version' => '1.0.0',
            ],
        ],
    ],
    'PassboltDevTestData' => [
        'saveStrategy' => 'default',
        'scenarios' => [
            'default' => [
                'install' => [
                    'shellTasks' => [
                        TestDataDefaultRolesScenario::class,
                        TestDataDefaultResourceTypesScenario::class,
                        TestDataDemoUsersScenario::class,
                        TestDataSecurityXssUsersScenario::class,
                        TestDataDemoGpgKeysScenario::class,
                        TestDataDemoGroupsScenario::class,
                        TestDataSecurityXssGroupsScenario::class,
                        TestDataDemoGroupsUsersScenario::class,
                        TestDataSecurityXssGroupsUsersScenario::class,
                        TestDataDemoResourcesScenario::class,
                        TestDataSecurityXssResourcesScenario::class,
                        TestDataDemoPermissionScenario::class,
                        TestDataSecurityXssPermissionsScenario::class,
                        TestDataDemoFavoritesScenario::class,
                        TestDataDemoCommentsScenario::class,
                        TestDataSecurityXssCommentsScenario::class,
                        TestDataDemoSecretsScenario::class,
                        TestDataDemoFoldersPermissionsScenario::class,
                        TestDataDemoFoldersScenario::class,
                        TestDataDemoFoldersRelationsScenario::class,
                    ],
                ],
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
                        BaseProfilesDataCommand::class,
                        XssProfilesDataCommand::class,
                        AvatarsDataCommand::class,
                        GpgkeysDataCommand::class,
                        BaseGroupsDataCommand::class,
                        XssGroupsDataCommand::class,
                        BaseGroupsUsersDataCommand::class,
                        XssGroupsUsersDataCommand::class,
                        BaseResourcesDataCommand::class,
                        XssResourcesDataCommand::class,
                        BasePermissionsDataCommand::class,
                        XssPermissionsDataCommand::class,
                        BaseFavoritesDataCommand::class,
                        BaseCommentsDataCommand::class,
                        XssCommentsDataCommand::class,
                        BaseSecretsDataCommand::class,
                        EmailQueueDataCommand::class,
                        AccountSettingsDataCommand::class,
                        ProTagsDataCommand::class,
                        ProResourcesTagsDataCommand::class,
                        FoldersPermissionsDataCommand::class,
                        FoldersDataCommand::class,
                        FoldersRelationsDataCommand::class,
                    ],
                ],
                'fixturize' => [
                    'shellTasks' => [
                        RolesDataCommand::class,
                        BaseUsersDataCommand::class,
                        BaseProfilesDataCommand::class,
                        AvatarsDataCommand::class,
                        GpgkeysDataCommand::class,
                        BaseGroupsDataCommand::class,
                        BaseGroupsUsersDataCommand::class,
                        BaseResourcesDataCommand::class,
                        BasePermissionsDataCommand::class,
                        BaseFavoritesDataCommand::class,
                        BaseCommentsDataCommand::class,
                        BaseSecretsDataCommand::class,
                        EmailQueueDataCommand::class,
                    ],
                ],
            ],
            'alt0' => [
                'install' => [
                    'shellTasks' => [
                        ResourceTypesDataCommand::class,
                        BaseGroupsDataCommand::class,
                        BaseUsersDataCommand::class,
                        BaseProfilesDataCommand::class,
                        GpgkeysDataCommand::class,
                        RolesDataCommand::class,
                        Alt0GroupsUsersDataCommand::class,
                        Alt0PermissionsDataCommand::class,
                        BaseResourcesDataCommand::class,
                        Alt0SecretsDataCommand::class,
                    ],
                ],
                'fixturize' => [
                    'shellTasks' => [
                        BaseGroupsDataCommand::class,
                        BaseUsersDataCommand::class,
                        BaseProfilesDataCommand::class,
                        GpgkeysDataCommand::class,
                        RolesDataCommand::class,
                        Alt0GroupsUsersDataCommand::class,
                        Alt0PermissionsDataCommand::class,
                        BaseResourcesDataCommand::class,
                        Alt0SecretsDataCommand::class,
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
                        LargeProfilesDataCommand::class,
                        AvatarsDataCommand::class,
                        GpgkeysDataCommand::class,
                        LargeGroupsDataCommand::class,
                        LargeGroupsUsersDataCommand::class,
                        LargeResourcesDataCommand::class,
                        LargePermissionsDataCommand::class,
                        LargeFavoritesDataCommand::class,
                        LargeCommentsDataCommand::class,
                        LargeSecretsDataCommand::class,
                        LargeTagsDataCommand::class,
                        LargeResourcesTagsDataCommand::class,
                    ],
                ],
            ],
        ],
    ],
];

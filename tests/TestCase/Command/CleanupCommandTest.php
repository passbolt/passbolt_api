<?php
declare(strict_types=1);

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
 * @since         3.1.0
 */
namespace App\Test\TestCase\Command;

use App\Command\CleanupCommand;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Migrations\TestSuite\Migrator;

class CleanupCommandTest extends AppTestCase
{
    use ConsoleIntegrationTestTrait;
    use TruncateDirtyTables;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        CleanupCommand::resetCleanups();
    }

    /**
     * Basic help test
     */
    public function testCleanupCommandHelp()
    {
        $this->exec('passbolt cleanup -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Identify and fix database relational integrity issues.');
        $this->assertOutputContains('cake passbolt cleanup');
    }

    /**
     * Basic test
     */
    public function testCleanupCommandFixMode()
    {
        (new Migrator())->run();
        UserFactory::make()->admin()->persist();
        $this->exec('passbolt cleanup');
        $this->assertExitSuccess();
        $this->assertOutputContains('Cleanup shell');
        $this->assertOutputContains('(fix mode)');
    }

    /**
     * Basic test dry run
     */
    public function testCleanupCommandDryRun()
    {
        UserFactory::make()->admin()->persist();
        $this->exec('passbolt cleanup --dry-run');
        $this->assertExitSuccess();
        $this->assertOutputContains('Cleanup shell');
        $this->assertOutputContains('(dry-run)');
    }

    /**
     * Fix groups with no members
     */
    public function testCleanupCommandFixMode_GroupsWithNoMembers()
    {
        // Add group with no member
        $groupWithNoMembers = GroupFactory::make()->persist();
        // Add group with member(s)
        $userManager = UserFactory::make()->admin()->persist();
        $groupWithMember = GroupFactory::make()->withGroupsManagersFor([$userManager])->persist();
        $deletedGroupWithMember = GroupFactory::make()->deleted()->withGroupsManagersFor([$userManager])->persist();
        $deletedGroupWithNoMember = GroupFactory::make()->deleted()->persist();

        $this->exec('passbolt cleanup');

        $this->assertExitSuccess();
        $this->assertOutputContains('Cleanup shell');
        $this->assertOutputContains('(fix mode)');
        // Make sure the groups with member(s) or already soft-deleted are not deleted
        $expectedGroupsNotDeleted = [
            $groupWithMember,
            $deletedGroupWithMember,
            $deletedGroupWithNoMember,
        ];
        $groups = GroupFactory::find('list')->all()->toArray();
        $this->assertCount(3, $groups);
        $this->assertSame(3, GroupFactory::count());
        foreach ($expectedGroupsNotDeleted as $group) {
            $this->assertArrayHasKey($group['id'], $groups);
        }
        $this->assertArrayNotHasKey($group['id'], $groupWithNoMembers);
    }

    public function testCleanupCommand_NoActiveAdministrator()
    {
        $this->exec('passbolt cleanup');

        $this->assertExitSuccess();
        $this->assertOutputContains('Cleanup shell');
        $this->assertOutputContains('(fix mode)');
        $this->assertErrorContains('Cleanup command cannot be executed on an instance having no active administrator');
    }

    public function testCleanupCommand_UsersTableNotCreated()
    {
        $this->markTestSkipped('Dropping `users` table fails subsequent tests');

        $connection = ConnectionManager::get('default');
        $quotedTableName = $connection->getDriver()->quoteIdentifier('users');
        $connection->query("DROP TABLE {$quotedTableName}");

        $this->exec('passbolt cleanup');

        $this->assertExitSuccess();
        $this->assertOutputContains('Cleanup shell');
        $this->assertOutputContains('(fix mode)');
        $this->assertErrorContains('Cleanup command cannot be executed on an instance having no users table');
    }

    public function testCleanupCommand_SelfRegisteringCore_IntegrationTest()
    {
        $verboseMethodCallPrints = [
            'Groups:cleanupWithNoMembers',
            'GroupsUsers:cleanupSoftDeletedUsers',
            'GroupsUsers:cleanupHardDeletedUsers',
            'GroupsUsers:cleanupSoftDeletedGroups',
            'GroupsUsers:cleanupHardDeletedGroups',
            'GroupsUsers:cleanupDuplicatedGroupsUsers',
            'Favorites:cleanupSoftDeletedUsers',
            'Favorites:cleanupHardDeletedUsers',
            'Favorites:cleanupSoftDeletedResources',
            'Favorites:cleanupHardDeletedResources',
            'Favorites:cleanupDuplicatedFavorites',
            'Comments:cleanupSoftDeletedUsers',
            'Comments:cleanupHardDeletedUsers',
            'Comments:cleanupSoftDeletedResources',
            'Comments:cleanupHardDeletedResources',
            'Permissions:cleanupSoftDeletedUsers',
            'Permissions:cleanupHardDeletedUsers',
            'Permissions:cleanupSoftDeletedGroups',
            'Permissions:cleanupHardDeletedGroups',
            'Permissions:cleanupSoftDeletedResources',
            'Permissions:cleanupHardDeletedResources',
            'Permissions:cleanupDuplicatedPermissions',
            'Secrets:cleanupSoftDeletedUsers',
            'Secrets:cleanupHardDeletedUsers',
            'Secrets:cleanupSoftDeletedResources',
            'Secrets:cleanupHardDeletedResources',
            'Secrets:cleanupHardDeletedPermissions',
            'Resources:cleanupMissingResourceTypeId',
            'Avatars:cleanupSoftDeletedUsers',
            'Avatars:cleanupHardDeletedUsers',
            'Avatars:cleanupHardDeletedProfiles',
            'Users:cleanupInactiveUsersWithDuplicatedUsername',
        ];

        UserFactory::make()->admin()->persist();
        $this->exec('passbolt cleanup --dry-run --verbose');
        $this->assertExitSuccess();
        foreach ($verboseMethodCallPrints as $methodCallPrint) {
            $this->assertOutputContains($methodCallPrint);
        }
    }

    public function testCleanupCommand_SelfRegisteringPluginsCE_IntegrationTest()
    {
        Configure::write('passbolt.plugins.folders', ['enabled' => true]);

        $verboseMethodCallPrints = [
            'Passbolt/Metadata.MetadataPrivateKeys:cleanupHardDeletedUsers',
            'Passbolt/Metadata.MetadataPrivateKeys:cleanupSoftDeletedUsers',
            'Passbolt/Folders.FoldersRelations:cleanupHardDeletedUsers',
            'Passbolt/Folders.FoldersRelations:cleanupSoftDeletedUsers',
            'Passbolt/Folders.FoldersRelations:cleanupHardDeletedResources',
            'Passbolt/Folders.FoldersRelations:cleanupSoftDeletedResources',
            'Passbolt/Folders.FoldersRelations:cleanupHardDeletedFolders',
            'Passbolt/Folders.FoldersRelations:cleanupHardDeletedFoldersParents',
            'Passbolt/Folders.FoldersRelations:cleanupMissingFoldersFoldersRelations',
            'Passbolt/Folders.FoldersRelations:cleanupMissingResourcesFoldersRelations',
            'Passbolt/Folders.FoldersRelations:cleanupDuplicatedFoldersRelations',
            'Permissions:cleanupHardDeletedFolders', // injected into Permissions table via a behavior from Folders
        ];

        UserFactory::make()->admin()->persist();
        $this->exec('passbolt cleanup --dry-run --verbose');
        $this->assertExitSuccess();
        foreach ($verboseMethodCallPrints as $methodCallPrint) {
            $this->assertOutputContains($methodCallPrint);
        }
    }
}

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
 * @since         v5.4.0
 */
namespace Passbolt\TestData\Test\TestCase\Command;

use App\Test\Factory\CommentFactory;
use App\Test\Factory\FavoriteFactory;
use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\GroupsUserFactory;
use App\Test\Factory\ProfileFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\SecretFactory;
use App\Test\Factory\UserFactory;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\Database\Driver\Postgres;
use Cake\Datasource\ConnectionManager;
use Cake\TestSuite\TestCase;
use Passbolt\EmailDigest\Test\Factory\EmailQueueFactory;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Folders\Test\Factory\FoldersRelationFactory;
use Passbolt\Folders\Test\Factory\PermissionFactory;
use Passbolt\Folders\Test\Factory\ResourceFactory;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

class TestDataInsertCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;

    /**
     * setUp methods
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Basic help test
     */
    public function testTestDataInsertCommand_Help()
    {
        $this->exec('passbolt insert -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('cake passbolt insert');
    }

    public function testTestDataInsertCommand_No_Scenario()
    {
        $this->exec('passbolt insert');
        $this->assertExitError();
    }

    public function testTestDataInsertCommand_Base_Scenario()
    {
        $this->exec('passbolt insert default');
        $this->assertExitSuccess();

        // Avatars
        // Does not do avatars as not says it is optional

        // Users
        $this->assertEquals(45, UserFactory::count());

        // Comments
        $this->assertEquals(326, CommentFactory::count());

        // Profiles
        $this->assertEquals(45, ProfileFactory::count());

        // Gpgkeys
        $this->assertEquals(44, GpgKeyFactory::count());

        // Groups
        $this->assertEquals(37, GroupFactory::count());
        // Group Users
        $this->assertEquals(353, GroupsUserFactory::count());

        // Resources
        $this->assertEquals(50, ResourceFactory::count());
        // Resources Types
        $this->assertEquals(8, ResourceTypeFactory::count());
        // Permissions
        $this->assertEquals(861, PermissionFactory::count());
        // 32 of Permissions are Folders Permissions

        // Roles
        $this->assertEquals(4, RoleFactory::count());

        // Secrets
        $this->assertEquals(506, SecretFactory::count());

        // Favorites
        $this->assertEquals(4, FavoriteFactory::count());

        // Email Queue
        // No emails in queue
        $this->assertEquals(0, EmailQueueFactory::count());

        // Folders
        $this->assertEquals(19, FolderFactory::count());

        // Folders Relations
        $this->assertEquals(38, FoldersRelationFactory::count());

        // Folders Permissions
        // 36 part of Permissions

        // AccountSettingsDataCommand::class,
        // TagsDataCommand::class,
        // ResourcesTagsDataCommand::class,
        // These 3 are skipped as they are Pro features
    }

    public function testTestDataInsertCommand_Alt0_Scenario()
    {
        $this->exec('passbolt insert alt0');
        $this->assertExitSuccess();

        // Users
        $this->assertEquals(27, UserFactory::count());

        // Profiles
        $this->assertEquals(27, ProfileFactory::count());

        // Gpgkeys
        $this->assertEquals(26, GpgKeyFactory::count());

        // Groups
        $this->assertEquals(19, GroupFactory::count());
        // Group Users
        $this->assertEquals(22, GroupsUserFactory::count());

        // Resources
        $this->assertEquals(32, ResourceFactory::count());
        // Resources Types
        $this->assertEquals(8, ResourceTypeFactory::count());
        // Permissions
        $this->assertEquals(60, PermissionFactory::count());

        // Roles
        $this->assertEquals(4, RoleFactory::count());

        // Secrets
        $this->assertEquals(68, SecretFactory::count());

        // Folders
        // config.php in passbolt-test-data insert does not have folders
        // nor does it have any Email Queues or avatars
    }

    public function testTestDataInsertCommand_Large_Scenario()
    {
        $this->exec('passbolt insert large');
        $this->assertExitSuccess();

        // The $resourcesTable->findIndex throws an error on Postgres
        // in Large CommentsDataCommand and FavoritesDataCommand
        // Thus no entries are persisted for these two models
        $isRunningOnPostgres = ConnectionManager::get('default')->getDriver() instanceof Postgres;

        // Avatars
        // Does not do avatars as not says it is optional

        // Users
        $this->assertEquals(52, UserFactory::count());

        // Comments
        $this->assertEquals($isRunningOnPostgres ? 0 : 28050, CommentFactory::count());

        // Profiles
        $this->assertEquals(52, ProfileFactory::count());

        // Gpgkeys
        $this->assertEquals(52, GpgKeyFactory::count());

        // Groups
        $this->assertEquals(101, GroupFactory::count());
        // Group Users
        $this->assertEquals(51, GroupsUserFactory::count());

        // Resources
        $this->assertEquals(3050, ResourceFactory::count());
        // Resources Types
        $this->assertEquals(8, ResourceTypeFactory::count());
        // Permissions
        $this->assertEquals(3050, PermissionFactory::count());

        // Roles
        $this->assertEquals(4, RoleFactory::count());

        // Secrets
        $this->assertEquals(28050, SecretFactory::count());

        // Favorites
        $this->assertEquals($isRunningOnPostgres ? 0 : 28050, FavoriteFactory::count());

        // Email Queue
        // EmailQueueDataCommand not called

        // FoldersPermissionsDataCommand
        // FoldersDataCommand
        // FoldersRelationsDataCommand
        // These 3 are not called

        // TagsDataCommand
        // ResourcesTagsDataCommand
        // These 2 do not work as could not describe coloumns
    }
}

<?php
declare(strict_types=1);

namespace Passbolt\TestData\Test\TestCase\Command;

use App\Test\Factory\AvatarFactory;
use App\Test\Factory\CommentFactory;
use App\Test\Factory\FavoriteFactory;
use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\GroupsUserFactory;
use App\Test\Factory\ProfileFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\SecretFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\EmailDigest\Test\Factory\EmailQueueFactory;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Folders\Test\Factory\FoldersRelationFactory;
use Passbolt\Folders\Test\Factory\PermissionFactory;
use Passbolt\Folders\Test\Factory\ResourceFactory;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

class TestDataInsertWithFactoriesCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;
    use TruncateDirtyTables;

    /**
     * Basic help test
     */
    public function testTestDataInsertWithFactoriesCommand_Help()
    {
        $this->exec('passbolt insert_with_factories -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('cake passbolt insert_with_factories');
    }

    public function testTestDataInsertWithFactoriesCommand_WithSeleniumFlagDisabled_Should_Not_Find_The_Command()
    {
        Configure::write('passbolt.selenium.active', false);
        $this->exec('passbolt insert_with_factories demo --truncate');
        $this->assertErrorContains('Error: Unknown option `truncate`.');
    }

    public function testTestDataInsertWithFactoriesCommand_No_Scenario()
    {
        $this->exec('passbolt insert_with_factories');
        $this->assertExitError();
        $this->assertErrorContains('The `scenario` argument is required.');
    }

    public function testTestDataInsertWithFactoriesCommand_Demo_Truncate_Scenario()
    {
        $this->exec('passbolt insert_with_factories demo --truncate');
        $this->assertOutputContains('<success>' . __('Data inserted successfully in '));
        $this->assertExitSuccess();

        // Avatars
        $this->assertEquals(25, AvatarFactory::count());

        // Users
        $this->assertEquals(27, UserFactory::count());

        // Comments
        $this->assertEquals(2, CommentFactory::count());

        // Profiles
        $this->assertEquals(27, ProfileFactory::count());

        // Gpgkeys
        $this->assertEquals(26, GpgKeyFactory::count());

        // Groups
        $this->assertEquals(19, GroupFactory::count());

        // Group Users
        $this->assertEquals(29, GroupsUserFactory::count());

        // Resources
        $this->assertEquals(32, ResourceFactory::count());

        // Resources Types
        $this->assertEquals(9, ResourceTypeFactory::count());

        // Permissions
        $this->assertEquals(213, PermissionFactory::count());
        // 32 of Permissions are Folders Permissions

        // Roles
        $this->assertEquals(3, RoleFactory::count());

        // Secrets
        $this->assertEquals(182, SecretFactory::count());

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
    }

    public function testTestDataInsertWithFactoriesCommand_Demo_withData_Fail_Scenario()
    {
        UserFactory::make([
            'id' => UuidFactory::uuid('user.id.admin'),
            'username' => 'admin@passbolt.com',
            'active' => 1,
            'deleted' => 0,
            'created_by' => UuidFactory::uuid('user.id.admin'),
            'modified_by' => UuidFactory::uuid('user.id.admin'),
        ])->admin()->persist();
        $this->exec('passbolt insert_with_factories demo');
        $this->assertExitError();
        $this->assertOutputContains('There is already dummy data in the DB, try with the --truncate option.');
    }

    public function testTestDataInsertWithFactoriesCommand_Security_Truncate_Scenario()
    {
        $this->exec('passbolt insert_with_factories security --truncate');
        $this->assertOutputContains('<success>' . __('Data inserted successfully in '));
        $this->assertExitSuccess();

        // Avatars
        $this->assertEquals(0, AvatarFactory::count());

        // Users
        $this->assertEquals(18, UserFactory::count());

        // Comments
        $this->assertEquals(324, CommentFactory::count());

        // Profiles
        $this->assertEquals(18, ProfileFactory::count());

        // Gpgkeys
        $this->assertEquals(18, GpgKeyFactory::count());

        // Groups
        $this->assertEquals(18, GroupFactory::count());

        // Group Users
        $this->assertEquals(324, GroupsUserFactory::count());

        // Resources
        $this->assertEquals(18, ResourceFactory::count());

        // Resources Types
        $this->assertEquals(9, ResourceTypeFactory::count());

        // Permissions
        $this->assertEquals(648, PermissionFactory::count());

        // Roles
        $this->assertEquals(3, RoleFactory::count());

        // Secrets
        $this->assertEquals(0, SecretFactory::count());

        // Favorites
        $this->assertEquals(0, FavoriteFactory::count());

        // Email Queue
        // No emails in queue
        $this->assertEquals(0, EmailQueueFactory::count());

        // Folders
        $this->assertEquals(0, FolderFactory::count());

        // Folders Relations
        $this->assertEquals(0, FoldersRelationFactory::count());
    }

    public function testTestDataInsertWithFactoriesCommand_Security_withData_Fail_Scenario()
    {
        UserFactory::make([
            'id' => UuidFactory::uuid('user.id.xss0'),
            'username' => 'xss0@passbolt.com',
            'active' => 1,
            'deleted' => 0,
            'created_by' => UuidFactory::uuid('user.id.admin'),
            'modified_by' => UuidFactory::uuid('user.id.admin'),
        ])->admin()->persist();
        $this->exec('passbolt insert_with_factories security');
        $this->assertExitError();
        $this->assertOutputContains('There is already dummy data in the DB, try with the --truncate option.');
    }

    public function testTestDataInsertWithFactoriesCommand_Default_Truncate_Scenario()
    {
        $this->exec('passbolt insert_with_factories default --truncate');
        $this->assertOutputContains('<success>' . __('Data inserted successfully in '));
        $this->assertExitSuccess();

        // Avatars
        $this->assertEquals(25, AvatarFactory::count());

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
        $this->assertEquals(9, ResourceTypeFactory::count());

        // Permissions
        $this->assertEquals(861, PermissionFactory::count());
        // 32 of Permissions are Folders Permissions

        // Roles
        $this->assertEquals(3, RoleFactory::count());

        // Secrets
        $this->assertEquals(182, SecretFactory::count());

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
    }

    public function testTestDataInsertWithFactoriesCommand_Default_withData_Fail_Scenario()
    {
        UserFactory::make([
            'id' => UuidFactory::uuid('user.id.admin'),
            'username' => 'admin@passbolt.com',
            'active' => 1,
            'deleted' => 0,
            'created_by' => UuidFactory::uuid('user.id.admin'),
            'modified_by' => UuidFactory::uuid('user.id.admin'),
        ])->admin()->persist();
        $this->exec('passbolt insert_with_factories default');
        $this->assertExitError();
        $this->assertOutputContains('There is already dummy data in the DB, try with the --truncate option.');
    }
}

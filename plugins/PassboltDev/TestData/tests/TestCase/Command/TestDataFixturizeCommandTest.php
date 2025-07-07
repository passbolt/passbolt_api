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
use Cake\TestSuite\TestCase;
use Passbolt\EmailDigest\Test\Factory\EmailQueueFactory;
use Passbolt\Folders\Test\Factory\PermissionFactory;
use Passbolt\Folders\Test\Factory\ResourceFactory;

class TestDataFixturizeCommandTest extends TestCase
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
        $this->markTestSkipped('Skiped as the fixturize command is deprecated.');
    }

    /**
     * Basic help test
     */
    public function testTestDataFixturizeCommand_Help()
    {
        $this->exec('passbolt fixturize -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('cake passbolt fixturize');
    }

    public function testTestDataFixturizeCommand_No_Scenario()
    {
        $this->exec('passbolt fixturize');
        $this->assertExitError();
    }

    public function testTestDataFixturizeCommand_Base_Scenario()
    {
        $this->exec('passbolt fixturize default');
        $this->assertExitSuccess();

        // Avatars
        // Does not do avatars as not says it is optional

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
        // Permissions
        $this->assertEquals(177, PermissionFactory::count());

        // Roles
        $this->assertEquals(4, RoleFactory::count());

        // Secrets
        $this->assertEquals(182, SecretFactory::count());

        // Favorites
        $this->assertEquals(4, FavoriteFactory::count());

        // Email Queue
        // No emails in queue
        $this->assertEquals(0, EmailQueueFactory::count());

        // Folders
        // config.php in passbolt-test-data fixturize does not have folders
    }

    public function testTestDataFixturizeCommand_Alt0_Scenario()
    {
        $this->exec('passbolt fixturize alt0');
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
        // Permissions
        $this->assertEquals(60, PermissionFactory::count());

        // Roles
        $this->assertEquals(4, RoleFactory::count());

        // Secrets
        $this->assertEquals(68, SecretFactory::count());

        // Folders
        // config.php in passbolt-test-data fixturize does not have folders
        // nor does it have any Email Queues or avatars
    }

    public function testTestDataFixturizeCommand_Large_Scenario()
    {
        $this->exec('passbolt fixturize large');
        $this->assertExitSuccess();
        // config.php in passbolt-test-data large has no fixturize
    }
}

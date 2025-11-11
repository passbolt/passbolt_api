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
 * @since         5.7.0
 */
namespace App\Test\TestCase\Command;

use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Test\Lib\Utility\PassboltCommandTestTrait;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;

class UsersIndexCommandTest extends AppTestCase
{
    use ConsoleIntegrationTestTrait;
    use EmailNotificationSettingsTestTrait;
    use EmailQueueTrait;
    use PassboltCommandTestTrait;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->loadNotificationSettings();
        $this->mockProcessUserService('www-data');
    }

    /**
     * Basic help test
     */
    public function testUsersIndexCommandHelp()
    {
        $this->exec('passbolt users_index -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('List users.');
        $this->assertOutputContains('cake passbolt users_index');
    }

    /**
     * @Given I am root
     * @When I run "passbolt users_index"
     * @Then the command cannot be run.
     */
    public function testUsersIndexCommandAsRoot()
    {
        $this->assertCommandCannotBeRunAsRootUser('users_index');
    }

    /**
     * @Given I am not root
     * @When I run "passbolt users_index"
     * @And there are users in the organization
     * @Then the command runs, and return a table containing all kind of users.
     */
    public function testUsersIndexCommandAsNonRootHavingUsers()
    {
        $active = UserFactory::make()->user()->active()->withProfileName('N_Active1', 'SN_Active1')->persist();
        $inactive = UserFactory::make()->user()->inactive()->withProfileName('N_Inactive1', 'SN_Inactive1')->persist();
        $disabled = UserFactory::make()->user()->disabled()->withProfileName('N_Disabled1', 'SN_Disabled1')->persist();
        $deleted = UserFactory::make()->user()->deleted()->withProfileName('N_Deleted1', 'SN_Deleted1')->persist();
        $admin = UserFactory::make()->admin()->active()->withProfileName('N_Admin1', 'SN_Admin1')->persist();
        $admin = UserFactory::make()->admin()->active()->withProfileName('N_Admin1', 'SN_Admin1')->persist();
        RoleFactory::make()->guest()->persist(); // db records for roles are required by users->findIndex query builder

        $this->exec('passbolt users_index');
        $this->assertExitSuccess();

        // we assert each kind of user is returned, even deleted ones
        $this->assertOutputContains($active->get('username'));
        $this->assertOutputContains($inactive->get('username'));
        $this->assertOutputContains($disabled->get('username'));
        $this->assertOutputContains($deleted->get('username'));
        $this->assertOutputContains($admin->get('username'));

        // we assert the role column is filled
        $this->assertOutputContains($active->get('role')->get('name'));
        $this->assertOutputContains($active->get('role')->get('name'));
    }

    /**
     * @Given I am not root
     * @When I run "passbolt users_index"
     * @And there are NO users in the organization
     * @Then the command runs, and return a success status but with a warning message.
     */
    public function testUsersIndexCommandAsNonRootWithoutUsers()
    {
        // required by the $userTable->findIndex()
        RoleFactory::make()->guest()->persist();

        $this->exec('passbolt users_index');
        $this->assertExitSuccess();

        $this->assertOutputContains('This organization has no registered users.');
    }
}

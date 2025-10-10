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

use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\PassboltCommandTestTrait;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;

class UserPromoteToAdministratorCommandTest extends AppTestCase
{
    use ConsoleIntegrationTestTrait;
    use PassboltCommandTestTrait;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->mockProcessUserService('www-data');
    }

    /**
     * Basic help test
     */
    public function testUserPromoteToAdministratorCommandHelp()
    {
        $this->exec('passbolt user_promote_to_administrator -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Promote a user to administrator.');
        $this->assertOutputContains('cake passbolt user_promote_to_administrator');
    }

    /**
     * @Given I am root
     * @When I run "passbolt register_user"
     * @Then the command cannot be run.
     */
    public function testUserPromoteToAdministratorCommandAsRoot()
    {
        $this->assertCommandCannotBeRunAsRootUser('user_promote_to_administrator  --user-username john.doe@passbolt.com --admin-username jane.doe@passbolt.com');
    }

    /**
     * @Given I am not root
     * @When I run "passbolt user_promote_to_administrator" with correct parameters
     * @Then the command runs, returning a success, and the given user has been promoted.
     */
    public function testUserPromoteToAdministratorCommandAsNonRoot()
    {
        $userToPromote = UserFactory::make()->user()->active()->persist();
        $admin = UserFactory::make()->admin()->active()->persist();

        // "--org acme_test" the tests will run in the database defined in the config.php file
        $options = " --user-username $userToPromote->username --admin-username $admin->username";
        $this->exec('passbolt user_promote_to_administrator' . $options);
        $this->assertExitSuccess();

        $userPromoted = UserFactory::get($userToPromote->get('id'));
        $this->assertSame($userPromoted->get('role_id'), $admin->get('role_id'));
    }

    /**
     * @Given I am not root
     * @When I run "passbolt user_promote_to_administrator" with incorrect admin name
     * @Then the command returns an error code with a message
     */
    public function testUserPromoteToAdministratorCommandAsNonRootWrongAdminUsername()
    {
        $userToPromote = UserFactory::make()->user()->active()->persist();
        $wrongAdminUserName = 'WrongAdminUserName@passbolt.localhost';

        // "--org acme_test" the tests will run in the database defined in the config.php file
        $options = " --user-username $userToPromote->username --admin-username $wrongAdminUserName";
        $this->exec('passbolt user_promote_to_administrator' . $options);
        $this->assertExitError();

        $this->assertOutputContains('No administrator matching');
    }

    /**
     * @Given I am not root
     * @When I run "passbolt user_promote_to_administrator" with incorrect admin name
     * @Then the command returns an error code with a message
     */
    public function testUserPromoteToAdministratorCommandAsNonRootWrongUserUsername()
    {
        $wrongUserUserName = 'WrongUserUserName@passbolt.localhost';
        $admin = UserFactory::make()->admin()->active()->persist();

        // "--org acme_test" the tests will run in the database defined in the config.php file
        $options = " --user-username $wrongUserUserName --admin-username $admin->username";
        $this->exec('passbolt user_promote_to_administrator' . $options);
        $this->assertExitError();

        $this->assertOutputContains('No user matching the username');
    }
}

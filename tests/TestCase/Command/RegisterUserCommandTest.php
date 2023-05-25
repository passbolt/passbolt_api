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

use App\Command\RegisterUserCommand;
use App\Model\Entity\Role;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Test\Lib\Utility\PassboltCommandTestTrait;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Faker\Factory;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;

class RegisterUserCommandTest extends AppTestCase
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
        $this->useCommandRunner();
        RegisterUserCommand::$isUserRoot = false;
        $this->loadNotificationSettings();
    }

    /**
     * Basic help test
     */
    public function testRegisterUserCommandHelp()
    {
        $this->exec('passbolt register_user -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Register a new user.');
        $this->assertOutputContains('cake passbolt register_user');
    }

    /**
     * @Given I am root
     * @When I run "passbolt register_user"
     * @Then the command cannot be run.
     */
    public function testRegisterUserCommandAsRoot()
    {
        $this->assertCommandCannotBeRunAsRootUser(RegisterUserCommand::class);
    }

    public function withAdmin(): array
    {
        return [[false, true]];
    }

    /**
     * @Given I am not root
     * @When I run "passbolt register_user" either if an admin or not is present
     * @Then the command runs, returning a failure, but no exception.
     * @dataProvider withAdmin
     */
    public function testRegisterUserCommandWithOrWithoutExistingAdmin(bool $withAdmin)
    {
        RoleFactory::make()->user()->persist();

        if ($withAdmin) {
            UserFactory::make()->admin()->persist();
        }

        $faker = Factory::create();
        $role = Role::USER;
        $username = $faker->email();
        $firstName = $faker->firstNameFemale();
        $lastName = $faker->lastName();

        $options = " -r $role -u $username -f $firstName -l $lastName";

        $this->exec('passbolt register_user' . $options);
        $this->assertExitSuccess();
        $this->assertSame(1 + $withAdmin, UserFactory::count());
//         TODO: fix this line in the CI
//        $this->assertEmailQueueCount(1);
    }

    /**
     * Register User with interaction
     *
     * @throws \Exception
     */
    public function testRegisterUserCommandInteractively()
    {
        RoleFactory::make()->user()->persist();

        // Prepare the interaction inputs
        $faker = Factory::create();
        $input = [$faker->email(), $faker->firstNameFemale(), $faker->lastName(), Role::USER];

        // Run the register command
        $this->exec('passbolt register_user -i', $input);
        $this->assertExitSuccess();
        $this->assertSame(1, UserFactory::count());

        // Assert that the correct link is provided in the console
        $user = UserFactory::find()->firstOrFail();
        $token = TableRegistry::getTableLocator()->get('AuthenticationTokens')->getByUserId($user->id);
        $setupLink = Router::url('/setup/start/' . $user->id . '/' . $token->token, true);
        $this->assertOutputContains($setupLink);
//         TODO: fix this line in the CI
//        $this->assertEmailQueueCount(1);
    }
}

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
 * @since         4.0.0
 */
namespace App\Test\TestCase\Command;

use App\Command\RegisterUserCommand;
use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;

class RecoverUserCommandTest extends TestCase
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
        $this->useCommandRunner();
        RegisterUserCommand::$isUserRoot = false;
    }

    public function testRecoverUserCommandHelp()
    {
        $this->exec('passbolt recover_user -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Get an existing account recovery token, or create a new one.');
        $this->assertOutputContains('--username, -u');
        $this->assertOutputContains('The user name (email).');
        $this->assertOutputContains('--create, -c');
        $this->assertOutputContains('Create a new token.');
    }

    public function testRecoverUserCommand_Fetch_On_Active_User()
    {
        $user = UserFactory::make()->user()->active()->persist();
        $token = AuthenticationTokenFactory::make()
            ->type(AuthenticationToken::TYPE_RECOVER)
            ->userId($user->id)
            ->active()->persist();
        $this->exec('passbolt recover_user -u ' . $user->username);
        $this->assertExitSuccess();
        $this->assertOutputContains(
            Router::url('/setup/recover/' . $user->id . '/' . $token['token'], true)
        );
        $this->assertSame(1, AuthenticationTokenFactory::count());
    }

    public function testRecoverUserCommand_Create_On_Active_User_Without_Token()
    {
        $user = UserFactory::make()->user()->active()->persist();
        $this->exec('passbolt recover_user -u ' . $user->username);
        $this->assertExitError();
        $this->assertSame(0, AuthenticationTokenFactory::count());
    }

    public function testRecoverUserCommand_Create_On_Active_User()
    {
        $user = UserFactory::make()->user()->active()->persist();
        $this->exec('passbolt recover_user -c -u ' . $user->username);
        $this->assertExitSuccess();
        $token = AuthenticationTokenFactory::find()->firstOrFail();
        $this->assertOutputContains(
            Router::url('/setup/recover/' . $user->id . '/' . $token['token'], true)
        );
        $this->assertSame(1, AuthenticationTokenFactory::count());
    }

    public function testRecoverUserCommand_On_Inactive_User()
    {
        $user = UserFactory::make()->inactive()->user()->persist();
        $this->exec('passbolt recover_user -u ' . $user->username);
        $this->assertExitError();
    }
}

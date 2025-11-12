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

use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\PassboltCommandTestTrait;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\Core\Configure;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;

/**
 * App\Command\PassboltCommand Test Case
 *
 * @uses \App\Command\PassboltCommand
 */
class PassboltCommandTest extends AppTestCase
{
    use ConsoleIntegrationTestTrait;
    use PassboltCommandTestTrait;
    use TruncateDirtyTables;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Test the help option
     *
     * @return void
     */
    public function testPassboltCommandHelp(): void
    {
        $this->exec('passbolt -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('The Passbolt CLI');
        $this->assertOutputContains('console.');
        $this->assertOutputContains('cake passbolt');
    }

    public function testPassboltCommand_As_Root(): void
    {
        $this->mockProcessUserService('root');
        $this->exec('passbolt healthcheck');
        $this->assertExitError();
        $this->assertOutputContains('Passbolt commands cannot be executed as root.');
        $this->assertOutputContains('The command should be executed with the same user as your web server. By instance:');
        $this->assertOutputContains('su -s /bin/bash -c "' . ROOT . '/bin/cake passbolt healthcheck" HTTP_USER');
    }

    public function testPassboltCommand_As_Unknown_Webserver_User(): void
    {
        $this->mockProcessUserService('foo');
        $this->exec('passbolt healthcheck --database');
        $this->assertExitSuccess();
        $this->assertOutputContains('The command should be executed with the same user as your web server. By instance:');
        $this->assertOutputContains('su -s /bin/bash -c "' . ROOT . '/bin/cake passbolt healthcheck" HTTP_USER');
    }

    public function testPassboltCommand_As_Unknown_Webserver_User_DisplayNonWebUserWarning_IsDisabled(): void
    {
        Configure::write('passbolt.security.displayNonWebUserWarning', false);
        $this->mockProcessUserService('foo');
        $this->exec('passbolt healthcheck --database');
        $this->assertExitSuccess();
        $this->assertOutputNotContains('Passbolt commands should only be executed as the web server user.');
    }
}

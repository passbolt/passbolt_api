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

use App\Command\HealthcheckCommand;
use App\Test\Lib\Utility\PassboltCommandTestTrait;
use Cake\TestSuite\ConsoleIntegrationTestTrait;
use Cake\TestSuite\TestCase;

class HealthcheckCommandTest extends TestCase
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
        $this->useCommandRunner();
        HealthcheckCommand::$isUserRoot = false;
    }

    /**
     * Basic help test
     */
    public function testHealthcheckCommandHelp()
    {
        $this->exec('passbolt healthcheck -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Check the configuration of this installation and associated environment.');
        $this->assertOutputContains('cake passbolt healthcheck');
    }

    /**
     * Will fail if run as root
     */
    public function testHealthcheckCommandRoot()
    {
        $this->assertCommandCannotBeRunAsRootUser(HealthcheckCommand::class);
    }

    /**
     * Basic test
     */
    public function testHealthcheckCommand()
    {
        $this->exec('passbolt healthcheck -d test');
        $this->assertExitSuccess();
        // Since the tests run with debug on, here will always be at least one error in the healthcheck.
        $this->assertOutputContains('error(s) found. Hang in there!');
    }

    public function testHealthcheckCommand_Environment()
    {
        $this->exec('passbolt healthcheck -d test --environment');
        $this->assertExitSuccess();
        $this->assertOutputContains('No error found. Nice one sparky!');
    }
}

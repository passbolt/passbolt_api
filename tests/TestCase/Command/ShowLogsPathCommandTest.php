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

use Cake\TestSuite\ConsoleIntegrationTestTrait;
use Cake\TestSuite\TestCase;

class ShowLogsPathCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->useCommandRunner();
    }

    /**
     * Basic help test
     */
    public function testShowLogsPathCommandHelp()
    {
        $this->exec('passbolt show_logs_path -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Shows error logs path for the current environment.');
        $this->assertOutputContains('cake passbolt show_logs_path');
    }

    /**
     * Basic check with a bit of data.
     */
    public function testShowLogsPathCommand()
    {
        $this->exec('passbolt show_logs_path');
        $this->assertExitSuccess();
        $this->assertOutputContains(LOGS . 'error.log');
    }
}

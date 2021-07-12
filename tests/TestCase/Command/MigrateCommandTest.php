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

use App\Command\MigrateCommand;
use App\Test\Lib\Utility\PassboltCommandTestTrait;
use Cake\TestSuite\ConsoleIntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Command\MigrateCommand Test Case
 *
 * @uses \App\Command\MigrateCommand
 */
class MigrateCommandTest extends TestCase
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
        MigrateCommand::$isUserRoot = false;
    }

    /**
     * Basic help test
     */
    public function testMigrateCommandHelp()
    {
        $this->exec('passbolt migrate -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Migration shell for the Passbolt application.');
        $this->assertOutputContains('cake passbolt migrate');
    }

    /**
     * @Given I am root
     * @When I run "passbolt migrate"
     * @Then the migrations cannot be run.
     */
    public function testMigrateCommandAsRoot()
    {
        $this->assertCommandCannotBeRunAsRootUser(MigrateCommand::class);
    }

    /**
     * @Given I am not root
     * @When I run "passbolt migrate"
     * @Then the migrations get run without generating the .lock file and the cache gets cleared.
     */
    public function testMigrateCommandAsNonRootWithoutBackup()
    {
        $this->exec('passbolt migrate -q -d test');
        $this->assertExitSuccess();
        $this->assertOutputEmpty();
    }

    /**
     * This will fail because the backup will be written at
     * some unreachable location. Still it is important to run this.
     *
     * @group mysqldump
     */
    public function testMigrateCommandAsNonRootWithBackup()
    {
        $this->exec('passbolt migrate -q --backup -d test');
        $this->assertExitSuccess();
        $this->assertOutputEmpty();
    }
}

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
 * @since         4.2.0
 */
namespace App\Test\TestCase\Command;

use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\TestSuite\TestCase;

class PurgeEmailQueueCommandTest extends TestCase
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
    public function testPurgeEmailQueueCommandHelp()
    {
        $this->exec('passbolt purge_email_queue -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('cake passbolt purge_email_queue');
    }

    /**
     * Basic test
     */
    public function testPurgeEmailQueueCommand()
    {
        $this->exec('passbolt purge_email_queue');
        $this->assertExitSuccess();
        $this->assertOutputContains('Nothing to delete.');
    }
}

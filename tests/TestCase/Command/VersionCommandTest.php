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

use Cake\Core\Configure;
use Cake\TestSuite\ConsoleIntegrationTestTrait;
use Cake\TestSuite\TestCase;

class VersionCommandTest extends TestCase
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
    public function testVersionCommandHelp()
    {
        $this->exec('passbolt version -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Print version number for the passbolt application.');
        $this->assertOutputContains('cake passbolt version');
    }

    /**
     * Basic test
     */
    public function testVersionCommand()
    {
        $this->exec('passbolt version');
        $this->assertExitSuccess();
        $this->assertOutputContains(Configure::read('passbolt.version') . "\n" . 'Cakephp ' . Configure::version());
    }
}

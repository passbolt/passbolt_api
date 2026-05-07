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
 * @since         5.12.0
 */
namespace App\Test\TestCase\Command;

use App\Test\Lib\AppIntegrationTestCase;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;

class SubscriptionCheckCommandTest extends AppIntegrationTestCase
{
    use ConsoleIntegrationTestTrait;

    /**
     * Basic help test
     */
    public function testSubscriptionCheckCommandHelp()
    {
        $this->exec('passbolt subscription_check -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Check the subscription.');
    }

    public function testSubscriptionCheckCommandAlias()
    {
        $this->exec('passbolt license_check -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Check the subscription.');
    }

    /**
     * Basic test
     */
    public function testVersionCommand()
    {
        $this->exec('passbolt subscription_check');
        $this->assertExitSuccess();
    }
}

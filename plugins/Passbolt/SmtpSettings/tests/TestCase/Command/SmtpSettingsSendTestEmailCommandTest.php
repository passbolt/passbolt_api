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
 * @since         3.8.0
 */
namespace Passbolt\SmtpSettings\Test\TestCase\Command;

use Cake\TestSuite\ConsoleIntegrationTestTrait;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\SmtpSettings\Test\Lib\SmtpSettingsIntegrationTestTrait;
use Passbolt\SmtpSettings\Test\Lib\SmtpSettingsTestTrait;

class SmtpSettingsSendTestEmailCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;
    use SmtpSettingsIntegrationTestTrait;
    use SmtpSettingsTestTrait;
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
    }

    /**
     * Basic test with failing email should display the trace
     */
    public function testSendTestEmailCommandWithFailingToSendTest_Should_Display_Trace()
    {
        $trace = [['cmd' => 'bar']];
        $errorMessage = 'Error message bar';
        $this->mockSmtpSettingsSendTestEmailServiceWithTrace($trace, $errorMessage);

        $this->exec('passbolt send_test_email -r test@test.test');
        $this->assertExitError();
        $this->assertOutputContains('<info> bar');
        $this->assertOutputContains("<error>Error: $errorMessage");
    }
}

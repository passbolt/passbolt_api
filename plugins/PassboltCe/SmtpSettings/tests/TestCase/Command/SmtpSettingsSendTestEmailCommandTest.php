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

use App\Test\Lib\Utility\EmailTestTrait;
use App\Utility\Application\FeaturePluginAwareTrait;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\SmtpSettings\Test\Factory\SmtpSettingFactory;
use Passbolt\SmtpSettings\Test\Lib\SmtpSettingsIntegrationTestTrait;
use Passbolt\SmtpSettings\Test\Lib\SmtpSettingsTestTrait;

class SmtpSettingsSendTestEmailCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;
    use EmailTestTrait;
    use FeaturePluginAwareTrait;
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
        EventManager::instance()->setEventList(new EventList());
        $this->enableFeaturePlugin('SmtpSettings');
    }

    /**
     * Basic test with failing email should display the trace
     */
    public function testSendTestEmailCommandWithFailingToSendTest_Should_Display_Trace()
    {
        // Insert valid data to pass validation
        $data = $this->getSmtpSettingsData();
        $this->encryptAndPersistSmtpSettings($data);

        $trace = [['cmd' => 'bar']];
        $errorMessage = 'Error message bar';
        $this->mockSmtpSettingsSendTestEmailServiceFail($trace, $errorMessage);

        $this->exec('passbolt send_test_email -r test@test.test');
        $this->assertExitError();
        $this->assertOutputContains('<info> bar');
        $this->assertOutputContains("<error>Error: $errorMessage");
    }

    /**
     * Basic test with invalid SMTP Settings in DB
     */
    public function testSendTestEmailCommandWithFailingToSendTest_With_Invalid_Settings_In_DB_Should_Fail()
    {
        SmtpSettingFactory::make()->persist();
        $this->mockSmtpSettingsSendTestEmailServiceSuccessful();
        $this->exec('passbolt send_test_email -r test@test.test');
        $this->assertOutputContains('<error>The OpenPGP server key cannot be used to decrypt the SMTP settings stored in database. To fix this problem, you need to configure the SMTP server again.');
        $this->assertExitError();
    }

    /**
     * Basic test with valid SMTP Settings in DB
     */
    public function testSendTestEmailCommandWithFailingToSendTest_With_Valid_Settings_In_DB()
    {
        $data = $this->getSmtpSettingsData();
        $this->encryptAndPersistSmtpSettings($data);
        $trace = [['cmd' => 'bar']];
        $this->mockSmtpSettingsSendTestEmailServiceSuccessful($trace);

        $this->exec('passbolt send_test_email -r test@test.test');

        $this->assertExitSuccess();
        $this->assertMailCount(1);
        $this->assertMailSentToAt(0, ['test@test.test' => 'test@test.test']);
        $this->assertOutputContains('<info>Trace</info>');
        $this->assertOutputContains('<info> bar</info>');
    }
}

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

namespace Passbolt\SmtpSettings\Test\TestCase\Controller;

use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Utility\EmailTestTrait;
use Passbolt\SmtpSettings\Service\SmtpSettingsSendTestMailerService;
use Passbolt\SmtpSettings\SmtpSettingsPlugin;
use Passbolt\SmtpSettings\Test\Factory\SmtpSettingFactory;
use Passbolt\SmtpSettings\Test\Lib\SmtpSettingsIntegrationTestTrait;
use Passbolt\SmtpSettings\Test\Lib\SmtpSettingsTestTrait;

/**
 * @covers \Passbolt\SmtpSettings\Controller\SmtpSettingsEmailController
 */
class SmtpSettingsEmailControllerTest extends AppIntegrationTestCase
{
    use EmailTestTrait;
    use SmtpSettingsIntegrationTestTrait;
    use SmtpSettingsTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(SmtpSettingsPlugin::class);
    }

    public function testSmtpSettingsEmailController_Success()
    {
        // Insert some dummy SMTP settings in the DB to ensure that these are ignored by the DebugSmtpTransport
        SmtpSettingFactory::make()->persist();

        $recipient = 'test@test.test';
        $data = $this->getSmtpSettingsData() + [SmtpSettingsSendTestMailerService::EMAIL_TEST_TO => $recipient];
        $this->logInAsAdmin();

        $trace = ['foo' => 'bar'];
        $this->mockSmtpSettingsSendTestEmailServiceSuccessful($trace);

        $this->postJson('/smtp/email.json', $data);

        $this->assertSuccess();
        $this->assertMailCount(1);
        $this->assertMailSentFromAt(0, ['johndoe@passbolt.test' => 'John Doe']);
        $this->assertMailSentToAt(0, [$recipient => $recipient]);
        $this->assertMailCount(1);
        $this->assertMailContainsAt(0, 'Congratulations!');
        $this->assertMailContainsAt(
            0,
            'If you receive this email, it means that your passbolt smtp configuration is working fine.'
        );
        $debug = $trace;
        $response = $this->getResponseBodyAsArray();
        $this->assertSame(compact('debug'), $response);
    }

    public function testSmtpSettingsEmailController_Email_Error()
    {
        $this->logInAsAdmin();
        $trace = ['foo' => 'bar'];
        $this->mockSmtpSettingsSendTestEmailServiceFail($trace);

        $this->postJson('/smtp/email.json', []);
        $this->assertError();
        $this->assertMailCount(0);
        $this->assertSame('bar', $this->_responseJsonBody->debug->foo);
    }

    public function testSmtpSettingsEmailController_Invalid_Data()
    {
        $data = $this->getSmtpSettingsData();
        $this->logInAsAdmin();

        $this->postJson('/smtp/email.json', $data);
        $this->assertError();
        $this->assertMailCount(0);
        $this->assertSame(
            'A test recipient is required.',
            $this->_responseJsonBody->email_test_to->_required
        );
    }

    public function testSmtpSettingsEmailController_Not_Admin_Should_Have_No_Access()
    {
        $this->logInAsUser();
        $this->getJson('/smtp/settings.json');
        $this->assertForbiddenError('Access restricted to administrators.');
    }

    public function testSmtpSettingsEmailController_Guest_Should_Have_No_Access()
    {
        $this->getJson('/smtp/settings.json');
        $this->assertAuthenticationError();
    }

    public function testSmtpSettingsEmailController_Should_Be_Forbidden_If_Security_Enabled()
    {
        $this->disableSmtpSettingsEndpoints();

        $this->postJson('/smtp/email.json');
        $this->assertForbiddenError('SMTP settings endpoints disabled.');

        $this->enableSmtpSettingsEndpoints();
    }
}

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
 * @since         2.5.0
 */
namespace Passbolt\WebInstaller\Test\TestCase\Controller;

use App\Test\Lib\Utility\EmailTestTrait;
use Passbolt\SmtpSettings\Test\Lib\SmtpSettingsIntegrationTestTrait;
use Passbolt\WebInstaller\Test\Lib\WebInstallerIntegrationTestCase;

/**
 * @covers \Passbolt\WebInstaller\Controller\EmailController
 */
class EmailControllerTest extends WebInstallerIntegrationTestCase
{
    use EmailTestTrait;
    use SmtpSettingsIntegrationTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->mockPassboltIsNotconfigured();
        $this->initWebInstallerSession();
    }

    public function testWebInstallerEmailViewSuccess()
    {
        $this->get('/install/email');
        $data = $this->_getBodyAsString();
        $this->assertResponseOk();
        $this->assertStringContainsString('Email configuration', $data);
    }

    public function testWebInstallerEmailPostSuccess()
    {
        $postData = [
            'sender_name' => 'Passbolt Test',
            'sender_email' => 'test@passbolt.com',
            'host' => 'unreachable_host',
            'tls' => true,
            'port' => 123,
            'authentication_method' => 'username_and_password',
            'username' => 'test@passbolt.com',
            'password' => 'password',
        ];

        $this->post('/install/email', $postData);
        $this->assertRedirectContains('install/account_creation');
        $this->assertMailCount(0);
    }

    public function testWebInstallerEmailPostTestEmailSuccess()
    {
        $recipient = 'receiver@passbolt.com';
        $postData = [
            'sender_name' => 'Passbolt Test',
            'sender_email' => 'sender@passbolt.com',
            'host' => 'unreachable_host',
            'tls' => true,
            'port' => 123,
            'authentication_method' => 'username_and_password',
            'username' => $recipient,
            'password' => 'password',
            'send_test_email' => true,
            'email_test_to' => $recipient,
        ];

        $this->post('/install/email', $postData);

        $this->assertResponseOk();
        $this->assertResponseContains('The test email has been sent successfully!');
        $this->assertMailSentFromAt(0, ['sender@passbolt.com' => 'Passbolt Test']);
        $this->assertMailSentToAt(0, [$recipient => $recipient]);
        $this->assertMailCount(1);
        $this->assertMailContainsAt(0, 'Congratulations!');
        $this->assertMailContainsAt(
            0,
            'If you receive this email, it means that your passbolt smtp configuration is working fine.'
        );
    }

    public function testWebInstallerEmailPostSuccess_With_No_Test_Emails()
    {
        $postData = [
            'sender_name' => 'Passbolt Test',
            'sender_email' => 'test@passbolt.com',
            'host' => 'unreachable_host',
            'tls' => true,
            'port' => 123,
            'authentication_method' => 'username_and_password',
            'username' => 'test@passbolt.com',
            'password' => 'password',
            'email_test_to' => '',
        ];

        $this->post('/install/email', $postData);
        $this->assertRedirect('/install/account_creation');
        $this->assertMailCount(0);
    }

    public function testWebInstallerEmailPostError_InvalidData()
    {
        $postData = [
            'sender_name' => 'Passbolt Test',
            'sender_email' => 'test@passbolt.com',
            'host' => 'unreachable_host',
            'tls' => true,
            'port' => 'invalid-port',
            'username' => 'test@passbolt.com',
            'password' => 'password',
        ];
        $this->post('/install/email', $postData);
        $data = $this->_getBodyAsString();
        $this->assertResponseOk();
        $this->assertStringContainsString('The data entered are not correct', $data);
        $this->assertMailCount(0);
    }

    public function testWebInstallerEmailPostError_CannotSendTestEmail()
    {
        $postData = [
            'sender_name' => 'Passbolt Test',
            'sender_email' => 'test@passbolt.com',
            'host' => 'unreachable_host',
            'tls' => true,
            'port' => 587,
            'authentication_method' => 'username_and_password',
            'username' => 'test@passbolt.com',
            'password' => 'password',
            'send_test_email' => true,
            'email_test_to' => 'test@passbolt.com',
        ];
        $trace = [['cmd' => 'bar']];
        $errorMessage = 'Error message';
        $this->mockSmtpSettingsSendTestEmailServiceFail($trace, $errorMessage);

        $this->post('/install/email', $postData);
        $data = $this->_getBodyAsString();
        $this->assertResponseOk();
        $this->assertResponseContains('Email could not be sent:');
        $this->assertResponseContains($errorMessage, $data);
        $this->assertResponseContains('bar', $data);
        $this->assertStringContainsString('Enter your SMTP server settings.', $data);
        $this->assertMailCount(0);
    }
}

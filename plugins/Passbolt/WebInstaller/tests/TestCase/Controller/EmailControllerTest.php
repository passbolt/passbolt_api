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

use Cake\Mailer\TransportFactory;
use Cake\TestSuite\EmailTrait;
use Passbolt\WebInstaller\Controller\EmailController;
use Passbolt\WebInstaller\Test\Lib\WebInstallerIntegrationTestCase;

class EmailControllerTest extends WebInstallerIntegrationTestCase
{
    use EmailTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->mockPassboltIsNotconfigured();
        $this->initWebInstallerSession();
    }

    public function tearDown(): void
    {
        TransportFactory::drop(EmailController::TRANSPORT_CONFIG_NAME);
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
            'username' => 'test@passbolt.com',
            'password' => 'password',
            'send_test_email' => true,
            'email_test_to' => 'receiver@passbolt.test',
        ];

        $this->post('/install/email', $postData);
        $this->assertResponseOk();
        $this->assertMailSentFrom('test@passbolt.com');
        $this->assertMailSentTo('receiver@passbolt.test');
        $this->assertMailCount(1);
        $this->assertMailContains('Congratulations!');
        $this->assertMailContains(
            'If you receive this email, it means that your passbolt smtp configuration is working fine.'
        );
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
            'username' => 'test@passbolt.com',
            'password' => 'password',
            'send_test_email' => true,
            'email_test_to' => 'test@passbolt.com',
        ];
        $this->post('/install/email', $postData);
        $data = $this->_getBodyAsString();
        $this->assertResponseOk();
        $this->assertStringContainsString('Enter your SMTP server settings.', $data);
        $this->assertMailCount(1);
    }
}

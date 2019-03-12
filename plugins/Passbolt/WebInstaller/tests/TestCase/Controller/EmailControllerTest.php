<?php
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

use App\Utility\Healthchecks;
use Cake\Core\Configure;
use Passbolt\WebInstaller\Test\Lib\WebInstallerIntegrationTestCase;

class EmailControllerTest extends WebInstallerIntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->mockPassboltIsNotconfigured();
        $this->initWebInstallerSession();
    }

    public function testWebInstallerEmailViewSuccess()
    {
        $this->get('/install/email');
        $data = ($this->_getBodyAsString());
        $this->assertResponseOk();
        $this->assertContains('Email configuration', $data);
    }

    public function testWebInstallerEmailPostSuccess()
    {
        // Problem how to test valid config in the test without
        // leaking credentials :)
        $this->markTestIncomplete();
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
            'password' => 'password'
        ];
        $this->post('/install/email', $postData);
        $data = ($this->_getBodyAsString());
        $this->assertResponseOk();
        $this->assertContains('The data entered are not correct', $data);
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
        $data = ($this->_getBodyAsString());
        $this->assertResponseOk();
        $this->assertContains('Email could not be sent', $data);
    }
}

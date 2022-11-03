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

namespace Passbolt\SmtpSettings\Test\TestCase\Service;

use App\Error\Exception\FormValidationException;
use Cake\Mailer\TransportFactory;
use Cake\TestSuite\EmailTrait;
use Cake\TestSuite\TestCase;
use Passbolt\SmtpSettings\Service\SmtpSettingsSendTestEmailService;
use Passbolt\SmtpSettings\Test\Lib\SmtpSettingsTestTrait;

/**
 * @covers \Passbolt\SmtpSettings\Service\SmtpSettingsSendTestEmailService
 */
class SmtpSettingsSendTestEmailServiceTest extends TestCase
{
    use EmailTrait;
    use SmtpSettingsTestTrait;

    /**
     * @var SmtpSettingsSendTestEmailService
     */
    protected $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new SmtpSettingsSendTestEmailService();
        $this->loadPlugins(['Passbolt/SmtpSettings' => []]);
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testSmtpSettingsSendTestEmailService_Valid()
    {
        $recipient = 'test@test.test';
        $data = $this->getSmtpSettingsData() + [$this->service::EMAIL_TEST_TO => $recipient];
        $this->service->sendTestEmail($data);
        $this->assertMailSentFrom('johndoe@passbolt.test');
        $this->assertMailSentTo($recipient);
        $this->assertMailCount(1);
        $this->assertMailContains('Congratulations!');
        $this->assertMailContains(
            'If you receive this email, it means that your passbolt smtp configuration is working fine.'
        );
    }

    public function testSmtpSettingsSendTestEmailService_Email_Test_To_Missing()
    {
        $data = $this->getSmtpSettingsData();
        $this->expectException(FormValidationException::class);
        $this->service->sendTestEmail($data);
    }

    public function testSmtpSettingsSendTestEmailService_Email_Test_To_Not_Email()
    {
        $data = $this->getSmtpSettingsData() + ['email_test_to' => 'foo'];
        $this->expectException(FormValidationException::class);
        $this->service->sendTestEmail($data);
    }

    public function testSmtpSettingsSendTestEmailService_NoSmtpTransport_Should_Throw_Exception()
    {
        $oldConfig = $newConfig = TransportFactory::getConfig('default');
        $newConfig['className'] = 'notSmtp';
        TransportFactory::drop('default');
        TransportFactory::setConfig('default', $newConfig);

        $recipient = 'test@test.test';
        $data = $this->getSmtpSettingsData() + [$this->service::EMAIL_TEST_TO => $recipient];

        $passed = false;
        try {
            $this->service->sendTestEmail($data);
        } catch (\BadMethodCallException $e) {
            $passed = true;
        }

        TransportFactory::drop('default');
        TransportFactory::setConfig('default', $oldConfig);
        $this->assertSame(true, $passed, 'This test should throw a \BadMethodCallException');
    }

    public function testSmtpSettingsSendTestEmailService_GetTrace_On_No_Email_Initiated()
    {
        $this->assertSame([], $this->service->getTrace());
    }
}

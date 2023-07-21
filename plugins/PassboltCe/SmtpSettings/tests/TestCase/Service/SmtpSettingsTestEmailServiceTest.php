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
use App\Test\Lib\Utility\EmailTestTrait;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Cake\Mailer\TransportFactory;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\SmtpSettings\Service\SmtpSettingsSendTestMailerService;
use Passbolt\SmtpSettings\Service\SmtpSettingsTestEmailService;
use Passbolt\SmtpSettings\Test\Factory\SmtpSettingFactory;
use Passbolt\SmtpSettings\Test\Lib\SmtpSettingsTestTrait;

/**
 * @covers \Passbolt\SmtpSettings\Service\SmtpSettingsTestEmailService
 */
class SmtpSettingsTestEmailServiceTest extends TestCase
{
    use EmailTestTrait;
    use SmtpSettingsTestTrait;
    use TruncateDirtyTables;

    /**
     * @var \Passbolt\SmtpSettings\Service\SmtpSettingsTestEmailService
     */
    protected $service;

    public function setUp(): void
    {
        parent::setUp();

        EventManager::instance()->setEventList(new EventList());
        $this->clearPlugins();
        $this->service = new SmtpSettingsTestEmailService(new SmtpSettingsSendTestMailerService());
    }

    public function tearDown(): void
    {
        unset($this->service);

        parent::tearDown();
    }

    public function testSmtpSettingsSendTestEmailService_Valid_With_InvalidSettings_In_DB()
    {
        $this->loadPlugins(['Passbolt/SmtpSettings' => []]);
        // Insert some dummy SMTP settings in the DB to ensure that these are ignored by the DebugSmtpTransport
        SmtpSettingFactory::make()->persist();

        $recipient = 'test@test.test';
        $senderEmailInPayload = 'inPayload@test.test';
        $senderNameInPayload = 'inPayload';
        $senderInPayload = [$senderEmailInPayload => $senderNameInPayload];
        $configPassedToService = $this->getSmtpSettingsData();
        $configPassedToService[SmtpSettingsSendTestMailerService::EMAIL_TEST_TO] = $recipient;
        $configPassedToService['sender_email'] = $senderEmailInPayload;
        $configPassedToService['sender_name'] = $senderNameInPayload;

        $this->service->sendTestEmail($configPassedToService);

        $this->assertMailCount(1);
        $this->assertMailSentFromAt(0, $senderInPayload);
        $this->assertMailSentToAt(0, [$recipient => $recipient]);
        $this->assertMailCount(1);
        $this->assertMailContainsAt(0, 'Congratulations!');
        $this->assertMailContainsAt(
            0,
            'If you receive this email, it means that your passbolt smtp configuration is working fine.'
        );
    }

    public function testSmtpSettingsSendTestEmailService_TLS_String_True_Should_Map_To_Boolean_True()
    {
        $recipient = 'test@test.test';
        $data = $this->getSmtpSettingsData('tls', 'true') + [SmtpSettingsSendTestMailerService::EMAIL_TEST_TO => $recipient];
        $settings = $this->service->validateAndGetSmtpSettings($data);
        $this->assertSame(true, $settings['tls']);
    }

    public function testSmtpSettingsSendTestEmailService_Email_Test_To_Missing()
    {
        $data = $this->getSmtpSettingsData();
        $this->expectException(FormValidationException::class);
        $this->service->sendTestEmail($data);
    }

    public function testSmtpSettingsSendTestEmailService_Sender_Email_Not_Valid_Should_Fail()
    {
        $data = $this->getSmtpSettingsData('sender_email', 'foo@test') + ['email_test_to' => 'bar@test.test'];
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
        $data = $this->getSmtpSettingsData() + [SmtpSettingsSendTestMailerService::EMAIL_TEST_TO => $recipient];

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

    public function testSmtpSettingsSendTestEmailService_GetTrace_MasksSensitiveDetails()
    {
        $smtpUsername = 'ada';
        $smtpPassword = 'secret';
        $smtpSettings = [
            'sender_name' => 'Passbolt test',
            'sender_email' => 'test@passbolt.test',
            'host' => 'localhost',
            'username' => $smtpUsername,
            'password' => $smtpPassword,
            'tls' => null,
            'client' => null,
            'port' => 1,
            'email_test_to' => 'test@passbolt.test',
        ];
        // Mock mailer service
        $mailerService = $this->createMock(SmtpSettingsSendTestMailerService::class);
        $mailerService->expects($this->once())->method('sendEmail')->with($smtpSettings);
        // Mock trace
        $cmd = sprintf(
            'AUTH PLAIN %s',
            base64_encode(chr(0) . $smtpUsername . chr(0) . $smtpPassword)
        );
        $trace = [
            ['cmd' => $cmd],
            ['response' => [['code' => '235', 'message' => 'Authentication successful']]],
        ];
        $mailerService->expects($this->once())->method('getTrace')->willReturn($trace);

        $sut = new SmtpSettingsTestEmailService($mailerService);
        $sut->sendTestEmail($smtpSettings);
        $result = $sut->getTrace();

        $this->assertSame('AUTH PLAIN *****', $result[0]['cmd']);
    }
}

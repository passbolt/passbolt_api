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

use App\Mailer\Transport\DebugTransport;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\EmailTestTrait;
use App\Utility\UuidFactory;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\Mailer\Mailer;
use Cake\Mailer\TransportFactory;
use Passbolt\SmtpSettings\Service\SmtpSettingsGetService;
use Passbolt\SmtpSettings\Service\SmtpSettingsSendTestMailerService;
use Passbolt\SmtpSettings\Service\SmtpSettingsTestEmailService;
use Passbolt\SmtpSettings\Test\Lib\SmtpSettingsIntegrationTestTrait;

/**
 * @covers \App\Command\SendTestEmailCommand
 */
class SendTestEmailCommandTest extends AppTestCase
{
    use ConsoleIntegrationTestTrait;
    use EmailTestTrait;
    use SmtpSettingsIntegrationTestTrait;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        // Reset state
        $defaultConfig = [
            'className' => DebugTransport::class,
            'host' => 'unreachable_host.dev',
            'port' => 123,
            'timeout' => 30,
            'username' => 'foo',
            'password' => 'bar',
            'client' => null,
            'tls' => true,
        ];
        TransportFactory::drop('default');
        TransportFactory::setConfig('default', $defaultConfig);

        parent::tearDown();
    }

    /**
     * Basic help test
     */
    public function testSendTestEmailCommandHelp()
    {
        $this->exec('passbolt send_test_email -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Try to send a test email and display debug information.');
        $this->assertOutputContains('cake passbolt send_test_email');
    }

    /**
     * Basic test without recipient should fail.
     */
    public function testSendTestEmailCommandWithoutRecipient()
    {
        $this->exec('passbolt send_test_email');

        $this->assertExitError();
        $this->assertErrorContains('The `recipient` option is required and has no default value');
    }

    /**
     * Basic test with recipient
     */
    public function testSendTestEmailCommandWithRecipient()
    {
        $config = TransportFactory::getConfig('default');
        $base64encodedString = base64_encode(
            chr(0) . $config['username'] . chr(0) . $config['password']
        );
        $trace = [['cmd' => 'Password: ' . $base64encodedString]];
        $recipient = 'test@passbolt.test';
        $this->mockService(SmtpSettingsSendTestMailerService::class, function () use ($trace) {
            $service = $this->getMockBuilder(SmtpSettingsSendTestMailerService::class)
                ->onlyMethods(['getTrace'])
                ->getMock();
            $service->method('getTrace')->willReturn($trace);

            return $service;
        });

        $this->exec('passbolt send_test_email -r ' . $recipient);

        $this->assertExitSuccess();
        $this->assertOutputContains('<info>Trace</info>');
        $this->assertOutputContains('<info> Password: *****</info>');
        $this->assertMailSentToAt(0, [$recipient => $recipient]);
        $this->assertMailSubjectContainsAt(0, 'Passbolt test email');
        $this->assertMailCount(1);
    }

    public function testSendTestEmailCommandWithRecipient_Oauth2ExchangeOnline(): void
    {
        // Set oauth2 related fields in transport for testing
        $config = array_merge(TransportFactory::getConfig('default'), [
            'tenant_id' => UuidFactory::uuid(),
            'client_id' => UuidFactory::uuid(),
            'client_secret' => 'secret',
            'oauth_username' => 'test@example.com',
        ]);
        TransportFactory::drop('default');
        TransportFactory::setConfig('default', $config);

        $recipient = 'test@passbolt.test';
        $this->mockService(SmtpSettingsTestEmailService::class, function () use ($recipient) {
            $trace = [['cmd' => 'AUTH XOAUTH2 *****']];

            // Build expected config the same way the command does
            $expectedConfig = (new SmtpSettingsGetService())->getSettings();
            $expectedConfig[SmtpSettingsSendTestMailerService::EMAIL_TEST_TO] = $recipient;

            $service = $this->getMockBuilder(SmtpSettingsTestEmailService::class)
                ->onlyMethods(['sendTestEmail', 'getTrace'])
                ->disableOriginalConstructor()
                ->getMock();
            $service->expects($this->once())->method('sendTestEmail')
                ->with($expectedConfig)
                ->willReturnCallback(function ($config) {
                    // Send email via DebugTransport to satisfy mail assertions (bypasses OAuth2 token fetch)
                    $email = new Mailer(['transport' => TransportFactory::get('default')]);
                    $email->setFrom([$config['sender_email'] => $config['sender_name']])
                        ->setTo($config[SmtpSettingsSendTestMailerService::EMAIL_TEST_TO])
                        ->setSubject(__('Passbolt test email'))
                        ->deliver('Test');
                });
            $service->method('getTrace')->willReturn($trace);

            return $service;
        });

        $this->exec('passbolt send_test_email -r ' . $recipient);

        $this->assertExitSuccess();
        $this->assertOutputContains('<info>Email configuration</info>');
        $this->assertOutputContains('Tenant ID: ');
        $this->assertOutputContains('Client ID: ');
        $this->assertOutputContains('Client Secret: *********');
        $this->assertOutputContains('Username: ');
        $this->assertOutputContains('<info>Trace</info>');
        $this->assertOutputContains('<info> AUTH XOAUTH2 *****</info>');
        $this->assertMailSentToAt(0, [$recipient => $recipient]);
        $this->assertMailSubjectContainsAt(0, 'Passbolt test email');
        $this->assertMailCount(1);
    }

    /**
     * Basic test with invalid recipient
     */
    public function testSendTestEmailCommandWithInvalidRecipient()
    {
        $recipient = 'this is not a valid recipient';
        $this->exec('passbolt send_test_email -r ' . $recipient);
        $this->assertExitError();
        $this->assertOutputContains('The recipient should be a valid email address.');
    }

    /**
     * Basic test with non Smtp config will fail
     */
    public function testSendTestEmailCommandWithConfigNotSmtp()
    {
        $config = TransportFactory::getConfig('default');
        $config['className'] = 'notSmtp';
        TransportFactory::drop('default');
        TransportFactory::setConfig('default', $config);

        $this->exec('passbolt send_test_email -r test@passbolt.test');

        $this->assertExitError();
        $this->assertOutputContains('Your email transport configuration is not set to use "Smtp"');
    }
}

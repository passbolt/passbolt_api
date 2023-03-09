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
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\Mailer\TransportFactory;
use Passbolt\SmtpSettings\Test\Lib\SmtpSettingsIntegrationTestTrait;

class SendTestEmailCommandTest extends AppTestCase
{
    use ConsoleIntegrationTestTrait;
    use EmailTestTrait;
    use SmtpSettingsIntegrationTestTrait;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->useCommandRunner();
        $config = [
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
        TransportFactory::setConfig('default', $config);
    }

    /**
     * Basic help test
     */
    public function testSendTestEmailCommandHelp()
    {
        $this->exec('passbolt send_test_email -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Debug Email shell for the passbolt application.');
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
        $trace = [['cmd' => 'bar']];
        $this->mockSmtpSettingsSendTestEmailServiceSuccessful($trace);
        $recipient = 'test@passbolt.test';
        $this->exec('passbolt send_test_email -r ' . $recipient);
        $this->assertExitSuccess();
        $this->assertOutputContains('<info>Trace</info>');
        $this->assertOutputContains('<info> *****</info>');
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

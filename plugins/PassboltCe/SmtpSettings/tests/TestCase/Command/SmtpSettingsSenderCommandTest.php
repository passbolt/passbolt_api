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
 * @since         3.11.0
 */
namespace Passbolt\SmtpSettings\Test\TestCase\Command;

use App\Mailer\Transport\SmtpTransport;
use App\Test\Lib\Utility\EmailTestTrait;
use App\Utility\Application\FeaturePluginAwareTrait;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Cake\Mailer\Mailer;
use Cake\Mailer\TransportFactory;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\EmailDigest\Test\Factory\EmailQueueFactory;
use Passbolt\SmtpSettings\Test\Lib\SmtpSettingsTestTrait;

/**
 * @uses \EmailQueue\Shell\SenderShell
 */
class SmtpSettingsSenderCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;
    use EmailTestTrait;
    use FeaturePluginAwareTrait;
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
        $this->clearPlugins();

        $this->useCommandRunner();
        EventManager::instance()->setEventList(new EventList());
    }

    public function testSmtpSettingsSenderCommand_Success_Path_On_DB_Settings()
    {
        $this->enableFeaturePlugin('SmtpSettings');
        $senderEmail = 'phpunit@passbolt.com';
        $senderName = 'phpunit';
        $smtpSettingsInDB = $this->getSmtpSettingsData();
        $smtpSettingsInDB['sender_email'] = $senderEmail;
        $smtpSettingsInDB['sender_name'] = $senderName;
        $sender = [$senderEmail => $senderName];
        $this->encryptAndPersistSmtpSettings($smtpSettingsInDB);

        $nMails = 2;
        EmailQueueFactory::make($nMails)->persist();
        $mails = EmailQueueFactory::find()->orderAsc('created');

        $this->exec('sender');
        $this->assertExitSuccess();

        $this->assertTransportConfigMatches($smtpSettingsInDB);
        $this->assertEventFired(SmtpTransport::SMTP_TRANSPORT_INITIALIZE_EVENT);
        $this->assertEventFired(SmtpTransport::SMTP_TRANSPORT_BEFORE_SEND_EVENT);

        foreach ($mails as $i => $mail) {
            $sentMessage = $this->getSentMessages()[$i];
            $this->assertSame([$mail->get('email') => $mail->get('email')], $sentMessage->getTo());
            $this->assertSame($mail->get('subject'), $sentMessage->getSubject());
            $this->assertSame($sender, $sentMessage->getFrom());
            $this->assertSame($sender, $sentMessage->getSender());
            $this->assertSame($sender, $sentMessage->getReturnPath());
        }
    }

    public function testSmtpSettingsSenderCommand_Success_Path_On_File_Settings()
    {
        // Read the sender in the config files
        $sender = Mailer::getConfig('default')['from'];
        $fileConfig = TransportFactory::get('default')->getConfig();

        $nMails = 3;
        EmailQueueFactory::make($nMails)->persist();
        $mails = EmailQueueFactory::find()->orderAsc('created');

        $this->exec('sender');
        $this->assertExitSuccess();

        $this->assertTransportConfigMatches($fileConfig);
        $this->assertEventFired(SmtpTransport::SMTP_TRANSPORT_INITIALIZE_EVENT);
        $this->assertEventFired(SmtpTransport::SMTP_TRANSPORT_BEFORE_SEND_EVENT);

        foreach ($mails as $i => $mail) {
            $sentMessage = $this->getSentMessages()[$i];
            $this->assertSame([$mail->get('email') => $mail->get('email')], $sentMessage->getTo());
            $this->assertSame($mail->get('subject'), $sentMessage->getSubject());
            $this->assertSame($sender, $sentMessage->getFrom());
            $this->assertSame($sender, $sentMessage->getReturnPath());
        }
    }

    public function testSmtpSettingsSenderCommand_Plugin_Not_Loaded_Rely_On_File_Settings()
    {
        $this->disableFeaturePlugin('SmtpSettings');
        $senderEmail = 'phpunit@passbolt.com';
        $senderName = 'phpunit';
        $settingsInDB = $this->getSmtpSettingsData();
        $settingsInDB['sender_email'] = $senderEmail;
        $settingsInDB['sender_name'] = $senderName;
        $this->encryptAndPersistSmtpSettings($settingsInDB);

        // Read the sender in the config files
        $sender = Mailer::getConfig('default')['from'];
        $fileConfig = TransportFactory::get('default')->getConfig();

        $nMails = 3;
        EmailQueueFactory::make($nMails)->persist();
        $mails = EmailQueueFactory::find()->orderAsc('created');

        $this->exec('sender');
        $this->assertExitSuccess();

        $this->assertTransportConfigMatches($fileConfig);
        $this->assertEventFired(SmtpTransport::SMTP_TRANSPORT_INITIALIZE_EVENT);
        $this->assertEventFired(SmtpTransport::SMTP_TRANSPORT_BEFORE_SEND_EVENT);

        foreach ($mails as $i => $mail) {
            $sentMessage = $this->getSentMessages()[$i];
            $this->assertSame([$mail->get('email') => $mail->get('email')], $sentMessage->getTo());
            $this->assertSame($mail->get('subject'), $sentMessage->getSubject());
            $this->assertSame($sender, $sentMessage->getFrom());
            $this->assertSame($sender, $sentMessage->getReturnPath());
        }
    }
}

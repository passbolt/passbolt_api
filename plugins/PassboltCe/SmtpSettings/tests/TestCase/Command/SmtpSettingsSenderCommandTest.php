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

use App\Utility\Application\FeaturePluginAwareTrait;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Cake\Mailer\Mailer;
use Cake\TestSuite\ConsoleIntegrationTestTrait;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\EmailDigest\Test\Factory\EmailQueueFactory;
use Passbolt\SmtpSettings\Mailer\Transport\SmtpTransport;
use Passbolt\SmtpSettings\Test\Lib\SmtpSettingsTestTrait;

/**
 * @uses \EmailQueue\Shell\SenderShell
 */
class SmtpSettingsSenderCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;
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
        $this->setPassboltDebugSmtpTransport();
    }

    public function testSmtpSettingsSenderCommand_Success_Path_On_DB_Settings()
    {
        $this->enableFeaturePlugin('SmtpSettings');
        $senderEmail = 'phpunit@passbolt.com';
        $senderName = 'phpunit';
        $data = $this->getSmtpSettingsData();
        $data['sender_email'] = $senderEmail;
        $data['sender_name'] = $senderName;
        $sender = [$senderEmail => $senderName];
        $this->encryptAndPersistSmtpSettings($data);

        $nMails = 3;
        EmailQueueFactory::make($nMails)->persist();
        $mails = EmailQueueFactory::find()->orderAsc('created');

        $this->exec('sender');
        $this->assertExitSuccess();

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

        $nMails = 3;
        EmailQueueFactory::make($nMails)->persist();
        $mails = EmailQueueFactory::find()->orderAsc('created');

        $this->exec('sender');
        $this->assertExitSuccess();

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
        $data = $this->getSmtpSettingsData();
        $data['sender_email'] = $senderEmail;
        $data['sender_name'] = $senderName;
        $this->encryptAndPersistSmtpSettings($data);

        // Read the sender in the config files
        $sender = Mailer::getConfig('default')['from'];

        $nMails = 3;
        EmailQueueFactory::make($nMails)->persist();
        $mails = EmailQueueFactory::find()->orderAsc('created');

        $this->exec('sender');
        $this->assertExitSuccess();

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

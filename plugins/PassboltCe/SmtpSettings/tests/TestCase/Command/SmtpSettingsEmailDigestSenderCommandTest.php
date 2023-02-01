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
namespace Passbolt\SmtpSettings\Test\TestCase\Command;

use App\Mailer\Transport\SmtpTransport;
use App\Test\Lib\Utility\EmailTestTrait;
use App\Utility\Application\FeaturePluginAwareTrait;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Cake\TestSuite\ConsoleIntegrationTestTrait;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\EmailDigest\Test\Factory\EmailQueueFactory;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettings;
use Passbolt\SmtpSettings\Test\Lib\SmtpSettingsTestTrait;

/**
 * @uses \Passbolt\EmailDigest\Command\SenderCommand
 */
class SmtpSettingsEmailDigestSenderCommandTest extends TestCase
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
        $this->useCommandRunner();
        $this->loadPlugins(['Passbolt/EmailDigest' => []]);
        EmailNotificationSettings::flushCache();
        $this->enableFeaturePlugin('SmtpSettings');
        EventManager::instance()->setEventList(new EventList());
    }

    /**
     * Basic Sender test.
     */
    public function testSmtpSettingsEmailDigestSenderCommandSender()
    {
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

        $this->exec('passbolt email_digest send');
        $this->assertExitSuccess();

        $this->assertEventFired(SmtpTransport::SMTP_TRANSPORT_BEFORE_SEND_EVENT);

        $this->assertMailCount($nMails);
        foreach ($mails as $i => $mail) {
            $this->assertMailSentFromAt($i, $sender);
            $this->assertMailSentToAt($i, [$mail->get('email') => $mail->get('email')]);
            $this->assertMailContainsAt($i, 'Sending email to: ' . $mail->get('email'));
        }
    }
}

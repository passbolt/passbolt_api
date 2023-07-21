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

use App\Test\Lib\Utility\EmailTestTrait;
use App\Utility\Application\FeaturePluginAwareTrait;
use App\View\Helper\AvatarHelper;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\Core\Configure;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Cake\Mailer\Mailer;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\EmailDigest\Test\Factory\EmailQueueFactory;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettings;
use Passbolt\SmtpSettings\Test\Lib\SmtpSettingsTestTrait;

/**
 * @uses \Passbolt\EmailDigest\Command\PreviewCommand
 */
class SmtpSettingsEmailDigestPreviewCommandTest extends TestCase
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
        $this->clearPlugins();
        $this->loadPlugins(['Passbolt/EmailDigest' => []]);
        EmailNotificationSettings::flushCache();
        EventManager::instance()->setEventList(new EventList());
        $this->enableFeaturePlugin('SmtpSettings');
    }

    /**
     * Basic Preview test.
     */
    public function testSmtpSettingsEmailDigestPreviewCommand_Without_Body_On_DB_Settings(): void
    {
        $senderEmail = 'phpunit@passbolt.com';
        $senderName = 'phpunit';
        $data = $this->getSmtpSettingsData();
        $data['sender_email'] = $senderEmail;
        $data['sender_name'] = $senderName;
        $this->encryptAndPersistSmtpSettings($data);

        $nMails = 2;
        EmailQueueFactory::make($nMails)->persist();
        $mails = EmailQueueFactory::find()->orderAsc('created');

        $this->exec('passbolt email_digest preview');
        $this->assertExitSuccess();

        foreach ($mails as $mail) {
            $this->assertOutputContains("From: {$senderName} <{$senderEmail}>");
            $this->assertOutputContains("Return-Path: {$senderName} <{$senderEmail}>");
            $this->assertOutputContains('To: ' . $mail->get('email'));
            $this->assertOutputContains('Subject: ' . $mail->get('subject'));
        }
    }

    /**
     * Basic Preview test.
     */
    public function testSmtpSettingsEmailDigestPreviewCommand_Without_Body_On_File_Plugin_Disabled(): void
    {
        $this->disableFeaturePlugin('SmtpSettings');
        $dataInDB = $this->getSmtpSettingsData();
        $dataInDB['sender_email'] = 'phpunit@passbolt.com';
        $dataInDB['sender_name'] = 'phpunit';
        $this->encryptAndPersistSmtpSettings($dataInDB);

        $sender = Mailer::getConfig('default')['from'];
        $senderEmail = array_keys($sender)[0];
        $senderName = $sender[$senderEmail];

        $nMails = 2;
        EmailQueueFactory::make($nMails)->persist();
        $mails = EmailQueueFactory::find()->orderAsc('created');

        $this->exec('passbolt email_digest preview');
        $this->assertExitSuccess();

        foreach ($mails as $mail) {
            $this->assertOutputContains("From: $senderName <$senderEmail>");
            $this->assertOutputContains("Return-Path: $senderName <$senderEmail>");
            $this->assertOutputContains('To: ' . $mail->get('email'));
            $this->assertOutputContains('Subject: ' . $mail->get('subject'));
        }
        $this->enableFeaturePlugin('SmtpSettings');
    }

    /**
     * Basic Preview test.
     *
     * @covers \App\Service\Avatars\AvatarsConfigurationService::loadConfiguration
     */
    public function testSmtpSettingsEmailDigestPreviewCommand_With_Body(): void
    {
        // Ensure that avatar image configs are null and
        // will be correctly loaded by the command.
        Configure::delete('FileStorage');
        $senderEmail = 'phpunit@passbolt.com';
        $senderName = 'phpunit';
        $data = $this->getSmtpSettingsData();
        $data['sender_email'] = $senderEmail;
        $data['sender_name'] = $senderName;
        $this->encryptAndPersistSmtpSettings($data);

        $email = EmailQueueFactory::make(['from_email' => 'foo@test.test'])->persist();
        $this->exec('passbolt email_digest preview --body');
        $this->assertExitSuccess();
        $this->assertOutputContains('Sending email to: ' . $email->get('email'));
        $this->assertOutputContains(AvatarHelper::getAvatarFallBackUrl());
    }
}

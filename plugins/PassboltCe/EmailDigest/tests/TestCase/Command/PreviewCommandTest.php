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
namespace Passbolt\EmailDigest\Test\TestCase\Command;

use App\Notification\Email\Redactor\Resource\ResourceCreateEmailRedactor;
use App\Service\Avatars\AvatarsConfigurationService;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Utility\EmailTestTrait;
use App\View\Helper\AvatarHelper;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\Core\Configure;
use Cake\I18n\I18n;
use Cake\Mailer\Mailer;
use Passbolt\EmailDigest\Command\PreviewCommand;
use Passbolt\EmailDigest\EmailDigestPlugin;
use Passbolt\EmailDigest\Test\Factory\EmailQueueFactory;
use Passbolt\EmailDigest\Test\Lib\EmailDigestMockTestTrait;
use Passbolt\Locale\Test\Lib\DummyTranslationTestTrait;

/**
 * @uses \Passbolt\EmailDigest\Command\PreviewCommand
 */
class PreviewCommandTest extends AppIntegrationTestCase
{
    use ConsoleIntegrationTestTrait;
    use DummyTranslationTestTrait;
    use EmailTestTrait;
    use EmailDigestMockTestTrait;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->useCommandRunner();
        $this->loadRoutes();
        $this->setDummyFrenchTranslator();
        (new AvatarsConfigurationService())->loadConfiguration();
        $this->enableFeaturePlugin(EmailDigestPlugin::class);
    }

    /**
     * Basic help test
     */
    public function testPreviewCommandHelp(): void
    {
        $this->exec('passbolt email_digest preview -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Preview a batch of queued emails as emails digests.');
        $this->assertOutputContains('cake passbolt email_digest preview');
    }

    /**
     * Basic Preview test.
     */
    public function testPreviewCommand_Without_Body(): void
    {
        /** @var array $sender */
        $sender = Mailer::getConfig('default')['from'];
        $senderEmail = array_keys($sender)[0];
        $senderName = $sender[$senderEmail];

        $email = EmailQueueFactory::make()->persist();
        $this->exec('passbolt email_digest preview');
        $this->assertExitSuccess();
        $this->assertOutputContains("From: {$senderName} <{$senderEmail}>");
        $this->assertOutputContains("Return-Path: {$senderName} <{$senderEmail}>");
        $this->assertOutputContains('To: ' . $email->get('email'));
        $this->assertOutputContains('Subject: ' . $email->get('subject'));
    }

    /**
     * Basic Preview test with body.
     *
     * @covers \App\Service\Avatars\AvatarsConfigurationService::loadConfiguration
     */
    public function testPreviewCommand_With_Body(): void
    {
        // Ensure that avatar image configs are null and
        // will be correctly loaded by the command.
        Configure::delete('FileStorage');
        $email = EmailQueueFactory::make()->persist();

        $this->exec('passbolt email_digest preview --body');

        $this->assertExitSuccess();
        $this->assertOutputContains('Sending email to: ' . $email->get('email'));
        $this->assertOutputContains(AvatarHelper::getAvatarFallBackUrl());
        // Assert <head> tag is not duplicated/present only once in the preview output
        $emailHtml = $this->_out->output();
        $this->assertMailBodyStringCount(1, '<head>', 0, $emailHtml);
        $this->assertMailBodyStringCount(1, '</head>', 0, $emailHtml);
    }

    /**
     * @Given I create 4 emails to recipient resp. English, French, English and French
     * @When I preview them
     * @Then the local of the emails should match those of the recipients and in the end
     * the locale should be English again.
     */
    public function testPreviewCommandLocale(): void
    {
        $this->loadPlugins(['Passbolt/Locale' => []]);
        $frenchLocale = 'fr-FR';
        /** @var \App\Model\Entity\User $frenchSpeakingUser */
        $frenchSpeakingUser = UserFactory::make()->user()->withLocale($frenchLocale)->persist();

        EmailQueueFactory::make()->listeningToBeforeSave()->persist();
        EmailQueueFactory::make()
            ->listeningToBeforeSave()
            ->setRecipient($frenchSpeakingUser->username)
            ->persist();

        $this->exec('passbolt email_digest preview --body');
        $emailHtml = $this->_out->output();

        $this->assertExitSuccess();
        $this->assertOutputContains($this->getDummyEnglishEmailSentence());
        $this->assertOutputContains($this->getDummyFrenchEmailSentence());
        $this->assertSame(I18n::getDefaultLocale(), I18n::getLocale());
        // assert tags count
        $this->assertMailBodyStringCount(2, '<head>', 0, $emailHtml);
        $this->assertMailBodyStringCount(2, '</head>', 0, $emailHtml);
        // assert email separator
        $this->assertMailBodyStringCount(2, PreviewCommand::EMAIL_SEPARATOR, 0, $emailHtml);
    }

    public function testPreviewCommand_ThresholdExceeded(): void
    {
        (new AvatarsConfigurationService())->loadConfiguration();
        $nResourcesAdded = 15;
        /** @var \App\Model\Entity\User $operator */
        $operator = UserFactory::make()->withAvatar()->persist();
        $recipient = 'john@test.test';
        EmailQueueFactory::make([
            'from_name' => 'No reply',
            'from_email' => 'no-reply@test.test',
        ], $nResourcesAdded)
            ->setRecipient($recipient)
            ->setTemplate(ResourceCreateEmailRedactor::TEMPLATE)
            ->setField('template_vars.body.user', $operator)
            ->setField('template_vars.locale', 'en-UK')
            ->persist();

        $this->exec('passbolt email_digest preview');

        $this->assertExitSuccess();
        $this->assertMailCount(0); // Make sure preview doesn't send emails
        $this->assertOutputContains('From: No reply <no-reply@test.test>');
        $this->assertOutputContains('To: john@test.test');
        $this->assertOutputContains('Subject: ' . h($operator->profile->full_name) . ' has made changes on several resources');
    }
}

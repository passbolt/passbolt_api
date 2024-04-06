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

use App\Notification\DigestTemplate\GroupMembershipDigestTemplate;
use App\Notification\DigestTemplate\ResourceChangesDigestTemplate;
use App\Notification\Email\Redactor\Group\GroupUserAddEmailRedactor;
use App\Notification\Email\Redactor\Resource\ResourceCreateEmailRedactor;
use App\Service\Avatars\AvatarsConfigurationService;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Utility\EmailTestTrait;
use App\Utility\UuidFactory;
use Cake\Chronos\Chronos;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\I18n\I18n;
use Cake\Mailer\Mailer;
use Cake\Network\Exception\SocketException;
use Passbolt\EmailDigest\EmailDigestPlugin;
use Passbolt\EmailDigest\Test\Factory\EmailQueueFactory;
use Passbolt\EmailDigest\Test\Lib\EmailDigestMockTestTrait;
use Passbolt\EmailDigest\Utility\Digest\DigestTemplateRegistry;
use Passbolt\Locale\Test\Lib\DummyTranslationTestTrait;

/**
 * @uses \Passbolt\EmailDigest\Command\SenderCommand
 */
class SenderCommandTest extends AppIntegrationTestCase
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
    public function testSenderCommandHelp()
    {
        $this->exec('passbolt email_digest send -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Sends a batch of queued emails as emails digests.');
        $this->assertOutputContains('cake passbolt email_digest send');
    }

    /**
     * Basic Sender test with SMTP configs from file.
     */
    public function testSenderCommandSender()
    {
        $sender = Mailer::getConfig('default')['from'];
        $email = EmailQueueFactory::make()->persist();

        $this->exec('passbolt email_digest send');

        $this->assertExitSuccess();
        $this->assertMailSentFromAt(0, $sender);
        $this->assertMailSentToAt(0, [$email->get('email') => $email->get('email')]);
        $this->assertMailContainsAt(0, 'Sending email to: ' . $email->get('email'));
        // Assert <head> tag is not duplicated/present only once in the email HTML
        $this->assertMailBodyStringCount(1, '<head>');
        $this->assertMailBodyStringCount(1, '</head>');
    }

    /**
     * @Given I create 4 emails to recipient resp. English, French, English and French
     * @When I send them
     * @Then the local of the emails should match those of the recipients and in the end
     * the locale should be English again.
     */
    public function testSenderCommandLocale()
    {
        $this->loadPlugins(['Passbolt/Locale' => []]);
        $frenchLocale = 'fr-FR';

        /** @var \App\Model\Entity\User $frenchSpeakingUser */
        $frenchSpeakingUser = UserFactory::make()->withLocale($frenchLocale)->persist();

        EmailQueueFactory::make(['created' => Chronos::now()->subDays(4)])->persist();
        EmailQueueFactory::make(['created' => Chronos::now()->subDays(3)])
            ->setRecipient($frenchSpeakingUser->username)
            ->persist();
        EmailQueueFactory::make(['created' => Chronos::now()->subDays(2)])
            ->persist();
        EmailQueueFactory::make(['created' => Chronos::now()->subDays(1)])
            ->setRecipient($frenchSpeakingUser->username)
            ->persist();

        $this->exec('passbolt email_digest send');
        $this->assertExitSuccess();

        $this->assertMailContainsAt(0, $this->getDummyEnglishEmailSentence());
        $this->assertMailContainsAt(1, $this->getDummyFrenchEmailSentence());
        $this->assertMailContainsAt(2, $this->getDummyEnglishEmailSentence());
        $this->assertMailContainsAt(3, $this->getDummyFrenchEmailSentence());
        $this->assertSame(I18n::getDefaultLocale(), I18n::getLocale());
        // Assert <head> tag is not duplicated/present only once in the email HTML
        $this->assertMailBodyStringCount(1, '<head>');
        $this->assertMailBodyStringCount(1, '</head>');
    }

    public function noRowsLockedProvider(): array
    {
        return [
            ['single_email' => 1],
            ['multiple_emails_in_digest' => 2],
            ['threshold_exceeded' => 11],
        ];
    }

    /**
     * @dataProvider noRowsLockedProvider
     */
    public function testSenderCommand_EmailQueueRowsAreUnlockedAfterSuccessfulSend(int $nResourcesAdded)
    {
        $operator = UserFactory::make()->withAvatar()->getEntity();
        $recipient = 'john@test.test';
        EmailQueueFactory::make($nResourcesAdded)
            ->setRecipient($recipient)
            ->setTemplate(ResourceCreateEmailRedactor::TEMPLATE)
            ->setField('template_vars.body.user', $operator)
            ->setField('template_vars.body.resource', [
                // Dummy data to render email without any warnings
                'id' => UuidFactory::uuid(),
                'created' => 'now',
                'name' => 'My pass',
                'secrets' => [['data' => 'foo bar baz']],
            ])
            ->setField('template_vars.body.showUsername', false)
            ->setField('template_vars.body.showUri', false)
            ->setField('template_vars.body.showDescription', false)
            ->setField('template_vars.body.showSecret', false)
            ->persist();

        $this->exec('passbolt email_digest send');

        $this->assertExitSuccess();
        $this->assertMailCount(1);
        $this->assertMailSentToAt(0, [$recipient => $recipient]);
        // Make sure email queue entries are not locked
        $count = EmailQueueFactory::find()->where(['locked' => true])->count();
        $this->assertEquals(0, $count);
        // Make sure email queue entries are sent
        $count = EmailQueueFactory::find()->where(['sent' => true, 'locked' => false])->count();
        $this->assertEquals($nResourcesAdded, $count);
    }

    /**
     * @dataProvider noRowsLockedProvider
     */
    public function testSenderCommand_EmailQueueRowsAreUnlockedAfterFailedSend(int $nResourcesAdded)
    {
        $operator = UserFactory::make()->withAvatar()->getEntity();
        $recipient = 'john@test.test';
        EmailQueueFactory::make($nResourcesAdded)
            ->setRecipient($recipient)
            ->setTemplate(ResourceCreateEmailRedactor::TEMPLATE)
            ->setField('template_vars.body.user', $operator)
            ->setField('template_vars.body.resource', [
                // Dummy data to render email without any warnings
                'id' => UuidFactory::uuid(),
                'created' => 'now',
                'name' => 'My pass',
                'secrets' => [['data' => 'foo bar baz']],
            ])
            ->setField('template_vars.body.showUsername', false)
            ->setField('template_vars.body.showUri', false)
            ->setField('template_vars.body.showDescription', false)
            ->setField('template_vars.body.showSecret', false)
            ->persist();

        $mockedEmailQueue = $this->getMockForModel(
            'EmailQueue.EmailQueue',
            ['success',],
            ['table' => 'email_queue',]
        );
        $mockedEmailQueue
            ->expects($this->once())
            ->method('success')
            ->willThrowException(new SocketException());

        $this->exec('passbolt email_digest send');

        $this->assertExitSuccess();
        $this->assertMailCount(1);
        $this->assertMailSentToAt(0, [$recipient => $recipient]);
        // Make sure email queue entries are not locked
        $count = EmailQueueFactory::find()->where(['locked' => true])->count();
        $this->assertEquals(0, $count);
        // Make sure email queue entries are not sent
        $count = EmailQueueFactory::find()->where(['sent' => false, 'locked' => false])->count();
        $this->assertEquals($nResourcesAdded, $count);
    }

    public function testSenderCommandMultipleDigests()
    {
        $recipient = 'foo@bar.baz';
        $nEmailsSent = 15;
        [$user, $admin] = UserFactory::make(2)->withAvatar()->persist();

        EmailQueueFactory::make($nEmailsSent)
            ->setRecipient($recipient)
            ->setTemplate(ResourceCreateEmailRedactor::TEMPLATE)
            ->setField('template_vars.body.user', $user)
            ->setField('template_vars.locale', 'en-UK')
            ->persist();

        EmailQueueFactory::make($nEmailsSent)
            ->setRecipient($recipient)
            ->setTemplate(GroupUserAddEmailRedactor::TEMPLATE)
            ->setField('template_vars.body.admin', $admin)
            ->setField('template_vars.body.user', $user)
            ->setField('template_vars.locale', 'en-UK')
            ->persist();

        // Upgrade priority of this template to ensure that the emails are sent in this order
        $priorityResourceChange = rand();
        DigestTemplateRegistry::getInstance()->addTemplate(new ResourceChangesDigestTemplate($priorityResourceChange));
        DigestTemplateRegistry::getInstance()->addTemplate(
            new GroupMembershipDigestTemplate($priorityResourceChange + 1)
        );

        $this->exec('passbolt email_digest send');
        $this->assertExitSuccess();

        $sentCount = EmailQueueFactory::find()->where(['sent' => true])->count();
        $this->assertSame($nEmailsSent * 2, $sentCount);

        $this->assertMailCount(2);
        $subject = $user->profile->full_name . ' has made changes on several resources';
        $this->assertMailSubjectContainsAt(0, $subject);
        $this->assertMailContainsAt(0, $nEmailsSent . ' resources were affected.');
        $this->assertMailContainsAt(0, $subject);

        $subject = $user->profile->full_name . ' updated your memberships in several groups';
        $this->assertMailSubjectContainsAt(1, $subject);
        $this->assertMailContainsAt(1, $subject);
        $this->assertMailContainsAt(1, $nEmailsSent . ' group memberships were affected.');
    }

    /**
     * If the email plugin is disabled, fall back on the legacy email queue command
     */
    public function testSenderCommandMultipleDigests_With_Plugin_Disabled()
    {
        $this->disableFeaturePlugin(EmailDigestPlugin::class);

        $nEmailsSent = 15;

        $operator = UserFactory::make()->withAvatar()->getEntity();
        $recipient = 'john@test.test';
        EmailQueueFactory::make($nEmailsSent)
            ->setRecipient($recipient)
            ->setTemplate(ResourceCreateEmailRedactor::TEMPLATE)
            ->setField('template_vars.body.user', $operator)
            ->setField('template_vars.body.resource', [
                // Dummy data to render email without any warnings
                'id' => UuidFactory::uuid(),
                'created' => 'now',
                'name' => 'My pass',
                'secrets' => [['data' => 'foo bar baz']],
            ])
            ->setField('template_vars.body.showUsername', false)
            ->setField('template_vars.body.showUri', false)
            ->setField('template_vars.body.showDescription', false)
            ->setField('template_vars.body.showSecret', false)
            ->persist();

        $this->exec('passbolt email_digest send');
        $this->assertExitSuccess();

        $sentCount = EmailQueueFactory::find()->where(['sent' => true])->count();
        $this->assertSame($nEmailsSent, $sentCount);
        $this->assertMailCount($nEmailsSent);
    }
}

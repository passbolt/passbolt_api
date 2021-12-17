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

use App\Test\Factory\UserFactory;
use Cake\Chronos\Chronos;
use Cake\I18n\I18n;
use Cake\TestSuite\ConsoleIntegrationTestTrait;
use Cake\TestSuite\EmailTrait;
use Cake\TestSuite\TestCase;
use Passbolt\EmailDigest\Test\Factory\EmailQueueFactory;
use Passbolt\Locale\Test\Lib\DummyTranslationTestTrait;

/**
 * @uses \Passbolt\EmailDigest\Command\SenderCommand
 */
class SenderCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;
    use DummyTranslationTestTrait;
    use EmailTrait;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->useCommandRunner();
        $this->setDummyFrenchTranslator();
        $this->loadPlugins(['Passbolt/EmailDigest']);
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
     * Basic Sender test.
     */
    public function testSenderCommandSender()
    {
        /** @var \Cake\Datasource\EntityInterface $email */
        $email = EmailQueueFactory::make()->persist();

        $this->exec('passbolt email_digest send');

        $this->assertExitSuccess();
        $this->assertMailSentFrom($email->get('from_email'));
        $this->assertMailSentTo($email->get('email'));
        $this->assertMailContains('Sending email from: ' . $email->get('from_email'));
        $this->assertMailContains('Sending email to: ' . $email->get('email'));
    }

    /**
     * @Given I create 4 emails to recipient resp. English, French, English and French
     * @When I send them
     * @Then the local of the emails should match those of the recipients and in the end
     * the locale should be English again.
     */
    public function testSenderCommandLocale()
    {
        $this->loadPlugins(['Passbolt/Locale']);
        $frenchLocale = 'fr-FR';

        $frenchSpeakingUser = UserFactory::make()->withLocale($frenchLocale)->persist();

        EmailQueueFactory::make(['created' => Chronos::now()->subDays(4)])->persist();
        EmailQueueFactory::make(['created' => Chronos::now()->subDays(3)])->setRecipient($frenchSpeakingUser->username)
            ->persist();
        EmailQueueFactory::make(['created' => Chronos::now()->subDays(2)])->persist();
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
    }
}

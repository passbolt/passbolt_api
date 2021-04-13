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
use Cake\I18n\I18n;
use Cake\TestSuite\ConsoleIntegrationTestTrait;
use Cake\TestSuite\EmailTrait;
use Cake\TestSuite\TestCase;
use Passbolt\EmailDigest\Test\Factory\EmailQueueFactory;
use Passbolt\Locale\Test\Lib\DummyTranslationTestTrait;

/**
 * @uses \Passbolt\EmailDigest\Command\PreviewCommand
 */
class PreviewCommandTest extends TestCase
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
    }

    /**
     * Basic help test
     */
    public function testPreviewCommandHelp(): void
    {
        $this->exec('passbolt preview -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Preview a batch of queued emails as emails digests.');
        $this->assertOutputContains('cake passbolt preview');
    }

    /**
     * Basic Preview test.
     */
    public function testPreviewCommandPreview(): void
    {
        $email = EmailQueueFactory::make()->persist();
        $this->exec('passbolt preview --body true');
        $this->assertExitSuccess();
        $this->assertOutputContains('Sending email from: ' . $email->from_email);
        $this->assertOutputContains('Sending email to: ' . $email->email);
    }

    /**
     * @Given I create 4 emails to recipient resp. English, French, English and French
     * @When I preview them
     * @Then the local of the emails should match those of the recipients and in the end
     * the locale should be English again.
     */
    public function testPreviewCommandLocale(): void
    {
        $this->loadPlugins(['Passbolt/Locale']);
        $frenchLocale = 'fr-FR';
        $frenchSpeakingUser = UserFactory::make()->user()->withLocale($frenchLocale)->persist();

        EmailQueueFactory::make()->listeningToBeforeSave()->persist();
        EmailQueueFactory::make()->listeningToBeforeSave()->setRecipient($frenchSpeakingUser->username)->persist();

        $this->exec('passbolt preview --body true');

        $this->assertExitSuccess();

        $this->assertOutputContains($this->getDummyEnglishEmailSentence());
        $this->assertOutputContains($this->getDummyFrenchEmailSentence());

        $this->assertSame(I18n::getDefaultLocale(), I18n::getLocale());
    }
}

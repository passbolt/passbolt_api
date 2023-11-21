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
 * @since         3.2.0
 */

namespace Passbolt\EmailDigest\Test\TestCase\Service;

use App\Service\Avatars\AvatarsConfigurationService;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\Chronos\Chronos;
use Passbolt\EmailDigest\Service\PreviewEmailBatchService;
use Passbolt\EmailDigest\Test\Factory\EmailQueueFactory;
use Passbolt\EmailDigest\Test\Lib\EmailDigestMockTestTrait;
use Passbolt\Locale\Test\Lib\DummyTranslationTestTrait;

class PreviewEmailBatchServiceTest extends AppTestCase
{
    use DummyTranslationTestTrait;
    use EmailDigestMockTestTrait;

    /**
     * @var PreviewEmailBatchService
     */
    private $previewEmailBatchService;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadRoutes();
        $this->loadPlugins(['Passbolt/EmailDigest' => []]);
        $this->setDummyFrenchTranslator();
        $this->previewEmailBatchService = new PreviewEmailBatchService();
        (new AvatarsConfigurationService())->loadConfiguration();
    }

    public function tearDown(): void
    {
        unset($this->previewEmailBatchService);

        parent::tearDown();
    }

    public function testPreviewNextEmailBatch(): void
    {
        $numberOfEmails = 3;
        /** @var \Cake\ORM\Entity[] $emails */
        $emails = EmailQueueFactory::make($numberOfEmails)->getEntities();

        $result = $this->previewEmailBatchService->previewNextEmailsBatch($emails);

        $this->assertSame($numberOfEmails, count($result));
        foreach ($result as $email) {
            $this->assertNotEmpty($email->getHeaders());
            $this->assertNotEmpty($email->getContent());
        }
    }

    public function testPreviewNextEmailBatchTranslated(): void
    {
        $this->loadPlugins(['Passbolt/Locale' => []]);

        $frenchLocale = 'fr-FR';
        /** @var \App\Model\Entity\User $frenchSpeakingUser */
        $frenchSpeakingUser = UserFactory::make()->user()->withLocale($frenchLocale)->persist();

        $emails[] = EmailQueueFactory::make(['created' => Chronos::now()->subDays(2)])->persist();
        $emails[] = EmailQueueFactory::make(['created' => Chronos::now()->subDays(1)])
            ->setRecipient($frenchSpeakingUser->username)
            ->persist();
        $emails[] = EmailQueueFactory::make(['created' => Chronos::now()])->persist();

        $emailBatch = $this->previewEmailBatchService->previewNextEmailsBatch($emails);
        $emailInEnglish1 = $emailBatch[0];
        $emailInFrench = $emailBatch[1];
        $emailInEnglish2 = $emailBatch[2];

        $this->assertStringContainsString($this->getDummyEnglishEmailSentence(), $emailInEnglish1->getContent());
        $this->assertStringContainsString($this->getDummyFrenchEmailSentence(), $emailInFrench->getContent());
        $this->assertStringContainsString($this->getDummyEnglishEmailSentence(), $emailInEnglish2->getContent());
    }
}

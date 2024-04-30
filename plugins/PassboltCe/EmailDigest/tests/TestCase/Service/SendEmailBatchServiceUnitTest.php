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
 * @since         4.5.0
 */
namespace Passbolt\EmailDigest\Test\TestCase\Service;

use App\Notification\DigestTemplate\GroupUserDeleteDigestTemplate;
use App\Notification\DigestTemplate\ResourceChangesDigestTemplate;
use App\Service\Avatars\AvatarsConfigurationService;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Utility\EmailTestTrait;
use Cake\I18n\I18n;
use Cake\TestSuite\TestCase;
use Cake\View\Exception\MissingTemplateException;
use Passbolt\EmailDigest\EmailDigestPlugin;
use Passbolt\EmailDigest\Service\SendEmailBatchService;
use Passbolt\EmailDigest\Test\Factory\EmailQueueFactory;
use Passbolt\EmailDigest\Test\Factory\GroupUserDeleteEmailQueueFactory;
use Passbolt\EmailDigest\Test\Factory\ResourceDeleteEmailQueueFactory;
use Passbolt\EmailDigest\Utility\Digest\DigestTemplateRegistry;
use Passbolt\Locale\LocalePlugin;

/**
 * @covers \Passbolt\EmailDigest\Service\SendEmailBatchService
 */
class SendEmailBatchServiceUnitTest extends TestCase
{
    use EmailTestTrait;

    private SendEmailBatchService $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new SendEmailBatchService();
        $this->loadPlugins([EmailDigestPlugin::class => []]);
        $this->loadPlugins([LocalePlugin::class => []]);
        (new AvatarsConfigurationService())->loadConfiguration();
        /** @psalm-suppress InternalMethod */
        I18n::getTranslator('default', 'fr_FR')->getPackage()->addMessages([
            'Name: {0}' => 'Nom: {0}',
            '{0} has made changes on several resources' => '{0} a apporté des modifications à plusieurs ressources',
            'You made changes on several resources' => 'Vous avez apporté des modifications à plusieurs ressources',
            '{0} resources were affected.' => '{0} ressources ont été affectées.',
            '{0} removed you from the group {1}' => '{0} vous a retiré du groupe {1}',
        ]);
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testSendEmailBatchServiceUnitTest_On_No_Email()
    {
        $this->service->sendNextEmailsBatch([]);
        $this->assertMailCount(0);
    }

    public function testSendEmailBatchServiceUnitTest_On_Email_With_Unknown_Template()
    {
        $email = EmailQueueFactory::make()->setTemplate('foo')->getEntity();
        $this->expectException(MissingTemplateException::class);
        $this->service->sendNextEmailsBatch([$email]);
    }

    /**
     * Run tests twice, once with email digest activated, once not
     *
     * @return array
     */
    public function withAndWithoutDigestTemplate(): array
    {
        return [[true], [false]];
    }

    /**
     * @dataProvider withAndWithoutDigestTemplate
     */
    public function testSendEmailBatchServiceUnitTest_On_One_Email_Translated(bool $withDigestTemplate)
    {
        if (!$withDigestTemplate) {
            DigestTemplateRegistry::clearInstance();
        }
        $operator = UserFactory::make()->withAvatarNull()->getEntity();
        $resourceDeleted = ResourceFactory::make()->getEntity();
        $subjectTranslated = 'Le sujet';
        $email = ResourceDeleteEmailQueueFactory::make()
            ->setId()
            ->setOperator($operator)
            ->setResource($resourceDeleted)
            ->setSubject($subjectTranslated)
            ->setLocale('fr-FR')
            ->getEntity();

        $this->service->sendNextEmailsBatch([$email]);

        $this->assertMailCount(1);
        $this->assertIsString($this->getMailAt()->getMessageId());
        $this->assertMailSentToAt(0, [$email->get('email') => $email->get('email')]);
        $this->assertMailSubjectContainsAt(0, $subjectTranslated);
        $this->assertMailContainsAt(0, $subjectTranslated);
        $this->assertMailContainsAt(0, 'Nom: ' . $resourceDeleted->name);
    }

    /**
     * @dataProvider withAndWithoutDigestTemplate
     */
    public function testSendEmailBatchServiceUnitTest_On_Multiple_Emails_Same_Recipient_Below_Threshold(
        bool $withDigestTemplate
    ) {
        if (!$withDigestTemplate) {
            DigestTemplateRegistry::clearInstance();
        }
        $operator = UserFactory::make()->withAvatarNull()->getEntity();
        $operator->profile->avatar = null;
        $resourceDeleted = ResourceFactory::make()->getEntity();
        $subjectTranslated = 'Le sujet';
        $recipient = 'foo@passbolt.com';
        $nEmails = rand(2, 10);
        $emails = ResourceDeleteEmailQueueFactory::make($nEmails)
            ->setId()
            ->setRecipient($recipient)
            ->setOperator($operator)
            ->setResource($resourceDeleted)
            ->setSubject($subjectTranslated)
            ->setLocale('fr-FR')
            ->getEntities();

        $this->service->sendNextEmailsBatch($emails);

        if ($withDigestTemplate) {
            $this->assertMailCount(1);
            $this->assertIsString($this->getMailAt()->getMessageId());
            $this->assertMailSentToAt(0, [$recipient => $recipient]);
            $this->assertMailSubjectContainsAt(0, $operator->profile->full_name . ' a apporté des modifications à plusieurs ressources');
            $this->assertMailContainsAt(0, $subjectTranslated);
            $this->assertMailContainsAt(0, 'Nom: ' . $resourceDeleted->name);
        } else {
            $this->assertMailCount($nEmails);
            foreach ($emails as $i => $email) {
                $this->assertMailSentToAt($i, [$recipient => $recipient]);
                $this->assertIsString($this->getMailAt($i)->getMessageId());
                $this->assertMailSubjectContainsAt($i, $subjectTranslated);
                $this->assertMailContainsAt($i, $subjectTranslated);
                $this->assertMailContainsAt($i, 'Nom: ' . $resourceDeleted->name);
            }
        }
    }

    /**
     * @dataProvider withAndWithoutDigestTemplate
     */
    public function testSendEmailBatchServiceUnitTest_On_Same_Digest_Template_Various_Recipients(
        bool $withDigestTemplate
    ) {
        if (!$withDigestTemplate) {
            DigestTemplateRegistry::clearInstance();
        }
        $recipient1 = 'foo@passbolt.com';
        $recipient2 = 'bar@passbolt.com';
        $operator = UserFactory::make()->withAvatarNull()->getEntity();
        [$resourceDeleted1, $resourceDeleted2] = ResourceFactory::make(2)->getEntities();
        $subject = 'Subject';
        $nEmails1 = rand(2, 10);
        $nEmails2 = rand(2, 10);
        $emails1 = ResourceDeleteEmailQueueFactory::make($nEmails1)
            ->setId()
            ->setRecipient($recipient1)
            ->setOperator($operator)
            ->setResource($resourceDeleted1)
            ->setSubject($subject)
            ->getEntities();
        $emails2 = ResourceDeleteEmailQueueFactory::make($nEmails2)
            ->setId()
            ->setRecipient($recipient2)
            ->setOperator($operator)
            ->setResource($resourceDeleted2)
            ->setSubject($subject)
            ->getEntities();

        $allEmails = array_merge($emails1, $emails2);

        $this->service->sendNextEmailsBatch($allEmails);

        if ($withDigestTemplate) {
            $this->assertMailCount(2);
            $this->assertMailSentToAt(0, [$recipient1 => $recipient1]);
            $this->assertMailSubjectContainsAt(0, $operator->profile->full_name . ' has made changes on several resources');
            $this->assertMailContainsAt(0, $subject);
            $this->assertMailContainsAt(0, 'Name: ' . $resourceDeleted1->name);

            $this->assertMailSentToAt(1, [$recipient2 => $recipient2]);
            $this->assertMailSubjectContainsAt(1, $operator->profile->full_name . ' has made changes on several resources');
            $this->assertMailContainsAt(1, $subject);
            $this->assertMailContainsAt(1, 'Name: ' . $resourceDeleted2->name);
        } else {
            $this->assertMailCount(count($allEmails));
            foreach ($emails1 as $i => $email) {
                $this->assertMailSentToAt($i, [$recipient1 => $recipient1]);
                $this->assertMailSubjectContainsAt($i, $subject);
                $this->assertMailContainsAt($i, $subject);
                $this->assertMailContainsAt($i, 'Name: ' . $resourceDeleted1->name);
            }

            foreach ($emails2 as $i => $email) {
                $index = (int)$i + $nEmails1;
                $this->assertMailSentToAt($index, [$recipient2 => $recipient2]);
                $this->assertMailSubjectContainsAt($index, $subject);
                $this->assertMailContainsAt($index, $subject);
                $this->assertMailContainsAt($index, 'Name: ' . $resourceDeleted2->name);
            }
        }
    }

    /**
     * @dataProvider withAndWithoutDigestTemplate
     */
    public function testSendEmailBatchServiceUnitTest_On_Multiple_Emails_Multiple_Operators_Below_Threshold(
        bool $withDigestTemplate
    ) {
        if (!$withDigestTemplate) {
            DigestTemplateRegistry::clearInstance();
        }
        $recipient = 'foo@passbolt.com';
        // Operator 1 is the recipient too. This changes the subject
        [$operator1, $operator2] = UserFactory::make([['username' => $recipient], []])->withAvatarNull()->getEntities();
        [$resourceDeleted1, $resourceDeleted2] = ResourceFactory::make(2)->getEntities();
        $subjectTranslated = 'Le sujet';
        $nEmails1 = rand(2, 10);
        $nEmails2 = rand(2, 10);
        $emails1 = ResourceDeleteEmailQueueFactory::make($nEmails1)
            ->setId()
            ->setRecipient($recipient)
            ->setOperator($operator1)
            ->setResource($resourceDeleted1)
            ->setSubject($subjectTranslated)
            ->setLocale('fr-FR')
            ->getEntities();
        $emails2 = ResourceDeleteEmailQueueFactory::make($nEmails2)
            ->setId()
            ->setRecipient($recipient)
            ->setOperator($operator2)
            ->setResource($resourceDeleted2)
            ->setSubject($subjectTranslated)
            ->setLocale('fr-FR')
            ->getEntities();

        $allEmails = array_merge($emails1, $emails2);

        $this->service->sendNextEmailsBatch($allEmails);

        if ($withDigestTemplate) {
            $this->assertMailCount(2);
            $this->assertMailSentToAt(0, [$recipient => $recipient]);
            $this->assertMailSubjectContainsAt(0, 'Vous avez apporté des modifications à plusieurs ressources');
            $this->assertMailContainsAt(0, $subjectTranslated);
            $this->assertMailContainsAt(0, 'Nom: ' . $resourceDeleted1->name);

            $this->assertMailSentToAt(1, [$recipient => $recipient]);
            $this->assertMailSubjectContainsAt(1, $operator2->profile->full_name . ' a apporté des modifications à plusieurs ressources');
            $this->assertMailContainsAt(1, $subjectTranslated);
            $this->assertMailContainsAt(1, 'Nom: ' . $resourceDeleted2->name);
        } else {
            $this->assertMailCount(count($allEmails));
            foreach ($emails1 as $i => $email) {
                $this->assertMailSentToAt($i, [$recipient => $recipient]);
                $this->assertMailSubjectContainsAt($i, $subjectTranslated);
                $this->assertMailContainsAt($i, $subjectTranslated);
                $this->assertMailContainsAt($i, 'Nom: ' . $resourceDeleted1->name);
            }

            foreach ($emails2 as $i => $email) {
                $index = (int)$i + $nEmails1;
                $this->assertMailSentToAt($index, [$recipient => $recipient]);
                $this->assertMailSubjectContainsAt($index, $subjectTranslated);
                $this->assertMailContainsAt($index, $subjectTranslated);
                $this->assertMailContainsAt($index, 'Nom: ' . $resourceDeleted2->name);
            }
        }
    }

    /**
     * @dataProvider withAndWithoutDigestTemplate
     */
    public function testSendEmailBatchServiceUnitTest_On_Multiple_Emails_Multiple_Operators_Below_And_Above_Threshold(
        bool $withDigestTemplate
    ) {
        if (!$withDigestTemplate) {
            DigestTemplateRegistry::clearInstance();
        }
        [$operator1, $operator2] = UserFactory::make(2)->withAvatarNull()->getEntities();
        [$resourceDeleted1, $resourceDeleted2] = ResourceFactory::make(2)->getEntities();
        $subjectTranslated = 'Le sujet';
        $recipient = 'foo@passbolt.com';
        $nEmails1 = 12;
        $nEmails2 = 2;
        // These emails are above the threshold
        $emails1 = ResourceDeleteEmailQueueFactory::make($nEmails1)
            ->setId()
            ->setRecipient($recipient)
            ->setOperator($operator1)
            ->setResource($resourceDeleted1)
            ->setSubject($subjectTranslated)
            ->setLocale('fr-FR')
            ->getEntities();
        $emails2 = ResourceDeleteEmailQueueFactory::make($nEmails2)
            ->setId()
            ->setRecipient($recipient)
            ->setOperator($operator2)
            ->setResource($resourceDeleted2)
            ->setSubject($subjectTranslated)
            ->setLocale('fr-FR')
            ->getEntities();

        $allEmails = array_merge($emails1, $emails2);

        $this->service->sendNextEmailsBatch($allEmails);

        if ($withDigestTemplate) {
            $this->assertMailCount(2);
            $this->assertMailSentToAt(0, [$recipient => $recipient]);
            $subject = $operator1->profile->full_name . ' a apporté des modifications à plusieurs ressources';
            $this->assertMailSubjectContainsAt(0, $subject);
            $this->assertMailContainsAt(0, $subject);
            $this->assertMailContainsAt(0, $nEmails1 . ' ressources ont été affectées.');

            $this->assertMailSentToAt(1, [$recipient => $recipient]);
            $this->assertMailSubjectContainsAt(1, $operator2->profile->full_name . ' a apporté des modifications à plusieurs ressources');
            $this->assertMailContainsAt(1, $subjectTranslated);
            $this->assertMailContainsAt(1, 'Nom: ' . $resourceDeleted2->name);
        } else {
            $this->assertMailCount(count($allEmails));
            foreach ($emails1 as $i => $email) {
                $this->assertMailSentToAt($i, [$recipient => $recipient]);
                $this->assertMailSubjectContainsAt($i, $subjectTranslated);
                $this->assertMailContainsAt($i, $subjectTranslated);
                $this->assertMailContainsAt($i, 'Nom: ' . $resourceDeleted1->name);
            }

            foreach ($emails2 as $i => $email) {
                $index = (int)$i + $nEmails1;
                $this->assertMailSentToAt($index, [$recipient => $recipient]);
                $this->assertMailSubjectContainsAt($index, $subjectTranslated);
                $this->assertMailContainsAt($index, $subjectTranslated);
                $this->assertMailContainsAt($index, 'Nom: ' . $resourceDeleted2->name);
            }
        }
    }

    public function testSendEmailBatchServiceUnitTest_On_Multiple_Emails_Multiple_Operators_Multiple_Digest_Templates_Below_And_Above_Threshold()
    {
        DigestTemplateRegistry::clearInstance();
        // Emails of the Group User delete template should be sent first
        DigestTemplateRegistry::getInstance()
            ->addTemplate(new GroupUserDeleteDigestTemplate(1))
            ->addTemplate((new ResourceChangesDigestTemplate()));

        [$operator1, $operator2] = UserFactory::make(2)
            ->withAvatarNull()
            ->getEntities();
        [$resourceDeleted1, $resourceDeleted2] = ResourceFactory::make(2)->getEntities();
        [$groupDeleted1, $groupDeleted2] = GroupFactory::make(2)->getEntities();
        $subjectTranslated = 'Le sujet';
        $recipient = 'foo@passbolt.com';
        $nEmailsResourceDeleted1 = rand(11, 20);
        $nEmailsResourceDeleted2 = rand(11, 20);
        $nEmailsGroupDeleted1 = rand(11, 20);
        $nEmailsGroupDeleted2 = rand(11, 20);

        // These emails are above the threshold
        $emailsResourceDeleted1 = ResourceDeleteEmailQueueFactory::make($nEmailsResourceDeleted1)
            ->setRecipient($recipient)
            ->setOperator($operator1)
            ->setResource($resourceDeleted1)
            ->setSubject($subjectTranslated)
            ->getEntities();
        $emailsResourceDeleted2 = ResourceDeleteEmailQueueFactory::make($nEmailsResourceDeleted2)
            ->setRecipient($recipient)
            ->setOperator($operator2)
            ->setResource($resourceDeleted2)
            ->setSubject($subjectTranslated)
            ->getEntities();
        $emailsGroupDeleted1 = GroupUserDeleteEmailQueueFactory::make($nEmailsGroupDeleted1)
            ->setRecipient($recipient)
            ->setOperator($operator1)
            ->setGroup($groupDeleted1)
            ->setSubject($subjectTranslated)
            ->getEntities();
        $emailsGroupDeleted2 = GroupUserDeleteEmailQueueFactory::make($nEmailsGroupDeleted2)
            ->setRecipient($recipient)
            ->setOperator($operator2)
            ->setGroup($groupDeleted2)
            ->setSubject($subjectTranslated)
            ->getEntities();

        $allEmails = array_merge(
            $emailsResourceDeleted1,
            $emailsResourceDeleted2,
            $emailsGroupDeleted1,
            $emailsGroupDeleted2
        );

        $this->service->sendNextEmailsBatch($allEmails);

        $this->assertMailCount(4);
        $this->assertMailSentToAt(0, [$recipient => $recipient]);
        $this->assertMailSubjectContainsAt(0, $operator1->profile->full_name . ' deleted several group memberships');
        $this->assertMailSentToAt(1, [$recipient => $recipient]);
        $this->assertMailSubjectContainsAt(1, $operator2->profile->full_name . ' deleted several group memberships');
        $this->assertMailSentToAt(2, [$recipient => $recipient]);
        $this->assertMailSubjectContainsAt(2, $operator1->profile->full_name . ' has made changes on several resources');
        $this->assertMailSentToAt(3, [$recipient => $recipient]);
        $this->assertMailSubjectContainsAt(3, $operator2->profile->full_name . ' has made changes on several resources');
    }

    public function testSendEmailBatchServiceUnitTest_On_Multiple_Full_Base_Url()
    {
        $recipient = 'test@passbolt.com';
        $fullBaseUrl1 = 'passbolt.local/orga-1';
        $fullBaseUrl2 = 'passbolt.local/orga-2';
        $operator = UserFactory::make()->withAvatarNull()->getEntity();
        $emails1 = ResourceDeleteEmailQueueFactory::make(2)
            ->setRecipient($recipient)
            ->setOperator($operator)
            ->setFullBaseUrl($fullBaseUrl1)
            ->getEntities();

        $emails2 = ResourceDeleteEmailQueueFactory::make(2)
            ->setRecipient($recipient)
            ->setOperator($operator)
            ->setFullBaseUrl($fullBaseUrl2)
            ->getEntities();

        $allEmails = array_merge($emails1, $emails2);

        $this->service->sendNextEmailsBatch($allEmails);

        $this->assertMailCount(2);
        $this->assertMailContainsAt(0, $fullBaseUrl1);
        $this->assertMailContainsAt(0, $fullBaseUrl1 . '/img/logo/logo.png');
        $this->assertMailContainsAt(1, $fullBaseUrl2);
        $this->assertMailContainsAt(1, $fullBaseUrl2 . '/img/logo/logo.png');
    }
}

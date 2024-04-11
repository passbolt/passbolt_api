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
 * @since         4.4.0
 */
namespace Passbolt\EmailDigest\Test\TestCase\Service;

use App\Notification\Email\Redactor\Resource\ResourceCreateEmailRedactor;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Test\Lib\Model\ResourcesModelTrait;
use App\Test\Lib\Utility\EmailTestTrait;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\I18n\I18n;
use Passbolt\EmailDigest\EmailDigestPlugin;
use Passbolt\EmailDigest\Service\SendEmailBatchService;
use Passbolt\EmailDigest\Test\Factory\EmailQueueFactory;
use Passbolt\Folders\Test\Factory\ResourceFactory;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

class SendEmailBatchServiceCreateResourceChangesDigestTest extends AppIntegrationTestCase
{
    use ConsoleIntegrationTestTrait;
    use EmailQueueTrait;
    use EmailTestTrait;
    use ResourcesModelTrait;

    private SendEmailBatchService $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->useCommandRunner();
        $this->service = new SendEmailBatchService();
        $this->setEmailNotificationsSetting('password.create', true);
        $this->enableFeaturePlugin(EmailDigestPlugin::class);
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testSendEmailBatchServiceCreateResourceChangesDigest_SendNextEmailsBatch_One_Email()
    {
        ResourceTypeFactory::make()->default()->persist();
        $data = $this->getDummyResourcesPostData([
            'name' => 'Nouveau nom de resource privée',
            'username' => 'username@domain.com',
            'uri' => 'https://www.mon-domain.com',
            'description' => 'Nouvelle description de resource privée',
        ]);

        /** @var \App\Model\Entity\User $frenchSpeakingUser */
        $frenchSpeakingUser = UserFactory::make()->user()->withLocale('fr-FR')->persist();
        /** @psalm-suppress InternalMethod */
        I18n::getTranslator('default', 'fr_FR')->getPackage()->addMessages([
            'You added the password {0}' => 'Vous avez ajouté le mot de passe {0}',
            'You have saved a new password' => 'Vous avez enregistré un nouveau mot de passe',
        ]);

        $this->logInAs($frenchSpeakingUser);
        $this->postJson('/resources.json', $data);
        $this->assertSuccess();

        $this->service->sendNextEmailsBatch(EmailQueueFactory::find()->all()->toArray());

        $this->assertSame(1, ResourceFactory::count());
        $this->assertMailCount(1);
        $this->assertMailSubjectContainsAt(0, 'Vous avez ajouté le mot de passe Nouveau nom de resource privée');
        $this->assertMailContainsAt(0, 'Vous avez enregistré un nouveau mot de passe');
    }

    public function testSendEmailBatchServiceCreateResourceChangesDigest_SendNextEmailsBatch_Below_Threshold()
    {
        ResourceTypeFactory::make()->default()->persist();
        $data = $this->getDummyResourcesPostData([
            'name' => 'Nouveau nom de resource privée',
            'username' => 'username@domain.com',
            'uri' => 'https://www.mon-domain.com',
            'description' => 'Nouvelle description de resource privée',
        ]);

        $nResourcesAdded = 2;
        /** @var \App\Model\Entity\User $frenchSpeakingUser */
        $frenchSpeakingUser = UserFactory::make()->user()->withLocale('fr-FR')->persist();
        /** @psalm-suppress InternalMethod */
        I18n::getTranslator('default', 'fr_FR')->getPackage()->addMessages([
            'You made changes on several resources' => 'Vous avez apporté des modifications à plusieurs ressources',
            'You have saved a new password' => 'Vous avez enregistré un nouveau mot de passe',
        ]);

        $this->logInAs($frenchSpeakingUser);
        for ($i = 0; $i < $nResourcesAdded; $i++) {
            $this->postJson('/resources.json', $data);
            $this->assertSuccess();
        }

        $this->service->sendNextEmailsBatch(EmailQueueFactory::find()->all()->toArray());

        $this->assertSame($nResourcesAdded, ResourceFactory::count());

        $this->assertMailCount(1);
        $this->assertMailSubjectContainsAt(0, 'Vous avez apporté des modifications à plusieurs ressources');
        $this->assertMailContainsAt(0, 'Vous avez apporté des modifications à plusieurs ressources');
        $this->assertMailContainsAt(0, 'Vous avez enregistré un nouveau mot de passe');
    }

    public function testSendEmailBatchServiceCreateResourceChangesDigest_SendNextEmailsBatch_Above_Threshold()
    {
        $nResourcesAdded = 15;
        /** @var \App\Model\Entity\User $operator */
        $operator = UserFactory::make()->withAvatar()->persist();
        EmailQueueFactory::make($nResourcesAdded)
            ->setRecipient('foo@bar.baz')
            ->setTemplate(ResourceCreateEmailRedactor::TEMPLATE)
            ->setField('template_vars.body.user', $operator)
            ->setField('template_vars.locale', 'fr-FR')
            ->persist();

        /** @psalm-suppress InternalMethod */
        I18n::getTranslator('default', 'fr_FR')->getPackage()->addMessages([
            '{0} has made changes on several resources' => '{0} a apporté des modifications à plusieurs ressources',
            '{0} resources were affected.' => '{0} ressources ont été affectées.',
        ]);

        $this->exec('passbolt email_digest send');
        $this->assertExitSuccess();

        $this->assertMailCount(1);
        $this->assertMailSubjectContainsAt(0, $operator->profile->full_name . ' a apporté des modifications à plusieurs ressources');
        $this->assertMailContainsAt(0, $operator->profile->full_name . ' a apporté des modifications à plusieurs ressources');
        $this->assertMailContainsAt(0, $nResourcesAdded . ' ressources ont été affectées.');
    }
}

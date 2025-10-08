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
 * @since         5.6.0
 */
namespace Passbolt\Metadata\Test\TestCase\Command\EmailDigest;

use App\Notification\DigestTemplate\GroupMembershipDigestTemplate;
use App\Notification\DigestTemplate\ResourceChangesDigestTemplate;
use App\Notification\Email\Redactor\Group\GroupUserAddEmailRedactor;
use App\Notification\Email\Redactor\Resource\ResourceCreateEmailRedactor;
use App\Service\Avatars\AvatarsConfigurationService;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use App\Test\Lib\Utility\EmailTestTrait;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Passbolt\EmailDigest\EmailDigestPlugin;
use Passbolt\EmailDigest\Test\Factory\EmailQueueFactory;
use Passbolt\EmailDigest\Test\Lib\EmailDigestMockTestTrait;
use Passbolt\EmailDigest\Utility\Digest\DigestTemplateRegistry;
use Passbolt\Locale\Test\Lib\DummyTranslationTestTrait;
use Passbolt\Metadata\Test\Factory\MetadataTypesSettingsFactory;

/**
 * @uses \Passbolt\EmailDigest\Command\SenderCommand
 */
class MetadataSenderCommandTest extends AppIntegrationTestCaseV5
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
        $this->loadRoutes();
        (new AvatarsConfigurationService())->loadConfiguration();
        $this->enableFeaturePlugin(EmailDigestPlugin::class);
    }

    public function testMetadataSenderCommand_MultipleDigests()
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        $recipient = 'foo@bar.baz';
        $nEmailsSent = 12;
        [$user, $admin] = UserFactory::make(2)->withAvatar()->persist();

        EmailQueueFactory::make($nEmailsSent)
            ->setRecipient($recipient)
            ->setTemplate(ResourceCreateEmailRedactor::TEMPLATE_V5)
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

        $sentCount = EmailQueueFactory::find()->where(['sent' => true])->all()->count();
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
}

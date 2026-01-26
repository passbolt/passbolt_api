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
 * @since         5.9.0
 */
namespace Passbolt\Folders\Test\TestCase\Command\EmailDigest;

use App\Notification\DigestTemplate\ResourceChangesDigestTemplate;
use App\Notification\Email\Redactor\Resource\ResourceCreateEmailRedactor;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Utility\EmailTestTrait;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Passbolt\EmailDigest\EmailDigestPlugin;
use Passbolt\EmailDigest\Test\Factory\EmailQueueFactory;
use Passbolt\EmailDigest\Utility\Digest\DigestTemplateRegistry;
use Passbolt\Folders\FoldersPlugin;
use Passbolt\Folders\Notification\DigestTemplate\FolderChangesDigestTemplate;
use Passbolt\Folders\Notification\Email\CreateFolderEmailRedactor;
use Passbolt\Folders\Test\Factory\FolderFactory;

/**
 * @uses \Passbolt\EmailDigest\Command\SenderCommand
 */
class FoldersEmailDigestSenderCommandTest extends AppIntegrationTestCase
{
    use ConsoleIntegrationTestTrait;
    use EmailTestTrait;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(EmailDigestPlugin::class);
        $this->enableFeaturePlugin(FoldersPlugin::class);
    }

    public function testFoldersEmailDigestSenderCommand(): void
    {
        $recipient = 'foo@bar.baz';
        $nEmailsSent = 15;
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withAvatar()->persist();

        EmailQueueFactory::make($nEmailsSent)
            ->setRecipient($recipient)
            ->setTemplate(ResourceCreateEmailRedactor::TEMPLATE)
            ->setField('template_vars.body.user', $user)
            ->setField('template_vars.locale', 'en-UK')
            ->persist();

        $folder = FolderFactory::make()->withPermissionsFor([$user])->persist();
        EmailQueueFactory::make($nEmailsSent)
            ->setRecipient($recipient)
            ->setTemplate(CreateFolderEmailRedactor::TEMPLATE)
            ->setField('template_vars.body.user', $user)
            ->setField('template_vars.body.folder', $folder)
            ->setField('template_vars.locale', 'en-UK')
            ->persist();

        // Upgrade priority of this template to ensure that the emails are sent in this order
        $priorityResourceChange = rand();
        DigestTemplateRegistry::getInstance()->addTemplate(new ResourceChangesDigestTemplate($priorityResourceChange));
        DigestTemplateRegistry::getInstance()->addTemplate(new FolderChangesDigestTemplate($priorityResourceChange + 1));

        $this->exec('passbolt email_digest send');
        $this->assertExitSuccess();

        $sentCount = EmailQueueFactory::find()->where(['sent' => true])->all()->count();
        $this->assertSame($nEmailsSent * 2, $sentCount);

        $this->assertMailCount(2);
        $subject = $user->profile->full_name . ' has made changes on several resources';
        $this->assertMailSubjectContainsAt(0, $subject);
        $this->assertMailContainsAt(0, $nEmailsSent . ' resources were affected.');
        $this->assertMailContainsAt(0, $subject);

        $subject = $user->profile->full_name . ' has made changes on several folders';
        $this->assertMailSubjectContainsAt(1, $subject);
        $this->assertMailContainsAt(1, $subject);
        $this->assertMailContainsAt(1, $nEmailsSent . ' folders were affected.');
    }
}

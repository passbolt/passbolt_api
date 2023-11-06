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
namespace Passbolt\EmailDigest\Test\TestCase\Command;

use App\Notification\Email\Redactor\Group\GroupUserAddEmailRedactor;
use App\Notification\Email\Redactor\Resource\ResourceCreateEmailRedactor;
use App\Service\Avatars\AvatarsConfigurationService;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Utility\EmailTestTrait;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Passbolt\EmailDigest\Test\Factory\EmailQueueFactory;

class SenderCommandMultipleDigestsTest extends AppIntegrationTestCase
{
    use ConsoleIntegrationTestTrait;
    use EmailTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->useCommandRunner();
        (new AvatarsConfigurationService())->loadConfiguration();
    }

    public function testSenderCommandMultipleDigests()
    {
        $recipient = 'foo@bar.baz';
        $nEmailsSent = 15;
        $user = UserFactory::make()->withAvatar()->persist();
        $admin = UserFactory::make()->withAvatar()->persist();
        EmailQueueFactory::make($nEmailsSent)
            ->setRecipient($recipient)
            ->setTemplate(ResourceCreateEmailRedactor::TEMPLATE)
            ->setField('template_vars.body.user', $user)
            ->persist();

        EmailQueueFactory::make($nEmailsSent)
            ->setRecipient($recipient)
            ->setTemplate(GroupUserAddEmailRedactor::TEMPLATE)
            ->setField('template_vars.body.admin', $admin)
            ->setField('template_vars.body.user', $user)
            ->persist();

        $this->exec('passbolt email_digest send');
        $this->assertExitSuccess();

        $this->assertMailCount(2);

        $this->assertMailSubjectContainsAt(0, 'Multiple passwords have been changed in passbolt');
        $this->assertMailContainsAt(0, $nEmailsSent . ' resources were affected.');
        $this->assertMailContainsAt(0, 'Edited multiple resources');

        $this->assertMailSubjectContainsAt(1, 'Your membership in several groups changed in passbolt');
        $this->assertMailContainsAt(1, $nEmailsSent . ' group memberships were affected.');
        $this->assertMailContainsAt(1, 'Edited your membership in several groups');
    }
}

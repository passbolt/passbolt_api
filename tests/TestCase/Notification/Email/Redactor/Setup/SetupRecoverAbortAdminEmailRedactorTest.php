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
 * @since         3.6.0
 */

namespace App\Test\TestCase\Notification\Email\Redactor\Setup;

use App\Notification\Email\Redactor\Setup\SetupRecoverAbortAdminEmailRedactor;
use App\Service\Setup\RecoverAbortService;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Passbolt\EmailDigest\Test\Factory\EmailQueueFactory;

class SetupRecoverAbortAdminEmailRedactorTest extends AppIntegrationTestCase
{
    use EmailQueueTrait;

    public function testSetupRecoverAbortAdminEmailRedactor_RedactorIsSubscribedToEvents()
    {
        $this->assertSame(
            [
                RecoverAbortService::RECOVER_ABORT_EVENT_NAME,
            ],
            (new SetupRecoverAbortAdminEmailRedactor())->getSubscribedEvents()
        );
    }

    public function testSetupRecoverAbortAdminEmailRedactor_Success()
    {
        UserFactory::make(3)->withAvatar()->admin()->persist();
        $user = UserFactory::make()->withAvatar()->user()->persist();

        // Needed to load event listeners / settings
        $this->getJson('/auth/is-authenticated.json');

        // Trigger event
        $event = new Event(RecoverAbortService::RECOVER_ABORT_EVENT_NAME, null, compact('user'));
        EventManager::instance()->dispatch($event);

        $this->assertSame(3, EmailQueueFactory::count());
        $email = EmailQueueFactory::find()->firstOrFail();
        $this->assertTextEquals('AD/setup_recover_abort', $email->template);
    }
}

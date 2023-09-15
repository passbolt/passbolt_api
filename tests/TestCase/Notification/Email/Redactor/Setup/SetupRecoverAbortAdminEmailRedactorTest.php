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
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;

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
}

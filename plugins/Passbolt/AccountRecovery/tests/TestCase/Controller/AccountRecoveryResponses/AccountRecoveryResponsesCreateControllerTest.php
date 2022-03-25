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

namespace Passbolt\AccountRecovery\Test\TestCase\Controller\AccountRecoveryResponses;

use App\Test\Lib\Model\EmailQueueTrait;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryIntegrationTestCase;

class AccountRecoveryResponsesCreateControllerTest extends AccountRecoveryIntegrationTestCase
{
    use EmailQueueTrait;

    public function setUp(): void
    {
        parent::setUp();
        EventManager::instance()->setEventList(new EventList());
    }

    /**
     * Authentication error guest not allowed
     */
    public function testAccountRecoveryResponsesCreateController_ErrorAuthentication()
    {
        $this->postJson('/account-recovery/responses.json', []);
        $this->assertError(401);
    }

    /**
     * Authorization error user must be an admin
     */
    public function testAccountRecoveryResponsesCreateController_ErrorAuthorizationUser()
    {
        $this->logInAsUser();
        $this->postJson('/account-recovery/responses.json', []);
        $this->assertError(403);
    }

    /**
     * Successful test case
     */
    public function testAccountRecoveryResponsesCreateController_ErrorNoData()
    {
        $this->logInAsAdmin();
        $this->postJson('/account-recovery/responses.json', []);
        $this->assertError(400);
    }
}

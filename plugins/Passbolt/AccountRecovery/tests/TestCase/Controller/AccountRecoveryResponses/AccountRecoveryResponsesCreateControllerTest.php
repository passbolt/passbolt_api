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

use App\Test\Factory\UserFactory;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Test\Lib\Utility\Gpg\GpgAdaSetupTrait;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryIntegrationTestCase;
use Passbolt\AccountRecovery\Test\Scenario\Request\ResponseCreateScenario;

class AccountRecoveryResponsesCreateControllerTest extends AccountRecoveryIntegrationTestCase
{
    use EmailQueueTrait;
    use GpgAdaSetupTrait;

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
     * Error case: no data
     */
    public function testAccountRecoveryResponsesCreateController_ErrorNoData()
    {
        $this->logInAsAdmin();
        $this->postJson('/account-recovery/responses.json', []);
        $this->assertError(400);
    }

    /**
     * Successful test case
     */
    public function testAccountRecoveryResponsesCreateController_Success_Approved()
    {
        [$request, $policy, $user] = $this->loadFixtureScenario(ResponseCreateScenario::class);
        $admins = UserFactory::make(3)->active()->admin()->persist(3);
        $password = $this->encrypt($request->fingerprint, $request->armored_key);
        $data = [
            'account_recovery_request_id' => $request->id,
            'status' => AccountRecoveryResponse::STATUS_APPROVED,
            'responder_foreign_model' => AccountRecoveryResponse::RESPONDER_FOREIGN_MODEL_ORGANIZATION_KEY,
            'responder_foreign_key' => $policy->public_key_id,
            'data' => $password,
        ];

        $this->logInAsAdmin();
        $this->postJson('/account-recovery/responses.json', $data);
        $this->assertResponseOk();

        $expectedEmailsCount = count($admins) + 1 + 1; // The admins, plus the one logged in, and the user
        $this->assertEmailQueueCount($expectedEmailsCount);
    }
}

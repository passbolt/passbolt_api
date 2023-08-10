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

use App\Model\Entity\User;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Test\Lib\Utility\Gpg\GpgAdaSetupTrait;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryRequestFactory;
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
     * Successful test case with response approved
     */
    public function testAccountRecoveryResponsesCreateController_Success_Approved()
    {
        [$request, $policy, $user, $authenticationToken] = $this->loadFixtureScenario(ResponseCreateScenario::class);
        $oldApprovedRequest = AccountRecoveryRequestFactory::make()
            ->withUser($user->id)
            ->approved()
            ->persist();
        $admins = UserFactory::make(3)->active()->admin()->persist(3);
        $password = $this->encrypt($request->fingerprint, $request->armored_key);
        $status = AccountRecoveryResponse::STATUS_APPROVED;
        $data = [
            'account_recovery_request_id' => $request->id,
            'status' => $status,
            'responder_foreign_model' => AccountRecoveryResponse::RESPONDER_FOREIGN_MODEL_ORGANIZATION_KEY,
            'responder_foreign_key' => $policy->public_key_id,
            'data' => $password,
        ];

        $admin = $this->logInAsAdmin();
        $this->postJson('/account-recovery/responses.json', $data);
        $this->assertResponseOk();

        $expectedEmailsCount = count($admins) + 1 + 1; // The admins, plus the one logged in, and the user
        $this->assertEmailQueueCount($expectedEmailsCount);

        // Assess mail sent to the user
        $this->assertEmailInBatchContains(
            '/account-recovery/continue/' . $request['user_id'] . '/' . $authenticationToken['token'],
            $user->username
        );
        $this->assertEmailInBatchContains(
            "{$admin->profile->first_name}($admin->username) has approved your recovery request.",
            $user->username
        );

        $this->assertAdminEmails($status, $user, $admin, $admins);

        // Assert that the status of the previous approved request is unchanged
        $this->assertTrue(AccountRecoveryRequestFactory::get($oldApprovedRequest->id)->isApproved());
    }

    /**
     * Successful test case with response rejected
     */
    public function testAccountRecoveryResponsesCreateController_Success_Rejected()
    {
        [$request, $policy, $user] = $this->loadFixtureScenario(ResponseCreateScenario::class);
        $admins = UserFactory::make(3)->active()->admin()->persist(3);
        $status = AccountRecoveryResponse::STATUS_REJECTED;
        $data = [
            'account_recovery_request_id' => $request->id,
            'status' => $status,
            'responder_foreign_model' => AccountRecoveryResponse::RESPONDER_FOREIGN_MODEL_ORGANIZATION_KEY,
            'responder_foreign_key' => $policy->public_key_id,
        ];

        $admin = $this->logInAsAdmin();
        $this->postJson('/account-recovery/responses.json', $data);
        $this->assertResponseOk();

        $expectedEmailsCount = count($admins) + 1 + 1; // The admins, plus the one logged in, and the user
        $this->assertEmailQueueCount($expectedEmailsCount);

        // Assess mail sent to the user
        $this->assertEmailInBatchNotContains(
            '/account-recovery/continue/',
            $user->username
        );
        $this->assertEmailInBatchContains(
            "{$admin->profile->first_name} ($admin->username) has denied your recovery request.",
            $user->username
        );

        $this->assertAdminEmails($status, $user, $admin, $admins);
    }

    private function assertAdminEmails(string $status, User $user, User $admin, array $admins)
    {
        // Assess mail sent to the acting admin
        $this->assertEmailInBatchContains(
            "You have updated a recovery request to {$status}.",
            $admin->username
        );
        $this->assertEmailInBatchContains(
            "You have set the status of the account recovery request initiated by {$user->profile->first_name} ({$user->username}) to {$status}.",
            $admin->username
        );

        // Assess the mail sent to the other admins
        foreach ($admins as $adm) {
            $this->assertEmailInBatchContains(
                "{$admin->profile->first_name}({$admin->username}) has updated a recovery request to {$status}.",
                $adm->username
            );
            $this->assertEmailInBatchContains(
                "{$admin->profile->first_name} ({$admin->username}) has set the status of the request initiated by {$user->profile->first_name} ({$user->username}) to {$status}.",
                $adm->username
            );
        }
    }
}

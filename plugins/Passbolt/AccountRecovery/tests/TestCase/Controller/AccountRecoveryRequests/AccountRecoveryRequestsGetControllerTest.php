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

namespace Passbolt\AccountRecovery\Test\TestCase\Controller\AccountRecoveryRequests;

use App\Test\Factory\UserFactory;
use App\Test\Lib\Model\EmailQueueTrait;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryIntegrationTestCase;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryRequestScenario;

class AccountRecoveryRequestsGetControllerTest extends AccountRecoveryIntegrationTestCase
{
    use EmailQueueTrait;

    /**
     * Successful test case
     */
    public function testAccountRecoveryRequestsGetController_Success()
    {
        [$request, $user, $token] = AccountRecoveryRequestScenario::startContinueScenarioApproved();
        $id = "$request->id/$user->id/$token->token";
        $this->getJson("/account-recovery/requests/$id.json");
        $this->assertResponseOk();
    }

    /**
     * @Given a correct user ID and token ID was provided
     * @When a wrong request ID is provided
     * @Then a potential security issue will be notified to admins
     */
    public function testAccountRecoveryRequestsGetController_Bad_Request_ID()
    {
        [$request, $user, $token] = AccountRecoveryRequestScenario::startContinueScenarioApproved();

        // Setup ip address
        $clientIp = 'Foo';
        $this->configRequest(['environment' => ['REMOTE_ADDR' => $clientIp]]);

        // Make three admins
        $nAdmins = 3;
        $admins = UserFactory::make($nAdmins)->active()->admin()->persist();

        // mistake request id with something else
        $id = "$request->user_id/$user->id/$token->token";
        $this->getJson("/account-recovery/requests/$id.json");
        $this->assertResponseError('The request could not be found.');

        $this->assertEmailQueueCount($nAdmins);
        foreach ($admins as $admin) {
            $this->assertEmailInBatchContains(
                "An account recovery request was attempted from a user with client IP $clientIp for {$user->profile->first_name}.",
                $admin->username
            );
            $this->assertEmailInBatchContains('The request could not be found in the database.', $admin->username);
        }
    }
}

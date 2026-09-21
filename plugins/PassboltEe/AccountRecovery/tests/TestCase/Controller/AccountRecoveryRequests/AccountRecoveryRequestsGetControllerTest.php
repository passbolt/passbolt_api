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
use Passbolt\Log\Test\Factory\ActionFactory;
use Passbolt\Rbacs\RbacsPlugin;
use Passbolt\Rbacs\Test\Factory\RbacFactory;

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
            $name = $user->profile->first_name . ' ' . $user->profile->last_name;
            $this->assertEmailInBatchContains(
                "An account recovery request was attempted from a user with client IP $clientIp for $name.",
                $admin->username,
            );
            $this->assertEmailInBatchContains('The request could not be found in the database.', $admin->username);
        }
    }

    public function testAccountRecoveryRequestsGetController_Bad_Request_ID_NotifiesRbacViewerAndExcludesRequester()
    {
        $this->enableFeaturePlugin(RbacsPlugin::class);
        [$request, $user, $token] = AccountRecoveryRequestScenario::startContinueScenarioApproved();

        $clientIp = 'Foo';
        $this->configRequest(['environment' => ['REMOTE_ADDR' => $clientIp]]);

        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->active()->admin()->persist();
        /** @var \App\Model\Entity\User $rbacViewer */
        $rbacViewer = UserFactory::make()->persist();
        $action = ActionFactory::make()->name('AccountRecoveryRequestsView.view')->persist();
        RbacFactory::make()->setAction($action)->setField('role_id', $rbacViewer->role_id)->persist();

        $id = "$request->user_id/$user->id/$token->token";
        $this->getJson("/account-recovery/requests/$id.json");
        $this->assertResponseError('The request could not be found.');

        $name = $user->profile->first_name . ' ' . $user->profile->last_name;
        $this->assertEmailInBatchContains(
            "An account recovery request was attempted from a user with client IP $clientIp for $name.",
            $rbacViewer->username,
        );
        $this->assertEmailInBatchContains(
            "An account recovery request was attempted from a user with client IP $clientIp for $name.",
            $admin->username,
        );
        $this->assertEmailWithRecipientIsInNotQueue($user->username);
    }
}

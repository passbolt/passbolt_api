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
use App\Utility\UuidFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryRequestFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryIntegrationTestCase;

class AccountRecoveryRequestsGetControllerTest extends AccountRecoveryIntegrationTestCase
{
    use EmailQueueTrait;

    /**
     * Successful test case
     */
    public function testAccountRecoveryRequestsPostController_Success()
    {
        $user = UserFactory::make()->active()->persist();
        $request = AccountRecoveryRequestFactory::make()
            ->withUserAndToken($user->id)
            ->pending()
            ->persist();

        $this->getJson(
            "/account-recovery/requests/$request->id/$request->user_id/$request->authentication_token_id.json"
        );
        $this->assertResponseOk();
    }

    /**
     * @Given a correct user ID and token ID was provided
     * @When a wrong request ID is provided
     * @Then a potential security issue will be notified to admins
     */
    public function testAccountRecoveryRequestsPostController_Bad_Request_ID()
    {
        $clientIp = 'Foo';
        $this->configRequest(['environment' => ['REMOTE_ADDR' => $clientIp]]);
        $user = UserFactory::make()->active()->persist();
        $nAdmins = 3;
        $admins = UserFactory::make($nAdmins)->active()->admin()->persist();
        $request = AccountRecoveryRequestFactory::make()
            ->withUserAndToken($user->id)
            ->pending()
            ->persist();

        $token = $request->authentication_token;
        $requestId = UuidFactory::uuid();

        $this->getJson("/account-recovery/requests/$requestId/$user->id/$token->id.json");
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

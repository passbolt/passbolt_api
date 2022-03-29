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

namespace Passbolt\AccountRecovery\Test\TestCase\Controller\Users;

use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryRequestFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryIntegrationTestCase;

class UsersIndexContainPendingAccountRecoveryRequestControllerTest extends AccountRecoveryIntegrationTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        RoleFactory::make()->guest()->persist();
    }

    public function testUsersIndexGetSuccess_ContainPendingAccountRecoveryRequest()
    {
        [$userWithPending, $userWithCompleted] = UserFactory::make(2)
            ->active()
            ->persist();
        $pendingRequest = AccountRecoveryRequestFactory::make()
            ->withUser($userWithPending->id)
            ->pending()
            ->persist();
        AccountRecoveryRequestFactory::make(3) // Creates also a few completed request to spice it up
            ->withUser($userWithPending->id)
            ->completed()
            ->persist();
        AccountRecoveryRequestFactory::make()
            ->withUser($userWithCompleted->id)
            ->completed()
            ->persist();

        ### Login as non admin
        $this->logInAsUser();
        $this->getJson('/users.json?contain[pending_account_recovery_request]=1');
        $this->assertResponseOk();
        $this->assertCount(3, $this->_responseJsonBody);
        foreach ($this->_responseJsonBody as $user) {
            $this->assertObjectNotHasAttribute('pending_account_recovery_request', $user);
        }

        ### Login as admin
        $this->logInAsAdmin();
        $this->getJson('/users.json?contain[pending_account_recovery_request]=1');
        $this->assertResponseOk();
        $this->assertCount(4, $this->_responseJsonBody);

        foreach ($this->_responseJsonBody as $user) {
            if ($user->id === $userWithPending->id) {
                $request = (array)$user->pending_account_recovery_request;
                $this->assertCount(2, $request);
                $this->assertSame($pendingRequest->id, $request['id']);
                $this->assertSame(AccountRecoveryRequest::ACCOUNT_RECOVERY_REQUEST_PENDING, $request['status']);
            } else {
                $this->assertSame(null, $user->pending_account_recovery_request);
            }
        }
    }
}

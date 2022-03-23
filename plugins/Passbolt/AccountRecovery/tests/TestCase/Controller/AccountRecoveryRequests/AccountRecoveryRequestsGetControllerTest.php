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

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Utility\UuidFactory;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryRequestFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryIntegrationTestCase;

class AccountRecoveryRequestsGetControllerTest extends AccountRecoveryIntegrationTestCase
{
    use EmailQueueTrait;

    public function setUp(): void
    {
        parent::setUp();
        EventManager::instance()->setEventList(new EventList());
    }

    /**
     * Successful test case
     */
    public function testAccountRecoveryRequestsPostController_Success()
    {
        $user = UserFactory::make()->user()->withAvatar()->persist();

        $token = AuthenticationTokenFactory::make()
            ->type(AuthenticationToken::TYPE_RECOVER)
            ->userId($user->id)
            ->active()
            ->persist();

        $request = AccountRecoveryRequestFactory::make()
            ->withUser($user->id)
            ->withToken($token->id)
            ->persist();

        $this->getJson("/account-recovery/requests/$request->id/$user->id/$token->id.json");
        $this->assertResponseOk();
    }

    /**
     * Successful test case
     */
    public function testAccountRecoveryRequestsPostController_Bad_Request_ID()
    {
        $user = UserFactory::make()->user()->withAvatar()->persist();

        $token = AuthenticationTokenFactory::make()
            ->type(AuthenticationToken::TYPE_RECOVER)
            ->userId($user->id)
            ->active()
            ->persist();

        $requestId = UuidFactory::uuid();

        $this->getJson("/account-recovery/requests/$requestId/$user->id/$token->id.json");
        $this->assertResponseError('The request could not be found.');
    }
}

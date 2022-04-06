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
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Cake\Routing\Router;
use Passbolt\AccountRecovery\Service\AccountRecoveryRequests\AccountRecoveryRequestCreateService;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPolicyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryRequestFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryUserSettingFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryIntegrationTestCase;

class AccountRecoveryRequestsCreateControllerTest extends AccountRecoveryIntegrationTestCase
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
    public function testAccountRecoveryRequestsCreateController_ErrorNotGuest()
    {
        $this->logInAsUser();
        $this->postJson('/account-recovery/requests.json', []);
        $this->assertError(403);
    }

    /**
     * Successful test case
     */
    public function testAccountRecoveryRequestsCreateController_Success()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->optin()
            ->withAccountRecoveryOrganizationPublicKey()
            ->persist();

        $user = UserFactory::make()->user()->withAvatar()->persist();
        $nAdmins = 3;
        $admins = UserFactory::make($nAdmins)->admin()->persist();
        $data = AccountRecoveryRequestFactory::make()->rsa4096Key()->getEntity();

        AccountRecoveryUserSettingFactory::make()
            ->setField('user_id', $user->id)
            ->approved()
            ->persist();

        $token = AuthenticationTokenFactory::make()
            ->type(AuthenticationToken::TYPE_RECOVER)
            ->userId($user->id)
            ->active()
            ->persist();

        $payload = [
            'authentication_token' => [
                'token' => $token->token,
            ],
            'user_id' => $user->id,
            'fingerprint' => $data->fingerprint,
            'armored_key' => $data->armored_key,
        ];

        $this->postJson('/account-recovery/requests.json', $payload);
        $this->assertResponseOk();

        $this->assertTrue(AuthenticationTokenFactory::get($token->id)->isActive());
        $request = AccountRecoveryRequestFactory::find()->firstOrFail();

        $this->assertEventFired(AccountRecoveryRequestCreateService::REQUEST_CREATED_EVENT_NAME);

        $this->assertEmailQueueCount($nAdmins + 1);

        $this->assertEmailInBatchContains('You just requested an account recovery');
        foreach ($admins as $admin) {
            $this->assertEmailInBatchContains(
                $user->profile->first_name . ' has initiated an account recovery request',
                $admin->username
            );
            $this->assertEmailInBatchContains(
                Router::url('/app/account-recovery/requests/review/' . $request->get('id'), true),
                $admin->username
            );
        }
    }
}

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
use Passbolt\AccountRecovery\Controller\AccountRecoveryRequests\AccountRecoveryRequestsPostController;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryIntegrationTestCase;

class AccountRecoveryRequestsPostControllerTest extends AccountRecoveryIntegrationTestCase
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
        $nAdmins = 3;
        $admins = UserFactory::make($nAdmins)->admin()->persist();

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
            'fingerprint' => 'EB85BB5FA33A75E15E944E63F231550C4F47E38E',
            'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'),
        ];

        $this->postJson('/account-recovery/requests.json', $payload);
        $this->assertResponseOk();

        $this->assertEventFired(AccountRecoveryRequestsPostController::REQUEST_CREATED_EVENT_NAME);

        $this->assertEmailQueueCount($nAdmins + 1);

        $this->assertEmailInBatchContains('You just requested an account recovery');
        $i = 0;
        foreach ($admins as $admin) {
            $i++;
            $this->assertEmailInBatchContains($user->profile->first_name . ' has initiated an account recovery request', $i);
        }
    }
}

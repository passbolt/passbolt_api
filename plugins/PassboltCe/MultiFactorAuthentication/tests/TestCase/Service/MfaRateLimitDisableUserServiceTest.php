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
 * @since         5.17.0
 */

namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Service;

use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\User;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\Core\Configure;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Passbolt\MultiFactorAuthentication\MultiFactorAuthenticationPlugin;
use Passbolt\MultiFactorAuthentication\Service\MfaRateLimitDisableUserService;

/**
 * @covers \Passbolt\MultiFactorAuthentication\Service\MfaRateLimitDisableUserService
 */
class MfaRateLimitDisableUserServiceTest extends AppTestCase
{
    private MfaRateLimitDisableUserService $service;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(MultiFactorAuthenticationPlugin::class);
        EventManager::instance()->setEventList(new EventList());
        Configure::write(MfaRateLimitDisableUserService::CONFIG_KEY, true);
        $this->service = new MfaRateLimitDisableUserService();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testMfaRateLimitDisableUserService_Success()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        AuthenticationTokenFactory::make(2)
            ->active()
            ->type(AuthenticationToken::TYPE_REFRESH_TOKEN)
            ->userId($user->id)
            ->persist();
        // A token of another flow must not be touched.
        $mfaToken = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_MFA)
            ->userId($user->id)
            ->persist();

        $result = $this->service->disable($user->id);

        $this->assertTrue($result);
        $this->assertNotNull(UserFactory::get($user->id)->get('disabled'));
        // deactivates refresh tokens
        $activeRefreshTokens = AuthenticationTokenFactory::find()
            ->where([
                'user_id' => $user->id,
                'type' => AuthenticationToken::TYPE_REFRESH_TOKEN,
                'active' => true,
            ])
            ->all()
            ->count();
        $this->assertSame(0, $activeRefreshTokens);
        $this->assertTrue(AuthenticationTokenFactory::get($mfaToken->get('id'))->get('active'));
        $this->assertEventFired(MfaRateLimitDisableUserService::EVENT_USER_WAS_DISABLED_ON_MFA_RATE_LIMIT);
    }

    public function testMfaRateLimitDisableUserService_Success_DispatchesEvent()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();

        $this->service->disable($user->id);

        $this->assertEventFired(MfaRateLimitDisableUserService::EVENT_USER_WAS_DISABLED_ON_MFA_RATE_LIMIT);
        $eventUser = null;
        foreach (EventManager::instance()->getEventList() as $event) {
            if ($event->getName() === MfaRateLimitDisableUserService::EVENT_USER_WAS_DISABLED_ON_MFA_RATE_LIMIT) {
                $eventUser = $event->getData('user');
            }
        }
        $this->assertInstanceOf(User::class, $eventUser);
        $this->assertSame($user->id, $eventUser->id);
        // The redactors consuming this event need the profile and the role.
        $this->assertNotEmpty($eventUser->profile);
        $this->assertNotEmpty($eventUser->role);
    }

    public function testMfaRateLimitDisableUserService_Failure_DoesNotDisableLastAdministrator()
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        // Neither of these can administrate the organization.
        UserFactory::make()->admin()->disabled()->persist();
        UserFactory::make()->user()->persist();

        $result = $this->service->disable($admin->id);

        $this->assertFalse($result);
        $this->assertNull(UserFactory::get($admin->id)->get('disabled'));
        $eventList = EventManager::instance()->getEventList();
        $this->assertFalse(
            $eventList->hasEvent(MfaRateLimitDisableUserService::EVENT_USER_WAS_DISABLED_ON_MFA_RATE_LIMIT),
        );
    }

    public function testMfaRateLimitDisableUserService_Success_DisablesAdministratorWhenAnotherOneRemains()
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        UserFactory::make()->admin()->persist();

        $result = $this->service->disable($admin->id);

        $this->assertTrue($result);
        $this->assertNotNull(UserFactory::get($admin->id)->get('disabled'));
    }
}

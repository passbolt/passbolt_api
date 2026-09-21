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
 * @since         5.13.0
 */

namespace Passbolt\JwtAuthentication\Test\TestCase\Event;

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use Cake\Event\Event;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\Edition\Service\EditionDowngradeService;
use Passbolt\Edition\Service\EditionUpgradeService;
use Passbolt\JwtAuthentication\Event\JwtAuthenticationLogoutAllUsersOnEditionChangeListener;

/**
 * @covers \Passbolt\JwtAuthentication\Event\JwtAuthenticationLogoutAllUsersOnEditionChangeListener
 */
class JwtAuthenticationLogoutAllUsersOnEditionChangeListenerTest extends TestCase
{
    use LocatorAwareTrait;
    use TruncateDirtyTables;

    private JwtAuthenticationLogoutAllUsersOnEditionChangeListener $listener;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->listener = new JwtAuthenticationLogoutAllUsersOnEditionChangeListener();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->listener);
        parent::tearDown();
    }

    public function testJwtAuthenticationLogoutAllUsersOnEditionChangeListener_OnDowngrade_DeactivatesAllRefreshTokensAcrossAllUsers(): void
    {
        $this->seedRefreshTokensForUsers(3, 2);

        $this->listener->invalidateRefreshTokens(
            new Event(EditionDowngradeService::EVENT_EDITION_DOWNGRADED),
        );

        $this->assertActiveRefreshTokenCount(0);
        $this->assertInactiveRefreshTokenCount(6);
    }

    public function testJwtAuthenticationLogoutAllUsersOnEditionChangeListener_OnDowngrade_LeavesNonRefreshAndInactiveTokensUntouched(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_REFRESH_TOKEN)
            ->userId($user->id)
            ->persist();
        AuthenticationTokenFactory::make()
            ->inactive()
            ->type(AuthenticationToken::TYPE_REFRESH_TOKEN)
            ->userId($user->id)
            ->persist();
        AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_LOGIN)
            ->userId($user->id)
            ->persist();

        $this->listener->invalidateRefreshTokens(
            new Event(EditionDowngradeService::EVENT_EDITION_DOWNGRADED),
        );

        $activeLoginTokens = AuthenticationTokenFactory::find()
            ->where(['type' => AuthenticationToken::TYPE_LOGIN, 'active' => true])
            ->count();
        $this->assertSame(1, $activeLoginTokens);
        $this->assertInactiveRefreshTokenCount(2);
        $this->assertActiveRefreshTokenCount(0);
    }

    public function testJwtAuthenticationLogoutAllUsersOnEditionChangeListener_OnDowngrade_NoOpWhenNoActiveRefreshTokens(): void
    {
        $this->listener->invalidateRefreshTokens(
            new Event(EditionDowngradeService::EVENT_EDITION_DOWNGRADED),
        );

        $this->assertActiveRefreshTokenCount(0);
    }

    public function testJwtAuthenticationLogoutAllUsersOnEditionChangeListener_OnUpgrade_DeactivatesAllRefreshTokensAcrossAllUsers(): void
    {
        $this->seedRefreshTokensForUsers(3, 2);

        $this->listener->invalidateRefreshTokens(
            new Event(EditionUpgradeService::EVENT_NAME),
        );

        $this->assertActiveRefreshTokenCount(0);
        $this->assertInactiveRefreshTokenCount(6);
    }

    public function testJwtAuthenticationLogoutAllUsersOnEditionChangeListener_ImplementedEvents_SubscribesBothEvents(): void
    {
        $events = $this->listener->implementedEvents();

        $this->assertArrayHasKey(EditionDowngradeService::EVENT_EDITION_DOWNGRADED, $events);
        $this->assertArrayHasKey(EditionUpgradeService::EVENT_NAME, $events);
        $this->assertSame('invalidateRefreshTokens', $events[EditionDowngradeService::EVENT_EDITION_DOWNGRADED]);
        $this->assertSame('invalidateRefreshTokens', $events[EditionUpgradeService::EVENT_NAME]);
    }

    // ---------------------------
    // Helper methods
    // ---------------------------

    private function seedRefreshTokensForUsers(int $userCount, int $tokensPerUser): void
    {
        /** @var array<\App\Model\Entity\User> $users */
        $users = UserFactory::make($userCount)->user()->persist();
        foreach ($users as $user) {
            AuthenticationTokenFactory::make($tokensPerUser)
                ->active()
                ->type(AuthenticationToken::TYPE_REFRESH_TOKEN)
                ->userId($user->id)
                ->persist();
        }
    }

    private function assertActiveRefreshTokenCount(int $expected): void
    {
        $this->assertSame(
            $expected,
            AuthenticationTokenFactory::find()
                ->where(['type' => AuthenticationToken::TYPE_REFRESH_TOKEN, 'active' => true])
                ->count(),
        );
    }

    private function assertInactiveRefreshTokenCount(int $expected): void
    {
        $this->assertSame(
            $expected,
            AuthenticationTokenFactory::find()
                ->where(['type' => AuthenticationToken::TYPE_REFRESH_TOKEN, 'active' => false])
                ->count(),
        );
    }
}

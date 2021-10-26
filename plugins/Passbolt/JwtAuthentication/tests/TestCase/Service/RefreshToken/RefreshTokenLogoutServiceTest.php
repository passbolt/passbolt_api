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
 * @since         3.3.0
 */

namespace Passbolt\JwtAuthentication\Test\TestCase\Service\RefreshToken;

use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\User;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use Cake\Datasource\ModelAwareTrait;
use Cake\TestSuite\TestCase;
use Passbolt\JwtAuthentication\Error\Exception\RefreshToken\RefreshTokenNotFoundException;
use Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenLogoutService;

/**
 * @covers \Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenLogoutService
 * @property \App\Model\Table\AuthenticationTokensTable $AuthenticationTokens
 */
class RefreshTokenLogoutServiceTest extends TestCase
{
    use ModelAwareTrait;

    public $service;

    public function setUp(): void
    {
        $this->loadModel('AuthenticationTokens');
        $this->service = new RefreshTokenLogoutService();
    }

    public function tearDown(): void
    {
        unset($this->service);
    }

    public function testRefreshTokenLogoutServiceTest_Logout_With_WrongToken()
    {
        $user = UserFactory::make()->persist();
        $this->expectExceptionMessage(RefreshTokenNotFoundException::class);
        $this->expectExceptionMessage('No active refresh token matching the request could be found.');
        $this->service->logout($user->id, 'Bar');
    }

    public function testRefreshTokenLogoutServiceTest_Logout_With_Token()
    {
        $user = UserFactory::make()->persist();
        $tokenToDeactivate = $this->persistTokenSet($user);

        $deactivated = $this->service->logout($user->id, $tokenToDeactivate->token);

        $this->assertSame(1, $deactivated);
        $this->assertSame(5, $this->AuthenticationTokens->find()->where(['active' => true])->count());
        $this->assertTrue($this->AuthenticationTokens->exists([
            'active' => false,
            'id' => $tokenToDeactivate->id,
        ]));
    }

    public function testRefreshTokenLogoutServiceTest_Logout_Without_Token()
    {
        $user = UserFactory::make()->persist();
         $this->persistTokenSet($user);

        $deactivated = $this->service->logout($user->id, null);

        $this->assertSame(2, $deactivated);
        $this->assertSame(4, $this->AuthenticationTokens->find()->where(['active' => true])->count());
        $this->assertSame(2, $this->AuthenticationTokens->find()->where([
            'active' => false,
            'user_id' => $user->id,
            'type' => AuthenticationToken::TYPE_REFRESH_TOKEN,
        ])->count());
    }

    /**
     * Create a set of token.
     *
     * @param User $user
     * @return AuthenticationToken A refresh token.
     */
    private function persistTokenSet(User $user): AuthenticationToken
    {
        // Refresh tokens associated to user
        /** @var array $refreshTokens */
        $refreshTokens = AuthenticationTokenFactory::make(2)
            ->active()
            ->userId($user->id)
            ->type(AuthenticationToken::TYPE_REFRESH_TOKEN)
            ->persist();

        // Random tokens associated to user
        AuthenticationTokenFactory::make(2)
            ->active()
            ->userId($user->id)
            ->persist();

        // Random tokens
        AuthenticationTokenFactory::make(2)
            ->active()
            ->persist();

        return $refreshTokens[0];
    }
}

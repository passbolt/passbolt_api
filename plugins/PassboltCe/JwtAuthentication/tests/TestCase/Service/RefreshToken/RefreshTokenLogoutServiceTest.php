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
use App\Utility\UuidFactory;
use Cake\Http\ServerRequest;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\JwtAuthentication\Error\Exception\RefreshToken\RefreshTokenNotFoundException;
use Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenLogoutService;

/**
 * @covers \Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenLogoutService
 */
class RefreshTokenLogoutServiceTest extends TestCase
{
    use LocatorAwareTrait;
    use TruncateDirtyTables;

    public $service;

    /**
     * @var \App\Model\Table\AuthenticationTokensTable
     */
    protected $AuthenticationTokens;

    public function setUp(): void
    {
        parent::setUp();
        $this->AuthenticationTokens = $this->fetchTable('AuthenticationTokens');
        $this->service = new RefreshTokenLogoutService();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->service);
    }

    public function testRefreshTokenLogoutServiceTest_Logout_With_NonUUID_Refresh_Token()
    {
        $this->expectExceptionMessage(RefreshTokenNotFoundException::class);
        $this->expectExceptionMessage('The refresh token should be a valid UUID.');
        $request = (new ServerRequest())->withData($this->service::REFRESH_TOKEN_DATA_KEY, 'Bar');
        $this->service->logout(UuidFactory::uuid(), $request);
    }

    public function testRefreshTokenLogoutServiceTest_Logout_With_WrongToken()
    {
        $this->expectExceptionMessage(RefreshTokenNotFoundException::class);
        $this->expectExceptionMessage('No active refresh token matching the request could be found.');
        $request = (new ServerRequest())->withData($this->service::REFRESH_TOKEN_DATA_KEY, UuidFactory::uuid());
        $this->service->logout(UuidFactory::uuid(), $request);
    }

    public function testRefreshTokenLogoutServiceTest_Logout_With_Valid_Token()
    {
        $user = UserFactory::make()->persist();
        $tokenToDeactivate = $this->persistTokenSet($user);
        $request = (new ServerRequest())
            ->withData($this->service::REFRESH_TOKEN_DATA_KEY, $tokenToDeactivate->token)
            ->withData('user_id', UuidFactory::uuid()); // This mismatch is ignored by the JwtLogoutController

        $deactivated = $this->service->logout($user->id, $request);

        $this->assertSame(1, $deactivated);
        $this->assertSame(5, AuthenticationTokenFactory::find()->where(['active' => true])->count());
        $this->assertTrue($this->AuthenticationTokens->exists([
            'active' => false,
            'id' => $tokenToDeactivate->id,
        ]));
    }

    public function testRefreshTokenLogoutServiceTest_Logout_Without_Token()
    {
        $user = UserFactory::make()->persist();
        $this->persistTokenSet($user);

        $deactivated = $this->service->logout($user->id, new ServerRequest());

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
     * 1 active target
     * 1 active of the same user as the target
     * 2 active same user as the target of another type
     * 2 active of another user and another type
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

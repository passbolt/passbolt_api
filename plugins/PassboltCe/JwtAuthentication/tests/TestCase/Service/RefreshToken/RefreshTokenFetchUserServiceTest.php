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
use App\Test\Factory\AuthenticationTokenFactory;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\JwtAuthentication\Error\Exception\RefreshToken\RefreshTokenNotFoundException;
use Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenAuthenticationService;

/**
 * @covers \Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenRenewalService
 */
class RefreshTokenFetchUserServiceTest extends TestCase
{
    use LocatorAwareTrait;
    use TruncateDirtyTables;

    /**
     * @var \App\Model\Table\AuthenticationTokensTable
     */
    protected $AuthenticationTokens;

    public function setUp(): void
    {
        parent::setUp();
        $this->AuthenticationTokens = $this->fetchTable('AuthenticationTokens');
    }

    public function testRefreshTokenFetchUserService_getUserIdFromToken_Success()
    {
        $refreshToken = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_REFRESH_TOKEN)
            ->persist();

        $service = (new RefreshTokenAuthenticationService());
        $this->assertSame($refreshToken->user_id, $service->getUserIdFromToken($refreshToken->token));
    }

    /**
     * No users are found for the refresh token type.
     */
    public function testRefreshTokenFetchUserService_getUserIdFromToken_No_User_Found()
    {
        $refreshToken = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_RECOVER)
            ->persist();

        $this->expectException(RefreshTokenNotFoundException::class);
        $this->expectExceptionMessage('No active refresh token matching the request could be found.');

        (new RefreshTokenAuthenticationService())->getUserIdFromToken($refreshToken->token);
    }
}

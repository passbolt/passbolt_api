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

namespace Passbolt\JwtAuthentication\Test\TestCase\Service\AccessToken;

use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;
use Cake\TestSuite\TestCase;
use Firebase\JWT\ExpiredException;
use Passbolt\JwtAuthentication\Service\AccessToken\JwtTokenCreateService;
use Passbolt\JwtAuthentication\Test\Utility\JwtTestTrait;

/**
 * @covers \Passbolt\JwtAuthentication\Service\AccessToken\JwtTokenCreateService
 */
class JwtTokenCreateServiceTest extends TestCase
{
    use JwtTestTrait;

    public function tokenExpiration(): array
    {
        return [
            ['5 minutes'],
            ['5 seconds'],
            ['0 seconds', ExpiredException::class],
            [null, InternalErrorException::class],
        ];
    }

    /**
     * @dataProvider tokenExpiration
     */
    public function testJwtTokenCreateService_CreateToken($expiration, ?string $exception = null)
    {
        $backUp = Configure::read(JwtTokenCreateService::JWT_EXPIRY_CONFIG_KEY);
        Configure::write(JwtTokenCreateService::JWT_EXPIRY_CONFIG_KEY, $expiration);
        $userId = UuidFactory::uuid();

        if ($exception) {
            $this->expectException($exception);
        }

        $secretToken = (new JwtTokenCreateService())->createToken($userId);
        $this->assertAccessTokenIsValid($secretToken, $userId);
        Configure::write(JwtTokenCreateService::JWT_EXPIRY_CONFIG_KEY, $backUp);
    }

    public function dataForTestJwtTokenCreateService_createExpiryDate()
    {
        return [
            [null, 300],
            ['60 seconds', 60],
            ['2 minutes', 120],
        ];
    }

    /**
     * @dataProvider dataForTestJwtTokenCreateService_createExpiryDate
     */
    public function testJwtTokenCreateService_createExpiryDate($date, $expectedTime)
    {
        $expiryDate = (new JwtTokenCreateService())->createExpiryDate($date);
        $expectedUXTime = time() + $expectedTime;

        // Allow a difference of 1 second for test processing time.
        $this->assertLessThanOrEqual(1, $expiryDate - $expectedUXTime);
    }

    /**
     * JWT access tokens generated within the same second are identical, provided the user ID
     * is the same. If we generate 3 JWT access tokens, we are sure that at two will be
     * in the same second, and at least two shall be identical.
     */
    public function testJwtTokenCreateService_Multiple_Token_Within_One_Same_Second_Should_Be_Identical()
    {
        $n = 3;
        $userId = UuidFactory::uuid();
        $allTokens = [];
        for ($i = 0; $i < $n; $i++) {
            $allTokens[] = (new JwtTokenCreateService())->createToken($userId);
        }

        $this->assertNotSame($allTokens, array_unique($allTokens));
    }
}

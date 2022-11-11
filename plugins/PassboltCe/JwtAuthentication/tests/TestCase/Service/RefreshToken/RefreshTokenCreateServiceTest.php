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

use App\Test\Factory\UserFactory;
use Cake\Core\Configure;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Cake\Http\ServerRequest;
use Cake\I18n\FrozenTime;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenAbstractService;
use Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenCreateService;

/**
 * @covers \Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenCreateService
 */
class RefreshTokenCreateServiceTest extends TestCase
{
    use TruncateDirtyTables;

    public function setUp(): void
    {
        parent::setUp();
        EventManager::instance()->setEventList(new EventList());
    }

    public function testRefreshTokenCreateService()
    {
        $cookieExpirationTime = '15 days';
        Configure::write(RefreshTokenAbstractService::REFRESH_TOKEN_EXPIRY_CONFIG_KEY, $cookieExpirationTime);
        $expectedExpiration = (new FrozenTime('+' . $cookieExpirationTime))->toUnixString();
        $userId = UserFactory::make()->persist()->id;
        $accessToken = 'Foo';

        $token = (new RefreshTokenCreateService())->createToken(new ServerRequest(), $userId, $accessToken);
        $cookie = (new RefreshTokenCreateService())->createHttpOnlySecureCookie($token);
        /** @var FrozenTime $expiration */
        $expiration = $cookie->getExpiry();

        $this->assertTrue($token->checkSessionId($accessToken));
        $this->assertTrue($cookie->isSecure());
        $this->assertTrue($cookie->isHttpOnly());
        $this->assertFalse($cookie->isExpired());
        // Allow a difference of five seconds second for CPU process
        $this->assertLessThanOrEqual(5, (int)$expiration->toUnixString() - (int)$expectedExpiration);

        // Assert that the create refresh token event is dispatched
        $this->assertEventFired(RefreshTokenCreateService::REFRESH_TOKEN_CREATED_EVENT);
    }
}

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
use App\Test\Factory\UserFactory;
use Cake\Datasource\ModelAwareTrait;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Cake\TestSuite\TestCase;
use Passbolt\JwtAuthentication\Error\Exception\RefreshToken\ConsumedRefreshTokenAccessException;
use Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenCreateService;
use Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenRenewalService;

/**
 * @covers \Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenRenewalService
 * @property \App\Model\Table\AuthenticationTokensTable $AuthenticationTokens
 */
class RefreshTokenRenewalServiceTest extends TestCase
{
    use ModelAwareTrait;

    public function setUp(): void
    {
        $this->loadModel('AuthenticationTokens');
        EventManager::instance()->setEventList(new EventList());
    }

    public function testRefreshTokenRenewalService_WithNoExistingRefreshCookie()
    {
        $userId = UserFactory::make()->persist()->id;
        $authToken = (new RefreshTokenCreateService())->createToken($userId);

        $tokenInTheRequest = $this->AuthenticationTokens->find()->firstOrFail();

        $someUserTokenNotInvolvedInTheRenewal = AuthenticationTokenFactory::make()
            ->type(AuthenticationToken::TYPE_REFRESH_TOKEN)
            ->active()
            ->userId($userId)
            ->persist();

        $service = new RefreshTokenRenewalService($userId, $authToken->token);
        $newToken = $service->renewToken();
        $cookie = $service->createHttpOnlySecureCookie($newToken);

        $this->assertTrue($this->AuthenticationTokens->exists(['id' => $someUserTokenNotInvolvedInTheRenewal->id]));
        $this->assertTrue($this->AuthenticationTokens->exists([
            'token' => $cookie->getValue(),
            'active' => true,
            'user_id' => $userId,
        ]));
        $this->assertTrue($this->AuthenticationTokens->exists(['id' => $tokenInTheRequest->id, 'active' => false]));
    }

    public function testRefreshTokenRenewalService_Renew_On_Consumed_Token()
    {
        $userId = UserFactory::make()->persist()->id;
        $authToken = (new RefreshTokenCreateService())->createToken($userId);

        $service = new RefreshTokenRenewalService($userId, $authToken->token);
        // This is O.K.
        $service->renewToken();

        // This is not O.K., should throw an excepion and should send an Email to both user and admin
        $this->expectException(ConsumedRefreshTokenAccessException::class);
        $this->expectExceptionMessage('The refresh token provided was already used.');
        $service->renewToken();
        $this->assertEventFired(ConsumedRefreshTokenAccessException::class);
    }
}

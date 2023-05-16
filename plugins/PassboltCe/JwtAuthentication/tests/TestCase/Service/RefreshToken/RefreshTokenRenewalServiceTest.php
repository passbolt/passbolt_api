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
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Cake\Http\ServerRequest;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\JwtAuthentication\Error\Exception\RefreshToken\ConsumedRefreshTokenAccessException;
use Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenCreateService;
use Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenRenewalService;

/**
 * @covers \Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenRenewalService
 */
class RefreshTokenRenewalServiceTest extends TestCase
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
        EventManager::instance()->setEventList(new EventList());
    }

    public function testRefreshTokenRenewalService_WithNoExistingRefreshCookie()
    {
        $userId = UserFactory::make()->persist()->id;
        $newAccessToken = 'Bar';
        $authToken = (new RefreshTokenCreateService())->createToken(new ServerRequest(), $userId, 'Foo');

        $tokenInTheRequest = $this->AuthenticationTokens->find()->firstOrFail();

        $someUserTokenNotInvolvedInTheRenewal = AuthenticationTokenFactory::make()
            ->type(AuthenticationToken::TYPE_REFRESH_TOKEN)
            ->active()
            ->userId($userId)
            ->persist();

        $service = new RefreshTokenRenewalService();
        $newToken = $service->renewToken(new ServerRequest(), $authToken, $newAccessToken);
        $cookie = $service->createHttpOnlySecureCookie($newToken);

        $this->assertTrue($this->AuthenticationTokens->exists(['id' => $someUserTokenNotInvolvedInTheRenewal->id]));
        /** @var AuthenticationToken $newRefreshToken */
        $newRefreshToken = $this->AuthenticationTokens->find()->where([
            'type' => AuthenticationToken::TYPE_REFRESH_TOKEN,
            'token' => $cookie->getValue(),
            'active' => true,
            'user_id' => $userId,
        ])->firstOrFail();

        $this->assertTrue($newRefreshToken->checkSessionId($newAccessToken));
        $this->assertTrue($this->AuthenticationTokens->exists(['id' => $tokenInTheRequest->get('id'), 'active' => false]));
    }

    public function testRefreshTokenRenewalService_Renew_On_Consumed_Token()
    {
        $userId = UserFactory::make()->persist()->id;
        $authToken = (new RefreshTokenCreateService())->createToken(new ServerRequest(), $userId, 'Foo');

        $service = new RefreshTokenRenewalService();
        // This is O.K. to renew once
        $service->renewToken(new ServerRequest(), $authToken, 'Bar');

        // This is not O.K. to renew again, should throw an exception and should send an Email to both user and admin
        $this->expectException(ConsumedRefreshTokenAccessException::class);
        $this->expectExceptionMessage('The refresh token provided was already used.');
        $service->renewToken(new ServerRequest(), $authToken, '');
        $this->assertEventFired(ConsumedRefreshTokenAccessException::class);
    }
}

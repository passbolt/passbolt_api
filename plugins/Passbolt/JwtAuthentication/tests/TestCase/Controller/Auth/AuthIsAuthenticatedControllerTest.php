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
namespace Passbolt\JwtAuthentication\Test\TestCase\Controller\Auth;

use App\Test\Factory\UserFactory;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Passbolt\JwtAuthentication\Event\LogAuthenticationWithNonValidJwtAccessToken;
use Passbolt\JwtAuthentication\Test\Utility\JwtAuthenticationIntegrationTestCase;

class AuthIsAuthenticatedControllerTest extends JwtAuthenticationIntegrationTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // enable event tracking
        EventManager::instance()->setEventList(new EventList());
    }

    public function testIsAuthenticatedWithJwt_Success()
    {
        $user = UserFactory::make()->user()->persist();
        $this->createJwtTokenAndSetInHeader($user->id);
        $this->getJson('/auth/is-authenticated.json');
        $this->assertResponseOk();
        $this->assertTextContains('success', $this->_responseJsonHeader->status);
    }

    public function testIsAuthenticatedWithJwt_ErrorWithInactiveUser()
    {
        $user = UserFactory::make()->user()->inactive()->persist();
        $this->createJwtTokenAndSetInHeader($user->id);
        $this->getJson('/auth/is-authenticated.json');
        $this->assertResponseError();
        $expectedLogMessage = "The access token provided for '/auth/is-authenticated.json' is not valid.";
        $this->assertEventFiredWith(
            LogAuthenticationWithNonValidJwtAccessToken::AUTHENTICATION_WITH_INVALID_ACCESS_TOKEN_EVENT,
            'message',
            $expectedLogMessage
        );
    }

    public function testIsAuthenticatedWithJwt_ErrorWithDeletedUser()
    {
        $user = UserFactory::make()->user()->deleted()->persist();
        $this->createJwtTokenAndSetInHeader($user->id);
        $this->getJson('/auth/is-authenticated.json');
        $this->assertResponseError();
        $this->assertEventFired(LogAuthenticationWithNonValidJwtAccessToken::AUTHENTICATION_WITH_INVALID_ACCESS_TOKEN_EVENT);
    }
}

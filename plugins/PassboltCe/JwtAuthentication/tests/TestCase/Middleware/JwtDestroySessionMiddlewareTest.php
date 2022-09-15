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
namespace Passbolt\JwtAuthentication\Test\TestCase\Middleware;

use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Passbolt\JwtAuthentication\Test\Utility\JwtAuthenticationIntegrationTestCase;

class JwtDestroySessionMiddlewareTest extends JwtAuthenticationIntegrationTestCase
{
    public function testJwtDestroySessionMiddleware()
    {
        $this->enableCsrfToken();

        $fooSessionKey = 'foo';
        $authSessionKey = 'Auth';
        $csrfTokenSessionKey = 'csrfToken';
        $this->session([$fooSessionKey => 'sessionValue',]);

        // In session mode, session is set
        $this->logInAsUser();
        $this->getJson('/auth/is-authenticated.json');
        $this->assertResponseSuccess();
        $this->assertSessionHasKey($fooSessionKey);
        $this->assertSessionHasKey($authSessionKey);
        $this->assertSessionHasKey($csrfTokenSessionKey);

        // In JWT, no session, and session related cookies
        // are expired.
        $this->createJwtTokenAndSetInHeader(UuidFactory::uuid());
        $this->getJson('/auth/is-authenticated.json');
        $this->assertResponseError();

        $this->assertSessionNotHasKey($fooSessionKey);
        $this->assertSessionNotHasKey($authSessionKey);
        $this->assertSessionNotHasKey($csrfTokenSessionKey);
        $this->assertSession(null, '');
        $this->assertCookieExpired('csrfToken');
        $sessionCookie = Configure::read('Session.cookie', session_name());
        $this->assertCookieExpired($sessionCookie);
    }
}

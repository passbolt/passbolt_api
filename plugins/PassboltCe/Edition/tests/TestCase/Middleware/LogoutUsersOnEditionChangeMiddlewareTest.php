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
namespace Passbolt\Edition\Test\TestCase\Middleware;

use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use Cake\Core\Configure;
use Cake\Http\Cookie\CookieCollection;
use Cake\I18n\DateTime;
use Passbolt\Edition\Service\EditionManager;

/**
 * @covers \Passbolt\Edition\Middleware\LogoutUsersOnEditionChangeMiddleware
 */
class LogoutUsersOnEditionChangeMiddlewareTest extends AppIntegrationTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // findIndex (called by /users/me.json) filters out the guest role by id;
        // the lookup returns null and the query errors out unless the role exists.
        RoleFactory::make()->guest()->persist();
    }

    public function tearDown(): void
    {
        // Clear the disable-flag env var so it doesn't leak into other tests
        // — PHP env state is process-wide and `putenv` with no `=` unsets it.
        putenv('PASSBOLT_PLUGINS_EDITION_DISABLE_LOGOUT_USERS_ON_EDITION_CHANGE_MIDDLEWARE');
        parent::tearDown();
    }

    public function testLogoutUsersOnEditionChangeMiddleware_NoOp_WhenNoChangeTimestamp(): void
    {
        // Configure key absent (instance that has never transitioned).
        Configure::delete(EditionManager::CONFIGURE_KEY_LAST_CHANGE_DATETIME);
        $user = UserFactory::make()->user()->active()
            ->setField('last_logged_in', new DateTime('2024-01-01 10:00:00'))
            ->persist();
        $this->logInAs($user);

        $this->getJson('/users/me.json');

        $this->assertResponseOk();
    }

    public function testLogoutUsersOnEditionChangeMiddleware_NoOp_WhenSessionPostDatesEditionChange(): void
    {
        Configure::write(
            EditionManager::CONFIGURE_KEY_LAST_CHANGE_DATETIME,
            new DateTime('2024-06-01 10:00:00'),
        );
        $user = UserFactory::make()->user()->active()
            ->setField('last_logged_in', new DateTime('2024-06-15 12:00:00'))
            ->persist();
        $this->logInAs($user);

        $this->getJson('/users/me.json');

        $this->assertResponseOk();
    }

    public function testLogoutUsersOnEditionChangeMiddleware_NoOp_WhenLastLoggedInIsNull(): void
    {
        // Pre-v5.4 sessions had no tracked login time. Per WP 3.8 spec,
        // treat null as no-op rather than mass-logout legacy users.
        Configure::write(
            EditionManager::CONFIGURE_KEY_LAST_CHANGE_DATETIME,
            new DateTime('2024-06-01 10:00:00'),
        );
        $user = UserFactory::make()->user()->active()
            ->setField('last_logged_in', null)
            ->persist();
        $this->logInAs($user);

        $this->getJson('/users/me.json');

        $this->assertResponseOk();
    }

    public function testLogoutUsersOnEditionChangeMiddleware_NoOp_WhenAnonymous(): void
    {
        Configure::write(
            EditionManager::CONFIGURE_KEY_LAST_CHANGE_DATETIME,
            new DateTime('2024-06-01 10:00:00'),
        );
        // No logInAs — request is anonymous. The endpoint will return its own
        // 401/403, but the middleware must not interfere along the way.
        $this->getJson('/users/me.json');

        // We only care that the middleware did NOT return its own 302/401 here:
        // an anonymous request can't have a "pre-change session." Either
        // outcome from /users/me.json (401 from auth or 403) is acceptable;
        // the failure mode would be the middleware producing a 302 redirect.
        $this->assertNotSame(302, $this->_response->getStatusCode());
    }

    public function testLogoutUsersOnEditionChangeMiddleware_LogsOut_JsonReturns401_WhenSessionPredatesEditionChange(): void
    {
        Configure::write(
            EditionManager::CONFIGURE_KEY_LAST_CHANGE_DATETIME,
            new DateTime('2024-06-15 12:00:00'),
        );
        $user = UserFactory::make()->user()->active()
            ->setField('last_logged_in', new DateTime('2024-06-01 10:00:00'))
            ->persist();
        $this->logInAs($user);

        $this->getJson('/users/me.json');

        $this->assertResponseCode(401);
        // The session cookie is expired so the browser stops sending the
        // pre-change session id on the next request.
        /** @var \Cake\Http\Response $response */
        $response = $this->_response;
        $cookies = $response->getCookieCollection();
        $this->assertTrue($cookies->has((string)session_name()));
        $this->assertTrue($cookies->get((string)session_name())->isExpired());
    }

    public function testLogoutUsersOnEditionChangeMiddleware_LogsOut_ExpiredCookieClonesSessionPath(): void
    {
        // Regression coverage for subpath installs (e.g. host served under
        // `/passbolt/`). The browser only deletes a cookie when the expiry
        // Set-Cookie matches the original by (name, domain, path, secure).
        // If the middleware emitted `Path=/` while the live cookie was set
        // with `Path=/passbolt/`, the browser would ignore the expiry and
        // the pre-change session id would keep being sent.
        //
        // Drive the cookie path via `Session.cookiePath` Configure key —
        // that's the documented operator-facing knob and CakePHP's Session
        // class propagates it to `session.cookie_path` ini, which is what
        // `session_get_cookie_params()` reads inside the middleware.
        Configure::write('Session.cookiePath', '/passbolt/');
        Configure::write(
            EditionManager::CONFIGURE_KEY_LAST_CHANGE_DATETIME,
            new DateTime('2024-06-15 12:00:00'),
        );
        $user = UserFactory::make()->user()->active()
            ->setField('last_logged_in', new DateTime('2024-06-01 10:00:00'))
            ->persist();
        $this->logInAs($user);

        $this->getJson('/users/me.json');

        $this->assertResponseCode(401);
        /** @var \Cake\Http\Response $response */
        $response = $this->_response;
        $sessionName = (string)session_name();
        $cookies = $response->getCookieCollection();
        $this->assertTrue($cookies->has($sessionName));
        $cookie = $cookies->get($sessionName);
        $this->assertTrue($cookie->isExpired());
        $this->assertSame('/passbolt/', $cookie->getPath());
    }

    public function testLogoutUsersOnEditionChangeMiddleware_LogsOut_HtmlReturns302_WhenSessionPredatesEditionChange(): void
    {
        Configure::write(
            EditionManager::CONFIGURE_KEY_LAST_CHANGE_DATETIME,
            new DateTime('2024-06-15 12:00:00'),
        );
        $user = UserFactory::make()->user()->active()
            ->setField('last_logged_in', new DateTime('2024-06-01 10:00:00'))
            ->persist();
        $this->logInAs($user);

        // Non-JSON request — HTML clients get a 302 to the login URL so the
        // browser auto-navigates without needing a manual refresh.
        $this->get('/users/me');

        $this->assertResponseCode(302);
        $this->assertStringContainsString('/auth/login', $this->_response->getHeaderLine('Location'));
    }

    public function testLogoutUsersOnEditionChangeMiddleware_LogsOut_OneSecondDriftIsTreatedAsBefore(): void
    {
        // Boundary: 1 full second earlier counts as pre-change (loose
        // comparison via getTimestamp(), matching the DTO rule).
        Configure::write(
            EditionManager::CONFIGURE_KEY_LAST_CHANGE_DATETIME,
            new DateTime('2024-06-15 12:00:01'),
        );
        $user = UserFactory::make()->user()->active()
            ->setField('last_logged_in', new DateTime('2024-06-15 12:00:00'))
            ->persist();
        $this->logInAs($user);

        $this->getJson('/users/me.json');

        $this->assertResponseCode(401);
    }

    public function testLogoutUsersOnEditionChangeMiddleware_NoOp_WhenLastLoggedInEqualsChangeAt(): void
    {
        // Boundary: same second — strict "<" comparison keeps the session
        // (we don't punish sessions that happened to log in at exactly the
        // edition-change moment).
        $sameInstant = new DateTime('2024-06-15 12:00:00');
        Configure::write(EditionManager::CONFIGURE_KEY_LAST_CHANGE_DATETIME, $sameInstant);
        $user = UserFactory::make()->user()->active()
            ->setField('last_logged_in', $sameInstant)
            ->persist();
        $this->logInAs($user);

        $this->getJson('/users/me.json');

        $this->assertResponseOk();
    }

    public function testLogoutUsersOnEditionChangeMiddleware_NoOp_DuringInProgressLogin(): void
    {
        // In-progress GPG handshake: AuthenticationMiddleware sets `identity`
        // on the request but `persistIdentity()` runs *after* downstream
        // middlewares — so the session does not yet have the `Auth` key when
        // we fire. We approximate this state with an anonymous request: no
        // `logInAs()` means no `Auth` in session either. The middleware must
        // delegate to the handler; if it produced its own 401 the response
        // body would carry the edition-change message, while the controller's
        // own 401 (auth required) does not.
        Configure::write(
            EditionManager::CONFIGURE_KEY_LAST_CHANGE_DATETIME,
            new DateTime('2024-06-15 12:00:00'),
        );

        $this->getJson('/users/me.json');

        $this->assertStringNotContainsString(
            'edition change',
            (string)$this->_response->getBody(),
            'Middleware must not return its own edition-change 401 on requests without an Auth session.',
        );
    }

    public function testLogoutUsersOnEditionChangeMiddleware_NoOp_DuringInProgressLogin_Html(): void
    {
        // HTML counterpart of the JSON test above. Same in-progress-handshake
        // condition (no `Auth` in session) — exercised via a non-JSON request
        // so we cover the 302-to-login branch of buildLogoutResponse(). The
        // unique side-effect of the middleware firing is an expired session
        // cookie on the response; a controller-level redirect to /auth/login
        // for anonymous HTML wouldn't touch the session cookie.
        //
        // Parse Set-Cookie via CookieCollection::createFromHeader rather than
        // $response->getCookieCollection() because the anonymous HTML flow
        // resolves to a PSR-7 RedirectResponse that doesn't carry Cake's
        // cookie-collection accessor.
        Configure::write(
            EditionManager::CONFIGURE_KEY_LAST_CHANGE_DATETIME,
            new DateTime('2024-06-15 12:00:00'),
        );

        $this->get('/users/me');

        $cookies = CookieCollection::createFromHeader(
            $this->_response->getHeader('Set-Cookie'),
        );
        $sessionName = (string)session_name();
        $expiredSessionCookieIssued = $cookies->has($sessionName)
            && $cookies->get($sessionName)->isExpired();
        $this->assertFalse(
            $expiredSessionCookieIssued,
            'Middleware must not expire the session cookie on a request without an Auth session.',
        );
    }

    public function testLogoutUsersOnEditionChangeMiddleware_NoOp_WhenDisabledByConfig(): void
    {
        // Operator escape hatch: even with all logout conditions met
        // (timestamp set, session predates it), the bypass flag must
        // short-circuit the middleware completely.
        //
        // The flag is loaded by EditionPlugin's config/bootstrap.php on every
        // integration request, which re-evaluates env via filter_var — so
        // Configure::write would be overwritten. Set the env var instead.
        putenv('PASSBOLT_PLUGINS_EDITION_DISABLE_LOGOUT_USERS_ON_EDITION_CHANGE_MIDDLEWARE=true');
        Configure::write(
            EditionManager::CONFIGURE_KEY_LAST_CHANGE_DATETIME,
            new DateTime('2024-06-15 12:00:00'),
        );
        $user = UserFactory::make()->user()->active()
            ->setField('last_logged_in', new DateTime('2024-06-01 10:00:00'))
            ->persist();
        $this->logInAs($user);

        $this->getJson('/users/me.json');

        $this->assertResponseOk();
    }

    public function testLogoutUsersOnEditionChangeMiddleware_LogsOut_WhenDisableFlagIsExplicitlyFalse(): void
    {
        // Strict bypass: only literal `true` short-circuits. Explicit `false`
        // (or any other falsy) keeps the normal logout path active. Guards
        // against a typo in pro.php / passbolt.php that would otherwise
        // silently disable the protection.
        putenv('PASSBOLT_PLUGINS_EDITION_DISABLE_LOGOUT_USERS_ON_EDITION_CHANGE_MIDDLEWARE=false');
        Configure::write(
            EditionManager::CONFIGURE_KEY_LAST_CHANGE_DATETIME,
            new DateTime('2024-06-15 12:00:00'),
        );
        $user = UserFactory::make()->user()->active()
            ->setField('last_logged_in', new DateTime('2024-06-01 10:00:00'))
            ->persist();
        $this->logInAs($user);

        $this->getJson('/users/me.json');

        $this->assertResponseCode(401);
    }
}

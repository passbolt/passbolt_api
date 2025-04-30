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
 * @since         4.0.0
 */

namespace Passbolt\Sso\Test\TestCase\Controller\Google;

use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\Routing\Router;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Service\Sso\AbstractSsoService;
use Passbolt\Sso\Test\Factory\SsoSettingsFactory;
use Passbolt\Sso\Test\Lib\GoogleProviderTestTrait;
use Passbolt\Sso\Test\Lib\SsoIntegrationTestCase;
use Passbolt\Sso\Utility\Google\Provider\GoogleProvider;
use Passbolt\Sso\Utility\Provider\SsoProviderFactory;

/**
 * @see \Passbolt\Sso\Controller\Google\SsoGoogleStage1Controller
 * @property \Cake\Http\Response $_response
 */
class SsoGoogleStage1ControllerTest extends SsoIntegrationTestCase
{
    use GoogleProviderTestTrait;

    /**
     * 200 returns a URL
     */
    public function testSsoGoogleStage1Controller_Success_Admin(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        SsoSettingsFactory::make()->google()->active()->persist();
        // Mock provider
        $mockGoogleProvider = $this->getProviderMockForStage1(GoogleProvider::class);
        $clientId = UuidFactory::uuid();
        $state = SsoState::generate();
        $url = $this->getDummyGoogleAuthorizationUrl($admin, $state, ['client_id' => $clientId]);
        $mockGoogleProvider->method('getAuthorizationUrl')->willReturn($url);
        $mockGoogleProvider->method('getState')->willReturn($state);
        // Swap actual implementation
        SsoProviderFactory::set($mockGoogleProvider);

        $this->postJson('/sso/google/login.json', ['user_id' => $admin->id]);

        $this->assertSuccess();
        $url = $this->_responseJsonBody->url;
        $this->assertStringContainsString('https://', $url);
        $this->assertStringContainsString('google.com', $url);
        $this->assertStringContainsString('oauth2/v2/auth', $url);
        $this->assertStringContainsString('response_type=code', $url);
        $this->assertStringContainsString('state', $url);
        $this->assertStringContainsString('nonce', $url);
        $this->assertStringContainsString('login_hint=' . rawurlencode($admin->username), $url);
        $this->assertStringContainsString("client_id={$clientId}", $url);
        $this->assertStringContainsString(
            'scope=' . rawurlencode(implode(' ', ['openid', 'profile', 'email'])),
            $url
        );
        $this->assertStringContainsString(
            'redirect_uri=' . rawurlencode(Router::url('/sso/google/redirect', true)),
            $url
        );
        // assert sso state cookie
        $this->assertCookieSet(AbstractSsoService::SSO_STATE_COOKIE);
        $cookie = $this->_response->getCookie(AbstractSsoService::SSO_STATE_COOKIE);
        $this->assertEquals('/sso', $cookie['path']);
    }

    public function testSsoGoogleStage1Controller_Success_User(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        SsoSettingsFactory::make()->google()->active()->persist();
        // Mock provider
        $mockGoogleProvider = $this->getProviderMockForStage1(GoogleProvider::class);
        $clientId = UuidFactory::uuid();
        $state = SsoState::generate();
        $url = $this->getDummyGoogleAuthorizationUrl($user, $state, ['client_id' => $clientId]);
        $mockGoogleProvider->method('getAuthorizationUrl')->willReturn($url);
        $mockGoogleProvider->method('getState')->willReturn($state);
        // Swap actual implementation
        SsoProviderFactory::set($mockGoogleProvider);

        $this->postJson('/sso/google/login.json', ['user_id' => $user->id]);

        $this->assertSuccess();
        $url = $this->_responseJsonBody->url;
        $this->assertStringContainsString('https://', $url);
        $this->assertStringContainsString('google.com', $url);
        $this->assertStringContainsString('oauth2/v2/auth', $url);
        $this->assertStringContainsString('response_type=code', $url);
        $this->assertStringContainsString('state', $url);
        $this->assertStringContainsString('nonce', $url);
        $this->assertStringContainsString('login_hint=' . rawurlencode($user->username), $url);
        $this->assertStringContainsString("client_id={$clientId}", $url);
        $this->assertStringContainsString(
            'scope=' . rawurlencode(implode(' ', ['openid', 'profile', 'email'])),
            $url
        );
        $this->assertStringContainsString(
            'redirect_uri=' . rawurlencode(Router::url('/sso/google/redirect', true)),
            $url
        );
        // assert sso state cookie
        $this->assertCookieSet(AbstractSsoService::SSO_STATE_COOKIE);
        $cookie = $this->_response->getCookie(AbstractSsoService::SSO_STATE_COOKIE);
        $this->assertEquals('/sso', $cookie['path']);
    }

    public function testSsoGoogleStage1Controller_SuccessWithSubdir(): void
    {
        Configure::write('App.base', '/passbolt');
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        SsoSettingsFactory::make()->google()->active()->persist();
        // Mock provider
        $mockGoogleProvider = $this->getProviderMockForStage1(GoogleProvider::class);
        $clientId = UuidFactory::uuid();
        $state = SsoState::generate();
        $redirectUrl = Router::url('/sso/google/redirect', true);
        $url = $this->getDummyGoogleAuthorizationUrl($admin, $state, ['client_id' => $clientId, 'redirect_uri' => $redirectUrl]);
        $mockGoogleProvider->method('getAuthorizationUrl')->willReturn($url);
        $mockGoogleProvider->method('getState')->willReturn($state);
        // Swap actual implementation
        SsoProviderFactory::set($mockGoogleProvider);

        $this->postJson('/sso/google/login.json', ['user_id' => $admin->id]);

        $this->assertSuccess();
        $url = $this->_responseJsonBody->url;
        $this->assertStringContainsString('oauth2/v2/auth', $url);
        $this->assertStringContainsString('nonce', $url);
        $this->assertStringContainsString('login_hint=' . rawurlencode($admin->username), $url);
        $this->assertStringContainsString("client_id={$clientId}", $url);
        $this->assertStringContainsString(
            'scope=' . rawurlencode(implode(' ', ['openid', 'profile', 'email'])),
            $url
        );
        $this->assertStringContainsString('redirect_uri=' . rawurlencode($redirectUrl), $url);
        // assert sso state cookie
        $this->assertCookieSet(AbstractSsoService::SSO_STATE_COOKIE);
        $cookie = $this->_response->getCookie(AbstractSsoService::SSO_STATE_COOKIE);
        $this->assertEquals('/passbolt/sso', $cookie['path']);
    }

    /**
     * 400 user is logged in
     */
    public function testSsoGoogleStage1Controller_ErrorLoggedIn(): void
    {
        $user = UserFactory::make()->admin()->persist();
        SsoSettingsFactory::make()->google()->active()->persist();
        $this->logInAs($user);

        $this->postJson('/sso/google/login.json', ['user_id' => $user->get('id')]);
        $this->assertError(403);
    }

    /**
     * 400 user is deleted
     */
    public function testSsoGoogleStage1Controller_ErrorDeletedUser(): void
    {
        $user = UserFactory::make()->admin()->deleted()->persist();
        SsoSettingsFactory::make()->google()->active()->persist();

        $this->postJson('/sso/google/login.json', ['user_id' => $user->get('id')]);
        $this->assertError(400);
    }

    /**
     * 400 user is not active
     */
    public function testSsoGoogleStage1Controller_ErrorInactiveUser(): void
    {
        $user = UserFactory::make()->admin()->inactive()->persist();
        SsoSettingsFactory::make()->google()->active()->persist();

        $this->postJson('/sso/google/login.json', ['user_id' => $user->get('id')]);
        $this->assertError(400);
    }

    /**
     * 400 user id is missing
     */
    public function testSsoGoogleStage1Controller_ErrorUserIdMissing(): void
    {
        SsoSettingsFactory::make()->google()->active()->persist();

        $this->postJson('/sso/google/login.json', ['user_id' => null]);
        $this->assertError(400);
    }

    /**
     * 400 user id is missing too
     */
    public function testSsoGoogleStage1Controller_ErrorUserIdMissing2(): void
    {
        SsoSettingsFactory::make()->google()->active()->persist();
        $this->postJson('/sso/google/login.json', []);
        $this->assertError(400);
    }

    /**
     * 400 user id is invalid
     */
    public function testSsoGoogleStage1Controller_ErrorUserIdInvalid(): void
    {
        SsoSettingsFactory::make()->google()->active()->persist();
        $this->postJson('/sso/google/login.json', ['user_id' => 'nope']);
        $this->assertError(400);
    }

    /**
     * 400 no active users
     */
    public function testSsoGoogleStage1Controller_ErrorNoActiveSettings(): void
    {
        $user = UserFactory::make()->admin()->persist();
        SsoSettingsFactory::make()->google()->draft()->persist();

        $this->postJson('/sso/google/login.json', ['user_id' => $user->get('id')]);
        $this->assertError(400);
    }
}

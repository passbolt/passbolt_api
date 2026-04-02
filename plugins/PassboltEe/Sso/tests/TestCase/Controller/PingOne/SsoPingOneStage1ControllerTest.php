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
 * @since         5.11.0
 */

namespace Passbolt\Sso\Test\TestCase\Controller\PingOne;

use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\Routing\Router;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Service\Sso\AbstractSsoService;
use Passbolt\Sso\Test\Factory\SsoSettingsFactory;
use Passbolt\Sso\Test\Lib\PingOneProviderTestTrait;
use Passbolt\Sso\Test\Lib\SsoIntegrationTestCase;
use Passbolt\Sso\Utility\PingOne\Provider\PingOneProvider;
use Passbolt\Sso\Utility\Provider\SsoProviderFactory;

/**
 * @covers \Passbolt\Sso\Controller\PingOne\SsoPingOneStage1Controller
 * @property \Cake\Http\Response $_response
 */
class SsoPingOneStage1ControllerTest extends SsoIntegrationTestCase
{
    use PingOneProviderTestTrait;

    /**
     * 200 returns a URL (admin user)
     */
    public function testSsoPingOneStage1Controller_Success_Admin(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        SsoSettingsFactory::make()->pingone()->active()->persist();
        // Mock provider
        $mockPingOneProvider = $this->getProviderMockForStage1(PingOneProvider::class);
        $clientId = UuidFactory::uuid();
        $environmentId = UuidFactory::uuid();
        $state = SsoState::generate();
        $url = $this->getDummyPingOneAuthorizationUrl($admin, $state, [
            'client_id' => $clientId,
            'environment_id' => $environmentId,
        ]);
        $mockPingOneProvider->method('getAuthorizationUrl')->willReturn($url);
        $mockPingOneProvider->method('getState')->willReturn($state);
        // Swap actual implementation
        SsoProviderFactory::set($mockPingOneProvider);

        $this->postJson('/sso/pingone/login.json', ['user_id' => $admin->id]);

        $this->assertSuccess();
        $url = $this->_responseJsonBody->url;
        $this->assertStringContainsString('/' . $environmentId . '/as/authorize', $url);
        $this->assertStringContainsString('nonce', $url);
        $this->assertStringContainsString('login_hint=' . rawurlencode($admin->username), $url);
        $this->assertStringContainsString("client_id={$clientId}", $url);
        $this->assertStringContainsString(
            'scope=' . rawurlencode(implode(' ', ['openid', 'email', 'profile'])),
            $url
        );
        $this->assertStringContainsString(
            'redirect_uri=' . rawurlencode(Router::url('/sso/pingone/redirect', true)),
            $url
        );
        // assert sso state cookie
        $this->assertCookieSet(AbstractSsoService::SSO_STATE_COOKIE);
        $cookie = $this->_response->getCookie(AbstractSsoService::SSO_STATE_COOKIE);
        $this->assertEquals('/sso', $cookie['path']);
    }

    /**
     * 200 returns a URL (regular user)
     */
    public function testSsoPingOneStage1Controller_Success_User(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        SsoSettingsFactory::make()->pingone()->active()->persist();
        // Mock provider
        $mockPingOneProvider = $this->getProviderMockForStage1(PingOneProvider::class);
        $clientId = UuidFactory::uuid();
        $environmentId = UuidFactory::uuid();
        $state = SsoState::generate();
        $url = $this->getDummyPingOneAuthorizationUrl($user, $state, [
            'client_id' => $clientId,
            'environment_id' => $environmentId,
        ]);
        $mockPingOneProvider->method('getAuthorizationUrl')->willReturn($url);
        $mockPingOneProvider->method('getState')->willReturn($state);
        // Swap actual implementation
        SsoProviderFactory::set($mockPingOneProvider);

        $this->postJson('/sso/pingone/login.json', ['user_id' => $user->id]);

        $this->assertSuccess();
        $url = $this->_responseJsonBody->url;
        $this->assertStringContainsString('/' . $environmentId . '/as/authorize', $url);
        $this->assertStringContainsString('nonce', $url);
        $this->assertStringContainsString('login_hint=' . rawurlencode($user->username), $url);
        $this->assertStringContainsString("client_id={$clientId}", $url);
        $this->assertStringContainsString(
            'scope=' . rawurlencode(implode(' ', ['openid', 'email', 'profile'])),
            $url
        );
        $this->assertStringContainsString(
            'redirect_uri=' . rawurlencode(Router::url('/sso/pingone/redirect', true)),
            $url
        );
        // assert sso state cookie
        $this->assertCookieSet(AbstractSsoService::SSO_STATE_COOKIE);
        $cookie = $this->_response->getCookie(AbstractSsoService::SSO_STATE_COOKIE);
        $this->assertEquals('/sso', $cookie['path']);
    }

    /**
     * 200 with subdirectory configuration
     */
    public function testSsoPingOneStage1Controller_SuccessWithSubdir(): void
    {
        Configure::write('App.base', '/passbolt');
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        SsoSettingsFactory::make()->pingone()->active()->persist();
        // Mock provider
        $mockPingOneProvider = $this->getProviderMockForStage1(PingOneProvider::class);
        $clientId = UuidFactory::uuid();
        $environmentId = UuidFactory::uuid();
        $state = SsoState::generate();
        $redirectUrl = Router::url('/sso/pingone/redirect', true);
        $url = $this->getDummyPingOneAuthorizationUrl($admin, $state, [
            'client_id' => $clientId,
            'environment_id' => $environmentId,
            'redirect_uri' => $redirectUrl,
        ]);
        $mockPingOneProvider->method('getAuthorizationUrl')->willReturn($url);
        $mockPingOneProvider->method('getState')->willReturn($state);
        // Swap actual implementation
        SsoProviderFactory::set($mockPingOneProvider);

        $this->postJson('/sso/pingone/login.json', ['user_id' => $admin->id]);

        $this->assertSuccess();
        $url = $this->_responseJsonBody->url;
        $this->assertStringContainsString('/' . $environmentId . '/as/authorize', $url);
        $this->assertStringContainsString('nonce', $url);
        $this->assertStringContainsString('login_hint=' . rawurlencode($admin->username), $url);
        $this->assertStringContainsString("client_id={$clientId}", $url);
        $this->assertStringContainsString(
            'scope=' . rawurlencode(implode(' ', ['openid', 'email', 'profile'])),
            $url
        );
        $this->assertStringContainsString('redirect_uri=' . rawurlencode($redirectUrl), $url);
        // assert sso state cookie path includes subdirectory
        $this->assertCookieSet(AbstractSsoService::SSO_STATE_COOKIE);
        $cookie = $this->_response->getCookie(AbstractSsoService::SSO_STATE_COOKIE);
        $this->assertEquals('/passbolt/sso', $cookie['path']);
    }

    /**
     * 403 user is logged in
     */
    public function testSsoPingOneStage1Controller_ErrorLoggedIn(): void
    {
        $user = UserFactory::make()->admin()->persist();
        SsoSettingsFactory::make()->pingone()->active()->persist();
        $this->logInAs($user);

        $this->postJson('/sso/pingone/login.json', ['user_id' => $user->get('id')]);

        $this->assertError(403);
    }

    /**
     * 400 user is deleted
     */
    public function testSsoPingOneStage1Controller_ErrorDeletedUser(): void
    {
        $user = UserFactory::make()->admin()->deleted()->persist();
        SsoSettingsFactory::make()->pingone()->active()->persist();

        $this->postJson('/sso/pingone/login.json', ['user_id' => $user->get('id')]);

        $this->assertError(400);
    }

    /**
     * 400 user is not active
     */
    public function testSsoPingOneStage1Controller_ErrorInactiveUser(): void
    {
        $user = UserFactory::make()->admin()->inactive()->persist();
        SsoSettingsFactory::make()->pingone()->active()->persist();

        $this->postJson('/sso/pingone/login.json', ['user_id' => $user->get('id')]);

        $this->assertError(400);
    }

    /**
     * 400 user id is null
     */
    public function testSsoPingOneStage1Controller_ErrorUserIdMissing(): void
    {
        SsoSettingsFactory::make()->pingone()->active()->persist();

        $this->postJson('/sso/pingone/login.json', ['user_id' => null]);

        $this->assertError(400);
    }

    /**
     * 400 user id is not provided
     */
    public function testSsoPingOneStage1Controller_ErrorUserIdMissing2(): void
    {
        SsoSettingsFactory::make()->pingone()->active()->persist();

        $this->postJson('/sso/pingone/login.json', []);

        $this->assertError(400);
    }

    /**
     * 400 user id is invalid
     */
    public function testSsoPingOneStage1Controller_ErrorUserIdInvalid(): void
    {
        SsoSettingsFactory::make()->pingone()->active()->persist();

        $this->postJson('/sso/pingone/login.json', ['user_id' => 'nope']);

        $this->assertError(400);
    }

    /**
     * 400 no active settings (only draft exists)
     */
    public function testSsoPingOneStage1Controller_ErrorNoActiveSettings(): void
    {
        $user = UserFactory::make()->admin()->persist();
        SsoSettingsFactory::make()->pingone()->draft()->persist();

        $this->postJson('/sso/pingone/login.json', ['user_id' => $user->get('id')]);

        $this->assertError(400);
    }
}

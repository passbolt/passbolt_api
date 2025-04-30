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
 * @since         4.6.0
 */

namespace Passbolt\Sso\Test\TestCase\Controller\Adfs;

use App\Test\Factory\UserFactory;
use Cake\Core\Configure;
use Cake\Routing\Router;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Service\Sso\AbstractSsoService;
use Passbolt\Sso\Test\Factory\SsoSettingsFactory;
use Passbolt\Sso\Test\Lib\SsoIntegrationTestCase;
use Passbolt\Sso\Test\Lib\SsoProviderTestTrait;
use Passbolt\Sso\Utility\Adfs\Provider\AdfsProvider;
use Passbolt\Sso\Utility\Provider\SsoProviderFactory;

/**
 * @see \Passbolt\Sso\Controller\Adfs\SsoAdfsStage1Controller
 * @property \Cake\Http\Response $_response
 */
class SsoAdfsStage1ControllerTest extends SsoIntegrationTestCase
{
    use SsoProviderTestTrait;

    /**
     * 200 returns a URL
     */
    public function testSsoAdfsStage1Controller_Success_Admin(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        SsoSettingsFactory::make()->adfs()->active()->persist();
        // Mock provider
        $mockAdfsProvider = $this->getProviderMockForStage1(AdfsProvider::class);
        $state = SsoState::generate();
        $url = $this->getDummyAdfsAuthorizationUrl($admin, $state);
        $mockAdfsProvider->method('getAuthorizationUrl')->willReturn($url);
        $mockAdfsProvider->method('getState')->willReturn($state);
        // Swap actual implementation
        SsoProviderFactory::set($mockAdfsProvider);

        $this->postJson('/sso/adfs/login.json', ['user_id' => $admin->id]);

        $this->assertSuccess();
        $url = $this->_responseJsonBody->url;
        $this->assertStringContainsString('adfs.passbolt.test', $url);
        $this->assertStringContainsString('login_hint', $url);
        $this->assertStringContainsString('scope', $url);
        $this->assertStringContainsString('redirect_uri', $url);
        $this->assertStringContainsString('response_type', $url);
        $this->assertStringContainsString('client_id', $url);
        $this->assertStringContainsString('state', $url);
        $this->assertStringContainsString('nonce', $url);
        // assert sso state cookie
        $this->assertCookieSet(AbstractSsoService::SSO_STATE_COOKIE);
        $cookie = $this->_response->getCookie(AbstractSsoService::SSO_STATE_COOKIE);
        $this->assertEquals('/sso', $cookie['path']);
    }

    public function testSsoAdfsStage1Controller_SuccessWithSubdir(): void
    {
        Configure::write('App.base', '/passbolt');
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        SsoSettingsFactory::make()->adfs()->active()->persist();
        // Mock provider
        $mockAdfsProvider = $this->getProviderMockForStage1(AdfsProvider::class);
        $redirectUrl = Router::url('/sso/adfs/redirect', true);
        $state = SsoState::generate();
        $url = $this->getDummyAdfsAuthorizationUrl($admin, $state, ['redirect_uri' => $redirectUrl]);
        $mockAdfsProvider->method('getAuthorizationUrl')->willReturn($url);
        $mockAdfsProvider->method('getState')->willReturn($state);
        // Swap actual implementation
        SsoProviderFactory::set($mockAdfsProvider);

        $this->postJson('/sso/adfs/login.json', ['user_id' => $admin->id]);

        $this->assertSuccess();
        $url = $this->_responseJsonBody->url;
        $this->assertStringContainsString('adfs.passbolt.test', $url);
        $this->assertStringContainsString('login_hint', $url);
        $this->assertStringContainsString('scope', $url);
        $this->assertStringContainsString('redirect_uri=' . rawurlencode($redirectUrl), $url);
        $this->assertStringContainsString('response_type', $url);
        $this->assertStringContainsString('client_id', $url);
        $this->assertStringContainsString('state', $url);
        $this->assertStringContainsString('nonce', $url);
        // assert sso state cookie
        $this->assertCookieSet(AbstractSsoService::SSO_STATE_COOKIE);
        $cookie = $this->_response->getCookie(AbstractSsoService::SSO_STATE_COOKIE);
        $this->assertEquals('/passbolt/sso', $cookie['path']);
    }

    /**
     * 403 user is logged in
     */
    public function testSsoAdfsStage1Controller_ErrorLoggedIn(): void
    {
        $user = UserFactory::make()->admin()->persist();
        SsoSettingsFactory::make()->adfs()->active()->persist();
        $this->logInAs($user);

        $this->postJson('/sso/adfs/login.json', ['user_id' => $user->get('id')]);

        $this->assertError(403);
    }

    /**
     * 400 user is deleted
     */
    public function testSsoAdfsStage1Controller_ErrorDeletedUser(): void
    {
        $user = UserFactory::make()->admin()->deleted()->persist();
        SsoSettingsFactory::make()->adfs()->active()->persist();

        $this->postJson('/sso/adfs/login.json', ['user_id' => $user->get('id')]);

        $this->assertError(400);
    }

    /**
     * 400 user is not active
     */
    public function testSsoAdfsStage1Controller_ErrorInactiveUser(): void
    {
        $user = UserFactory::make()->admin()->inactive()->persist();
        SsoSettingsFactory::make()->adfs()->active()->persist();

        $this->postJson('/sso/adfs/login.json', ['user_id' => $user->get('id')]);

        $this->assertError(400);
    }

    /**
     * 400 user id is missing
     */
    public function testSsoAdfsStage1Controller_ErrorUserIdMissing(): void
    {
        SsoSettingsFactory::make()->adfs()->active()->persist();
        $this->postJson('/sso/adfs/login.json', ['user_id' => null]);
        $this->assertError(400);
    }

    /**
     * 400 user id is missing too
     */
    public function testSsoAdfsStage1Controller_ErrorUserIdMissing2(): void
    {
        SsoSettingsFactory::make()->adfs()->active()->persist();
        $this->postJson('/sso/adfs/login.json', []);
        $this->assertError(400);
    }

    /**
     * 400 user id is invalid
     */
    public function testSsoAdfsStage1Controller_ErrorUserIdInvalid(): void
    {
        SsoSettingsFactory::make()->adfs()->active()->persist();
        $this->postJson('/sso/adfs/login.json', ['user_id' => 'nope']);
        $this->assertError(400);
    }

    /**
     * 400 no active users
     */
    public function testSsoAdfsStage1Controller_ErrorNoActiveSettings(): void
    {
        $user = UserFactory::make()->admin()->persist();
        SsoSettingsFactory::make()->adfs()->draft()->persist();

        $this->postJson('/sso/adfs/login.json', ['user_id' => $user->get('id')]);

        $this->assertError(400);
    }
}

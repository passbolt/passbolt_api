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
use Passbolt\Sso\Middleware\SsoEndpointsSecurityMiddleware;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Test\Factory\SsoSettingsFactory;
use Passbolt\Sso\Test\Lib\PingOneProviderTestTrait;
use Passbolt\Sso\Test\Lib\SsoIntegrationTestCase;
use Passbolt\Sso\Utility\PingOne\Provider\PingOneProvider;
use Passbolt\Sso\Utility\Provider\SsoProviderFactory;

/**
 * @covers \Passbolt\Sso\Controller\PingOne\SsoPingOneStage1DryRunController
 */
class SsoPingOneStage1DryRunControllerTest extends SsoIntegrationTestCase
{
    use PingOneProviderTestTrait;

    /**
     * 200 returns a URL for PingOne provider
     */
    public function testSsoPingOneStage1DryRunController_Success(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        $settings = SsoSettingsFactory::make()->pingone()->draft()->persist();
        // Mock provider
        $mockPingOneProvider = $this->getProviderMockForStage1(PingOneProvider::class);
        $clientId = UuidFactory::uuid();
        $state = SsoState::generate();
        $url = $this->getDummyPingOneAuthorizationUrl($admin, $state, ['client_id' => $clientId]);
        $mockPingOneProvider->method('getAuthorizationUrl')->willReturn($url);
        $mockPingOneProvider->method('getState')->willReturn($state);
        // Swap actual implementation
        SsoProviderFactory::set($mockPingOneProvider);

        $this->logInAs($admin);
        $this->postJson('/sso/pingone/login/dry-run.json', ['sso_settings_id' => $settings->get('id')]);

        $this->assertSuccess();
        $url = $this->_responseJsonBody->url;
        $this->assertStringContainsString('/as/authorize', $url);
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
    }

    /**
     * 401 user is not logged in
     */
    public function testSsoPingOneStage1DryRunController_ErrorNotLoggedIn(): void
    {
        $ssoSetting = SsoSettingsFactory::make()->pingone()->draft()->persist();

        $this->postJson('/sso/pingone/login/dry-run.json', ['sso_settings_id' => $ssoSetting->get('id')]);

        $this->assertError(401);
    }

    /**
     * 403 user is not an admin
     */
    public function testSsoPingOneStage1DryRunController_ErrorNotAdmin(): void
    {
        $user = UserFactory::make()->user()->persist();
        $settings = SsoSettingsFactory::make()->pingone()->draft()->persist();
        $this->logInAs($user);

        $this->postJson('/sso/pingone/login/dry-run.json', ['sso_settings_id' => $settings->get('id')]);

        $this->assertError(403);
    }

    /**
     * 400 missing settings
     */
    public function testSsoPingOneStage1DryRunController_ErrorSettingsMissing(): void
    {
        $admin = UserFactory::make()->admin()->persist();
        $this->logInAs($admin);

        $this->postJson('/sso/pingone/login/dry-run.json', []);

        $this->assertError(400);
    }

    /**
     * 400 null settings
     */
    public function testSsoPingOneStage1DryRunController_ErrorNullSettings(): void
    {
        $admin = UserFactory::make()->admin()->persist();
        SsoSettingsFactory::make()->pingone()->draft()->persist();
        $this->logInAs($admin);

        $this->postJson('/sso/pingone/login/dry-run.json', ['sso_settings_id' => null]);

        $this->assertError(400);
    }

    /**
     * 400 Invalid settings id
     */
    public function testSsoPingOneStage1DryRunController_ErrorSettingsInvalid(): void
    {
        $admin = UserFactory::make()->admin()->persist();
        $this->logInAs($admin);

        $this->postJson('/sso/pingone/login/dry-run.json', ['sso_settings_id' => 'nope']);

        $this->assertError(400);
    }

    /**
     * 404 settings not found
     */
    public function testSsoPingOneStage1DryRunController_ErrorSettingsNotFound(): void
    {
        $admin = UserFactory::make()->admin()->persist();
        $this->logInAs($admin);

        $this->postJson('/sso/pingone/login/dry-run.json', ['sso_settings_id' => UuidFactory::uuid()]);

        $this->assertError(404);
    }

    /**
     * 404 settings not found - not in draft state
     */
    public function testSsoPingOneStage1DryRunController_ErrorSettingsNotDraft(): void
    {
        $admin = UserFactory::make()->admin()->persist();
        $settings = SsoSettingsFactory::make()->pingone()->active()->persist();
        $this->logInAs($admin);

        $this->postJson('/sso/pingone/login/dry-run.json', ['sso_settings_id' => $settings->get('id')]);

        $this->assertError(404);
    }

    /**
     * 403 If administrator has locked the setting endpoint.
     */
    public function testSsoPingOneStage1DryRunController_Error_EndpointsDisabled(): void
    {
        Configure::write(SsoEndpointsSecurityMiddleware::SECURITY_CONFIG_KEY, true);
        $ssoSetting = SsoSettingsFactory::make()->pingone()->draft()->persist();

        $this->postJson('/sso/pingone/login/dry-run.json', ['sso_settings_id' => $ssoSetting->get('id')]);

        $this->assertForbiddenError('SSO settings edit endpoints are disabled');
    }
}

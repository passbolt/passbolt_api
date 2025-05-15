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
use Cake\Routing\Router;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Test\Factory\SsoSettingsFactory;
use Passbolt\Sso\Test\Lib\GoogleProviderTestTrait;
use Passbolt\Sso\Test\Lib\SsoIntegrationTestCase;
use Passbolt\Sso\Utility\Google\Provider\GoogleProvider;
use Passbolt\Sso\Utility\Provider\SsoProviderFactory;

/**
 * @see \Passbolt\Sso\Controller\Google\SsoGoogleStage1DryRunController
 */
class SsoGoogleStage1DryRunControllerTest extends SsoIntegrationTestCase
{
    use GoogleProviderTestTrait;

    /**
     * 200 returns a URL for Google provider
     */
    public function testSsoGoogleStage1DryRunController_Success(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        $settings = SsoSettingsFactory::make()->google()->draft()->persist();
        // Mock provider
        $mockGoogleProvider = $this->getProviderMockForStage1(GoogleProvider::class);
        $clientId = UuidFactory::uuid();
        $state = SsoState::generate();
        $url = $this->getDummyGoogleAuthorizationUrl($admin, $state, ['client_id' => $clientId]);
        $mockGoogleProvider->method('getAuthorizationUrl')->willReturn($url);
        $mockGoogleProvider->method('getState')->willReturn($state);
        // Swap actual implementation
        SsoProviderFactory::set($mockGoogleProvider);

        $this->logInAs($admin);
        $this->postJson('/sso/google/login/dry-run.json', ['sso_settings_id' => $settings->get('id')]);

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
        $this->assertStringContainsString(
            'redirect_uri=' . rawurlencode(Router::url('/sso/google/redirect', true)),
            $url
        );
    }

    /**
     * 400 user is logged in
     */
    public function testSsoGoogleStage1DryRunController_ErrorNotLoggedIn(): void
    {
        $user = UserFactory::make()->admin()->persist();
        SsoSettingsFactory::make()->google()->draft()->persist();

        $this->postJson('/sso/google/login/dry-run.json', ['user_id' => $user->get('id')]);

        $this->assertError(401);
    }

    /**
     * 403 user is not an admin
     */
    public function testSsoGoogleStage1DryRunController_ErrorNotAdmin(): void
    {
        $user = UserFactory::make()->user()->persist();
        $settings = SsoSettingsFactory::make()->google()->draft()->persist();
        $this->logInAs($user);

        $this->postJson('/sso/google/login/dry-run.json', ['sso_settings_id' => $settings->get('id')]);

        $this->assertError(403);
    }

    /**
     * 403 settings missing too
     */
    public function testSsoGoogleStage1DryRunController_ErrorSettingsMissing(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $this->logInAs($user);

        $this->postJson('/sso/google/login/dry-run.json', []);

        $this->assertError(400);
    }

    /**
     * 400 settings missing too
     */
    public function testSsoGoogleStage1DryRunController_ErrorSettingsMissing2(): void
    {
        $user = UserFactory::make()->admin()->persist();
        SsoSettingsFactory::make()->google()->draft()->persist();
        $this->logInAs($user);

        $this->postJson('/sso/google/login/dry-run.json', ['sso_settings_id' => null]);

        $this->assertError(400);
    }

    /**
     * 400 settings id invalid
     */
    public function testSsoGoogleStage1DryRunController_ErrorSettingsInvalid(): void
    {
        $user = UserFactory::make()->admin()->persist();
        SsoSettingsFactory::make()->google()->draft()->persist();
        $this->logInAs($user);

        $this->postJson('/sso/google/login/dry-run.json', ['sso_settings_id' => 'nope']);

        $this->assertError(400);
    }

    /**
     * 404 settings not found
     */
    public function testSsoGoogleStage1DryRunController_ErrorSettingsNotFound(): void
    {
        $user = UserFactory::make()->admin()->persist();
        SsoSettingsFactory::make()->google()->draft()->persist();
        $this->logInAs($user);

        $this->postJson('/sso/google/login/dry-run.json', ['sso_settings_id' => UuidFactory::uuid()]);

        $this->assertError(404);
    }

    /**
     * 404 settings not found - not in draft state
     */
    public function testSsoGoogleStage1DryRunController_ErrorSettingsNotDraft(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $settings = SsoSettingsFactory::make()->google()->active()->persist();
        $this->logInAs($user);

        $this->postJson('/sso/google/login/dry-run.json', ['sso_settings_id' => $settings->get('id')]);

        $this->assertError(404);
    }
}

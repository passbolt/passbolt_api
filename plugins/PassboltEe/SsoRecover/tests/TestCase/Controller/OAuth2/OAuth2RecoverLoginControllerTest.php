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
 * @since         5.12.0
 */

namespace Passbolt\SsoRecover\Test\TestCase\Controller\OAuth2;

use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Cake\Routing\Exception\MissingRouteException;
use Cake\Routing\Router;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Service\Sso\AbstractSsoService;
use Passbolt\Sso\Test\Factory\SsoSettingsFactory;
use Passbolt\Sso\Test\Factory\SsoStateFactory;
use Passbolt\Sso\Test\Lib\SsoProviderTestTrait;
use Passbolt\Sso\Utility\OAuth2\Provider\OAuth2Provider;
use Passbolt\Sso\Utility\Provider\SsoProviderFactory;
use Passbolt\SsoRecover\Test\Lib\SsoRecoverIntegrationTestCase;

/**
 * @covers \Passbolt\SsoRecover\Controller\OAuth2\OAuth2RecoverLoginController
 */
class OAuth2RecoverLoginControllerTest extends SsoRecoverIntegrationTestCase
{
    use SsoProviderTestTrait;

    public function testOAuth2RecoverLoginController_ErrorFeatureDisabled(): void
    {
        $this->disableFeaturePlugin('SsoRecover');
        $this->disableErrorHandlerMiddleware();

        $this->expectException(MissingRouteException::class);

        $this->postJson('/sso/recover/oauth2.json');
    }

    public function testOAuth2RecoverLoginController_ErrorNoSsoSettings(): void
    {
        $this->postJson('/sso/recover/oauth2.json');

        $this->assertError(400, 'The SSO settings do not exist');
    }

    public function testOAuth2RecoverLoginController_ErrorUserLoggedIn(): void
    {
        SsoSettingsFactory::make()->oauth2()->active()->persist();
        $this->logInAsAdmin();

        $this->postJson('/sso/recover/oauth2.json');

        $this->assertError(403, 'The user should not be logged in');
    }

    public function testOAuth2RecoverLoginController_Success(): void
    {
        $admin = UserFactory::make()->admin()->persist();
        /** @var \Passbolt\Sso\Model\Entity\SsoSetting $settings */
        $settings = SsoSettingsFactory::make()->oauth2()->active()->persist();
        // Mock provider
        $mockOAuth2Provider = $this->getProviderMockForStage1(OAuth2Provider::class);
        $clientId = UuidFactory::uuid();
        $state = SsoState::generate();
        $url = $this->getDummyOAuth2AuthorizationUrl($admin, $state, [
            'client_id' => $clientId,
            'login_hint' => false,
        ]);
        $mockOAuth2Provider->method('getAuthorizationUrl')->willReturn($url);
        $mockOAuth2Provider->method('getState')->willReturn($state);
        // Swap actual implementation
        SsoProviderFactory::set($mockOAuth2Provider);

        $this->postJson('/sso/recover/oauth2.json');

        $this->assertSuccess();
        // Assert URL
        $url = $this->_responseJsonBody->url;
        $this->assertStringContainsString('oauth2.passbolt.test/authorize', $url);
        $this->assertStringContainsString('nonce', $url);
        $this->assertStringContainsString("client_id={$clientId}", $url);
        $this->assertStringContainsString(
            'redirect_uri=' . rawurlencode(Router::url('/sso/oauth2/redirect', true)),
            $url,
        );
        $this->assertStringNotContainsString('login_hint', $url);
        // Assert SSO state
        /** @var \Passbolt\Sso\Model\Entity\SsoState $ssoState */
        $ssoState = SsoStateFactory::find()->firstOrFail();
        $this->assertEquals(SsoState::TYPE_SSO_RECOVER, $ssoState->type);
        $this->assertEquals(null, $ssoState->user_id);
        $this->assertEquals($settings->get('id'), $ssoState->sso_settings_id);
        $this->assertNotEmpty($ssoState->nonce);
        $this->assertNotEmpty($ssoState->state);
        // Assert cookie is created
        $this->assertCookie($ssoState->state, AbstractSsoService::SSO_STATE_COOKIE);
    }
}

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

namespace Passbolt\SsoRecover\Test\TestCase\Controller\PingOne;

use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Cake\Routing\Exception\MissingRouteException;
use Cake\Routing\Router;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Service\Sso\AbstractSsoService;
use Passbolt\Sso\Test\Factory\SsoSettingsFactory;
use Passbolt\Sso\Test\Factory\SsoStateFactory;
use Passbolt\Sso\Test\Lib\PingOneProviderTestTrait;
use Passbolt\Sso\Utility\PingOne\Provider\PingOneProvider;
use Passbolt\Sso\Utility\Provider\SsoProviderFactory;
use Passbolt\SsoRecover\Test\Lib\SsoRecoverIntegrationTestCase;

/**
 * @covers \Passbolt\SsoRecover\Controller\PingOne\PingOneRecoverLoginController
 */
class PingOneRecoverLoginControllerTest extends SsoRecoverIntegrationTestCase
{
    use PingOneProviderTestTrait;

    public function testPingOneRecoverLoginController_ErrorFeatureDisabled(): void
    {
        $this->disableFeaturePlugin('SsoRecover');
        $this->disableErrorHandlerMiddleware();

        $this->expectException(MissingRouteException::class);

        $this->postJson('/sso/recover/pingone.json');
    }

    public function testPingOneRecoverLoginController_ErrorNoSsoSettings(): void
    {
        $this->postJson('/sso/recover/pingone.json');

        $this->assertError(400, 'The SSO settings do not exist');
    }

    public function testPingOneRecoverLoginController_ErrorUserLoggedIn(): void
    {
        SsoSettingsFactory::make()->pingone()->active()->persist();
        $this->logInAsAdmin();

        $this->postJson('/sso/recover/pingone.json');

        $this->assertError(403, 'The user should not be logged in');
    }

    public function testPingOneRecoverLoginController_Success(): void
    {
        $admin = UserFactory::make()->admin()->persist();
        /** @var \Passbolt\Sso\Model\Entity\SsoSetting $settings */
        $settings = SsoSettingsFactory::make()->pingone()->active()->persist();
        // Mock provider
        $mockPingOneProvider = $this->getProviderMockForStage1(PingOneProvider::class);
        $clientId = UuidFactory::uuid();
        $environmentId = UuidFactory::uuid();
        $state = SsoState::generate();
        $url = $this->getDummyPingOneAuthorizationUrl($admin, $state, [
            'client_id' => $clientId,
            'environment_id' => $environmentId,
            'login_hint' => false,
        ]);
        $mockPingOneProvider->method('getAuthorizationUrl')->willReturn($url);
        $mockPingOneProvider->method('getState')->willReturn($state);
        // Swap actual implementation
        SsoProviderFactory::set($mockPingOneProvider);

        $this->postJson('/sso/recover/pingone.json');

        $this->assertSuccess();
        // Assert URL
        $url = $this->_responseJsonBody->url;
        $this->assertStringContainsString('/' . $environmentId . '/as/authorize', $url);
        $this->assertStringContainsString('nonce', $url);
        $this->assertStringContainsString("client_id={$clientId}", $url);
        $this->assertStringContainsString(
            'scope=' . rawurlencode(implode(' ', ['openid', 'email', 'profile'])),
            $url
        );
        $this->assertStringContainsString(
            'redirect_uri=' . rawurlencode(Router::url('/sso/pingone/redirect', true)),
            $url
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

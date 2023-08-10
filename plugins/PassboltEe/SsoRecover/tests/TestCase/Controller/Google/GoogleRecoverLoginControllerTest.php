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

namespace Passbolt\SsoRecover\Test\TestCase\Controller\Google;

use App\Test\Factory\UserFactory;
use Cake\Routing\Exception\MissingRouteException;
use Cake\Routing\Router;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Service\Sso\AbstractSsoService;
use Passbolt\Sso\Test\Factory\SsoStateFactory;
use Passbolt\SsoRecover\Test\Lib\SsoRecoverIntegrationTestCase;

/**
 * @covers \Passbolt\SsoRecover\Controller\Google\GoogleRecoverLoginController
 */
class GoogleRecoverLoginControllerTest extends SsoRecoverIntegrationTestCase
{
    public function testGoogleRecoverLoginController_ErrorFeatureDisabled(): void
    {
        $this->disableFeaturePlugin('SsoRecover');
        $this->disableErrorHandlerMiddleware();

        $this->expectException(MissingRouteException::class);

        $this->postJson('/sso/recover/google.json');
    }

    public function testGoogleRecoverLoginController_ErrorNoSsoSettings(): void
    {
        $this->postJson('/sso/recover/google.json');

        $this->assertError(400, 'The SSO settings do not exist');
    }

    public function testGoogleRecoverLoginController_ErrorUserLoggedIn(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $this->createGoogleSettingsFromConfig($user);
        $this->logInAsAdmin();

        $this->postJson('/sso/recover/google.json');

        $this->assertError(403, 'The user should not be logged in');
    }

    public function testGoogleRecoverLoginController_Success(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $ssoSettingsDto = $this->createGoogleSettingsFromConfig($user);

        $this->postJson('/sso/recover/google.json');

        $this->assertSuccess();
        // Assert URL
        $url = $this->_responseJsonBody->url;
        $this->assertStringContainsString('oauth2/v2/auth', $url);
        $this->assertStringContainsString('nonce', $url);
        $this->assertStringContainsString("client_id={$ssoSettingsDto->data->toArray()['client_id']}", $url);
        $this->assertStringContainsString(
            'scope=' . rawurlencode(implode(' ', ['openid', 'profile', 'email'])),
            $url
        );
        $this->assertStringContainsString(
            'redirect_uri=' . rawurlencode(Router::url('/sso/google/redirect', true)),
            $url
        );
        $this->assertStringNotContainsString('login_hint', $url);
        /** @var \Passbolt\Sso\Model\Entity\SsoState $ssoState */
        $ssoState = SsoStateFactory::find()->firstOrFail();
        $this->assertEquals(SsoState::TYPE_SSO_RECOVER, $ssoState->type);
        $this->assertEquals(null, $ssoState->user_id);
        $this->assertEquals($ssoSettingsDto->id, $ssoState->sso_settings_id);
        $this->assertNotEmpty($ssoState->nonce);
        $this->assertNotEmpty($ssoState->state);
        // Assert cookie is created
        $this->assertCookie($ssoState->state, AbstractSsoService::SSO_STATE_COOKIE);
    }
}

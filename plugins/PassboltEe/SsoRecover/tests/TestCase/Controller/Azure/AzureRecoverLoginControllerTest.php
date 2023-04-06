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
 * @since         3.11.0
 */

namespace Passbolt\SsoRecover\Test\TestCase\Controller\Azure;

use App\Test\Factory\UserFactory;
use Cake\Routing\Exception\MissingRouteException;
use Cake\Routing\Router;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Service\Sso\AbstractSsoService;
use Passbolt\Sso\Test\Factory\SsoStateFactory;
use Passbolt\SsoRecover\Test\Lib\SsoRecoverIntegrationTestCase;

/**
 * @covers \Passbolt\SsoRecover\Controller\Azure\AzureRecoverLoginController
 */
class AzureRecoverLoginControllerTest extends SsoRecoverIntegrationTestCase
{
    public function testAzureRecoverLoginController_ErrorFeatureDisabled(): void
    {
        $this->disableFeaturePlugin('SsoRecover');
        $this->disableErrorHandlerMiddleware();

        $this->expectException(MissingRouteException::class);

        $this->postJson('/sso/recover/azure.json');
    }

    public function testAzureRecoverLoginController_ErrorNoSsoSettings(): void
    {
        $this->postJson('/sso/recover/azure.json');

        $this->assertError(400, 'The SSO settings do not exist');
    }

    public function testAzureRecoverLoginController_ErrorUserLoggedIn(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $this->createAzureSettingsFromConfig($user);
        $this->logInAsAdmin();

        $this->postJson('/sso/recover/azure.json');

        $this->assertError(403, 'The user should not be logged in');
    }

    public function testAzureRecoverLoginController_Success(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $ssoSettingsDto = $this->createAzureSettingsFromConfig($user);

        $this->postJson('/sso/recover/azure.json');

        $this->assertSuccess();
        $response = $this->_responseJsonBody;
        $this->assertStringContainsString('microsoft', $response->url);
        $this->assertObjectHasAttributes(
            ['response_type', 'nonce', 'state', 'scope', 'redirect_uri', 'client_id'],
            $response->data
        );
        $this->assertSame('GET', $response->method);
        $this->assertSame($ssoSettingsDto->data->toArray()['client_id'], $response->data->client_id);
        $this->assertSame(implode(' ', ['openid', 'profile', 'email']), $response->data->scope);
        $this->assertSame(Router::url('/sso/azure/redirect', true), $response->data->redirect_uri);
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

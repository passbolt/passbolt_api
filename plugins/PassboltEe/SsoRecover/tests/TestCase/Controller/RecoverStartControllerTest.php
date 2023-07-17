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

namespace Passbolt\SsoRecover\Test\TestCase\Controller;

use App\Model\Entity\AuthenticationToken;
use App\Service\Users\UserRecoverServiceInterface;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use Cake\Core\Configure;
use Cake\Routing\Exception\MissingRouteException;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Test\Factory\SsoAuthenticationTokenFactory;
use Passbolt\Sso\Test\Factory\SsoSettingsFactory;
use Passbolt\Sso\Test\Lib\SsoIntegrationTestCase;
use Passbolt\SsoRecover\Test\Lib\SsoRecoverIntegrationTestCase;

/**
 * @covers \Passbolt\SsoRecover\Controller\RecoverStartController
 */
class RecoverStartControllerTest extends SsoRecoverIntegrationTestCase
{
    public function testRecoverStartController_ErrorFeatureDisabled(): void
    {
        $this->disableFeaturePlugin('SsoRecover');
        $this->disableErrorHandlerMiddleware();

        $this->expectException(MissingRouteException::class);

        $this->postJson('/sso/recover/start.json');
    }

    public function testRecoverStartController_ErrorInvalidRequestType(): void
    {
        $this->post('/sso/recover/start');
        $this->assertResponseCode(400);
    }

    public function testRecoverStartController_ErrorUserLoggedIn(): void
    {
        $this->logInAsUser();
        $this->postJson('/sso/recover/start.json');
        $this->assertError(403, 'The user should not be logged in');
    }

    public function testRecoverStartController_ErrorValidateData(): void
    {
        SsoSettingsFactory::make()->azure()->active()->persist();

        $this->postJson('/sso/recover/start.json');

        $response = $this->_responseJsonBody;
        $this->assertError(400, 'Could not validate');
        $this->assertObjectHasAttribute('case', $response);
        $this->assertObjectHasAttribute('token', $response);
        $this->assertObjectHasAttribute('_required', $response->token);
        $this->assertObjectHasAttribute('_required', $response->case);
    }

    public function testRecoverStartController_ErrorCaseNotSupported(): void
    {
        $settings = SsoSettingsFactory::make()->azure()->active()->persist();
        $ssoAuthToken = SsoAuthenticationTokenFactory::make()
            ->type(SsoState::TYPE_SSO_RECOVER)
            ->active()
            ->data([
                'sso_setting_id' => $settings->id,
                'ip' => SsoIntegrationTestCase::IP_ADDRESS,
                'user_agent' => SsoIntegrationTestCase::USER_AGENT,
            ])
            ->persist();

        $this->postJson('/sso/recover/start.json', [
            'token' => $ssoAuthToken->token,
            'case' => UserRecoverServiceInterface::ACCOUNT_RECOVERY_CASE_LOST_PASSPHRASE,
        ]);

        $this->assertError(400, 'Could not validate');
        $response = $this->_responseJsonBody;
        $this->assertObjectHasAttribute('case', $response);
        $this->assertObjectHasAttribute('invalidCase', $response->case);
    }

    public function testRecoverStartController_ErrorInactiveToken(): void
    {
        $settings = SsoSettingsFactory::make()->azure()->active()->persist();
        $ssoAuthToken = SsoAuthenticationTokenFactory::make()->inactive()->persist();

        $this->postJson('/sso/recover/start.json', [
            'token' => $ssoAuthToken->token,
            'case' => UserRecoverServiceInterface::ACCOUNT_RECOVERY_CASE_DEFAULT,
        ]);

        $this->assertError(400, 'authentication token does not exist');
    }

    public function testRecoverStartController_ErrorNoSsoSettings(): void
    {
        $ssoAuthToken = SsoAuthenticationTokenFactory::make()
            ->type(SsoState::TYPE_SSO_RECOVER)
            ->active()
            ->persist();

        $this->postJson('/sso/recover/start.json', [
            'token' => $ssoAuthToken->token,
            'case' => UserRecoverServiceInterface::ACCOUNT_RECOVERY_CASE_DEFAULT,
        ]);

        $this->assertError(400, 'No valid SSO settings found');
    }

    public function testRecoverStartController_SuccessRecovery(): void
    {
        Configure::write('App.fullBaseUrl', 'https://passbolt.local');
        $settings = SsoSettingsFactory::make()->azure()->active()->persist();
        $user = UserFactory::make()->user()->active()->persist();
        $ssoAuthToken = SsoAuthenticationTokenFactory::make()
            ->type(SsoState::TYPE_SSO_RECOVER)
            ->userId($user->id)
            ->active()
            ->data([
                'sso_setting_id' => $settings->id,
                'ip' => SsoIntegrationTestCase::IP_ADDRESS,
                'user_agent' => SsoIntegrationTestCase::USER_AGENT,
            ])
            ->persist();

        $this->postJson('/sso/recover/start.json', [
            'token' => $ssoAuthToken->token,
            'case' => UserRecoverServiceInterface::ACCOUNT_RECOVERY_CASE_DEFAULT,
        ]);

        $response = $this->_responseJsonBody;
        $this->assertSuccess();
        /** @var \Passbolt\Sso\Model\Entity\SsoAuthenticationToken $updatedAuthToken */
        $updatedAuthToken = SsoAuthenticationTokenFactory::find()
            ->where(['token' => $ssoAuthToken->token])
            ->firstOrFail();
        $this->assertFalse($updatedAuthToken->active);
        // Assert auth token for recover created
        /** @var \App\Model\Entity\AuthenticationToken $recoverAuthToken */
        $recoverAuthToken = AuthenticationTokenFactory::find()
            ->where(['type' => AuthenticationToken::TYPE_RECOVER])
            ->firstOrFail();
        // Assert URL
        $this->assertObjectHasAttribute('url', $response);
        $this->assertStringContainsString(
            "https://passbolt.local/setup/recover/{$user->id}/{$recoverAuthToken->token}",
            $response->url
        );
    }

    public function testRecoverStartController_SuccessSetup(): void
    {
        Configure::write('App.fullBaseUrl', 'https://passbolt.local');
        $settings = SsoSettingsFactory::make()->azure()->active()->persist();
        $user = UserFactory::make()->user()->inactive()->persist();
        $ssoAuthToken = SsoAuthenticationTokenFactory::make()
            ->type(SsoState::TYPE_SSO_RECOVER)
            ->userId($user->id)
            ->active()
            ->data([
                'sso_setting_id' => $settings->id,
                'ip' => SsoIntegrationTestCase::IP_ADDRESS,
                'user_agent' => SsoIntegrationTestCase::USER_AGENT,
            ])
            ->persist();

        $this->postJson('/sso/recover/start.json', [
            'token' => $ssoAuthToken->token,
            'case' => UserRecoverServiceInterface::ACCOUNT_RECOVERY_CASE_DEFAULT,
        ]);

        $response = $this->_responseJsonBody;
        $this->assertSuccess();
        // Assert sso_recover token consumed
        /** @var \Passbolt\Sso\Model\Entity\SsoAuthenticationToken $updatedAuthToken */
        $updatedAuthToken = SsoAuthenticationTokenFactory::find()
            ->where(['token' => $ssoAuthToken->token])
            ->firstOrFail();
        $this->assertFalse($updatedAuthToken->active);
        // Assert auth token for register(aka setup) created
        /** @var \App\Model\Entity\AuthenticationToken $registerAuthToken */
        $registerAuthToken = AuthenticationTokenFactory::find()
            ->where(['type' => AuthenticationToken::TYPE_REGISTER])
            ->firstOrFail();
        // Assert URL
        $this->assertObjectHasAttribute('url', $response);
        $this->assertStringContainsString(
            "https://passbolt.local/setup/start/{$user->id}/{$registerAuthToken->token}",
            $response->url
        );
    }
}

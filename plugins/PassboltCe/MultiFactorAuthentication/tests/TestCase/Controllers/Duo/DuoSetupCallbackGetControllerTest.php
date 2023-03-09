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
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Controllers\Duo;

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\OrganizationSettingFactory;
use App\Utility\UuidFactory;
use Cake\Routing\Router;
use Duo\DuoUniversal\Client;
use Passbolt\MultiFactorAuthentication\Controller\Duo\DuoSetupGetController;
use Passbolt\MultiFactorAuthentication\Service\Duo\MfaDuoStateCookieService;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Mock\DuoSdkClientMock;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Duo\MfaDuoOrganizationOnlyScenario;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Duo\MfaDuoScenario;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Totp\MfaTotpScenario;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;

class DuoSetupCallbackGetControllerTest extends MfaIntegrationTestCase
{
    public function testDuoSetupCallbackGetController_Error_NotAuthenticated()
    {
        $this->get('/mfa/setup/duo/callback');
        $this->assertRedirect();
        $this->assertRedirectContains('/auth/login?redirect=%2Fmfa%2Fsetup%2Fduo%2Fcallback');
    }

    public function testDuoSetupCallbackGetController_Error_JsonNotAllowed()
    {
        $user = $this->logInAsUser();
        $this->mockMfaCookieValid($this->makeUac($user), MfaSettings::PROVIDER_DUO);
        $this->getJson('/mfa/setup/duo/callback.json');
        $errorMessageRegex = 'This functionality is not available using AJAX\/JSON.';
        $this->assertError(400, $errorMessageRegex);
    }

    public function testDuoSetupCallbackGetController_Error_AlreadyConfigured()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaDuoScenario::class, $user);
        $this->mockMfaCookieValid($this->makeUac($user), MfaSettings::PROVIDER_DUO);
        $this->get('/mfa/setup/duo/callback');
        $this->assertResponseError('This authentication provider is already setup. Disable it first');
        $this->assertSame(1, OrganizationSettingFactory::count());
    }

    public function testDuoSetupCallbackGetController_Error_OrgSettingsNotEnabled()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaTotpScenario::class, $user);
        $this->mockMfaCookieValid($this->makeUac($user), MfaSettings::PROVIDER_TOTP);
        $this->get('/mfa/setup/duo/callback');
        $this->assertResponseError();
        $this->assertResponseContains('This authentication provider is not enabled for your organization.');
    }

    public function testDuoSetupCallbackGetController_Error_DuoStateCookieMissing()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaDuoOrganizationOnlyScenario::class);
        $duoState = UuidFactory::uuid();
        $userId = $user->get('id');
        $this->mockService(Client::class, function () use ($user) {
            return DuoSdkClientMock::createDefault($this, $user)->getClient();
        });

        AuthenticationTokenFactory::make()->active()->data([
            'provider' => 'duo',
            'state' => $duoState,
            'redirect' => '',
            'user_agent' => 'PassboltUA',
        ])->userId($userId)->type(AuthenticationToken::TYPE_MFA_SETUP)->persist();

        $this->get('/mfa/setup/duo/callback?state=' . $duoState . '&duo_code=' . UuidFactory::uuid());
        $this->assertResponseCode(400);
        $this->assertResponseContains('A Duo state cookie is required.');

        $this->assertCookieNotSet(MfaVerifiedCookie::MFA_COOKIE_ALIAS);
        $this->assertCookieNotSet(MfaDuoStateCookieService::MFA_COOKIE_DUO_STATE);
    }

    public function testDuoSetupCallbackGetController_Error_UnableToAuthenticateToDuo()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaDuoOrganizationOnlyScenario::class);
        $duoState = UuidFactory::uuid();
        $userId = $user->get('id');
        $this->mockService(Client::class, function () use ($user) {
            return DuoSdkClientMock::createDefault($this, $user)->getClient();
        });

        $authToken = AuthenticationTokenFactory::make()->active()->data([
            'provider' => 'duo',
            'state' => $duoState,
            'redirect' => '',
            'user_agent' => 'PassboltUA',
        ])->userId($userId)->type(AuthenticationToken::TYPE_MFA_SETUP)->persist();
        $token = $authToken->token;

        $this->cookie(MfaDuoStateCookieService::MFA_COOKIE_DUO_STATE, $token);

        $this->get('/mfa/setup/duo/callback?error=DuoCallbackError&error_description=DuoCallbackErrorDescription');
        $this->assertResponseCode(400);
        $this->assertResponseContains('Unable to authenticate to Duo.');

        $this->assertCookieNotSet(MfaVerifiedCookie::MFA_COOKIE_ALIAS);
        $this->assertCookieNotSet(MfaDuoStateCookieService::MFA_COOKIE_DUO_STATE);
    }

    public function testDuoSetupCallbackGetController_Error_With_Redirect()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaDuoOrganizationOnlyScenario::class);
        $duoState = UuidFactory::uuid();
        $redirect = DuoSetupGetController::DUO_SETUP_REDIRECT_PATH;
        $userId = $user->get('id');
        $error = 'DuoCallbackError';
        $errorDesc = 'DuoCallbackErrorDescription';
        $this->mockService(Client::class, function () use ($user) {
            return DuoSdkClientMock::createDefault($this, $user)->getClient();
        });

        $authToken = AuthenticationTokenFactory::make()->active()->data([
            'provider' => 'duo',
            'state' => $duoState,
            'redirect' => $redirect,
            'user_agent' => 'PassboltUA',
        ])->userId($userId)->type(AuthenticationToken::TYPE_MFA_SETUP)->persist();
        $token = $authToken->token;

        $this->cookie(MfaDuoStateCookieService::MFA_COOKIE_DUO_STATE, $token);

        $this->get("/mfa/setup/duo/callback?error={$error}&error_description={$errorDesc}");
        $this->assertRedirect($redirect);
        $flashElement = $this->getSession()->read('Flash')['flash'][0];
        $this->assertEquals($flashElement['message'], "Unable to authenticate to Duo. {$error}: {$errorDesc}");

        $this->assertCookieNotSet(MfaVerifiedCookie::MFA_COOKIE_ALIAS);
        $this->assertCookieNotSet(MfaDuoStateCookieService::MFA_COOKIE_DUO_STATE);
    }

    public function testDuoSetupCallbackGetController_Error_With_Wrong_Redirect()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaDuoOrganizationOnlyScenario::class);
        $duoState = UuidFactory::uuid();
        $redirect = '/app';
        $userId = $user->get('id');
        $error = 'DuoCallbackError';
        $errorDesc = 'DuoCallbackErrorDescription';
        $this->mockService(Client::class, function () use ($user) {
            return DuoSdkClientMock::createDefault($this, $user)->getClient();
        });

        $authToken = AuthenticationTokenFactory::make()->active()->data([
            'provider' => 'duo',
            'state' => $duoState,
            'redirect' => $redirect,
            'user_agent' => 'PassboltUA',
        ])->userId($userId)->type(AuthenticationToken::TYPE_MFA_SETUP)->persist();
        $token = $authToken->token;

        $this->cookie(MfaDuoStateCookieService::MFA_COOKIE_DUO_STATE, $token);

        $this->get("/mfa/setup/duo/callback?error={$error}&error_description={$errorDesc}");
        $this->assertResponseCode(400);
        $this->assertResponseContains("Unable to authenticate to Duo. {$error}: {$errorDesc}");

        $this->assertCookieNotSet(MfaVerifiedCookie::MFA_COOKIE_ALIAS);
        $this->assertCookieNotSet(MfaDuoStateCookieService::MFA_COOKIE_DUO_STATE);
    }

    public function testDuoSetupCallbackGetController_Error_CouldNotValidateDuoCallbackData()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaDuoOrganizationOnlyScenario::class);
        $duoState = UuidFactory::uuid();
        $userId = $user->get('id');
        $this->mockService(Client::class, function () use ($user) {
            return DuoSdkClientMock::createDefault($this, $user)->getClient();
        });

        $authToken = AuthenticationTokenFactory::make()->active()->data([
            'provider' => 'duo',
            'state' => $duoState,
            'redirect' => '',
            'user_agent' => 'PassboltUA',
        ])->userId($userId)->type(AuthenticationToken::TYPE_MFA_SETUP)->persist();
        $token = $authToken->token;

        $this->cookie(MfaDuoStateCookieService::MFA_COOKIE_DUO_STATE, $token);

        $this->get('/mfa/setup/duo/callback');
        $this->assertResponseCode(400);
        $this->assertResponseContains('Unable to validate the Duo callback data.');

        $this->assertCookieNotSet(MfaVerifiedCookie::MFA_COOKIE_ALIAS);
        $this->assertCookieNotSet(MfaDuoStateCookieService::MFA_COOKIE_DUO_STATE);
    }

    public function testDuoSetupCallbackGetController_Success()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaDuoOrganizationOnlyScenario::class);
        $duoState = UuidFactory::uuid();
        $userId = $user->get('id');
        $this->mockService(Client::class, function () use ($user) {
            return DuoSdkClientMock::createDefault($this, $user)->getClient();
        });

        $authToken = AuthenticationTokenFactory::make()->active()->data([
            'provider' => 'duo',
            'state' => $duoState,
            'redirect' => '',
            'user_agent' => 'PassboltUA',
        ])->userId($userId)->type(AuthenticationToken::TYPE_MFA_SETUP)->persist();
        $token = $authToken->token;

        $this->cookie(MfaDuoStateCookieService::MFA_COOKIE_DUO_STATE, $token);

        $this->get('/mfa/setup/duo/callback?state=' . $duoState . '&duo_code=' . UuidFactory::uuid());
        $this->assertResponseOk();

        $this->assertCookieSet(MfaVerifiedCookie::MFA_COOKIE_ALIAS);
        $this->assertCookieNotSet(MfaDuoStateCookieService::MFA_COOKIE_DUO_STATE);
    }

    public function testDuoSetupCallbackGetController_Success_Redirect()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaDuoOrganizationOnlyScenario::class);
        $duoState = UuidFactory::uuid();
        $userId = $user->get('id');
        $this->mockService(Client::class, function () use ($user) {
            return DuoSdkClientMock::createDefault($this, $user)->getClient();
        });

        $authToken = AuthenticationTokenFactory::make()->active()->data([
            'provider' => 'duo',
            'state' => $duoState,
            'redirect' => DuoSetupGetController::DUO_SETUP_REDIRECT_PATH,
            'user_agent' => 'PassboltUA',
        ])->userId($userId)->type(AuthenticationToken::TYPE_MFA_SETUP)->persist();

        $this->cookie(MfaDuoStateCookieService::MFA_COOKIE_DUO_STATE, $authToken->token);

        $this->get('/mfa/setup/duo/callback?state=' . $duoState . '&duo_code=' . UuidFactory::uuid());
        $this->assertResponseCode(302);
        $this->assertRedirectEquals(Router::url('/app/settings/mfa/duo', true));

        $this->assertCookieSet(MfaVerifiedCookie::MFA_COOKIE_ALIAS);
        $this->assertCookieNotSet(MfaDuoStateCookieService::MFA_COOKIE_DUO_STATE);
    }

    public function testDuoSetupCallbackGetController_Error_Form_Contains_Logs()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaDuoOrganizationOnlyScenario::class);
        $duoState = UuidFactory::uuid();
        $userId = $user->get('id');
        $this->mockService(Client::class, function () use ($user) {
            return DuoSdkClientMock::createDefault($this, $user)->getClient();
        });

        $authToken = AuthenticationTokenFactory::make()->active()->data([
            'provider' => 'duo',
            'state' => $duoState,
            'redirect' => DuoSetupGetController::DUO_SETUP_REDIRECT_PATH,
            'user_agent' => 'PassboltUA',
        ])->userId($userId)->type(AuthenticationToken::TYPE_MFA_SETUP)->persist();

        $this->cookie(MfaDuoStateCookieService::MFA_COOKIE_DUO_STATE, $authToken->token);

        $this->get('/mfa/setup/duo/callback?state=' . $duoState . '&duo_code=' . UuidFactory::uuid());
        $this->assertResponseCode(302);
        $this->assertRedirectEquals(Router::url('/app/settings/mfa/duo', true));

        $this->assertCookieSet(MfaVerifiedCookie::MFA_COOKIE_ALIAS);
        $this->assertCookieNotSet(MfaDuoStateCookieService::MFA_COOKIE_DUO_STATE);
    }
}

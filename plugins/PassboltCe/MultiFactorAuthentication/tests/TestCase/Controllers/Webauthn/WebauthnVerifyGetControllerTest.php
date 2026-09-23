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
 * @since         5.17.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Controllers\Webauthn;

use App\Test\Factory\UserFactory;
use Passbolt\AccountSettings\Test\Factory\AccountSettingFactory;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Totp\MfaTotpScenario;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Webauthn\MfaWebauthnOrganizationOnlyScenario;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Webauthn\MfaWebauthnScenario;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class WebauthnVerifyGetControllerTest extends MfaIntegrationTestCase
{
    public function testWebauthnVerifyGetController_NotAuthenticated(): void
    {
        $this->getJson('/mfa/verify/webauthn.json?api-version=v2');
        $this->assertResponseError('You need to login to access this location.');
    }

    public function testWebauthnVerifyGetController_Success_ReturnsProviders(): void
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnScenario::class, $user);

        $this->getJson('/mfa/verify/webauthn.json?api-version=v2');
        $this->assertResponseOk();
        $this->assertContains(MfaSettings::PROVIDER_WEBAUTHN, $this->_responseJsonBody->providers);
        $this->assertObjectHasProperty('isRememberMeForAMonthEnabled', $this->_responseJsonBody);
    }

    public function testWebauthnVerifyGetController_ProviderNotConfiguredForUser(): void
    {
        $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnOrganizationOnlyScenario::class);

        $this->getJson('/mfa/verify/webauthn.json?api-version=v2');
        $this->assertResponseError('No valid multi-factor authentication settings found.');
    }

    public function testWebauthnVerifyGetController_AlreadyVerified_ReturnsBadRequest(): void
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnScenario::class, $user);
        $this->mockMfaCookieValid($this->makeUac($user), MfaSettings::PROVIDER_WEBAUTHN);

        $this->getJson('/mfa/verify/webauthn.json?api-version=v2');
        $this->assertBadRequestError('The multi-factor authentication is not required.');
    }

    public function testWebauthnVerifyGetController_Html_AlreadyVerified_ReturnsBadRequest(): void
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnScenario::class, $user);
        $this->mockMfaCookieValid($this->makeUac($user), MfaSettings::PROVIDER_WEBAUTHN);

        $this->get('/mfa/verify/webauthn');
        $this->assertResponseError();
        $this->assertResponseContains('The multi-factor authentication is not required');
    }

    public function testWebauthnVerifyGetController_Html_OrgSettingsNotEnabled_Redirects(): void
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaTotpScenario::class, $user);

        $this->get('/mfa/verify/webauthn');
        $this->assertRedirect('/');
    }

    public function testWebauthnVerifyGetController_Html_RendersVerifyLandingPage(): void
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnScenario::class, $user);

        $this->get('/mfa/verify/webauthn');
        $this->assertResponseOk();
        $this->assertResponseContains('js/app/api-vendors.js');
        $this->assertResponseContains('js/app/api-mfa-verify.js');
        $this->assertResponseContains('data-file="api_authentication.min.css"');
        $this->assertResponseContains('id="api-mfa-verify"');
        $this->assertResponseContains('data-redirect="/"');
    }

    public function testWebauthnVerifyGetController_Html_PassesSanitizedRedirect(): void
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnScenario::class, $user);

        $this->get('/mfa/verify/webauthn?redirect=/app/passwords');
        $this->assertResponseContains('data-redirect="/app/passwords"');

        $this->get('/mfa/verify/webauthn?redirect=https://evil.example/');
        $this->assertResponseContains('data-redirect="/"');
    }

    public function testWebauthnVerifyGetController_Html_PassesUserTheme(): void
    {
        $user = UserFactory::make()->user()
            ->with('AccountSettings', AccountSettingFactory::make()->theme('midgar'))
            ->persist();
        $this->logInAs($user);
        $this->loadFixtureScenario(MfaWebauthnScenario::class, $user);

        $this->get('/mfa/verify/webauthn');
        $this->assertResponseContains('data-theme="midgar"');
    }

    public function testWebauthnVerifyGetController_Html_WithoutUserTheme(): void
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnScenario::class, $user);

        $this->get('/mfa/verify/webauthn');
        $this->assertResponseNotContains('data-theme');
    }

    public function testWebauthnVerifyGetController_Html_PassesUserLocale(): void
    {
        $user = UserFactory::make()->user()->withLocale('fr-FR')->persist();
        // The locale middleware reads the username of the session, which logInAs() does not set.
        $this->session(['Auth' => ['user' => ['id' => $user->id, 'username' => $user->username]]]);
        $this->loadFixtureScenario(MfaWebauthnScenario::class, $user);

        $this->get('/mfa/verify/webauthn');
        $this->assertResponseContains('data-locale="fr-FR"');
    }

    public function testWebauthnVerifyGetController_Html_WithoutUserLocale(): void
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnScenario::class, $user);

        $this->get('/mfa/verify/webauthn');
        $this->assertResponseContains('data-locale="en-UK"');
    }
}

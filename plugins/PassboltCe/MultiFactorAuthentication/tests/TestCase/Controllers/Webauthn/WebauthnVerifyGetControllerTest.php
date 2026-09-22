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
}

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
 * @since         3.10.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Controllers\Duo;

use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\OrganizationSettingFactory;
use Duo\DuoUniversal\Client;
use Passbolt\MultiFactorAuthentication\Service\Duo\MfaDuoStateCookieService;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Mock\DuoSdkClientMock;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Duo\MfaDuoOrganizationOnlyScenario;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Duo\MfaDuoScenario;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Totp\MfaTotpScenario;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class DuoSetupPromptPostControllerTest extends MfaIntegrationTestCase
{
    public function testDuoSetupPromptPostController_Error_NotAuthenticated()
    {
        $this->post('/mfa/setup/duo/prompt');
        $this->assertRedirect();
        $this->assertRedirectContains('/auth/login?redirect=%2Fmfa%2Fsetup%2Fduo%2Fprompt');
    }

    public function testDuoSetupPromptPostController_Error_JsonNotAllowed()
    {
        $user = $this->logInAsUser();
        $this->mockMfaCookieValid($this->makeUac($user), MfaSettings::PROVIDER_DUO);
        $this->postJson('/mfa/setup/duo/prompt.json');
        $errorMessageRegex = 'This functionality is not available using AJAX\/JSON.';
        $this->assertError(400, $errorMessageRegex);
    }

    public function testDuoSetupPromptPostController_Error_AlreadyConfigured()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaDuoScenario::class, $user);
        $this->mockMfaCookieValid($this->makeUac($user), MfaSettings::PROVIDER_DUO);
        $this->post('/mfa/setup/duo/prompt');
        $this->assertResponseError('This authentication provider is already setup. Disable it first');
        $this->assertSame(1, OrganizationSettingFactory::count());
    }

    public function testDuoSetupPromptPostController_Error_OrgSettingsNotEnabled()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaTotpScenario::class, $user);
        $this->mockMfaCookieValid($this->makeUac($user), MfaSettings::PROVIDER_TOTP);
        $this->post('/mfa/setup/duo/prompt');
        $this->assertResponseError();
        $this->assertResponseContains('This authentication provider is not enabled for your organization.');
    }

    public function testDuoSetupPromptPostController_Success()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaDuoOrganizationOnlyScenario::class);
        $this->mockService(Client::class, function () use ($user) {
            return DuoSdkClientMock::createDefault($this, $user)->getClient();
        });
        $redirect = '/app/users';

        $this->post('/mfa/setup/duo/prompt?redirect=' . $redirect);

        $this->assertResponseCode(302);
        $this->assertRedirectContains('https://api-45e9f2ca.duosecurity.com/oauth/v1/authorize?sate=duo-not-so-random-state');

        $authToken = AuthenticationTokenFactory::find()->firstOrFail();
        $storedRedirect = json_decode($authToken->get('data'), true)['redirect'] ?? null;
        $this->assertEquals($redirect, $storedRedirect);
        $token = $authToken->get('token');
        $this->assertCookie($token, MfaDuoStateCookieService::MFA_COOKIE_DUO_STATE);
        $this->assertCookieNotExpired(MfaDuoStateCookieService::MFA_COOKIE_DUO_STATE);
    }

    public function testDuoSetupPromptPostController_Success_Without_Redirect()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaDuoOrganizationOnlyScenario::class);
        $this->mockService(Client::class, function () use ($user) {
            return DuoSdkClientMock::createDefault($this, $user)->getClient();
        });

        $this->post('/mfa/setup/duo/prompt');

        $this->assertResponseCode(302);
        $this->assertRedirectContains('https://api-45e9f2ca.duosecurity.com/oauth/v1/authorize?sate=duo-not-so-random-state');

        $authToken = AuthenticationTokenFactory::find()->firstOrFail();
        $storedRedirect = json_decode($authToken->get('data'), true)['redirect'] ?? null;
        $this->assertEquals('', $storedRedirect);
        $token = $authToken->get('token');
        $this->assertCookie($token, MfaDuoStateCookieService::MFA_COOKIE_DUO_STATE);
        $this->assertCookieNotExpired(MfaDuoStateCookieService::MFA_COOKIE_DUO_STATE);
    }
}

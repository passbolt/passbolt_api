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

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use Cake\Core\Configure;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Webauthn\MfaWebauthnOrganizationOnlyScenario;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Webauthn\MfaWebauthnScenario;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class WebauthnVerifyBeginPostControllerTest extends MfaIntegrationTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Configure::write('App.fullBaseUrl', 'https://passbolt.example.com');
    }

    public function testWebauthnVerifyBeginPostController_NotAuthenticated(): void
    {
        $this->postJson('/mfa/verify/webauthn/begin.json?api-version=v2', []);
        $this->assertResponseError('You need to login to access this location.');
    }

    public function testWebauthnVerifyBeginPostController_ProviderNotConfiguredForUser(): void
    {
        $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnOrganizationOnlyScenario::class);

        $this->postJson('/mfa/verify/webauthn/begin.json?api-version=v2', []);
        $this->assertResponseError();
    }

    public function testWebauthnVerifyBeginPostController_AlreadyVerified_ReturnsBadRequest(): void
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnScenario::class, $user);
        $this->mockMfaCookieValid($this->makeUac($user), MfaSettings::PROVIDER_WEBAUTHN);

        $this->postJson('/mfa/verify/webauthn/begin.json?api-version=v2', []);
        $this->assertBadRequestError('The multi-factor authentication is not required.');
    }

    public function testWebauthnVerifyBeginPostController_Error_FullBaseUrlIsIpAddress(): void
    {
        Configure::write('App.fullBaseUrl', 'https://[2001:db8::1]');
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnScenario::class, $user);

        $this->postJson('/mfa/verify/webauthn/begin.json?api-version=v2', []);
        $this->assertBadRequestError(
            'Passkeys require Passbolt to be reached through a domain name, not an IP address.',
        );
        $tokens = AuthenticationTokenFactory::find()->where(['type' => AuthenticationToken::TYPE_MFA_VERIFY]);
        $this->assertSame(0, $tokens->count());
    }

    public function testWebauthnVerifyBeginPostController_Success_PersistsHandleAndReturnsOptions(): void
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnScenario::class, $user);

        $this->postJson('/mfa/verify/webauthn/begin.json?api-version=v2', []);
        $this->assertResponseOk();
        $this->assertNotEmpty($this->_responseJsonBody->handle);
        $this->assertNotEmpty($this->_responseJsonBody->credentialRequestOptions);

        $handle = $this->_responseJsonBody->handle;
        /** @var \App\Model\Entity\AuthenticationToken $token */
        $token = AuthenticationTokenFactory::find()
            ->where(['token' => $handle, 'user_id' => $user->id])
            ->firstOrFail();
        $this->assertSame(AuthenticationToken::TYPE_MFA_VERIFY, $token->type);
        $this->assertTrue($token->active);
    }
}

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

class WebauthnSetupBeginPostControllerTest extends MfaIntegrationTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Configure::write('App.fullBaseUrl', 'https://passbolt.example.com');
    }

    public function testWebauthnSetupBeginPostController_NotAuthenticated(): void
    {
        $this->postJson('/mfa/setup/webauthn/begin.json?api-version=v2', []);
        $this->assertResponseError('You need to login to access this location.');
    }

    public function testWebauthnSetupBeginPostController_NotJson(): void
    {
        $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnOrganizationOnlyScenario::class);

        $this->post('/mfa/setup/webauthn/begin', []);
        $this->assertResponseError();
        $this->assertResponseContains('This functionality is only available using AJAX/JSON.');
    }

    public function testWebauthnSetupBeginPostController_OrgProviderDisabled(): void
    {
        $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnOrganizationOnlyScenario::class, false);

        $this->postJson('/mfa/setup/webauthn/begin.json?api-version=v2', []);
        $this->assertResponseError();
        $this->assertResponseContains('not enabled for your organization');
    }

    public function testWebauthnSetupBeginPostController_Error_FullBaseUrlIsIpAddress(): void
    {
        Configure::write('App.fullBaseUrl', 'https://192.168.1.10');
        $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnOrganizationOnlyScenario::class);

        $this->postJson('/mfa/setup/webauthn/begin.json?api-version=v2', []);
        $this->assertBadRequestError(
            'Passkeys require Passbolt to be reached through a domain name, not an IP address.',
        );
        $this->assertSame(
            'Passkeys require Passbolt to be reached through a domain name, not an IP address.',
            $this->getResponseBodyAsArray()['error']['invalidDomain'] ?? null,
        );
        $tokens = AuthenticationTokenFactory::find()->where(['type' => AuthenticationToken::TYPE_MFA_SETUP]);
        $this->assertSame(0, $tokens->count());
    }

    public function testWebauthnSetupBeginPostController_Success_PersistsHandleAndReturnsOptions(): void
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnOrganizationOnlyScenario::class);

        $this->postJson('/mfa/setup/webauthn/begin.json?api-version=v2', []);
        $this->assertResponseOk();
        $this->assertNotEmpty($this->_responseJsonBody->handle);
        $this->assertNotEmpty($this->_responseJsonBody->credentialCreationOptions);

        $handle = $this->_responseJsonBody->handle;
        /** @var \App\Model\Entity\AuthenticationToken $token */
        $token = AuthenticationTokenFactory::find()
            ->where(['token' => $handle, 'user_id' => $user->id])
            ->firstOrFail();
        $this->assertSame(AuthenticationToken::TYPE_MFA_SETUP, $token->type);
        $this->assertTrue($token->active);
    }
}

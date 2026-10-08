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
use Cake\Validation\Validation;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\Delegated\MfaWebauthnDelegatedStateService;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Webauthn\MfaWebauthnOrganizationOnlyScenario;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Webauthn\MfaWebauthnScenario;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class WebauthnDelegatedPromptPostControllerTest extends MfaIntegrationTestCase
{
    public function testWebauthnDelegatedPromptPostController_Error_NotAuthenticated(): void
    {
        $this->postJson('/mfa/verify/webauthn/delegated/prompt.json');
        $this->assertResponseError('You need to login to access this location.');
    }

    public function testWebauthnDelegatedPromptPostController_Error_NotJson(): void
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnScenario::class, $user);

        $this->post('/mfa/verify/webauthn/delegated/prompt');

        $this->assertResponseError('This functionality is only available using AJAX/JSON.');
    }

    public function testWebauthnDelegatedPromptPostController_Error_ProviderNotEnabledForUser(): void
    {
        $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnOrganizationOnlyScenario::class);

        $this->postJson('/mfa/verify/webauthn/delegated/prompt.json');

        $this->assertResponseError();
    }

    public function testWebauthnDelegatedPromptPostController_Error_AlreadyVerified(): void
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnScenario::class, $user);
        $this->mockMfaCookieValid($this->makeUac($user), MfaSettings::PROVIDER_WEBAUTHN);

        $this->postJson('/mfa/verify/webauthn/delegated/prompt.json');

        $this->assertBadRequestError('The multi-factor authentication is not required.');
    }

    public function testWebauthnDelegatedPromptPostController_Success(): void
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnScenario::class, $user);

        $this->postJson('/mfa/verify/webauthn/delegated/prompt.json', []);

        $this->assertResponseOk();
        $body = $this->getResponseBodyAsArray();
        $this->assertTrue(Validation::uuid($body['mfa_delegated_state']));
        $this->assertTrue(Validation::uuid($body['mfa_delegated_secret']));
        $this->assertNotSame($body['mfa_delegated_state'], $body['mfa_delegated_secret']);
        /** @var \App\Model\Entity\AuthenticationToken $row */
        $row = AuthenticationTokenFactory::find()
            ->where(['token' => $body['mfa_delegated_state'], 'user_id' => $user->id])
            ->firstOrFail();
        $this->assertSame(AuthenticationToken::TYPE_MFA_DELEGATED_STATE, $row->type);
        $this->assertTrue($row->active);
        $this->assertSame(
            $body['mfa_delegated_secret'],
            $row->getDataValue(MfaWebauthnDelegatedStateService::SECRET_KEY),
        );
    }
}

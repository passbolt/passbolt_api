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
use Passbolt\MultiFactorAuthentication\Test\Factory\MfaWebauthnCredentialFactory;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Webauthn\MfaWebauthnOrganizationOnlyScenario;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Webauthn\MfaWebauthnScenario;

class WebauthnSetupGetControllerTest extends MfaIntegrationTestCase
{
    public function testWebauthnSetupGetController_NotAuthenticated(): void
    {
        $this->getJson('/mfa/setup/webauthn.json?api-version=v2');
        $this->assertResponseError('You need to login to access this location.');
    }

    public function testWebauthnSetupGetController_MfaRequired_WhenNoValidCookie(): void
    {
        // MfaRequiredCheckMiddleware short-circuits the request with a 302 to /mfa/verify/error.json
        // before it reaches the controller.
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnScenario::class, $user);

        $this->get('/mfa/setup/webauthn.json?api-version=v2');
        $this->assertRedirect('/mfa/verify/error.json');
    }

    public function testWebauthnSetupGetController_ProviderNotSupportedByOrg(): void
    {
        $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnOrganizationOnlyScenario::class, false);
        $this->getJson('/mfa/setup/webauthn.json?api-version=v2');
        $this->assertResponseError();
    }

    public function testWebauthnSetupGetController_Success_ReturnsCredentialList_WithoutData(): void
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnOrganizationOnlyScenario::class);

        MfaWebauthnCredentialFactory::make()->withUser($user)->name('Yubikey Nano')->credentialId('cred-a')->persist();
        MfaWebauthnCredentialFactory::make()->withUser($user)->name('Passkey Phone')->credentialId('cred-b')->persist();

        $this->getJson('/mfa/setup/webauthn.json?api-version=v2');
        $this->assertResponseOk();
        $credentials = $this->getResponseBodyAsArray();
        $this->assertCount(2, $credentials);

        foreach ($credentials as $credential) {
            $this->assertArrayNotHasKey('data', $credential);
            $this->assertArrayHasKey('credential_id', $credential);
            $this->assertArrayHasKey('name', $credential);
        }
    }

    public function testWebauthnSetupGetController_DoesNotLeakOtherUserCredentials(): void
    {
        $me = $this->logInAsUser();
        /** @var \App\Model\Entity\User $other */
        $other = UserFactory::make()->user()->persist();
        $this->loadFixtureScenario(MfaWebauthnOrganizationOnlyScenario::class);

        MfaWebauthnCredentialFactory::make()->withUser($me)->credentialId('mine')->persist();
        MfaWebauthnCredentialFactory::make()->withUser($other)->credentialId('theirs')->persist();

        $this->getJson('/mfa/setup/webauthn.json?api-version=v2');
        $this->assertResponseOk();
        $credentials = $this->getResponseBodyAsArray();
        $this->assertCount(1, $credentials);
        $this->assertSame('mine', $credentials[0]['credential_id']);
    }
}

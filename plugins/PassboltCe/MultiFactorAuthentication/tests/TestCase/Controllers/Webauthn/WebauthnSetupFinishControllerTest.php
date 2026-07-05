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
 * @since         5.14.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Controllers\Webauthn;

use App\Utility\UuidFactory;
use Passbolt\MultiFactorAuthentication\Model\Entity\MfaWebauthnCredential;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnVerifyAttestationService;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Webauthn\MfaWebauthnOrganizationOnlyScenario;

/**
 * @group mfa
 * @group mfaSetup
 * @group webauthn
 */
class WebauthnSetupFinishControllerTest extends MfaIntegrationTestCase
{
    public function testWebauthnSetupFinish_MissingToken(): void
    {
        $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnOrganizationOnlyScenario::class);

        $this->postJson('/mfa/setup/webauthn/finish.json?api-version=v2', [
            'credential' => ['id' => 'x', 'type' => 'public-key', 'response' => []],
        ]);

        $this->assertResponseError('A challenge token is required.');
    }

    public function testWebauthnSetupFinish_MissingCredential(): void
    {
        $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnOrganizationOnlyScenario::class);

        $this->postJson('/mfa/setup/webauthn/finish.json?api-version=v2', [
            'token' => UuidFactory::uuid(),
        ]);

        $this->assertResponseError('A registration response is required.');
    }

    public function testWebauthnSetupFinish_Success(): void
    {
        $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnOrganizationOnlyScenario::class);
        $this->mockSessionId('webauthn-session');

        // Stub the attestation verification so the test does not require a real authenticator.
        $stub = $this->createMock(MfaWebauthnVerifyAttestationService::class);
        $stub->method('verify')->willReturn(new MfaWebauthnCredential());
        $this->mockService(MfaWebauthnVerifyAttestationService::class, function () use ($stub) {
            return $stub;
        });

        $this->postJson('/mfa/setup/webauthn/finish.json?api-version=v2', [
            'token' => UuidFactory::uuid(),
            'credential' => ['id' => 'abc', 'type' => 'public-key', 'response' => []],
            'name' => 'My security key',
        ]);

        $this->assertResponseSuccess();
    }
}

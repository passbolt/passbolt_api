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
namespace Passbolt\PasskeyAuthentication\Test\TestCase\Controller;

use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Passbolt\PasskeyAuthentication\Model\Entity\PasskeyCredential;
use Passbolt\PasskeyAuthentication\Service\PasskeyVerifyAttestationService;

/**
 * @group passkey
 */
class PasskeySetupFinishControllerTest extends AppIntegrationTestCase
{
    public function testPasskeySetupFinish_MissingToken(): void
    {
        $this->logInAsUser();

        $this->postJson('/passkey/setup/finish.json?api-version=v2', [
            'credential' => ['id' => 'x', 'type' => 'public-key', 'response' => []],
            'server_kit_key' => 'kit',
        ]);

        $this->assertResponseError('A challenge token is required.');
    }

    public function testPasskeySetupFinish_MissingKit(): void
    {
        $this->logInAsUser();

        $this->postJson('/passkey/setup/finish.json?api-version=v2', [
            'token' => UuidFactory::uuid(),
            'credential' => ['id' => 'x', 'type' => 'public-key', 'response' => []],
        ]);

        $this->assertResponseError('The passphrase kit is required.');
    }

    public function testPasskeySetupFinish_MissingCredential(): void
    {
        $this->logInAsUser();

        $this->postJson('/passkey/setup/finish.json?api-version=v2', [
            'token' => UuidFactory::uuid(),
            'server_kit_key' => 'kit',
        ]);

        $this->assertResponseError('An authentication response is required.');
    }

    public function testPasskeySetupFinish_Success(): void
    {
        $this->logInAsUser();

        // Stub the attestation verification so the test does not require a real authenticator.
        $entity = new PasskeyCredential([
            'id' => UuidFactory::uuid(),
            'credential_id' => 'abc',
            'name' => 'My security key',
        ]);
        $stub = $this->createMock(PasskeyVerifyAttestationService::class);
        $stub->method('verify')->willReturn($entity);
        $this->mockService(PasskeyVerifyAttestationService::class, function () use ($stub) {
            return $stub;
        });

        $this->postJson('/passkey/setup/finish.json?api-version=v2', [
            'token' => UuidFactory::uuid(),
            'credential' => ['id' => 'abc', 'type' => 'public-key', 'response' => []],
            'server_kit_key' => 'server-half-jwk',
            'name' => 'My security key',
        ]);

        $this->assertResponseSuccess();
        $this->assertSame('abc', $this->_responseJsonBody->credential_id);
    }
}

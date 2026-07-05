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
use Passbolt\PasskeyAuthentication\Service\PasskeyVerifyAssertionService;

/**
 * @group passkey
 */
class PasskeyLoginFinishControllerTest extends AppIntegrationTestCase
{
    public function testPasskeyLoginFinish_InvalidUserId(): void
    {
        $this->postJson('/passkey/login/finish.json?api-version=v2', [
            'user_id' => 'not-a-uuid',
            'token' => UuidFactory::uuid(),
            'credential' => ['id' => 'x', 'type' => 'public-key', 'response' => []],
        ]);
        $this->assertResponseError('A valid user id is required.');
    }

    public function testPasskeyLoginFinish_MissingToken(): void
    {
        $this->postJson('/passkey/login/finish.json?api-version=v2', [
            'user_id' => UuidFactory::uuid(),
            'credential' => ['id' => 'x', 'type' => 'public-key', 'response' => []],
        ]);
        $this->assertResponseError('A challenge token is required.');
    }

    public function testPasskeyLoginFinish_ReleasesServerKitHalfOnSuccess(): void
    {
        // Stub the assertion verification so the test does not require a real authenticator: on a
        // valid assertion the service returns the server half of the kit, which the endpoint must
        // relay back to the (still unauthenticated) client.
        $stub = $this->createMock(PasskeyVerifyAssertionService::class);
        $stub->method('verify')->willReturn('server-half-jwk-base64');
        $this->mockService(PasskeyVerifyAssertionService::class, function () use ($stub) {
            return $stub;
        });

        $this->postJson('/passkey/login/finish.json?api-version=v2', [
            'user_id' => UuidFactory::uuid(),
            'token' => UuidFactory::uuid(),
            'credential' => ['id' => 'abc', 'type' => 'public-key', 'response' => []],
        ]);

        $this->assertResponseSuccess();
        $this->assertSame('server-half-jwk-base64', $this->_responseJsonBody->server_kit_key);
    }
}

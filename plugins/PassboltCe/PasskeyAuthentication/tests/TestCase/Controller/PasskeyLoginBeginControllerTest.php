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

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Passbolt\PasskeyAuthentication\Service\PasskeyStartAssertionService;
use Passbolt\PasskeyAuthentication\Test\Factory\PasskeyCredentialFactory;

/**
 * @group passkey
 */
class PasskeyLoginBeginControllerTest extends AppIntegrationTestCase
{
    public function testPasskeyLoginBegin_InvalidUserId(): void
    {
        // No session: this endpoint is reachable unauthenticated.
        $this->postJson('/passkey/login/begin.json?api-version=v2', ['user_id' => 'not-a-uuid']);
        $this->assertResponseError('A valid user id is required.');
    }

    public function testPasskeyLoginBegin_NoCredentialStillSucceeds(): void
    {
        // Anti-enumeration: a user with no passkey must get the same success response as a user with
        // one, so this unauthenticated endpoint cannot reveal which accounts have a passkey key.
        $user = UserFactory::make()->user()->persist();

        $this->postJson('/passkey/login/begin.json?api-version=v2', ['user_id' => $user->id]);

        $this->assertResponseSuccess();
        $this->assertNotEmpty($this->_responseJsonBody->token);
        $this->assertNotEmpty($this->_responseJsonBody->credentialRequestOptions);
        // A user with no credential yields an empty allowCredentials list (discoverable request).
        $this->assertEmpty($this->_responseJsonBody->credentialRequestOptions->allowCredentials ?? []);
    }

    public function testPasskeyLoginBegin_Success(): void
    {
        $user = UserFactory::make()->user()->persist();
        PasskeyCredentialFactory::make()->withUser($user->id)->persist();

        // Stub the assertion options builder so the test does not require a real credential record.
        $token = AuthenticationTokenFactory::make()
            ->patchData(['token' => UuidFactory::uuid(), 'type' => AuthenticationToken::TYPE_PASSKEY_LOGIN])
            ->getEntity();
        $stub = $this->createMock(PasskeyStartAssertionService::class);
        $stub->method('start')->willReturn(['options' => '{"challenge":"abc"}', 'token' => $token]);
        $this->mockService(PasskeyStartAssertionService::class, function () use ($stub) {
            return $stub;
        });

        $this->postJson('/passkey/login/begin.json?api-version=v2', ['user_id' => $user->id]);

        $this->assertResponseSuccess();
        $this->assertSame($token->token, $this->_responseJsonBody->token);
        $this->assertNotEmpty($this->_responseJsonBody->credentialRequestOptions);
    }
}

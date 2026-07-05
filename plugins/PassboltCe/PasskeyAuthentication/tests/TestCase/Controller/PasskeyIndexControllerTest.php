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

use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use Passbolt\PasskeyAuthentication\Test\Factory\PasskeyCredentialFactory;

/**
 * @group passkey
 */
class PasskeyIndexControllerTest extends AppIntegrationTestCase
{
    public function testPasskeyIndex_NotAuthenticated(): void
    {
        $this->getJson('/passkey/settings.json?api-version=v2');
        $this->assertResponseError('You need to login to access this location.');
    }

    public function testPasskeyIndex_Success(): void
    {
        $user = $this->logInAsUser();
        // Two credentials for the current user and one for another user: the list is user-scoped.
        PasskeyCredentialFactory::make(2)->withUser($user->id)->persist();
        $other = UserFactory::make()->user()->persist();
        PasskeyCredentialFactory::make()->withUser($other->id)->persist();

        $this->getJson('/passkey/settings.json?api-version=v2');

        $this->assertResponseSuccess();
        $this->assertCount(2, $this->_responseJsonBody->credentials);
    }

    public function testPasskeyIndex_EmptyWhenNoCredential(): void
    {
        $this->logInAsUser();

        $this->getJson('/passkey/settings.json?api-version=v2');

        $this->assertResponseSuccess();
        $this->assertEmpty($this->_responseJsonBody->credentials);
    }

    public function testPasskeyIndex_DoesNotLeakSensitiveFields(): void
    {
        $user = $this->logInAsUser();
        PasskeyCredentialFactory::make()
            ->withUser($user->id)
            ->patchData([
                'data' => '{"secret":"credential-record"}',
                'server_kit_key' => 'server-half-jwk',
                'name' => 'My key',
            ])
            ->persist();

        $this->getJson('/passkey/settings.json?api-version=v2');

        $this->assertResponseSuccess();
        $credential = $this->_responseJsonBody->credentials[0];
        $this->assertObjectHasProperty('credential_id', $credential);
        $this->assertSame('My key', $credential->name);
        // The authoritative material and the server half of the kit must never be serialized.
        $this->assertObjectNotHasProperty('data', $credential);
        $this->assertObjectNotHasProperty('server_kit_key', $credential);
        $this->assertResponseNotContains('server-half-jwk');
        $this->assertResponseNotContains('credential-record');
    }
}

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
namespace Passbolt\PasskeyAuthentication\Test\TestCase\Model\Entity;

use Cake\TestSuite\TestCase;
use Passbolt\PasskeyAuthentication\Model\Entity\PasskeyCredential;

/**
 * @group passkey
 */
class PasskeyCredentialTest extends TestCase
{
    /**
     * The authoritative credential material and the server half of the passphrase kit must never
     * leak through entity serialization (zero-knowledge): they are exposed only, explicitly, by the
     * login finish endpoint after a valid assertion.
     *
     * @return void
     */
    public function testPasskeyCredential_HidesSensitiveFieldsFromJson(): void
    {
        $entity = new PasskeyCredential([
            'id' => 'a1b2',
            'user_id' => 'u1',
            'credential_id' => 'cred-id',
            'data' => '{"secret":"credential-record"}',
            'server_kit_key' => 'server-half-jwk',
            'name' => 'My key',
        ]);

        $array = $entity->toArray();
        $this->assertArrayNotHasKey('data', $array);
        $this->assertArrayNotHasKey('server_kit_key', $array);
        $this->assertArrayHasKey('credential_id', $array);

        $json = json_encode($entity);
        $this->assertStringNotContainsString('server-half-jwk', (string)$json);
        $this->assertStringNotContainsString('credential-record', (string)$json);
    }

    /**
     * Only the user provided name is mass-assignable; credential material must be set explicitly.
     *
     * @return void
     */
    public function testPasskeyCredential_OnlyNameIsAccessible(): void
    {
        $entity = new PasskeyCredential();
        $entity->patch([
            'name' => 'Patched name',
            'user_id' => 'attacker',
            'server_kit_key' => 'attacker-key',
        ]);

        $this->assertSame('Patched name', $entity->get('name'));
        $this->assertNull($entity->get('user_id'));
        $this->assertNull($entity->get('server_kit_key'));
    }
}

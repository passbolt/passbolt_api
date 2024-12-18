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
 * @since         4.11.0
 */

namespace Passbolt\Metadata\Test\TestCase\Controller;

use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataPrivateKeyFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;

/**
 * @uses \Passbolt\Metadata\Controller\MetadataMissingPrivateKeysShareController
 */
class MetadataKeyShareMissingKeysControllerTest extends AppIntegrationTestCaseV5
{
    use GpgMetadataKeysTestTrait;

    public function testMetadataMissingPrivateKeysShareController_Success(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->persist();
        /** @var \App\Model\Entity\User $user2 */
        $user2 = UserFactory::make()->user()->withValidGpgKey()->disabled()->persist();
        /** @var \Passbolt\Metadata\Model\Entity\MetadataKey $key */
        $key = MetadataKeyFactory::make()->withServerPrivateKey()->persist();

        $this->logInAs($admin);
        $data = $this->getValidPrivateKeyData($user->gpgkey->armored_key);
        $data2 = $this->getValidPrivateKeyData($user2->gpgkey->armored_key);
        $this->postJson('/metadata/keys/privates.json', [
            [
                'metadata_key_id' => $key->get('id'),
                'user_id' => $user->get('id'),
                'data' => $data,
            ],
            [
                'metadata_key_id' => $key->get('id'),
                'user_id' => $user2->get('id'),
                'data' => $data2,
            ],
        ]);

        $this->assertSuccess();
        /** @var \Passbolt\Metadata\Model\Entity\MetadataPrivateKey[] $resultKeys */
        $resultKeys = MetadataPrivateKeyFactory::find()
            ->where([
                'metadata_key_id' => $key->get('id'),
                'user_id IS NOT NULL',
            ])
            ->toArray();
        $this->assertCount(2, $resultKeys);
        $this->assertSame($data, $resultKeys[0]->data);
        $this->assertSame($data2, $resultKeys[1]->data);
        // assert response is empty because as of now client is not interested in any data in return
        $response = $this->getResponseBodyAsArray();
        $this->assertEmpty($response);
    }

    public function testMetadataMissingPrivateKeysShareController_Success_WithExpiredKey(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->persist();
        /** @var \Passbolt\Metadata\Model\Entity\MetadataKey $key */
        $key = MetadataKeyFactory::make()->withServerPrivateKey()->expired()->persist();

        $this->logInAs($admin);
        $data = $this->getValidPrivateKeyData($user->gpgkey->armored_key);
        $this->postJson('/metadata/keys/privates.json', [
            [
                'metadata_key_id' => $key->get('id'),
                'user_id' => $user->get('id'),
                'data' => $data,
            ],
        ]);

        $this->assertSuccess();
        /** @var \Passbolt\Metadata\Model\Entity\MetadataPrivateKey[] $resultKeys */
        $resultKeys = MetadataPrivateKeyFactory::find()
            ->where(['metadata_key_id' => $key->get('id'), 'user_id' => $user->get('id')])
            ->toArray();
        $this->assertCount(1, $resultKeys);
        $this->assertSame($data, $resultKeys[0]->data);
    }

    public function testMetadataMissingPrivateKeysShareController_ErrorNotLoggedIn(): void
    {
        $this->postJson('/metadata/keys/privates.json', []);
        $this->assertAuthenticationError();
    }

    public function testMetadataMissingPrivateKeysShareController_ErrorNotAdministrator(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->persist();
        /** @var \Passbolt\Metadata\Model\Entity\MetadataKey $key */
        $metadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();

        $this->logInAsUser();
        $this->postJson('/metadata/keys/privates.json', [
            [
                'metadata_key_id' => $metadataKey->get('id'),
                'user_id' => $user->id,
                'data' => $this->getValidPrivateKeyData($user->gpgkey->armored_key),
            ],
        ]);
        $this->assertResponseCode(403);
    }

    public function testMetadataMissingPrivateKeysShareController_ErrorEmptyData(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        $this->logInAs($admin);
        $this->postJson('/metadata/keys/privates.json', []);
        $this->assertResponseCode(400);
    }

    public function testMetadataMissingPrivateKeysShareController_ErrorMetadataAlreadyExistForUser(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->persist();
        /** @var \Passbolt\Metadata\Model\Entity\MetadataKey $key */
        $key = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($key)->withUserPrivateKey($user->gpgkey)->persist();

        $this->logInAs($admin);
        $this->postJson('/metadata/keys/privates.json', [
            [
                'metadata_key_id' => $key->get('id'),
                'user_id' => $user->get('id'),
                'data' => $this->getValidPrivateKeyData($user->gpgkey->armored_key),
            ],
        ]);

        $this->assertResponseCode(400);
        $response = $this->getResponseBodyAsArray();
        $this->assertCount(1, $response[0]);
        $this->assertArrayHasKey('user_id', $response[0]);
        $this->assertArrayHasKey('_isUnique', $response[0]['user_id']);
    }

    public function testMetadataMissingPrivateKeysShareController_ErrorDataNotEncryptedWithUserKey(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()
            ->admin()
            ->with('Gpgkeys', GpgkeyFactory::make()->withBettyKey())
            ->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->persist();
        $user2 = UserFactory::make()->withValidGpgKey()->persist();
        /** @var \Passbolt\Metadata\Model\Entity\MetadataKey $key */
        $key = MetadataKeyFactory::make()->withServerPrivateKey()->persist();

        $this->logInAs($admin);
        $this->postJson('/metadata/keys/privates.json', [
            [
                'metadata_key_id' => $key->get('id'),
                'user_id' => $user->get('id'),
                'data' => $this->getValidPrivateKeyData($user->gpgkey->armored_key),
            ],
            [
                'metadata_key_id' => $key->get('id'),
                'user_id' => $user2->get('id'),
                'data' => $this->getValidPrivateKeyData($admin->gpgkey->armored_key),
            ],
        ]);

        $this->assertResponseCode(400);
        $response = $this->getResponseBodyAsArray();
        $this->assertSame(1, array_keys($response)[0]); // ensure key is preserved
        $this->assertCount(1, $response[1]);
        $this->assertArrayHasKey('data', $response[1]);
        $this->assertArrayHasKey('isValidEncryptedMetadataPrivateKey', $response[1]['data']);
    }
}

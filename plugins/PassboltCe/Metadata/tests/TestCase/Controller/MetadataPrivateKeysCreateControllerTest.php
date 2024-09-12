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
 * @since         4.10.0
 */

namespace Passbolt\Metadata\Test\TestCase\Controller;

use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use App\Utility\UuidFactory;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;

/**
 * @uses \Passbolt\Metadata\Controller\MetadataPrivateKeysCreateController
 */
class MetadataPrivateKeysCreateControllerTest extends AppIntegrationTestCaseV5
{
    use GpgMetadataKeysTestTrait;

    public function testMetadataPrivateKeysCreateController_Success(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->persist();
        /** @var \Passbolt\Metadata\Model\Entity\MetadataKey $key */
        $key = MetadataKeyFactory::make()->withServerPrivateKey()->persist();

        $this->logInAs($admin);
        $this->postJson('/metadata/keys/' . $key->id . '/private.json', [
            'user_id' => $user->id,
            'data' => $this->getValidPrivateKeyData($user->gpgkey->armored_key),
        ]);

        $this->assertSuccess();
    }

    public function testMetadataPrivateKeysCreateController_ErrorNotLoggedIn(): void
    {
        $id = UuidFactory::uuid();
        $this->postJson('/metadata/keys/' . $id . '/private.json', []);
        $this->assertAuthenticationError();
    }

    public function testMetadataPrivateKeysCreateController_ErrorNotValidId(): void
    {
        $this->logInAsAdmin();
        $this->postJson('/metadata/keys/uuid/private.json', []);
        $this->assertResponseCode(400);
    }

    public function testMetadataPrivateKeysCreateController_ErrorEmptyData(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        /** @var \Passbolt\Metadata\Model\Entity\MetadataKey $key */
        $key = MetadataKeyFactory::make()->withServerPrivateKey()->persist();

        $this->logInAs($admin);
        $this->postJson('/metadata/keys/' . $key->id . '/private.json', []);
        $this->assertResponseCode(400);
    }

    public function testMetadataPrivateKeysCreateController_ErrorValidation_DataNotScalar(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->persist();
        /** @var \Passbolt\Metadata\Model\Entity\MetadataKey $key */
        $key = MetadataKeyFactory::make()->withServerPrivateKey()->persist();

        $this->logInAs($admin);
        $this->postJson('/metadata/keys/' . $key->id . '/private.json', [
            'user_id' => $user->id,
            'data' => [
                'key' => $this->getEncryptedMetadataPrivateKeyFoUserDifferent(),
            ]]);

        $this->assertResponseCode(400);
    }

    public function testMetadataPrivateKeysCreateController_ErrorValidation_IncorrectUser(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->persist();
        /** @var \Passbolt\Metadata\Model\Entity\MetadataKey $key */
        $key = MetadataKeyFactory::make()->withServerPrivateKey()->persist();

        $this->logInAs($admin);
        $this->postJson('/metadata/keys/' . $key->id . '/private.json', [
            'user_id' => $user->id,
            'data' => $this->getEncryptedMetadataPrivateKeyFoUserDifferent(),
        ]);

        $this->assertResponseCode(400);
        $this->assertResponseContains('isValidEncryptedMetadataPrivateKey');
    }
}

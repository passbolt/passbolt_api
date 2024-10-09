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
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use App\Utility\UuidFactory;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;

/**
 * @uses \Passbolt\Metadata\Controller\MetadataPrivateKeysUpdateController
 */
class MetadataPrivateKeysUpdateControllerTest extends AppIntegrationTestCaseV5
{
    use GpgMetadataKeysTestTrait;

    public function testMetadataPrivateKeysUpdateController_Success(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->persist();
        /** @var \Passbolt\Metadata\Model\Entity\MetadataKey $key */
        $key = MetadataKeyFactory::make()->withUserPrivateKey($user->gpgkey)->withServerPrivateKey()->persist();

        $gpg = OpenPGPBackendFactory::get();
        $adaPrivateKey = file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_private_nopassphrase.key');
        $gpg->setEncryptKey($adaPrivateKey);
        $gpg->setSignKey($adaPrivateKey, '');
        $msg = $gpg->encryptSign(json_encode($this->getValidPrivateKeyCleartext()));

        $this->logInAs($user);
        $this->putJson('/metadata/keys/private/' . $key->metadata_private_keys[0]->id . '.json', ['data' => $msg]);
        $this->assertSuccess();
    }

    public function testMetadataPrivateKeysUpdateController_ErrorNotLoggedIn(): void
    {
        $id = UuidFactory::uuid();
        $this->putJson('/metadata/keys/private/' . $id . '.json', []);
        $this->assertAuthenticationError();
    }

    public function testMetadataPrivateKeysUpdateController_ErrorNotValidId(): void
    {
        $this->logInAsUser();
        $this->putJson('/metadata/keys/private/uuid.json', []);
        $this->assertResponseCode(400);
    }

    public function testMetadataPrivateKeysUpdateController_ErrorEmptyData(): void
    {
        $this->logInAsUser();
        $id = UuidFactory::uuid();
        $this->putJson('/metadata/keys/private/' . $id . '.json', []);
        $this->assertResponseCode(400);
    }

    public function testMetadataPrivateKeysUpdateController_ErrorValidation(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->persist();
        /** @var \Passbolt\Metadata\Model\Entity\MetadataKey $key */
        $key = MetadataKeyFactory::make()->withUserPrivateKey($user->gpgkey)->withServerPrivateKey()->persist();

        $this->logInAs($user);
        $this->putJson('/metadata/keys/private/' . $key->metadata_private_keys[0]->id . '.json', ['data' => '🔥']);
        $this->assertResponseCode(400);
    }
}

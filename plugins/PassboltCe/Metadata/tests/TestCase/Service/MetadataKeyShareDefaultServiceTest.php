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

namespace Passbolt\Metadata\Test\TestCase\Service;

use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCaseV5;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\Core\Configure;
use Passbolt\Metadata\Exception\MetadataKeyShareException;
use Passbolt\Metadata\Service\MetadataKeyShareDefaultService;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataPrivateKeyFactory;

/**
 * @covers \Passbolt\Metadata\Service\MetadataKeyShareDefaultServiceTest
 */
class MetadataKeyShareDefaultServiceTest extends AppTestCaseV5
{
    public function testMetadataKeyShareDefaultService_FactorySanityCheck(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->persist();
        MetadataKeyFactory::make()->withUserPrivateKey($user->gpgkey)->withServerPrivateKey()->persist();
        $this->assertEquals(2, MetadataPrivateKeyFactory::count());
        $gpg = OpenPGPBackendFactory::get();
        $gpg->importServerKeyInKeyring();
        $gpg->setDecryptKeyFromFingerprint(
            Configure::read('passbolt.gpg.serverKey.fingerprint'),
            Configure::read('passbolt.gpg.serverKey.passphrase')
        );
        /** @var \Passbolt\Metadata\Model\Entity\MetadataPrivateKey $secret */
        $secret = MetadataPrivateKeyFactory::find()->where(['user_id IS' => null])->firstOrFail();
        $cleartext = $gpg->decrypt($secret->data);
        $gpg->clearKeys();
        $dto = json_decode($cleartext, true, 2, JSON_THROW_ON_ERROR);
        $this->assertEquals(MetadataPrivateKeyFactory::getValidPrivateKeyCleartext(), $dto);
    }

    public function testMetadataKeyShareDefaultService_Success(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->persist();
        MetadataKeyFactory::make()->withServerPrivateKey()->persist();

        $sut = new MetadataKeyShareDefaultService();
        $sut->shareMetadataKeyWithUser($user);

        /** @var \Passbolt\Metadata\Model\Entity\MetadataPrivateKey $privateKey */
        $privateKey = MetadataPrivateKeyFactory::find()->where(['user_id IS' => $user->id])->firstOrFail();
        $gpg = OpenPGPBackendFactory::get();
        $key = FIXTURES . DS . 'Gpgkeys' . DS . 'ada_private_nopassphrase.key';
        $fingerprint = $gpg->importKeyIntoKeyring(file_get_contents($key));
        $gpg->setDecryptKeyFromFingerprint($fingerprint, '');
        $json = $gpg->decrypt($privateKey->data);
        $privateKeyDto = json_decode($json, true, 2, JSON_THROW_ON_ERROR);

        $this->assertEquals(MetadataPrivateKeyFactory::getValidPrivateKeyCleartext(), $privateKeyDto);
    }

    public function testMetadataKeyShareDefaultService_Error_KeyNotFound(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $sut = new MetadataKeyShareDefaultService();
        try {
            $sut->shareMetadataKeyWithUser($user);
            $this->fail();
        } catch (MetadataKeyShareException $exception) {
            $this->assertTextContains('not found', $exception->getMessage());
        }
    }

    public function testMetadataKeyShareDefaultService_Error_KeyMessageNotForServer(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->persist();
        $filename = FIXTURES . DS . 'OpenPGP' . DS . 'Messages' . DS . 'ada_for_betty_signed.msg';
        $armoredMessage = file_get_contents($filename);
        $privateKey = MetadataPrivateKeyFactory::make()->patchData([
            'data' => $armoredMessage,
            'user_id' => null,
        ]);
        MetadataKeyFactory::make()->with('MetadataPrivateKeys', $privateKey)->persist();

        $sut = new MetadataKeyShareDefaultService();
        try {
            $sut->shareMetadataKeyWithUser($user);
            $this->fail();
        } catch (MetadataKeyShareException $exception) {
            $this->assertTextContains('Decryption failed', $exception->getMessage());
        }
    }

    public function testMetadataKeyShareDefaultService_Error_InvalidSignature(): void
    {
        $this->markTestIncomplete();
    }

    public function testMetadataKeyShareDefaultService_Error_KeyMessageNotAValidJson(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->persist();
        $gpg = OpenPGPBackendFactory::get();
        $gpg->importServerKeyInKeyring();
        $gpg->setEncryptKeyFromFingerprint(Configure::read('passbolt.gpg.serverKey.fingerprint'));
        $msg = $gpg->encrypt('🔥');
        /** @var \Passbolt\Metadata\Model\Entity\MetadataPrivateKey $privateKey */
        $privateKey = MetadataPrivateKeyFactory::make()->patchData([
            'data' => $msg,
            'user_id' => null,
        ]);
        MetadataKeyFactory::make()->with('MetadataPrivateKeys', $privateKey)->persist();

        $sut = new MetadataKeyShareDefaultService();
        try {
            $sut->shareMetadataKeyWithUser($user);
            $this->fail();
        } catch (MetadataKeyShareException $exception) {
            $this->assertTextContains('JSON', $exception->getMessage());
        }
    }

    public function testMetadataKeyShareDefaultService_Error_KeyMessageNotAValidKey(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->persist();
        $gpg = OpenPGPBackendFactory::get();
        $gpg->importServerKeyInKeyring();
        $gpg->setEncryptKeyFromFingerprint(Configure::read('passbolt.gpg.serverKey.fingerprint'));
        $msg = $gpg->encrypt(json_encode(['🔥' => '🔥']));
        /** @var \Passbolt\Metadata\Model\Entity\MetadataPrivateKey $privateKey */
        $privateKey = MetadataPrivateKeyFactory::make()->patchData([
            'data' => $msg,
            'user_id' => null,
        ]);
        MetadataKeyFactory::make()->with('MetadataPrivateKeys', $privateKey)->persist();

        $sut = new MetadataKeyShareDefaultService();
        try {
            $sut->shareMetadataKeyWithUser($user);
            $this->fail();
        } catch (MetadataKeyShareException $exception) {
            $this->assertTextContains('cleartext data is not valid', $exception->getMessage());
        }
    }

    public function testMetadataKeyShareDefaultService_ErrorDeletedUser(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->deleted()->persist();
        MetadataKeyFactory::make()->withServerPrivateKey()->persist();

        $sut = new MetadataKeyShareDefaultService();
        try {
            $sut->shareMetadataKeyWithUser($user);
            $this->fail();
        } catch (MetadataKeyShareException $exception) {
            $this->assertTextContains('not valid', $exception->getMessage());
            $this->assertTextContains('validated', $exception->getMessage());
        }
    }
}

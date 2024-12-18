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

use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCaseV5;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\Core\Configure;
use Cake\Routing\Router;
use Passbolt\Metadata\Exception\MetadataKeyShareException;
use Passbolt\Metadata\Service\MetadataKeyShareDefaultService;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataPrivateKeyFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;

/**
 * @covers \Passbolt\Metadata\Service\MetadataKeyShareDefaultServiceTest
 */
class MetadataKeyShareDefaultServiceTest extends AppTestCaseV5
{
    use GpgMetadataKeysTestTrait;

    private $gpg;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->gpg = OpenPGPBackendFactory::get();
        // Set ssl force config to `false` when url is http, this is to pass `urlWithProtocol` validation rule for the domain field
        $domain = Router::url('/', true);
        if (strpos($domain, 'http://') === 0) {
            Configure::write('passbolt.ssl.force', false);
        }
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        OpenPGPBackendFactory::reset();
        $this->gpg = null;

        parent::tearDown();
    }

    public function testMetadataKeyShareDefaultService_FactorySanityCheck(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->persist();
        MetadataKeyFactory::make()->withUserPrivateKey($user->gpgkey)->withServerPrivateKey()->persist();
        $this->assertEquals(2, MetadataPrivateKeyFactory::count());
        $this->gpg = OpenPGPBackendFactory::get();
        $this->gpg->importServerKeyInKeyring();
        $this->gpg->setDecryptKeyFromFingerprint(
            Configure::read('passbolt.gpg.serverKey.fingerprint'),
            Configure::read('passbolt.gpg.serverKey.passphrase')
        );
        /** @var \Passbolt\Metadata\Model\Entity\MetadataPrivateKey $secret */
        $secret = MetadataPrivateKeyFactory::find()->where(['user_id IS' => null])->firstOrFail();
        $cleartext = $this->gpg->decrypt($secret->data);
        $dto = json_decode($cleartext, true, 2, JSON_THROW_ON_ERROR);
        $this->assertEquals($this->getValidPrivateKeyCleartext(), $dto);
    }

    public function testMetadataKeyShareDefaultService_Success_CreatedByServer(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->persist();
        MetadataKeyFactory::make()->withServerPrivateKey()->persist();

        $sut = new MetadataKeyShareDefaultService();
        $sut->shareMetadataKeysWithUser($user);

        /** @var \Passbolt\Metadata\Model\Entity\MetadataPrivateKey $privateKey */
        $privateKey = MetadataPrivateKeyFactory::find()->where(['user_id IS' => $user->id])->firstOrFail();
        $this->gpg = OpenPGPBackendFactory::get();
        $key = FIXTURES . DS . 'Gpgkeys' . DS . 'ada_private_nopassphrase.key';
        $fingerprint = $this->gpg->importKeyIntoKeyring(file_get_contents($key));
        $this->gpg->setDecryptKeyFromFingerprint($fingerprint, '');
        $json = $this->gpg->decrypt($privateKey->data);
        $privateKeyDto = json_decode($json, true, 2, JSON_THROW_ON_ERROR);

        $this->assertEquals($this->getValidPrivateKeyCleartext(), $privateKeyDto);
    }

    public function testMetadataKeyShareDefaultService_Success_CreatedByUser(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()
            ->admin()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withBettyKey())
            ->persist();
        $this->gpg = OpenPGPBackendFactory::get();
        $this->gpg->importServerKeyInKeyring();
        $fingerprint = Configure::read('passbolt.gpg.serverKey.fingerprint');
        $this->gpg->setEncryptKeyFromFingerprint($fingerprint);
        $adaPrivateKey = file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_private_nopassphrase.key');
        $this->gpg->setSignKey($adaPrivateKey, '');
        $msg = $this->gpg->encryptSign(json_encode($this->getValidPrivateKeyCleartext()));
        /** @var \Passbolt\Metadata\Model\Entity\MetadataPrivateKey $privateKey */
        $privateKey = MetadataPrivateKeyFactory::make()->patchData([
            'data' => $msg,
            'user_id' => null,
            'created_by' => $admin->id,
            'modified_by' => $admin->id,
        ]);
        MetadataKeyFactory::make()->with('MetadataPrivateKeys', $privateKey)->persist();

        $sut = new MetadataKeyShareDefaultService();
        $sut->shareMetadataKeysWithUser($user);

        /** @var \Passbolt\Metadata\Model\Entity\MetadataPrivateKey $privateKey */
        $privateKey = MetadataPrivateKeyFactory::find()->where(['user_id IS' => $user->id])->firstOrFail();
        $this->assertNotEmpty($privateKey);
    }

    public function testMetadataKeyShareDefaultService_Error_KeyNotFound(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $sut = new MetadataKeyShareDefaultService();
        try {
            $sut->shareMetadataKeysWithUser($user);
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
            $sut->shareMetadataKeysWithUser($user);
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
        $this->gpg = OpenPGPBackendFactory::get();
        $this->gpg->importServerKeyInKeyring();
        $fingerprint = Configure::read('passbolt.gpg.serverKey.fingerprint');
        $this->gpg->setEncryptKeyFromFingerprint($fingerprint);
        $this->gpg->setSignKeyFromFingerprint($fingerprint, Configure::read('passbolt.gpg.serverKey.passphrase'));
        $msg = $this->gpg->encryptSign('🔥');
        /** @var \Passbolt\Metadata\Model\Entity\MetadataPrivateKey $privateKey */
        $privateKey = MetadataPrivateKeyFactory::make()->patchData([
            'data' => $msg,
            'user_id' => null,
        ]);
        MetadataKeyFactory::make()->with('MetadataPrivateKeys', $privateKey)->persist();

        $sut = new MetadataKeyShareDefaultService();
        try {
            $sut->shareMetadataKeysWithUser($user);
            $this->fail();
        } catch (MetadataKeyShareException $exception) {
            $this->assertTextContains('JSON', $exception->getMessage());
        }
    }

    public function testMetadataKeyShareDefaultService_Error_KeyMessageNotAValidKey(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->persist();
        $this->gpg = OpenPGPBackendFactory::get();
        $this->gpg->importServerKeyInKeyring();
        $fingerprint = Configure::read('passbolt.gpg.serverKey.fingerprint');
        $this->gpg->setEncryptKeyFromFingerprint($fingerprint);
        $this->gpg->setSignKeyFromFingerprint($fingerprint, Configure::read('passbolt.gpg.serverKey.passphrase'));
        $msg = $this->gpg->encryptSign(json_encode(['🔥' => '🔥']));
        /** @var \Passbolt\Metadata\Model\Entity\MetadataPrivateKey $privateKey */
        $privateKey = MetadataPrivateKeyFactory::make()->patchData([
            'data' => $msg,
            'user_id' => null,
        ]);
        MetadataKeyFactory::make()->with('MetadataPrivateKeys', $privateKey)->persist();

        $sut = new MetadataKeyShareDefaultService();
        try {
            $sut->shareMetadataKeysWithUser($user);
            $this->fail();
        } catch (MetadataKeyShareException $exception) {
            $this->assertTextContains('cleartext data is not valid', $exception->getMessage());
        }
    }

    public function testMetadataKeyShareDefaultService_Error_KeyMessageNotAValidSignatureForCreatedBy(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()
            ->admin()
            ->with('Gpgkeys', GpgkeyFactory::make()->withBettyKey())
            ->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->persist();
        $this->gpg = OpenPGPBackendFactory::get();
        $this->gpg->importServerKeyInKeyring();
        $fingerprint = Configure::read('passbolt.gpg.serverKey.fingerprint');
        $this->gpg->setEncryptKeyFromFingerprint($fingerprint);
        $this->gpg->setSignKeyFromFingerprint($fingerprint, Configure::read('passbolt.gpg.serverKey.passphrase'));
        $msg = $this->gpg->encryptSign(json_encode($this->getValidPrivateKeyCleartext()));
        /** @var \Passbolt\Metadata\Model\Entity\MetadataPrivateKey $privateKey */
        $privateKey = MetadataPrivateKeyFactory::make()->patchData([
            'data' => $msg,
            'user_id' => null,
            'created_by' => $admin->id,
            'modified_by' => $admin->id,
        ]);
        MetadataKeyFactory::make()->with('MetadataPrivateKeys', $privateKey)->persist();

        $sut = new MetadataKeyShareDefaultService();
        try {
            $sut->shareMetadataKeysWithUser($user);
            $this->fail();
        } catch (MetadataKeyShareException $exception) {
            $this->assertTextContains('Invalid signature', $exception->getMessage());
        }
    }

    public function testMetadataKeyShareDefaultService_ErrorDeletedUser(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->withValidGpgKey()->deleted()->persist();
        MetadataKeyFactory::make()->withServerPrivateKey()->persist();

        $sut = new MetadataKeyShareDefaultService();
        try {
            $sut->shareMetadataKeysWithUser($user);
            $this->fail();
        } catch (MetadataKeyShareException $exception) {
            $this->assertTextContains('not valid', $exception->getMessage());
            $this->assertTextContains('validated', $exception->getMessage());
        }
    }
}

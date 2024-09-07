<?php
declare(strict_types=1);

namespace Passbolt\Metadata\Test\Factory;

use App\Model\Entity\Gpgkey;
use App\Model\Entity\User;
use App\Test\Factory\UserFactory;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\Chronos\Chronos;
use Cake\Core\Configure;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;
use Passbolt\Metadata\Model\Entity\MetadataKey;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;

/**
 * MetadataPrivateKeyFactory
 *
 * @method \Passbolt\Metadata\Model\Entity\MetadataPrivateKey getEntity()
 * @method \Passbolt\Metadata\Model\Entity\MetadataPrivateKey[] getEntities()
 * @method \Passbolt\Metadata\Model\Entity\MetadataPrivateKey|\Passbolt\Metadata\Model\Entity\MetadataPrivateKey[] persist()
 * @method static \Passbolt\Metadata\Model\Entity\MetadataPrivateKey get(mixed $primaryKey, array $options = [])
 */
class MetadataPrivateKeyFactory extends CakephpBaseFactory
{
    use GpgMetadataKeysTestTrait;

    /**
     * @var array|null cached private keys
     */
    private static $keycache = null;

    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Passbolt/Metadata.MetadataPrivateKeys';
    }

    /**
     * Defines the factory's default values. This is useful for
     * not nullable fields. You may use methods of the present factory here too.
     *
     * @return void
     */
    protected function setDefaultTemplate(): void
    {
        $this->setDefaultData(function (Generator $faker) {
            return [
                'data' => $this->getDummyPrivateKeyOpenPGPMessage(),
                'created' => Chronos::now()->subDays($faker->randomNumber(3)),
                'modified' => Chronos::now()->subDays($faker->randomNumber(3)),
            ];
        });
    }

    /**
     * Sets user_id to null.
     *
     * @return $this
     */
    public function serverKey()
    {
        return $this->setField('user_id', null);
    }

    /**
     * @param MetadataKey|null $metadataKey Metadata key entity.
     * @return $this
     */
    public function withMetadataKey(?MetadataKey $metadataKey = null)
    {
        if (is_null($metadataKey)) {
            if (is_null($this->getEntity()->get('user_id'))) {
                $metadataKey = MetadataKeyFactory::make()->withServerKey()->withCreatorAndModifier()->persist();
            } else {
                // user related
                $metadataKey = MetadataKeyFactory::make()->withCreatorAndModifier()->persist();
            }
        }

        return $this->with('MetadataKeys', $metadataKey);
    }

    /**
     * @param User|null $user
     * @return $this
     */
    public function withUser(?User $user = null)
    {
        if (is_null($user)) {
            $user = UserFactory::make()->persist();
        }

        return $this->with('Users', $user);
    }

    public function withUserPrivateKey(Gpgkey $gpgkey)
    {
        return $this->patchData([
            'data' => $this->getValidPrivateKeyData($gpgkey->armored_key),
            'user_id' => $gpgkey->user_id,
        ]);
    }

    public function withServerPrivateKey()
    {
        return $this->patchData([
            'data' => $this->getValidPrivateKeyDataForServer(),
            'user_id' => null,
        ]);
    }

    public function getValidPrivateKeyDataForServer(): string
    {
        $fingerprint = Configure::read('passbolt.gpg.serverKey.fingerprint');
        if (!isset(static::$keycache[$fingerprint])) {
            $gpg = OpenPGPBackendFactory::get();
            $gpg->importServerKeyInKeyring();
            $gpg->setEncryptKeyFromFingerprint($fingerprint);
            $gpg->setSignKeyFromFingerprint($fingerprint, Configure::read('passbolt.gpg.serverKey.passphrase'));
            self::$keycache[$fingerprint] = $gpg->encryptSign($this->getValidPrivateKeyCleartextJson());
            $gpg->clearKeys();
        }

        return self::$keycache[$fingerprint];
    }

    public function getValidPrivateKeyData(string $publicKey): string
    {
        $gpg = OpenPGPBackendFactory::get();
        $encryptKeyInfo = $gpg->getPublicKeyInfo($publicKey);
        $fingerprint = $encryptKeyInfo['fingerprint'];
        if (!isset(static::$keycache[$fingerprint])) {
            $gpg = OpenPGPBackendFactory::get();
            $gpg->setEncryptKey($publicKey);
            $gpg->setSignKeyFromFingerprint(
                Configure::read('passbolt.gpg.serverKey.fingerprint'),
                Configure::read('passbolt.gpg.serverKey.passphrase')
            );
            self::$keycache[$fingerprint] = $gpg->encrypt($this->getValidPrivateKeyCleartextJson());
            $gpg->clearKeys();
        }

        return self::$keycache[$fingerprint];
    }

    public function getValidPrivateKeyCleartextJson(): string
    {
        return json_encode($this->getValidPrivateKeyCleartext());
    }
}

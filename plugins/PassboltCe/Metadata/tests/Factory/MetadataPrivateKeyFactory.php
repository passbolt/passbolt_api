<?php
declare(strict_types=1);

namespace Passbolt\Metadata\Test\Factory;

use App\Model\Entity\Gpgkey;
use App\Model\Entity\User;
use App\Test\Factory\UserFactory;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\Chronos\Chronos;
use Cake\Core\Configure;
use Cake\Routing\Router;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;
use Passbolt\Metadata\Model\Entity\MetadataKey;

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
                'data' => self::getDummyPrivateKeyOpenPGPMessage(),
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
                $metadataKey = MetadataKeyFactory::make()->withValidOpenPGPKey()->withCreatorAndModifier()->persist();
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
            'data' => self::getValidPrivateKeyData($gpgkey->armored_key),
            'user_id' => $gpgkey->user_id,
        ]);
    }

    public function withServerPrivateKey()
    {
        return $this->patchData([
            'data' => self::getValidPrivateKeyDataForServer(),
            'user_id' => null,
        ]);
    }

    public static function getValidPrivateKeyDataForServer(): string
    {
        $fingerprint = Configure::read('passbolt.gpg.serverKey.fingerprint');
        if (!isset(static::$keycache[$fingerprint])) {
            $gpg = OpenPGPBackendFactory::get();
            $gpg->importServerKeyInKeyring();
            $gpg->setEncryptKeyFromFingerprint($fingerprint);
            self::$keycache[$fingerprint] = $gpg->encrypt(self::getValidPrivateKeyCleartextJson());
        }

        return self::$keycache[$fingerprint];
    }

    public static function getValidPrivateKeyData(string $publicKey): string
    {
        $gpg = OpenPGPBackendFactory::get();
        $encryptKeyInfo = $gpg->getPublicKeyInfo($publicKey);
        $fingerprint = $encryptKeyInfo['fingerprint'];
        if (!isset(static::$keycache[$fingerprint])) {
            $gpg = OpenPGPBackendFactory::get();
            $gpg->setEncryptKey($publicKey);
            self::$keycache[$fingerprint] = $gpg->encrypt(self::getValidPrivateKeyCleartextJson());
        }

        return self::$keycache[$fingerprint];
    }

    public static function getValidPrivateKeyCleartextJson(): string
    {
        return json_encode(self::getValidPrivateKeyCleartext());
    }

    public static function getValidPrivateKeyCleartext(): array
    {
        // See MetadataKeyFactory::getValidPublicKey()
        // No passphrase
        return [
            'object_type' => 'PASSBOLT_METADATA_PRIVATE_KEY',
            'domain' => Router::url('/', true),
            'fingerprint' => '75E953F48EC5C1FCFFE575BB1BD05459D565666B',
            'passphrase' => '',
            'armored_key' => '-----BEGIN PGP PRIVATE KEY BLOCK-----

lFgEZtXbahYJKwYBBAHaRw8BAQdAgsF7oiI4napsPbhAQ9mrWY6vPLI5PHvqF53n
PVVdHIYAAQCgbcRKgjAlDoeqGG+8cNKQUkhOHEt4grJx5lgrOzTQ1Q4wtDZQYXNz
Ym9sdCBTaGFyZWQgTWV0YWRhdGEgS2V5IDx1bml0LXRlc3RzQHBhc3Nib2x0LmNv
bT6IkwQTFgoAOxYhBHXpU/SOxcH8/+V1uxvQVFnVZWZrBQJm1dtqAhsjBQsJCAcC
AiICBhUKCQgLAgQWAgMBAh4HAheAAAoJEBvQVFnVZWZrB0oBAJuk0GM+PPkFW/EM
sT7CTfbMPHw+jYP8501Rfjw0DOEsAP4jLPjGq7UTvJOoHfdWfCsdGKAgT6Ycx2CU
GgmXjGElC5xdBGbV22oSCisGAQQBl1UBBQEBB0AxhMzEG+RJeroF02KLX5uEmpsl
vOopT1firRJ2j22tEwMBCAcAAP9ZVlMjLX+cE44Z1eemDRrf7arlEKY+lqtsYRrr
FwBTGA6TiHgEGBYKACAWIQR16VP0jsXB/P/ldbsb0FRZ1WVmawUCZtXbagIbDAAK
CRAb0FRZ1WVma2nnAPsHnnyfzsVdMLP3vpBcjEHCd4yFtf6uj/EtiBEYHE2QvAEA
q1g/Fm0XqgBkfG2yUm3DH2mv0MFG8okEW1YWhcLJMQ0=
=ETeQ
-----END PGP PRIVATE KEY BLOCK-----',
        ];
    }

    /**
     * A valid OpenPGP message encrypted for maki_public.key and signed with unsecure_private.key.
     *
     * @deprecated NOT A VALID PRIVATE KEY MESSAGE
     * @return string
     */
    public static function getDummyPrivateKeyOpenPGPMessage(): string
    {
        // Decrypted message: super secret message
        return "-----BEGIN PGP MESSAGE-----

wV4DrGC7ooPDztsSAQdAln93/614xeFEl9aaP1VVTFZtbqF7+vle6L+kzSc+BU8w
jk/YF9DJ9h1ovB64DKi4z0rxplOSR+d1FEBZBnDLHD5N2npyFxtGuAQ6vOoloJPD
0sHCAXm7PcQeEN2dMhL7ctRWfOTP3F1OF9CG3dUbumKkKDDPf9uHqT17ij7Ifavn
c3sii0LRDDlknva30jxtfwmJilX6LiWqAI+HzPeSwK1FLBhd5tM8Tknr2kh8pCKF
lxLInZJZQbOCUJ1mQ6oW9IcV3Eu6n+BkeT26l/kGseuqITnDfo13X6FQCpHO7uLR
993AN6Lf0kUNbcYVmyA/o1Fbz/PLgRGIzJRwWB/DTjUJ9vfwl3DLNz+25FGr+zxL
NhyuchytmtY8ozO49YZp+l3d8N8yJvg2b++KG3PFB+JCfzlbLoTjD14hBig907Ez
eC8n5Zmg6uIBY4CXVspCA5JoPZcGWii+jxhX4GnK82k2TPVMsIwkiBAWqqT80FWP
ssMIWA23BDAA7DojZIUf/s+Tv05xtoEfNIPeuUP72g6K7bBaTloL116eEuzq7ctj
JpilQqzgQuIx/UpxkXg+XYnbLCT/kxvaf4pjwepsm3R4kbt8acpB6VkVeM/Va0eI
Ucuo2SD00yK5DTV/OMmS8ERYSD+N3lwzMbo9WBrpz3UYX37b2mnDMisXaUu54xGX
n8pIxxyYdb6dVwxzJpvINvAiVUxC6wSDu+1u0Urh1ZV8sdN85qXCZMCn1af6RmQ6
VmUhzIwATp4OkNJMSIvwMcVZ9UCfN33xLrn3Vo+7Bm2u08Q5CpLGuRSMeVgySikj
MDkFSiznzXL0gQ4U1f8pDcY4+HIBItVtew/5fUNUkzNKA+JXqb9eOgavRAZIbb4d
I4okmMzJpJrQJ7zEzOh8g3eIjBInevhcaaJqSwt9JGphoSND+b0XCIV1XOehLwEe
dT/PmTWE57npBIIz4kQQcHOziFAG
=vK1i
-----END PGP MESSAGE-----
";
    }
}

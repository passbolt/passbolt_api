<?php
declare(strict_types=1);

namespace Passbolt\Metadata\Test\Factory;

use App\Model\Entity\User;
use App\Test\Factory\UserFactory;
use Cake\Chronos\Chronos;
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
                'data' => self::getValidPgpMessage(),
                'created' => Chronos::now()->subDays($faker->randomNumber(3)),
                'modified' => Chronos::now()->subDays($faker->randomNumber(3)),
            ];
        });
    }

    /**
     * @return $this
     */
    public function serverKey()
    {
        return $this->setField('user_id', null);
    }

    /**
     * @param MetadataKey|null $metadataKey
     * @return $this
     */
    public function withMetadataKey(?MetadataKey $metadataKey = null)
    {
        if (is_null($metadataKey)) {
            $metadataKey = MetadataKeyFactory::make()->withMakiKey()->withCreatorAndModifier()->persist();
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

    /**
     * A valid OpenPGP message encrypted for maki_public.key and signed with unsecure_private.key.
     *
     * @return string
     */
    public static function getValidPgpMessage(): string
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

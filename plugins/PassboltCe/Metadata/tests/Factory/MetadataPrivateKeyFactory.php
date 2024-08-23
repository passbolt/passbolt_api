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

    public static function getValidPgpMessage(): string
    {
        return "-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf9HpfcNeuC5W/VAzEtAe8mTBUk1vcJENtGpMyRkVTC8KbQ
xaEr3+UG6h0ZVzfrMFYrYLolS3fie83cj4FnC3gg1uijo7zTf9QhJMdi7p/ASB6N
y7//8AriVqUAOJ2WCxAVseQx8qt2KqkQvS7F7iNUdHfhEhiHkczTlehyel7PEeas
SdM/kKEsYKk6i4KLPBrbWsflFOkfQGcPL07uRK3laFz8z4LNzvNQOoU7P/C1L0X3
tlK3vuq+r01zRwmflCaFXaHVifj3X74ljhlk5i/JKLoPRvbxlPTevMNag5e6QhPQ
kpj+TJD2frfGlLhyM50hQMdJ7YVypDllOBmnTRwZ0tJFAXm+F987ovAVLMXGJtGO
P+b3c493CfF0fQ1MBYFluVK/Wka8usg/b0pNkRGVWzBcZ1BOONYlOe/JmUyMutL5
hcciUFw5
=TcQF
-----END PGP MESSAGE-----";
    }
}

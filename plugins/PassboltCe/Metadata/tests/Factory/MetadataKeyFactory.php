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
namespace Passbolt\Metadata\Test\Factory;

use App\Model\Entity\User;
use App\Test\Factory\Traits\ArmoredKeyFactoryTrait;
use App\Test\Factory\UserFactory;
use Cake\Chronos\Chronos;
use Cake\I18n\FrozenTime;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;

/**
 * Metadata key factory.

 * @method \Passbolt\Metadata\Model\Entity\MetadataKey|\Passbolt\Metadata\Model\Entity\MetadataKey[] persist()
 * @method \Passbolt\Metadata\Model\Entity\MetadataKey getEntity()
 * @method \Passbolt\Metadata\Model\Entity\MetadataKey[] getEntities()
 */
class MetadataKeyFactory extends CakephpBaseFactory
{
    use ArmoredKeyFactoryTrait;
    use GpgMetadataKeysTestTrait;

    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Passbolt/Metadata.MetadataKeys';
    }

    /**
     * Defines the factory's default values. This is useful for
     * not nullable fields. You may use methods of the present factory here too.
     *
     * @return void
     */
    protected function setDefaultTemplate(): void
    {
        $dummyData = self::getDummyKeyInfo();

        $this->setDefaultData(function (Generator $faker) use ($dummyData) {
            return [
                'fingerprint' => $dummyData['fingerprint'],
                'armored_key' => $dummyData['armored_key'],
                'created' => Chronos::now()->subDays($faker->randomNumber(3)),
                'modified' => Chronos::now()->subDays($faker->randomNumber(3)),
                'deleted' => null,
                'created_by' => $faker->uuid(),
                'modified_by' => $faker->uuid(),
            ];
        });
    }

    /**
     * Set deleted in the past.
     *
     * @return $this
     */
    public function deleted()
    {
        return $this->setField('deleted', FrozenTime::yesterday());
    }

    /**
     * Set the armored key and fingerprint to a valid openpgp key
     *
     * @return $this
     */
    public function withValidOpenPGPKey()
    {
        return $this->withMakiKey();
    }

    /**
     * @return $this
     */
    public function withMakiKey()
    {
        $keyInfo = $this->getMakiKeyInfo();

        return $this->patchData([
            'armored_key' => $keyInfo['armored_key'],
            'fingerprint' => $keyInfo['fingerprint'],
        ]);
    }

    /**
     * @return $this
     */
    public function withExpiredKey()
    {
        $keyInfo = $this->getExpiredKeyInfo();

        return $this->patchData([
            'armored_key' => $keyInfo['armored_key'],
            'fingerprint' => $keyInfo['fingerprint'],
        ]);
    }

    public function withCreatorAndModifier(?User $user = null)
    {
        return $this->withModifier($user)->withCreator($user);
    }

    public function withModifier(?User $user = null)
    {
        if (is_null($user)) {
            $user = UserFactory::make()->persist();
        }

        return $this->with('Modifier', $user)->setField('modified_by', $user->get('id'));
    }

    public function withCreator(?User $user = null)
    {
        if (is_null($user)) {
            $user = UserFactory::make()->persist();
        }

        return $this->with('Creator', $user)->setField('created_by', $user->get('id'));
    }

    /**
     * Returns key information to use for metadata key tests.
     *
     * @return string[]
     */
    public static function getDummyKeyInfo(): array
    {
        return [
            'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----

xjMEZs7I7BYJKwYBBAHaRw8BAQdAXPnuKJwjOYF/cNRZSGCX/ftwHDfiLWTc
PQE0dMglp7/NN01ldGFkYXRhIFNlcnZlciBLZXkgPG1ldGFkYXRhX3NlcnZl
cl9rZXlAcGFzc2JvbHQudGVzdD7CjAQQFgoAPgWCZs7I7AQLCQcICZB5WxWo
41ot9AMVCAoEFgACAQIZAQKbAwIeARYhBN5tDBSCnh2xswxonnlbFajjWi30
AACKNAD/d8kCyHxZ2SFJ3YmkKfQwhoy7BSr8oOUt8DwUVtX05moBAMh7y2Hd
8YWNAt2y5VKor70p9Aigd4qOAqowhNlibOwLzjgEZs7I7BIKKwYBBAGXVQEF
AQEHQL3ZD0EgxabScRBLIfrb+0SU8jkbILrsEfNYQcRX4LM0AwEIB8J4BBgW
CgAqBYJmzsjsCZB5WxWo41ot9AKbDBYhBN5tDBSCnh2xswxonnlbFajjWi30
AADm+wD/UoI/iQYbjTVt1YvvgHVWROtFHpJlmIHtfeNkVG420KABAOH1/krs
8lq98P8ujYtxzHFy1BMGgYaA7rBTQ9IS49oH
=VVZV
-----END PGP PUBLIC KEY BLOCK-----
',
            'fingerprint' => 'DE6D0C14829E1DB1B30C689E795B15A8E35A2DF4',
        ];
    }
}

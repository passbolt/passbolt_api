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

use App\Model\Entity\Gpgkey;
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
        $dummyData = self::getValidPublicKey();

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
        $keyInfo = self::getValidPublicKey();

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

    public function withUserPrivateKey(Gpgkey $gpgkey)
    {
        return $this->with('MetadataPrivateKeys', MetadataPrivateKeyFactory::make()->withUserPrivateKey($gpgkey));
    }

    public function withServerPrivateKey()
    {
        return $this->with('MetadataPrivateKeys', MetadataPrivateKeyFactory::make()->withServerPrivateKey());
    }

    /**
     * Returns key information to use for metadata key tests.
     *
     * @return string[]
     */
    public static function getValidPublicKey(): array
    {
        return [
            'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----

mDMEZtXbahYJKwYBBAHaRw8BAQdAgsF7oiI4napsPbhAQ9mrWY6vPLI5PHvqF53n
PVVdHIa0NlBhc3Nib2x0IFNoYXJlZCBNZXRhZGF0YSBLZXkgPHVuaXQtdGVzdHNA
cGFzc2JvbHQuY29tPoiTBBMWCgA7FiEEdelT9I7Fwfz/5XW7G9BUWdVlZmsFAmbV
22oCGyMFCwkIBwICIgIGFQoJCAsCBBYCAwECHgcCF4AACgkQG9BUWdVlZmsHSgEA
m6TQYz48+QVb8QyxPsJN9sw8fD6Ng/znTVF+PDQM4SwA/iMs+MartRO8k6gd91Z8
Kx0YoCBPphzHYJQaCZeMYSULuDgEZtXbahIKKwYBBAGXVQEFAQEHQDGEzMQb5El6
ugXTYotfm4SamyW86ilPV+KtEnaPba0TAwEIB4h4BBgWCgAgFiEEdelT9I7Fwfz/
5XW7G9BUWdVlZmsFAmbV22oCGwwACgkQG9BUWdVlZmtp5wD7B558n87FXTCz976Q
XIxBwneMhbX+ro/xLYgRGBxNkLwBAKtYPxZtF6oAZHxtslJtwx9pr9DBRvKJBFtW
FoXCyTEN
=zZuC
-----END PGP PUBLIC KEY BLOCK-----
',
            'fingerprint' => '75E953F48EC5C1FCFFE575BB1BD05459D565666B',
        ];
    }
}

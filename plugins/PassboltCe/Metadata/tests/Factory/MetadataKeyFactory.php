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
use Cake\I18n\DateTime;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;

/**
 * Metadata key factory.

 * @method \Passbolt\Metadata\Model\Entity\MetadataKey|\Passbolt\Metadata\Model\Entity\MetadataKey[] persist()
 * @method \Passbolt\Metadata\Model\Entity\MetadataKey getEntity()
 * @method \Passbolt\Metadata\Model\Entity\MetadataKey[] getEntities()
 * @method static  \Passbolt\Metadata\Model\Entity\MetadataKey firstOrFail($conditions = null)
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
        $dummyData = $this->getMetadataKeyInfo();

        $this->setDefaultData(function (Generator $faker) use ($dummyData) {
            return [
                'fingerprint' => $dummyData['fingerprint'],
                'armored_key' => $dummyData['public_key'],
                'created' => Chronos::now()->subDays($faker->randomNumber(3)),
                'modified' => Chronos::now()->subDays($faker->randomNumber(3)),
                'expired' => null,
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
        return $this->setField('deleted', \Cake\I18n\DateTime::yesterday());
    }

    /**
     * Set expired in the past.
     *
     * @return $this
     */
    public function expired()
    {
        return $this->setField('expired', \Cake\I18n\DateTime::yesterday());
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

    /**
     * @return $this
     */
    public function withServerKey()
    {
        $keyInfo = $this->getMetadataKeyInfo();

        return $this->patchData([
            'armored_key' => $keyInfo['public_key'],
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
}

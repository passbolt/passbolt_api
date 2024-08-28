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

/**
 * Metadata key factory.

 * @method \Passbolt\Metadata\Model\Entity\MetadataKey|\Passbolt\Metadata\Model\Entity\MetadataKey[] persist()
 * @method \Passbolt\Metadata\Model\Entity\MetadataKey getEntity()
 * @method \Passbolt\Metadata\Model\Entity\MetadataKey[] getEntities()
 */
class MetadataKeyFactory extends CakephpBaseFactory
{
    use ArmoredKeyFactoryTrait;

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
        $this->setDefaultData(function (Generator $faker) {
            return [
                'fingerprint' => $faker->shuffle('ABCDE12345ABCDE12345ABCDE12345ABCDE12345'), // 40 character random upper case
                'armored_key' => $faker->text(),
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
     * Set the armored key and fingerprint as found in config
     *
     * @return $this
     */
    public function validFingerprint()
    {
        return $this->patchData([
            // Alg: ecc, curve25519, user: shared@passbolt.test
            'armored_key' => file_get_contents(__DIR__ . DS . '..' . DS . 'Fixture' . DS . 'shared_public.key'),
            'fingerprint' => 'BF06A5F8615F6DEDC687AA72CCE0BADF53537AA7',
        ]);
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
        // TODO: replace this with GpgMetadataKeysTestTrait::getMakiKeyInfo
        return $this->patchData([
            'armored_key' => file_get_contents(__DIR__ . DS . '..' . DS . 'Fixture' . DS . 'maki_public.key'), // ecc, curve25519
            'fingerprint' => '3EED5E73EA34C95198A904067B28D501637D5102',
        ]);
    }

    /**
     * @return $this
     */
    public function withExpiredKey()
    {
        return $this->patchData([
            'armored_key' => file_get_contents(__DIR__ . DS . '..' . DS . 'Fixture' . DS . 'expired_public.key'),
            'fingerprint' => '7997026C7DE2B04044C98604A98D5FCDBFC94281',
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
     * Returns valid OpenPGP public key.
     *
     * @return false|string
     */
    public static function getValidPublicKey()
    {
        return file_get_contents(__DIR__ . DS . '..' . DS . 'Fixture' . DS . 'maki_public.key');
    }
}

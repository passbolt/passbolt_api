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
 * @since         3.0.0
 */
namespace App\Test\Factory;

use App\Test\Factory\Traits\ArmoredKeyFactoryTrait;
use Cake\Core\Configure;
use Cake\I18n\FrozenTime;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * GpgkeyFactory

 * @method \App\Model\Entity\Gpgkey|\App\Model\Entity\Gpgkey[] persist()
 * @method \App\Model\Entity\Gpgkey getEntity()
 * @method \App\Model\Entity\Gpgkey[] getEntities()
 */
class GpgkeyFactory extends CakephpBaseFactory
{
    use ArmoredKeyFactoryTrait;

    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Gpgkeys';
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
                'armored_key' => $faker->text(),
                'uid' => $faker->text(128),
                'key_id' => $faker->shuffle('0123456789ABCDEF'),
                'fingerprint' => $faker->shuffle('ABCDE12345ABCDE12345ABCDE12345ABCDE12345'), // 40 character random upper case
            ];
        });
    }

    /**
     * Set the armored key and fingerprint as found in config
     *
     * @return $this
     */
    public function validFingerprint()
    {
        return $this->patchData([
            'armored_key' => file_get_contents(Configure::read('passbolt.gpg.serverKey.private')),
            'fingerprint' => Configure::read('passbolt.gpg.serverKey.fingerprint'),
        ]);
    }

    /**
     * Set the expires field to the past
     *
     * @return $this
     */
    public function expired()
    {
        return $this->setField('expires', FrozenTime::yesterday());
    }

    /**
     * Set the armored key and fingerprint to Sofia's one
     *
     * @return $this
     */
    public function withValidOpenPGPKey()
    {
        return $this->patchData([
            'armored_key' => file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_public.key'),
            'fingerprint' => '03F60E958F4CB29723ACDF761353B5B15D9B054F',
        ]);
    }
}

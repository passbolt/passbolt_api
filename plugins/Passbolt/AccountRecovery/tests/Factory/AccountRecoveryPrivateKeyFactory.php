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
 * @since         3.6.0
 */
namespace Passbolt\AccountRecovery\Test\Factory;

use App\Test\Factory\UserFactory;
use Cake\Chronos\Chronos;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;
use Passbolt\AccountRecovery\Model\Table\AccountRecoveryPrivateKeysTable;

/**
 * AccountRecoveryPrivateKeyFactory
 *
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKey|\Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKey[] persist()
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKey getEntity()
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKey[] getEntities()
 */
class AccountRecoveryPrivateKeyFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return AccountRecoveryPrivateKeysTable::class;
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
            $file = FIXTURES . DS . 'OpenPGP' . DS . 'Messages' . DS . 'symetric_secret_password_sig_ada.msg';

            return [
                'id' => $faker->uuid(),
                'user_id' => $faker->uuid(),
                'data' => file_get_contents($file),
                'created_by' => $faker->uuid(),
                'modified_by' => $faker->uuid(),
                'created' => Chronos::now()->subDay($faker->randomNumber(4)),
                'modified' => Chronos::now()->subDay($faker->randomNumber(4)),
            ];
        });
    }

    /**
     * @param UserFactory|null $factory User Factory
     * @return AccountRecoveryPrivateKeyFactory
     */
    public function withUser(?UserFactory $factory = null)
    {
        return $this->with('Users', $factory);
    }

    /**
     * @param UserFactory|null $factory User Factory
     * @return AccountRecoveryPrivateKeyFactory
     */
    public function createdBy(?UserFactory $factory = null)
    {
        return $this->with('Creator', $factory);
    }

    /**
     * @param UserFactory|null $factory User Factory
     * @return AccountRecoveryPrivateKeyFactory
     */
    public function modifiedBy(?UserFactory $factory = null)
    {
        return $this->with('Modifier', $factory);
    }
}

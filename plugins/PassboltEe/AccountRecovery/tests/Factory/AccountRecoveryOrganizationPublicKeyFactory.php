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

use App\Test\Factory\Traits\ArmoredKeyFactoryTrait;
use App\Utility\UuidFactory;
use Cake\Chronos\Chronos;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * AccountRecoveryOrganizationPublicKeyFactory
 *
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey|\Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey[] persist()
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey getEntity()
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey[] getEntities()
 */
class AccountRecoveryOrganizationPublicKeyFactory extends CakephpBaseFactory
{
    use ArmoredKeyFactoryTrait;

    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Passbolt/AccountRecovery.AccountRecoveryOrganizationPublicKeys';
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
                'created_by' => UuidFactory::uuid(),
                'modified_by' => UuidFactory::uuid(),
                'created' => Chronos::now()->subDay($faker->randomNumber(4)),
                'modified' => Chronos::now()->subDay($faker->randomNumber(4)),
                'deleted' => null,
            ];
        });

        $this->rsa4096Key();
    }

    /**
     * @return $this
     */
    public function deleted()
    {
        return $this->setField('deleted', Chronos::now());
    }
}

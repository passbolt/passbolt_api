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
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         4.0.0
 */

namespace Passbolt\Log\Test\Factory;

use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use Cake\Chronos\Chronos;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * SecretAccessFactory
 *
 * @method \Passbolt\Log\Model\Entity\SecretAccess|\Passbolt\Log\Model\Entity\SecretAccess[] persist()
 * @method \Passbolt\Log\Model\Entity\SecretAccess getEntity()
 * @method \Passbolt\Log\Model\Entity\SecretAccess[] getEntities()
 * @method static \Passbolt\Log\Model\Entity\SecretAccess get($primaryKey, array $options = [])
 */
class SecretAccessFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Passbolt/Log.SecretAccesses';
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
                'user_id' => $faker->uuid(),
                'resource_id' => $faker->uuid(),
                'secret_id' => $faker->uuid(),
                'created' => Chronos::now()->subMinutes($faker->randomNumber(8)),
            ];
        });
    }

    /**
     * @param UserFactory $factory User Factory
     * @return SecretAccessFactory
     */
    public function withUsers(UserFactory $factory)
    {
        return $this->with('Users', $factory);
    }

    /**
     * @param ResourceFactory $factory Resource Factory
     * @return SecretAccessFactory
     */
    public function withResources(ResourceFactory $factory)
    {
        return $this->with('Resources', $factory);
    }
}

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
 * @since         5.7.0
 */

namespace Passbolt\Log\Test\Factory;

use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * SecretsHistoryFactory
 *
 * @method \Passbolt\Log\Model\Entity\SecretHistory|\Passbolt\Log\Model\Entity\SecretHistory[] persist()
 * @method \Passbolt\Log\Model\Entity\SecretHistory getEntity()
 * @method \Passbolt\Log\Model\Entity\SecretHistory[] getEntities()
 * @method static \Passbolt\Log\Model\Entity\SecretHistory get($primaryKey, array|string $options = [], \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 */
class SecretsHistoryFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Passbolt/Log.SecretsHistory';
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
                'id' => $faker->uuid(), // same as secret id
                'user_id' => $faker->uuid(),
                'resource_id' => $faker->uuid(),
            ];
        });
    }

    /**
     * @param UserFactory $factory User Factory
     * @return SecretsHistoryFactory
     */
    public function withUsers(UserFactory $factory): self
    {
        return $this->with('Users', $factory);
    }

    /**
     * @param ResourceFactory $factory Resource Factory
     * @return SecretsHistoryFactory
     */
    public function withResources(ResourceFactory $factory): self
    {
        return $this->with('Resources', $factory);
    }
}

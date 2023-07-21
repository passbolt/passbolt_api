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

use App\Model\Entity\Resource;
use App\Model\Entity\User;
use Cake\Chronos\Chronos;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * FavoriteFactory
 */
class FavoriteFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Favorites';
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
                'foreign_key' => $faker->uuid(),
                'foreign_model' => $faker->uuid(),
                'created' => Chronos::now()->subDay($faker->randomNumber(4)),
                'modified' => Chronos::now()->subDay($faker->randomNumber(4)),
            ];
        });
    }

    public function setUser(User $user): self
    {
        return $this->setField('user_id', $user->get('id'));
    }

    public function setResource(Resource $resource): self
    {
        return $this
            ->setField('foreign_key', $resource->get('id'))
            ->setField('foreign_model', 'Resource');
    }
}

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
 * @since         4.0.0
 */

namespace Passbolt\ResourceTypes\Test\Factory;

use Cake\I18n\FrozenDate;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;
use Passbolt\ResourceTypes\Model\Table\ResourceTypesTable;

/**
 * ResourceFactory
 *
 * @method \Passbolt\ResourceTypes\Model\Entity\ResourceType|\Passbolt\ResourceTypes\Model\Entity\ResourceType[] persist()
 * @method \Passbolt\ResourceTypes\Model\Entity\ResourceType getEntity()
 * @method \Passbolt\ResourceTypes\Model\Entity\ResourceType[] getEntities()
 * @method static \Passbolt\ResourceTypes\Model\Entity\ResourceType get($primaryKey, array $options = [])
 */
class ResourceTypeFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'ResourceTypes';
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
                'slug' => $faker->slug(3),
                'name' => $faker->words(3, true),
                'description' => $faker->text(64),
                'created' => FrozenDate::now()->subDays($faker->randomNumber(4)),
                'modified' => FrozenDate::now()->subDays($faker->randomNumber(4)),
            ];
        });
    }

    public function default(): self
    {
        return $this->patchData(['id' => ResourceTypesTable::getDefaultTypeId()]);
    }

    public function passwordAndDescription(): self
    {
        return $this->patchData(['id' => ResourceTypesTable::getPasswordAndDescriptionTypeId()]);
    }
}

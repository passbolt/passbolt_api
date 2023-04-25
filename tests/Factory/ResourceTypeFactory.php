<?php
declare(strict_types=1);

namespace App\Test\Factory;

use App\Model\Table\ResourceTypesTable;
use Cake\I18n\FrozenDate;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * ResourceFactory
 *
 * @method \App\Model\Entity\ResourceType|\App\Model\Entity\ResourceType[] persist()
 * @method \App\Model\Entity\ResourceType getEntity()
 * @method \App\Model\Entity\ResourceType[] getEntities()
 * @method static \App\Model\Entity\ResourceType get($primaryKey, array $options = [])
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
                'created' => FrozenDate::now()->subDay($faker->randomNumber(4)),
                'modified' => FrozenDate::now()->subDay($faker->randomNumber(4)),
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

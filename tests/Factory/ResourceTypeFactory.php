<?php
declare(strict_types=1);

namespace App\Test\Factory;

use App\Model\Table\ResourceTypesTable;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * ResourceFactory
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
                'name' => $faker->text(64),
                'slug' => $faker->text(64),
            ];
        });
    }

    public function default(): self
    {
        return $this->patchData(['id' => ResourceTypesTable::getDefaultTypeId()]);
    }
}

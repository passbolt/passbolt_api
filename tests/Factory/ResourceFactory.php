<?php
declare(strict_types=1);

namespace App\Test\Factory;

use Cake\Chronos\Chronos;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * ResourceFactory
 */
class ResourceFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Resources';
    }

    /**
     * Defines the factory's default values. This is useful for
     * not nullable fields. You may use methods of the present factory here too.
     *
     * @return void
     */
    protected function setDefaultTemplate()
    {
        $this->setDefaultData(function (Generator $faker) {
            return [
                'name' => $faker->text(64),
                'created_by' => $faker->uuid,
                'modified_by' => $faker->uuid,
                'created' => Chronos::now(),
            ];
        });

//        $this->with('Permission', ['aco' => PermissionsTable::RESOURCE_ACO]);
    }

    /**
     * @param UserFactory $factory
     * @return ResourceFactory
     */
    public function withCreator(UserFactory $factory)
    {
        return $this->with('Creator', $factory);
    }
}

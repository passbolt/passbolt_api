<?php
declare(strict_types=1);

namespace App\Test\Factory;

use App\Model\Table\PermissionsTable;
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
    protected function setDefaultTemplate(): void
    {
        $this->setDefaultData(function (Generator $faker) {
            return [
                'name' => $faker->text(64),
                'username' => $faker->email,
                'uri' => $faker->url,
                'created_by' => $faker->uuid,
                'modified_by' => $faker->uuid,
                'created' => Chronos::now()->subDay($faker->randomNumber(4)),
                'modified' => Chronos::now()->subDay($faker->randomNumber(4)),
            ];
        });
    }

    /**
     * @param UserFactory $factory
     * @return ResourceFactory
     */
    public function withCreator(UserFactory $factory)
    {
        return $this->with('Creator', $factory);
    }

    public function withCreatorAndPermission(string $created_by)
    {
        $aco = PermissionsTable::RESOURCE_ACO;
        $aro_foreign_key = $created_by;

        return $this
            ->patchData(compact('created_by'))
            ->with(
                'Permission',
                PermissionFactory::make(compact('aco', 'aro_foreign_key'))//->with('Users', $creator)
            );
    }
}

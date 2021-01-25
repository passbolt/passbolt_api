<?php
declare(strict_types=1);

namespace App\Test\Factory;

use App\Model\Entity\Role;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * RoleFactory
 */
class RoleFactory extends CakephpBaseFactory
{
    protected $uniqueProperties = [
        'name',
    ];

    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Roles';
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
                'name' => $faker->name,
            ];
        });
    }

    public function guest()
    {
        return $this->patchData(['name' => Role::GUEST]);
    }

    public function user()
    {
        return $this->patchData(['name' => Role::USER]);
    }

    public function admin()
    {
        return $this->patchData(['name' => Role::ADMIN]);
    }
}

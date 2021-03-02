<?php
declare(strict_types=1);

namespace App\Test\Factory;

use App\Model\Entity\Role;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * UserFactory
 */
class UserFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Users';
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
                'username' => $faker->userName . '@passbolt.com',
                'active' => true,
                'deleted' => false,
            ];
        });

        $this->with('Roles');
    }

    /**
     * @param string $name
     * @return $this
     */
    public function withRole(string $name)
    {
        $this->without('Roles');

        return $this->patchData([
            'role_id' => RoleFactory::make()->getRootTableRegistry()->findOrCreate(compact('name'))->id,
        ]);
    }

    /**
     * @return $this
     */
    public function admin()
    {
        return $this->withRole(Role::ADMIN);
    }

    /**
     * @return $this
     */
    public function inactive()
    {
        return $this->patchData(['active' => false]);
    }

    /**
     * @return $this
     */
    public function active()
    {
        return $this->patchData(['active' => true]);
    }
}

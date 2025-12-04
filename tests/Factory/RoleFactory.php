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

use App\Model\Entity\Role;
use App\Utility\UuidFactory;
use Cake\Chronos\Chronos;
use Cake\I18n\Date;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * RoleFactory
 *
 * @method static self make(mixed $makeParameter = [], int $times = 1)
 * @method \App\Model\Entity\Role|\App\Model\Entity\Role[] persist()
 * @method \App\Model\Entity\Role getEntity()
 * @method \App\Model\Entity\Role[] getEntities()
 * @method static \App\Model\Entity\Role get($primaryKey, array $options = [])
 * @method \App\Model\Entity\Role firstOrFail($conditions = null)
 */
class RoleFactory extends CakephpBaseFactory
{
    protected array $uniqueProperties = [
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
    protected function setDefaultTemplate(): void
    {
        $this->setDefaultData(function (Generator $faker) {
            $name = $faker->name();
            $id = UuidFactory::uuid5($name);

            return [
                'id' => $id,
                'name' => $name,
                'description' => null,
                'deleted' => null,
                'created_by' => null,
                'modified_by' => null,
                'deleted_by' => null,
                'created' => Chronos::now()->subDays($faker->randomNumber(4)),
                'modified' => Chronos::now()->subDays($faker->randomNumber(4)),
            ];
        });
    }

    public function guest()
    {
        $role = Role::GUEST;

        return $this->patchData(['id' => UuidFactory::uuid5($role), 'name' => $role]);
    }

    public function user()
    {
        $role = Role::USER;

        return $this->patchData(['id' => UuidFactory::uuid5($role), 'name' => $role]);
    }

    public function admin()
    {
        $role = Role::ADMIN;

        return $this->patchData(['id' => UuidFactory::uuid5($role), 'name' => $role]);
    }

    public function root()
    {
        return $this->patchData([
            'id' => UuidFactory::uuid5('root'),
            'name' => 'root',
            'description' => 'Super Administrator',
        ]);
    }

    public function deleted(?Date $deleted = null): self
    {
        if (is_null($deleted)) {
            $deleted = Date::yesterday();
        }

        return $this->setField('deleted', $deleted);
    }

    public function findOrCreate(): Role
    {
        $role = $this->getEntity();
        $duplicate = $this->getRootTableRegistry()
            ->findByName($role->name)
            ->first();

        if ($duplicate) {
            return $duplicate;
        } else {
            return $this->persist();
        }
    }
}

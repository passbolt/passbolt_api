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

use App\Model\Entity\User;
use App\Model\Table\PermissionsTable;
use Cake\Chronos\Chronos;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * ResourceFactory
 *
 * @method \App\Model\Entity\Resource getEntity()()
 * @method \App\Model\Entity\Resource[] getEntities()()
 * @method \App\Model\Entity\Resource persist()
 */
class ResourceFactory extends CakephpBaseFactory
{
    use FactoryDeletedTrait;

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
                'name' => $faker->text(255),
                'username' => $faker->email(),
                'uri' => $faker->url(),
                'created_by' => $faker->uuid(),
                'modified_by' => $faker->uuid(),
                'created' => Chronos::now()->subDay($faker->randomNumber(4)),
                'modified' => Chronos::now()->subDay($faker->randomNumber(4)),
            ];
        });
    }

    /**
     * Define the associated permissions to create for a given list of aros (users or groups).
     *
     * @param array $aros Array of users or groups to create the resource for
     * @param mixed $permissionsType (Optional) The permission type, default OWNER
     * @return ResourceFactory
     */
    public function withPermissionsFor(array $aros, $permissionsType = 15): ResourceFactory
    {
        foreach ($aros as $aro) {
            $aroType = $aro instanceof User ? PermissionsTable::USER_ARO : PermissionsTable::GROUP_ARO;
            $permissionsMeta = ['aco' => PermissionsTable::RESOURCE_ACO, 'aro' => $aroType, 'aro_foreign_key' => $aro->id, 'type' => $permissionsType];
            $this->with('Permissions', $permissionsMeta);
        }

        return $this;
    }

    /**
     * @param UserFactory $factory
     * @return ResourceFactory
     */
    public function withCreator(UserFactory $factory): self
    {
        return $this->with('Creator', $factory);
    }

    /**
     * Associates a previously persisted user with ACO permission.
     *
     * @param \App\Model\Entity\User $creator Persisted creator
     * @return $this
     */
    public function withCreatorAndPermission(User $creator)
    {
        $aco = PermissionsTable::RESOURCE_ACO;
        $aro_foreign_key = $creator->id;

        return $this
            ->patchData(['created_by' => $creator->id])
            ->with(
                'Permission',
                PermissionFactory::make(compact('aco', 'aro_foreign_key'))
            );
    }
}

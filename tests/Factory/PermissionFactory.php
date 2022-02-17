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

use App\Model\Entity\Group;
use App\Model\Entity\Permission;
use App\Model\Entity\Resource;
use App\Model\Entity\User;
use App\Model\Table\PermissionsTable;
use Cake\Chronos\Chronos;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;
use Passbolt\Folders\Model\Entity\Folder;

/**
 * PermissionFactory
 *
 * @method \App\Model\Entity\Permission|\App\Model\Entity\Permission[] persist()
 * @method \App\Model\Entity\Permission getEntity()
 * @method \App\Model\Entity\Permission[] getEntities()
 */
class PermissionFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Permissions';
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
                'aco' => $faker->text(30),
                'aro' => $faker->text(30),
                'type' => rand(),
                'aco_foreign_key' => $faker->uuid(),
                'aro_foreign_key' => $faker->uuid(),
                'created' => Chronos::now(),
                'modified' => Chronos::now(),
            ];
        });
    }

    /**
     * Define the associated user aro
     *
     * @param UserFactory|null $factory
     * @return PermissionFactory
     */
    public function withAroUser(?UserFactory $factory = null): self
    {
        $this->patchData(['aro' => PermissionsTable::USER_ARO]);

        return $this->with('Users', $factory);
    }

    /**
     * Define the associated group aro
     *
     * @param GroupFactory|null $factory
     * @return PermissionFactory
     */
    public function withAroGroup(?GroupFactory $factory = null): self
    {
        $this->patchData(['aro' => PermissionsTable::GROUP_ARO]);

        return $this->with('Groups', $factory);
    }

    /**
     * Define the associated resource aco
     *
     * @param ResourceFactory|null $factory
     * @return PermissionFactory
     */
    public function withAcoResource(?ResourceFactory $factory = null): self
    {
        $this->patchData(['aco' => PermissionsTable::RESOURCE_ACO]);

        return $this->with('Resources', $factory);
    }

    /**
     * Define the associated folder aco
     *
     * @param ResourceFactory|null $factory
     * @return PermissionFactory
     */
    public function withAcoFolder(?ResourceFactory $factory = null): self
    {
        $this->patchData(['aco' => PermissionsTable::FOLDER_ACO]);

        return $this->with('Folders', $factory);
    }

    /**
     * Define the permission type as read
     *
     * @return PermissionFactory
     */
    public function typeRead(): self
    {
        return $this->patchData(['type' => Permission::READ]);
    }

    /**
     * Define the permission type as update
     *
     * @return PermissionFactory
     */
    public function typeUpdate(): self
    {
        return $this->patchData(['type' => Permission::UPDATE]);
    }

    /**
     * Define the permission type as owner
     *
     * @return PermissionFactory
     */
    public function typeOwner(): self
    {
        return $this->patchData(['type' => Permission::OWNER]);
    }

    /**
     * Define the aro as user
     *
     * @param User|null $user (optional) User to use as aro_foregin_key
     * @return PermissionFactory
     */
    public function aroUser(?User $user = null): self
    {
        $this->patchData(['aro' => PermissionsTable::USER_ARO]);

        if (!is_null($user)) {
            $this->patchData(['aro_foreign_key' => $user->id]);
        }

        return $this;
    }

    /**
     * Define the aro as group
     *
     * @param Group|null $group (optional) Group to use as aro_foregin_key
     * @return PermissionFactory
     */
    public function aroGroup(?Group $group = null): self
    {
        $this->patchData(['aro' => PermissionsTable::GROUP_ARO]);

        if (!is_null($group)) {
            $this->patchData(['aro_foreign_key' => $group->id]);
        }

        return $this;
    }

    /**
     * Define the aro as group
     *
     * @param Resource|null $resource (optional) Resource to use as aco_foregin_key
     * @return PermissionFactory
     */
    public function acoResource(?Resource $resource = null): self
    {
        $this->patchData(['aco' => PermissionsTable::RESOURCE_ACO]);

        if (!is_null($resource)) {
            $this->patchData(['aco_foreign_key' => $resource->id]);
        }

        return $this;
    }

    /**
     * Define the aro as group
     *
     * @param Folder|null $folder (optional) Folder to use as aco_foregin_key
     * @return PermissionFactory
     */
    public function acoFolder(?Folder $folder = null): self
    {
        $this->patchData(['aco' => PermissionsTable::FOLDER_ACO]);

        if (!is_null($folder)) {
            $this->patchData(['aco_foreign_key' => $folder->id]);
        }

        return $this;
    }
}

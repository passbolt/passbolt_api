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
use App\Model\Entity\User;
use App\Model\Table\PermissionsTable;
use App\Test\Factory\Traits\FactoryDeletedTrait;
use App\Utility\UuidFactory;
use Cake\Chronos\Chronos;
use Cake\I18n\DateTime;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;
use Passbolt\ResourceTypes\Model\Entity\ResourceType;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;
use Passbolt\SecretRevisions\Test\Factory\SecretRevisionFactory;

/**
 * ResourceFactory
 *
 * @method \App\Model\Entity\Resource getEntity()
 * @method \App\Model\Entity\Resource[] getEntities()
 * @method \App\Model\Entity\Resource|\App\Model\Entity\Resource[] persist()
 * @method static \App\Model\Entity\Resource firstOrFail($conditions = null)()
 * @method static \App\Model\Entity\Resource get($primaryKey, array $options = [])
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
                'description' => $faker->text(10),
                'created_by' => $faker->uuid(),
                'modified_by' => $faker->uuid(),
                'resource_type_id' => UuidFactory::uuid5('resource-types.id.' . ResourceType::SLUG_PASSWORD_AND_DESCRIPTION),
                'created' => Chronos::now()->subDays($faker->randomNumber(4)),
                'modified' => Chronos::now()->subDays($faker->randomNumber(4)),
            ];
        });

        $this->with('ResourceTypes', ResourceTypeFactory::make()->passwordAndDescription());
    }

    /**
     * Define the associated permissions to create for a given list of aros (users or groups).
     *
     * @param array $aros Array of users or groups to create a permission for
     * @param mixed $permissionsType (Optional) The permission type, default OWNER
     * @return ResourceFactory
     */
    public function withPermissionsFor(array $aros, $permissionsType = Permission::OWNER): ResourceFactory
    {
        foreach ($aros as $aro) {
            $aroType = $aro instanceof User ? PermissionsTable::USER_ARO : PermissionsTable::GROUP_ARO;
            $permissionsMeta = ['aco' => PermissionsTable::RESOURCE_ACO, 'aro' => $aroType, 'aro_foreign_key' => $aro->id, 'type' => $permissionsType];
            $this->with('Permissions', $permissionsMeta);
        }

        return $this;
    }

    /**
     * Define the secrets for the given users
     *
     * @param array $users Array of users to create a secret for
     * @return ResourceFactory
     */
    public function withSecretsFor(array $users): ResourceFactory
    {
        foreach ($users as $user) {
            if ($user instanceof User) {
                $secretData = [
                    'user_id' => $user->id,
                    'created_by' => $user->id,
                    'modified_by' => $user->id,
                ];
                $this->with('Secrets', $secretData);
            } elseif ($user instanceof Group) {
                foreach ($user->groups_users as $groupUser) {
                    $secretData = ['user_id' => $groupUser->user_id];
                    $this->with('Secrets', $secretData);
                }
            }
        }

        return $this;
    }

    /**
     * @param UserFactory $factory
     * @return ResourceFactory
     */
    public function setDeleted(): self
    {
        return $this->setField('deleted', true);
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
        $aro = PermissionsTable::USER_ARO;

        return $this
            ->patchData(['created_by' => $creator->id])
            ->with(
                'Permission',
                PermissionFactory::make(compact('aco', 'aro', 'aro_foreign_key'))
            );
    }

    /**
     * @return $this
     */
    public function expired(?DateTime $expired = null)
    {
        return $this->setField('expired', $expired ?? DateTime::now()->subMinutes(1));
    }

    /**
     * @param bool $isShared Is metadata type shared or not.
     * @return $this
     */
    public function v5Fields(bool $isShared = false, array $v5Fields = [])
    {
        $type = $isShared ? 'shared_key' : 'user_key';
        if (isset($v5Fields['metadata_key_id'])) {
            $this->setField('metadata_key_id', $v5Fields['metadata_key_id']);
            unset($v5Fields['metadata_key_id']);
        } else {
            $this->with('MetadataKeys');
        }

        $data = array_merge([
            // Set V5 fields (not null and valid)
            'metadata' => $v5Fields['metadata'] ?? 'foo-bar', // todo set proper encrypted resource metadata
            'metadata_key_type' => $type,
            // Set V4 fields to null
            'name' => null,
            'username' => null,
            'uri' => null,
            'description' => null,
        ], $v5Fields);

        return $this->patchData($data)->with('ResourceTypes', ResourceTypeFactory::make()->v5Default());
    }

    public function withSecretRevisions(?SecretRevisionFactory $factory = null): self
    {
        if (is_null($factory)) {
            $factory = SecretRevisionFactory::make();
        }

        return $this->with('SecretRevisions', $factory);
    }
}

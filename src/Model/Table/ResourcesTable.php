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
 * @since         2.0.0
 */

namespace App\Model\Table;

use App\Model\Entity\Permission;
use App\Model\Entity\Resource;
use App\Model\Entity\Role;
use App\Model\Rule\IsNotSoftDeletedRule;
use App\Model\Traits\Resources\ResourcesFindersTrait;
use App\Model\Validation\DateTime\IsParsableDateTimeValidationRule;
use App\Utility\Application\FeaturePluginAwareTrait;
use App\Utility\UuidFactory;
use Cake\Event\Event;
use Cake\I18n\FrozenTime;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validation;
use Cake\Validation\Validator;
use Passbolt\ResourceTypes\Model\Table\ResourceTypesTable;

/**
 * Resources Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\HasOne $Creator
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\HasOne $Modifier
 * @property \App\Model\Table\SecretsTable&\Cake\ORM\Association\HasMany $Secrets
 * @property \App\Model\Table\PermissionsTable&\Cake\ORM\Association\HasMany $Permissions
 * @method \App\Model\Entity\Resource get($primaryKey, $options = [])
 * @method \App\Model\Entity\Resource[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Resource|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Resource patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Resource[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Resource findOrCreate($search, ?callable $callback = null, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @property \App\Model\Table\FavoritesTable&\Cake\ORM\Association\HasOne $Favorites
 * @property \App\Model\Table\PermissionsTable&\Cake\ORM\Association\HasOne $Permission
 * @property \Passbolt\ResourceTypes\Model\Table\ResourceTypesTable&\Cake\ORM\Association\BelongsTo $ResourceTypes
 * @property \Passbolt\Log\Model\Table\EntitiesHistoryTable&\Cake\ORM\Association\BelongsTo $EntitiesHistory
 * @method \App\Model\Entity\Resource newEmptyEntity()
 * @method \App\Model\Entity\Resource newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Resource saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method iterable<\App\Model\Entity\Resource>|iterable<\Cake\Datasource\EntityInterface>|false saveMany(iterable $entities, $options = [])
 * @method iterable<\App\Model\Entity\Resource>|iterable<\Cake\Datasource\EntityInterface> saveManyOrFail(iterable $entities, $options = [])
 * @method iterable<\App\Model\Entity\Resource>|iterable<\Cake\Datasource\EntityInterface>|false deleteMany(iterable $entities, $options = [])
 * @method iterable<\App\Model\Entity\Resource>|iterable<\Cake\Datasource\EntityInterface> deleteManyOrFail(iterable $entities, $options = [])
 * @method \Cake\ORM\Query findByIdAndDeleted(string $id, bool $delete)
 */
class ResourcesTable extends Table
{
    use ResourcesFindersTrait;
    use FeaturePluginAwareTrait;

    public const DESCRIPTION_MAX_LENGTH = 10000;
    public const NAME_MAX_LENGTH = 255;
    public const URI_MAX_LENGTH = 1024;
    public const USERNAME_MAX_LENGTH = 255;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('resources');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasOne('Creator', [
            'className' => 'Users',
            'bindingKey' => 'created_by',
            'foreignKey' => 'id',
        ]);
        $this->hasOne('Favorites', [
            'foreignKey' => 'foreign_key',
        ]);
        $this->hasOne('Modifier', [
            'className' => 'Users',
            'bindingKey' => 'modified_by',
            'foreignKey' => 'id',
        ]);
        $this->hasOne('Permission', [
            'className' => 'Permissions',
            'foreignKey' => 'aco_foreign_key',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('Permissions', [
            'foreignKey' => 'aco_foreign_key',
            'saveStrategy' => 'replace',
            // Important so that we can track the delete event and log it.
            'cascadeCallbacks' => true,
        ]);
        $this->hasMany('Secrets', [
            'foreignKey' => 'resource_id',
            'saveStrategy' => 'replace',
        ]);

        $this->belongsTo('ResourceTypes');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->uuid('id', __('The identifier should be a valid UUID.'))
            ->allowEmptyString('id', __('The identifier should not be empty.'), 'create');

        $validator
            ->utf8Extended('name', __('The name should be a valid UTF8 string.'))
            ->maxLength(
                'name',
                self::NAME_MAX_LENGTH,
                __('The name length should be maximum {0} characters.', self::NAME_MAX_LENGTH)
            )
            ->requirePresence('name', 'create', __('A name is required.'))
            ->allowEmptyString('name', __('The name should not be empty.'), false);

        $validator
            ->utf8Extended('username', __('The username should be a valid UTF8 string.'))
            ->maxLength(
                'username',
                self::USERNAME_MAX_LENGTH,
                __('The username length should be maximum {0} characters.', self::USERNAME_MAX_LENGTH)
            )
            ->allowEmptyString('username');

        $validator
            ->utf8('uri', __('The uri should be a valid BMP-UTF8 string.'))
            ->maxLength(
                'uri',
                self::URI_MAX_LENGTH,
                __('The uri length should be maximum {0} characters.', self::URI_MAX_LENGTH)
            )
            ->allowEmptyString('uri');

        $validator
            ->utf8Extended('description', __('The description should be a valid UTF8 string.'))
            ->maxLength(
                'description',
                self::DESCRIPTION_MAX_LENGTH,
                __('The description length should be maximum {0} characters.', self::DESCRIPTION_MAX_LENGTH)
            )
            ->allowEmptyString('description');

        $validator
            ->boolean('deleted', __('The deleted status should be a valid boolean.'))
            ->allowEmptyString('deleted', __('The deleted status should not be empty'), false);

        $validator
        ->allowEmptyDateTime('expired')
        ->add('expired', 'expired', new IsParsableDateTimeValidationRule());

        $validator
            ->uuid('created_by', __('The identifier of the user who created the resource should be a valid UUID.'))
            ->requirePresence(
                'created_by',
                'create',
                __('The identifier of the user who created the resource is required.')
            )
            ->allowEmptyString(
                'created_by',
                __('The identifier of the user who created the resource should not be empty.'),
                false
            );

        $validator
            ->uuid(
                'modified_by',
                __('The identifier of the user who last modified the resource should be a valid UUID.')
            )
            ->requirePresence(
                'modified_by',
                'create',
                __('The identifier of the user who last modified the resource required.')
            )
            ->allowEmptyString(
                'modified_by',
                __('The identifier of the user who last modified the resource should not be empty.'),
                false
            );

        $validator
            ->uuid('resource_type_id', __('The resource type identifier should be a valid UUID.'))
            ->requirePresence('resource_type_id', 'create', __('A resource type identifier is required.'));

        // Associated fields
        $validator
            ->requirePresence('permissions', 'create', __('The permissions are required.'))
            // @todo Secrets is an array
            ->allowEmptyString('permissions', __('The permissions should not be empty.'), false)
            ->hasAtMost(
                'permissions',
                1,
                __('The permissions should contain only the permission of the owner.'),
                'create'
            );

        $validator
            ->requirePresence('secrets', 'create', __('The owner secret is required.'))
            // @todo Secrets is an array
            ->allowEmptyString('secrets', __('The secrets should not be empty.'), false)
            ->hasAtMost('secrets', 1, __('The secrets should contain only the secret of the owner.'), 'create');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        // Create and Update rules
        $rules->add($rules->existsIn('resource_type_id', 'ResourceTypes'), 'resource_type_exists', [
            'message' => __('The resource type does not exist.'),
        ]);

        // Create rules.
        $rules->addCreate([$this, 'isOwnerPermissionProvidedRule'], 'owner_permission_provided', [
            'errorField' => 'permissions',
            'message' => __('The permissions should contain the owner permission.'),
        ]);
        $rules->addCreate([$this, 'isOwnerSecretProvidedRule'], 'owner_secret_provided', [
            'errorField' => 'secrets',
            'message' => __('The secrets should contain the owner secret.'),
        ]);

        // Update rules.
        $rules->addUpdate([$this, 'isSecretsProvidedRule'], 'secrets_provided', [
            'errorField' => 'secrets',
            'message' => __('The secrets should contain the secrets of all the users having access to the resource.'),
        ]);
        $rules->addUpdate(new IsNotSoftDeletedRule(), 'resource_is_not_soft_deleted', [
            'table' => 'Resources',
            'errorField' => 'id',
            'message' => __('The resource does not exist.'),
        ]);
        $rules->addUpdate([$this, 'isOwnerPermissionProvidedRule'], 'at_least_one_owner', [
            'errorField' => 'permissions',
            'message' => __('The permissions should contain at least the owner permission.'),
        ]);

        return $rules;
    }

    /**
     * @param \Cake\Event\Event $event event
     * @param \ArrayObject $data data
     * @param \ArrayObject $options options
     * @return void
     * @deprecated can be removed once isDescriptionEmptyOnPasswordAndDescriptionResourceType is a rule
     */
    public function beforeMarshal(Event $event, \ArrayObject $data, \ArrayObject $options): void
    {
        if (!$this->isDescriptionEmptyOnPasswordAndDescriptionResourceType($data)) {
            $data['description'] = null;
        }
        if (isset($data['expired']) && !empty($data['expired'])) {
            // Parse the expired date into a time object
            try {
                $data['expired'] = FrozenTime::parse($data['expired']);
            } catch (\Throwable $e) {
                // If the expired date cannot be parsed, let the validation
                // handle the fail
            }
        }
    }

    /**
     * Validate that the entity has at least one owner
     *
     * @param \App\Model\Entity\Resource $entity The entity that will be created or updated.
     * @param array|null $options options
     * @return bool
     */
    public function isOwnerPermissionProvidedRule(Resource $entity, ?array $options = [])
    {
        if (isset($entity->permissions)) {
            $found = Hash::extract($entity->permissions, '{n}[type=' . Permission::OWNER . ']');
            if (empty($found)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Validate that the entity description is empty if the resource type is password and description
     * This can be refactored as a rule after 4.0.0
     *
     * @param \ArrayObject $data data
     * @return bool
     * @todo refactor this as a rule after 4.0.0
     */
    public function isDescriptionEmptyOnPasswordAndDescriptionResourceType(\ArrayObject $data): bool
    {
        $resourceTypeId = $data['resource_type_id'] ?? null;
        $description = $data['description'] ?? null;
        $resourceTypePasswordAndDescriptionId = UuidFactory::uuid('resource-types.id.password-and-description');
        $isResourceTypePasswordAndDescription = $resourceTypeId === $resourceTypePasswordAndDescriptionId;

        if ($isResourceTypePasswordAndDescription && !empty($description)) {
            return false;
        }

        return true;
    }

    /**
     * Validate that a resource can be created only if the secret of the owner is provided.
     *
     * @param \App\Model\Entity\Resource $entity The entity that will be created.
     * @param array|null $options options
     * @return bool
     */
    public function isOwnerSecretProvidedRule(Resource $entity, ?array $options = [])
    {
        return $entity->secrets[0]->user_id === $entity->created_by;
    }

    /**
     * Validate that the secrets of all the allowed users are provided if the secret changed.
     *
     * @param \App\Model\Entity\Resource $entity The entity that will be created.
     * @param array|null $options options
     * @return bool
     */
    public function isSecretsProvidedRule(Resource $entity, ?array $options = [])
    {
        // Secrets are not required to update a resource, but if provided check that the list of secrets correspond
        // only to the users who have access to the resource.
        if (!isset($entity->secrets)) {
            return true;
        }

        // Retrieve the users who are allowed to access the resource.
        /** @var \App\Model\Table\UsersTable $Users */
        $Users = TableRegistry::getTableLocator()->get('Users');
        $usersFindOptions['filter']['has-access'] = [$entity->id];
        $allowedUsersIds = $Users->findIndex(Role::USER, $usersFindOptions)
            ->all()
            ->extract('id')
            ->toArray();

        // Extract the users for whom the secrets will be updated.
        $secretsUsersIds = Hash::extract($entity->secrets, '{n}.user_id');

        // If the list of secrets does not correspond to the list of users who have access to the resource,
        // do not validate.
        if (
            count($secretsUsersIds) != count($allowedUsersIds)
            || !empty(array_diff($allowedUsersIds, $secretsUsersIds))
        ) {
            return false;
        }

        return true;
    }

    /**
     * Soft delete a resource.
     *
     * @param string $userId The user who perform the delete.
     * @param \App\Model\Entity\Resource $resource The resource to delete.
     * @throws \InvalidArgumentException if the user id is not a uuid
     * @return bool true if success
     */
    public function softDelete(string $userId, Resource $resource): bool
    {
        // The softDelete will perform an update to the entity to soft delete it.
        if (!Validation::uuid($userId)) {
            throw new \InvalidArgumentException('The user identifier should be a valid UUID.');
        }
        if ($resource->deleted) {
            $resource->setError('deleted', [
                'is_not_soft_deleted' => __('The resource should not be already soft deleted.'),
            ]);

            return false;
        }

        $acoType = PermissionsTable::RESOURCE_ACO;
        if (!$this->Permissions->hasAccess($acoType, $resource->id, $userId, Permission::UPDATE)) {
            $resource->setError('id', [
                'has_access' => __('The user cannot delete this resource.'),
            ]);

            return false;
        }

        // Patch the entity.
        $data = [
            'deleted' => true,
            'modified_by' => $userId,
            'secrets' => [],
            // cleanup sensitive data
            'username' => null,
            'uri' => null,
            'description' => null,
        ];
        $patchOptions = [
            'accessibleFields' => [
                'username' => true,
                'uri' => true,
                'description' => true,
                'deleted' => true,
                'secrets' => true,
                'modified' => true,
                'modified_by' => true,
            ],
        ];
        $this->patchEntity($resource, $data, $patchOptions);
        if ($resource->getErrors()) {
            return false;
        }

        // Soft delete the resource.
        $this->save($resource, ['checkRules' => false]);
        if ($resource->getErrors()) {
            return false;
        }

        // Remove all the associated permissions.
        $this->getAssociation('Permissions')
            ->deleteAll(['Permissions.aco_foreign_key' => $resource->id]);

        // Remove all the associated favorites.
        $this->getAssociation('Favorites')
            ->deleteAll(['Favorites.foreign_key' => $resource->id]);

        // Notify other components about the resource soft delete.
        $event = new Event('Model.Resource.afterSoftDelete', $resource);
        $this->getEventManager()->dispatch($event);

        return true;
    }

    /**
     * Remove the resource associated data for the users who lost access to the resource.
     *
     * @param string $resourceId The resource identifier the users lost the access to
     * @param array $usersId The list of users who lost access to the resource
     * @return void
     */
    public function deleteLostAccessAssociatedData(string $resourceId, array $usersId = []): void
    {
        if (empty($usersId)) {
            return;
        }

        $Favorites = TableRegistry::getTableLocator()->get('Favorites');
        $Favorites->deleteAll([
            'foreign_key' => $resourceId,
            'user_id IN' => $usersId,
        ]);
    }

    /**
     * Soft delete a list of resources by Ids
     *
     * @param array $resourceIds uuid of Resources
     * @param bool $cascade true
     * @return void
     */
    public function softDeleteAll(array $resourceIds, bool $cascade = true): void
    {
        // CakePHP will return an error on the coming query if $resourceIds is empty
        if (empty($resourceIds)) {
            return;
        }

        $this->updateAll([
            'deleted' => true,
            'username' => null,
            'uri' => null,
            'description' => null,
        ], ['id IN' => $resourceIds]);

        if ($cascade) {
            $Favorites = TableRegistry::getTableLocator()->get('Favorites');
            $Favorites->deleteAll(['foreign_key IN' => $resourceIds]);

            $Secrets = TableRegistry::getTableLocator()->get('Secrets');
            $Secrets->deleteAll(['resource_id IN' => $resourceIds]);

            $Permissions = TableRegistry::getTableLocator()->get('Permissions');
            $Permissions->deleteAll(['aco_foreign_key IN' => $resourceIds]);
        }
    }

    /**
     * Cleanup resource where resource type id is null
     * Set it to the default
     *
     * @param bool $dryRun false
     * @return int number of affected rows.
     */
    public function cleanupMissingResourceTypeId(bool $dryRun = false): int
    {
        $condition = ['resource_type_id IS' => null];
        if ($dryRun) {
            return $this->find()
                ->where($condition)
                ->count();
        }

        return $this->updateAll(['resource_type_id' => ResourceTypesTable::getDefaultTypeId()], $condition);
    }
}

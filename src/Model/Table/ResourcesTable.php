<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */

namespace App\Model\Table;

use App\Error\Exception\ValidationRuleException;
use App\Model\Entity\Permission;
use App\Model\Entity\Role;
use App\Model\Rule\IsNotSoftDeletedRule;
use Cake\Collection\CollectionInterface;
use Cake\Core\Configure;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validation;
use Cake\Validation\Validator;

/**
 * Resources Model
 *
 * @property \App\Model\Table\SecretsTable|\Cake\ORM\Association\HasOne $Creator
 * @property \App\Model\Table\SecretsTable|\Cake\ORM\Association\HasOne $Modifier
 * @property \App\Model\Table\SecretsTable|\Cake\ORM\Association\HasMany $Secrets
 * @property \App\Model\Table\SecretsTable|\Cake\ORM\Association\HasOne $Permissions
 *
 * @method \App\Model\Entity\Resource get($primaryKey, $options = [])
 * @method \App\Model\Entity\Resource newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Resource[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Resource|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Resource patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Resource[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Resource findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ResourcesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('resources');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasOne('Creator', [
            'className' => 'Users',
            'bindingKey' => 'created_by',
            'foreignKey' => 'id'
        ]);
        $this->hasOne('Favorites', [
            'foreignKey' => 'foreign_key'
        ]);
        $this->hasOne('Modifier', [
            'className' => 'Users',
            'bindingKey' => 'modified_by',
            'foreignKey' => 'id'
        ]);
        $this->hasOne('Permission', [
            'className' => 'Permissions',
            'foreignKey' => 'aco_foreign_key'
        ]);
        $this->hasMany('Permissions', [
            'foreignKey' => 'aco_foreign_key',
            'saveStrategy' => 'replace'
        ]);
        $this->hasMany('Secrets', [
            'foreignKey' => 'resource_id',
            'saveStrategy' => 'replace'
        ]);

        if (Configure::read('passbolt.plugins.tags')) {
            $this->belongsToMany('Tags', [
                'through' => 'Passbolt/Tags.ResourcesTags',
            ]);
        }
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->uuid('id')
            ->allowEmpty('id', 'create');

        $validator
            ->utf8Extended('name', __('The name is not a valid utf8 string.'))
            ->maxLength('name', 64, __('The name length should be maximum {0} characters.', 64))
            ->requirePresence('name', 'create', __('A name is required.'))
            ->notEmpty('name', __('The name cannot be empty.'));

        $validator
            ->utf8Extended('username', __('The username is not a valid utf8 string.'))
            ->maxLength('username', 64, __('The username length should be maximum {0} characters.', 64))
            ->allowEmpty('username');

        $validator
            ->utf8('uri', __('The uri is not a valid utf8 string (emoticons excluded).'))
            ->maxLength('uri', 1024, __('The uri length should be maximum {0} characters.', 1024))
            ->allowEmpty('uri');

        $validator
            ->utf8Extended('description', __('The description is not a valid utf8 string.'))
            ->maxLength('description', 10000, __('The description length should be maximum {0} characters.', 10000))
            ->allowEmpty('description');

        $validator
            ->boolean('deleted')
            ->notEmpty('deleted');

        $validator
            ->uuid('created_by')
            ->requirePresence('created_by', 'create')
            ->notEmpty('created_by');

        $validator
            ->uuid('modified_by')
            ->requirePresence('modified_by', 'create')
            ->notEmpty('modified_by');

        // Associated fields
        $validator
            ->requirePresence('permissions', 'create', __('The permissions are required.'))
            ->notEmpty('permissions', __('The permissions cannot be empty.'))
            ->count(1, __('Only the permission of the owner is required.'));

        $validator
            ->requirePresence('secrets', 'create', __('A secret is required.'))
            ->notEmpty('secrets', __('The secret cannot be empty.'), 'create')
            ->count(1, __('Only the secret of the owner is required.'));

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        // Create rules.
        $rules->addCreate([$this, 'isOwnerPermissionProvidedRule'], 'owner_permission_provided', [
            'errorField' => 'permissions',
            'message' => __('At least one owner permission must be provided.')
        ]);
        $rules->addCreate([$this, 'isOwnerSecretProvidedRule'], 'owner_secret_provided', [
            'errorField' => 'secrets',
            'message' => __('The secret of the owner is required.')
        ]);

        // Update rules.
        $rules->addUpdate([$this, 'isSecretsProvidedRule'], 'secrets_provided', [
            'errorField' => 'secrets',
            'message' => __('The secrets of all the users having access to the resource are required.')
        ]);
        $rules->addUpdate(new IsNotSoftDeletedRule(), 'resource_is_not_soft_deleted', [
            'table' => 'Resources',
            'errorField' => 'id',
            'message' => __('The resource cannot be soft deleted.')
        ]);
        $rules->addUpdate([$this, 'isOwnerPermissionProvidedRule'], 'at_least_one_owner', [
            'errorField' => 'permissions',
            'message' => __('At least one owner permission must be provided.')
        ]);

        return $rules;
    }

    /**
     * Validate that the entity has at least one owner
     *
     * @param \App\Model\Entity\Resource $entity The entity that will be created or updated.
     * @param array $options options
     * @return bool
     */
    public function isOwnerPermissionProvidedRule($entity, array $options = [])
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
     * Validate that the a resource can be created only if the secret of the owner is provided.
     *
     * @param \App\Model\Entity\Resource $entity The entity that will be created.
     * @param array $options options
     * @return bool
     */
    public function isOwnerSecretProvidedRule(\App\Model\Entity\Resource $entity, array $options = [])
    {
        return ($entity->secrets[0]->user_id === $entity->created_by);
    }

    /**
     * Validate that the secrets of all the allowed users are provided if the secret changed.
     *
     * @param \App\Model\Entity\Resource $entity The entity that will be created.
     * @param array $options options
     * @return bool
     */
    public function isSecretsProvidedRule(\App\Model\Entity\Resource $entity, array $options = [])
    {
        // Secrets are not required to update a resource, but if provided check that the list of secrets correspond
        // only to the users who have access to the resource.
        if (!isset($entity->secrets)) {
            return true;
        }

        // Retrieve the users who are allowed to access the resource.
        $Users = TableRegistry::get('Users');
        $usersFindOptions['filter']['has-access'] = [$entity->id];
        $allowedUsersIds = $Users->findIndex(Role::USER, $usersFindOptions)
            ->extract('id')
            ->toArray();

        // Extract the users for whom the secrets will be updated.
        $secretsUsersIds = Hash::extract($entity->secrets, '{n}.user_id');

        // If the list of secrets does not correspond to the list of users who have access to the resource,
        // do not validate.
        if (count($secretsUsersIds) != count($allowedUsersIds)
            || !empty(array_diff($allowedUsersIds, $secretsUsersIds))) {
            return false;
        }

        return true;
    }

    /**
     * Build the query that fetches data for resource index
     *
     * @param string $userId The user to get the resources for
     * @param array $options options
     * @throws \InvalidArgumentException if the userId parameter is not a valid uuid.
     * @return \Cake\ORM\Query
     */
    public function findIndex(string $userId, array $options = [])
    {
        if (!Validation::uuid($userId)) {
            throw new \InvalidArgumentException(__('The user id should be a valid uuid.'));
        }

        $query = $this->find();

        // If contains Secrets.
        if (isset($options['contain']['secret'])) {
            $query->contain('Secrets', function ($q) use ($userId) {
                return $q->where(['Secrets.user_id' => $userId]);
            });
        }

        // If contains creator.
        if (isset($options['contain']['creator'])) {
            $query->contain('Creator');
        }

        // If contains modifier.
        if (isset($options['contain']['modifier'])) {
            $query->contain('Modifier');
        }

        // If filtered by favorite.
        if (isset($options['filter']['is-favorite'])) {
            // Filter on the favorite resources.
            if ($options['filter']['is-favorite']) {
                $query->innerJoinWith('Favorites', function ($q) use ($userId) {
                    return $q->where(['Favorites.user_id' => $userId]);
                });
            } else {
                // Filter out the favorite resources.
                $query->notMatching('Favorites', function ($q) use ($userId) {
                    return $q->where(['Favorites.user_id' => $userId]);
                });
            }
        }

        // If shared with group.
        if (isset($options['filter']['is-owned-by-me'])) {
            $query = $this->_filterQueryIsOwnedByUser($query, $userId);
        }

        // If shared with group.
        if (isset($options['filter']['is-shared-with-me'])) {
            $query = $this->_filterQuerySharedWithUser($query, $userId);
        }

        // If shared with group.
        if (isset($options['filter']['is-shared-with-group'])) {
            $query = $this->_filterQuerySharedWithGroup($query, $options['filter']['is-shared-with-group']);
        }

        // If contains favorite.
        if (isset($options['contain']['favorite'])) {
            $query->contain('Favorites', function ($q) use ($userId) {
                return $q->where(['Favorites.user_id' => $userId]);
            });
        }

        // If plugin tag is present and request contains tags
        if (Configure::read('passbolt.plugins.tags')) {
            $query = \Passbolt\Tags\Model\Table\TagsTable::decorateForeignFind($query, $options, $userId);
        }

        /*
         * If the contain permission option is given, filter on resources the user is allowed to access
         * and retrieve the user permission. Otherwise only filter on resources the user is allowed to
         * access.
         */
        if (isset($options['contain']['permission'])) {
            // Matching will make available the user permission in the result _matchingData property.
            $query->matching('Permission');
            $query = $this->_filterQueryByPermissionsType($query, $userId, Permission::READ);
            // Format the query result to populate the permission property as a contain would do.
            $query->formatResults(function (CollectionInterface $results) {
                return $results->map(function ($row) {
                    $row['permission'] = $row['_matchingData']['Permission'];
                    unset($row['_matchingData']['Permission']);

                    return $row;
                });
            });
        } else {
            $query->innerJoinWith('Permission');
            $query = $this->_filterQueryByPermissionsType($query, $userId, Permission::READ);
        }

        // Manage order clauses.
        if (isset($options['order']['Resources.modified'])) {
            $query->order('Resources.modified DESC');
        } else {
            $query->orderAsc('Resources.name');
        }

        // Filter out deleted resources
        $query->where(['Resources.deleted' => false]);

        return $query;
    }

    /**
     * Build the query that fetches data for resource view
     *
     * @param string $userId The user to get the resources for
     * @param string $resourceId The resource to retrieve
     * @param array $options options
     * @throws \InvalidArgumentException if the userId parameter is not a valid uuid.
     * @throws \InvalidArgumentException if the resourceId parameter is not a valid uuid.
     * @return \Cake\ORM\Query
     */
    public function findView(string $userId, string $resourceId, array $options = [])
    {
        if (!Validation::uuid($userId)) {
            throw new \InvalidArgumentException(__('The parameter userId should be a valid uuid.'));
        }
        if (!Validation::uuid($resourceId)) {
            throw new \InvalidArgumentException(__('The parameter resourceId should be a valid uuid.'));
        }

        $query = $this->findIndex($userId, $options);
        $query->where(['Resources.id' => $resourceId]);

        return $query;
    }

    /**
     * Build the query that fetches the resources that a group has access on.
     *
     * @param string $groupId uuid The group to fetch the resources for
     * @return \Cake\ORM\Query
     */
    public function findAllByGroupAccess(string $groupId)
    {
        if (!Validation::uuid($groupId)) {
            throw new \InvalidArgumentException(__('The group id should be a valid uuid.'));
        }

        $query = $this->find();

        // Filter on resources the group has a permission for.
        $query->innerJoinWith('Permissions')
            ->where(['aro_foreign_key' => $groupId]);

        // Filter out deleted resources
        $query->where(['Resources.deleted' => false]);

        return $query;
    }

    /**
     * Get a list of resources with a given list of ids
     *
     * @param string $userId uuid
     * @param array $resourceIds array of resource uuids
     * @throws \InvalidArgumentException if the userId parameter is not a valid uuid.
     * @throws \InvalidArgumentException if the resourceId parameter is not a valid uuid.
     * @return \Cake\ORM\Query
     */
    public function findAllByIds(string $userId, array $resourceIds)
    {
        if (!Validation::uuid($userId)) {
            throw new \InvalidArgumentException(__('The user id should be a valid uuid.'));
        }
        if (empty($resourceIds)) {
            throw new \InvalidArgumentException(__('The resources can not be empty.'));
        } else {
            foreach ($resourceIds as $resourceId) {
                if (!Validation::uuid($resourceId)) {
                    throw new \InvalidArgumentException(__('The resource id should be a valid uuid.'));
                }
            }
        }

        $query = $this->findIndex($userId)
            ->where(['Resources.id IN' => $resourceIds])
            ->all();

        return $query;
    }

    /**
     * Check that a user has access to a resource.
     *
     * @param string $userId The user to get check the access for
     * @param string $resourceId The target resource
     * @param int $permissionType The minimum permission type
     * @throws \InvalidArgumentException if the userId parameter is not a valid uuid.
     * @throws \InvalidArgumentException if the resourceId parameter is not a valid uuid.
     * @return bool
     */
    public function hasAccess(string $userId, string $resourceId, int $permissionType = Permission::READ)
    {
        if (!Validation::uuid($userId)) {
            throw new \InvalidArgumentException(__('The user id should be a valid uuid.'));
        }
        if (!Validation::uuid($resourceId)) {
            throw new \InvalidArgumentException(__('The resource id should be a valid uuid.'));
        }
        if (!$this->association('Permissions')->isValidPermissionType($permissionType)) {
            throw new \InvalidArgumentException(__('The permission type should be in the list of allowed permission type.'));
        }

        $query = $this->find();
        $query->where(['Resources.id' => $resourceId]);
        $query->innerJoinWith('Permission');
        $query = $this->_filterQueryByPermissionsType($query, $userId, $permissionType);

        return !is_null($query->first());
    }

    /**
     * Augment any Resources queries joined with Permissions to ensure the query returns only the
     * resources a user has access.
     *
     * A user has access to a resource if one the following conditions is respected :
     * - A permission is defined directly for the user and for a given resource.
     * - A permission is defined for a group the user is member of and for a given resource.
     *
     * This function can be used on any queries joined with Permissions as following
     * > $query->innerJoinWith('Permissions') or $query->matching('Permissions')
     * > _filterQueryByPermissionsType($query, $userId);
     *
     * @param \Cake\ORM\Query $query The query to filter.
     * @param string $userId The user to check the permissions for.
     * @param int $permissionType The minimum permission type.
     * @throws \InvalidArgumentException if the user id is not a uuid
     * @return \Cake\ORM\Query
     */
    private function _filterQueryByPermissionsType(\Cake\ORM\Query $query, string $userId, int $permissionType = Permission::READ)
    {
        if (!Validation::uuid($userId)) {
            throw new \InvalidArgumentException(__('The user id should be a valid uuid.'));
        }

        // Build the list of allowed permission types.
        $allowedPermissionTypes = array_filter(PermissionsTable::ALLOWED_TYPES, function ($type) use ($permissionType) {
            return $type >= $permissionType;
        });

        // Retrieve the groups ids the user is member of.
        $groupsIds = $this->_findGroupsByUserId($userId)
            ->extract('id')
            ->toArray();

        // In a subquery retrieve the highest permission.
        $permissionSubquery = $this->association('Permissions')
            ->find()
            ->select('Permissions.id');

        // A permission is defined directly for the user and for a given resource.
        $where = [
            'Permissions.aco_foreign_key = Resources.id',
            'Permissions.aro_foreign_key' => $userId,
            'Permissions.type IN' => $allowedPermissionTypes,
        ];

        // A permission is defined for a group the user is member of and for a given resource.
        if (!empty($groupsIds)) {
            $where = [
                'OR' => [ $where, [
                'Permissions.aco_foreign_key = Resources.id',
                'Permissions.aro_foreign_key IN' => $groupsIds,
                'Permissions.type IN' => $allowedPermissionTypes,
                ]]];
        }
        $permissionSubquery->where($where);
        $permissionSubquery->order(['Permissions.type' => 'DESC'])
            ->limit(1);

        // Filter the Resources query by permissions.
        $query->where(['Permission.id' => $permissionSubquery]);

        return $query;
    }

    /**
     * Augment any Resources queries to filter on resources owned by the given user.
     * A owned resource means a resource that is shared with the OWNER permission.
     * @param \Cake\ORM\Query $query The query to filter.
     * @param string $userId The user identifier to filter on.
     * @return \Cake\ORM\Query
     */
    private function _filterQueryIsOwnedByUser(\Cake\ORM\Query $query, string $userId)
    {
        $query = $this->_filterQueryByPermissionsType($query, $userId, Permission::OWNER);

        return $query;
    }

    /**
     * Augment any Resources queries to filter on resources shared with the given user.
     * We consider that a resource is shared with a user when it is accessible by the user but has not
     * been created by him.
     * @param \Cake\ORM\Query $query The query to filter.
     * @param string $userId The user identifier to filter on.
     * @return \Cake\ORM\Query
     */
    private function _filterQuerySharedWithUser(\Cake\ORM\Query $query, string $userId)
    {
        $query->where(['Resources.created_by <>' => $userId]);

        return $query;
    }

    /**
     * Augment any Resources queries to filter on resources shared with a given group.
     *
     * @param \Cake\ORM\Query $query The query to filter.
     * @param string $groupId The group to check the permissions for.
     * @throws \InvalidArgumentException if the group id is not a uuid
     * @return \Cake\ORM\Query
     */
    private function _filterQuerySharedWithGroup(\Cake\ORM\Query $query, string $groupId)
    {
        if (!Validation::uuid($groupId)) {
            throw new \InvalidArgumentException(__('The group id should be a valid uuid.'));
        }

        // Filter the main query.
        $query->innerJoinWith('Permissions', function ($q) use ($groupId) {
            return $q->where([
                'Permissions.aco_foreign_key = Resources.id',
                'Permissions.aro_foreign_key' => $groupId
            ]);
        });

        return $query;
    }

    /**
     * Retrieve the groups a user is member of.
     *
     * @param string $userId The user to retrieve the group for.
     * @throws \InvalidArgumentException if the user id is not a uuid
     * @return \Cake\ORM\Query
     */
    private function _findGroupsByUserId(string $userId)
    {
        if (!Validation::uuid($userId)) {
            throw new \InvalidArgumentException(__('The user id should be a valid uuid.'));
        }

        return $this->association('Permissions')
            ->association('Groups')
            ->find()
            ->innerJoinWith('Users')
            ->where([
                'Groups.deleted' => 0,
                'Users.id' => $userId
            ]);
    }

    /**
     * Soft delete a resource.
     *
     * @param string $userId The user who perform the delete.
     * @param \App\Model\Entity\Resource $resource The resource to delete.
     * @throws \InvalidArgumentException if the user id is not a uuid
     * @return bool true if success
     */
    public function softDelete(string $userId, \App\Model\Entity\Resource $resource)
    {
        // The softDelete will perform an update to the entity to soft delete it.
        if (!Validation::uuid($userId)) {
            throw new \InvalidArgumentException(__('The user id should be a valid uuid.'));
        }
        if ($resource->deleted) {
            $resource->setError('deleted', [
                'is_not_soft_deleted' => __('The resource cannot be soft deleted.')
            ]);

            return false;
        }
        if (!$this->hasAccess($userId, $resource->id, Permission::UPDATE)) {
            $resource->setError('id', [
                'has_access' => __('The user cannot delete this resource.')
            ]);

            return false;
        }

        // Patch the entity.
        $data = [
            'deleted' => true,
            'modified_by' => $userId,
            'secrets' => []
        ];
        $patchOptions = [
            'accessibleFields' => [
                'deleted' => true,
                'secrets' => true,
                'modified' => true,
                'modified_by' => true
            ]
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
        $this->association('Permissions')
            ->deleteAll(['Permissions.aco_foreign_key' => $resource->id]);

        // Remove all the associated favorites.
        $this->association('Favorites')
            ->deleteAll(['Favorites.foreign_key' => $resource->id]);

        // Delete all tags
        if (Configure::read('passbolt.plugins.tags')) {
            $ResourcesTags = TableRegistry::get('Passbolt/Tags.ResourcesTags');
            $ResourcesTags->deleteAll(['resource_id' => $resource->id]);
            $Tags = TableRegistry::get('Passbolt/Tags.Tags');
            $Tags->deleteAllUnusedTags();
        }

        return true;
    }

    /**
     * Simulate a share of a resource with a list of changes.
     * To see how a change is formatted, see : App\Model\Table\Permissions::patchEntitiesWithChanges
     *
     * The function returns an associative array that contains a list of new users who will have access to the resource,
     * and a list of users who will lose their access. These lists will be used to encrypt the secrets for the users
     * who get access and to remove the secrets of the users who lost their access.
     *
     * [
     *   'added' => [uuid],
     *   'removed' => [uuid]
     * ]
     *
     * @param \App\Model\Entity\Resource $resource The resource to patch. The permissions property has to be populated.
     * @param array $changes The list of changes to apply
     * @return array
     */
    public function shareDryRun($resource, array $changes = [])
    {
        // Save the permissions changes. As it's a simulate execute the operation in a transaction so the operation
        // can be canceled with a rollback.
        $result = [];
        $this->getConnection()->transactional(function () use ($resource, $changes, &$result) {
            $result = $this->_patchAndUpdatePermissions($resource, $changes);

            return false;
        });

        return $result;
    }

    /**
     * Share a resource by applying a list of changes.
     * To see how a change is formatted, see : App\Model\Table\Permissions::patchEntitiesWithChanges
     *
     * @param \App\Model\Entity\Resource $resource The resource to patch. The permissions and secrets properties have to be populated.
     * @param array $changes The list of changes to apply
     * @param array $secrets The list of secrets to add
     * @throw \InvalidArgumentException If the resource permission property is not populated
     * @throw \InvalidArgumentException If the resource secrets property is not populated
     * @return bool true if success
     */
    public function share($resource, array $changes = [], array $secrets = [])
    {
        if (!isset($resource->permissions)) {
            throw new \InvalidArgumentException(__('The resource permissions property is not populated.'));
        }
        if (!isset($resource->secrets)) {
            throw new \InvalidArgumentException(__('The resource secrets property is not populated.'));
        }

        // As the share is done with multiple sql operations: save the permissions and save the secrets. Do the
        // operations in a transaction, so in case of error the operations can be canceled with a rollback.
        return $this->getConnection()->transactional(function () use ($resource, $changes, $secrets) {
            $resultUpdatePermissions = $this->_patchAndUpdatePermissions($resource, $changes);
            if (!empty($resource->getErrors())) {
                return false;
            }

            $this->patchAndUpdateSecrets($resource, $secrets, $resultUpdatePermissions['removed']);
            if (!empty($resource->getErrors())) {
                return false;
            }

            // Remove associated data for users who lost access to the resource
            $this->deleteLostAccessAssociatedData($resource->id, $resultUpdatePermissions['removed']);

            return true;
        });
    }

    /**
     * Patch and update the resource permissions.
     *
     * @param \App\Model\Entity\Resource $resource The resource to patch. The permissions property
     *        has to be populated.
     * @param array $changes The list of changes to apply
     * @return array
     */
    protected function _patchAndUpdatePermissions($resource, array $changes = [])
    {
        $changesReferences = [];

        // Patch the resource permissions
        $resource = $this->_patchPermissionsWithChanges($resource, $changes, $changesReferences);

        // Retrieve the users who have access to the resource before applying the changes.
        $findUsersOptions['filter']['has-access'] = [$resource->id];
        $beforeUsersIds = $this->Permissions->Users->findIndex(Role::USER, $findUsersOptions)->extract('id')->toArray();

        // Save the resource permissions.
        $resource = $this->_updatePermissions($resource, $changesReferences);

        // Retrieve the users who have access to the resource after applying the changes.
        $afterUsersIds = $this->Permissions->Users->findIndex(Role::USER, $findUsersOptions)->extract('id')->toArray();

        // Extract the users that will require the secrets to be encrypted for.
        $secretsToAddFor = array_diff($afterUsersIds, $beforeUsersIds);
        // Extract the users the secrets will be deleted for.
        $secretsToDeleteFor = array_diff($beforeUsersIds, $afterUsersIds);

        return [
            'added' => $secretsToAddFor,
            'removed' => $secretsToDeleteFor
        ];
    }

    /**
     * Patch the resource permissions with a list of changes
     *
     * @param \App\Model\Entity\Resource $resource The resource to patch. The permissions property has to be populated.
     * @param array $changes The list of changes to apply
     * @param array $changesReferences (Reference) A list of reference to know on which permissions the changes have
     *        been applied on.
     * @return \App\Model\Entity\Resource
     */
    protected function _patchPermissionsWithChanges($resource, array $changes = [], array &$changesReferences = [])
    {
        // Cannot use the association Permissions to call the function patchEntitiesWithChanges.
        // A weird bug make the passage by reference of the variables not working as expected.
        // The variable ($changesReferences) changes made in patchEntitiesWithChanges are not visible in the current
        // scope when using $this->Permission->patchEntitiesWithChanges().
        $Permissions = TableRegistry::get('Permissions');

        // Patch the permission entities with the changes.
        try {
            $resource->permissions = $Permissions->patchEntitiesWithChanges(
                $resource->permissions,
                $changes,
                $resource->id,
                $changesReferences
            );
            $resource->setDirty('permissions', true);
        } catch (ValidationRuleException $e) {
            return $resource->setError('permissions', $e->getErrors());
        }

        return $resource;
    }

    /**
     * Update the resource permissions.
     *
     * If an error occurred when saving the resource, try to associate the error with a change, so the caller
     * can identify easily what's wrong.
     *
     * By instance, if the caller try to update a permission with this changes list :
     *
     * $changes = [
     *   0 => [
     *     'id' => 'PERMISSION_ID',
     *     'type' => UNKNOWN_TYPE
     *   ]
     * ];
     *
     * The resource save returns an error like :
     *
     * $errors = [
     *   'permissions' => [
     *     24 => [
     *       'type' => [
     *         'inList' => 'The type must be one of the following: ...'
     *       ]
     *     ]
     *   ]
     * ];
     *
     * Then it's hard for the caller to identify the change that is responsible of the error. To avoid this the errors
     * should be associated with the changes (if possible) :
     *
     * $errors = [
     *   'permissions' => [
     *     0 => [
     *       'type' => [
     *         'inList' => 'The type must be one of the following: ...'
     *       ]
     *     ]
     *   ]
     * ];
     *
     * @param \App\Model\Entity\Resource $resource The resource to update. The permissions property has to be populated.
     * @param array $changesReferences (Reference) A list of reference to know on which permissions the changes have
     *        been applied on.
     * @return \App\Model\Entity\Resource
     */
    protected function _updatePermissions($resource, array $changesReferences = [])
    {
        // Save the resource permissions.
        $options = [
            'accessibleFields' => [
                'permissions' => true
            ],
            'associated' => [
                'Permissions' => [
                    'accessibleFields' => [
                        'aco' => true,
                        'aco_foreign_key' => true,
                        'aro' => true,
                        'aro_foreign_key' => true,
                        'type' => true
                    ]
                ]
            ]
        ];
        $this->save($resource, $options);

        // Associate the errors that occurred while saving with the changes (if possible).
        $errors = $resource->getErrors();
        if (!empty($errors['permissions'])) {
            $errorsPermissions = $errors['permissions'];
            unset($errors['permissions']);
            foreach ($errorsPermissions as $permissionKey => $errors) {
                // If the error key is an integer, the error is relative to the save operation of a permission entity.
                // Try to link it with a corresponding change if any.
                if (is_int($permissionKey)) {
                    $changeKey = array_search($permissionKey, $changesReferences);
                    if ($changeKey !== false) {
                        $resource->setError('permissions', [$changeKey => $errors]);
                    } else {
                        // An issue relative to a permission that has not been updated is unexpected.
                    }
                } else {
                    // This is an error relative to the field permissions of the model Resource.
                    $resource->setError('permissions', [$permissionKey => $errors]);
                }
            }
        }

        return $resource;
    }

    /**
     * Patch and update the resource secrets.
     *
     * @param \App\Model\Entity\Resource $resource The resource to patch. The permissions property has to be populated.
     * @param array $add The list of secrets to add
     * @param array $delete The list of user identifies for whom the secrets must be deleted
     * @return \App\Model\Entity\Resource|bool
     */
    public function patchAndUpdateSecrets($resource, array $add = [], array $delete = [])
    {
        // Patch the secret entities with the changes.
        try {
            $resource->secrets = $this->Secrets->patchEntitiesWithChanges(
                $resource->id,
                $resource->secrets,
                $add,
                $delete
            );
            $resource->setDirty('secrets', true);
        } catch (ValidationRuleException $e) {
            $resource->setError('secrets', $e->getErrors());

            return false;
        }

        // Save the resource secrets.
        $options = [
            'accessibleFields' => [
                'secrets' => true
            ],
            'associated' => [
                'Secrets' => [
                    'accessibleFields' => [
                        'data' => true,
                    ]
                ]
            ]
        ];

        return $this->save($resource, $options);
    }

    /**
     * Remove the resource associated data for the users who lost access to the resource.
     *
     * @param string $resourceId The resource identifier the users lost the access to
     * @param array $usersId The list of users who lost access to the resource
     * @return void
     */
    public function deleteLostAccessAssociatedData($resourceId, array $usersId = [])
    {
        if (empty($usersId)) {
            return;
        }

        $Favorites = TableRegistry::get('Favorites');
        $Favorites->deleteAll([
            'foreign_key' => $resourceId,
            'user_id IN' => $usersId
        ]);

        if (Configure::read('passbolt.plugins.tags')) {
            $ResourcesTags = TableRegistry::get('Passbolt/Tags.ResourcesTags');
            $ResourcesTags->deleteAll([
                'resource_id' => $resourceId,
                'user_id IN' => $usersId
            ]);
            $Tags = TableRegistry::get('Passbolt/Tags.Tags');
            $Tags->deleteAllUnusedTags();
        }
    }

    /**
     * Soft delete a list of resources by Ids
     *
     * @param string $resourceIds uuid of Resources
     * @param bool $cascade true
     * @return void
     */
    public function softDeleteAll($resourceIds, $cascade = true)
    {
        $Resources = TableRegistry::get('Resources');
        $Resources->updateAll(['deleted' => true], ['id IN' => $resourceIds]);

        if ($cascade) {
            $Favorites = TableRegistry::get('Favorites');
            $Favorites->deleteAll(['foreign_key IN' => $resourceIds]);

            $Secrets = TableRegistry::get('Secrets');
            $Secrets->deleteAll(['resource_id IN' => $resourceIds]);

            $Permissions = TableRegistry::get('Permissions');
            $Permissions->deleteAll(['aco_foreign_key IN' => $resourceIds]);

            if (Configure::read('passbolt.plugins.tags')) {
                $ResourcesTags = TableRegistry::get('Passbolt/Tags.ResourcesTags');
                $ResourcesTags->deleteAll(['resource_id IN' => $resourceIds]);
                $Tags = TableRegistry::get('Passbolt/Tags.Tags');
                $Tags->deleteAllUnusedTags();
            }
        }
    }
}

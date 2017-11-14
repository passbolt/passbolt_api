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

use App\Model\Entity\Permission;
use App\Model\Entity\Role;
use App\Model\Rule\HasResourceAccessRule;
use App\Model\Rule\IsNotSoftDeletedRule;
use Cake\Collection\CollectionInterface;
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
            'foreignKey' => 'foreign_id'
        ]);
        $this->hasOne('Modifier', [
            'className' => 'Users',
            'bindingKey' => 'modified_by',
            'foreignKey' => 'id'
        ]);
        $this->hasOne('Permissions', [
            'foreignKey' => 'aco_foreign_key'
        ]);
        $this->hasMany('Secrets', [
            'foreignKey' => 'resource_id'
        ]);
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
            ->lengthBetween('name', [3, 64], __('The name length should be between {0} and {1} characters.', 3, 64))
            ->requirePresence('name', 'create', __('A name is required.'))
            ->notEmpty('name', __('The name cannot be empty.'));

        $validator
            ->utf8Extended('username', __('The username is not a valid utf8 string.'))
            ->lengthBetween('username', [3, 64], __('The username length should be between {0} and {1} characters.', 3, 64))
            ->allowEmpty('username');

        $validator
            ->utf8('uri', __('The uri is not a valid utf8 string (emoticons excluded).'))
            ->lengthBetween('uri', [3, 255], __('The uri length should be between {0} and {1} characters.', 3, 255))
            ->allowEmpty('uri');

        $validator
            ->utf8Extended('description', __('The description is not a valid utf8 string.'))
            ->lengthBetween('description', [3, 10000], __('The description length should be between {0} and {1} characters.', 3, 10000))
            ->allowEmpty('description');

        $validator
            ->boolean('deleted')
            ->requirePresence('deleted', 'create')
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
            ->requirePresence('permission', 'create', __('A permission is required.'))
            ->notEmpty('permission', __('The permission cannot be empty.'));

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
        $rules->addCreate([$this, 'ruleOwnerPermissionProvided'], 'owner_permission_provided', [
            'errorField' => 'permission',
            'message' => __('The permission of the owner is required.')
        ]);
        $rules->addCreate([$this, 'ruleOwnerSecretProvided'], 'owner_secret_provided', [
            'errorField' => 'secrets',
            'message' => __('The secret of the owner is required.')
        ]);

        // Update rules.
        $rules->addUpdate([$this, 'ruleSecretsProvided'], 'secrets_provided', [
            'errorField' => 'secrets',
            'message' => __('The secrets of all the users having access to the resource are required.')
        ]);
        $rules->addUpdate(new IsNotSoftDeletedRule(), 'resource_is_not_soft_deleted', [
            'table' => 'Resources',
            'errorField' => 'id',
            'message' => __('The resource cannot be soft deleted.')
        ]);
        $rules->addUpdate(new HasResourceAccessRule(), 'has_resource_access', [
            'errorField' => 'id',
            'message' => __('Access denied.'),
            'userField' => 'modified_by',
            'resourceField' => 'id',
            'permissionType' => Permission::UPDATE
        ]);

        return $rules;
    }

    /**
     * Validate that the a resource can be created only if the permission of the owner is provided.
     *
     * @param \App\Model\Entity\Resource $entity The entity that will be created.
     * @param array $options options
     * @return bool
     */
    public function ruleOwnerPermissionProvided($entity, array $options = [])
    {
        return $entity->permission->aro_foreign_key == $entity->created_by;
    }

    /**
     * Validate that the a resource can be created only if the secret of the owner is provided.
     *
     * @param \App\Model\Entity\Resource $entity The entity that will be created.
     * @param array $options options
     * @return bool
     */
    public function ruleOwnerSecretProvided($entity, array $options = [])
    {
        return $entity->secrets[0]->user_id == $entity->created_by;
    }

    /**
     * Validate that the secrets of all the allowed users are provided if the secret changed.
     *
     * @param \App\Model\Entity\Resource $entity The entity that will be created.
     * @param array $options options
     * @return bool
     */
    public function ruleSecretsProvided($entity, array $options = [])
    {
        // Secrets are not required to update a resource.
        if (!isset($entity->secrets) || empty($entity->secrets)) {
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

        // If the lists are not the same, it means the list of secrets provided are not good.
        if (!empty(array_diff($allowedUsersIds, $secretsUsersIds))) {
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
    public function findIndex($userId, array $options = [])
    {
        if (!Validation::uuid($userId)) {
            throw new \InvalidArgumentException(__('The parameter userId should be a valid uuid.'));
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
            } // Filter out the favorite resources.
            else {
                $query->notMatching('Favorites', function ($q) use ($userId) {
                    return $q->where(['Favorites.user_id' => $userId]);
                });
            }
        }

        // If contains favorite.
        if (isset($options['contain']['favorite'])) {
            $query->contain('Favorites', function ($q) use ($userId) {
                return $q->where(['Favorites.user_id' => $userId]);
            });
        }

        /*
         * Filter on resources the user is allowed to access.
         *
         * If the contain permission option is given, prefer to use the \Cake\ORM\Query::matching
         * function instead of the \Cake\ORM\Query::innerJoinWith function. The permission will be
         * retrieved within a unique sql request and the permission will be available in the
         * property _matchingData. Format the query result to populate the permission property
         * as a contain would do.
         */
        if (isset($options['contain']['permission'])) {
            // Filter on resources the user is allowed to access.
            $query->matching('Permissions');
            $query = $this->_filterQueryByPermissionsType($query, $userId, Permission::READ);

            // Format the query result to populate the permission property as a contain would do.
            $query->formatResults(function (CollectionInterface $results) {
                return $results->map(function ($row) {
                    $row['permission'] = $row['_matchingData']['Permissions'];
                    unset($row['_matchingData']['Permissions']);

                    return $row;
                });
            });
        } else {
            $query->innerJoinWith('Permissions');
            $query = $this->_filterQueryByPermissionsType($query, $userId, Permission::READ);
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
    public function findView($userId, $resourceId, array $options = [])
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
     * Check that a user has access to a resource.
     *
     * @param string $userId The user to get check the access for
     * @param string $resourceId The target resource
     * @param int $permissionType The minimum permission type
     * @throws \InvalidArgumentException if the userId parameter is not a valid uuid.
     * @throws \InvalidArgumentException if the resourceId parameter is not a valid uuid.
     * @return bool
     */
    public function hasAccess($userId, $resourceId, $permissionType = Permission::READ)
    {
        if (!Validation::uuid($userId)) {
            throw new \InvalidArgumentException(__('The parameter userId should be a valid uuid.'));
        }
        if (!Validation::uuid($resourceId)) {
            throw new \InvalidArgumentException(__('The parameter resourceId should be a valid uuid.'));
        }
        if (!$this->association('Permissions')->isValidPermissionType($permissionType)) {
            throw new \InvalidArgumentException(__('The parameter permissionType should be a permission type.'));
        }

        $query = $this->find();
        $query->where(['Resources.id' => $resourceId]);
        $query->innerJoinWith('Permissions');
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
     * @return \Cake\ORM\Query
     */
    private function _filterQueryByPermissionsType($query, $userId, $permissionType = Permission::READ)
    {
        // Retrieve the groups ids the user is member of.
        $groupsIds = $this->_findGroupsByUserId($userId)
            ->extract('id')
            ->toArray();

        // In a subquery retrieve the highest permission.
        $permissionSubquery = $this->association('Permissions')
            ->find()
            ->select('Permissions.id');

        $where = [
            'Permissions.aco_foreign_key = Resources.id',
            'Permissions.aro_foreign_key' => $userId,
            'Permissions.type >=' => $permissionType,
        ];

        if (!empty($groupsIds)) {
            $where = [
                'OR' => [ $where, [
                'Permissions.aco_foreign_key = Resources.id',
                'Permissions.aro_foreign_key IN' => $groupsIds,
                'Permissions.type >=' => $permissionType,
            ]]];
        }
        $permissionSubquery->where($where);
        $permissionSubquery->order(['Permissions.type' => 'DESC'])
            ->limit(1);

        // Filter the Resources query by permissions.
        $query->where(['Permissions.id' => $permissionSubquery]);
        return $query;
    }

    /**
     * Retrieve the groups a user is member of.
     *
     * @param string $userId The user to retrieve the group for.
     * @return \Cake\ORM\Query
     */
    private function _findGroupsByUserId($userId)
    {
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
     * Event fired before request data is converted into entities
     * - On create, set not deleted to false
     *
     * @param \Cake\Event\Event $event event
     * @param \ArrayObject $data data
     * @param \ArrayObject $options options
     * @return void
     */
    public function beforeMarshal(\Cake\Event\Event $event, \ArrayObject $data, \ArrayObject $options)
    {
        if (isset($options['validate']) && $options['validate'] === 'default') {
            $data['deleted'] = false;
        }
    }

    /**
     *
     */
    public function findSharedResourcesUserIsSoleOwner($userId)
    {
        // The resources id the user is owner of
        $query = $this->Permissions->find();
        $resourceIds = $query
            ->select('aco_foreign_key')
            ->where([
                'aro_foreign_key' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
                'aro' => 'User',
                'aco' => 'Resource',
                'type' => Permission::OWNER
            ])
            ->all()
            ->toArray();

        // get the number of users for the resources the user owns

    }
}

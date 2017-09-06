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

use App\Model\Entity\Role;
use Aura\Intl\Exception;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \App\Model\Table\RolesTable|\Cake\ORM\Association\BelongsTo $Roles
 * @property \App\Model\Table\AuthenticationTokensTable|\Cake\ORM\Association\HasMany $AuthenticationTokens
 * @property \App\Model\Table\ControllerLogsTable|\Cake\ORM\Association\HasMany $ControllerLogs
 * @property \App\Model\Table\FavoritesTable|\Cake\ORM\Association\HasMany $Favorites
 * @property \App\Model\Table\FileStorageTable|\Cake\ORM\Association\HasMany $FileStorage
 * @property \App\Model\Table\GpgkeysTable|\Cake\ORM\Association\HasMany $Gpgkeys
 * @property \App\Model\Table\ProfilesTable|\Cake\ORM\Association\HasMany $Profiles
 * @property \App\Model\Table\SecretsTable|\Cake\ORM\Association\HasMany $Secrets
 * @property \App\Model\Table\UsersResourcesPermissionsTable|\Cake\ORM\Association\HasMany $UsersResourcesPermissions
 * @property \App\Model\Table\GroupsTable|\Cake\ORM\Association\BelongsToMany $Groups
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
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

        $this->setTable('users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Roles', [
            'foreignKey' => 'role_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('AuthenticationTokens', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('Favorites', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('FileStorage', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasOne('Gpgkeys', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasOne('Profiles', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('Secrets', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('UsersResourcesPermissions', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('GroupsUsers', [
            'foreignKey' => 'user_id'
        ]);
        $this->belongsToMany('Groups', [
            'foreignKey' => 'user_id',
            'targetForeignKey' => 'group_id',
            'joinTable' => 'groups_users'
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
            ->scalar('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('username')
            ->requirePresence('username', 'create')
            ->notEmpty('username');

        $validator
            ->boolean('active')
            ->requirePresence('active', 'create')
            ->notEmpty('active');

        $validator
            ->boolean('deleted')
            ->requirePresence('deleted', 'create')
            ->notEmpty('deleted');

        $validator
            ->scalar('created_by')
            ->requirePresence('created_by', 'create')
            ->notEmpty('created_by');

        $validator
            ->scalar('modified_by')
            ->requirePresence('modified_by', 'create')
            ->notEmpty('modified_by');

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
        $rules->add($rules->isUnique(['username']));
        $rules->add($rules->existsIn(['role_id'], 'Roles'));

        return $rules;
    }

    /**
     * Build the query that fetches data for user index
     *
     * @param Query $query a query instance
     * @param array $options options
     * @throws Exception if no role is specified
     * @return Query
     */
    public function findIndex(Query $query, array $options)
    {
        // Options must contain a role
        if (!isset($options['role'])) {
            throw new Exception(__('User table findIndex should have a role set in options.'));
        }

        // Default associated data
        $query->contain([
            'Roles',
            'Profiles',
            //'Profiles.Avatar',
            'Gpgkeys',
            'GroupsUsers'
        ]);

        // Filter out guests, inactive and deleted users
        $where = [
            'Users.deleted' => false,
            'Users.active' => true,
            'Roles.name <>' => Role::GUEST
        ];

        // if user is admin, we allow seing inactive users via the 'is-active' filter
        if ($options['role'] === Role::ADMIN) {
            if (isset($options['filter']['is-active'])) {
                $where['active'] = ($options['filter']['is-active'] ? true : false);
            }
        }
        $query->where($where);

        return $query;
    }

    /**
     * Find view
     *
     * @param Query $query
     * @param array $options
     * @throws Exception if no id is specified
     * @return Query
     */
    public function findView(Query $query, array $options) {
        // Options must contain an id
        if (!isset($options['id'])) {
            throw new Exception(__('User table findView should have an id set in options.'));
        }
        // Same rule than index apply
        // with a specific id requested
        $query = $this->findIndex($query, $options);
        $query->where(['Users.id' => $options['id']]);

        return $query;
    }

    /**
     * Build the query that fetches the user data during authentication
     *
     * @param Query $query a query instance
     * @param array $options options
     * @return Query $query
     */
    public function findAuth(Query $query, array $options)
    {
        return $query->contain(['Roles']);
    }
}

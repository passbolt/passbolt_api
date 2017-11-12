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
use App\Model\Table\RolesTable;
use Aura\Intl\Exception;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\InternalErrorException;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

/**
 * Users Model
 *
 * @property \App\Model\Table\RolesTable|\Cake\ORM\Association\BelongsTo $Roles
 * @property \App\Model\Table\FileStorageTable|\Cake\ORM\Association\HasMany $FileStorage
 * @property \App\Model\Table\GpgkeysTable|\Cake\ORM\Association\HasMany $Gpgkeys
 * @property \App\Model\Table\ProfilesTable|\Cake\ORM\Association\HasMany $Profiles
 * @property \App\Model\Table\GroupsUsersTable|\Cake\ORM\Association\HasMany $GroupsUsers
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
        $this->hasMany('FileStorage', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasOne('Gpgkeys', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasOne('Profiles', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('GroupsUsers', [
            'foreignKey' => 'user_id'
        ]);
        $this->belongsToMany('Groups', [
            'through' => 'GroupsUsers'
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
            ->uuid('id', __('User id by must be a valid UUID.'))
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('username', 'create', __('A username is required.'))
            ->notEmpty('username', __('A username is required.'))
            ->maxLength('username', 255, __('The username length should be maximum {0} characters.', 255))
            ->email('username', true, __('The username should be a valid email address.'));

        $validator
            ->boolean('active')
            ->requirePresence('active', 'create')
            ->notEmpty('active');

        $validator
            ->boolean('deleted')
            ->requirePresence('deleted', 'create')
            ->notEmpty('deleted');

        $validator
            ->requirePresence('profile', 'create')
            ->notEmpty('profile');

        return $validator;
    }

    /**
     * Register validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationRegister(Validator $validator)
    {
        return $this->validationDefault($validator);
    }

    /**
     * Register validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationRecover(Validator $validator)
    {
        $validator
            ->requirePresence('username', 'create', __('A username is required.'))
            ->notEmpty('username', __('A username is required.'))
            ->maxLength('username', 255, __('The username length should be maximum 254 characters.'))
            ->email('username', true, __('The username should be a valid email address.'));

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
        $rules->add($rules->isUnique(['username']), 'uniqueUsername', [
            'message' => __('This username is already in use.')
        ]);
        $rules->add($rules->existsIn(['role_id'], 'Roles'), 'validRole', [
            'message' => __('This is not a valid role')
        ]);

        return $rules;
    }

    /**
     * Build the query that fetches data for user index
     *
     * @param string $role name
     * @param array $options filters
     * @throws Exception if no role is specified
     * @return Query
     */
    public function findIndex($role, $options = null)
    {
        $query = $this->find();

        // Options must contain a role
        if (!isset($role)) {
            throw new Exception(__('User table findIndex should have a role set in options.'));
        }

        // Default associated data
        $query->contain([
            'Profiles',
            'Gpgkeys',
            'Roles',
            'GroupsUsers'
            // @todo avatar as part of profile.avatar
        ]);

        // Filter out guests and deleted users
        $query->where([
            'Users.deleted' => false,
            'Roles.name <>' => Role::GUEST
        ]);

        // If user is admin, we allow seeing inactive users via the 'is-active' filter
        if ($role === Role::ADMIN && isset($options['filter']['is-active'])) {
            $query->where(['Users.active' => $options['filter']['is-active']]);
        } else {
            // otherwise we only show active users
            $query->where(['Users.active' => true]);
        }

        // If searching for a name or username
        if (isset($options['filter']['search']) && count($options['filter']['search'])) {
            $search = '%' . $options['filter']['search'][0] . '%';
            $query->where(['OR' => [
                ['Users.username LIKE' => $search],
                ['Profiles.first_name LIKE' => $search],
                ['Profiles.last_name LIKE' => $search]
            ]]);
        }

        // If searching by group id
        if (isset($options['filter']['has-groups']) && count($options['filter']['has-groups'])) {
            $query = $this->_filterQueryByGroupsUsers($query, $options['filter']['has-groups']);
        }

        // Ordering options
        if (isset($options['order'])) {
            $query->order($options['order']);
        }

        return $query;
    }

    /**
     * Find view
     *
     * @param string $userId uuid
     * @param string $roleName role name
     * @throws Exception if no id is specified
     * @return Query
     */
    public function findView($userId, $roleName)
    {
        // Same rule than index apply
        // with a specific id requested
        $query = $this->findIndex($roleName);
        $query->where(['Users.id' => $userId]);

        return $query;
    }

    /**
     * Build the query that fetches the user data during authentication
     *
     * @param Query $query a query instance
     * @param array $options options
     * @throws Exception if fingerprint id is not set
     * @return Query $query
     */
    public function findAuth(Query $query, array $options)
    {
        // Options must contain an id
        if (!isset($options['fingerprint'])) {
            throw new Exception(__('User table findAuth should have a fingerprint id set in options.'));
        }

        // auth query is always done as guest
        // Use default index option (active:true, deleted:false) and contains
        $query = $this->findIndex(Role::GUEST)
            ->where(['Gpgkeys.fingerprint' => $options['fingerprint']]);

        return $query;
    }

    /**
     * Build the query that fetches data for user recovery form
     *
     * @param string $username email of user to retrieve
     * @param array $options options
     * @return \Cake\ORM\Query
     */
    public function findRecover($username, array $options = [])
    {
        // show active first and do not count deleted ones
        $query = $this->find()
            ->where(['Users.username' => $username, 'Users.deleted' => false])
            ->contain(['Roles', 'Profiles']) // @TODO Avatar for recovery email
            ->order(['Users.active' => 'DESC']);

        return $query;
    }

    /**
     * Build the query that fetches data for user setup start
     *
     * @param string $userId uuid
     * @return object $user entity
     */
    public function findSetup($userId)
    {
        // show active first and do not count deleted ones
        $user = $this->find()
            ->contain(['Roles', 'Profiles', 'Roles'])
            ->where([
                'Users.id' => $userId,
                'Users.deleted' => false, // forbid deleted users to start setup
                'Users.active' => false   // forbid users that have completed the setup to retry
            ])
            ->first();

        return $user;
    }

    /**
     * Build the query that checks data for user setup start/completion
     *
     * @param string $userId uuid
     * @return object $user entity
     */
    public function findSetupRecover($userId)
    {
        // show active first and do not count deleted ones
        $user = $this->find()
            ->contain(['Roles', 'Profiles', 'Roles'])
            ->where([
                'Users.id' => $userId,
                'Users.deleted' => false, // forbid deleted users to start setup
                'Users.active' => true    // forbid users that have not completed the setup to recover
            ])
            ->first();

        return $user;
    }

    /**
     * Event fired before request data is converted into entities
     * Set user to inactive and not deleted on register
     *
     * @param \Cake\Event\Event $event event
     * @param \ArrayObject $data data
     * @param \ArrayObject $options options
     * @return void
     */
    public function beforeMarshal(\Cake\Event\Event $event, \ArrayObject $data, \ArrayObject $options)
    {
        if (isset($options['validate']) && $options['validate'] === 'register') {
            // Do not allow the user to set these flags
            $data['active'] = false;
            $data['deleted'] = false;

            // Set role to Role::USER by default
            if (isset($data['role_id']) && $options['currentUserRole'] === Role::ADMIN) {
                // let it be
            } else {
                $data['role_id'] = $this->Roles->getIdByName(Role::USER);
            }
        }
    }

    /**
     * Filter a Groups query by groups users.
     *
     * @param \Cake\ORM\Query $query The query to augment.
     * @param array<string> $groupsIds The users to filter the query on.
     * @param bool $areManager (optional) Should the users be managers ? Default false.
     * @return $query
     */
    private function _filterQueryByGroupsUsers($query, array $groupsIds, $areManager = false)
    {
        // If there is only one group use a left join
        if (count($groupsIds) == 1) {
            $query->leftJoinWith('GroupsUsers');
            $query->where(['GroupsUsers.group_id' => $groupsIds[0]]);

            return $query;
        }

        // Otherwise use a subquery to
        // find all the users that are members of all the listed groups
        $GroupsUsers = TableRegistry::get('GroupsUsers');
        $subQuery = $GroupsUsers->find()
            ->select([
                'GroupsUsers.user_id',
                'count' => $query->func()->count('GroupsUsers.user_id')
            ])
            ->where([
                'GroupsUsers.group_id IN' => $groupsIds
            ])
            ->group('GroupsUsers.user_id')
            ->having(['count' => count($groupsIds)]);

        // Execute the sub query and extract the user ids.
        $matchingUserIds = Hash::extract($subQuery->toArray(), '{n}.user_id');

        // Filter the query.
        if (empty($matchingUserIds)) {
            // if no user match all groups it should return nobody
            // @TODO find more elegant way?
            $query->where(['true' => false]);
        } else {
            $query->where(['Users.id IN' => $matchingUserIds]);
        }

        return $query;
    }

    /**
     * Return a user entity
     *
     * @param $data
     * @param $roleName
     * @return \App\Model\Entity\User
     */
    public function buildEntity($data, $roleName)
    {
        $accessibleFields = [
            'username' => true,
            'profile' => true,
            'active' => true, // reset in beforeMarshal
            'deleted' => true, // idem
            'role_id' => true, // idem
        ];

        return $this->newEntity(
            $data,
            [
                'validate' => 'register',
                'accessibleFields' => $accessibleFields,
                'associated' => [
                    'Profiles' => [
                        'validate' => 'register',
                        'accessibleFields' => [
                            'first_name' => true,
                            'last_name' => true
                        ]
                    ]
                ],
                'currentUserRole' => $roleName
            ]
        );
    }

    public function getForEmail($userId)
    {
        $user = $this->find()
            ->where(['Users.id' => $userId])
            ->contain([
                'Profiles',
                'Roles',
                //'Avatar' // TODO avatar
            ])
            ->first();

        return $user;
    }
}

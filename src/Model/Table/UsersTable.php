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

use App\Error\Exception\ValidationException;
use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\Avatar;
use App\Model\Entity\Role;
use App\Model\Entity\User;
use App\Model\Rule\IsNotSoleManagerOfNonEmptyGroupRule;
use App\Model\Rule\IsNotSoleOwnerOfSharedResourcesRule;
use App\Model\Traits\Users\UsersFindersTrait;
use App\Utility\UserAccessControl;
use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \App\Model\Table\RolesTable&\Cake\ORM\Association\BelongsTo $Roles
 * @property \App\Model\Table\GpgkeysTable&\Cake\ORM\Association\HasOne $Gpgkeys
 * @property \App\Model\Table\PermissionsTable&\Cake\ORM\Association\HasMany $Permissions
 * @property \App\Model\Table\ProfilesTable&\Cake\ORM\Association\HasOne $Profiles
 * @property \App\Model\Table\GroupsUsersTable&\Cake\ORM\Association\HasMany $GroupsUsers
 * @property \App\Model\Table\GroupsTable&\Cake\ORM\Association\BelongsToMany $Groups
 * @property \Passbolt\Log\Model\Table\EntitiesHistoryTable&\Cake\ORM\Association\HasMany $EntitiesHistory
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, ?callable $callback = null, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @property \App\Model\Table\AuthenticationTokensTable&\Cake\ORM\Association\HasMany $AuthenticationTokens
 * @property \Passbolt\Log\Model\Table\ActionLogsTable&\Cake\ORM\Association\HasMany $ActionLogs
 * @method \App\Model\Entity\User newEmptyEntity()
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 * @method \Cake\ORM\Query findById(string $id)
 * @method \Cake\ORM\Query findByUsername(string $username)
 */
class UsersTable extends Table
{
    use UsersFindersTrait;

    public const AFTER_REGISTER_SUCCESS_EVENT_NAME = 'Model.Users.afterRegister.success';

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Passbolt/Locale.Locale');
        $this->addBehavior('Timestamp');

        $this->belongsTo('Roles', [
            'foreignKey' => 'role_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('AuthenticationTokens', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasOne('Gpgkeys', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasOne('Profiles', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('GroupsUsers', [
            'foreignKey' => 'user_id',
        ]);
        $this->belongsToMany('Groups', [
            'through' => 'GroupsUsers',
        ]);
        $this->hasMany('Permissions', [
            'foreignKey' => 'aro_foreign_key',
        ]);
        $this->hasMany('EntitiesHistory', [
            'className' => 'Passbolt/Log.EntitiesHistory',
            'foreignKey' => 'foreign_key',
        ]);
        $this->hasMany('ActionLogs', [
            'className' => 'Passbolt/Log.ActionLogs',
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('AccountSettings', [
            'className' => 'Passbolt/AccountSettings.AccountSettings',
            'foreignKey' => 'user_id',
        ]);
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
            ->uuid('id', __('The user identifier by should be a valid UUID.'))
            ->allowEmptyString('id', __('The identifier should not be empty.'), 'create');

        $validator
            ->requirePresence('username', 'create', __('A username is required.'))
            ->maxLength('username', 255, __('The username length should be maximum {0} characters.', 255))
            ->email(
                'username',
                Configure::read('passbolt.email.validate.mx'),
                __('The username should be a valid email address.')
            );

        $validator
            ->boolean('active', __('The active status should be a valid boolean.'));

        $validator
            ->uuid('role_id', __('The role identifier should be a valid UUID.'))
            ->requirePresence('role_id', 'create', __('A role identifier is required.'));

        $validator
            ->boolean('deleted', __('The deleted status should be a valid boolean.'));

        $validator
            ->requirePresence('profile', 'create', 'A profile is required.')
            // @todo translation comment: is it still something necessary?
            ->allowEmptyString('profile', __('The profile should not be empty.'), false);

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
     * Update validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationUpdate(Validator $validator)
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
            ->notEmptyString('username', __('The username should not be empty.'))
            ->maxLength('username', 255, __('The username length should be maximum 255 characters.'))
            ->email(
                'username',
                Configure::read('passbolt.email.validate.mx'),
                __('The username should be a valid email address.')
            );

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
        // Add rule
        $rules->add($rules->isUnique(['username', 'deleted']), 'uniqueUsername', [
            'message' => __('The username is already in use.'),
        ]);
        $rules->add($rules->existsIn(['role_id'], 'Roles'), 'validRole', [
            'message' => __('The role identifier does not exist.'),
        ]);

        // Delete rules
        $msg = __('The user should not be sole owner of shared content, transfer the ownership to other users.');
        $rules->addDelete(new IsNotSoleOwnerOfSharedResourcesRule(), 'soleOwnerOfSharedContent', [
            'errorField' => 'id',
            'message' => $msg,
        ]);
        $msg = __('The user should not be sole group manager of group(s), transfer the management to other users.');
        $rules->addDelete(new IsNotSoleManagerOfNonEmptyGroupRule(), 'soleManagerOfNonEmptyGroup', [
            'errorField' => 'id',
            'message' => $msg,
        ]);

        return $rules;
    }

    /**
     * Return a user entity
     *
     * @param array $data the request data
     * @throws \InvalidArgumentException if role name is not valid
     * @return \App\Model\Entity\User
     */
    public function buildEntity(array $data)
    {
        return $this->newEntity(
            $data,
            [
                'validate' => 'register',
                'accessibleFields' => [
                    'username' => true,
                    'deleted' => true,
                    'profile' => true,
                    'role_id' => true,
                ],
                'associated' => [
                    'Profiles' => [
                        'validate' => 'register',
                        'accessibleFields' => [
                            'first_name' => true,
                            'last_name' => true,
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Edit a given entity with the prodived data according to the permission of the current user role
     * Only allow editing the first_name and last_name
     * Also allow editing the role_id but only if admin
     * Other changes such as active or username are not permitted
     *
     * @param \App\Model\Entity\User $user User
     * @param array $data request data
     * @param string $roleName role name for example Role::User or Role::ADMIN
     * @return \App\Model\Entity\User the patched user entity
     */
    public function editEntity(User $user, array $data, string $roleName)
    {
        $accessibleUserFields = [
            'active' => false,
            'deleted' => false,
            'created' => false,
            'username' => false,
            'role_id' => false,
            'profile' => true,
            'gpgkey' => false,
        ];
        // only admins can set roles
        if ($roleName === Role::ADMIN) {
            $accessibleUserFields['role_id'] = true;
        }

        $accessibleProfileFields = [
            'user_id' => false,
            'created' => false,
            'first_name' => true,
            'last_name' => true,
            'avatar' => true,
        ];

        // Populates fields required for Avatar, if needed.
        if (!empty(Hash::get($data, 'profile.avatar'))) {
            if (!empty(Hash::get($data, 'profile.avatar.file'))) {
                // Force creation of new Avatar.
                $user->profile->avatar = new Avatar();
            } else {
                // If file is not provided, nothing else should be. We simply delete the whole entry.
                unset($data['profile']['avatar']);
                $user->profile->avatar = null;
            }
        }

        return $this->patchEntity($user, $data, [
            'validate' => 'update',
            'accessibleFields' => $accessibleUserFields,
            'associated' => [
                'Profiles' => [
                    'validate' => 'update',
                    'accessibleFields' => $accessibleProfileFields,
                    'associated' => [
                        'Avatars',
                    ],
                ],
            ],
        ]);
    }

    /**
     * Soft delete a user and their associated items
     * Mark user as deleted = true
     * Mark all the user resources only associated with this user as deleted = true
     * Mark all groups where user is sole member as deleted = true
     * Delete all UserGroups association entries
     * Delete all Permissions
     *
     * @param \App\Model\Entity\User $user entity
     * @param array|null $options additional delete options such as ['checkRules' => true]
     * @return bool status
     */
    public function softDelete(User $user, ?array $options = null)
    {
        // Check the delete rules like a normal operation
        if (!isset($options['checkRules'])) {
            $options['checkRules'] = true;
        }
        if ($options['checkRules']) {
            if (!$this->checkRules($user, RulesChecker::DELETE)) {
                return false;
            }
        }

        // find all the resources that only belongs to the user and mark them as deleted
        // Note: all resources that cannot be deleted should have been
        // transferred to other people already (ref. checkRules)
        $resourceIds = $this->Permissions
            ->findAcosOnlyAroCanAccess(PermissionsTable::RESOURCE_ACO, $user->id, ['checkGroupsUsers' => true])
            ->extract('aco_foreign_key')
            ->toArray();
        if (!empty($resourceIds)) {
            /** @var \App\Model\Table\ResourcesTable $Resources */
            $Resources = TableRegistry::getTableLocator()->get('Resources');
            $Resources->softDeleteAll($resourceIds);
        }

        // We do not want empty groups
        // Soft delete all the groups where the user is alone
        // Note that all associated resources are already deleted in previous step
        // ref. findAcosOnlyAroCanAccess checkGroupsUsers = true
        $groupsId = $this->GroupsUsers->findGroupsWhereUserOnlyMember($user->id)->extract('group_id')->toArray();
        if (!empty($groupsId)) {
            $this->Groups->updateAll(['deleted' => true], ['id IN' => $groupsId]);
            $this->Permissions->deleteAll(['aro_foreign_key IN' => $groupsId]);
        }

        // Delete all group memberships
        // Delete all permissions
        $this->GroupsUsers->deleteAll(['user_id' => $user->id]);
        $this->Permissions->deleteAll(['aro_foreign_key' => $user->id]);

        // Delete all secrets
        $Secrets = TableRegistry::getTableLocator()->get('Secrets');
        $Secrets->deleteAll(['user_id' => $user->id]);

        // Delete all favorites
        $Favorites = TableRegistry::getTableLocator()->get('Favorites');
        $Favorites->deleteAll(['user_id' => $user->id]);

        // Mark gpg ke as deleted
        $this->Gpgkeys->updateAll(['deleted' => true], ['user_id' => $user->id]);

        // Mark user as deleted
        $user->deleted = true;
        if (!$this->save($user, ['checkRules' => false])) {
            $msg = __('Could not delete the user {0}, please try again later.', $user->username);
            throw new InternalErrorException($msg);
        }

        return true;
    }

    /**
     * Register a user
     *
     * @param array $data register data
     * @param \App\Utility\UserAccessControl|null $control who is requesting the registration
     * @throws \Cake\Http\Exception\InternalErrorException if there was an issue during the save
     * @throws \App\Error\Exception\ValidationException if the user data do not validate
     * @return \App\Model\Entity\User entity
     */
    public function register(array $data, ?UserAccessControl $control = null): User
    {
        // if role id is empty make it a user
        // Only admins are allowed to set the role
        if (!isset($data['role_id']) || !isset($control) || !$control->isAdmin()) {
            $data['role_id'] = $this->Roles->getIdByName(Role::USER);
        }

        // Force deleted to false. If not set, cakephp will interpret it as null
        // which causes isUnique build rule not to work when looking for duplicate entries.
        $data['deleted'] = false;

        // Check validation rules
        $user = $this->buildEntity($data);
        if (!empty($user->getErrors())) {
            throw new ValidationException(__('Could not validate user data.'), $user, $this);
        }

        // Check business rules
        $this->checkRules($user);
        if (!empty($user->getErrors())) {
            throw new ValidationException(__('Could not validate user data.'), $user, $this);
        }

        // Check for internal error on save
        $user = $this->save($user, ['checkRules' => false]);
        if (!$user) {
            throw new InternalErrorException('Could not save the user, try again later.');
        }

        // Generate an authentication token
        /** @var \App\Model\Table\AuthenticationTokensTable $AuthenticationTokens */
        $AuthenticationTokens = TableRegistry::getTableLocator()->get('AuthenticationTokens');
        $token = $AuthenticationTokens->generate($user->id, AuthenticationToken::TYPE_REGISTER);

        // Generate event data
        $eventData = ['user' => $user, 'token' => $token];
        if (isset($control) && !empty($control->getId())) {
            $eventData['adminId'] = $control->getId();
        }
        $this->dispatchEvent(static::AFTER_REGISTER_SUCCESS_EVENT_NAME, $eventData, $this);

        return $user;
    }
}

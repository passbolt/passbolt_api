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
use App\Model\Dto\EntitiesChangesDto;
use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\Avatar;
use App\Model\Entity\Role;
use App\Model\Entity\User;
use App\Model\Rule\IsNotSoleManagerOfNonEmptyGroupRule;
use App\Model\Rule\IsNotSoleOwnerOfSharedResourcesRule;
use App\Model\Traits\Users\UsersFindersTrait;
use App\Model\Validation\EmailValidationRule;
use App\Utility\UserAccessControl;
use Cake\Core\Configure;
use Cake\Event\Event;
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
 * @method iterable<\App\Model\Entity\User>|iterable<\Cake\Datasource\EntityInterface>|false saveMany(iterable $entities, $options = [])
 * @method iterable<\App\Model\Entity\User>|iterable<\Cake\Datasource\EntityInterface> saveManyOrFail(iterable $entities, $options = [])
 * @method iterable<\App\Model\Entity\User>|iterable<\Cake\Datasource\EntityInterface>|false deleteMany(iterable $entities, $options = [])
 * @method iterable<\App\Model\Entity\User>|iterable<\Cake\Datasource\EntityInterface> deleteManyOrFail(iterable $entities, $options = [])
 * @method \Cake\ORM\Query findById(string $id)
 * @method \Cake\ORM\Query findByUsername(string $username)
 */
class UsersTable extends Table
{
    use UsersFindersTrait;

    public const AFTER_REGISTER_SUCCESS_EVENT_NAME = 'Model.Users.afterRegister.success';
    public const AFTER_SELF_REGISTER_SUCCESS_EVENT_NAME = 'Model.Users.afterSelfRegister.success';
    public const PASSBOLT_SECURITY_USERNAME_CASE_SENSITIVE = 'passbolt.security.username.caseSensitive';
    public const PASSBOLT_SECURITY_USERNAME_LOWER_CASE = 'passbolt.security.username.lowerCase';

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
            ->add('username', 'email', new EmailValidationRule([
                'message' => __('The username should be a valid email address.'),
            ]));

        $validator
            ->boolean('active', __('The active status should be a valid boolean.'));

        $validator
            ->uuid('role_id', __('The role identifier should be a valid UUID.'))
            ->requirePresence('role_id', 'create', __('A role identifier is required.'));

        $validator
            ->boolean('deleted', __('The deleted status should be a valid boolean.'));

        $validator
            ->dateTime('disabled', ['ymd'], __('The disabled date should be a valid date.'))
            ->allowEmptyDateTime('disabled');

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
    public function validationRegister(Validator $validator): Validator
    {
        return $this->validationDefault($validator);
    }

    /**
     * Update validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationUpdate(Validator $validator): Validator
    {
        return $this->validationDefault($validator);
    }

    /**
     * Healthcheck validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationHealthcheck(Validator $validator): Validator
    {
        return $this->validationDefault($validator)->remove('profile');
    }

    /**
     * Register validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationRecover(Validator $validator): Validator
    {
        $validator
            ->requirePresence('username', 'create', __('A username is required.'))
            ->notEmptyString('username', __('The username should not be empty.'))
            ->maxLength('username', 255, __('The username length should be maximum 255 characters.'))
            ->add('username', 'email', new EmailValidationRule([
                'message' => __('The username should be a valid email address.'),
            ]));

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
        $rules->add([$this, 'isUniqueUsername'], 'uniqueUsername', [
            'errorField' => 'username',
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
     * Lower case the username if the username is not case-sensitive
     *
     * @param \Cake\Event\Event $event Event
     * @param \ArrayObject $data data
     * @param \ArrayObject $options options
     * @return void
     */
    public function beforeMarshal(Event $event, \ArrayObject $data, \ArrayObject $options): void
    {
        if ($this->isUsernameLowerCase() && is_string($data['username'] ?? null)) {
            $data['username'] = mb_strtolower($data['username']);
        }
    }

    /**
     * If false (default), username are not case-sensitive: john@passbolt.com and John@passbolt.com cannot be both not-deleted
     * If true, username is case-sensitive: john@passbolt.com and John@passbolt.com can both be not-deleted
     *
     * @return bool
     */
    public function isUsernameCaseSensitive(): bool
    {
        return Configure::read(self::PASSBOLT_SECURITY_USERNAME_CASE_SENSITIVE);
    }

    /**
     * Lower case username before marshaling if true
     *
     * @return bool
     */
    public function isUsernameLowerCase(): bool
    {
        return Configure::read(self::PASSBOLT_SECURITY_USERNAME_LOWER_CASE, true);
    }

    /**
     * Assert that the username is unique among all non-deleted users
     *
     * @param \App\Model\Entity\User $user user being saved
     * @return bool
     * @throws \Cake\Http\Exception\InternalErrorException if the username field is not accessible
     */
    public function isUniqueUsername(User $user): bool
    {
        if (!$user->isNew() && !$user->isDirty('username')) {
            return true;
        }
        if (is_null($user->username)) {
            throw new InternalErrorException('The field username is not accessible.');
        }
        $userExists = $this
                ->findByUsernameCaseAware($user->username)
                ->where(['deleted' => false])
                ->all()->count() > 0;

        return $userExists === false;
    }

    /**
     * Return a user entity
     *
     * @param array $data the request data
     * @throws \InvalidArgumentException if role name is not valid
     * @return \App\Model\Entity\User
     */
    public function buildEntity(array $data): User
    {
        return $this->newEntity(
            $data,
            [
                'validate' => 'register',
                'accessibleFields' => [
                    'username' => true,
                    'deleted' => true,
                    'disabled' => false,
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
     * Edit a given entity with the provided data according to the permission of the current user role
     * Only allow editing the first_name and last_name
     * Also allow editing the role_id but only if admin
     * Other changes such as active or username are not permitted
     *
     * @param \App\Model\Entity\User $user User
     * @param array $data request data
     * @param \App\Utility\UserAccessControl $uac user performing the action
     * @return \App\Model\Entity\User the patched user entity
     */
    public function editEntity(User $user, array $data, UserAccessControl $uac): User
    {
        $accessibleUserFields = [
            'active' => false,
            'deleted' => false,
            'created' => false,
            'disabled' => false,
            'username' => false,
            'role_id' => false,
            'profile' => true,
            'gpgkey' => false,
        ];
        // only admins can set roles
        if ($uac->isAdmin()) {
            $accessibleUserFields['role_id'] = true;
        }
        // only admins can disable users - though not themselves
        if ($uac->isAdmin() && $uac->getId() !== $user->id) {
            $accessibleUserFields['disabled'] = true;
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
     * @return \App\Model\Dto\EntitiesChangesDto|bool The list of entities changes, false if a validation error occurred.
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

        $entitiesChanges = new EntitiesChangesDto();

        // find all the resources that only belongs to the user and mark them as deleted
        // Note: all resources that cannot be deleted should have been
        // transferred to other people already (ref. checkRules)
        $resourceIds = $this->Permissions
            ->findAcosOnlyAroCanAccess(PermissionsTable::RESOURCE_ACO, $user->id, ['checkGroupsUsers' => true])
            ->all()
            ->extract('aco_foreign_key')
            ->toArray();
        if (!empty($resourceIds)) {
            /** @var \App\Model\Table\ResourcesTable $Resources */
            $Resources = TableRegistry::getTableLocator()->get('Resources');
            $Resources->softDeleteAll($resourceIds);
        }

        if (Configure::read('passbolt.plugins.folders.enabled')) {
            // Find all the folders that only belongs to the deleted user and delete them.
            // Note: all folders that cannot be deleted should have been transferred to other people already.
            $foldersIds = $this->Permissions
                ->findAcosOnlyAroCanAccess(PermissionsTable::FOLDER_ACO, $user->id, ['checkGroupsUsers' => true])
                ->all()
                ->extract('aco_foreign_key')
                ->toArray();
            if (!empty($foldersIds)) {
                $foldersTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.Folders');
                $foldersRelationsTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.FoldersRelations');
                $foldersTable->deleteAll(['id IN' => $foldersIds]);
                $foldersRelationsTable->deleteAll(['foreign_id IN' => $foldersIds]);
                $foldersRelationsTable
                    ->updateAll(['folder_parent_id' => null], ['folder_parent_id IN ' => $foldersIds]);
            }
            // Remove all the folders relations of the users.
            $foldersRelationsTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.FoldersRelations');
            $foldersRelationsTable->deleteAll(['user_id' => $user->id]);
        }

        // We do not want empty groups
        // Soft delete all the groups where the user is alone
        // Note that all associated resources are already deleted in previous step
        // ref. findAcosOnlyAroCanAccess checkGroupsUsers = true
        $groupsId = $this->GroupsUsers->findGroupsWhereUserOnlyMember($user->id)
            ->all()
            ->extract('group_id')
            ->toArray();
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
        $secretsToDelete = $Secrets->find()
            ->select(['id', 'user_id', 'resource_id'])
            ->where(['user_id' => $user->id])
            ->all()->toArray();
        $Secrets->deleteMany($secretsToDelete);
        $entitiesChanges->pushDeletedEntities($secretsToDelete);

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

        return $entitiesChanges;
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
            $this->dispatchEvent(static::AFTER_REGISTER_SUCCESS_EVENT_NAME, $eventData, $this);
        } else {
            $this->dispatchEvent(self::AFTER_SELF_REGISTER_SUCCESS_EVENT_NAME, $eventData, $this);
        }

        return $user;
    }
}

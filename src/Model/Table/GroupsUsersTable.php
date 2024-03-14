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

use App\Model\Entity\GroupsUser;
use App\Model\Rule\IsActiveRule;
use App\Model\Rule\IsNotSoftDeletedRule;
use App\Model\Traits\Cleanup\GroupsCleanupTrait;
use App\Model\Traits\Cleanup\TableCleanupTrait;
use App\Model\Traits\Cleanup\UsersCleanupTrait;
use ArrayObject;
use Cake\Event\Event;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validation;
use Cake\Validation\Validator;

/**
 * GroupsUsers Model
 *
 * @property \App\Model\Table\GroupsTable&\Cake\ORM\Association\BelongsTo $Groups
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @method \App\Model\Entity\GroupsUser get($primaryKey, $options = [])
 * @method \App\Model\Entity\GroupsUser newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\GroupsUser[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\GroupsUser|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GroupsUser patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\GroupsUser[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\GroupsUser findOrCreate($search, ?callable $callback = null, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @method \App\Model\Entity\GroupsUser newEmptyEntity()
 * @method \App\Model\Entity\GroupsUser saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method iterable<\App\Model\Entity\GroupsUser>|iterable<\Cake\Datasource\EntityInterface>|false saveMany(iterable $entities, $options = [])
 * @method iterable<\App\Model\Entity\GroupsUser>|iterable<\Cake\Datasource\EntityInterface> saveManyOrFail(iterable $entities, $options = [])
 * @method iterable<\App\Model\Entity\GroupsUser>|iterable<\Cake\Datasource\EntityInterface>|false deleteMany(iterable $entities, $options = [])
 * @method iterable<\App\Model\Entity\GroupsUser>|iterable<\Cake\Datasource\EntityInterface> deleteManyOrFail(iterable $entities, $options = [])
 * @method \Cake\ORM\Query findById(string $id)
 * @method \Cake\ORM\Query findByGroupId(string $groupId)
 * @method \Cake\ORM\Query findByIdAndGroupId(string $id, string $groupId)
 * @method \Cake\ORM\Query findByGroupIdAndUserId(string $groupId, string $userId)
 * @method \Cake\ORM\Query findByGroupIdAndIsAdmin(string $groupId, bool $isAdmin)
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class GroupsUsersTable extends Table
{
    use GroupsCleanupTrait;
    use TableCleanupTrait;
    use UsersCleanupTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('groups_users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Groups');
        $this->belongsTo('Users');
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
            ->uuid('group_id', __('The group identifier should be a valid UUID.'))
            ->requirePresence('group_id', 'create', __('A group identifier is required.'))
            ->notEmptyString('group_id', __('The group identifier should not be empty.'));

        $validator
            ->uuid('user_id', __('The user identifier should be a valid UUID.'))
            ->requirePresence('user_id', 'create', __('A user identifier is required.'))
            ->notEmptyString('user_id', __('The user identifier should not be empty.'));

        $validator
            ->boolean('is_admin', __('The group manager status should be a valid boolean.'))
            ->requirePresence('is_admin', true, __('A group manager status is required.'))
            ->notEmptyString('is_admin', __('The group manager status should not be empty.'));

        return $validator;
    }

    /**
     * Create group validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationSaveGroup(Validator $validator)
    {
        $validator = $this->validationDefault($validator);

        // The group_id is added by cake after the group is created.
        $validator->remove('group_id');

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
        $rules->addCreate($rules->isUnique(['group_id', 'user_id']), 'group_user_unique', [
            'errorField' => 'group_id',
            'message' => __('The user is already member of this group.'),
        ]);
        $rules->addCreate($rules->existsIn(['group_id'], 'Groups'), 'group_exists', [
            'errorField' => 'group_id',
            'message' => __('The group does not exist.'),
        ]);
        $rules->addCreate(new IsNotSoftDeletedRule(), 'group_is_not_soft_deleted', [
            'table' => 'Groups',
            'errorField' => 'group_id',
            'message' => __('The group does not exist.'),
        ]);
        $rules->addCreate($rules->existsIn(['user_id'], 'Users'), 'user_exists', [
            'errorField' => 'user_id',
            'message' => __('The user does not exist.'),
        ]);
        $rules->addCreate(new IsNotSoftDeletedRule(), 'user_is_not_soft_deleted', [
            'table' => 'Users',
            'errorField' => 'user_id',
            'message' => __('The user does not exist.'),
        ]);
        $rules->addCreate(new IsActiveRule(), 'user_is_active', [
            'table' => 'Users',
            'errorField' => 'user_id',
            'message' => __('The user does not exist.'),
        ]);

        return $rules;
    }

    /**
     * Get the list of group id where the user is the sole manager and not the sole member
     * Useful to know if a new group manager need to be appointed when deleting a user
     *
     * @param string $userId user uuid
     * @return \Cake\ORM\Query
     */
    public function findNonEmptyGroupsWhereUserIsSoleManager(string $userId)
    {
        if (!Validation::uuid($userId)) {
            throw new \InvalidArgumentException('The user identifier should be a valid UUID.');
        }

        // R = All the non empty groups where the user given as parameter is the sole manager
        //
        // Details:
        // USER, the user given as parameter
        // GROUPS, all groups that have entries in the groups table
        // GROUPS_USER_IS_SOLE_MANAGER, all groups the user is sole manager
        // GROUPS_USER_ONLY_MEMBER, all groups that have only USER has member
        // R = GROUPS_USER_IS_SOLE_MANAGER - GROUPS_USER_ONLY_MEMBER, all the non empty groups where the user given as parameter is the sole manager

        // GROUPS_USER_ONLY_MEMBER
        $groupsUserOnlyMember = $this->findGroupsWhereUserOnlyMember($userId);

        // (R)
        return $this->findGroupsWhereUserIsSoleManager($userId)
            // GROUPS_USER_IS_SOLE_MANAGER - GROUPS_USER_ONLY_MEMBER
            ->where(['group_id NOT IN' => $groupsUserOnlyMember]);
    }

    /**
     * Get the list of group id where the user is the sole manager and not the sole member
     * Useful to know if a new group manager need to be appointed when deleting a user
     *
     * @param string $userId user uuid
     * @return \Cake\ORM\Query
     */
    public function findGroupsWhereUserIsSoleManager(string $userId)
    {
        if (!Validation::uuid($userId)) {
            throw new \InvalidArgumentException('The user identifier should be a valid UUID.');
        }

        // R = All the groups where the user given as parameter is the sole manager
        //
        // Details:
        // USER, the user given as parameter
        // OTHER_USERS, all users that are not the USER
        // GROUPS, all groups that have entries in the groups table
        // GROUPS_USER_IS_MANAGER, all groups the user is manager
        // GROUPS_OTHER_USER_ARE_MANAGER, all groups that have OTHER_USERS has manager (it can include the groups where the USER is manager)
        // R = GROUPS_USER_IS_MANAGER - GROUPS_OTHER_USER_ARE_MANAGER, all groups that have only the USER has manager

        // (GROUPS_OTHER_USER_ARE_MANAGER)
        // SELECT group_id
        // FROM groups_users
        // WHERE user_id <> USER
        // AND is_admin = true
        $groupsOtherUsersAreManager = $this->find()
            ->select(['group_id'])->distinct()
            ->where([
                'user_id <>' => $userId,
                'is_admin' => true,
            ]);

        // (R)
        // SELECT group_id
        // FROM groups_users
        // WHERE user_id = USER
        // AND is_admin = true
        // AND group_id NOT IN (GROUPS_OTHER_USER_IS_MANAGER)
        return $this->find()
            ->select(['group_id'])
            // R = GROUPS_USER_IS_MANAGER
            ->where([
                'user_id' => $userId,
                'is_admin' => true,
            ])
            // R = R - GROUPS_OTHER_USER_ARE_MANAGER
            ->where(['group_id NOT IN' => $groupsOtherUsersAreManager]);
    }

    /**
     * Get the list of group id where the user is the only member
     * Useful to know which group to delete when deleting a user
     * The user should be the manager at the point but we might as well cast a larger net
     *
     * @param string $userId user uuid
     * @return \Cake\ORM\Query
     */
    public function findGroupsWhereUserOnlyMember(string $userId)
    {
        if (!Validation::uuid($userId)) {
            throw new \InvalidArgumentException('The user identifier should be a valid UUID.');
        }

        // R = All the groups where the user given as parameter is the only member
        //
        // Details:
        // USER, the user given as parameter
        // OTHER_USERS, all users that are not the USER
        // GROUPS, all groups that have entries in the groups table
        // GROUPS_USER_IS_MEMBER, all groups the user is member
        // GROUPS_OTHER_USER_ARE_MEMBER, all groups that have OTHER_USERS has member (it can include the groups where the USER is member)
        // R = GROUPS_USER_IS_MEMBER - GROUPS_OTHER_USER_ARE_MEMBER, all groups that have only the USER has member

        // (GROUPS_OTHER_USER_ARE_MEMBER)
        // SELECT group_id
        // FROM groups_users
        // WHERE user_id <> USER
        $groupsOtherUsers = $this->find()
            ->select(['group_id'])->distinct()
            ->where([
                'user_id <>' => $userId,
            ]);

        // (R)
        // SELECT group_id
        // FROM groups_users
        // WHERE user_id = USER
        // AND group_id NOT IN (GROUPS_OTHER_USER_ARE_MEMBER)
        $query = $this->find()
            ->select(['group_id'])->distinct()
            ->where([
                'user_id' => $userId,
                // GROUPS_USER_IS_MEMBER - GROUPS_OTHER_USER_ARE_MEMBER
                'group_id NOT IN' => $groupsOtherUsers,
            ]);

        return $query;
    }

    /**
     * Get the list of group id where the user is not the only member
     * Useful to know which group to delete when deleting a user
     *
     * @param string $userId user uuid
     * @return \Cake\ORM\Query
     */
    public function findGroupsWhereUserNotOnlyMember(string $userId)
    {
        if (!Validation::uuid($userId)) {
            throw new \InvalidArgumentException('The user identifier should be a valid UUID.');
        }

        // R = All the groups where the user given as parameter is the only member
        //
        // Details:
        // USER, the user given as parameter
        // GROUPS, all groups that have entries in the groups table
        // GROUPS_USER_ONLY_MEMBER, all groups the user is only member
        // GROUPS_USER_IS_MEMBER, all groups the user is member
        // R = GROUPS_USER_IS_MEMBER - GROUPS_USER_ONLY_MEMBER, all non empty groups that have the USER has member

        // (GROUPS_USER_ONLY_MEMBER)
        $groupsUserOnlyMember = $this->findGroupsWhereUserOnlyMember($userId);

        // (R)
        // SELECT group_id
        // FROM groups_users
        // WHERE user_id = USER
        // AND group_id NOT IN (GROUPS_USER_ONLY_MEMBER)
        $query = $this->find()
            ->select(['group_id'])->distinct()
            ->where([
                'user_id' => $userId,
                // GROUPS_USER_IS_MEMBER - GROUPS_USER_ONLY_MEMBER
                'group_id NOT IN' => $groupsUserOnlyMember,
            ]);

        return $query;
    }

    /**
     * Check if the given user is the manager of a given group
     *
     * @param string $userId uuid
     * @param string $groupId uuid
     * @return bool true if user is marked as admin
     */
    public function isManager(string $userId, string $groupId)
    {
        if (!Validation::uuid($userId) || !Validation::uuid($groupId)) {
            return false;
        }
        $user = $this->find()
            ->select(['group_id'])
            ->where([
                'user_id' => $userId,
                'group_id' => $groupId,
                'is_admin' => true,
            ])->first();

        return !empty($user);
    }

    /**
     * Return a groupUser entity.
     *
     * @param array $data entity data
     * @return \App\Model\Entity\GroupsUser
     */
    public function buildEntity(array $data): GroupsUser
    {
        if (!isset($data['is_admin'])) {
            $data['is_admin'] = false;
        }

        return $this->newEntity($data, [
            'accessibleFields' => [
                'group_id' => true,
                'user_id' => true,
                'is_admin' => true,
                'created' => true,
                'created_by' => true,
            ],
        ]);
    }

    /**
     * Event fired before request data is converted into entities
     * - On create, if not defined set is_admin to false
     *
     * @param \Cake\Event\Event $event event
     * @param \ArrayObject $data data
     * @param \ArrayObject $options options
     * @return void
     */
    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)
    {
        if (!isset($data['is_admin'])) {
            $data['is_admin'] = false;
        }
    }

    /**
     * Delete all groups_users records where groups are soft deleted
     *
     * @param bool $dryRun false
     * @return int Number of affected records
     */
    public function cleanupSoftDeletedGroups($dryRun = false)
    {
        return $this->cleanupSoftDeleted('Groups', $dryRun);
    }

    /**
     * Delete all groups_users records where groups are deleted
     *
     * @param bool $dryRun false
     * @return int Number of affected records
     */
    public function cleanupHardDeletedGroups($dryRun = false)
    {
        return $this->cleanupHardDeleted('Groups', $dryRun);
    }

    /**
     * Delete duplicated groups users
     *
     * @param bool $dryRun false
     * @return int of affected records
     */
    public function cleanupDuplicatedGroupsUsers(?bool $dryRun = false): int
    {
        $keys = ['group_id', 'user_id'];

        return $this->cleanupDuplicates($keys, $dryRun);
    }
}

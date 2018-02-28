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
use App\Model\Rule\IsActiveRule;
use App\Model\Rule\IsNotSoftDeletedRule;
use App\Model\Traits\Cleanup\GroupsCleanupTrait;
use App\Model\Traits\Cleanup\TableCleanupTrait;
use App\Model\Traits\Cleanup\UsersCleanupTrait;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Hash;
use Cake\Validation\Validation;
use Cake\Validation\Validator;

/**
 * GroupsUsers Model
 *
 * @property \App\Model\Table\GroupsTable|\Cake\ORM\Association\BelongsTo $Groups
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\GroupsUser get($primaryKey, $options = [])
 * @method \App\Model\Entity\GroupsUser newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\GroupsUser[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\GroupsUser|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GroupsUser patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\GroupsUser[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\GroupsUser findOrCreate($search, callable $callback = null, $options = [])
 *
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
    public function initialize(array $config)
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
    public function validationDefault(Validator $validator)
    {
        $validator
            ->uuid('id')
            ->allowEmpty('id', 'create');

        $validator
            ->uuid('group_id')
            ->requirePresence('group_id', 'create')
            ->notEmpty('group_id');

        $validator
            ->uuid('user_id')
            ->requirePresence('user_id', 'create')
            ->notEmpty('user_id');

        $validator
            ->boolean('is_admin')
            ->notEmpty('is_admin');

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
    public function buildRules(RulesChecker $rules)
    {
        $rules->addCreate($rules->isUnique(['group_id', 'user_id']), 'group_user_unique', [
            'errorField' => 'group_id',
            'message' => __('The user is already member of this group.')
        ]);
        $rules->addCreate($rules->existsIn(['group_id'], 'Groups'), 'group_exists', [
            'errorField' => 'group_id',
            'message' => __('The group does not exist.')
        ]);
        $rules->addCreate(new IsNotSoftDeletedRule(), 'group_is_not_soft_deleted', [
            'table' => 'Groups',
            'errorField' => 'group_id',
            'message' => __('The group does not exist.')
        ]);
        $rules->addCreate($rules->existsIn(['user_id'], 'Users'), 'user_exists', [
            'errorField' => 'user_id',
            'message' => __('The user does not exist.')
        ]);
        $rules->addCreate(new IsNotSoftDeletedRule(), 'user_is_not_soft_deleted', [
            'table' => 'Users',
            'errorField' => 'user_id',
            'message' => __('The user does not exist.')
        ]);
        $rules->addCreate(new IsActiveRule(), 'user_is_active', [
            'table' => 'Users',
            'errorField' => 'user_id',
            'message' => __('The user does not exist.')
        ]);

        return $rules;
    }

    /**
     * Get the list of group id where the user is the sole manager and not the sole member
     * Useful to know if a new group manager need to be appointed when deleting a user
     *
     * @param string $userId user uuid
     * @return array of group uuid
     */
    public function findNonEmptyGroupsWhereUserIsSoleManager(string $userId)
    {
        if (!Validation::uuid($userId)) {
            throw new \InvalidArgumentException(__('The user id should be a valid uuid.'));
        }

        // SELECT group_id AS `group_id`,
        //      (SUM(is_admin)) AS `count_admin`,
        //      (COUNT(user_id)) AS `count_user`
        // FROM groups_users
        // WHERE group_id IN (
        //      SELECT group_id
        //      FROM groups_users
        //      WHERE (user_id = $user_id AND is_admin=1)
        // )
        // GROUP BY group_id
        // HAVING count_admin=1 AND count_user > 1;

        $subquery = $this->find();
        $subquery
            ->select(['group_id'])
            ->where([
                'user_id' => $userId,
                'is_admin' => true
            ]);

        $query = $this->find();
        $query
            ->select([
                'group_id' => 'group_id',
                'count_admin' => $query->func()->sum('is_admin'),
                'count_user' => $query->func()->count('user_id')
            ])
            ->where(['group_id IN' => $subquery])
            ->group('group_id')
            ->having(['count_admin' => 1, 'count_user >' => 1]);

        $result = $query->all()->toArray();
        $result = Hash::extract($result, '{n}.group_id');

        return $result;
    }

    /**
     * Get the list of group id where the user is the sole manager and not the sole member
     * Useful to know if a new group manager need to be appointed when deleting a user
     *
     * @param string $userId user uuid
     * @return array of group uuid
     */
    public function findGroupsWhereUserIsSoleManager(string $userId)
    {
        if (!Validation::uuid($userId)) {
            throw new \InvalidArgumentException(__('The user id should be a valid uuid.'));
        }

        // SELECT group_id AS `group_id`,
        //      (SUM(is_admin)) AS `count_admin`
        // FROM groups_users
        // WHERE group_id IN (
        //      SELECT group_id
        //      FROM groups_users
        //      WHERE (user_id = $user_id AND is_admin=1)
        // )
        // GROUP BY group_id
        // HAVING count_admin=1;

        $subquery = $this->find();
        $subquery
            ->select(['group_id'])
            ->where([
                'user_id' => $userId,
                'is_admin' => true
            ]);

        $query = $this->find();
        $query
            ->select([
                'group_id' => 'group_id',
                'count_admin' => $query->func()->sum('is_admin')
            ])
            ->where(['group_id IN' => $subquery])
            ->group('group_id')
            ->having(['count_admin' => 1]);

        $result = $query->all()->toArray();
        $result = Hash::extract($result, '{n}.group_id');

        return $result;
    }

    /**
     * Get the list of group id where the user is the only member
     * Useful to know which group to delete when deleting a user
     * The user should be the manager at the point but we might as well cast a larger net
     *
     * @param string $userId user uuid
     * @return array of group uuid
     */
    public function findGroupsWhereUserOnlyMember(string $userId)
    {
        if (!Validation::uuid($userId)) {
            throw new \InvalidArgumentException(__('The user id should be a valid uuid.'));
        }

        // SELECT group_id AS `group_id`,
        //      (COUNT(user_id)) AS `count_user`
        // FROM groups_users
        // WHERE group_id IN (
        //      SELECT group_id
        //      FROM groups_users
        //      WHERE (user_id = $user_id)
        // )
        // GROUP BY group_id
        // HAVING count_user=1;

        $subquery = $this->find();
        $subquery
            ->select(['group_id'])
            ->where([
                'user_id' => $userId
            ]);

        $query = $this->find();
        $query
            ->select([
                'group_id' => 'group_id',
                'count_user' => $query->func()->count('user_id')
            ])
            ->where(['group_id IN' => $subquery])
            ->group('group_id')
            ->having(['count_user' => 1]);

        $result = $query->all()->toArray();
        $result = Hash::extract($result, '{n}.group_id');

        return $result;
    }

    /**
     * Get the list of group id where the user is not the only member
     * Useful to know which group to delete when deleting a user
     *
     * @param string $userId user uuid
     * @return array of group uuid
     */
    public function findGroupsWhereUserNotOnlyMember(string $userId)
    {
        if (!Validation::uuid($userId)) {
            throw new \InvalidArgumentException(__('The user id should be a valid uuid.'));
        }

        // SELECT group_id AS `group_id`,
        //      (COUNT(user_id)) AS `count_user`
        // FROM groups_users
        // WHERE group_id IN (
        //      SELECT group_id
        //      FROM groups_users
        //      WHERE (user_id = $user_id)
        // )
        // GROUP BY group_id
        // HAVING count_user>1;

        $subquery = $this->find();
        $subquery
            ->select(['group_id'])
            ->where([
                'user_id' => $userId
            ]);

        $query = $this->find();
        $query
            ->select([
                'group_id' => 'group_id',
                'count_user' => $query->func()->count('user_id')
            ])
            ->where(['group_id IN' => $subquery])
            ->group('group_id')
            ->having(['count_user >' => 1]);

        $result = $query->all()->toArray();
        $result = Hash::extract($result, '{n}.group_id');

        return $result;
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
                'is_admin' => true
            ])->first();

        return (!empty($user));
    }

    /**
     * Patch a list of group_user entities with a list of changes.
     * A change is formatted as following :
     *
     * - Add a new group user:
     * [
     *   'user_id' => string,
     *   'is_admin' => bool
     * ]
     *
     * - Update a group user:
     * [
     *   'id' => uuid,
     *   'is_admin' => bool
     * ]
     *
     * - Delete a group user
     * [
     *   'id' => uuid,
     *   'delete' => boolean
     * ]
     *
     * @param array $entities The list of groups users entities to patch
     * @param array $changes The changes to apply
     * @param null $groupId The group identifier that the entities belong to
     * @param array $options A list of options to apply to the operations
     *
     *  Allowed operations:
     *  Define which operations are allowed when patching the entities, by default none of them are allowed.
     *  [
     *    'allowedOperations' => [
     *      'add' => true,
     *      'update' => true
     *      'delete' => true,
     *  ]
     *
     * @throw ValidationRuleException If a change try to modify a group user that is not in the list of groups users
     * @throw ValidationRuleException If a change does not validate when calling patchEntity
     * @throw ValidationRuleException If a change does not validate when calling newEntity
     * @return array The list of groups users entities patched with the changes
     */
    public function patchEntitiesWithChanges($entities = [], $changes = [], $groupId = null, array $options = [])
    {
        // What operations are allowed.
        $canAdd = Hash::get($options, 'allowedOperations.add', false);
        $canUpdate = Hash::get($options, 'allowedOperations.update', false);
        $canDelete = Hash::get($options, 'allowedOperations.delete', false);

        // Apply the changes to the list of entities.
        foreach ($changes as $changeKey => $change) {
            // Update or Delete case.
            if (isset($change['id'])) {
                // Retrieve the group_user a change is requested for.
                $groupUserKey = null;
                foreach ($entities as $groupUserKey => $entity) {
                    if ($entity['id'] == $change['id']) {
                        break;
                    }
                }
                // The groupUserKey does not belong to the group.
                if (is_null($groupUserKey)) {
                    $errors = ['id' => [
                        'group_user_exists' => __('The membership does not exist.', $change['id'])
                    ]];
                    throw new ValidationRuleException(__('Validation error.'), [$changeKey => $errors]);
                }

                // Delete case.
                if ($canDelete && isset($change['delete']) && $change['delete']) {
                    unset($entities[$groupUserKey]);
                } elseif ($canUpdate) {
                    // Update case
                    $options = ['accessibleFields' => ['is_admin' => true]];
                    $this->patchEntity($entities[$groupUserKey], $change, $options);
                    $errors = $entities[$groupUserKey]->getErrors();
                    if (!empty($errors)) {
                        throw new ValidationRuleException(__('Validation error.'), [$changeKey => $errors]);
                    }
                }
            } elseif ($canAdd) {
                // Add case.
                // Enforce data.
                $change['group_id'] = $groupId;
                // New entity options.
                $options = ['accessibleFields' => [
                    'group_id' => true,
                    'user_id' => true,
                    'is_admin' => true,
                ]];
                // Create and validate the new group_user entity.
                $groupUser = $this->newEntity($change, $options);
                $errors = $groupUser->getErrors();
                if (!empty($errors)) {
                    throw new ValidationRuleException(__('Validation error.'), [$changeKey => $errors]);
                }
                $entities[] = $groupUser;
            }
        }

        return $entities;
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
    public function beforeMarshal(\Cake\Event\Event $event, \ArrayObject $data, \ArrayObject $options)
    {
        if (!isset($data['is_admin'])) {
            $data['is_admin'] = false;
        }
    }

    /**
     * Delete all groups_users records where groups are soft deleted
     *
     * @param bool $dryRun false
     * @return number of affected records
     */
    public function cleanupSoftDeletedGroups($dryRun = false)
    {
        return $this->cleanupSoftDeleted('Groups', $dryRun);
    }

    /**
     * Delete all groups_users records where groups are deleted
     *
     * @param bool $dryRun false
     * @return number of affected records
     */
    public function cleanupHardDeletedGroups($dryRun = false)
    {
        return $this->cleanupHardDeleted('Groups', $dryRun);
    }
}

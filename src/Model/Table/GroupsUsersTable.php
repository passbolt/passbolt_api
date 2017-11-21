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

use App\Model\Rule\IsNotSoftDeletedRule;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Hash;
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
            ->allowEmpty('is_admin', 'create');

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

        return $rules;
    }

    /**
     * Get the list of group id where the user is the sole manager
     * Useful because we do not want to have a group without manager by deleting a user
     *
     * @param string $userId user uuid
     * @return array
     */
    public function findGroupsWhereUserIsSoleManager($userId)
    {
        // SELECT group_id AS `group_id`, (COUNT(is_admin)) AS `count_admin`
        // FROM groups_users GroupsUsers
        // WHERE (is_admin= = :c0 AND group_id in(
        //      SELECT GroupsUsers.group_id AS `GroupsUsers__group_id`
        //      FROM groups_users GroupsUsers
        //      WHERE (user_id = :c1 AND is_admin = :c2))
        // ) GROUP BY group_id
        // HAVING count_admin= :c3

        $subquery = $this->find();
        $subquery->select(['group_id'])
            ->where([
                'user_id' => $userId,
                'is_admin' => true
            ]);

        $query = $this->find();
        $query
            ->select([
                'group_id' => 'group_id',
                'count_admin' => $query->func()->count('is_admin')
            ])
            ->where(['is_admin' => 1, 'group_id IN' => $subquery])
            ->group('group_id')
            ->having(['count_admin' => 1]);

        $result = $query->all()->toArray();
        $result = Hash::extract($result, '{n}.group_id');

        return $result;
    }
}

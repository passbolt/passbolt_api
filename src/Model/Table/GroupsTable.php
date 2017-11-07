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

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validation;
use Cake\Validation\Validator;

/**
 * Groups Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsToMany $Users
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\HasMany $GroupsUsers
 * @property \App\Model\Table\SecretsTable|\Cake\ORM\Association\HasOne $Modifier
 *
 * @method \App\Model\Entity\Group get($primaryKey, $options = [])
 * @method \App\Model\Entity\Group newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Group[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Group|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Group patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Group[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Group findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class GroupsTable extends Table
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

        $this->setTable('groups');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasOne('Modifier', [
            'className' => 'Users',
            'bindingKey' => 'modified_by',
            'foreignKey' => 'id'
        ]);

        $this->hasMany('GroupsUsers');

        $this->belongsToMany('Users', [
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
            ->scalar('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('name')
            ->notEmpty('name', 'create');

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
        // Add create rules.
        $rules->addCreate(
            $rules->isUnique(
                ['name'],
                __('The group name provided is already used by another group.')
            ),
            'group_unique'
        );
        $rules->addCreate([$this, 'ruleAtLeastOneAdmin'], 'at_least_one_admin', [
            'errorField' => 'groups_users',
            'message' => __('A group manager must be provided.')
        ]);

        return $rules;
    }

    /**
     * Validate that the a group can be created only if at least one admin is provided.
     *
     * @param \App\Model\Entity\Group $entity The entity that will be created.
     * @param array $options options
     * @return bool
     */
    public function ruleAtLeastOneAdmin($entity, array $options = [])
    {
        $adminUsers = [];
        if (isset($entity->groups_users)) {
            $adminUsers = Hash::extract($entity->groups_users, '{n}[is_admin=true]');
        }

        return !empty($adminUsers);
    }

    /**
     * Build the query that fetches data for group index
     *
     * @param array $options options
     * @return \Cake\ORM\Query
     */
    public function findIndex(array $options = [])
    {
        $query = $this->find();

        // If contains modifier.
        if (isset($options['contain']['modifier'])) {
            $query->contain('Modifier');
        }

        // If contains user.
        if (isset($options['contain']['user'])) {
            $query->contain('Users');
        }

        // Filter on groups that have specified users.
        if (isset($options['filter']['has-users']) && is_array($options['filter']['has-users'])) {
            $query = $this->_filterQueryByGroupsUsers($query, $options['filter']['has-users']);
        }

        // Filter on groups that have specified managers.
        if (isset($options['filter']['has-managers']) && is_array($options['filter']['has-managers'])) {
            $query = $this->_filterQueryByGroupsUsers($query, $options['filter']['has-managers'], true);
        }

        // Filter out deleted groups
        $query->where(['Groups.deleted' => false]);

        return $query;
    }

    /**
     * Filter a Groups query by groups users.
     *
     * @param \Cake\ORM\Query $query The query to augment.
     * @param array<string> $usersIds The users to filter the query on.
     * @param bool $areManager (optional) Should the users be managers ? Default false.
     * @return $query
     */
    private function _filterQueryByGroupsUsers($query, array $usersIds, $areManager = false)
    {
        // If there is only one user use a left join
        if (count($usersIds) == 1) {
            $query->leftJoinWith('GroupsUsers');
            $query->where(['GroupsUsers.user_id' => $usersIds[0]]);

            return $query;
        }

        // Find all the groups that have the given users.
        $GroupsUsers = TableRegistry::get('GroupsUsers');
        $subQuery = $GroupsUsers->find()
            ->select([
                'GroupsUsers.group_id',
                'count' => $query->func()->count('GroupsUsers.group_id')
            ])
            ->where([
                'GroupsUsers.user_id IN' => $usersIds
            ])
            ->group('GroupsUsers.group_id')
            ->having(['count' => count($usersIds)]);

        // If we want to retrieve only managers.
        if ($areManager) {
            $subQuery->where(['GroupsUsers.is_admin' => true]);
        }

        // Execute the sub query and extract the groups ids.
        $matchingGroupsIds = Hash::extract($subQuery->toArray(), '{n}.group_id');

        // Filter the query.
        if (empty($matchingGroupsIds)) {
            // @TODO If no group contains all the users, the main request should return nothing. Find an elegant way to do it.
            $query->where(['true' => false]);
        } else {
            $query->where(['Groups.id IN' => $matchingGroupsIds]);
        }

        return $query;
    }

    /**
     * Build the query that fetches data for group view
     *
     * @param string $groupId The group to retrieve
     * @param array $options options
     * @throws \InvalidArgumentException if the groupId parameter is not a valid uuid.
     * @return \Cake\ORM\Query
     */
    public function findView($groupId, array $options = [])
    {
        if (!Validation::uuid($groupId)) {
            throw new \InvalidArgumentException(__('The parameter groupId should be a valid uuid.'));
        }

        $query = $this->findIndex($options);
        $query->where(['Groups.id' => $groupId]);

        return $query;
    }

    /**
     * Event fired before request data is converted into entities
     * - On created, set not deleted to false
     *
     * @param \Cake\Event\Event $event event
     * @param \ArrayObject $data data
     * @param \ArrayObject $options options
     * @return void
     */
    public function beforeMarshal(\Cake\Event\Event $event, \ArrayObject $data, \ArrayObject $options)
    {
        // @todo when saving a new group the validate option is set to default.
        if (isset($options['validate']) && $options['validate'] === 'default') {
            $data['deleted'] = false;
        }
    }
}

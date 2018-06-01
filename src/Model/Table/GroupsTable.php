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
use App\Model\Rule\IsNotSoleOwnerOfSharedResourcesRule;
use Cake\Network\Exception\InternalErrorException;
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
        $this->hasMany('GroupsUsers', [
            'saveStrategy' => 'replace'
        ]);
        $this->hasOne('MyGroupUser', [
            'className' => 'GroupsUsers'
        ]);
        $this->hasMany('Permissions', [
            'foreignKey' => 'aro_foreign_key'
        ]);
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
            ->uuid('id')
            ->allowEmpty('id', 'create');

        $validator
            ->utf8Extended('name', __('The name is not a valid utf8 string.'))
            ->lengthBetween('name', [0, 255], __('The name length should be maximum {0} characters.', 255))
            ->requirePresence('name', 'create', __('A name is required.'))
            ->notEmpty('name', __('The name cannot be empty.'));

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
                ['name', 'deleted'],
                __('The name provided is already used by another group.')
            ),
            'group_unique'
        );
        $rules->addCreate([$this, 'atLeastOneAdminRule'], 'at_least_one_admin', [
            'errorField' => 'groups_users',
            'message' => __('A group manager must be provided.')
        ]);

        // Update rules.
        $rules->addUpdate(
            $rules->isUnique(
                ['name', 'deleted'],
                __('The name provided is already used by another group.')
            ),
            'group_unique'
        );
        $rules->addUpdate([$this, 'atLeastOneAdminRule'], 'at_least_one_admin', [
            'errorField' => 'groups_users',
            'message' => __('A group manager must be provided.')
        ]);
        $rules->addUpdate(new IsNotSoftDeletedRule(), 'group_is_not_soft_deleted', [
            'table' => 'Groups',
            'errorField' => 'id',
            'message' => __('The group cannot be soft deleted.')
        ]);

        // Delete rules
        $rules->addDelete(new IsNotSoleOwnerOfSharedResourcesRule(), 'soleOwnerOfSharedResource', [
            'errorField' => 'id',
            'message' => __('You need to transfer the ownership for the shared passwords owned by this user before deleting this user.')
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
    public function atLeastOneAdminRule(\App\Model\Entity\Group $entity, array $options = [])
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

        // If contains modifier profile.
        if (isset($options['contain']['modifier.profile'])) {
            $query->contain('Modifier.Profiles');
        }

        // If contains user.
        if (isset($options['contain']['user'])) {
            $query->contain('Users');
        }

        // If contains user_group_user.
        if (isset($options['contain']['my_group_user'])) {
            $query->contain('MyGroupUser', function ($q) use ($options) {
                return $q->where(['MyGroupUser.user_id' => $options['my_user_id']]);
            });
        }

        // If contains group_user.
        if (isset($options['contain']['group_user'])) {
            $query->contain('GroupsUsers');
        }

        // If contains group_user user.
        if (isset($options['contain']['group_user.user'])) {
            $query->contain('GroupsUsers.Users');
        }

        // If contains user profile.
        if (isset($options['contain']['group_user.user.profile'])) {
            $query->contain('GroupsUsers.Users.Profiles');
        }

        // If contains user gpgkey.
        if (isset($options['contain']['group_user.user.gpgkey'])) {
            $query->contain('GroupsUsers.Users.Gpgkeys');
        }

        // If contains user_count.
        if (isset($options['contain']['user_count'])) {
            $query = $this->_containUserCount($query);
        }

        // Filter on groups that have specified users.
        if (isset($options['filter']['has-users']) && is_array($options['filter']['has-users'])) {
            $query = $this->_filterQueryByGroupsUsers($query, $options['filter']['has-users']);
        }

        // Filter on groups that have specified managers.
        if (isset($options['filter']['has-managers']) && is_array($options['filter']['has-managers'])) {
            $query = $this->_filterQueryByGroupsUsers($query, $options['filter']['has-managers'], true);
        }

        // Filter on groups that do not have a direct permission for a resource
        if (isset($options['filter']['has-not-permission']) && count($options['filter']['has-not-permission'])) {
            $query = $this->_filterQueryByHasNotPermission($query, $options['filter']['has-not-permission'][0]);
        }

        // If searching for a name
        if (isset($options['filter']['search']) && count($options['filter']['search'])) {
            $query = $this->_filterQueryBySearch($query, $options['filter']['search'][0]);
        }

        // Filter out deleted groups
        $query->where(['Groups.deleted' => false]);

        // Ordering options
        if (isset($options['order'])) {
            $query->order($options['order']);
        }

        return $query;
    }

    /**
     * Count the members of the groups and add the value to the field user_count.
     *
     * @param \Cake\ORM\Query $query The query to augment.
     * @return \Cake\ORM\Query
     */
    private function _containUserCount(\Cake\ORM\Query $query)
    {
        // Count the members of the groups in a subquery.
        $subQuery = $this->association('GroupsUsers')->find();
        $subQuery->select(['count' => $subQuery->func()->count('*')])
            ->where(['GroupsUsers.group_id = Groups.id']);

        // Add the user_count field to the Groups query.
        $query->select(['user_count' => $subQuery])
            ->enableAutoFields();

        return $query;
    }

    /**
     * Filter a Groups query by groups users.
     *
     * @param \Cake\ORM\Query $query The query to augment.
     * @param array <string> $usersIds The users to filter the query on.
     * @param bool $areManager (optional) Should the users be managers ? Default false.
     * @return \Cake\ORM\Query
     */
    private function _filterQueryByGroupsUsers(\Cake\ORM\Query $query, array $usersIds, bool $areManager = false)
    {
        // If there is only one user use a left join
        if (count($usersIds) == 1) {
            $query->leftJoinWith('GroupsUsers');
            $query->where(['GroupsUsers.user_id' => $usersIds[0]]);
            // If we want to retrieve only managers.
            if ($areManager) {
                $query->where(['GroupsUsers.is_admin' => true]);
            }

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

        $matchingGroupsIds = $subQuery->extract('group_id')
            ->toArray();

        // Filter the query.
        if (empty($matchingGroupsIds)) {
            // If no group contains all the users, the main request should return nothing
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
    public function findView(string $groupId, array $options = [])
    {
        if (!Validation::uuid($groupId)) {
            throw new \InvalidArgumentException(__('The parameter groupId should be a valid uuid.'));
        }

        return $this->findIndex($options)
            ->where(['Groups.id' => $groupId]);
    }

    /**
     * Get a list of groups matching a given list of group ids
     *
     * @param array $groupsIds array of groups uuids
     * @return \Cake\ORM\Query
     */
    public function findAllByIds(array $groupsIds)
    {
        if (empty($groupsIds)) {
            throw new \InvalidArgumentException(__('The parameter groupIds cannot be empty.'));
        }
        foreach ($groupsIds as $groupId) {
            if (!Validation::uuid($groupId)) {
                throw new \InvalidArgumentException(__('The group id should be a valid uuid.'));
            }
        }

        return $this->findIndex()
            ->where(['Groups.id IN' => $groupsIds])
            ->all();
    }

    /**
     * Filter a Groups query by groups that don't have permission for a resource.
     *
     * By instance :
     * $query = $Users->find();
     * $Users->_filterQueryByHasNotPermission($query, 'ada');
     *
     * Should filter all the users that do not have a permission for apache.
     *
     * @param \Cake\ORM\Query $query The query to augment.
     * @param string $resourceId The resource to search potential groups for.
     * @return \Cake\ORM\Query $query
     */
    private function _filterQueryByHasNotPermission(\Cake\ORM\Query $query, string $resourceId)
    {
        $permissionQuery = $this->association('Permissions')
            ->find()
            ->select(['Permissions.aro_foreign_key'])
            ->where([
                'Permissions.aro' => 'Group',
                'Permissions.aco_foreign_key' => $resourceId
            ]);

        // Filter on the groups that do not have yet a permission.
        return $query->where(['Groups.id NOT IN' => $permissionQuery]);
    }

    /**
     * Filter a Groups query by search.
     * Search on the name field.
     *
     * By instance :
     * $query = $Groups->find();
     * $Groups->_filterQueryBySearch($query, 'creative');
     *
     * Should filter all the groups with a name containing creative.
     *
     * @param \Cake\ORM\Query $query The query to augment.
     * @param string $search The string to search.
     * @return \Cake\ORM\Query $query
     */
    private function _filterQueryBySearch(\Cake\ORM\Query $query, string $search)
    {
        $search = '%' . $search . '%';

        return $query->where(['Groups.name LIKE' => $search]);
    }

    /**
     * Soft delete a group and their associated items
     * Mark group as deleted = true
     * Mark all the group resources only associated with this group as deleted = true
     * Delete all UserGroups association entries
     * Delete all Permissions associated with this group
     *
     * @throws \InvalidArgumentException if $group is not a valid group entity
     * @param \App\Model\Entity\Group $group entity
     * @param array $options additional delete options such as ['checkRules' => true]
     * @return bool status
     */
    public function softDelete(\App\Model\Entity\Group $group, array $options = null)
    {
        // Check the delete rules like a normal operation
        if (!isset($options['checkRules'])) {
            $options['checkRules'] = true;
        }
        if ($options['checkRules']) {
            if (!$this->checkRules($group, RulesChecker::DELETE)) {
                return false;
            }
        }

        // find all the resources that only belongs to the group and mark them as deleted
        // Note: all resources that cannot be deleted should have been
        // transferred to other people already (ref. delete checkRules)
        $Permissions = TableRegistry::get('Permissions');
        $resourceIds = $Permissions->findResourcesOnlyGroupCanAccess($group->id);
        if (!empty($resourceIds)) {
            $Resources = TableRegistry::get('Resources');
            $Resources->softDeleteAll($resourceIds);
        }

        // Delete all group memberships
        $this->GroupsUsers->deleteAll(['group_id' => $group->id]);

        // Delete all permissions
        // Delete all the secrets that lost permissions in the process
        $Permissions->deleteAll(['aro_foreign_key' => $group->id]);
        $Secrets = TableRegistry::get('Secrets');
        $Secrets->cleanupHardDeletedPermissions();

        // Mark group as deleted
        $group->deleted = true;
        if (!$this->save($group, ['checkRules' => false, 'validate' => false])) {
            $msg = __('Could not delete the group {0}, please try again later.', $group->name);
            throw new InternalErrorException($msg);
        }

        return true;
    }
}

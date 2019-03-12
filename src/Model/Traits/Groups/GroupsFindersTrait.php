<?php
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
namespace App\Model\Traits\Groups;

use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;

trait GroupsFindersTrait
{
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
    private function _filterQueryByHasNotPermission(Query $query, string $resourceId)
    {
        $permissionQuery = $this->getAssociation('Permissions')
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
    private function _filterQueryBySearch(Query $query, string $search)
    {
        $search = '%' . $search . '%';

        return $query->where(['Groups.name LIKE' => $search]);
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
    private function _containUserCount(Query $query)
    {
        // Count the members of the groups in a subquery.
        $subQuery = $this->getAssociation('GroupsUsers')->find();
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
    private function _filterQueryByGroupsUsers(Query $query, array $usersIds, bool $areManager = false)
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
        $GroupsUsers = TableRegistry::getTableLocator()->get('GroupsUsers');
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
     * @param array $options array of options
     *
     * @return \Cake\ORM\Query
     */
    public function findAllByIds(array $groupsIds, array $options = [])
    {
        if (empty($groupsIds)) {
            throw new \InvalidArgumentException(__('The parameter groupIds cannot be empty.'));
        }
        foreach ($groupsIds as $groupId) {
            if (!Validation::uuid($groupId)) {
                throw new \InvalidArgumentException(__('The group id should be a valid uuid.'));
            }
        }

        return $this->findIndex($options)
            ->where(['Groups.id IN' => $groupsIds]);
    }
}

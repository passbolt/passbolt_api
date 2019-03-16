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
namespace App\Model\Traits\Users;

use App\Model\Entity\Role;
use App\Model\Table\AvatarsTable;
use Cake\Core\Configure;
use Cake\ORM\Query;
use Cake\Utility\Hash;
use Cake\Validation\Validation;

trait UsersFindersTrait
{

    /**
     * Filter a Groups query by groups users.
     *
     * @param \Cake\ORM\Query $query The query to augment.
     * @param array<string> $groupsIds The users to filter the query on.
     * @param bool $areManager (optional) Should the users be only managers ? Default false.
     * @return \Cake\ORM\Query $query
     */
    private function _filterQueryByGroupsUsers(Query $query, array $groupsIds, bool $areManager = false)
    {
        // If there is only one group use a left join
        if (count($groupsIds) == 1) {
            $query->leftJoinWith('GroupsUsers');
            $query->where(['GroupsUsers.group_id' => $groupsIds[0]]);
            if ($areManager) {
                $query->where(['GroupsUsers.is_admin' => true]);
            }

            return $query;
        }

        // Otherwise use a subquery to find all the users that are members of all the listed groups
        $subQuery = $this->GroupsUsers->find()
            ->select([
                'GroupsUsers.user_id',
                'count' => $query->func()->count('GroupsUsers.user_id')
            ])
            ->where(['GroupsUsers.group_id IN' => $groupsIds])
            ->group('GroupsUsers.user_id')
            ->having(['count' => count($groupsIds)]);

        // Execute the sub query and extract the user ids.
        $matchingUserIds = Hash::extract($subQuery->toArray(), '{n}.user_id');

        // Filter the query.
        if (empty($matchingUserIds)) {
            // if no user match all groups it should return nobody
            $query->where(['true' => false]);
        } else {
            $query->where(['Users.id IN' => $matchingUserIds]);
        }

        return $query;
    }

    /**
     * Filter a Users query by resource access.
     * Only the users who have a permission (Read/Update/Owner) to access a resource should be returned by the query.
     *
     * By instance :
     * $query = $Users->find()->where('Users.username LIKE' => '%@passbolt.com');
     * _filterQueryByResourceAccess($query, 'RESOURCE_UUID');
     *
     * Should filter all the users with a passbolt username who have a permission to access the resource identified by
     * RESOURCE_UUID.
     *
     * @param \Cake\ORM\Query $query The query to augment.
     * @param string $resourceId The resource the users must have access.
     * @throws \InvalidArgumentException if the ressourceId is not a valid uuid
     * @return \Cake\ORM\Query $query
     */
    private function _filterQueryByResourceAccess(\Cake\ORM\Query $query, string $resourceId)
    {
        if (!Validation::uuid($resourceId)) {
            throw new \InvalidArgumentException(__('The resource id should be a valid uuid.'));
        }

        // The query requires a join with Permissions not constraint with the default condition added by the HasMany
        // relationship : Users.id = Permissions.aro_foreign_key.
        // The join will be used in relation to Groups as well, to find the users inherited permissions from Groups.
        // To do so, add an extra join.
        $query->join([
            'table' => $this->getAssociation('Permissions')->getTable(),
            'alias' => 'PermissionsFilterAccess',
            'type' => 'INNER',
            'conditions' => ['PermissionsFilterAccess.aco_foreign_key' => $resourceId],
        ]);

        // Subquery to retrieve the groups the user is member of.
        $groupsSubquery = $this->Groups->find()
            ->innerJoinWith('GroupsUsers')
            ->select('Groups.id')
            ->where([
                'Groups.deleted' => false,
                'GroupsUsers.user_id = Users.id'
            ]);

        // Use distinct to avoid duplicate as it can happen that a user is member of two groups which
        // both have a permission for the same resource
        return $query->distinct()
            // Filter on the users who have a direct permissions.
            // Or on users who are members of a group which have permissions.
            ->where(
                ['OR' => [
                    ['PermissionsFilterAccess.aro_foreign_key = Users.id'],
                    ['PermissionsFilterAccess.aro_foreign_key IN' => $groupsSubquery]
                ]]
            );
    }

    /**
     * Filter a Users query by search.
     * Search on the following fields :
     * - Users.username
     * - Users.Profile.first_name
     * - Users.Profile.last_name
     *
     * By instance :
     * $query = $Users->find();
     * $Users->_filterQueryBySearch($query, 'ada');
     *
     * Should filter all the users with a username or a name containing ada.
     *
     * @param \Cake\ORM\Query $query The query to augment.
     * @param string $search The string to search.
     * @return \Cake\ORM\Query $query
     */
    private function _filterQueryBySearch(Query $query, string $search)
    {
        $search = '%' . $search . '%';

        return $query->where(['OR' => [
            ['Users.username LIKE' => $search],
            ['Profiles.first_name LIKE' => $search],
            ['Profiles.last_name LIKE' => $search]
        ]]);
    }

    /**
     * Filter a Users query by users that don't have permission for a resource.
     *
     * By instance :
     * $query = $Users->find();
     * $Users->_filterQueryByHasNotPermission($query, 'ada');
     *
     * Should filter all the users that do not have a permission for apache.
     *
     * @param \Cake\ORM\Query $query The query to augment.
     * @param string $resourceId The resource to search potential users for.
     * @throws \InvalidArgumentException if the resource id is not a valid uuid
     * @return \Cake\ORM\Query $query
     */
    private function _filterQueryByHasNotPermission(Query $query, string $resourceId)
    {
        if (!Validation::uuid($resourceId)) {
            throw new \InvalidArgumentException(__('The resource id should be a valid uuid.'));
        }

        $permissionQuery = $this->Permissions->find()
            ->select(['Permissions.aro_foreign_key'])
            ->where([
                'Permissions.aro' => 'User',
                'Permissions.aco_foreign_key' => $resourceId
            ]);

        // Filter on the users who do not have yet a permission.
        return $query->where(['Users.id NOT IN' => $permissionQuery]);
    }

    /**
     * Build the query that fetches data for user index
     *
     * @param string $role name
     * @param array $options filters
     * @throws \InvalidArgumentException if no role is specified
     * @return Query
     */
    public function findIndex(string $role, array $options = null)
    {
        $query = $this->find();

        // Options must contain a role
        if (!isset($role)) {
            $msg = __('User table findIndex should have a role set in options.');
            throw new \InvalidArgumentException($msg);
        }
        if (!$this->Roles->isValidRoleName($role)) {
            throw new \InvalidArgumentException(__('The role name is not valid.'));
        }

        // Default associated data
        $containDefault = ['Profiles', 'Gpgkeys', 'Roles', 'GroupsUsers'];
        $containWhiteList = ['LastLoggedIn'];
        if (!isset($options['contain']) || (!is_array($options['contain']))) {
            $contain = $containDefault;
        } else {
            $containOptions = [];
            foreach ($options['contain'] as $option => $value) {
                if ($value == 1) {
                    $containOptions[] = $option;
                }
            }
            $contain = array_merge($containDefault, array_intersect($containOptions, $containWhiteList));
        }

        // If contains Profiles, then include Avatars too.
        if (in_array('Profiles', $contain)) {
            $contain['Profiles'] = AvatarsTable::addContainAvatar();
            unset($contain[array_search('Profiles', $contain)]);
        }

        if (in_array('LastLoggedIn', $contain)) {
            $query = $this->_containLastLoggedIn($query);
            unset($contain[array_search('LastLoggedIn', $contain)]);
        }

        $query->contain($contain);

        // Filter out guests and deleted users
        $query->where([
            'Users.deleted' => false,
            'Roles.name <>' => Role::GUEST
        ]);

        // If searching admins
        if (isset($options['filter']['is-admin'])) {
            $query->where([
                'Roles.name' => Role::ADMIN
            ]);
        }

        // If user is admin, we allow seeing inactive users via the 'is-active' filter
        if ($role === Role::ADMIN) {
            if (isset($options['filter']['is-active'])) {
                $query->where(['Users.active' => $options['filter']['is-active']]);
            }
        } else {
            // otherwise we only show active users
            $query->where(['Users.active' => true]);
        }

        // If searching for a name or username
        if (isset($options['filter']['search']) && count($options['filter']['search'])) {
            $query = $this->_filterQueryBySearch($query, $options['filter']['search'][0]);
        }

        // If searching by group id
        if (isset($options['filter']['has-groups']) && count($options['filter']['has-groups'])) {
            $query = $this->_filterQueryByGroupsUsers($query, $options['filter']['has-groups']);
        }

        // If searching by resource access
        if (isset($options['filter']['has-access']) && count($options['filter']['has-access'])) {
            $query = $this->_filterQueryByResourceAccess($query, $options['filter']['has-access'][0]);
        }

        // If searching by resource the user do not have a direct permission for
        if (isset($options['filter']['has-not-permission']) && count($options['filter']['has-not-permission'])) {
            $query = $this->_filterQueryByHasNotPermission($query, $options['filter']['has-not-permission'][0]);
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
     * @throws \InvalidArgumentException if the role name or user id are not valid
     * @throws Exception
     * @return Query
     */
    public function findView(string $userId, string $roleName)
    {
        if (!Validation::uuid($userId)) {
            throw new \InvalidArgumentException(__('The user id should be a valid uuid.'));
        }
        if (!$this->Roles->isValidRoleName($roleName)) {
            throw new \InvalidArgumentException(__('The role name is not valid.'));
        }

        // Same rule than index apply with a specific id requested
        return $this->findIndex($roleName)->where(['Users.id' => $userId]);
    }

    /**
     * Find delete
     *
     * @param string $userId uuid
     * @param string $roleName role name
     * @throws \InvalidArgumentException if the role name or user id are not valid
     * @return Query
     */
    public function findDelete(string $userId, string $roleName)
    {
        if (!Validation::uuid($userId)) {
            throw new \InvalidArgumentException(__('The user id should be a valid uuid.'));
        }
        if (!$this->Roles->isValidRoleName($roleName)) {
            throw new \InvalidArgumentException(__('The role name is not valid.'));
        }

        return $this->findIndex($roleName)->where(['Users.id' => $userId]);
    }

    /**
     * Build the query that fetches the user data during authentication
     *
     * @param Query $query a query instance
     * @param array $options options
     * @throws \Exception if fingerprint id is not set
     * @return \Cake\ORM\Query
     */
    public function findAuth(Query $query, array $options)
    {
        // Options must contain an id
        if (!isset($options['fingerprint'])) {
            throw new \Exception(__('User table findAuth should have a fingerprint id set in options.'));
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
     * @throws \InvalidArgumentException if the username is not an email
     * @return \Cake\ORM\Query
     */
    public function findRecover(string $username, array $options = [])
    {
        if (!Validation::email($username, Configure::read('passbolt.email.validate.mx'))) {
            throw new \InvalidArgumentException(__('The username should be a valid email.'));
        }
        // show active first and do not count deleted ones
        $query = $this->find()
            ->where(['Users.username' => $username, 'Users.deleted' => false])
            ->contain([
                'Roles',
                'Profiles' => AvatarsTable::addContainAvatar()
            ])
            ->order(['Users.active' => 'DESC']);

        return $query;
    }

    /**
     * Build the query that fetches data for user setup start
     *
     * @param string $userId uuid
     * @throws \InvalidArgumentException if the user id is not a uuid
     * @return object $user entity
     */
    public function findSetup($userId)
    {
        if (!Validation::uuid($userId)) {
            throw new \InvalidArgumentException(__('The user id should be a valid uuid.'));
        }

        // show active first and do not count deleted ones
        $user = $this->find()
            ->contain(['Roles', 'Profiles', 'Roles'])
            ->where([
                'Users.id' => $userId,
                'Users.deleted' => false, // forbid deleted users to start setup
                'Users.active' => false // forbid users that have completed the setup to retry
            ])
            ->first();

        return $user;
    }

    /**
     * Build the query that checks data for user setup start/completion
     *
     * @param string $userId uuid
     * @throws \InvalidArgumentException if the user id is not a uuid
     * @return object $user entity
     */
    public function findSetupRecover(string $userId)
    {
        if (!Validation::uuid($userId)) {
            throw new \InvalidArgumentException(__('The user id should be a valid uuid.'));
        }

        // show active first and do not count deleted ones
        $user = $this->find()
            ->contain(['Roles', 'Profiles', 'Roles'])
            ->where([
                'Users.id' => $userId,
                'Users.deleted' => false, // forbid deleted users to start setup
                'Users.active' => true // forbid users that have not completed the setup to recover
            ])
            ->first();

        return $user;
    }

    /**
     * Get a user info for an email notification context
     *
     * @param string $userId uuid
     * @throws \InvalidArgumentException if the user id is not a valid uuid
     * @return object User
     */
    public function findFirstForEmail(string $userId)
    {
        if (!Validation::uuid($userId)) {
            throw new \InvalidArgumentException(__('The user id should be a valid uuid.'));
        }

        $user = $this->find()
            ->where(['Users.id' => $userId])
            ->contain([
                'Profiles' => AvatarsTable::addContainAvatar(),
                'Roles',
            ])
            ->first();

        return $user;
    }

    /**
     * Get a user info for an email notification context
     *
     * @return object User
     */
    public function findFirstAdmin()
    {
        $user = $this->find()
            ->where([
                'Users.deleted' => false,
                'Users.active' => true,
                'Roles.name' => Role::ADMIN
            ])
            ->order(['Users.created' => 'ASC'])
            ->contain(['Roles'])
            ->first();

        return $user;
    }
}

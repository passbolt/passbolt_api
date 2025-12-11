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
namespace App\Model\Traits\Users;

use App\Error\Exception\NoAdminInDbException;
use App\Model\Entity\Role;
use App\Model\Entity\User;
use App\Model\Event\TableFindIndexBefore;
use App\Model\Table\AvatarsTable;
use App\Model\Table\Dto\FindIndexOptions;
use App\Model\Traits\Query\CaseInsensitiveSearchQueryTrait;
use App\Model\Validation\EmailValidationRule;
use App\Utility\UuidFactory;
use Cake\Collection\CollectionInterface;
use Cake\Database\Expression\IdentifierExpression;
use Cake\Database\Expression\QueryExpression;
use Cake\I18n\DateTime;
use Cake\ORM\Query;
use Cake\ORM\Query\SelectQuery;
use Cake\Utility\Hash;
use Cake\Validation\Validation;
use Exception;
use InvalidArgumentException;

/**
 * @method \Cake\Event\EventManager getEventManager()
 * @property \Passbolt\Log\Model\Table\ActionLogsTable $ActionLogs
 */
trait UsersFindersTrait
{
    use CaseInsensitiveSearchQueryTrait;

    /**
     * Filter a Groups query by groups users.
     *
     * @param \Cake\ORM\Query\SelectQuery $query The query to augment.
     * @param array<string> $groupsIds The users to filter the query on.
     * @param bool $areManager (optional) Should the users be only managers ? Default false.
     * @return \Cake\ORM\Query\SelectQuery $query
     */
    private function _filterQueryByGroupsUsers(
        SelectQuery $query,
        array $groupsIds,
        bool $areManager = false
    ): SelectQuery {
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
        $having = $query->getConnection()->getDriver()->quoteIdentifier('COUNT(GroupsUsers.user_id)');
        $subQuery = $this->GroupsUsers->find()
            ->select('GroupsUsers.user_id')
            ->where(['GroupsUsers.group_id IN' => $groupsIds])
            ->groupBy('GroupsUsers.user_id')
            ->having([$having => count($groupsIds)]);

        // Execute the sub query and extract the user ids.
        $matchingUserIds = Hash::extract($subQuery->toArray(), '{n}.user_id');

        // Filter the query.
        if (empty($matchingUserIds)) {
            // if no user match all groups it should return nobody
            $query->where(['Users.id' => 'NOT_A_VALID_USER_ID']);
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
     * @param \Cake\ORM\Query\SelectQuery $query The query to augment.
     * @param string $resourceId The resource the users must have access.
     * @return \Cake\ORM\Query\SelectQuery $query
     * @throws \InvalidArgumentException if the ressourceId is not a valid uuid
     */
    public function filterQueryByResourceAccess(SelectQuery $query, string $resourceId): SelectQuery
    {
        if (!Validation::uuid($resourceId)) {
            throw new InvalidArgumentException(__('The resource identifier should be a valid UUID.'));
        }

        return $this->filterQueryByResourcesAccess($query, [$resourceId]);
    }

    /**
     * @param \Cake\ORM\Query\SelectQuery $query Users query
     * @param \Cake\ORM\Query\SelectQuery|array $resourceIds Resource IDs the users should have access to
\     * @param array $permissionTypes array of permission type to filter along (OWNER, UPDATE or READ). If empty do not filter vy permission type
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function filterQueryByResourcesAccess(
        SelectQuery $query,
        array|Query $resourceIds,
        array $permissionTypes = []
    ): SelectQuery {
        if (is_array($resourceIds) && empty($resourceIds)) {
            return $query;
        }
        // The query requires a join with Permissions not constraint with the default condition added by the HasMany
        // relationship : Users.id = Permissions.aro_foreign_key.
        // The join will be used in relation to Groups as well, to find the users inherited permissions from Groups.
        // To do so, add an extra join.
        $conditions = ['PermissionsFilterAccess.aco_foreign_key IN' => $resourceIds];
        if (!empty($permissionTypes)) {
            $conditions['PermissionsFilterAccess.type IN'] = $permissionTypes;
        }
        $query->join([
            'table' => $this->getAssociation('Permissions')->getTable(),
            'alias' => 'PermissionsFilterAccess',
            'type' => 'INNER',
            'conditions' => $conditions,
        ]);

        // Subquery to retrieve the groups the user is member of.
        $groupIdsSubquery = $this->Groups->GroupsUsers
            ->find()
            ->select('group_id')
            ->where(['user_id' => new IdentifierExpression('Users.id')]);

        // Use distinct to avoid duplicate as it can happen that a user is member of two groups which
        // both have a permission for the same resource
        return $query->distinct()
            // Filter on the users who have a direct permissions.
            // Or on users who are members of a group which have permissions.
            ->where(
                ['OR' => [
                    ['PermissionsFilterAccess.aro_foreign_key' => new IdentifierExpression('Users.id')],
                    ['PermissionsFilterAccess.aro_foreign_key IN' => $groupIdsSubquery],
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
     * @param \Cake\ORM\Query\SelectQuery $query The query to augment.
     * @param string $search The string to search.
     * @return \Cake\ORM\Query\SelectQuery $query
     */
    private function _filterQueryBySearch(SelectQuery $query, string $search): SelectQuery
    {
        return $this->searchCaseInsensitiveOnMultipleFields($query, [
            'Users.username',
            'Profiles.first_name',
            'Profiles.last_name',
        ], $search);
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
     * @param \Cake\ORM\Query\SelectQuery $query The query to augment.
     * @param string $resourceId The resource to search potential users for.
     * @return \Cake\ORM\Query\SelectQuery $query
     * @throws \InvalidArgumentException if the resource id is not a valid uuid
     */
    private function _filterQueryByHasNotPermission(SelectQuery $query, string $resourceId): SelectQuery
    {
        if (!Validation::uuid($resourceId)) {
            throw new InvalidArgumentException('The resource identifier should be a valid UUID.');
        }

        $permissionQuery = $this->Permissions->find()
            ->select(['Permissions.aro_foreign_key'])
            ->where([
                'Permissions.aro' => 'User',
                'Permissions.aco_foreign_key' => $resourceId,
            ]);

        // Filter on the users who do not have yet a permission.
        return $query->where(['Users.id NOT IN' => $permissionQuery]);
    }

    /**
     * Build the query that fetches data for user index
     *
     * @param string $role name
     * @param array|null $options filters
     * @return \Cake\ORM\Query\SelectQuery
     * @throws \InvalidArgumentException if no role is specified
     */
    public function findIndex(string $role, ?array $options = []): SelectQuery
    {
        /** @var \Cake\ORM\Query\SelectQuery $query */
        $query = $this->find();

        $event = TableFindIndexBefore::create($query, FindIndexOptions::createFromArray($options), $this);

        /** @var \App\Model\Event\TableFindIndexBefore $event */
        $this->getEventManager()->dispatch($event);

        $query = $event->getQuery();

        // Default associated data
        $containDefault = [
            'gpgkey' => true, 'profile' => true, 'groups_users' => true, 'role' => true,
        ];
        $options['contain'] = $options['contain'] ?? [];
        $options['contain'] = array_merge($containDefault, $options['contain']);

        if (isset($options['contain']['role']) && $options['contain']['role']) {
            $query->contain('Roles');
        }
        if (isset($options['contain']['gpgkey']) && $options['contain']['gpgkey']) {
            $query->contain('Gpgkeys');
        }
        if (isset($options['contain']['profile']) && $options['contain']['profile']) {
            $query->contain(['Profiles' => AvatarsTable::addContainAvatar()]);
        }
        if (isset($options['contain']['groups_users']) && $options['contain']['groups_users']) {
            $query->contain('GroupsUsers');
        }

        // Filter out guests and deleted users
        $query->where([
            'Users.deleted' => false,
            'Users.role_id <>' => $this->Roles->getIdByName(Role::GUEST),
        ]);

        // If searching admins
        if (isset($options['filter']['is-admin'])) {
            $query->where([
                'Users.role_id' => $this->Roles->getIdByName(Role::ADMIN),
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
            $query = $this->filterQueryByResourceAccess($query, $options['filter']['has-access'][0]);
        }

        // If searching by resource the user do not have a direct permission for
        if (isset($options['filter']['has-not-permission']) && count($options['filter']['has-not-permission'])) {
            $query = $this->_filterQueryByHasNotPermission($query, $options['filter']['has-not-permission'][0]);
        }

        // Ordering options
        if (isset($options['order'])) {
            $query->orderBy($options['order']);
        }

        return $query;
    }

    /**
     * Find view
     *
     * @param string $userId uuid
     * @param string $roleName role name
     * @return \Cake\ORM\Query\SelectQuery
     * @throws \Exception
     * @throws \InvalidArgumentException if the role name or user id are not valid
     */
    public function findView(string $userId, string $roleName): SelectQuery
    {
        if (!Validation::uuid($userId)) {
            throw new InvalidArgumentException('The user identifier should be a valid UUID.');
        }

        // Same rule than index apply with a specific id requested
        return $this->findIndex($roleName)->where(['Users.id' => $userId]);
    }

    /**
     * Find delete
     *
     * @param string $userId uuid
     * @param string $roleName role name
     * @return \Cake\ORM\Query\SelectQuery
     * @throws \InvalidArgumentException if the role name or user id are not valid
     */
    public function findDelete(string $userId, string $roleName): SelectQuery
    {
        if (!Validation::uuid($userId)) {
            throw new InvalidArgumentException('The user identifier should be a valid UUID.');
        }

        return $this->findIndex($roleName)->where(['Users.id' => $userId]);
    }

    /**
     * Build the query that fetches the user data during authentication
     *
     * @param \Cake\ORM\Query\SelectQuery $query a query instance
     * @param ?string $fingerprint fingerprint
     * @return \Cake\ORM\Query\SelectQuery
     * @throws \Exception if fingerprint id is not set
     */
    public function findAuth(SelectQuery $query, ?string $fingerprint = null): SelectQuery
    {
        // Options must contain an id
        if (!isset($fingerprint)) {
            throw new Exception('User table findAuth should have a fingerprint id set in options.');
        }

        // auth query is always done as guest
        // Use default index option (active:true, deleted:false) and contains
        $query = $this->findIndex(Role::GUEST)
            ->where(['Gpgkeys.fingerprint' => $fingerprint]);

        return $query;
    }

    /**
     * Active, non-deleted and not disables users only with role contained
     * This finder is used to retrieve the user from the user ID stored in session
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query to carve.
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findAuthIdentifier(SelectQuery $query): SelectQuery
    {
        return $query
            ->find('activeNotDeletedContainRole')
            ->find('notDisabled')
            ->select([
                'Users.id',
                'Users.role_id',
                'Users.username',
                'Roles.id',
                'Roles.name',
            ]);
    }

    /**
     * Build the query that fetches a user by username
     * including role and profile
     *
     * @param string $username email of user to retrieve
     * @param array|null $options options
     * @return \Cake\ORM\Query\SelectQuery
     * @throws \InvalidArgumentException if the username is not an email
     */
    public function findByUsername(string $username, ?array $options = []): SelectQuery
    {
        if (!EmailValidationRule::check($username)) {
            throw new InvalidArgumentException('The username should be a valid email.');
        }

        // show active first and do not count deleted ones
        return $this->findByUsernameCaseAware($username)
            ->where([$this->aliasField('deleted') => false])
            ->contain([
                'Roles',
                'Profiles' => AvatarsTable::addContainAvatar(),
            ])
            ->orderBy(['Users.active' => 'DESC']);
    }

    /**
     * Search a user by username. If username are defined as case-sensitive,
     * filter out the false matches
     *
     * @param string $username username to query
     * @return \Cake\ORM\Query\SelectQuery
     * @throws \InvalidArgumentException if the username is not valid email
     * @see UsersTable::isUsernameCaseSensitive()
     */
    public function findByUsernameCaseAware(string $username): SelectQuery
    {
        /** @var \Cake\ORM\Query\SelectQuery $query */
        $query = $this->find()->where([
            'LOWER(Users.username)' => mb_strtolower($username),
        ]);

        if ($this->isUsernameCaseSensitive()) {
            $query->formatResults(function (CollectionInterface $results) use ($username): CollectionInterface {
                    return $results->filter(function (User $user) use ($username) {
                        return $user->username === $username;
                    })->compile(false);
            });
        }

        return $query;
    }

    /**
     * Lists ['user_id' => 'username'] not deleted and featured multiple times
     *
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function listDuplicateUsernames(): SelectQuery
    {
        if ($this->isUsernameCaseSensitive()) {
            return $this->listDuplicateUsernameCaseSensitive();
        } else {
            return $this->listDuplicateUsernamesCaseInsensitive();
        }
    }

    /**
     * Lists all duplicated lower-cased usernames
     *
     * @return \Cake\ORM\Query\SelectQuery
     */
    protected function listDuplicateUsernamesCaseInsensitive(): SelectQuery
    {
        $subQueryOfLowerCasedUsernameDuplicates = $this
            ->find()
            // MAX() here is just to make MySQL happy without that query breaks in MySQL(especially in 5.7)
            ->select(['lower_username' => 'MAX(LOWER(Users.username))'])
            ->where([$this->aliasField('deleted') => false])
            ->groupBy('LOWER(Users.username)')
            ->having('count(*) > 1');

        return $this->find('list', keyField: 'id', valueField: 'username')
            ->disableHydration()
            ->select(['id', 'username'])
            ->where([
                'LOWER(username) IN' => $subQueryOfLowerCasedUsernameDuplicates,
                $this->aliasField('deleted') => false,
            ])
            ->orderByAsc('LOWER(username)');
    }

    /**
     * @return \Cake\ORM\Query\SelectQuery
     */
    protected function listDuplicateUsernameCaseSensitive(): SelectQuery
    {
        // Let PHP remove the unique usernames, case sensitive
        $filterUniqueCaseSensitive = function (CollectionInterface $results): CollectionInterface {
            $duplicates = $results->toArray();
            foreach (array_count_values($duplicates) as $val => $c) {
                if ($c === 1) {
                    $results = $results->reject(function (string $value) use ($val) {
                        return $val === $value;
                    });
                }
            }

            return $results->compile(false);
        };

        return $this
            ->listDuplicateUsernamesCaseInsensitive()
            ->formatResults($filterUniqueCaseSensitive);
    }

    /**
     * Get a user info for an email notification context
     *
     * @param string $userId uuid
     * @throws \InvalidArgumentException if the user id is not a valid uuid
     * @return \App\Model\Entity\User|null
     */
    public function findFirstForEmail(string $userId): ?User
    {
        if (!Validation::uuid($userId)) {
            throw new InvalidArgumentException('The user identifier should be a valid UUID.');
        }

        /** @var \App\Model\Entity\User $user */
        $user = $this->find('locale')
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
     * @return \App\Model\Entity\User|null
     */
    public function findFirstAdmin(): ?User
    {
        $tableHasDisabledField = $this->getSchema()->hasColumn('disabled');
        $query = $this->find();
        // This check is required as this method is called in migrations
        // anterior to the creation of the "disabled" field
        if ($tableHasDisabledField) {
            $query->find('notDisabled');
        }
        /** @var \App\Model\Entity\User $user */
        $user = $query
            ->where([
                'Users.deleted' => false,
                'Users.active' => true,
                'Roles.name' => Role::ADMIN,
            ])
            ->orderBy(['Users.created' => 'ASC'])
            ->contain(['Roles'])
            ->first();

        return $user;
    }

    /**
     * @return \App\Model\Entity\User
     * @throws \App\Error\Exception\NoAdminInDbException if no admin were found
     */
    public function findFirstAdminOrThrowNoAdminInDbException(): User
    {
        $user = $this->findFirstAdmin();
        if (is_null($user)) {
            throw new NoAdminInDbException();
        }

        return $user;
    }

    /**
     * Return a list of admin users (active, non soft-deleted) with their role attached
     *
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findAdmins(): Query
    {
        return $this->find()
            ->where(
                [
                    'Users.deleted' => false,
                    'Users.active' => true,
                    'Roles.name' => Role::ADMIN,
                ]
            )
            ->orderBy(['Users.created' => 'ASC'])
            ->contain(['Roles']);
    }

    /**
     * Get all active users.
     *
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findActive(): SelectQuery
    {
        return $this->find()
             ->where([
                 'Users.deleted' => false,
                 'Users.active' => true,
             ])
             ->orderByAsc('Users.created');
    }

    /**
     * Filter out disabled users.
     *
     * @param \Cake\ORM\Query\SelectQuery $query query
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findNotDisabled(SelectQuery $query): SelectQuery
    {
        return $query->where(function (QueryExpression $where) {
            return $where->or(function (QueryExpression $or) {
                return $or
                    ->isNull($this->aliasField('disabled'))
                    ->gt($this->aliasField('disabled'), DateTime::now());
            });
        });
    }

    /**
     * Retrieve users' last logged in date.
     *
     * @deprecated only used for populating old data. With v5.4, the field in users table is used.
     * @param \Cake\ORM\Query\SelectQuery $query query
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findlastLoggedIn(SelectQuery $query): SelectQuery
    {
        // Retrieve the last logged in date for each user, based on the action_logs table.
        $loginActionIds = [
            UuidFactory::uuid('AuthLogin.loginPost'),
            UuidFactory::uuid('JwtLogin.loginPost'),
        ];
        $actionLogsSubQuery = $this->ActionLogs->find();
        $latestActionLogs = $actionLogsSubQuery
            ->func()
            ->max(new IdentifierExpression('ActionLogs.created'));
        $actionLogsSubQuery
            ->select(['action_logs_last_logged_in' => $latestActionLogs])
            ->where([
                 'ActionLogs.action_id IN' => $loginActionIds,
                 'ActionLogs.status' => 1,
                 'ActionLogs.user_id' => new IdentifierExpression('Users.id'),
            ])
            ->limit(1);

        $query->selectAlso(['action_logs_last_logged_in' => $actionLogsSubQuery]);
        $selectTypeMap = $query->getSelectTypeMap();
        $selectTypeMap->addDefaults(['action_logs_last_logged_in' => 'datetime']);

        return $query;
    }

    /**
     * Active and non deleted users.
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query to carve.
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findActiveNotDeleted(SelectQuery $query): SelectQuery
    {
        return $query->where([
            $this->aliasField($this->aliasField('active')) => true,
            $this->aliasField($this->aliasField('deleted')) => false,
        ]);
    }

    /**
     * Active and non deleted users only with role
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query to carve.
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findActiveNotDeletedContainRole(SelectQuery $query): SelectQuery
    {
        return $query->find('activeNotDeleted')->contain('Roles');
    }

    /**
     * Find non-disabled users to notify when group permissions are changed
     *
     * @param string $groupId group ID
     * @return \Cake\ORM\Query
     */
    public function findGroupManagersToNotify(string $groupId): Query
    {
        return $this
            ->find('locale')
            ->find('notDisabled')
            ->innerJoinWith('GroupsUsers', function (Query $q) use ($groupId) {
                return $q->where([
                    'GroupsUsers.group_id' => $groupId,
                    'GroupsUsers.is_admin' => true,
                ]);
            });
    }
}

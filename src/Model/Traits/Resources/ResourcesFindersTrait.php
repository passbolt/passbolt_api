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
namespace App\Model\Traits\Resources;

use App\Model\Entity\Permission;
use App\Model\Table\AvatarsTable;
use App\Model\Table\PermissionsTable;
use Cake\Collection\CollectionInterface;
use Cake\Validation\Validation;

trait ResourcesFindersTrait
{
    /**
     * Build the query that fetches data for resource index
     *
     * @param string $userId The user to get the resources for
     * @param array $options options
     * @throws \InvalidArgumentException if the userId parameter is not a valid uuid.
     * @return \Cake\ORM\Query
     */
    public function findIndex(string $userId, array $options = [])
    {
        if (!Validation::uuid($userId)) {
            throw new \InvalidArgumentException(__('The user id should be a valid uuid.'));
        }

        $query = $this->find();

        // Filter out deleted resources
        $query->where(['Resources.deleted' => false]);

        if (isset($options['filter']['has-id'])) {
            $query->where(['Resources.id IN' => $options['filter']['has-id']]);
        }

        // If filtered by favorite.
        if (isset($options['filter']['is-favorite'])) {
            // Filter on the favorite resources.
            if ($options['filter']['is-favorite']) {
                $query->innerJoinWith('Favorites', function ($q) use ($userId) {
                    return $q->where(['Favorites.user_id' => $userId]);
                });
            } else {
                // Filter out the favorite resources.
                $query->notMatching('Favorites', function ($q) use ($userId) {
                    return $q->where(['Favorites.user_id' => $userId]);
                });
            }
        }

        // If shared with group.
        if (isset($options['filter']['is-owned-by-me'])) {
            $query = $this->_filterQueryIsOwnedByUser($query, $userId);
        }

        // If shared with group.
        if (isset($options['filter']['is-shared-with-me'])) {
            $query = $this->_filterQuerySharedWithUser($query, $userId);
        }

        // If shared with group.
        if (isset($options['filter']['is-shared-with-group'])) {
            $query = $this->_filterQuerySharedWithGroup($query, $options['filter']['is-shared-with-group']);
        }

        /*
         * If the contain permission option is given, filter on resources the user is allowed to access
         * and retrieve the user permission. Otherwise only filter on resources the user is allowed to
         * access.
         */
        if (isset($options['contain']['permission'])) {
            // Matching will make available the user permission in the result _matchingData property.
            $query->matching('Permission');
            $query = $this->_filterQueryByPermissionsType($query, $userId, Permission::READ);
            // Format the query result to populate the permission property as a contain would do.
            $query->formatResults(function (CollectionInterface $results) {
                return $results->map(function ($row) {
                    $row['permission'] = $row['_matchingData']['Permission'];
                    unset($row['_matchingData']);

                    return $row;
                });
            });
        } else {
            $query->innerJoinWith('Permission');
            $query = $this->_filterQueryByPermissionsType($query, $userId, Permission::READ);
        }

        // If contains Secrets.
        if (isset($options['contain']['secret'])) {
            $query->contain('Secrets', function ($q) use ($userId) {
                return $q->where(['Secrets.user_id' => $userId]);
            });
        }

        // If contains creator.
        if (isset($options['contain']['creator'])) {
            $query->contain('Creator');
        }

        // If contains modifier.
        if (isset($options['contain']['modifier'])) {
            $query->contain('Modifier');
        }

        // If contains favorite.
        if (isset($options['contain']['favorite'])) {
            $query->contain('Favorites', function ($q) use ($userId) {
                return $q->where(['Favorites.user_id' => $userId]);
            });
        }

        // Retrieve the permission and the details of a user attach to it if any
        if (isset($options['contain']['permissions.user.profile'])) {
            $query->contain([
                'Permissions' => [
                    'Users' => [
                        'Profiles' => AvatarsTable::addContainAvatar()
                    ]
                ]
            ]);
        }

        // Retrieve the permission and the details of a group attach to it if any
        if (isset($options['contain']['permissions.group'])) {
            $query->contain('Permissions.Groups');
        }

        // Manage order clauses.
        if (isset($options['order']['Resources.modified'])) {
            $query->order('Resources.modified DESC');
        } else {
            $query->orderAsc('Resources.name');
        }

        return $query;
    }

    /**
     * Build the query that fetches data for resource view
     *
     * @param string $userId The user to get the resources for
     * @param string $resourceId The resource to retrieve
     * @param array $options options
     * @throws \InvalidArgumentException if the userId parameter is not a valid uuid.
     * @throws \InvalidArgumentException if the resourceId parameter is not a valid uuid.
     * @return \Cake\ORM\Query
     */
    public function findView(string $userId, string $resourceId, array $options = [])
    {
        if (!Validation::uuid($userId)) {
            throw new \InvalidArgumentException(__('The parameter userId should be a valid uuid.'));
        }
        if (!Validation::uuid($resourceId)) {
            throw new \InvalidArgumentException(__('The parameter resourceId should be a valid uuid.'));
        }

        $query = $this->findIndex($userId, $options);
        $query->where(['Resources.id' => $resourceId]);

        return $query;
    }

    /**
     * Build the query that fetches the resources that a group has access on.
     *
     * @param string $groupId uuid The group to fetch the resources for
     * @return \Cake\ORM\Query
     */
    public function findAllByGroupAccess(string $groupId)
    {
        if (!Validation::uuid($groupId)) {
            throw new \InvalidArgumentException(__('The group id should be a valid uuid.'));
        }

        $query = $this->find();

        // Filter on resources the group has a permission for.
        $query->innerJoinWith('Permissions')
            ->where(['aro_foreign_key' => $groupId]);

        // Filter out deleted resources
        $query->where(['Resources.deleted' => false]);

        return $query;
    }

    /**
     * Get a list of resources with a given list of ids
     *
     * @param string $userId uuid
     * @param array $resourceIds array of resource uuids
     * @param array $options array of options
     * @throws \InvalidArgumentException if the userId parameter is not a valid uuid.
     * @throws \InvalidArgumentException if the resourceId parameter is not a valid uuid.
     * @return \Cake\ORM\Query
     */
    public function findAllByIds(string $userId, array $resourceIds = [], array $options = [])
    {
        if (!Validation::uuid($userId)) {
            throw new \InvalidArgumentException(__('The user id should be a valid uuid.'));
        }
        if (empty($resourceIds)) {
            throw new \InvalidArgumentException(__('The resources can not be empty.'));
        } else {
            foreach ($resourceIds as $resourceId) {
                if (!Validation::uuid($resourceId)) {
                    throw new \InvalidArgumentException(__('The resource id should be a valid uuid.'));
                }
            }
        }

        return $this->findIndex($userId, $options)
            ->where(['Resources.id IN' => $resourceIds]);
    }

    /**
     * Augment any Resources queries joined with Permissions to ensure the query returns only the
     * resources a user has access.
     *
     * A user has access to a resource if one the following conditions is respected :
     * - A permission is defined directly for the user and for a given resource.
     * - A permission is defined for a group the user is member of and for a given resource.
     *
     * This function can be used on any queries joined with Permissions as following
     * > $query->innerJoinWith('Permissions') or $query->matching('Permissions')
     * > _filterQueryByPermissionsType($query, $userId);
     *
     * @param \Cake\ORM\Query $query The query to filter.
     * @param string $userId The user to check the permissions for.
     * @param int $permissionType The minimum permission type.
     * @throws \InvalidArgumentException if the user id is not a uuid
     * @return \Cake\ORM\Query
     */
    private function _filterQueryByPermissionsType(\Cake\ORM\Query $query, string $userId, int $permissionType = Permission::READ)
    {
        if (!Validation::uuid($userId)) {
            throw new \InvalidArgumentException(__('The user id should be a valid uuid.'));
        }

        // Build the list of allowed permission types.
        $allowedPermissionTypes = array_filter(PermissionsTable::ALLOWED_TYPES, function ($type) use ($permissionType) {
            return $type >= $permissionType;
        });

        // Retrieve the groups ids the user is member of in a subquery.
        $groupsIdsSubQuery = $this->_findGroupsByUserId($userId)
            ->select('Groups.id');

        // In a subquery retrieve the highest permission.
        $permissionSubquery = $this->getAssociation('Permissions')
            ->find()
            ->select('Permissions.id')
            ->where([
                'Permissions.aco_foreign_key = Resources.id',
                'OR' => [
                    ['Permissions.aro_foreign_key' => $userId],
                    ['Permissions.aro_foreign_key IN' => $groupsIdsSubQuery],
                ],
                'Permissions.type IN' => $allowedPermissionTypes,
            ])
            ->order(['Permissions.type' => 'DESC'])
            ->limit(1);

        // Filter the Resources query by permissions.
        $query->where(['Permission.id' => $permissionSubquery]);

        return $query;
    }

    /**
     * Augment any Resources queries to filter on resources owned by the given user.
     * A owned resource means a resource that is shared with the OWNER permission.
     * @param \Cake\ORM\Query $query The query to filter.
     * @param string $userId The user identifier to filter on.
     * @return \Cake\ORM\Query
     */
    private function _filterQueryIsOwnedByUser(\Cake\ORM\Query $query, string $userId)
    {
        $query = $this->_filterQueryByPermissionsType($query, $userId, Permission::OWNER);

        return $query;
    }

    /**
     * Augment any Resources queries to filter on resources shared with the given user.
     * We consider that a resource is shared with a user when it is accessible by the user but is not owner or one
     * of a group he is member is owner.
     * @param \Cake\ORM\Query $query The query to filter.
     * @param string $userId The user identifier to filter on.
     * @return \Cake\ORM\Query
     */
    private function _filterQuerySharedWithUser(\Cake\ORM\Query $query, string $userId)
    {
        $query = $query->where(['Resources.id NOT IN' => $this->Permissions->findResourcesUserIsOwner($userId, true)]);

        return $query;
    }

    /**
     * Augment any Resources queries to filter on resources shared with a given group.
     *
     * @param \Cake\ORM\Query $query The query to filter.
     * @param string $groupId The group to check the permissions for.
     * @throws \InvalidArgumentException if the group id is not a uuid
     * @return \Cake\ORM\Query
     */
    private function _filterQuerySharedWithGroup(\Cake\ORM\Query $query, string $groupId)
    {
        if (!Validation::uuid($groupId)) {
            throw new \InvalidArgumentException(__('The group id should be a valid uuid.'));
        }

        // Filter the main query.
        $query->innerJoinWith('Permissions', function ($q) use ($groupId) {
            return $q->where([
                'Permissions.aco_foreign_key = Resources.id',
                'Permissions.aro_foreign_key' => $groupId
            ]);
        });

        return $query;
    }

    /**
     * Retrieve the groups a user is member of.
     *
     * @param string $userId The user to retrieve the group for.
     * @throws \InvalidArgumentException if the user id is not a uuid
     * @return \Cake\ORM\Query
     */
    private function _findGroupsByUserId(string $userId)
    {
        if (!Validation::uuid($userId)) {
            throw new \InvalidArgumentException(__('The user id should be a valid uuid.'));
        }

        return $this->getAssociation('Permissions')
            ->getAssociation('Groups')
            ->find()
            ->innerJoinWith('Users')
            ->where([
                'Groups.deleted' => 0,
                'Users.id' => $userId
            ]);
    }
}

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
 * @since         2.13.0
 */

namespace Passbolt\Folders\Model\Traits\Folders;

use App\Model\Table\AvatarsTable;
use App\Model\Table\PermissionsTable;
use Cake\ORM\Query;
use Cake\Validation\Validation;
use InvalidArgumentException;
use Passbolt\Folders\Model\Behavior\FolderizableBehavior;
use Passbolt\Folders\Model\Entity\Folder;

/**
 * Trait FoldersFindersTrait
 * @package Passbolt\Folders\Model\Traits\Folders
 *
 * @mixin FolderizableBehavior
 */
trait FoldersFindersTrait
{
    /**
     * Build the query that fetches data for folders index
     *
     * @param string $userId The user to get the folders for
     * @param array $options options
     * @return Query
     * @throws InvalidArgumentException if the userId parameter is not a valid uuid.
     */
    public function findIndex(string $userId, array $options = [])
    {
        if (!Validation::uuid($userId)) {
            throw new InvalidArgumentException(__('The user id should be a valid uuid.'));
        }

        $query = $this->find();

        $query->find(FolderizableBehavior::FINDER_NAME, ['user_id' => $userId]);

        if (isset($options['filter']['has-id'])) {
            $this->filterByIds($query, $options['filter']['has-id']);
        }

        if (isset($options['filter']['search'])) {
            $this->filterQueryBySearch($query, $options['filter']['search'][0]);
        }

        if (isset($options['filter']['has-parent'])) {
            $this->filterQueryByParentIds($query, $options['filter']['has-parent']);
        }

        // Filter on folders the user has access
        $query = $this->_filterQueryByPermissions($query, $userId);

        // If contains the user permission.
        if (isset($options['contain']['permission'])) {
            $query->contain('Permission', function (Query $q) use ($userId) {
                $subQueryOptions = ['checkGroupsUsers' => true];
                $permissionIdSubQuery = $this->Permissions->findAllByAro(PermissionsTable::FOLDER_ACO, $userId, $subQueryOptions)
                    ->where(['Permissions.aco_foreign_key = Folders.id'])
                    ->order(['Permissions.type DESC'])
                    ->limit(1)
                    ->select(['Permissions.id']);

                return $q->where(['Permission.id' => $permissionIdSubQuery]);
            });
        }

        // If contains children_folders.
        if (isset($options['contain']['children_folders'])) {
            $query->contain('ChildrenFolders', function (Query $q) use ($userId) {
                return $q->where(['user_id' => $userId])
                    ->find(FolderizableBehavior::FINDER_NAME, ['user_id' => $userId]);
            });
        }

        // If contains children_resources.
        if (isset($options['contain']['children_resources'])) {
            $query->contain('ChildrenResources', function (Query $q) use ($userId) {
                return $q->where(['user_id' => $userId])
                    ->find(FolderizableBehavior::FINDER_NAME, ['user_id' => $userId]);
            });
        }

        // If contains creator.
        if (isset($options['contain']['creator'])) {
            $query->contain('Creator');
        }

        // If contains create profile.
        if (isset($options['contain']['creator.profile'])) {
            $query->contain([
                'Creator' => [
                    'Profiles' => AvatarsTable::addContainAvatar(),
                ],
            ]);
        }

        // If contains modifier.
        if (isset($options['contain']['modifier'])) {
            $query->contain('Modifier');
        }

        // If contains create profile.
        if (isset($options['contain']['modifier.profile'])) {
            $query->contain([
                'Modifier' => [
                    'Profiles' => AvatarsTable::addContainAvatar(),
                ],
            ]);
        }

        // Retrieve the permission and the details of a user attach to it if any
        if (isset($options['contain']['permissions'])) {
            $query->contain(['Permissions']);
        }

        // Retrieve the permission and the details of a user attach to it if any
        if (isset($options['contain']['permissions.user.profile'])) {
            $query->contain([
                'Permissions' => [
                    'Users' => [
                        'Profiles' => AvatarsTable::addContainAvatar(),
                    ],
                ],
            ]);
        }

        // Retrieve the permission and the details of a group attach to it if any
        if (isset($options['contain']['permissions.group'])) {
            $query->contain('Permissions.Groups');
        }

        $query->orderAsc('Folders.name');

        return $query;
    }

    /**
     * Build the query that fetches data for folders view
     *
     * @param string $userId The user to get the folders for
     * @param string $folderId The folder to retrieve
     * @param array $options options
     * @return Query
     */
    public function findView(string $userId, string $folderId, array $options = [])
    {
        $query = $this->findIndex($userId, $options)
            ->where(['Folders.id' => $folderId]);

        return $query;
    }

    /**
     * Filter a folders query to return only folders the user has access to.
     *
     * @param Query $query The query to filter.
     * @param string $userId The user to check the permissions for.
     * @return Query
     */
    private function _filterQueryByPermissions(Query $query, string $userId)
    {
        $subQueryOptions = [
            'checkGroupsUsers' => true,
        ];
        $resourcesFilterByPermissionTypeSubQuery = $this->Permissions->findAllByAro(PermissionsTable::FOLDER_ACO, $userId, $subQueryOptions)
            ->select(['Permissions.aco_foreign_key'])
            ->distinct();

        $query->where(['Folders.id IN' => $resourcesFilterByPermissionTypeSubQuery]);

        return $query;
    }

    /**
     * Filter a query to retrieve folders on their ids with the given list of ids
     *
     * @param Query $query Query to filter on
     * @param array $folderIds array of folders ids
     * @return Query|Folder[]
     */
    public function filterByIds(Query $query, array $folderIds)
    {
        return $query->where(['Folders.id IN' => $folderIds]);
    }

    /**
     * Filter a Folders query by search.
     * Search on the name field.
     *
     * By instance :
     * $query = $Folders->find();
     * $Groups->_filterQueryBySearch($query, 'creative');
     *
     * Should filter all the groups with a name containing creative.
     *
     * @param Query $query Query to filter
     * @param string $name Name to filter
     * @return Query
     */
    public function filterQueryBySearch(Query $query, string $name)
    {
        return $query->where([
            ['name LIKE' => '%' . $name . '%'],
        ]);
    }

    /**
     * Filter a query by parents ids.
     *
     * @param Query $query Query to filter on
     * @param array $parentIds Array of parent ids
     * @return Query
     */
    public function filterQueryByParentIds(Query $query, array $parentIds)
    {
        if (empty($parentIds)) {
            return $query;
        }

        $includeRoot = false;
        $parentIds = array_filter($parentIds, function ($value) use (&$includeRoot) {
            if ($value == Folder::ROOT_ID) {
                $includeRoot = true;

                return false;
            }

            return true;
        });

        return $query->innerJoinWith('ChildrenFolders', function (Query $q) use ($parentIds, $includeRoot) {
            $conditions = [];
            if (!empty($parentIds)) {
                $conditions[] = ['folder_parent_id IN' => $parentIds];
            }
            if ($includeRoot) {
                $conditions[] = ['folder_parent_id IS NULL'];
            }

            return $q->where(['OR' => $conditions]);
        });
    }
}

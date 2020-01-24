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
 * @since         2.14.0
 */

namespace Passbolt\Folders\Model\Traits\Folders;

use App\Model\Table\AvatarsTable;
use App\Model\Table\PermissionsTable;
use Cake\ORM\Query;
use Cake\Validation\Validation;
use Passbolt\Folders\Model\Entity\Folder;

trait FoldersFindersTrait
{
    /**
     * Build the query that fetches data for folders index
     *
     * @param string $userId The user to get the folders for
     * @param array $options options
     * @return Query
     * @throws \InvalidArgumentException if the userId parameter is not a valid uuid.
     */
    public function findIndex(string $userId, array $options = [])
    {
        if (!Validation::uuid($userId)) {
            throw new \InvalidArgumentException(__('The user id should be a valid uuid.'));
        }

        $query = $this->find();

        if (isset($options['filter']['has-id'])) {
            $this->_filterByIds($query, $options['filter']['has-id']);
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

        // If contains creator.
        if (isset($options['contain']['creator'])) {
            $query->contain('Creator');
        }

        // If contains modifier.
        if (isset($options['contain']['modifier'])) {
            $query->contain('Modifier');
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
    public function _filterByIds(Query $query, array $folderIds)
    {
        return $query->where(['Folders.id IN' => $folderIds]);
    }
}

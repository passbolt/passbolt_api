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
 * @since         2.13.0
 */

namespace Passbolt\Folders\Service\FoldersRelations;

use Cake\ORM\TableRegistry;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Utility\Tarjan;

class FoldersRelationsDetectStronglyConnectedComponents
{
    /**
     * @var \Passbolt\Folders\Model\Table\FoldersRelationsTable
     */
    private $foldersRelationsTable;

    /**
     * @var \App\Model\Table\UsersTable
     */
    private $usersTable;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->foldersRelationsTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.FoldersRelations');
        $this->usersTable = TableRegistry::getTableLocator()->get('Users');
    }

    /**
     * Bulk detect strongly connected components comparing a list of given users with all the non deleted users.
     * The script stops and returns the first SCC found.
     *
     * @param array $usersIds The list of users ids to check for
     * @return array
     * [
     *   [
     *     'foreign_id' => The foreign id,
     *     'folder_parent_id' => the folder parent id
     *   ],
     *   ...
     * ]
     */
    public function bulkDetectForUsers(array $usersIds)
    {
        $result = [];
        $usersIdsToCompareWith = $this->getAllNonDeletedUsersIds();
        $usersFoldersRelations = $this->getUsersFoldersRelationsGroupedByUser($usersIdsToCompareWith);

        foreach ($usersIds as $firstUserId) {
            foreach ($usersIdsToCompareWith as $secondUserId) {
                $foldersRelations = array_merge(
                    $usersFoldersRelations[$firstUserId],
                    $usersFoldersRelations[$secondUserId]
                );
                $scc = $this->detectInFoldersRelations($foldersRelations);
                if (!empty($scc)) {
                    return $scc;
                }
            }
            // Avoid comparing users that have already been compared. As the user has already been compared with all non
            // deleted users, then it has already been compared with all the users given in parameter, remove it from
            // the list of users to compare with
            $firstUserIndex = array_search($firstUserId, $usersIdsToCompareWith);
            if ($firstUserIndex != -1) {
                unset($usersIdsToCompareWith[$firstUserIndex]);
            }
        }

        return $result;
    }

    /**
     * Get all non deleted users.
     *
     * @return array
     */
    private function getAllNonDeletedUsersIds()
    {
        return $this->usersTable->find()
            ->where(['deleted' => false])
            ->select('id')
            ->extract('id')
            ->toArray();
    }

    /**
     * Retrieve users folders relations.
     *
     * @param array $usersIds The users to retrieve the folders relations for
     * @param bool $includePersonal Include personal folders. Default false
     * @return array<array> Return an array of folders relations formatted as following
     * [
     *   [
     *     'foreign_id' => The foreign id,
     *     'folder_parent_id' => the folder parent id
     *   ],
     *   ...
     * ]
     */
    private function getUsersFoldersRelationsGroupedByUser(array $usersIds, ?bool $includePersonal = false)
    {
        $result = array_fill_keys($usersIds, []);

        $query = $this->foldersRelationsTable->find();
        $query = $this->foldersRelationsTable->filterByUsersIds($query, $usersIds);
        $query = $this->foldersRelationsTable->filterByForeignModel($query, FoldersRelation::FOREIGN_MODEL_FOLDER);
        if (!$includePersonal) {
            $query = $this->foldersRelationsTable->filterQueryByIsNotPersonalFolder($query);
        }
        $foldersRelations = $query->select(['foreign_id', 'folder_parent_id', 'user_id'])
            ->disableHydration()->toArray();

        foreach ($foldersRelations as $key => $folderRelation) {
            $result[$folderRelation['user_id']][] = [
                'foreign_id' => $folderRelation['foreign_id'],
                'folder_parent_id' => $folderRelation['folder_parent_id'],
            ];
            unset($foldersRelations[$key]);
        }

        return $result;
    }

    /**
     * Return the first detected strongly components set
     *
     * @param array $foldersRelations The folders relation to test formatted as following
     * [
     *   [
     *     'foreign_id' => The foreign id,
     *     'folder_parent_id' => the folder parent id
     *   ],
     *   ...
     * ]
     * @return array
     */
    private function detectInFoldersRelations(array $foldersRelations)
    {
        $result = [];

        [$graph, $graphForeignIdsMap] = $this->formatFoldersRelationInAdjacencyGraph($foldersRelations);
        $stronglyConnectedComponentsSets = Tarjan::detect($graph);
        if (!empty($stronglyConnectedComponentsSets)) {
            $nodes = explode('|', $stronglyConnectedComponentsSets[0]);
            $result = $this->formatDetectInGraphResultInFoldersRelations($nodes, $graphForeignIdsMap);
        }

        return $result;
    }

    /**
     * Get an adjacency graph relative to the aggregated trees of the users given in parameter.
     *
     * @param array $foldersRelations The folders relations to format.
     * @return array
     * [
     *   array $graph The tarjan adjacency graph
     *   array $graphForeignIdsMap The mapping between the tarjan graph nodes id and the folders id
     * ]
     *
     * graph. The key represents a node id, and its value a list of children nodes ids. Tarjan algorithm requires these
     * ids to be expressed as integer.
     *
     * [
     *   0 => [1,2]
     *   1 => [3]
     *   2 => [4]
     * ]
     *
     * graphForeignIdsMap. The key represents a tarjan node id (integer). The value represents the mapped folder id (uuid).
     * the array
     * [
     *   0 => e97b14ba-8957-57c9-a357-f78a6e1e1a46
     *   1 => 904bcd9f-ff51-5cfd-9de8-d2c876ade498
     * ]
     */
    private function formatFoldersRelationInAdjacencyGraph(array $foldersRelations)
    {
        $graphForeignIdsMap = [];
        $graph = [];

        // Build the adjacency graph.
        foreach ($foldersRelations as $folderRelation) {
            $foreignId = $folderRelation['foreign_id'];
            $folderParentId = $folderRelation['folder_parent_id'];

            if (!array_key_exists($foreignId, $graphForeignIdsMap)) {
                $graphForeignIdsMap[$foreignId] = count($graphForeignIdsMap);
                $graph[$graphForeignIdsMap[$foreignId]] = [];
            }

            if (!is_null($folderParentId)) {
                if (!array_key_exists($folderParentId, $graphForeignIdsMap)) {
                    $graphForeignIdsMap[$folderParentId] = count($graphForeignIdsMap);
                    $graph[$graphForeignIdsMap[$folderParentId]] = [];
                }
                $graph[$graphForeignIdsMap[$folderParentId]][] = $graphForeignIdsMap[$foreignId];
            }
        }

        $graphForeignIdsMap = array_flip($graphForeignIdsMap);

        return [$graph, $graphForeignIdsMap];
    }

    /**
     * Format the algorithm result list into a folders relations list.
     *
     * @param array $nodes A list of integers representing the strongly connected components set
     * [0, 2, 3, 5, 1]
     * @param array $graphForeignIdsMap The nodes map. The map key is relative to a node when the value is relative to
     * a folder id.
     * @return array
     * [
     *   [
     *     string $foreign_id The entity id
     *     string $folder_parent_id The entity parent id
     *   ],
     *   ...
     * ]
     */
    private function formatDetectInGraphResultInFoldersRelations(array $nodes, array $graphForeignIdsMap)
    {
        $result = [];

        foreach ($nodes as $i => $node) {
            // If first node, then its parent is the last element of the list, otherwise the previous one.
            $folderParentIdIndex = $i === 0 ? count($nodes) - 1 : $i - 1;
            $result[] = [
                'foreign_id' => $graphForeignIdsMap[$node],
                'folder_parent_id' => $graphForeignIdsMap[$nodes[$folderParentIdIndex]],
            ];
        }

        return $result;
    }

    /**
     * Detect the first strongly connected components set in a given user tree.
     * The detection also includes personal folders.
     * The script stops and returns the first SCC found.
     *
     * @param string $userId The target user
     * @return array The list of folders relations involved in the strongly connected components set
     * [
     *   [
     *     'foreign_id' => The foreign id,
     *     'folder_parent_id' => the folder parent id
     *   ],
     *   ...
     * ]
     */
    public function detectInUserTree(string $userId)
    {
        $query = $this->foldersRelationsTable->findByUserId($userId);
        $query = $this->foldersRelationsTable->filterByForeignModel($query, FoldersRelation::FOREIGN_MODEL_FOLDER);
        $foldersRelations = $query->select(['foreign_id', 'folder_parent_id'])
            ->disableHydration()->toArray();

        return $this->detectInFoldersRelations($foldersRelations);
    }
}

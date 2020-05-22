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

namespace Passbolt\Folders\Service\FoldersRelations;

use App\Model\Table\UsersTable;
use Cake\ORM\TableRegistry;
use Passbolt\Folders\Model\Table\FoldersRelationsTable;

class FoldersRelationsDetectStronglyConnectedComponents
{
    /**
     * @var FoldersRelationsTable
     */
    private $foldersRelationsTable;

    /**
     * @var UsersTable
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
     * Bulk detect strongly connected components for a list of users.
     * Detect SCCS by checking all users trees of the list with the rest of passbolt users.
     *
     * @param array $usersIds The list of users ids to check for
     * @param bool $stopOnFirst Stop the detection on the first SCC found
     * @return array
     * [
     *   [
     *     string $foreign_id The entity id
     *     string $folder_parent_id The entity parent id
     *   ],
     *   ...
     * ]
     */
    public function bulkDetectForUsers(array $usersIds, bool $stopOnFirst = false)
    {
        $result = [];
        $allUsersIds = $this->getAllNonDeletedUsersIds();
        $alreadyComparedUsers = [];

        foreach ($usersIds as $firstUserId) {
            foreach ($allUsersIds as $secondUserId) {
                // If the 2 users trees have already been compared, skip.
                $comparedUsersIdsKey = $this->getUsersIdsCombinedKey($firstUserId, $secondUserId);
                if (array_key_exists($comparedUsersIdsKey, $alreadyComparedUsers)) {
                    continue;
                }
                $alreadyComparedUsers[$comparedUsersIdsKey] = true;

                $sccs = $this->detectInAggregatedUsersTrees([$firstUserId, $secondUserId], false);
                if (!empty($sccs)) {
                    foreach ($sccs as $sccHash => $sccForeignIds) {
                        // If the SCC has already been found don't add it to the result list.
                        if (!array_key_exists($sccHash, $result)) {
                            $result[$sccHash] = $sccForeignIds;
                        }
                    }
                    // Stop on first SCCs found.
                    if ($stopOnFirst) {
                        break 2;
                    }
                }
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
        return $this->foldersRelationsTable->Users->find()
            ->where(['deleted' => false])
            ->select('id')
            ->extract('id')
            ->toArray();
    }

    /**
     * Get a unique combined key representing 2 users ids.
     *
     * @param string $firstUserId The target first user id
     * @param string $secondUserId The target second user id
     * @return string
     */
    private function getUsersIdsCombinedKey(string $firstUserId, string $secondUserId)
    {
        $key = [$firstUserId, $secondUserId];
        sort($key);

        return implode('-', $key);
    }

    /**
     * Detect strongly connected components sets in a graph composed by the aggregated trees of a list of users.
     *
     * @param array $usersIds The list of users to aggregate the graph for.
     * @param bool $includePersonalFolders Include personal folder in the aggregated graph. Default false.
     * @return array
     * [
     *    SCC_HASH => array $foldersRelations The list of folders relations involved in the strongly connected components set
     * ]
     */
    public function detectInAggregatedUsersTrees(array $usersIds, bool $includePersonalFolders = false)
    {
        $result = [];
        list ($graph, $graphForeignIdsMap) = $this->getTarjanAdjacencyGraphFor($usersIds, $includePersonalFolders);

        $stronglyConnectedComponentsSets = $this->php_tarjan_entry($graph);
        foreach ($stronglyConnectedComponentsSets as $stronglyConnectedComponentsSet) {
            $nodes = explode('|', $stronglyConnectedComponentsSet);
            if (!array_key_exists($stronglyConnectedComponentsSet, $result)) {
                $result[$stronglyConnectedComponentsSet] = $this->formatTarjanResultListToFoldersRelations($nodes, $graphForeignIdsMap);
            }
        }

        return $result;
    }

    /**
     * Get an adjacency graph relative to the aggregated trees of the users given in parameter.
     *
     * @param array $usersIds The list of users to aggregate the trees to the graph for
     * @param bool $includePersonalFolders Include personal folder in the graph. Default false
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
     * [
     *   0 => e97b14ba-8957-57c9-a357-f78a6e1e1a46
     *   1 => 904bcd9f-ff51-5cfd-9de8-d2c876ade498
     * ]
     */
    private function getTarjanAdjacencyGraphFor(array $usersIds, bool $includePersonalFolders = false)
    {
        $graphForeignIdsMap = [];
        $graph = [];

        $foldersRelations = $this->getUsersFoldersRelations($usersIds, $includePersonalFolders);

        // Build the tarjan adjacency graph.
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
     * Retrieve users' folders relations.
     *
     * @param array $usersIds The users to look for
     * @param bool $includePersonalFolders Include personal folder in the graph. Default false
     * @return array
     */
    private function getUsersFoldersRelations(array $usersIds, bool $includePersonalFolders = false)
    {
        $query = $this->foldersRelationsTable->findUsersFoldersRelations($usersIds);
        if (!$includePersonalFolders) {
            $query = $this->foldersRelationsTable->filterQueryByIsNotPersonalFolder($query);
        }

        return $query->select([
            'foreign_id',
            'folder_parent_id',
        ])->toArray();
    }

    // @codingStandardsIgnoreStart

    /**
     * Detect strongly connected components in an adjacency graph.
     *
     * @param array $graph The graph to look into
     * @return array list of SCCs found in the graph
     * [
     *    '0|2|3|5',
     *    '1|2|4',
     * ]
     */
    private function php_tarjan_entry(array $graph)
    {
        $cycles = [];
        $marked = [];
        $marked_stack = [];
        $point_stack = [];

        for ($x = 0; $x < count($graph); $x++) {
            $marked[$x] = false;
        }

        for ($i = 0; $i < count($graph); $i++) {
            $this->php_tarjan($i, $i, $graph, $cycles, $marked, $marked_stack, $point_stack);
            while (!empty($marked_stack)) {
                $marked[array_pop($marked_stack)] = false;
            }
//            echo '<br>'.($i+1).' / '.count($G_local); // Enable if you wish to follow progression through the array rows.
        }

        $cycles = array_keys($cycles);

        return $cycles;
    }

    /**
     * Apply the tarjan algorithm on a node
     *
     * @param $s
     * @param $v
     * @param $graph
     * @param $cycles
     * @param $marked
     * @param $marked_stack
     * @param $point_stack
     * @return bool
     */
    private function php_tarjan($s, $v, &$graph, &$cycles, &$marked, &$marked_stack, &$point_stack)
    {
        $f = false;
        $point_stack[] = $v;
        $marked[$v] = true;
        $marked_stack[] = $v;

        //$maxlooplength = 3; // Enable to Limit the length of loops to keep in the results (see below).

        foreach ($graph[$v] as $w) {
            if ($w < $s) {
                $graph[$w] = [];
            } elseif ($w == $s) {
                //if (count($point_stack) == $maxlooplength){ // Enable to collect cycles of a given length only.
                // Add new cycles as array keys to avoid duplication. Way faster than using array_search.
                $cycles[implode('|', $point_stack)] = true;
                //}
                $f = true;
            } elseif ($marked[$w] === false) {
                //if (count($point_stack) < $maxlooplength){ // Enable to only collect cycles up to $maxlooplength.
                $g = $this->php_tarjan($s, $w, $graph, $cycles, $marked, $marked_stack, $point_stack);
                //}
                if (!empty($f) || !empty($g)) {
                    $f = true;
                }
            }
        }

        if ($f === true) {
            while (end($marked_stack) != $v) {
                $marked[array_pop($marked_stack)] = false;
            }
            array_pop($marked_stack);
            $marked[$v] = false;
        }

        array_pop($point_stack);

        return $f;
    }

    // @codingStandardsIgnoreEnd

    /**
     * Format Tarjan result list into a folders relations list.
     *
     * @param array $nodes The tarjan result list. A list of integers representing the strongly connected components set
     * i.e. [0, 2, 3, 5, 1]
     * @param array $graphForeignIdsMap The tarjan nodes map. The map key is relative to a tarjan node when the value
     * is relative to a folder id.
     * @return array
     * [
     *   [
     *     string $foreign_id The entity id
     *     string $folder_parent_id The entity parent id
     *   ],
     *   ...
     * ]
     */
    private function formatTarjanResultListToFoldersRelations(array $nodes, array $graphForeignIdsMap)
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
}

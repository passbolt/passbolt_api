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

namespace Passbolt\Folders\Service\FoldersRelations;

use Cake\ORM\TableRegistry;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Model\Table\FoldersRelationsTable;

class FoldersRelationsDetectStronglyConnectedComponents
{
    /**
     * @var FoldersRelationsTable
     */
    private $foldersRelationsTable;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->foldersRelationsTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.FoldersRelations');
    }

    /**
     * Detect SCCs between a list of users and all other passbolt users.
     *
     * @param array $usersIds The list of users to check for
     * @return array The list of found SCCs
     * [
     *    SCC_HASH => [
     *      array $usersIds The list of users having this SCC
     *      array $foldersRelations The list of folders relations involved in this SCC
     *    ]
     * ]
     */
    public function detectBetweenUsersAndAllUsers(array $usersIds)
    {
        $result = [];
        $allUsersIds = $this->foldersRelationsTable->Users->find()->where(['deleted' => false])
            ->select('id')->extract('id')->toArray();

        foreach ($usersIds as $userId) {
            foreach ($allUsersIds as $allUserId) {
                $sccs = $this->detectBetweenUsers([$userId, $allUserId], false);
                foreach ($sccs as $sccHash => $sccForeignIds) {
                    if (!array_key_exists($sccHash, $result)) {
                        $result[$sccHash] = [
                            'users_ids' => [$userId, $allUserId],
                            'folders_relations' => $sccForeignIds
                        ];
                    } else {
                        $result[$sccHash][] = $allUserId;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Detect SCCs in an graph relative to the aggregated trees of the user given in parameter.
     *
     * @param array $usersIds The list of users to check for
     * @param bool $includePersonalFolders Include personal folder in the check. Default false
     * @return array
     * [
     *    SCC_HASH => array $foldersRelations The list of folders relations involved in this SCC
     * ]
     */
    public function detectBetweenUsers(array $usersIds, bool $includePersonalFolders = false)
    {
        $result = [];
        list ($graph, $graphReferences) = $this->getAdjacencyGraphFor($usersIds, $includePersonalFolders);
        $graphReferences = array_flip($graphReferences);

        $stronglyConnectedComponentsSets = $this->php_tarjan_entry($graph);
        foreach ($stronglyConnectedComponentsSets as $stronglyConnectedComponentsSet) {
            $nodes = explode('|', $stronglyConnectedComponentsSet);
            if (!array_key_exists($stronglyConnectedComponentsSet, $result)) {
                foreach ($nodes as $i => $node) {
                    $folderParentIdIndex = $i === 0 ? count($nodes) - 1 : $i -1;
                    $result[$stronglyConnectedComponentsSet][] = [
                        'foreign_id' => $graphReferences[$node],
                        'folder_parent_id' => $graphReferences[$nodes[$folderParentIdIndex]]
                    ];
                }
            }
        }

        return $result;
    }

    /**
     * Get an adjacency graph relative to the aggregated trees of the users given in parameter.
     *
     * @param array $usersIds The list of users to get the graph for
     * @param bool $includePersonalFolders Include personal folder in the check. Default false
     * @return array
     */
    private function getAdjacencyGraphFor(array $usersIds, bool $includePersonalFolders = false)
    {
        $graphReferences = [];
        $graph = [];

        $subQuery = $this->foldersRelationsTable->find();
        $foldersIdsNotPersonal = $subQuery->select([
            'foreign_id',
            'countee' => $subQuery->func()->count('foreign_id'),
        ])
            ->where(['foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,])
            ->group('foreign_id')
            ->having('countee > 1')
            ->extract('foreign_id')
            ->toArray();

        // @todo apply the join only if $includePersonalFolder is false
        $foldersRelations = $this->foldersRelationsTable->find()
            ->select([
                'FoldersRelations.foreign_id',
                'FoldersRelations.folder_parent_id',
            ])
            ->where([
                'user_id IN' => $usersIds,
            ]);
        if (!$includePersonalFolders) {
            if (!empty($foldersIdsNotPersonal)) {
                $foldersRelations->where(['foreign_id IN' => $foldersIdsNotPersonal]);
            } else {
                $foldersRelations->where(['foreign_id' => 'false']);
            }
        }

        foreach ($foldersRelations as $folderRelation) {
            $foreignId = $folderRelation['foreign_id'];
            $folderParentId = $folderRelation['folder_parent_id'];

            if (!array_key_exists($foreignId, $graphReferences)) {
                $graphReferences[$foreignId] = count($graphReferences);
                $graph[$graphReferences[$foreignId]] = [];
            }

            if (!is_null($folderParentId)) {
                if (!array_key_exists($folderParentId, $graphReferences)) {
                    $graphReferences[$folderParentId] = count($graphReferences);
                    $graph[$graphReferences[$folderParentId]] = [];
                }
                $graph[$graphReferences[$folderParentId]][] = $graphReferences[$foreignId];
            }
        }

        return [$graph, $graphReferences];
    }

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
        $cycles = array();
        $marked = array();
        $marked_stack = array();
        $point_stack = array();

        for ($x = 0; $x < count($graph); $x++) {
            $marked[$x] = FALSE;
        }

        for ($i = 0; $i < count($graph); $i++) {
            $this->php_tarjan($i, $i, $graph, $cycles, $marked, $marked_stack, $point_stack);
            while (!empty($marked_stack)) {
                $marked[array_pop($marked_stack)] = FALSE;
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
        $f = FALSE;
        $point_stack[] = $v;
        $marked[$v] = TRUE;
        $marked_stack[] = $v;

        //$maxlooplength = 3; // Enable to Limit the length of loops to keep in the results (see below).

        foreach ($graph[$v] as $w) {
            if ($w < $s) {
                $graph[$w] = array();
            } else if ($w == $s) {
                //if (count($point_stack) == $maxlooplength){ // Enable to collect cycles of a given length only.
                // Add new cycles as array keys to avoid duplication. Way faster than using array_search.
                $cycles[implode('|', $point_stack)] = TRUE;
                //}
                $f = TRUE;
            } else if ($marked[$w] === FALSE) {
                //if (count($point_stack) < $maxlooplength){ // Enable to only collect cycles up to $maxlooplength.
                $g = $this->php_tarjan($s, $w, $graph, $cycles, $marked, $marked_stack, $point_stack);
                //}
                if (!empty($f) OR !empty($g)) {
                    $f = TRUE;
                }
            }
        }

        if ($f === TRUE) {
            while (end($marked_stack) != $v) {
                $marked[array_pop($marked_stack)] = FALSE;
            }
            array_pop($marked_stack);
            $marked[$v] = FALSE;
        }

        array_pop($point_stack);
        return $f;
    }
}

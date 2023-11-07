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

use Cake\Database\Expression\TupleComparison;
use Cake\ORM\TableRegistry;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Utility\Tarjan;

class FoldersRelationsDetectStronglyConnectedComponentsService
{
    /**
     * @var \Passbolt\Folders\Model\Table\FoldersRelationsTable
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
     * Detect the first strongly connected components for the whole shared folders relations graph.
     *
     * @return array<\Passbolt\Folders\Model\Entity\FoldersRelation> The folders relations involved in the strongly connected components set.
     * @throws \Exception
     */
    public function detectFirstInSharedFolders(): array
    {
        $foldersRelationsDtos = $this->getAllNotPersonalFoldersRelationsDtos();
        $sccSets = $this->detectInFoldersRelations($foldersRelationsDtos);

        foreach ($sccSets as $sccSet) {
            if ($this->isValidSharedFoldersScc($sccSet)) {
                return $sccSet;
            }
        }

        return [];
    }

    /**
     * Check if a strongly component is valid as per passbolt requirements. It is considered valid if it involves 1 or
     * 2 users.
     *
     * By instance for the following graph, there is a valid cycle as it involves 1 user to form A-B-A.
     * - Ada: A-B-A
     * - Betty: A
     * - Carole: A
     *
     * By instance for the following graph, there is a valid cycle as it involves 2 users to form A-B-C-A.
     * - Ada: A-B-C
     * - Betty: B-C-A
     *
     * By instance for the following graph, there is no valid cycle as it involves 3 users to form A-B-C-A.
     * - Ada: A-B
     * - Betty: B-C
     * - Carole: C-A
     *
     * @param array $sccFoldersRelations The folders relations involved in a strong connected components set.
     * @return bool
     */
    private function isValidSharedFoldersScc(array $sccFoldersRelations): bool
    {
        $foldersRelationsDtos = $this->getFoldersRelationsInvolveInScc($sccFoldersRelations);
        $countFoldersRelations = count($sccFoldersRelations);

        /*
         * Prepare an array representing how the folders relations involved in the cycle are seen by the users
         * having access to these folders relations.
         * [
         *   Ada: [A-B:true, B-C: true]
         *   Betty: [B-C:true, C-A: true]
         * ]
         */
        $usersFoldersRelations = [];
        foreach ($foldersRelationsDtos as $folderRelation) {
            for ($i = 0; $i < $countFoldersRelations; $i++) {
                $sccFoldersRelation = $sccFoldersRelations[$i];
                if (
                    $folderRelation['foreign_id'] === $sccFoldersRelation->foreign_id
                    && $folderRelation['folder_parent_id'] === $sccFoldersRelation->folder_parent_id
                ) {
                    $usersFoldersRelations[$folderRelation['user_id']][$i] = true;
                }
            }
        }

        /*
         * Try to find to users completing each other to see the whole cycle. With the example of arrays prepared above,
         * and for a cycle [A-B, B-C, B-A], Ada and Betty are completing each other and there folders relations
         * form a cycle. Note that it works also for a single user as the algorithm will also compare the users with
         * themselves.
         */
        foreach ($usersFoldersRelations as $userAFoldersRelations) {
            foreach ($usersFoldersRelations as $userBFoldersRelations) {
                for ($i = 0; $i < $countFoldersRelations; $i++) {
                    // None of the 2 current users see the folder relation at this position, they don't complete each other
                    // and there trees don't form a cycle.
                    if (!(isset($userAFoldersRelations[$i]) || isset($userBFoldersRelations[$i]))) {
                        continue 2;
                    }
                }
                // The 2 current users complete each other and there trees form a cycle.
                return true;
            }
        }

        return false;
    }

    /**
     * Retrieve all the folders relations tuple foreign_id and folder_parent_id.
     * The function doesn't return an array of entities for performance reasons.
     *
     * @return array<array> Return an array of folders relations dtos represented as following.
     * [
     *   [
     *     foreign_id => <UUID>,
     *     folder_parent_id => <UUID|null>,
     *   ],
     *   ...
     * ]
     */
    private function getAllNotPersonalFoldersRelationsDtos(): array
    {
        $query = $this->foldersRelationsTable->find();
        $query = $this->foldersRelationsTable->filterByForeignModel($query, FoldersRelation::FOREIGN_MODEL_FOLDER);
        $query = $this->foldersRelationsTable->filterQueryByIsNotPersonalFolder($query);

        return $query->select(['foreign_id', 'folder_parent_id'])
            ->distinct()
            ->disableHydration()
            ->all()
            ->toArray();
    }

    /**
     * Return the folders relations involve in an SCC.
     * The function doesn't return an array of entities for performance reasons.
     *
     * @param array<\Passbolt\Folders\Model\Entity\FoldersRelation> $foldersRelations The set of folders relations
     * @return array Return an array of folders relations dtos represented as following.
     * [
     *   [
     *     foreign_id => <UUID>,
     *     folder_parent_id => <UUID|null>,
     *     user_id => <UUID>,
     *   ],
     *   ...
     * ]
     */
    private function getFoldersRelationsInvolveInScc(array $foldersRelations): array
    {
        return $this->foldersRelationsTable->find()
            ->select(['foreign_id', 'folder_parent_id', 'user_id'])
            ->where($this->buildFoldersRelationsTupleComparisonExpression($foldersRelations))
            ->disableHydration()
            ->all()->toArray();
    }

    /**
     * Build a folders relations IN or NOT IN tuple comparison used in query where clause.
     * Output SQL like:
     * WHERE (foreign_id, folder_parent_id) IN ((FOLDER_RELATION_1_FOREIGN_ID, FOLDER_RELATION_1_FOLDER_PARENT_ID), ...)
     *
     * @param array<\Passbolt\Folders\Model\Entity\FoldersRelation> $foldersRelations The folders relations to build a tuple comparison expression for
     * @param bool $isInOperator (Optional) By default true and the expression with use the IN operator. If false the
     * expression will use the NOT IN operator.
     * @return \Cake\Database\Expression\TupleComparison
     */
    private function buildFoldersRelationsTupleComparisonExpression(
        array $foldersRelations,
        ?bool $isInOperator = true
    ): TupleComparison {
        $operator = $isInOperator ? 'IN' : 'NOT IN';
        $foldersRelationsTupleData = array_map(function (FoldersRelation $folderRelation) {
            return $folderRelation->extract(['foreign_id', 'folder_parent_id']);
        }, $foldersRelations);

        return new TupleComparison(
            ['FoldersRelations.foreign_id', 'FoldersRelations.folder_parent_id'],
            $foldersRelationsTupleData,
            [],
            $operator
        );
    }

    /**
     * Detect all the strongly connected components in the given folders relations. Sort the result to return first
     * the smallest ones.
     *
     * @param array $foldersRelationsDtos An array folders relations dtos to test
     * [
     *   [
     *     foreign_id => <UUID>,
     *     folder_parent_id => <UUID|null>,
     *   ],
     *   ...
     * ]
     * @return array<array<\Passbolt\Folders\Model\Entity\FoldersRelation>> Array of SCCs containing folders relations involved
     * @throws \Exception If it cannot format the result.
     */
    private function detectInFoldersRelations(array $foldersRelationsDtos): array
    {
        $result = [];

        [$graph, $graphForeignIdsMap] = $this->formatFoldersRelationInAdjacencyGraph($foldersRelationsDtos);
        $stronglyConnectedComponentsSets = Tarjan::detect($graph);
        foreach ($stronglyConnectedComponentsSets as $stronglyConnectedComponentsSet) {
            $nodes = explode('|', $stronglyConnectedComponentsSet);
            $result[] = $this->formatDetectInGraphResultInFoldersRelations(
                $nodes,
                $graphForeignIdsMap,
                $foldersRelationsDtos
            );
        }

        usort($result, function ($sccA, $sccB) {
            return count($sccA) - count($sccB);
        });

        return $result;
    }

    /**
     * Format the algorithm result list into a folders relations list.
     *
     * @param array $nodes A list of integers representing the strongly connected components set
     * [0, 2, 3, 5, 1]
     * @param array $graphForeignIdsMap The nodes map. The map key is relative to a node when the value is relative to
     * a folder id.
     * @param array $foldersRelationsDtos The array of folders relations dtos to search in
     * a SCC in.
     * @return array<\Passbolt\Folders\Model\Entity\FoldersRelation>
     * @throws \Exception If it cannot format the result because a folder relation relative to a node cannot be found.
     */
    private function formatDetectInGraphResultInFoldersRelations(
        array $nodes,
        array $graphForeignIdsMap,
        array $foldersRelationsDtos
    ): array {
        $result = [];

        /** @var int $i */
        foreach ($nodes as $i => $node) {
            $foreignId = $graphForeignIdsMap[$node];
            // If first node, then its parent is the last element of the list, otherwise the previous one.
            $folderParentIdIndex = $i === 0 ? count($nodes) - 1 : $i - 1;
            $folderParentId = $graphForeignIdsMap[$nodes[$folderParentIdIndex]];
            // Retrieve the relative folder relation.
            $folderRelationDto = $this->searchFolderRelationInArray($foldersRelationsDtos, $foreignId, $folderParentId);
            $result[] = new FoldersRelation($folderRelationDto);
        }

        return $result;
    }

    /**
     * Get an adjacency graph relative to the aggregated trees of the users given in parameter.
     *
     * @param array $foldersRelationsDtos An array folders relations dtos to format.
     * [
     *   [
     *     foreign_id => <UUID>,
     *     folder_parent_id => <UUID|null>,
     *     user_id => <UUID>,
     *   ],
     *   ...
     * ]
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
    private function formatFoldersRelationInAdjacencyGraph(array $foldersRelationsDtos): array
    {
        $graphForeignIdsMap = [];
        $graph = [];
        $graphCount = 0;

        // Build the adjacency graph.
        foreach ($foldersRelationsDtos as $folderRelationDto) {
            if (!isset($graphForeignIdsMap[$folderRelationDto['foreign_id']])) {
                $graphForeignIdsMap[$folderRelationDto['foreign_id']] = $graphCount++;
                $graph[$graphForeignIdsMap[$folderRelationDto['foreign_id']]] = [];
            }

            if (!is_null($folderRelationDto['folder_parent_id'])) {
                if (!isset($graphForeignIdsMap[$folderRelationDto['folder_parent_id']])) {
                    $graphForeignIdsMap[$folderRelationDto['folder_parent_id']] = $graphCount++;
                    $graph[$graphForeignIdsMap[$folderRelationDto['folder_parent_id']]] = [];
                }
                $graph[$graphForeignIdsMap[$folderRelationDto['folder_parent_id']]][] =
                    &$graphForeignIdsMap[$folderRelationDto['foreign_id']];
            }
        }

        $graphForeignIdsMap = array_flip($graphForeignIdsMap);

        return [$graph, $graphForeignIdsMap];
    }

    /**
     * Search a folder relation by its foreign id and folder parent id in an array of folders relations.
     *
     * @param array<array> $foldersRelationsDtos An array folders relations dtos to search in.
     * [
     *   [
     *     foreign_id => <UUID>,
     *     folder_parent_id => <UUID|null>,
     *     user_id => <UUID>,
     *   ],
     *   ...
     * ]
     * @param string $foreignId The needle foreign id
     * @param string|null $folderParentId The needle folder parent id
     * @return array The found folder relation dto
     * @throws \Exception If a folder relation cannot be found.
     */
    private function searchFolderRelationInArray(
        array $foldersRelationsDtos,
        string $foreignId,
        ?string $folderParentId = null
    ): array {
        foreach ($foldersRelationsDtos as $folderRelationDto) {
            if (
                $folderRelationDto['foreign_id'] === $foreignId
                && $folderRelationDto['folder_parent_id'] === $folderParentId
            ) {
                return $folderRelationDto;
            }
        }

        throw new \Exception('Unable to find a folder relation.');
    }

    /**
     * Detect the first strongly connected components set in a given user tree.
     * The detection also includes personal folders.
     * The script stops and returns the first SCC found.
     *
     * @param string $userId The target user
     * @return array The list of folders relations dtos involved in the strongly connected components set
     * [
     *   [
     *     foreign_id => <UUID>,
     *     folder_parent_id => <UUID|null>,
     *     user_id => <UUID>,
     *   ],
     *   ...
     * ]
     */
    public function detectInUserTree(string $userId): array
    {
        $query = $this->foldersRelationsTable->findByUserId($userId);
        $query = $this->foldersRelationsTable->filterByForeignModel($query, FoldersRelation::FOREIGN_MODEL_FOLDER);
        $foldersRelationsDtos = $query->select(['foreign_id', 'folder_parent_id'])
            ->disableHydration()->all()->toArray();
        $sccs = $this->detectInFoldersRelations($foldersRelationsDtos);

        if (!empty($sccs)) {
            return $sccs[0];
        }

        return [];
    }
}

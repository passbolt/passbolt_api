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

class FoldersRelationsDetectStronglyConnectedComponentsService
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
        /** @phpstan-ignore-next-line */
        $this->foldersRelationsTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.FoldersRelations');
        /** @phpstan-ignore-next-line */
        $this->usersTable = TableRegistry::getTableLocator()->get('Users');
    }

    /**
     * Bulk detect strongly connected components for a list of given users.
     * Compare the tree of the given users with the trees of all the non deleted users.
     *
     * The function stops and returns the first SCC found.
     *
     * @param array $usersIds The list of users ids to check for
     * @return array<\Passbolt\Folders\Model\Entity\FoldersRelation> The folders relations involved in the strongly connected components set.
     */
    public function bulkDetectForUsers(array $usersIds)
    {
        $result = [];
        $usersIdsToCompareWith = $this->usersTable->findActive()
            ->disableHydration()
            ->all()
            ->extract('id')
            ->toArray();
        $usersFoldersRelationsDtos = $this->getUsersFoldersRelationsGroupedByUser($usersIdsToCompareWith);

        foreach ($usersIds as $firstUserId) {
            foreach ($usersIdsToCompareWith as $secondUserId) {
                $foldersRelationsDtos = array_merge(
                    $usersFoldersRelationsDtos[$firstUserId],
                    $usersFoldersRelationsDtos[$secondUserId]
                );
                $result = $this->detectInFoldersRelations($foldersRelationsDtos);
                if (!empty($result)) {
                    break 2;
                }
            }
            // Avoid comparing users that have already been compared. As the user has already been compared with all non
            // deleted users, then it has already been compared with all the users given in parameter, remove it from
            // the list of users to compare with
            $firstUserIndex = array_search($firstUserId, $usersIdsToCompareWith);
            if ($firstUserIndex !== false) {
                unset($usersIdsToCompareWith[$firstUserIndex]);
            }
        }

        return $result;
    }

    /**
     * Retrieve folders relations for a given list of users and group them by users.
     *
     * @param array $usersIds The users to retrieve the folders relations for
     * @param bool $includePersonal Include personal folders. Default false
     * @return array<array<array>> Return an array of folders relations dtos grouped by users ids
     * [
     *   USER_ID => [
     *     [
     *       foreign_id => <UUID>,
     *       folder_parent_id => <UUID|null>,
     *       user_id => <UUID>,
     *     ],
     *     ...
     *   ],
     *   ...
     * ]
     */
    private function getUsersFoldersRelationsGroupedByUser(array $usersIds, ?bool $includePersonal = false): array
    {
        $result = array_fill_keys($usersIds, []);

        $query = $this->foldersRelationsTable->find();
        $query = $this->foldersRelationsTable->filterByForeignModel($query, FoldersRelation::FOREIGN_MODEL_FOLDER);
        $query = $this->foldersRelationsTable->filterByUsersIds($query, $usersIds);
        if (!$includePersonal) {
            $query = $this->foldersRelationsTable->filterQueryByIsNotPersonalFolder($query);
        }
        $foldersRelationsDtos = $query->select(['foreign_id', 'folder_parent_id', 'user_id'])
            ->disableHydration()
            ->all()
            ->toArray();

        foreach ($foldersRelationsDtos as $folderRelationDto) {
            $result[$folderRelationDto['user_id']][] = $folderRelationDto;
        }

        return $result;
    }

    /**
     * Return the first detected strongly components set represented as an array of folders relations.
     *
     * @param array $foldersRelationsDtos An array folders relations dtos to test
     * [
     *   [
     *     foreign_id => <UUID>,
     *     folder_parent_id => <UUID|null>,
     *     user_id => <UUID>,
     *   ],
     *   ...
     * ]
     * @return array<\Passbolt\Folders\Model\Entity\FoldersRelation> Array of SCCs containing folders relations involved
     * @throws \Exception If it cannot format the result.
     */
    private function detectInFoldersRelations(array $foldersRelationsDtos): array
    {
        $result = [];

        [$graph, $graphForeignIdsMap] = $this->formatFoldersRelationInAdjacencyGraph($foldersRelationsDtos);
        $stronglyConnectedComponentsSets = Tarjan::detect($graph);
        if (!empty($stronglyConnectedComponentsSets)) {
            $nodes = explode('|', $stronglyConnectedComponentsSets[0]);
            $result = $this->formatDetectInGraphResultInFoldersRelations(
                $nodes,
                $graphForeignIdsMap,
                $foldersRelationsDtos
            );
        }

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

        return $this->detectInFoldersRelations($foldersRelationsDtos);
    }
}

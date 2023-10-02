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
 * @since         3.7.2
 */

namespace Passbolt\Folders\Service\FoldersRelations;

use App\Utility\UserAccessControl;
use Cake\Database\Expression\TupleComparison;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\Folders\Model\Entity\FoldersRelation;

class FoldersRelationsSortService
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
     * Sort a list of folders relations as following (On top the items with the greatest priority):
     * 1. The folder relation presence in the operator tree. Priority to the operator view.
     * 2. The folder relation usage. Priority to the more used.
     * 3. (Optional) The folder relation presence in the target user tree. Priority to the target user view.
     * 4. The folder relation age. Priority to the oldest folder relation.
     *
     * **Note** The function doesn't sort folders relations having root as folder parent.
     *
     * @param array $foldersRelations The array of folders relations to sort.
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the action.
     * @param string|null $userId The target user id
     * @return void
     */
    public function sort(array &$foldersRelations, UserAccessControl $uac, ?string $userId = null)
    {
        if (empty($foldersRelations)) {
            return;
        }

        $changesDetails = $this->getFolderRelationsDetails($foldersRelations, $uac, $userId);

        usort($foldersRelations, function (
            FoldersRelation $relationA,
            FoldersRelation $relationB
        ) use (
            $changesDetails,
            $userId
        ) {
            $inOperatorTreePriority = $this->hasInOperatorTreePriority($relationA, $relationB, $changesDetails);
            if (!is_null($inOperatorTreePriority)) {
                return $inOperatorTreePriority ? -1 : 1;
            }
            $usagePriority = $this->hasUsagePriority($relationA, $relationB, $changesDetails);
            if (!is_null($usagePriority)) {
                return $usagePriority ? -1 : 1;
            }
            if ($userId) {
                $inUserTreePriority = $this->hasInUserTreePriority($relationA, $relationB, $changesDetails);
                if (!is_null($inUserTreePriority)) {
                    return $inUserTreePriority ? -1 : 1;
                }
            }
            $grandPaPriority = $this->hasGrandPaPriority($relationA, $relationB, $changesDetails);
            if (!is_null($grandPaPriority)) {
                return $grandPaPriority ? -1 : 1;
            }

            return 0;
        });
    }

    /**
     * Get the folders relations details:
     * - In operator tree
     * - Usage
     * - (Optional) In user tree.
     * - Created oldest
     *
     * @param array<\Passbolt\Folders\Model\Entity\FoldersRelation> $foldersRelations The folders relations to sort
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param string|null $userId (Optional) The target user
     * @return array
     * [
     *   RELATION_ID => [
     *     in_operator_tree => bool,
     *     usage_count => int,
     *     in_user_tree => bool,
     *     created_oldest => string,
     *   ]
     * ]
     * Where RELATION_ID is a composite of the folder relation foreign_id and folder_parent_id
     */
    private function getFolderRelationsDetails(
        array $foldersRelations,
        UserAccessControl $uac,
        ?string $userId = null
    ): array {
        $inOperatorTreeDetails = $this->getFoldersRelationsInOperatorTreeDetails($foldersRelations, $uac);
        $usageDetails = $this->getFoldersRelationsUsageDetails($foldersRelations);
        $inUserTreeDetails = [];
        if (!is_null($userId)) {
            $inUserTreeDetails = $this->getFoldersRelationsInUserTreeDetails($foldersRelations, $userId);
        }
        $createdOldestDetails = $this->getFoldersRelationsCreatedOldestDetails($foldersRelations);

        return array_merge_recursive($inOperatorTreeDetails, $usageDetails, $inUserTreeDetails, $createdOldestDetails);
    }

    /**
     * Retrieve the folders relations "in operator tree" details.
     *
     * @param array<\Passbolt\Folders\Model\Entity\FoldersRelation> $foldersRelations The folders relations to sort
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @return array
     * [
     *   RELATION_ID => [
     *     in_operator_tree => bool,
     *   ]
     * ]
     * Where RELATION_ID is a composite of the folder relation foreign_id and folder_parent_id
     */
    private function getFoldersRelationsInOperatorTreeDetails(array $foldersRelations, UserAccessControl $uac): array
    {
        return $this->foldersRelationsTable
            ->findByUserId($uac->getId())
            ->select([
                'foreign_id' => 'foreign_id',
                'folder_parent_id' => 'folder_parent_id',
            ])
            ->where($this->buildFoldersRelationsTupleComparisonExpression($foldersRelations))
            ->all()
            ->combine([$this, 'getRelationDetailsKey'], function () {
                return ['in_operator_tree' => true];
            })
            ->toArray();
    }

    /**
     * Retrieve the folders relations "usage" details.
     *
     * @param array<\Passbolt\Folders\Model\Entity\FoldersRelation> $foldersRelations The folders relations to sort
     * @return array
     * [
     *   RELATION_ID => [
     *     usage_count => int,
     *   ]
     * ]
     * Where RELATION_ID is a composite of the folder relation foreign_id and folder_parent_id
     */
    private function getFoldersRelationsUsageDetails(array $foldersRelations): array
    {
        $foldersRelationsUsageQuery = $this->foldersRelationsTable->find();

        return $foldersRelationsUsageQuery->select([
            'foreign_id' => 'foreign_id',
            'folder_parent_id' => 'folder_parent_id',
            'usage_count' => $foldersRelationsUsageQuery->func()->count('*'),
        ])
            ->select([
                'foreign_id' => 'foreign_id',
                'folder_parent_id' => 'folder_parent_id',
            ])
            ->where($this->buildFoldersRelationsTupleComparisonExpression($foldersRelations))
            ->group(['foreign_id', 'folder_parent_id'])
            ->all()
            ->combine([$this, 'getRelationDetailsKey'], function ($folderRelation) {
                return $folderRelation->extract(['usage_count']);
            })
            ->toArray();
    }

    /**
     * Retrieve the folders relations "in user tree" details.
     *
     * @param array<\Passbolt\Folders\Model\Entity\FoldersRelation> $foldersRelations The folders relations to sort
     * @param string $userId The target user id
     * @return array
     * [
     *   RELATION_ID => [
     *     in_user_tree => bool,
     *   ]
     * ]
     * Where RELATION_ID is a composite of the folder relation foreign_id and folder_parent_id
     */
    private function getFoldersRelationsInUserTreeDetails(array $foldersRelations, string $userId): array
    {
        return $this->foldersRelationsTable
            ->findByUserId($userId)
            ->select(['foreign_id', 'folder_parent_id'])
            ->where($this->buildFoldersRelationsTupleComparisonExpression($foldersRelations))
            ->all()
            ->combine([$this, 'getRelationDetailsKey'], function () {
                return ['in_user_tree' => true];
            })
            ->toArray();
    }

    /**
     * Retrieve the folders relations "created oldest" details.
     *
     * @param array<\Passbolt\Folders\Model\Entity\FoldersRelation> $foldersRelations The folders relations to sort
     * @return array
     * [
     *   RELATION_ID => [
     *     created_oldest => string,
     *   ]
     * ]
     * Where RELATION_ID is a composite of the folder relation foreign_id and folder_parent_id
     */
    private function getFoldersRelationsCreatedOldestDetails(array $foldersRelations): array
    {
        return $this->foldersRelationsTable->find()
            ->select([
                'foreign_id' => 'foreign_id',
                'folder_parent_id' => 'folder_parent_id',
                'created_oldest' => 'MIN(created)',
            ])
            ->where($this->buildFoldersRelationsTupleComparisonExpression($foldersRelations))
            ->group(['foreign_id', 'folder_parent_id'])
            ->all()
            ->combine([$this, 'getRelationDetailsKey'], function ($folderRelation) {
                return $folderRelation->extract(['created_oldest']);
            })
            ->toArray();
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
        bool $isInOperator = true
    ): TupleComparison {
        $operator = $isInOperator ? 'IN' : 'NOT IN';
        $excludeFoldersRelationsArray = array_map(function (FoldersRelation $excludeFolderRelation) {
            return $excludeFolderRelation->extract(['foreign_id', 'folder_parent_id']);
        }, $foldersRelations);

        return new TupleComparison(['foreign_id', 'folder_parent_id'], $excludeFoldersRelationsArray, [], $operator);
    }

    /**
     * Get the key of a folder relation in the folders relations details hashtable.
     *
     * @param \Passbolt\Folders\Model\Entity\FoldersRelation $folderRelation The list of folders relations to sort.
     * @return string
     */
    public function getRelationDetailsKey(FoldersRelation $folderRelation): string
    {
        return "{$folderRelation->foreign_id} {$folderRelation->folder_parent_id}";
    }

    /**
     * Check which folder relation has the operator tree priority. Which relation is in the tree while the
     * other one is not.
     *
     * @param \Passbolt\Folders\Model\Entity\FoldersRelation $relationA The first folder relation to check the priority for.
     * @param \Passbolt\Folders\Model\Entity\FoldersRelation $relationB The second folder relation to check the priority for.
     * @param array $changesDetails The array of folders relations details
     * @return bool|null return true if the first relation has the priority, return false if the second relation has
     * the priority or return null if none of them has the priority.
     */
    private function hasInOperatorTreePriority(
        FoldersRelation $relationA,
        FoldersRelation $relationB,
        array $changesDetails
    ) {
        $inTreeA = Hash::get($changesDetails, "{$this->getRelationDetailsKey($relationA)}.in_operator_tree", false);
        $inTreeB = Hash::get($changesDetails, "{$this->getRelationDetailsKey($relationB)}.in_operator_tree", false);
        if ($inTreeA && !$inTreeB) {
            return true;
        } elseif (!$inTreeA && $inTreeB) {
            return false;
        }

        return null;
    }

    /**
     * Check which folder relation has the usage priority. Which relation is more used than the other one.
     *
     * @param \Passbolt\Folders\Model\Entity\FoldersRelation $relationA The first folder relation to check the priority for.
     * @param \Passbolt\Folders\Model\Entity\FoldersRelation $relationB The second folder relation to check the priority for.
     * @param array $changesDetails The array of folders relations details
     * @return bool|null return true if the first relation has the priority, return false if the second relation has
     * the priority or return null if none of them has the priority.
     */
    private function hasUsagePriority(FoldersRelation $relationA, FoldersRelation $relationB, array $changesDetails)
    {
        $usageCountA = Hash::get($changesDetails, "{$this->getRelationDetailsKey($relationA)}.usage_count", 0);
        $usageCountB = Hash::get($changesDetails, "{$this->getRelationDetailsKey($relationB)}.usage_count", 0);

        if ($usageCountA > $usageCountB) {
            return true;
        } elseif ($usageCountA < $usageCountB) {
            return false;
        }

        return null;
    }

    /**
     * Check which folder relation has the target user tree priority. Which relation is in the tree while the
     * other one is not.
     *
     * @param \Passbolt\Folders\Model\Entity\FoldersRelation $relationA The first folder relation to check the priority for.
     * @param \Passbolt\Folders\Model\Entity\FoldersRelation $relationB The second folder relation to check the priority for.
     * @param array $changesDetails The array of folders relations details
     * @return bool|null return true if the first relation has the priority, return false if the second relation has
     * the priority or return null if none of them has the priority.
     */
    private function hasInUserTreePriority(
        FoldersRelation $relationA,
        FoldersRelation $relationB,
        array $changesDetails
    ) {
        $inTreeA = Hash::get($changesDetails, "{$this->getRelationDetailsKey($relationA)}.in_user_tree", false);
        $inTreeB = Hash::get($changesDetails, "{$this->getRelationDetailsKey($relationB)}.in_user_tree", false);
        if ($inTreeA && !$inTreeB) {
            return true;
        } elseif (!$inTreeA && $inTreeB) {
            return false;
        }

        return null;
    }

    /**
     * Check which folder relation has the grand pa priority. Which relation is older than the other one.
     *
     * @param \Passbolt\Folders\Model\Entity\FoldersRelation $relationA The first folder relation to check the priority for.
     * @param \Passbolt\Folders\Model\Entity\FoldersRelation $relationB The second folder relation to check the priority for.
     * @param array $changesDetails The array of folders relations details
     * @return bool|null return true if the first relation has the priority, return false if the second relation has
     * the priority or return null if none of them has the priority.
     */
    private function hasGrandPaPriority(FoldersRelation $relationA, FoldersRelation $relationB, array $changesDetails)
    {
        $createdOldestA = Hash::get($changesDetails, "{$this->getRelationDetailsKey($relationA)}.created_oldest", 0);
        $createdOldestB = Hash::get($changesDetails, "{$this->getRelationDetailsKey($relationB)}.created_oldest", 0);

        if ($createdOldestA < $createdOldestB) {
            return true;
        } elseif ($createdOldestA > $createdOldestB) {
            return false;
        }

        return null;
    }
}

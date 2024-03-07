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
namespace App\Model\Traits\Cleanup;

use Cake\Database\Expression\ComparisonExpression;
use Cake\Database\Expression\IdentifierExpression;
use Cake\Database\Expression\UnaryExpression;
use Cake\ORM\Query;
use Cake\Utility\Hash;

trait TableCleanupTrait
{
    /**
     * Delete all association records where associated model entities are soft deleted
     *
     * @param string $association association
     * @param bool|null $dryRun false
     * @param \Cake\ORM\Query|null $query custom query to replace the default find if any
     * @return int Number of affected records
     */
    public function cleanupSoftDeleted(string $association, ?bool $dryRun = false, ?Query $query = null): int
    {
        if (!isset($query)) {
            $query = $this->selectQuery()
                ->select(['id'])
                ->leftJoinWith($association)
                ->where([$this->getModelNameFromAssociation($association) . '.deleted' => true]);
        }
        $records = Hash::extract($query->toArray(), '{n}.id');
        if ($dryRun) {
            return count($records);
        }
        if (count($records)) {
            return $this->deleteAll(['id IN' => $records]);
        }

        return 0;
    }

    /**
     * Delete all association records where associated model entities are deleted
     *
     * @param string $association association
     * @param bool|null $dryRun false
     * @param \Cake\ORM\Query|null $query custom query to replace the default find if any
     * @return int Number of affected records
     */
    public function cleanupHardDeleted(string $association, ?bool $dryRun = false, ?Query $query = null): int
    {
        if (!isset($query)) {
            $query = $this->selectQuery()
                ->select(['id'])
                ->leftJoinWith($association)
                ->whereNull($this->getModelNameFromAssociation($association) . '.id');
        }
        $records = Hash::extract($query->toArray(), '{n}.id');
        if ($dryRun) {
            return count($records);
        }
        if (count($records)) {
            return $this->deleteAll(['id IN' => $records]);
        }

        return 0;
    }

    /**
     * Delete duplicated entries for a given combined key.
     * By instance a permission is supposed to be unique for a given "aco", "aco_foreign_key", "aro",
     * "aro_foreign_key" and "type".
     *
     * @param array $combinedKey the columns name that together form a combined key.
     * @param bool|null $dryRun false
     * @param bool|null $deleteNewest (optional) Should the newest duplicate entries deleted? Default true.
     * @return int Number of affected records
     * @throws \Exception If the table to cleanup does not have an "id" column
     * @throw Exception if the table doesn't have a column "id".
     */
    public function cleanupDuplicates(array $combinedKey = [], ?bool $dryRun = false, ?bool $deleteNewest = true): int
    {
        $tableColumns = $this->getSchema()->columns();
        if (array_search('id', $tableColumns) === false) {
            throw new \Exception('Cannot run cleanup duplicates operation on a table not having an "id" column.');
        }

        /*
         * The SQL that retrieves the duplicate rows for the given combined keys combined_field_1, combined_field_2.
         *
         * SELECT
         *   `target_table`.`id`,
         *   `target_table`.`combined_field_1`,
         *   `target_table`.`combined_field_2`
         * FROM `target_table`
         * INNER JOIN (
         *   SELECT
         *     `sub_query_duplicated_table`.`combined_field_1`,
         *     `sub_query_duplicated_table`.`combined_field_2`
         *   FROM `target_table` `sub_query_duplicated_table`
         *   GROUP BY
         *     `sub_query_duplicated_table`.`combined_field_1`,
         *     `sub_query_duplicated_table`.`combined_field_2`
         *   HAVING count(*) > 1
         * ) `duplicated_table` ON (
         *   (
         *     `target_table`.`combined_field_1` = `duplicated_table`.`combined_field_1`
         *     OR (
         *       `target_table`.`combined_field_1` IS NULL
         *        AND `duplicated_table`.`combined_field_1` IS NULL
         *     )
         *   ) AND (
         *     `target_table`.`combined_field_2` = `duplicated_table`.`combined_field_2`
         *     OR (
         *       `target_table`.`combined_field_2` IS NULL
         *        AND `duplicated_table`.`combined_field_2` IS NULL
         *     )
         *   )
         * )
         * ORDER BY
         *   `target_table`.`combined_field_1`,
         *   `target_table`.`combined_field_2`,
         *   `modified` ASC
         */

        // Sub query that finds the duplicated tuples.
        $duplicatedTuplesQuery = $this->selectQuery()
            ->select($combinedKey)
            ->group($combinedKey)
            ->having('count(*) > 1');

        // The inner join conditions to match the duplicated tuples sub query result fields with the main query fields.
        $innerJoinConditions = [];
        $targetTableName = $this->getAlias();
        $duplicateTableName = 'DuplicatedTableName';
        foreach ($combinedKey as $key) {
            $innerJoinConditions[]['OR'] = [
                new ComparisonExpression(
                    new IdentifierExpression($key),
                    new IdentifierExpression("$duplicateTableName.{$targetTableName}__$key")
                ),
                [
                    /**
                     * @see \Cake\Database\Expression\QueryExpression::isNull
                     */
                    new UnaryExpression(
                        'IS NULL',
                        new IdentifierExpression("$duplicateTableName.{$targetTableName}__$key"),
                        UnaryExpression::POSTFIX
                    ),
                    new UnaryExpression('IS NULL', new IdentifierExpression($key), UnaryExpression::POSTFIX),
                ],
            ];
        }

        // Find all the rows corresponding to the identified duplicated tuples.
        $duplicatedRowsQuery = $this->selectQuery()
            ->select(array_merge(['id'], $combinedKey))
            ->join([
                'table' => $duplicatedTuplesQuery,
                'alias' => $duplicateTableName,
                'type' => 'INNER',
                'conditions' => $innerJoinConditions,
            ])
            ->order($combinedKey);

        /*
         * If modified or created field is available on the table, sort the duplicates with it. It will be useful to
         * define a delete strategy: newest or oldest first.
         */
        if (array_search('modified', $tableColumns) !== false) {
            $duplicatedRowsQuery->order(['modified' => $deleteNewest ? 'ASC' : 'DESC']);
        } elseif (array_search('created', $tableColumns) !== false) {
            $duplicatedRowsQuery->order(['created' => $deleteNewest ? 'ASC' : 'DESC']);
        }

        $duplicatedRows = $duplicatedRowsQuery->disableHydration()->toArray();

        // Extract the rows id to delete.
        $idsToRemove = [];
        foreach ($duplicatedRows as $index => $row) {
            foreach ($combinedKey as $key) {
                if ($index === 0 || $duplicatedRows[(int)$index - 1][$key] !== $row[$key]) {
                    continue 2;
                }
            }
            $idsToRemove[] = $row['id'];
        }

        if ($dryRun) {
            return count($idsToRemove);
        }
        if (!empty($idsToRemove)) {
            return $this->deleteAll(['id IN' => $idsToRemove]);
        }

        return 0;
    }

    /**
     * Extracts the string after the last dot.
     *
     * @param string $association Association path
     * @return string
     */
    protected function getModelNameFromAssociation(string $association): string
    {
        $pos = strrpos($association, '.');

        return $pos === false ? $association : substr($association, $pos + 1);
    }
}

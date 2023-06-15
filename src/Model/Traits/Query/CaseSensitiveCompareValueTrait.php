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
 * @since         4.1.0
 */
namespace App\Model\Traits\Query;

use Cake\Database\Driver\Mysql;
use Cake\ORM\Query;

/**
 * Helper methods to get values that are used for case-sensitive comparisons.
 * This is useful for the databases that are case-insensitive by default, i.e. MySQL/MariaDB.
 */
trait CaseSensitiveCompareValueTrait
{
    /**
     * @param \Cake\ORM\Query $query Reference query object.
     * @param mixed $col Column value to convert into case-sensitive binary.
     * @return \Cake\Database\Expression\QueryExpression|string
     */
    public function getCaseSensitiveValue(Query $query, $col)
    {
        /**
         * Mysql is case-insensitive by default, so have to make case-sensitive comparison via explicitly specifying charset.
         * Solution is inspired from here: https://stackoverflow.com/a/56283818
         */
        if (!$query->getConnection()->getDriver() instanceof Mysql) {
            return $col;
        }

        $valuePlaceholder = ':value_case_insensitive_' . rand();
        $query = $query->bind($valuePlaceholder, $col, $this->getBindType($col));

        return $query->newExpr()->add("CONVERT({$valuePlaceholder} using utf8mb4) COLLATE utf8mb4_bin");
    }

    /**
     * @param \Cake\ORM\Query $query Reference query object.
     * @param array $values Array of values to convert into case-sensitive binary.
     * @return array
     */
    public function getCaseSensitiveValues(Query $query, array $values): array
    {
        /**
         * Mysql is case-insensitive by default, so have to make case-sensitive comparison via explicitly specifying charset.
         * Solution is inspired from here: https://stackoverflow.com/a/56283818
         */
        if (!$query->getConnection()->getDriver() instanceof Mysql) {
            return $values;
        }

        $conditions = [];
        $values = array_unique($values);
        foreach ($values as $value) {
            $valuePlaceholder = ':value_case_insensitive_' . rand();
            $conditions[] = $query
                ->newExpr()
                ->add("CONVERT({$valuePlaceholder} using utf8mb4) COLLATE utf8mb4_bin");

            $query = $query->bind($valuePlaceholder, $value, $this->getBindType($value));
        }

        return $conditions;
    }

    /**
     * Returns bind type from given value.
     *
     * @param mixed $value Value from the type needs to be interpreted.
     * @return string
     */
    public function getBindType($value): string
    {
        $type = 'string';
        if (is_int($value)) {
            $type = 'integer';
        }

        return $type;
    }
}

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
 * @since         3.1.0
 */

namespace App\Test\Lib\Utility;

use Cake\Utility\Hash;

trait PaginationTrait
{
    /**
     * @var string
     */
    public $defaultSortField;

    /**
     * @var string
     */
    public $defaultSortDirection = 'asc';

    /**
     * @param int $expected Number of entities expected in the response
     */
    private function assertCountPaginatedEntitiesEquals(int $expected)
    {
        $this->assertSame($expected, count($this->_responseJsonBody));
    }

    /**
     * Assert that an array of entities of modelName is sorted
     * along $sortedField in the provided $direction.
     *
     * @param string $direction Sort direction.
     * @param string $path Path where to find the sorted field in the response data.
     * @param bool|null $sortedFieldIsDateString Needed when comparing date strings.
     */
    private function assertBodyContentIsSorted(string $direction, string $path, ?bool $sortedFieldIsDateString = false)
    {
        $nRecords = count($this->_responseJsonBody);
        for ($i = 1; $i < $nRecords; $i++) {
            $entity1 = $this->convertObjectToArrayRecursively($this->_responseJsonBody[$i - 1]);
            $entity2 = $this->convertObjectToArrayRecursively($this->_responseJsonBody[$i]);

            $field1 = Hash::get($entity1, $path);
            $field2 = Hash::get($entity2, $path);

            $this->assertIsString($field1);
            $this->assertIsString($field2, "This is not a string $field2");

            if ($sortedFieldIsDateString) {
                $field1 = strtotime($field1);
                $field2 = strtotime($field2);
            }

            if ($direction === 'desc') {
                $this->greaterThanOrEqual($field2, $field1);
            } else {
                $this->greaterThanOrEqual($field1, $field2);
            }
        }
    }

    /**
     * Convert an object to array.
     *
     * @param object|array $object Object to convert to array
     * @return array
     */
    public function convertObjectToArrayRecursively($object): array
    {
        $array = (array)$object;
        foreach ($array as &$attribute) {
            if (is_object($attribute) || is_array($attribute)) {
                $attribute = $this->convertObjectToArrayRecursively($attribute);
            }
        }

        return $array;
    }
}

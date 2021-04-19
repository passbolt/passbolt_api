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

trait PaginationTestTrait
{
    /**
     * @var string
     */
    public $defaultSortField;

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
     * @param string $path Path where to find the sorted field in the response data.
     * @param string|null $direction Sort direction.
     */
    private function assertBodyContentIsSorted(string $path, string $direction = 'asc')
    {
        $response = $this->convertObjectToArrayRecursively($this->_responseJsonBody);
        $sortedResponse = Hash::sort($response, '{n}.' . $path, $direction);
        $this->assertSame($sortedResponse, $response);
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

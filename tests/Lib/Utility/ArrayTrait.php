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
namespace App\Test\Lib\Utility;

trait ArrayTrait
{
    /**
     * Asserts that an object has specified attributes.
     *
     * @param string[] $attributesNames Attribute names
     * @param array|\ArrayObject $check Array to check
     */
    public function assertArrayHasAttributes(array $attributesNames, $check)
    {
        foreach ($attributesNames as $attributeName) {
            $this->assertTrue(
                array_key_exists($attributeName, $check),
                'The following attribute is missing in array: ' . $attributeName
            );
        }
    }

    /**
     * Asserts that an object has exactly these attributes.
     *
     * @param string[] $attributesNames Attribute names
     * @param array $check Array to check
     */
    public function assertArrayHasExactAttributes(array $attributesNames, array $check)
    {
        $attributes = array_keys($check);
        sort($attributesNames);
        sort($attributes);
        $this->assertSame($attributesNames, $attributes);
    }

    /**
     * Asserts that an object has exactly these attributes.
     *
     * @param array $expected Expected array.
     * @param array $actual Result array.
     * @param bool $filterNull Should filter out null values.
     * @return void
     */
    public function assertArrayEqualsCanonicalizing(array $expected, array $actual, bool $filterNull = false)
    {
        // Remove null values from the arrays
        if ($filterNull) {
            $expected = array_filter($expected, function ($value) {
                return !is_null($value);
            });
            $actual = array_filter($actual, function ($value) {
                return !is_null($value);
            });
        }

        // Sort both arrays by keys to make it canonicalize
        ksort($expected);
        ksort($actual);

        $this->assertEquals($expected, $actual);
    }
}

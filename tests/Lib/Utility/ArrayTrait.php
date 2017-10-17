<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         3.3.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Test\Lib\Utility;

Trait ArrayTrait {

    /**
     * Asserts that an object has specified attributes.
     *
     * @param string $attributesNames
     * @param object $check
     */
    public function assertArrayHasAttributes($attributesNames, $check)
    {
        foreach ($attributesNames as $attributeName) {
            $this->assertTrue(
                array_key_exists($attributeName, $check),
                'The following attribute is missing in array: ' . $attributeName
            );
        }
    }
}

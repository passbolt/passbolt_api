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

Trait ObjectTrait {

    /**
     * Override the phpunit Assert::assertObjectHasAttribute to assert that an object has a specified attribute.
     * We override the parent method to take care of \Cake\ORM\Entity objects for which the properties are declared
     * on the fly and cannot be tested with the php ReflectionObject used by the phpunit
     * PHPUnit\Framework\Constraint::ObjectHasAttribute class.
     *
     * @param string $attributeName
     * @param object $object
     * @param string $message
     */
    public static function assertObjectHasAttribute($attributeName, $object, $message = '') {

        if (is_a($object, 'Cake\ORM\Entity')) {
            self::assertTrue($object->has($attributeName));
        } else {
            parent::assertObjectHasAttribute($attributeName, $object, $message);
        }
    }

    /**
     * Override the phpunit Assert::assertObjectHasAttribute to assert that an object has a specified attribute.
     * We override the parent method to take care of \Cake\ORM\Entity objects for which the properties are declared
     * on the fly and cannot be tested with the php ReflectionObject used by the phpunit
     * PHPUnit\Framework\Constraint::ObjectHasAttribute class.
     *
     * @param string $attributeName
     * @param object $object
     * @param string $message
     */
    public static function assertObjectNotHasAttribute($attributeName, $object, $message = '') {

        if (is_a($object, 'Cake\ORM\Entity')) {
            self::assertFalse($object->has($attributeName));
        } else {
            parent::assertObjectNotHasAttribute($attributeName, $object, $message);
        }
    }

    /**
     * Asserts that an object has specified attributes.
     *
     * @param string $attributesNames
     * @param object $object
     */
    public function assertObjectHasAttributes($attributesNames, $object)
    {
        foreach ($attributesNames as $attributeName) {
            $this->assertObjectHasAttribute($attributeName, $object);
        }
    }
}

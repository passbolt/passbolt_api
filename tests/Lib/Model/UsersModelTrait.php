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

namespace App\Test\Lib\Model;

trait UsersModelTrait
{
    /**
     * Asserts that an object has all the attributes a user should have.
     *
     * @param object $user
     */
    protected function assertUserAttributes($user)
    {
        $attributes = ['id', 'role_id', 'username', 'active', 'deleted', 'created', 'modified'];
        $this->assertObjectHasAttributes($attributes, $user);
    }
}

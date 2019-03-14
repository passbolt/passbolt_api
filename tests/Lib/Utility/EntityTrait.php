<?php
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

use Cake\Utility\Hash;

trait EntityTrait
{

    /**
     * Asserts that an entity has a specified error.
     *
     * Example:
     *
     * $resource = $this->get(UuidFactory::uuid('user.id.jquery');
     * $this->Resources->save($resource);
     * $this->assertEntityError($resource, 'id.resource_is_not_soft_deleted');
     *
     * Asserts that the resource which should have failed on the IsNotSoftDeleted rule has in the
     * errors returned by the function $resource->getErrors() the error
     * [
     *   'id' => [
     *     'resource_is_not_soft_deleted' => 'The resource cannot be soft deleted.'
     *   ]
     * ]
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity to check the errors.
     * @param string $errorField The error field to check the presence.
     */
    public function assertEntityError($entity, $errorField)
    {
        $errors = $entity->getErrors();
        $error = Hash::get($errors, $errorField);
        $this->assertNotNull($error, "Expected error not found : {$errorField}. Errors: " . json_encode($errors));
    }
}

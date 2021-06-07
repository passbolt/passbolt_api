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
 * @since         3.3.0
 */

namespace App\Service\Resources;

use App\Error\Exception\ValidationException;
use App\Model\Entity\Permission;
use App\Model\Entity\Resource;
use App\Model\Table\ResourceTypesTable;
use App\Utility\UserAccessControl;
use Cake\Core\Configure;
use Cake\Datasource\ModelAwareTrait;
use Cake\Event\Event;
use Cake\Http\Exception\ServiceUnavailableException;
use Cake\Log\Log;

/**
 * Class ResourcesAddService.
 *
 * A particularity of this class is to persist resources and the associated secret outside
 * of a transaction in order to avoid table locks when performing imports.
 *
 * @property \App\Model\Table\ResourcesTable $Resources
 * @property \App\Model\Table\UsersTable $Users
 */
class ResourcesAddService
{
    use ModelAwareTrait;

    public const ADD_SUCCESS_EVENT_NAME = 'ResourcesAddController.addPost.success';

    public const SLEEP_DURATION_WHEN_LOCKED = 100000;

    public const ATTEMPTS_ALLOWED = 5;

    /**
     * ResourcesAddService constructor.
     */
    public function __construct()
    {
        $this->loadModel('Resources');
        $this->loadModel('Users');
    }

    /**
     * Add the resource.
     * This transaction is prone to deadlock when an import
     * of resources is performed on parallel processes on a relatively
     * fresh database. To counter this issue, the saving process is
     * performed several times in case a PDO Exception is encountered.
     *
     * @param string $userId User ID creating the resource.
     * @param array $data Payload to create the resource.
     * @return Resource
     * @throws \App\Error\Exception\ValidationException
     * @throws \Cake\Http\Exception\ServiceUnavailableException
     * @see ResourcesAddServiceStressTest
     */
    public function add(string $userId, array $data): Resource
    {
        $attempts = 1;
        do {
            $resource = $this->buildEntity($userId, $data);
            $this->handleValidationError($resource);
            try {
                $this->Resources->save($resource);
                $this->handleValidationError($resource);
                break;
            } catch (\PDOException $e) {
                Log::error(get_class($e) . ' --- attempt #' . $attempts . ' --- ' . $e->getMessage());
                $attempts++;
            }
            usleep(self::SLEEP_DURATION_WHEN_LOCKED);
        } while ($attempts <= self::ATTEMPTS_ALLOWED);

        $this->handleAttemptsExceededError($attempts);

        return $resource;
    }

    /**
     * Build the resource entity from user input
     *
     * @param string $userId User ID creating the resource.
     * @param array $data Array of data.
     * @return Resource
     * @throws \App\Error\Exception\ValidationException
     */
    protected function buildEntity(string $userId, array $data): Resource
    {
        // Enforce data.
        $data['created_by'] = $userId;
        $data['modified_by'] = $userId;
        $data['permissions'] = [[
            'aro' => 'User',
            'aro_foreign_key' => $userId,
            'aco' => 'Resource',
            'type' => Permission::OWNER,
        ]];

        // If no secrets given, the model will throw a validation error, no need to take care of it here.
        if (isset($data['secrets'])) {
            $data['secrets'][0]['user_id'] = $userId;
        }

        if (!isset($data['resource_type_id']) || !Configure::read('passbolt.plugins.resourceTypes.enabled')) {
            $data['resource_type_id'] = ResourceTypesTable::getDefaultTypeId();
        }

        // Build entity and perform basic check
        return $this->Resources->newEntity($data, [
            'accessibleFields' => [
                'name' => true,
                'username' => true,
                'uri' => true,
                'description' => true,
                'created_by' => true,
                'modified_by' => true,
                'secrets' => true,
                'permissions' => true,
                'resource_type_id' => true,
            ],
            'associated' => [
                'Permissions' => [
                    'validate' => 'saveResource',
                    'accessibleFields' => [
                        'aco' => true,
                        'aro' => true,
                        'aro_foreign_key' => true,
                        'type' => true,
                    ],
                ],
                'Secrets' => [
                    'validate' => 'saveResource',
                    'accessibleFields' => [
                        'user_id' => true,
                        'data' => true,
                    ],
                ],
            ],
        ]);
    }

    /**
     * Manage validation errors.
     *
     * @param Resource $resource resource
     * @throws \App\Error\Exception\ValidationException if the resource validation failed
     * @return void
     */
    protected function handleValidationError(Resource $resource): void
    {
        $errors = $resource->getErrors();
        if (!empty($errors)) {
            throw new ValidationException(__('Could not validate resource data.'), $resource, $this->Resources);
        }
    }

    /**
     * @param int $attempts Number of save attempts stopped by a table lock.
     * @return void
     * @throws \Cake\Http\Exception\ServiceUnavailableException if the table lock blocked the saving process more than the allowed attempts.
     */
    protected function handleAttemptsExceededError(int $attempts): void
    {
        if ($attempts > self::ATTEMPTS_ALLOWED) {
            $msg = __('An unexpected error occured, please contact an administrator.') . ' ';
            $msg .= __('The server returned the following error:') . ' ';
            $msg .= 'Deadlock error while saving resource: attempts limit exceeded.';
            throw new ServiceUnavailableException($msg);
        }
    }

    /**
     * Triggered by the after save resource event.
     *
     * @param Resource $resource The created resource
     * @param \App\Utility\UserAccessControl $uac The user creating the resource.
     * @param array $data Payload to create the resource.
     * @return void
     * @throws \App\Error\Exception\ValidationException
     */
    public function afterSave(
        Resource $resource,
        UserAccessControl $uac,
        array $data
    ): void {
        $this->handleValidationError($resource);
        $user = $this->Users->findFirstForEmail($uac->getId());
        $eventData = compact('resource', 'uac', 'user', 'data');
        $event = new Event(static::ADD_SUCCESS_EVENT_NAME, $this, $eventData);
        $this->Resources->getEventManager()->dispatch($event);
        $this->handleValidationError($resource);
    }
}

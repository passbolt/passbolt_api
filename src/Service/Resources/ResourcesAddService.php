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
use App\Utility\UserAccessControl;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Event\EventInterface;
use Cake\Http\Exception\ServiceUnavailableException;
use Cake\Log\Log;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Metadata\Model\Dto\MetadataResourceDto;
use Passbolt\Metadata\Model\Dto\MetadataTypesSettingsDto;
use Passbolt\Metadata\Utility\MetadataSettingsAwareTrait;
use Passbolt\ResourceTypes\Model\Table\ResourceTypesTable;

/**
 * Class ResourcesAddService.
 *
 * A particularity of this class is to persist resources and the associated secret outside
 * of a transaction in order to avoid table locks when performing imports.
 */
class ResourcesAddService
{
    use LocatorAwareTrait;
    use ResourceSaveV5AwareTrait;
    use MetadataSettingsAwareTrait;

    public const ADD_SUCCESS_EVENT_NAME = 'ResourcesAddController.addPost.success';

    public const SLEEP_DURATION_WHEN_LOCKED = 100000;

    public const ATTEMPTS_ALLOWED = 5;

    /**
     * @var \App\Model\Table\ResourcesTable
     */
    protected $Resources;

    /**
     * @var \App\Model\Table\UsersTable
     */
    protected $Users;

    /**
     * ResourcesAddService constructor.
     */
    public function __construct()
    {
        /** @phpstan-ignore-next-line */
        $this->Resources = $this->fetchTable('Resources');
        /** @phpstan-ignore-next-line */
        $this->Users = $this->fetchTable('Users');
    }

    /**
     * Add the resource.
     * This transaction is prone to deadlock when an import
     * of resources is performed on parallel processes on a relatively
     * fresh database. To counter this issue, the saving process is
     * performed several times in case a PDO Exception is encountered.
     *
     * @param \App\Utility\UserAccessControl $uac User access control.
     * @param \Passbolt\Metadata\Model\Dto\MetadataResourceDto $resourceDto The resource DTO
     * @return Resource
     * @throws \App\Error\Exception\ValidationException
     * @throws \Cake\Http\Exception\ServiceUnavailableException
     * @see ResourcesAddServiceStressTest
     */
    public function add(UserAccessControl $uac, MetadataResourceDto $resourceDto): Resource
    {
        $this->assertAgainstMetadataSettings($resourceDto->isV5(), MetadataTypesSettingsDto::ENTITY_RESOURCE);

        $this->attachListenerToAfterSaveEvent($uac, $resourceDto);
        $attempts = 1;
        do {
            $resource = $this->buildEntity($uac->getId(), $resourceDto);
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
     * @param \Passbolt\Metadata\Model\Dto\MetadataResourceDto $resourceDto Array of data.
     * @return Resource
     * @throws \App\Error\Exception\ValidationException
     */
    protected function buildEntity(string $userId, MetadataResourceDto $resourceDto): Resource
    {
        $data = $resourceDto->toArray();
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
        if (isset($data['secrets']) && is_array($data['secrets'])) {
            $data['secrets'][0]['user_id'] = $userId;
        }

        if (!isset($data['resource_type_id']) || !Configure::read('passbolt.plugins.resourceTypes.enabled')) {
            $data['resource_type_id'] = ResourceTypesTable::getDefaultTypeId();
        }

        $options = $this->getOptionsForResourceSave($resourceDto);
        $options['accessibleFields'] = array_merge($options['accessibleFields'], [
            'expired' => true,
            'created_by' => true,
            'modified_by' => true,
            'secrets' => true,
            'permissions' => true,
            'resource_type_id' => true,
        ]);

        return $this->Resources->newEntity($data, $options);
    }

    /**
     * Manage validation errors.
     *
     * @param \App\Model\Entity\Resource $resource resource
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
            $msg = __('An unexpected error occurred, please contact an administrator.') . ' ';
            $msg .= __('The server returned the following error:') . ' ';
            $msg .= 'Deadlock error while saving resource: attempts limit exceeded.';
            throw new ServiceUnavailableException($msg);
        }
    }

    /**
     * Triggered by the after save resource event.
     *
     * @param \App\Model\Entity\Resource $resource The created resource
     * @param \App\Utility\UserAccessControl $uac The user creating the resource.
     * @param \Passbolt\Metadata\Model\Dto\MetadataResourceDto $resourceDto Resource DTO.
     * @return void
     * @throws \App\Error\Exception\ValidationException
     */
    public function afterSave(
        Resource $resource,
        UserAccessControl $uac,
        MetadataResourceDto $resourceDto
    ): void {
        $this->handleValidationError($resource);
        $user = $this->Users->findFirstForEmail($uac->getId());
        $data = $resourceDto->toArray();
        $isV5 = $resourceDto->isV5();
        $eventData = compact('resource', 'uac', 'user', 'data', 'isV5');
        $event = new Event(static::ADD_SUCCESS_EVENT_NAME, $this, $eventData);
        $this->Resources->getEventManager()->dispatch($event);
        $this->handleValidationError($resource);
    }

    /**
     * Create the after save events on the Resources table.
     *
     * @param \App\Utility\UserAccessControl $uac User access control.
     * @param \Passbolt\Metadata\Model\Dto\MetadataResourceDto $resourceDto DTO.
     * @return void
     */
    protected function attachListenerToAfterSaveEvent(UserAccessControl $uac, MetadataResourceDto $resourceDto): void
    {
        $this->Resources->getEventManager()->on(
            'Model.afterSave',
            ['priority' => 1],
            function (EventInterface $event, $resource) use ($uac, $resourceDto) {
                $this->afterSave(
                    $resource,
                    $uac,
                    $resourceDto
                );
            }
        );
    }
}

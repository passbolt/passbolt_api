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
 * @since         2.13.0
 */

namespace App\Service\Resources;

use App\Error\Exception\CustomValidationException;
use App\Error\Exception\ValidationException;
use App\Model\Entity\Permission;
use App\Model\Entity\Resource;
use App\Model\Table\PermissionsTable;
use App\Model\Table\ResourcesTable;
use App\Model\Table\SecretsTable;
use App\Service\Permissions\PermissionsGetUsersIdsHavingAccessToService;
use App\Service\Permissions\UserHasPermissionService;
use App\Service\Secrets\SecretsUpdateSecretsService;
use App\Utility\UserAccessControl;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class ResourcesUpdateService
{
    use EventDispatcherTrait;

    const UPDATE_SUCCESS_EVENT_NAME = 'ResourcesUpdateController.update.success';

    /**
     * @var PermissionsGetUsersIdsHavingAccessToService
     */
    private $getUsersIdsHavingAccessToService;

    /**
     * @var PermissionsTable
     */
    private $permissionsTable;

    /**
     * @var ResourcesTable
     */
    private $resourcesTable;

    /**
     * @var SecretsTable
     */
    private $secretsTable;

    /**
     * @var SecretsUpdateSecretsService
     */
    private $secretsUpdateSecretsService;

    /**
     * @var UserHasPermissionService
     */
    private $userHasPermissionService;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->getUsersIdsHavingAccessToService = new PermissionsGetUsersIdsHavingAccessToService();
        $this->permissionsTable = TableRegistry::getTableLocator()->get('Permissions');
        $this->resourcesTable = TableRegistry::getTableLocator()->get('Resources');
        $this->secretsTable = TableRegistry::getTableLocator()->get('Secrets');
        $this->secretsUpdateSecretsService = new SecretsUpdateSecretsService();
        $this->userHasPermissionService = new UserHasPermissionService();
    }

    /**
     * Update a resource for the current user.
     *
     * @param UserAccessControl $uac The current user
     * @param string $id The resource to update
     * @param array $data The resource data
     * @return App\Model\Entity\Resource
     * @throws \Exception If an unexpected error occurred
     */
    public function update(UserAccessControl $uac, string $id, array $data = [])
    {
        $resource = $this->getResource($uac, $id);
        $meta = $this->extractDataResourceMeta($data);
        $secrets = Hash::get($data, 'secrets', []);

        if (empty($meta) && empty($secrets)) {
            return $resource;
        }

        $this->resourcesTable->getConnection()->transactional(function () use (&$resource, $uac, $data, $meta, $secrets) {
            $this->updateResourceMeta($uac, $resource, $meta);
            if (!empty($secrets)) {
                $this->updateResourceSecrets($uac, $resource, $secrets);
            }
            $this->postResourceUpdate($uac, $resource, $data);
        });

        return $resource;
    }

    /**
     * Retrieve the resource.
     *
     * @param UserAccessControl $uac UserAccessControl updating the resource
     * @param string $id The resource identifier to retrieve.
     * @return App\Model\Entity\Resource
     * @throws NotFoundException If the resource does not exist.
     * @throws NotFoundException If the resource is soft deleted.
     * @throws NotFoundException If the user does not have access to the resource.
     */
    private function getResource(UserAccessControl $uac, string $id)
    {
        $permission = $this->permissionsTable
            ->findHighestByAcoAndAro(PermissionsTable::RESOURCE_ACO, $id, $uac->userId())
            ->first();

        if (empty($permission)) {
            throw new NotFoundException(__('The resource does not exist.'));
        } elseif ($permission->type < Permission::UPDATE) {
            throw new ForbiddenException(__('You are not allowed to update this resource.'));
        }

        return $this->resourcesTable->get($id);
    }

    /**
     * Extract the resource meta data from the request data
     * @param array $data The request data
     * @return array
     */
    private function extractDataResourceMeta(array $data)
    {
        $meta = [];

        if (array_key_exists('name', $data)) {
            $meta['name'] = $data['name'];
        }
        if (array_key_exists('username', $data)) {
            $meta['username'] = $data['username'];
        }
        if (array_key_exists('uri', $data)) {
            $meta['uri'] = $data['uri'];
        }
        if (array_key_exists('description', $data)) {
            $meta['description'] = $data['description'];
        }

        return $meta;
    }

    /**
     * Update the resource meta data.
     * @param UserAccessControl $uac The operator
     * @param resource $resource The resource to update
     * @param array $data The request data
     * @return void
     */
    private function updateResourceMeta(UserAccessControl $uac, Resource $resource, array $data)
    {
        $this->patchEntity($uac, $resource, $data);
        $this->handleValidationErrors($resource);
        $this->resourcesTable->save($resource);
        $this->handleValidationErrors($resource);
    }

    /**
     * Patch the folder entity.
     *
     * @param UserAccessControl $uac UserAccessControl updating the resource
     * @param resource $resource The resource entity to update
     * @param array $data The resource data.
     * @return App\Model\Entity\Resource
     */
    private function patchEntity(UserAccessControl $uac, Resource $resource, array $data)
    {
        $data['modified_by'] = $uac->userId();

        $accessibleFields = [
            'name' => true,
            'username' => true,
            'uri' => true,
            'description' => true,
            'modified_by' => true,
        ];

        return $this->resourcesTable->patchEntity($resource, $data, ['accessibleFields' => $accessibleFields]);
    }

    /**
     * Handle resource validation errors.
     *
     * @param resource $resource entity
     * @return void
     * @throws ValidationException
     * @throws NotFoundException
     */
    protected function handleValidationErrors(Resource $resource)
    {
        $errors = $resource->getErrors();
        if (!empty($errors)) {
            throw new ValidationException(__('Could not validate resource data.'), $resource, $this->resourcesTable);
        }
    }

    /**
     * Update the secrets.
     *
     * @param UserAccessControl $uac The operator
     * @param resource $resource The target resource
     * @param array $data The list of secrets to update
     * @return void
     * @throws \Exception If an unexpected error occurred
     */
    private function updateResourceSecrets(UserAccessControl $uac, Resource $resource, array $data)
    {
        $usersIdsHavingAccess = $this->getUsersIdsHavingAccessToService->getUsersIdsHavingAccessTo($resource->id);
        sort($usersIdsHavingAccess);
        $usersIdsSecretsProvided = Hash::extract($data, '{n}.user_id');
        sort($usersIdsSecretsProvided);

        if ($usersIdsHavingAccess !== $usersIdsSecretsProvided) {
            $error = ['secrets_provided' => 'The secrets of all the users having access to the resource are required.'];
            $resource->setError('secrets', $error);
            $this->handleValidationErrors($resource);
        }

        try {
            $this->secretsUpdateSecretsService->updateSecrets($uac, $resource->id, $data);
        } catch (CustomValidationException $e) {
            $resource->setError('secrets', $e->getErrors());
            $this->handleValidationErrors($resource);
        }
    }

    /**
     * Trigger the after resource update event.
     *
     * @param UserAccessControl $uac UserAccessControl updating the resource
     * @param resource $resource The updated resource
     * @param array $data The request data
     * @return void
     */
    private function postResourceUpdate(UserAccessControl $uac, Resource $resource, array $data)
    {
        $secrets = $this->secretsTable->findByResourcesUser([$resource->id], $uac->userId())->all()->toArray();
        $resource['secrets'] = $secrets;
        $eventData = ['resource' => $resource, 'accessControl' => $uac, 'data' => $data];
        $this->dispatchEvent(static::UPDATE_SUCCESS_EVENT_NAME, $eventData);
    }
}

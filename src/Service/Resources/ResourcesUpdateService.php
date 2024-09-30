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
 * @since         2.13.0
 */

namespace App\Service\Resources;

use App\Error\Exception\CustomValidationException;
use App\Error\Exception\ValidationException;
use App\Model\Entity\Permission;
use App\Model\Entity\Resource;
use App\Model\Entity\Secret;
use App\Model\Table\PermissionsTable;
use App\Service\Permissions\PermissionsGetUsersIdsHavingAccessToService;
use App\Service\Secrets\SecretsUpdateSecretsService;
use App\Utility\UserAccessControl;
use Cake\Core\Configure;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\I18n\FrozenTime;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Utility\Hash;
use Passbolt\Metadata\Model\Dto\MetadataResourceDto;
use Passbolt\Metadata\Model\Dto\MetadataTypesSettingsDto;
use Passbolt\Metadata\Utility\MetadataSettingsAwareTrait;
use Passbolt\ResourceTypes\Model\Table\ResourceTypesTable;

class ResourcesUpdateService
{
    use EventDispatcherTrait;
    use LocatorAwareTrait;
    use ResourceSaveV5AwareTrait;
    use MetadataSettingsAwareTrait;

    public const UPDATE_SUCCESS_EVENT_NAME = 'ResourcesUpdateController.update.success';

    /**
     * @var \App\Service\Permissions\PermissionsGetUsersIdsHavingAccessToService
     */
    private $getUsersIdsHavingAccessToService;

    /**
     * @var \App\Model\Table\PermissionsTable
     */
    private $Permissions;

    /**
     * @var \App\Model\Table\ResourcesTable
     */
    private $Resources;

    /**
     * @var \App\Service\Secrets\SecretsUpdateSecretsService
     */
    private $secretsUpdateSecretsService;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->getUsersIdsHavingAccessToService = new PermissionsGetUsersIdsHavingAccessToService();
        $this->secretsUpdateSecretsService = new SecretsUpdateSecretsService();
        /** @phpstan-ignore-next-line */
        $this->Permissions = $this->fetchTable('Permissions');
        /** @phpstan-ignore-next-line */
        $this->Resources = $this->fetchTable('Resources');
    }

    /**
     * Update a resource for the logged-in user.
     *
     * @param \App\Utility\UserAccessControl $uac The current user
     * @param string $id The resource to update
     * @param \Passbolt\Metadata\Model\Dto\MetadataResourceDto $resourceDto The resource DTO
     * @return Resource
     * @throws \Exception If an unexpected error occurred
     */
    public function update(UserAccessControl $uac, string $id, MetadataResourceDto $resourceDto): Resource
    {
        $this->assertAgainstMetadataSettings($resourceDto->isV5(), MetadataTypesSettingsDto::ENTITY_RESOURCE);

        $resource = $this->getResource($uac, $id);
        $meta = $this->extractDataResourceMeta($resourceDto);
        $meta = $this->presetOrAssertResourceType($meta);

        $secrets = Hash::get($resourceDto->toArray(), 'secrets', []);

        if (empty($meta) && empty($secrets)) {
            return $resource;
        }

        $this->Resources->getConnection()->transactional(
            function () use (&$resource, $uac, $meta, $secrets, $resourceDto) {
                $this->updateResourceMeta($uac, $resource, $meta, $resourceDto);

                $updatedSecrets = [];
                if (!empty($secrets)) {
                    $updatedSecrets = $this->updateResourceSecrets($uac, $resource, $secrets);
                }

                $this->postResourceUpdate($uac, $resource, $updatedSecrets, $resourceDto);
            }
        );

        return $resource;
    }

    /**
     * Retrieve the resource.
     *
     * @param \App\Utility\UserAccessControl $uac UserAccessControl updating the resource
     * @param string $id The resource identifier to retrieve.
     * @throws \Cake\Http\Exception\NotFoundException If the resource does not exist.
     * @throws \Cake\Http\Exception\NotFoundException If the resource is soft deleted.
     * @throws \Cake\Http\Exception\NotFoundException If the user does not have access to the resource.
     * @return Resource
     */
    private function getResource(UserAccessControl $uac, string $id): Resource
    {
        $permission = $this->Permissions
            ->findHighestByAcoAndAro(PermissionsTable::RESOURCE_ACO, $id, $uac->getId())
            ->first();

        if (empty($permission)) {
            throw new NotFoundException(__('The resource does not exist.'));
        } elseif ($permission->get('type') < Permission::UPDATE) {
            throw new ForbiddenException(__('You are not allowed to update this resource.'));
        }

        return $this->Resources->get($id);
    }

    /**
     * Make sure the default resource type is set on update to support backward compatibility checks
     * Also assert only default resource type is used if plugin is marked as disabled by admin
     *
     * @param array $meta The filtered request data
     * @throws \Cake\Http\Exception\BadRequestException if non default resource type is used when plugin is disabled
     * @return array updated $meta if needed
     */
    private function presetOrAssertResourceType(array $meta): array
    {
        $defaultType = ResourceTypesTable::getDefaultTypeId();
        if (!isset($meta['resource_type_id'])) {
            $meta['resource_type_id'] = $defaultType;
        } elseif (!Configure::read('passbolt.plugins.resourceTypes.enabled')) {
            if ($meta['resource_type_id'] !== $defaultType) {
                throw new BadRequestException(__('Additional resource types are not enabled on this server.'));
            }
        }

        return $meta;
    }

    /**
     * Extract the resource meta data from the request data
     *
     * @param \Passbolt\Metadata\Model\Dto\MetadataResourceDto $resourceDto The request data
     * @return array
     */
    private function extractDataResourceMeta(MetadataResourceDto $resourceDto): array
    {
        $meta = [];

        $data = $resourceDto->toArray();
        foreach ($resourceDto->getMetadataProps() as $metadataProp) {
            if (array_key_exists($metadataProp, $data)) {
                $meta[$metadataProp] = $data[$metadataProp];
            }
        }
        if (array_key_exists('resource_type_id', $data)) {
            $meta['resource_type_id'] = $data['resource_type_id'];
        }
        if (array_key_exists('expired', $data)) {
            $meta['expired'] = $data['expired'];
        }

        return $meta;
    }

    /**
     * Update the resource meta data.
     *
     * @param \App\Utility\UserAccessControl $uac The operator
     * @param \App\Model\Entity\Resource $resource The resource to update
     * @param array $data The request data
     * @param \Passbolt\Metadata\Model\Dto\MetadataResourceDto $resourceDto Resource DTO.
     * @return void
     */
    private function updateResourceMeta(
        UserAccessControl $uac,
        Resource $resource,
        array $data,
        MetadataResourceDto $resourceDto
    ): void {
        $this->patchEntity($uac, $resource, $data, $resourceDto);
        $this->handleValidationErrors($resource);
        $this->Resources->save($resource);
        $this->handleValidationErrors($resource);
    }

    /**
     * Patch the folder entity.
     *
     * @param \App\Utility\UserAccessControl $uac UserAccessControl updating the resource
     * @param \App\Model\Entity\Resource $resource The resource entity to update
     * @param array $data The resource data.
     * @param \Passbolt\Metadata\Model\Dto\MetadataResourceDto $resourceDto Resource DTO.
     * @return Resource
     */
    private function patchEntity(
        UserAccessControl $uac,
        Resource $resource,
        array $data,
        MetadataResourceDto $resourceDto
    ): Resource {
        $data['modified_by'] = $uac->getId();
        // Force the modified field to be updated to ensure the field is updated even if no meta are. It's the case
        // when a user updates only the secret.
        $data['modified'] = new FrozenTime();

        $options = $this->getOptionsForResourceSave($resourceDto);
        $options['accessibleFields'] = array_merge($options['accessibleFields'], [
            'name' => true,
            'username' => true,
            'uri' => true,
            'description' => true,
            'modified' => true,
            'modified_by' => true,
            'resource_type_id' => true,
            'expired' => true,
        ]);

        return $this->Resources->patchEntity($resource, $data, $options);
    }

    /**
     * Handle resource validation errors.
     *
     * @param \App\Model\Entity\Resource $resource entity
     * @return void
     * @throws \App\Error\Exception\ValidationException
     * @throws \Cake\Http\Exception\NotFoundException
     */
    protected function handleValidationErrors(Resource $resource): void
    {
        $errors = $resource->getErrors();
        if (!empty($errors)) {
            throw new ValidationException(__('Could not validate resource data.'), $resource, $this->Resources);
        }
    }

    /**
     * Update the secrets.
     *
     * @param \App\Utility\UserAccessControl $uac The operator
     * @param \App\Model\Entity\Resource $resource The target resource
     * @param array $data The list of secrets to update
     * @return \App\Model\Entity\Secret[]
     * @throws \Exception If an unexpected error occurred
     */
    private function updateResourceSecrets(UserAccessControl $uac, Resource $resource, array $data): array
    {
        $secrets = [];
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
            $entitiesChanges = $this->secretsUpdateSecretsService->updateSecrets($uac, $resource->id, $data);
            /** @var \App\Model\Entity\Secret[] $secrets */
            $secrets = $entitiesChanges->getUpdatedEntities(Secret::class);
        } catch (CustomValidationException $e) {
            $resource->setError('secrets', $e->getErrors());
            $this->handleValidationErrors($resource);
        }

        return $secrets;
    }

    /**
     * Trigger the after resource update event.
     *
     * @param \App\Utility\UserAccessControl $uac UserAccessControl updating the resource
     * @param \App\Model\Entity\Resource $resource The updated resource
     * @param \App\Model\Entity\Secret[] $secrets The secrets
     * @param \Passbolt\Metadata\Model\Dto\MetadataResourceDto $resourceDto Resource DTO.
     * @return void
     */
    private function postResourceUpdate(
        UserAccessControl $uac,
        Resource $resource,
        array $secrets,
        MetadataResourceDto $resourceDto
    ): void {
        $eventData = [
            'resource' => $resource,
            'accessControl' => $uac,
            'secrets' => $secrets,
            'isV5' => $resourceDto->isV5(),
        ];
        $this->dispatchEvent(static::UPDATE_SUCCESS_EVENT_NAME, $eventData);
    }
}

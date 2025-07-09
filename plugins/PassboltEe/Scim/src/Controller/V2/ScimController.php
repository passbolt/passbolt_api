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
 * @since         4.1.0
 */

namespace Passbolt\Scim\Controller\V2;

use App\Controller\AppController;
use Cake\Event\EventInterface;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Exception\NotImplementedException;
use Cake\Log\Log;
use Cake\Routing\Router;
use Passbolt\Scim\Exception\ScimException;
use Passbolt\Scim\Log\ScimLog;
use Passbolt\Scim\Resources\ListResponse;
use Passbolt\Scim\Resources\ResourceTypeFactory;
use Passbolt\Scim\Resources\ResourceTypeInterface;
use Passbolt\Scim\Resources\Schema;
use Passbolt\Scim\Resources\ServiceProviderConfig;

class ScimController extends AppController
{
    public const STATUS_CREATED = 201;
    public const STATUS_BAD_REQUEST = 400;

    /**
     * Before Filter callback
     *
     * @param \Cake\Event\EventInterface $event Event
     * @return void
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        /** @todo change this to use token instead. Only unauthenticated should be /Schemas, /ServiceProviderConfig and /ResourceTypes */
        $this->Authentication->allowUnauthenticated(
            ['add', 'view','index', 'schemas', 'serviceProviderConfig', 'resourceTypes']
        );
        $this->disableAutoRender();
        $this->setResponse($this->getResponse()->withType('application/scim+json'));
    }

    /**
     * SCIM index action
     *
     * @param string $settingId Org Setting Id
     * @param string $resourceType Resource Type (User, Group)
     * @return void
     * @throws \Exception
     */
    public function index(string $settingId, string $resourceType): void
    {
        try {
            $listResponse = new ListResponse([], 1, 0);
            $listResponse->fetchAllResources($resourceType, $this->getRequest()->getQuery('filter'));
            $data = $listResponse->toSCIM();
            $this->processResponse($settingId, $data);
        } catch (\Exception $e) {
            $this->processException($settingId, $e);
        }
    }

    /**
     * SCIM view action
     *
     * @param string $settingId Org Setting Id
     * @param string $resourceType Resource Type (User, Group)
     * @param string $resourceId Resource Id
     * @return void
     */
    public function view(string $settingId, string $resourceType, string $resourceId): void
    {
        try {
            $resource = ResourceTypeFactory::build($resourceType);
            $data = $resource->getResource($resourceId)->toSCIM();
            $this->processResponse($settingId, $data);
        } catch (\Exception $e) {
            $this->processException($settingId, $e);
        }
    }

    /**
     * SCIM add action
     *
     * @param string $settingId Org Setting Id
     * @param string $resourceType Resource Type (User, Group)
     * @return void
     */
    public function add(string $settingId, string $resourceType): void
    {
        try {
            Log::debug($this->getRequest()->getUri()->getPath());
            /** @var \Passbolt\Scim\Resources\ResourceType\UserResourceType $resource */
            $resource = ResourceTypeFactory::build($resourceType);
            $resource->setFromScim($this->getRequest()->getData());
            $data = $resource->add()->toSCIM();
            $this->processResponse($settingId, $data, static::STATUS_CREATED);
        } catch (\Exception $e) {
            $this->processException($settingId, $e);
        }
    }

    /**
     * SCIM edit action
     *
     * @param string $settingId Org Setting Id
     * @param string $resourceType Resource Type (User, Group)
     * @param string $resourceId Resource Id
     * @return void
     */
    public function edit(string $settingId, string $resourceType, string $resourceId): void
    {
        throw new NotImplementedException('Missing SCIM action');
    }

    /**
     * Delete SCIM endpoint
     *  - DELETE /Users/
     *
     * @param string $settingId Org Setting Id
     * @param string $resourceType Resource Type (User, Group)
     * @param string $resourceId Resource Id
     * @return void
     */
    public function delete(string $settingId, string $resourceType, string $resourceId): void
    {
        throw new NotImplementedException('Missing SCIM action');
    }

    /**
     * /Schemas SCIM Endpoint (Unauthenticated)
     *  - GET /Schemas return User and Group Schemas
     *  - GET /Schemas/urn:ietf:params:scim:schemas:core:2.0:User return User Schema
     *  - GET /Schemas/urn:ietf:params:scim:schemas:core:2.0:Group return Group Schema
     *
     * @param string $settingId Org Setting Id
     * @param string|null $schemaId Schema Id
     * @return void
     */
    public function schemas(string $settingId, ?string $schemaId = null)
    {
        if ($schemaId && !isset(Schema::SCHEMA_MAPPING[$schemaId])) {
            throw new NotFoundException('Invalid Schema');
        }
        $schemaName = $schemaId ? Schema::SCHEMA_MAPPING[$schemaId] : 'ALL_SCHEMAS';
        $schema = constant("\Passbolt\Scim\Resources\Schema::$schemaName");
        if (!$schemaId) {
            $listResponse = new ListResponse($schema, 1, count($schema));
            $schema = $listResponse->toSCIM();
        }

        $this->processResponse($settingId, $schema);
    }

    /**
     * /ResourceTypes SCIM Endpoint (Unauthenticated)
     *
     * @param string $settingId Org Setting Id
     * @param string|null $resourceType Resource Type (User, Group)
     * @return void
     */
    public function resourceTypes(string $settingId, ?string $resourceType = null)
    {
        if ($resourceType && !isset(ResourceTypeInterface::RESOURCE_TYPE_MAPPING[$resourceType])) {
            throw new NotFoundException('Invalid Schema');
        }

        $resourceTypeName = $resourceType ?
            ResourceTypeInterface::RESOURCE_TYPE_MAPPING[$resourceType] :
            'ALL_RESOURCE_TYPES';
        $resourceTypeDefinition = constant(
            "\Passbolt\Scim\Resources\ResourceTypeInterface::$resourceTypeName"
        );
        if (!$resourceType) {
            $listResponse = new ListResponse($resourceTypeDefinition, 1, count($resourceTypeDefinition));
            $resourceTypeDefinition = $listResponse->toSCIM();
        }
        $this->processResponse($settingId, $resourceTypeDefinition);
    }

    /**
     * Process exception and create response
     *
     * @param string $settingId Setting id
     * @param \Exception $e Exception
     * @return void
     */
    protected function processException(string $settingId, \Exception $e)
    {
        $status = $e->getCode();
        $data = [
            'schemas' => [Schema::ERROR],
            'status' => $e->getCode(),
            'detail' => $e->getMessage(),
        ];
        if ($e instanceof ScimException) {
            $data['scimType'] = $e->getScimType();
        }

        $this->processResponse($settingId, $data, $status);
    }

    /**
     * Prepare SCIM response, it is called from every endpoint
     *  - Replace {scimUrl} placeholder from JSON response
     *
     * @param string $settingId Org Setting Id
     * @param array $data Response data
     * @param int $status Status code
     * @return void
     */
    protected function processResponse(string $settingId, array $data, int $status = 200)
    {
        $scimUrl = Router::url('scim/v2/' . $settingId, true);
        $json = str_replace('{scimUrl}', $scimUrl, json_encode($data, JSON_PRETTY_PRINT));
        $this->setResponse($this->getResponse()->withStringBody($json)->withStatus($status));
    }

    /**
     * /ServiceProviderConfig SCIM Endpoint (Unauthenticated)
     *
     * @param string $settingId Org Setting Id
     * @return void
     */
    public function serviceProviderConfig(string $settingId)
    {
        $this->processResponse($settingId, ServiceProviderConfig::CONFIG);
    }
}

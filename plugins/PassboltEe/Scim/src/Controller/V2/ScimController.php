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
use Cake\Routing\Router;
use Exception;
use Passbolt\Scim\Utility\Object\ErrorResponse;
use Passbolt\Scim\Utility\Object\ListResponse;
use Passbolt\Scim\Utility\Object\PatchRequest;
use Passbolt\Scim\Utility\Object\ServiceProviderConfig;
use Passbolt\Scim\Utility\Resources;
use Passbolt\Scim\Utility\ResourceTypes;
use Passbolt\Scim\Utility\Schemas;
use Passbolt\Scim\Utility\ScimObjectInterface;

class ScimController extends AppController
{
    public const STATUS_CREATED = 201;
    public const STATUS_EDITED = 200;
    public const STATUS_DELETED = 204;
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
        $this->Authentication->allowUnauthenticated(
            ['schemas', 'serviceProviderConfig', 'resourceTypes']
        );
        $this->disableAutoRender();
        $this->setResponse($this->getResponse()->withType('application/scim+json'));
    }

    /**
     * SCIM index action
     *
     * @param string $settingId Org Setting Id
     * @param string $resourceType Resource Type (Users, Groups)
     * @return void
     * @throws \Exception
     */
    public function index(string $settingId, string $resourceType): void
    {
        try {
            $queryParams = $this->getRequest()->getQueryParams();
            $startIndex = null;
            if (isset($queryParams['startIndex'])) {
                $startIndex = (int)$queryParams['startIndex'];
            }
            $count = null;
            if (isset($queryParams['count'])) {
                $count = (int)$queryParams['count'];
            }
            $filter = null;
            if (isset($queryParams['filter'])) {
                $filter = (string)$queryParams['filter'];
            }
            $listResponse = (new ListResponse())
                ->fetchResources(
                    $resourceType,
                    $startIndex,
                    $count,
                    $filter
                );
            $this->processResponse($settingId, $listResponse);
        } catch (Exception $e) {
            $this->processException($settingId, $e);
        }
    }

    /**
     * SCIM view action
     *
     * @param string $settingId Org Setting Id
     * @param string $resourceType Resource Type (Users, Groups)
     * @param string $resourceId Resource Id
     * @return void
     */
    public function view(string $settingId, string $resourceType, string $resourceId): void
    {
        try {
            $this->processResponse($settingId, Resources::build($resourceType)->setFromDatabase($resourceId));
        } catch (Exception $e) {
            $this->processException($settingId, $e);
        }
    }

    /**
     * SCIM add action
     *
     * @param string $settingId Org Setting Id
     * @param string $resourceType Resource Type (Users, Groups)
     * @return void
     */
    public function add(string $settingId, string $resourceType): void
    {
        try {
            $resource = Resources::build($resourceType)
                ->setFromScim($this->getRequest()->getData())
                ->create();
            $this->processResponse($settingId, $resource, static::STATUS_CREATED);
        } catch (Exception $e) {
            $this->processException($settingId, $e);
        }
    }

    /**
     * SCIM edit action
     *
     * @param string $settingId Org Setting Id
     * @param string $resourceType Resource Type (Users, Groups)
     * @param string $resourceId Resource Id
     * @return void
     */
    public function edit(string $settingId, string $resourceType, string $resourceId): void
    {
        try {
            $patchRequest = (new PatchRequest())->setFromScim($this->getRequest()->getData());
            $userResource = Resources::build($resourceType)->setFromDatabase($resourceId);
            $userResource->update($patchRequest);
            $this->processResponse($settingId, $userResource, static::STATUS_EDITED);
        } catch (Exception $e) {
            $this->processException($settingId, $e);
        }
    }

    /**
     * Delete SCIM endpoint
     *  - DELETE /Users/
     *
     * @param string $settingId Org Setting Id
     * @param string $resourceType Resource Type (Users, Groups)
     * @param string $resourceId Resource Id
     * @return void
     */
    public function delete(string $settingId, string $resourceType, string $resourceId): void
    {
        try {
            Resources::build($resourceType)
                ->setFromDatabase($resourceId)
                ->delete();
            $this->processResponse($settingId, [], static::STATUS_DELETED);
        } catch (Exception $e) {
            $this->processException($settingId, $e);
        }
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
        try {
            if ($schemaId && !Schemas::isValid($schemaId)) {
                throw new NotFoundException(sprintf('The Schema `%s` is invalid or not supported', $schemaId));
            }

            if ($schemaId) {
                $responseData = Schemas::build($schemaId);
            } else {
                $schemas = Schemas::getAll();
                $responseData = new ListResponse($schemas, totalResults: count($schemas));
            }
            $this->processResponse($settingId, $responseData);
        } catch (Exception $e) {
            $this->processException($settingId, $e);
        }
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
        try {
            if ($resourceType && !ResourceTypes::isValid($resourceType)) {
                throw new NotFoundException(
                    sprintf('The ResourceType `%s` is invalid or not supported', $resourceType)
                );
            }

            if ($resourceType) {
                $responseData = ResourceTypes::build($resourceType);
            } else {
                $resourceTypes = ResourceTypes::getAll();
                $responseData = new ListResponse($resourceTypes, totalResults: count($resourceTypes));
            }
            $this->processResponse($settingId, $responseData);
        } catch (Exception $e) {
            $this->processException($settingId, $e);
        }
    }

    /**
     * Process exception and create response
     *
     * @param string $settingId Setting id
     * @param \Exception $e Exception
     * @return void
     */
    protected function processException(string $settingId, Exception $e)
    {
        $status = $e->getCode();
        $this->processResponse($settingId, new ErrorResponse($e), $status);
    }

    /**
     * Prepare SCIM response, it is called from every endpoint
     *  - Replace {scimUrl} placeholder from JSON response
     *
     * @param string $settingId Org Setting Id
     * @param \Passbolt\Scim\Utility\ScimObjectInterface|array $data Response data
     * @param int $status Status code
     * @return void
     */
    protected function processResponse(string $settingId, ScimObjectInterface|array $data, int $status = 200)
    {
        if ($data instanceof ScimObjectInterface) {
            $data = $data->toSCIM();
        }
        $scimUrl = str_replace('"', '', json_encode(Router::url('scim/v2/' . $settingId, true)));
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
        $this->processResponse($settingId, new ServiceProviderConfig());
    }
}

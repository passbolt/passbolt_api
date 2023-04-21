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

namespace Passbolt\Scim\Resources;

use Cake\Datasource\EntityInterface;

/**
 * Interface that define the public methods for ResourceType classes
 */
interface ResourceTypeInterface
{
    /**
     * User resource type
     */
    public const USER_RESOURCE_TYPE = [
        'schemas' => [
            'urn:ietf:params:scim:schemas:core:2.0:ResourceType',
        ],
        'id' => 'User',
        'name' => 'User',
        'endpoint' => 'Users',
        'description' => 'https://tools.ietf.org/html/rfc7643#section-8.7.1',
        'schema' => 'urn:ietf:params:scim:schemas:core:2.0:User',
        'schemaExtensions' => [
            [
                'schema' => 'urn:ietf:params:scim:schemas:extension:enterprise:2.0:User',
                'required' => false,
            ],
        ],
        'meta' => [
            'location' => '{scimUrl}/ResourceTypes/User',
            'resourceType' => 'ResourceType',
        ],
    ];

    /**
     * Group resource type
     */
    public const GROUP_RESOURCE_TYPE = [
        'schemas' => [
            'urn:ietf:params:scim:schemas:core:2.0:ResourceType',
        ],
        'id' => 'Group',
        'name' => 'Group',
        'endpoint' => 'Groups',
        'description' => 'https://tools.ietf.org/html/rfc7643#section-8.7.1',
        'schema' => 'urn:ietf:params:scim:schemas:core:2.0:Group',
        'meta' => [
            'location' => '{scimUrl}/ResourceTypes/Group',
            'resourceType' => 'ResourceType',
        ],
    ];

    /**
     * All resource types
     */
    public const ALL_RESOURCE_TYPES = [
        self::USER_RESOURCE_TYPE,
        self::GROUP_RESOURCE_TYPE,
    ];

    public const RESOURCE_TYPE_MAPPING = [
        'User' => 'USER_RESOURCE_TYPE',
        'Group' => 'GROUP_RESOURCE_TYPE',
    ];

    /**
     * Create a resource type from the request data array
     *
     * @param array $data Data from SCIM
     * @return $this
     */
    public function setFromScim(array $data): self;

    /**
     * Create a resource type from the database record
     *
     * @param \Cake\Datasource\EntityInterface $entity Entity
     * @return $this
     */
    public function setFromDatabase(EntityInterface $entity): self;

    /**
     * Create a resource in the database
     *
     * @return $this
     * @throws \Exception
     */
    public function add(): self;

    /**
     * Get a resource from database
     *
     * @param string $resourceId Resource Id
     * @return $this
     * @throws \Exception
     */
    public function getResource(string $resourceId): self;

    /**
     * Create an SCIM compatible array
     *
     * @return array
     */
    public function toSCIM(): array;
}

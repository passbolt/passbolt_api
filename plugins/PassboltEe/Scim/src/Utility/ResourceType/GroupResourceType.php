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

namespace Passbolt\Scim\Utility\ResourceType;

use Passbolt\Scim\Utility\SchemaIdentifier;
use Passbolt\Scim\Utility\ScimObjectInterface;

/**
 * GroupResourceType class
 */
class GroupResourceType implements ScimObjectInterface
{
    /**
     * @inheritDoc
     */
    public function toSCIM(): array
    {
        return [
            'schemas' => [
                SchemaIdentifier::CORE_RESOURCE_TYPE,
            ],
            'id' => 'Group',
            'name' => 'Group',
            'endpoint' => 'Groups',
            'description' => 'https://tools.ietf.org/html/rfc7643#section-8.7.1',
            'schema' => SchemaIdentifier::CORE_GROUP,
            'meta' => [
                'location' => '{scimUrl}/ResourceTypes/Group',
                'resourceType' => 'ResourceType',
            ],
        ];
    }
}

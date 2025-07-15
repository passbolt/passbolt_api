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

namespace Passbolt\Scim\Utility\Schema;

use Passbolt\Scim\Utility\SchemaIdentifier;
use Passbolt\Scim\Utility\ScimObjectInterface;

/**
 * Group Resource Schema
 */
class GroupSchema implements ScimObjectInterface
{
    /**
     * @inheritDoc
     */
    public function toSCIM(): array
    {
        $schemaIdentifier = SchemaIdentifier::CORE_GROUP;

        return [
            'schemas' => [
                SchemaIdentifier::CORE_SCHEMA,
            ],
            'id' => $schemaIdentifier,
            'name' => 'Group',
            'description' => 'Group',
            'attributes' => [
                [
                    'name' => 'displayName',
                    'type' => 'string',
                    'multiValued' => false,
                    'description' => 'A human-readable name for the Group. REQUIRED.',
                    'required' => true,
                    'caseExact' => false,
                    'mutability' => 'readWrite',
                    'returned' => 'default',
                    'uniqueness' => 'server',
                ],
            ],
            'meta' => [
                'resourceType' => 'Schema',
                'location' => '{scimUrl}/Schemas/' . $schemaIdentifier,
            ],
        ];
    }
}

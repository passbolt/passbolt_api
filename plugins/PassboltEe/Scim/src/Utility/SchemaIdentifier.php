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

namespace Passbolt\Scim\Utility;

/**
 * Class with the identifier of the SCIM objects schemas
 */
class SchemaIdentifier
{
    public const API_BULK_REQUEST = 'urn:ietf:params:scim:api:messages:2.0:BulkRequest';
    public const API_BULK_RESPONSE = 'urn:ietf:params:scim:api:messages:2.0:BulkResponse';
    public const API_ERROR = 'urn:ietf:params:scim:api:messages:2.0:Error';
    public const API_LIST_RESPONSE = 'urn:ietf:params:scim:api:messages:2.0:ListResponse';
    public const API_PATCH_OPERATION = 'urn:ietf:params:scim:api:messages:2.0:PatchOp';
    public const CORE_RESOURCE_TYPE = 'urn:ietf:params:scim:schemas:core:2.0:ResourceType';
    public const CORE_SERVICE_PROVIDER_CONFIG = 'urn:ietf:params:scim:schemas:core:2.0:ServiceProviderConfig';
    public const CORE_SCHEMA = 'urn:ietf:params:scim:schemas:core:2.0:Schema';
    public const CORE_USER = 'urn:ietf:params:scim:schemas:core:2.0:User';
    public const CORE_GROUP = 'urn:ietf:params:scim:schemas:core:2.0:Group';
    public const EXTENSION_ENTERPRISE_USER = 'urn:ietf:params:scim:schemas:extension:enterprise:2.0:User';
}

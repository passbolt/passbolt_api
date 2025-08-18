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
 * @since         5.5.0
 */

namespace Passbolt\Scim\Controller\V2;

use Cake\Http\Exception\NotFoundException;
use Exception;
use Passbolt\Scim\Utility\Object\ListResponse;
use Passbolt\Scim\Utility\Schemas;

class ScimSchemasController extends ScimController
{
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
    public function schemas(string $settingId, ?string $schemaId = null): void
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
}

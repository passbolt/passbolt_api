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
use Passbolt\Scim\Utility\ScimResourceTypes;

class ScimResourceTypesController extends ScimController
{
    /**
     * /ResourceTypes SCIM Endpoint (Unauthenticated)
     *
     * @param string $settingId Org Setting Id
     * @param string|null $resourceType Resource Type (User, Group)
     * @return void
     */
    public function resourceTypes(string $settingId, ?string $resourceType = null): void
    {
        try {
            if ($resourceType && !ScimResourceTypes::isValid($resourceType)) {
                throw new NotFoundException(
                    sprintf('The ResourceType `%s` is invalid or not supported', $resourceType)
                );
            }

            if ($resourceType) {
                $responseData = ScimResourceTypes::build($resourceType);
            } else {
                $resourceTypes = ScimResourceTypes::getAll();
                $responseData = new ListResponse($resourceTypes, totalResults: count($resourceTypes));
            }
            $this->processResponse($settingId, $responseData);
        } catch (Exception $e) {
            $this->processException($settingId, $e);
        }
    }
}

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

use Exception;
use Passbolt\Scim\Utility\Object\PatchRequest;
use Passbolt\Scim\Utility\ScimResources;

class ScimUpdateController extends ScimController
{
    /**
     * SCIM edit action
     *
     * @param string $settingId Org Setting Id
     * @param string $resourceType Resource Type (Users, Groups)
     * @param string $resourceId Resource Id
     * @return void
     */
    public function update(string $settingId, string $resourceType, string $resourceId): void
    {
        try {
            $patchRequest = (new PatchRequest())->setFromScim($this->getRequest()->getData());
            $userResource = ScimResources::build($resourceType)
                ->setFromDatabase($resourceId)
                ->update($patchRequest);
            $this->processResponse($settingId, $userResource, static::STATUS_EDITED);
        } catch (Exception $e) {
            $this->processException($settingId, $e);
        }
    }
}

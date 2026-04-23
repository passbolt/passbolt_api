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
use Passbolt\Scim\Utility\ScimResources;

class ScimViewController extends AbstractScimController
{
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
            $this->processResponse($settingId, ScimResources::build($resourceType)->setFromDatabase($resourceId));
        } catch (Exception $e) {
            $this->processException($settingId, $e);
        }
    }
}

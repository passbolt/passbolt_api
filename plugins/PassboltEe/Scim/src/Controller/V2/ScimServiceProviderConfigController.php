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

use Passbolt\Scim\Utility\Object\ServiceProviderConfig;

class ScimServiceProviderConfigController extends AbstractScimController
{
    /**
     * /ServiceProviderConfig SCIM Endpoint (Unauthenticated)
     *
     * @param string $settingId Org Setting Id
     * @return void
     */
    public function serviceProviderConfig(string $settingId): void
    {
        $this->processResponse($settingId, new ServiceProviderConfig());
    }
}

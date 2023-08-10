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
 * @since         3.9.0
 */

namespace Passbolt\Sso\Model\Dto;

use Passbolt\Sso\Service\Providers\SsoActiveProvidersGetService;

class SsoSettingsDefaultDto extends AbstractSsoSettingsDto
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->providers = (new SsoActiveProvidersGetService())->get();
        $this->provider = null;
        $this->id = null;
        $this->status = null;
        $this->data = null;
        $this->created = null;
        $this->modified = null;
        $this->created_by = null;
        $this->modified_by = null;
    }

    /**
     * @return array serialize data
     */
    public function toArray(): array
    {
        return [
            'provider' => $this->getProvider(),
            'providers' => $this->getProviders(),
        ];
    }
}

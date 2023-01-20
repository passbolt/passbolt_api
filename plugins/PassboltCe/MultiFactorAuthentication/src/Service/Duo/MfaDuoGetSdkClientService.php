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
 * @since         3.11.0
 */

namespace Passbolt\MultiFactorAuthentication\Service\Duo;

use Cake\Http\Exception\InternalErrorException;
use Cake\Routing\Router;
use Duo\DuoUniversal\Client;
use Duo\DuoUniversal\DuoException;
use Passbolt\MultiFactorAuthentication\Service\MfaOrgSettings\MfaOrgSettingsDuoService;

/**
 * Class MfaDuoGetSdkClientService
 */
class MfaDuoGetSdkClientService
{
    /**
     * Get the Duo Sdk Client object or fail.
     *
     * @param \Passbolt\MultiFactorAuthentication\Service\MfaOrgSettings\MfaOrgSettingsDuoService $settings Duo org settings
     * @return \Duo\DuoUniversal\Client
     * @throws \Cake\Http\Exception\InternalErrorException If it cannot instantiate the Duo Sdk client.
     */
    public function getOrFail(MfaOrgSettingsDuoService $settings): Client
    {
        try {
            return new Client(
                $settings->getDuoClientId(),
                $settings->getDuoClientSecret(),
                $settings->getDuoApiHostname(),
                Router::url('/mfa/setup/duo/callback', true),
                true,
            );
        } catch (DuoException $e) {
            throw new InternalErrorException(__('Could not validate the Duo settings.'), null, $e);
        }
    }
}

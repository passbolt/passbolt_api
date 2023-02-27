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
 * @since         3.7.3
 */

namespace Passbolt\MultiFactorAuthentication\Service\MfaOrgSettings;

use App\Utility\UserAccessControl;
use Duo\DuoUniversal\Client;
use Passbolt\MultiFactorAuthentication\Utility\MfaOrgSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class MfaOrgSettingsSetService
{
    /**
     * @param array $data data in the payload
     * @param \App\Utility\UserAccessControl $uac UAC
     * @param \Duo\DuoUniversal\Client|null $duoClient Duo SDK Client
     * @param array $options Options used to save & validate organisation settings
     * @return array Organization Settings configs
     * @throws \Cake\Http\Exception\InternalErrorException if the UAC changed during the request (improbable)
     * @throws \App\Error\Exception\CustomValidationException in case of validation error
     */
    public function setOrgSettings(
        array $data,
        UserAccessControl $uac,
        ?Client $duoClient = null,
        array $options = []
    ): array {
        $mfaSettings = MfaSettings::get($uac);

        // Allow some flexibility in inputs names
        $provider = MfaSettings::PROVIDER_DUO;
        $hostname = MfaOrgSettings::DUO_API_HOSTNAME;
        if (isset($data[$provider]['hostname'])) {
            $data[$provider][$hostname] = $data[$provider]['hostname'];
        }

        $orgSettings = $mfaSettings->getOrganizationSettings();
        $orgSettings->save($data, $uac, $duoClient, $options);

        return $mfaSettings->getOrganizationSettings()->getConfig();
    }
}

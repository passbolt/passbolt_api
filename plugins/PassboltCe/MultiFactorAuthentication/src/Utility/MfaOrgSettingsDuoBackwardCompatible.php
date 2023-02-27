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
 * @since         3.10.0
 */

namespace Passbolt\MultiFactorAuthentication\Utility;

class MfaOrgSettingsDuoBackwardCompatible
{
    /**
     * @param array $settings Settings payload
     * @param string $from Setting key in payload before rename
     * @param string $to Setting key in payload after rename
     * @return array Organization Settings configs
     */
    private static function remapSetting(array $settings, string $from, string $to): array
    {
        if (isset($settings[MfaSettings::PROVIDER_DUO][$from])) {
            $settings[MfaSettings::PROVIDER_DUO][$to] = $settings[MfaSettings::PROVIDER_DUO][$from];
            unset($settings[MfaSettings::PROVIDER_DUO][$from]);
        }

        return $settings;
    }

    /**
     * This function is TEMPORARY. It is called when trying to READ organization settings.
     * Its purpose is to map Duo organization settings from the old fields to the new fields.
     * Remove this function once the frontend is using the new fields format.
     *
     * @param array $config config payload
     * @return array Organization Settings configs
     */
    public static function remapGetDuoSettings(array $config): array
    {
        $config = self::remapSetting($config, MfaOrgSettings::DUO_CLIENT_ID, 'integrationKey');
        $config = self::remapSetting($config, MfaOrgSettings::DUO_CLIENT_SECRET, 'secretKey');
        $config = self::remapSetting($config, MfaOrgSettings::DUO_API_HOSTNAME, 'hostName');

        return $config;
    }

    /**
     * This function is TEMPORARY. It is called when trying to UPDATE organization settings.
     * Its purpose is to map Duo organization settings from the old fields to the new fields.
     * Remove this function once the frontend is using the new fields format.
     *
     * @param array $data data in the payload
     * @return array Organization Settings configs
     */
    public static function remapSetDuoSettings(array $data): array
    {
        $data = self::remapSetting($data, 'integrationKey', MfaOrgSettings::DUO_CLIENT_ID);
        $data = self::remapSetting($data, 'secretKey', MfaOrgSettings::DUO_CLIENT_SECRET);
        $data = self::remapSetting($data, 'hostName', MfaOrgSettings::DUO_API_HOSTNAME);
        unset($data[MfaSettings::PROVIDER_DUO]['salt']);

        return $data;
    }
}

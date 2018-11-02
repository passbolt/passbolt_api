<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.5.0
 */
namespace Passbolt\MultiFactorAuthentication\Utility;

use Cake\Datasource\Exception\RecordNotFoundException;

trait MfaOrgSettingsYubikeyTrait
{
    /**
     * @throw RecordNotFoundException if config is missing
     * @return string
     */
    public function getYubikeyOTPSecretKey()
    {
        if (!isset($this->settings[MfaSettings::PROVIDER_YUBIKEY]['secretKey'])) {
            throw new RecordNotFoundException(__('No configuration set for Yubikey OTP secret key.'));
        }

        return $this->settings[MfaSettings::PROVIDER_YUBIKEY]['secretKey'];
    }

    /**
     * @throw RecordNotFoundException if config is missing
     * @return string
     */
    public function getYubikeyOTPClientId()
    {
        if (!isset($this->settings[MfaSettings::PROVIDER_YUBIKEY]['clientId'])) {
            throw new RecordNotFoundException(__('No configuration set for Yubikey OTP clientId.'));
        }

        return $this->settings[MfaSettings::PROVIDER_YUBIKEY]['clientId'];
    }
}

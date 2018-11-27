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

use App\Error\Exception\CustomValidationException;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Validation\Validation;

trait MfaOrgSettingsYubikeyTrait
{
    /**
     * @throw RecordNotFoundException if config is missing
     * @return string
     */
    public function getYubikeyOTPSecretKey()
    {
        if (!isset($this->settings[MfaSettings::PROVIDER_YUBIKEY][MfaOrgSettings::YUBIKEY_SECRET_KEY])) {
            throw new RecordNotFoundException(__('No configuration set for Yubikey OTP secret key.'));
        }

        return $this->settings[MfaSettings::PROVIDER_YUBIKEY][MfaOrgSettings::YUBIKEY_SECRET_KEY];
    }

    /**
     * @throw RecordNotFoundException if config is missing
     * @return string
     */
    public function getYubikeyOTPClientId()
    {
        if (!isset($this->settings[MfaSettings::PROVIDER_YUBIKEY][MfaOrgSettings::YUBIKEY_CLIENT_ID])) {
            throw new RecordNotFoundException(__('No configuration set for Yubikey OTP clientId.'));
        }

        return $this->settings[MfaSettings::PROVIDER_YUBIKEY][MfaOrgSettings::YUBIKEY_CLIENT_ID];
    }

    /**
     * @throw CustomValidationException if there is an issue
     * @param array $data user provider data
     */
    public function validateYubikeySettings(array $data)
    {
        $msg = __('Could not validate Yubikey configuration.');
        $errors = [];
        if (!isset($data[MfaSettings::PROVIDER_YUBIKEY][MfaOrgSettings::YUBIKEY_CLIENT_ID])) {
            $errors[MfaSettings::PROVIDER_YUBIKEY][MfaOrgSettings::YUBIKEY_CLIENT_ID] = __('No configuration set for Yubikey OTP clientId.');
        } else {
            $clientID = $data[MfaSettings::PROVIDER_YUBIKEY][MfaOrgSettings::YUBIKEY_CLIENT_ID];
            if (!Validation::custom($clientID, '/^[0-9]{1,64}$/')) {
                $errors[MfaSettings::PROVIDER_YUBIKEY][MfaOrgSettings::YUBIKEY_CLIENT_ID] = __('Yubikey OTP clientId should be an integer.');
            }
        }
        if (!isset($data[MfaSettings::PROVIDER_YUBIKEY][MfaOrgSettings::YUBIKEY_SECRET_KEY])) {
            $errors[MfaSettings::PROVIDER_YUBIKEY][MfaOrgSettings::YUBIKEY_SECRET_KEY] = __('No configuration set for Yubikey OTP secret key.');
        } else {
            $secretKey = $data[MfaSettings::PROVIDER_YUBIKEY][MfaOrgSettings::YUBIKEY_SECRET_KEY];
            if (!Validation::custom($secretKey, '/^[a-zA-Z0-9\/=]{10,128}$/')) {
                $errors[MfaSettings::PROVIDER_YUBIKEY][MfaOrgSettings::YUBIKEY_SECRET_KEY] = __('Yubikey OTP secret key is not valid.');
            }
        }
        if (count($errors) !== 0) {
            throw new CustomValidationException($msg, $errors);
        }
    }
}

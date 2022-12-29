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
 * @since         2.5.0
 */
namespace Passbolt\MultiFactorAuthentication\Utility;

use App\Error\Exception\CustomValidationException;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Validation\Validation;

trait MfaOrgSettingsYubikeyTrait
{
    /**
     * getYubikeyOTPSecretKey
     *
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
     * getYubikeyOTPClientId
     *
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
     * validateYubikeySettings
     *
     * @throw CustomValidationException if there is an issue
     * @param array $data user provider data
     * @return void
     */
    public function validateYubikeySettings(array $data)
    {
        $errors = [];

        if (!isset($data[MfaSettings::PROVIDER_YUBIKEY][MfaOrgSettings::YUBIKEY_CLIENT_ID])) {
            $msg = __('No configuration set for Yubikey OTP clientId.');
            $errors[MfaSettings::PROVIDER_YUBIKEY][MfaOrgSettings::YUBIKEY_CLIENT_ID]['notEmpty'] = $msg;
        } else {
            $clientID = $data[MfaSettings::PROVIDER_YUBIKEY][MfaOrgSettings::YUBIKEY_CLIENT_ID];
            if (!Validation::custom($clientID, '/^[0-9]{1,64}$/')) {
                $msg = __('Yubikey OTP clientId should be an integer.');
                $errors[MfaSettings::PROVIDER_YUBIKEY][MfaOrgSettings::YUBIKEY_CLIENT_ID]['isValidClientId'] = $msg;
            }
        }

        if (!isset($data[MfaSettings::PROVIDER_YUBIKEY][MfaOrgSettings::YUBIKEY_SECRET_KEY])) {
            $msg = __('No configuration set for Yubikey OTP secret key.');
            $errors[MfaSettings::PROVIDER_YUBIKEY][MfaOrgSettings::YUBIKEY_SECRET_KEY]['notEmpty'] = $msg;
        } else {
            $secretKey = $data[MfaSettings::PROVIDER_YUBIKEY][MfaOrgSettings::YUBIKEY_SECRET_KEY];
            if (!Validation::custom($secretKey, '/^[a-zA-Z0-9\/=\+]{10,128}$/')) {
                $msg = __('Yubikey OTP secret key is not valid.');
                $errors[MfaSettings::PROVIDER_YUBIKEY][MfaOrgSettings::YUBIKEY_SECRET_KEY]['isValidSecretKey'] = $msg;
            }
        }

        if (count($errors) !== 0) {
            $msg = __('Could not validate Yubikey configuration.');
            throw new CustomValidationException($msg, $errors);
        }
    }
}

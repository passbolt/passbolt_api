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

trait MfaOrgSettingsDuoTrait
{

    /**
     * @throw RecordNotFoundException if config is missing
     * @return string
     */
    public function getDuoIntegrationKey()
    {
        if (!isset($this->settings[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_INTEGRATION_KEY])) {
            throw new RecordNotFoundException(__('No configuration set for Duo integration key.'));
        }

        return $this->settings[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_INTEGRATION_KEY];
    }

    /**
     * @throw RecordNotFoundException if config is missing
     * @return string
     */
    public function getDuoHostname()
    {
        if (!isset($this->settings[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_HOSTNAME])) {
            throw new RecordNotFoundException(__('No configuration set for Duo host name.'));
        }

        return $this->settings[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_HOSTNAME];
    }

    /**
     * @throw RecordNotFoundException if config is missing
     * @return string
     */
    public function getDuoSecretKey()
    {
        if (!isset($this->settings[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_SECRET_KEY])) {
            throw new RecordNotFoundException(__('No configuration set for Duo secret key.'));
        }

        return $this->settings[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_SECRET_KEY];
    }

    /**
     * @throw RecordNotFoundException if config is missing
     * @return string
     */
    public function getDuoSalt()
    {
        if (!isset($this->settings[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_SALT])) {
            throw new RecordNotFoundException(__('No configuration set for Duo salt.'));
        }

        return $this->settings[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_SALT];
    }

    /**
     * @throw CustomValidationException if there is an issue
     * @param array $data user provider data
     */
    public function validateDuoSettings(array $data)
    {
        $errors = [];

        if (!isset($data[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_SALT])) {
            $msg = __('No configuration set for Duo salt.');
            $errors[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_SALT]['notEmpty'] = $msg;
        } else {
            $salt = $data[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_SALT];
            if (!Validation::lengthBetween($salt, 40, 128)) {
                $msg = __('Duo salt should be between 40 and 128 characters in length.');
                $errors[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_SALT]['lengthBetween'] = $msg;
            }
        }

        if (!isset($data[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_SECRET_KEY])) {
            $msg = __('No configuration set for Duo secret key.');
            $errors[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_SECRET_KEY]['notEmpty'] = $msg;
        } else {
            $secretKey = $data[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_SECRET_KEY];
            if (!Validation::custom($secretKey, '/^[a-zA-Z0-9]{32,128}$/')) {
                $msg = __('This is not a valid Duo secret key.');
                $errors[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_SECRET_KEY]['isValidSecretKey'] = $msg;
            }
        }

        if (!isset($data[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_HOSTNAME])) {
            $msg = __('No configuration set for Duo host name.');
            $errors[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_HOSTNAME]['notEmpty'] = $msg;
        } else {
            $hostname = $data[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_HOSTNAME];
            if (!Validation::custom($hostname, '/^api-[a-fA-F0-9]{8,16}\.duosecurity\.com$/')) {
                $msg = __('This is not a valid Duo host name.');
                $errors[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_HOSTNAME]['isValidHostname'] = $msg;
            }
        }

        if (!isset($data[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_INTEGRATION_KEY])) {
            $msg = __('No configuration set for Duo integration key.');
            $errors[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_INTEGRATION_KEY]['notEmpty'] = $msg;
        } else {
            $integrationKey = $data[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_INTEGRATION_KEY];
            if (!Validation::custom($integrationKey, '/^[a-zA-Z0-9]{16,32}$/')) {
                $msg = __('This is not a valid Duo integration key.');
                $errors[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_INTEGRATION_KEY]['isValidIntegrationKey'] = $msg;
            }
        }

        if (count($errors) !== 0) {
            $msg = __('Could not validate Duo configuration');
            throw new CustomValidationException($msg, $errors);
        }
    }
}

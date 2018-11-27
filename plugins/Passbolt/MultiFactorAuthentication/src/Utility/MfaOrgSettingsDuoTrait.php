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
        $msg = __('Could not validate Duo configuration');
        $errors = [];
        if (!isset($data[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_SALT])) {
            $errors[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_SALT] = __('No configuration set for Duo salt.');
        }
        if (!isset($data[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_SECRET_KEY])) {
            $errors[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_SECRET_KEY] = __('No configuration set for Duo secret key.');
        }
        if (!isset($data[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_HOSTNAME])) {
            $errors[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_HOSTNAME] = __('No configuration set for Duo host name.');
        }
        if (!isset($data[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_INTEGRATION_KEY])) {
            $errors[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_INTEGRATION_KEY] = __('No configuration set for Duo integration key.');
        }
        if (count($errors) !== 0) {
            throw new CustomValidationException($msg, $errors);
        }
    }
}

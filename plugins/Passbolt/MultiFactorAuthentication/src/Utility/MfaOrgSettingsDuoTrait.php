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

trait MfaOrgSettingsDuoTrait
{
    /**
     * @throw RecordNotFoundException if config is missing
     * @return string
     */
    public function getDuoIntegrationKey()
    {
        if (!isset($this->settings[MfaSettings::PROVIDER_DUO]['integrationKey'])) {
            throw new RecordNotFoundException(__('No configuration set for Duo integration key.'));
        }

        return $this->settings[MfaSettings::PROVIDER_DUO]['integrationKey'];
    }

    /**
     * @throw RecordNotFoundException if config is missing
     * @return string
     */
    public function getDuoHostname()
    {
        if (!isset($this->settings[MfaSettings::PROVIDER_DUO]['hostName'])) {
            throw new RecordNotFoundException(__('No configuration set for Duo host name.'));
        }

        return $this->settings[MfaSettings::PROVIDER_DUO]['hostName'];
    }

    /**
     * @throw RecordNotFoundException if config is missing
     * @return string
     */
    public function getDuoSecretKey()
    {
        if (!isset($this->settings[MfaSettings::PROVIDER_DUO]['secretKey'])) {
            throw new RecordNotFoundException(__('No configuration set for Duo secret key.'));
        }

        return $this->settings[MfaSettings::PROVIDER_DUO]['secretKey'];
    }

    /**
     * @throw RecordNotFoundException if config is missing
     * @return string
     */
    public function getDuoSalt()
    {
        if (!isset($this->settings[MfaSettings::PROVIDER_DUO]['salt'])) {
            throw new RecordNotFoundException(__('No configuration set for Duo salt.'));
        }

        return $this->settings[MfaSettings::PROVIDER_DUO]['salt'];
    }
}

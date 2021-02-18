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

use Cake\Datasource\Exception\RecordNotFoundException;

trait MfaAccountSettingsTotpTrait
{
    /**
     * Return OTP provisioning url
     *
     * @throws \Cake\Datasource\Exception\RecordNotFoundException if URI is not set
     * @return string
     */
    public function getOtpProvisioningUri()
    {
        if (!isset($this->settings[MfaSettings::PROVIDER_TOTP][MfaAccountSettings::OTP_PROVISIONING_URI])) {
            throw new RecordNotFoundException(__('MFA setting OTP provisioning uri is not set.'));
        }

        return $this->settings[MfaSettings::PROVIDER_TOTP][MfaAccountSettings::OTP_PROVISIONING_URI];
    }

    /**
     * Return true if otp provisioning uri is set
     *
     * @return bool
     */
    public function isOtpProvisioningUriSet()
    {
        return isset($this->settings[MfaSettings::PROVIDER_TOTP][MfaAccountSettings::OTP_PROVISIONING_URI]);
    }
}

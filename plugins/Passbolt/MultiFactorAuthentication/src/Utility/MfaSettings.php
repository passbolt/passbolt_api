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
 * @since         2.4.0
 */
namespace Passbolt\MultiFactorAuthentication\Utility;
use App\Utility\UserAccessControl;
use Passbolt\AccountSettings\Model\Entity\AccountSetting;
use Cake\ORM\TableRegistry;

class MfaSettings
{
    const MFA = 'mfa';
    const PROVIDER = 'provider';
    const PROVIDER_OTP = 'otp';
    const VERIFIED = 'verified';
    const OTP_PROVISIONING_URI = 'otpProvisioningUri';

    protected $settings;
    protected $original;
    protected $uac;
    protected $errors;

    public function __construct(UserAccessControl $uac, $settings = null) {
        $this->uac = $uac;
        if ($settings instanceof AccountSetting) {
            $this->original = $settings;
            if (preg_match('/^[\[\{]\"/', $settings->value)) {
                $decodedJson = json_decode($settings->value, true);
                if (!is_null($decodedJson)) {
                    $this->settings = $decodedJson;
                }
            }
            return;
        }
        if (is_array($settings)) {
            $this->settings = $settings;
        }
        $this->errors = null;
    }

    public function getCreated()
    {
        if (isset($this->original->created)) {
            return $this->original->created;
        }
        return null;
    }

    public function getModified()
    {
        if (isset($this->original->modified)) {
            return $this->original->modified;
        }
        return null;
    }

    public function isReadyToUse()
    {
        if (!$this->isProviderSet() || !$this->isVerified()) {
            return false;
        }
        switch ($this->getProvider()) {
            case self::PROVIDER_OTP:
                return ($this->isOtpProvisioningUriSet());
                break;
            default:
                return true;
        }
    }

    public function toJson()
    {
        return json_encode($this->settings);
    }

    public function toArray()
    {
        return $this->settings;
    }

    public function getProvider()
    {
        return $this->settings[self::PROVIDER];
    }

    public function isProviderSet()
    {
        return (isset($this->settings[self::PROVIDER]));
    }

    public function isVerified()
    {
        return (isset($this->settings[self::VERIFIED]) && $this->settings[self::VERIFIED]);
    }

    public function getOtpProvisioningUri()
    {
        return $this->settings[self::PROVIDER_OTP][self::OTP_PROVISIONING_URI];
    }

    public function isOtpProvisioningUriSet()
    {
        return (isset($this->settings[self::PROVIDER_OTP][self::OTP_PROVISIONING_URI]));
    }

    /**
     * @param UserAccessControl $uac
     * @throw RecordNotFoundException
     * @return MfaSettings
     */
    static public function get(UserAccessControl $uac)
    {
        $AccountSettings = TableRegistry::get('Passbolt/AccountSettings.AccountSettings');
        $settings = $AccountSettings->getFirstPropertyOrFail($uac->getId(), MfaSettings::MFA);
        return new MfaSettings($uac, $settings);
    }

    public function save()
    {
        $AccountSettings = TableRegistry::get('Passbolt/AccountSettings.AccountSettings');
        $AccountSettings->createOrUpdateSetting($this->uac->getId(), self::MFA, $this->toJson());
    }
}
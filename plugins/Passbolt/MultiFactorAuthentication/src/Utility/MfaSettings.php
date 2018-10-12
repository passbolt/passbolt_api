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
use App\Error\Exception\ValidationException;
use App\Utility\UserAccessControl;
use Passbolt\AccountSettings\Model\Entity\AccountSetting;
use Cake\ORM\TableRegistry;
use Passbolt\AccountSettings\Model\Table\AccountSettingsTable;

class MfaSettings
{
    const MFA = 'mfa';
    const PROVIDERS = 'providers';
    const PROVIDER_OTP = 'otp';
    const VERIFIED = 'verified';
    const OTP_PROVISIONING_URI = 'otpProvisioningUri';

    protected $settings;
    protected $original;
    protected $uac;
    protected $errors;
    protected $remember;

    /**
     * @var AccountSettingsTable
     */
    protected $AccountSettings;

    public function __construct(UserAccessControl $uac, $settings = null) {
        $this->AccountSettings = TableRegistry::get('Passbolt/AccountSettings.AccountSettings');
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

    public function isReadyToUse($provider = null)
    {
        if (!$this->isProviderSet() || !$this->isVerified()) {
            return false;
        }
        if (isset($provider) && $this->getProvider() !== $provider) {
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
        return $this->settings[self::PROVIDERS][0];
    }

    public function getProviders()
    {
        return $this->settings[self::PROVIDERS];
    }

    public function isProviderSet()
    {
        return (isset($this->settings[self::PROVIDERS]) && count($this->settings[self::PROVIDERS]));
    }

    public function isVerified()
    {
        foreach($this->settings[self::PROVIDERS] as $provider) {
            if (isset($this->settings[$provider])) {
                return $this->settings[$provider][self::VERIFIED];
            }
            return false;
        }
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

    public function delete()
    {
        $this->AccountSettings->deleteByProperty($this->uac->getId(), self::MFA);
    }

    public function save()
    {
        $this->AccountSettings->createOrUpdateSetting($this->uac->getId(), self::MFA, $this->toJson());
    }

    public function disableProvider($providerToDisable = self::PROVIDER_OTP)
    {
        $providers = $this->settings[self::PROVIDERS];
        foreach($providers as $i => $provider) {
            if ($provider === $providerToDisable) {
                unset($this->settings[self::PROVIDERS][$i]);
                unset($this->settings[$providerToDisable]);
                if (!count($this->settings[self::PROVIDERS])) {
                    $this->delete();
                } else {
                    $this->save();
                }
            }
        }
    }
}
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

use App\Error\Exception\CustomValidationException;
use App\Error\Exception\ValidationException;
use App\Utility\UserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\I18n\FrozenTime;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Passbolt\AccountSettings\Model\Table\AccountSettingsTable;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\InternalErrorException;

class MfaAccountSettings
{
    const VERIFIED = 'verified';
    const PROVIDERS = 'providers';
    const OTP_PROVISIONING_URI = 'otpProvisioningUri';

    private static $instance;

    protected $settings;
    protected $uac;
    protected $errors;
    protected $remember;

    /**
     * @var AccountSettingsTable
     */
    protected $AccountSettings;

    /**
     * MfaSettings constructor.
     *
     * @param UserAccessControl $uac
     * @param mixed $settings or Array
     */
    public function __construct(UserAccessControl $uac, array $settings = null)
    {
        $this->AccountSettings = TableRegistry::get('Passbolt/AccountSettings.AccountSettings');
        $this->uac = $uac;
        $this->settings = $settings;
        $this->errors = null;
    }

    /**
     * Return true if a provider setting is ready to use
     *
     * @param string $provider
     * @return bool
     */
    public function isProviderReady($provider)
    {
        if (!isset($this->settings[$provider])) {
            return false;
        }
        if (!isset($this->settings[$provider][self::VERIFIED])) {
            return false;
        }
        switch ($provider) {
            case MfaSettings::PROVIDER_TOTP:
                return ($this->isOtpProvisioningUriSet());
                break;
            default:
                return true;
        }
    }

    /**
     * Return MfaSettings as JSON object
     *
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->settings);
    }

    /**
     * Return MfaSettings as an array
     *
     * @return array|null
     */
    public function toArray()
    {
        return $this->settings;
    }

    /**
     * Return the default mfa provider
     *
     * @throws RecordNotFoundException if there is no provider set
     * @return string
     */
    public function getDefaultProvider()
    {
        if ($this->isOneProviderSet()) {
            return $this->settings[self::PROVIDERS][0];
        }
        throw new RecordNotFoundException(__('No default MFA provider set.'));
    }

    /**
     * Return all providers
     *
     * @throws RecordNotFoundException if there is no provider set
     * @return array list of providers
     */
    public function getProviders()
    {
        if (!isset($this->settings[self::PROVIDERS])) {
            throw new RecordNotFoundException(__('No MFA provider set.'));
        }
        return $this->settings[self::PROVIDERS];
    }

    /**
     * Return tru if at least one provider is set
     *
     * @return bool
     */
    public function isOneProviderSet()
    {
        return (isset($this->settings[self::PROVIDERS]) && count($this->settings[self::PROVIDERS]));
    }

    /**
     * Return true if at least one provider is ready to use
     *
     * @return bool
     */
    public function isReady()
    {
        if (!$this->isOneProviderSet()) {
            return false;
        }
        $providers = $this->getProviders();
        foreach ($providers as $provider) {
            if ($this->isProviderReady($provider)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Return verification date time as FrozenTime
     *
     * @param string $provider
     * @return FrozenTime
     */
    public function getVerifiedFrozenTime(string $provider)
    {
        return new FrozenTime($this->settings[$provider][MfaAccountSettings::VERIFIED]);
    }

    /**
     * Return OTP provisioning url
     *
     * @throws RecordNotFoundException if URI is not set
     * @return string
     */
    public function getOtpProvisioningUri()
    {
        if (!isset($this->settings[MfaSettings::PROVIDER_TOTP][self::OTP_PROVISIONING_URI])) {
            throw new RecordNotFoundException(__('MFA setting OTP provisioning uri is not set'));
        }
        return $this->settings[MfaSettings::PROVIDER_TOTP][self::OTP_PROVISIONING_URI];
    }

    /**
     * Return true if otp provisioning uri is set
     *
     * @return bool
     */
    public function isOtpProvisioningUriSet()
    {
        return (isset($this->settings[MfaSettings::PROVIDER_TOTP][self::OTP_PROVISIONING_URI]));
    }

    /**
     * Get MfaSettings (Singleton)
     *
     * @param UserAccessControl $uac
     * @return MfaAccountSettings
     */
    static public function get(UserAccessControl $uac)
    {
        if (self::$instance === null) {
            $AccountSettings = TableRegistry::get('Passbolt/AccountSettings.AccountSettings');
            $settings = $AccountSettings->getFirstPropertyOrFail($uac->getId(), MfaSettings::MFA);
            $decodedJson = json_decode($settings->value, true);
            self::$instance = new MfaAccountSettings($uac, $decodedJson);
        }
        return self::$instance;
    }

    /**
     * Enable a new mfa provider for the given user
     *
     * @param UserAccessControl $uac
     * @param string $provider
     * @param array $data
     */
    static public function enableProvider(UserAccessControl $uac, string $provider, array $data = [])
    {
        $data['verified'] = FrozenTime::now();
        try {
            $AccountSettings = TableRegistry::get('Passbolt/AccountSettings.AccountSettings');
            $settings = $AccountSettings->getFirstPropertyOrFail($uac->getId(), MfaSettings::MFA);
            $decodedJson = json_decode($settings->value, true);
            self::$instance = new MfaAccountSettings($uac, $decodedJson);
            self::$instance->settings[MfaAccountSettings::PROVIDERS][] = $provider;
            self::$instance->settings[$provider] = $data;
        } catch (RecordNotFoundException $exception) {
            self::$instance = new MfaAccountSettings($uac, [
                'providers' => [$provider],
                $provider => $data
            ]);
        }
        self::$instance->save();
    }

    /**
     * Save the MFA account settings as account setting prop
     *
     * @throws BadRequestException if userId does not exist
     * @throws ValidationException if could not save because of validation issues
     * @throws InternalErrorException if save operation saved for another reason
     * @return void
     */
    public function save()
    {
        throw new CustomValidationException('test',  $this->toJson());
        $this->AccountSettings->createOrUpdateSetting($this->uac->getId(), MfaSettings::MFA, $this->toJson());
    }

    /**
     * Delete Mfa settings
     *
     * @return bool true if successful
     */
    public function delete()
    {
        MfaVerifiedToken::deleteAll($this->uac);
        return $this->AccountSettings->deleteByProperty($this->uac->getId(), MfaSettings::MFA);
    }

    /**
     * Disable a given provider
     *
     * @param string $providerToDisable
     */
    public function disableProvider($providerToDisable)
    {
        $providers = $this->getProviders();
        foreach($providers as $i => $provider) {
            if ($provider === $providerToDisable) {
                unset($this->settings[self::PROVIDERS][$i]);
                unset($this->settings[$providerToDisable]);
                if (!count($this->settings[self::PROVIDERS])) {
                    // if there is no provider left
                    $this->delete();
                } else {
                    $this->save();
                }
            }
        }
    }

    /**
     * Get the list of verification url by enabled providers
     * Example: ['totp' => 'BASE_URL/verify/totp']
     *
     * @return array
     */
    public function getProvidersVerifyUrls($json = true) {
        $providers = $this->getProviders();
        $data = [];
        foreach ($providers as $provider) {
            if(!$this->isProviderReady($provider)) {
                continue;
            }
            $data[$provider] = $this->getProviderVerifyUrl($provider, $json);
        }
        return $data;
    }

    /**
     * Get default provider verification url
     *
     * @return string
     */
    public function getDefaultProviderVerifyUrl($json = true) {
        $provider = $this->getDefaultProvider();
        return $this->getProviderVerifyUrl($provider, $json);
    }

    /**
     * Return a given provider verification url
     *
     * @param $provider
     * @param bool $json
     * @return string
     */
    public function getProviderVerifyUrl($provider, $json = true) {
        if ($json) {
            $json = '.json';
        } else {
            $json = '';
        }
        return Router::url("/mfa/verify/$provider$json");
    }

    /**
     * Return a list of provider
     */
    public function getProvidersStatus()
    {
        $status = [];
        $acccountProviders = $this->getProviders();
        $possibleProviders = MfaSettings::getProviders();
        foreach($possibleProviders as $i => $provider) {
            $status[$provider] = in_array($provider, $acccountProviders);
        }
        return $status;
    }
}
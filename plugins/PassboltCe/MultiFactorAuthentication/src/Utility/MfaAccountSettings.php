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
 * @since         2.4.0
 */
namespace Passbolt\MultiFactorAuthentication\Utility;

use App\Utility\UserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\InternalErrorException;
use Cake\I18n\FrozenTime;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;

class MfaAccountSettings
{
    use MfaAccountSettingsTotpTrait;
    use MfaAccountSettingsYubikeyTrait;

    // Names used for settings props
    public const OTP_PROVISIONING_URI = 'otpProvisioningUri';
    public const YUBIKEY_ID = 'yubikeyId';
    public const VERIFIED = 'verified';
    public const PROVIDERS = 'providers';

    /**
     * @var array|null
     */
    protected $settings;

    /**
     * @var \App\Utility\UserAccessControl
     */
    protected $uac;

    /**
     * @var \Passbolt\AccountSettings\Model\Table\AccountSettingsTable
     */
    protected $AccountSettings;

    /**
     * Get MfaSettings (Singleton)
     *
     * @param \App\Utility\UserAccessControl $uac user access control
     * @return self
     * @throws \Cake\Datasource\Exception\RecordNotFoundException if the property is not found or the user not identified.
     * @throws \Cake\Http\Exception\InternalErrorException if the UAC's ID is not a valid UUID.
     */
    public static function get(UserAccessControl $uac): MfaAccountSettings
    {
        $uacId = $uac->getId();
        if (is_null($uacId)) {
            Log::error('User Account Control ID provided to MfaAccountSettings::get() cannot be null.');
            throw new InternalErrorException('Invalid User Account Control ID.');
        }
        /** @var \Passbolt\AccountSettings\Model\Table\AccountSettingsTable $AccountSettings */
        $AccountSettings = TableRegistry::getTableLocator()->get('Passbolt/AccountSettings.AccountSettings');
        $settings = $AccountSettings->getFirstPropertyOrFail($uacId, MfaSettings::MFA);
        $decodedJson = json_decode($settings->value, true);

        return new MfaAccountSettings($uac, $decodedJson);
    }

    /**
     * MfaSettings constructor.
     *
     * @param \App\Utility\UserAccessControl $uac user access control
     * @param array $settings account settings
     */
    public function __construct(UserAccessControl $uac, ?array $settings = null)
    {
        $this->AccountSettings = TableRegistry::getTableLocator()->get('Passbolt/AccountSettings.AccountSettings');
        $this->uac = $uac;
        $this->settings = $settings;
    }

    /**
     * Return true if a provider setting is ready to use
     *
     * @param string $provider name of the provider
     * @return bool
     */
    public function isProviderReady(string $provider)
    {
        if (!isset($this->settings[MfaSettings::PROVIDERS]) || !count($this->settings[MfaSettings::PROVIDERS])) {
            return false;
        }
        if (array_search($provider, $this->settings[MfaSettings::PROVIDERS]) === false) {
            return false;
        }
        if (!isset($this->settings[$provider]) || !isset($this->settings[$provider][self::VERIFIED])) {
            return false;
        }

        switch ($provider) {
            case MfaSettings::PROVIDER_TOTP:
                $result = $this->isOtpProvisioningUriSet();
                break;
            case MfaSettings::PROVIDER_YUBIKEY:
                $result = $this->isYubikeyUserIdSet();
                break;
            default:
                $result = true;
                break;
        }

        return $result;
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
     * Return all providers
     *
     * @throws \Cake\Datasource\Exception\RecordNotFoundException if there is no provider set
     * @return array list of providers
     */
    protected function getProviders()
    {
        if (!isset($this->settings[self::PROVIDERS])) {
            throw new RecordNotFoundException(__('No MFA provider set.'));
        }

        return $this->settings[self::PROVIDERS];
    }

    /**
     * Get an array of provider name that are enabled and verified for this user
     *
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     * @return array
     */
    public function getEnabledProviders()
    {
        $result = [];
        $providers = $this->getProviders();
        foreach ($providers as $provider) {
            if ($this->isProviderReady($provider)) {
                $result[] = $provider;
            }
        }

        return $result;
    }

    /**
     * Return verification date time as FrozenTime
     *
     * @param string $provider name of the provider
     * @return \Cake\I18n\FrozenTime
     */
    public function getVerifiedFrozenTime(string $provider)
    {
        if (!isset($this->settings[$provider][self::VERIFIED])) {
            throw new RecordNotFoundException(__('MFA verification date is not set for this provider.'));
        }

        return new FrozenTime($this->settings[$provider][MfaAccountSettings::VERIFIED]);
    }

    /**
     * Enable a new mfa provider for the given user
     *
     * @param \App\Utility\UserAccessControl $uac access control
     * @param string $provider name of the provider
     * @param array|null $data data
     * @return void
     */
    public static function enableProvider(UserAccessControl $uac, string $provider, ?array $data = [])
    {
        $data['verified'] = FrozenTime::now();
        try {
            /** @var \Passbolt\AccountSettings\Model\Table\AccountSettingsTable $AccountSettings */
            $AccountSettings = TableRegistry::getTableLocator()->get('Passbolt/AccountSettings.AccountSettings');
            $settings = $AccountSettings->getFirstPropertyOrFail($uac->getId(), MfaSettings::MFA);
            $decodedJson = json_decode($settings->value, true);
            $mfaAccountSettings = new MfaAccountSettings($uac, $decodedJson);
            $mfaAccountSettings->settings[MfaAccountSettings::PROVIDERS][] = $provider;
            $mfaAccountSettings->settings[$provider] = $data;
        } catch (RecordNotFoundException $exception) {
            $mfaAccountSettings = new MfaAccountSettings($uac, [
                'providers' => [$provider],
                $provider => $data,
            ]);
        }
        $mfaAccountSettings->save();
    }

    /**
     * Save the MFA account settings as account setting prop
     *
     * @throws \Cake\Http\Exception\BadRequestException if userId does not exist
     * @throws \App\Error\Exception\ValidationException if could not save because of validation issues
     * @throws \Cake\Http\Exception\InternalErrorException if save operation saved for another reason
     * @return void
     */
    public function save()
    {
        $this->AccountSettings->createOrUpdateSetting($this->uac->getId(), MfaSettings::MFA, $this->toJson());
    }

    /**
     * Delete Mfa settings
     *
     * @return bool true if successful
     */
    public function delete()
    {
        MfaVerifiedToken::setAllInactive($this->uac);

        return $this->AccountSettings->deleteByProperty($this->uac->getId(), MfaSettings::MFA);
    }

    /**
     * Disable a given provider
     *
     * @param string $providerToDisable name of the provider
     * @return void
     */
    public function disableProvider(string $providerToDisable)
    {
        $providers = $this->getProviders();
        foreach ($providers as $i => $provider) {
            if ($provider === $providerToDisable) {
                array_splice($this->settings[self::PROVIDERS], $i, 1);
                if (isset($this->settings[$providerToDisable])) {
                    unset($this->settings[$providerToDisable]);
                }
                if (!count($this->settings[self::PROVIDERS])) {
                    // if there is no provider left
                    $this->delete();
                } else {
                    $this->save();
                }
                break;
            }
        }
    }

    /**
     * Return an associative array of provider with provider name as key and status value
     * example: ['totp' => true, 'duo' => false]
     *
     * @return array
     */
    public function getProvidersStatus()
    {
        $status = [];
        $possibleProviders = MfaSettings::getProviders();
        foreach ($possibleProviders as $i => $provider) {
            $status[$provider] = false;
        }
        try {
            $accountProviders = $this->getEnabledProviders();
        } catch (RecordNotFoundException $exception) {
            return $status;
        }
        foreach ($possibleProviders as $i => $provider) {
            $status[$provider] = in_array($provider, $accountProviders);
        }

        return $status;
    }
}

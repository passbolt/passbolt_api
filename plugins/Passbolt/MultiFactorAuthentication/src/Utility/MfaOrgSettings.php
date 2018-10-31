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

use Cake\Core\Configure;
use Cake\Datasource\Exception\RecordNotFoundException;

class MfaOrgSettings
{
    use MfaOrgSettingsDuoTrait;
    use MfaOrgSettingsYubikeyTrait;

    /**
     * @var array|null
     */
    protected $settings;

    /**
     * MfaOrgSettings constructor.
     * @param array|null $databaseSettings settings from db
     * @param array|null $configureSettings settings from config
     */
    public function __construct(array $databaseSettings = null, array $configureSettings = null)
    {
        $this->settings = $configureSettings;
    }

    /**
     * Get Organization MFA Settings
     *
     * @return MfaOrgSettings
     */
    public static function get()
    {
        $configureSettings = Configure::read('passbolt.plugins.multiFactorAuthentication');
        $databaseSettings = null;

        return new MfaOrgSettings($databaseSettings, $configureSettings);
    }

    /**
     * Get the list of providers for this organization including invalid/disabled ones
     *
     * @throws RecordNotFoundException
     * @return array containing providers name
     */
    public function getProviders()
    {
        if (!isset($this->settings) || !isset($this->settings['providers']) || !count($this->settings['providers'])) {
            throw new RecordNotFoundException(__('No MFA provider set for this organization.'));
        }

        return array_keys($this->settings['providers']);
    }

    /**
     * Get the list of provider names that are enabled for that organization
     *
     * @return array
     */
    public function getEnabledProviders()
    {
        $result = [];
        $providers = MfaSettings::getProviders();
        foreach ($providers as $key => $provider) {
            if ($this->isProviderEnabled($provider)) {
                $result[] = $provider;
            }
        }

        return $result;
    }

    /**
     * Return a list of provider
     *
     * @return array with provider as key and al
     */
    public function getProvidersStatus()
    {
        $results = [];
        $providers = MfaSettings::getProviders();
        foreach ($providers as $provider) {
            $results[$provider] = $this->isProviderEnabled($provider);
        }

        return $results;
    }

    /**
     * Is a given mfa provider allowed for the organization?
     *
     * @param string $provider name of the provider
     * @return bool
     */
    public function isProviderEnabled(string $provider)
    {
        if (!isset($this->settings) || !isset($this->settings['providers'])) {
            return false;
        }
        if (!isset($this->settings['providers'][$provider]) || !$this->settings['providers'][$provider]) {
            return false;
        }
        $result = false;
        switch ($provider) {
            case MfaSettings::PROVIDER_TOTP:
                $result = true;
                break;
            case MfaSettings::PROVIDER_YUBIKEY:
                try {
                    $this->getYubikeyOTPClientId();
                    $this->getYubikeyOTPSecretKey();
                    $result = true;
                } catch (RecordNotFoundException $exception) {
                }
                break;
            case MfaSettings::PROVIDER_DUO:
                try {
                    $this->getDuoIntegrationKey();
                    $this->getDuoSecretKey();
                    $this->getDuoHostname();
                    $this->getDuoSalt();
                    $result = true;
                } catch (RecordNotFoundException $exception) {
                }
                break;
        }

        return $result;
    }
}

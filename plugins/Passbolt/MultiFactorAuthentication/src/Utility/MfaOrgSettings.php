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
    use MfaOrgSettingsYubikeyTrait;
    use MfaOrgSettingsDuoTrait;

    protected $settings;

    /**
     * MfaOrgSettings constructor.
     * @param array|null $databaseSettings
     * @param array|null $configureSettings
     */
    public function __construct(array $databaseSettings = null, array $configureSettings = null)
    {
        // TODO merge configure and orgSettings table entries
        $this->settings = $configureSettings;
    }

    /**
     * Get Organization MFA Settings
     *
     * @return MfaOrgSettings
     */
    static public function get() {
        // TODO class that
        $configureSettings = Configure::read('passbolt.plugins.multiFactorAuthentication');
        // TODO get from database
        $databaseSettings = null;
        return new MfaOrgSettings($databaseSettings, $configureSettings);
    }

    /**
     * Get the list of enabled providers for this organization
     *
     * @throws RecordNotFoundException
     * @return mixed
     */
    public function getProviders()
    {
        if (!isset($this->settings['providers']) && !count($this->settings['providers'])) {
            throw new RecordNotFoundException(__('No MFA provider set for this organization.'));
        }
        return array_keys($this->settings['providers']);
    }

    /**
     * Get the list of provider names that are enabled for that organization
     *
     */
    public function getEnabledProviders() {
        $result = [];
        $providers = $this->getProviders();
        foreach ($providers as $key => $provider) {
            if ($this->isProviderAllowed($provider)) {
                $result[] = $provider;
            }
        }
        return $result;
    }

    /**
     * Return a list of provider
     * @return array with provider as key and al
     */
    public function getProvidersStatus()
    {
        return $this->settings['providers'];
    }

    /**
     * Is a given mfa provider allowed for the organization?
     *
     * @param string $provider name of the provider
     * @return bool
     */
    public function isProviderAllowed(string $provider)
    {
        return (isset($this->settings['providers'][$provider]) && $this->settings['providers'][$provider]);
    }

    /**
     * Return true if at least one provider is enabled for the org
     *
     * @return bool
     */
    public function isOneProviderSet()
    {
        return (count($this->getProviders()) > 0);
    }

    /**
     * Return true if at least one of the given provider is enabled for the org
     *
     * @param array $providers
     * @return bool
     */
    public function isOneProviderAllowed(array $providers)
    {
        foreach ($providers as $provider) {
            if ($this->isProviderAllowed($provider)) {
                return true;
            }
        }
        return false;
    }
}
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
use App\Utility\UserAccessControl;
use Cake\Core\Configure;

class MfaOrgSettings
{
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
     * @param UserAccessControl $uac
     * @return MfaOrgSettings
     */
    static public function get(UserAccessControl $uac) {
        // TODO class that
        $configureSettings = Configure::read('passbolt.plugins.multiFactorAuthentication');
        // TODO get from database
        $databaseSettings = null;
        return new MfaOrgSettings($databaseSettings, $configureSettings);
    }

    /**
     * Get the list of enabled providers for this organization
     *
     * @return mixed
     */
    public function getProviders()
    {
        return $this->settings['providers'];
    }

    /**
     * Return a list of provider
     * @return array with provider as key and al
     */
    public function getProvidersStatus()
    {
        $status = [];
        $possibleProviders = MfaSettings::getProviders();
        foreach($possibleProviders as $i => $provider) {
            $status[$provider] = $this->isProviderAllowed($provider);
        }
        return $status;
    }

    /**
     * Is a given mfa provider allowed for the organization?
     *
     * @param string $provider
     * @return bool
     */
    public function isProviderAllowed(string $provider)
    {
        $orgProviders = $this->getProviders();
        return (isset($orgProviders[$provider]) && $orgProviders[$provider]);
    }
}
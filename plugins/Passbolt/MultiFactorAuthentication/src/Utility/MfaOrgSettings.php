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
use App\Model\Table\OrganizationSettingsTable;
use App\Utility\UserAccessControl;
use Cake\Core\Configure;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Network\Exception\InternalErrorException;
use Cake\ORM\Locator\TableLocator;
use Cake\ORM\TableRegistry;

class MfaOrgSettings
{
    use MfaOrgSettingsDuoTrait;
    use MfaOrgSettingsYubikeyTrait;

    /**
     * Duo constants
     */
    const DUO_SECRET_KEY = 'secretKey';
    const DUO_HOSTNAME = 'hostName';
    const DUO_INTEGRATION_KEY = 'integrationKey';
    const DUO_SALT = 'salt';

    /**
     * Yubikey constants
     */
    const YUBIKEY_CLIENT_ID = 'clientId';
    const YUBIKEY_SECRET_KEY = 'secretKey';

    /**
     * @var OrganizationSettingsTable
     */
    protected $OrganizationSettings;

    /**
     * @var array|null
     */
    protected $settings;

    /**
     * MfaOrgSettings constructor.
     *
     * @param array|null $settings merged settings from configure and database
     */
    public function __construct(array $settings = null)
    {
        if (!isset($settings) || !isset($settings[MfaSettings::PROVIDERS])) {
            throw new InternalErrorException(__('Invalid MFA org settings.'));
        }
        $settings[MfaSettings::PROVIDERS] = $this->formatProviders($settings[MfaSettings::PROVIDERS]);
        $this->settings = $settings;
        $this->OrganizationSettings = TableRegistry::get('OrganizationSettings');
    }

    /**
     * We accept both format ['providers' => ['totp' => true ]] and ['providers' => ['totp']]
     * This function format the former to the later to ensure consistant format
     *
     * @param array $providers
     * @return array
     */
    private function formatProviders(array $providers) {
        $result = $providers;
        if (count(array_filter(array_keys($providers), 'is_string')) > 0) {
            $result = [];
            foreach ($providers as $provider => $enabled) {
                if ($enabled) {
                    $result[] = $provider;
                }
            }
        }
        return $result;
    }

    /**
     * Get Organization MFA Settings
     *
     * @return MfaOrgSettings
     */
    public static function get()
    {
        $defaultSettings = ['providers' => []];
        $configureSettings = Configure::read('passbolt.plugins.multiFactorAuthentication');
        try {
            $orgSettings = TableRegistry::getTableLocator()->get('OrganizationSettings');
            $databaseSettings = $orgSettings->getFirstSettingOrFail(MfaSettings::MFA);
            $databaseSettings = json_decode($databaseSettings->value, true);
        } catch (RecordNotFoundException $exception) {
            $databaseSettings = null;
        }

        // Use in order the configuration stored in:
        // 1. database
        // 2. configuration file
        // 3. the defaults in this function
        if (isset($databaseSettings)) {
            $settings = $databaseSettings;
        } else {
            if (isset($configureSettings)) {
                $settings = $configureSettings;
            } else {
                $settings = $defaultSettings;
            }
        }

        return new MfaOrgSettings($settings);
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
        foreach ($providers as $provider) {
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
        if (!in_array($provider, $this->settings['providers'])) {
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

    /**
     * Get config
     *
     * @return array
     */
    public function getConfig() {
        $providers = $this->getEnabledProviders();
        $results = ['providers' => $providers];
        foreach ($providers as $provider) {
            switch ($provider) {
                case MfaSettings::PROVIDER_DUO:
                    $results[MfaSettings::PROVIDER_DUO] = [
                        self::DUO_SALT => $this->getDuoSalt(),
                        self::DUO_SECRET_KEY => $this->getDuoSecretKey(),
                        self::DUO_HOSTNAME => $this->getDuoHostname(),
                        self::DUO_INTEGRATION_KEY => $this->getDuoIntegrationKey()
                    ];
                    break;
                case MfaSettings::PROVIDER_YUBIKEY:
                    $results[MfaSettings::PROVIDER_YUBIKEY] = [
                        self::YUBIKEY_CLIENT_ID => $this->getYubikeyOTPClientId(),
                        self::YUBIKEY_SECRET_KEY => $this->getYubikeyOTPSecretKey()
                    ];
                    break;
            }
        }

        return $results;
    }

    /**
     * Validate
     *
     * @param array $data user provided data
     * @throws CustomValidationException if the data does not validate
     * @return bool if data validates
     */
    public function validate(array $data) {
        if (!isset($data) || empty($data)) {
            throw new CustomValidationException(__('The MFA settings data cannot be empty.'));
        }
        if (!isset($data[MfaSettings::PROVIDERS]) || !is_array($data[MfaSettings::PROVIDERS])) {
            throw new CustomValidationException(__('The MFA providers list is missing.'));
        }
        $results = [];
        foreach ($data[MfaSettings::PROVIDERS] as $provider) {
            $errors = null;
            switch ($provider) {
                case MfaSettings::PROVIDER_YUBIKEY:
                    try {
                        $this->validateYubikeySettings($data);
                    } catch(CustomValidationException $exception) {
                        $errors = $exception->getErrors();
                    }
                    break;
                case MfaSettings::PROVIDER_DUO:
                    try {
                        $this->validateDuoSettings($data);
                    } catch(CustomValidationException $exception) {
                        $errors = $exception->getErrors();
                    }
                    break;
                case MfaSettings::PROVIDER_TOTP:
                    // Nothing else to validate
                    break;
                default:
                    $errors[$provider]['invalidProvider'] = __('Unknown MFA provider: {0}.', $provider);
                    break;
            }
            if (isset($errors[$provider])) {
                $results[$provider] = $errors[$provider];
            }
        }
        if (count($results) !== 0) {
            throw new CustomValidationException(__('Could not validate MFA provider configuration.'), $results);
        }

        return true;
    }

    /**
     * Save a user provided org settings in database
     *
     * @throws CustomValidationException in case of validation error
     * @throws InternalErrorException
     * @param array $data user provided input
     * @param UserAccessControl $uac user access control
     */
    public function save(array $data, UserAccessControl $uac) {
        if (isset($data[MfaSettings::PROVIDERS])) {
            $data[MfaSettings::PROVIDERS] = $this->formatProviders($data[MfaSettings::PROVIDERS]);
        }
        $this->validate($data);
        $this->settings = $data;
        $json = json_encode($data);
        $this->OrganizationSettings->createOrUpdateSetting(MfaSettings::MFA, $json, $uac);
    }
}

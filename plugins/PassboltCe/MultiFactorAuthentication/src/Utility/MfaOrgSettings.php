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
 * @since         2.0.0
 */
namespace Passbolt\MultiFactorAuthentication\Utility;

use App\Error\Exception\CustomValidationException;
use App\Utility\UserAccessControl;
use Cake\Core\Configure;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\TableRegistry;
use Duo\DuoUniversal\Client;
use Passbolt\MultiFactorAuthentication\Service\MfaOrgSettings\MfaOrgSettingsDuoService;

class MfaOrgSettings
{
    use MfaOrgSettingsYubikeyTrait;

    /**
     * Duo constants
     */
    public const DUO_HEALTH_CHECK = 'healthCheck';
    public const DUO_CLIENT_ID = 'clientId';
    public const DUO_CLIENT_SECRET = 'clientSecret';
    public const DUO_API_HOSTNAME = 'apiHostName';

    /**
     * Yubikey constants
     */
    public const YUBIKEY_CLIENT_ID = 'clientId';
    public const YUBIKEY_SECRET_KEY = 'secretKey';

    /**
     * @var \App\Model\Table\OrganizationSettingsTable
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
    public function __construct(?array $settings = null)
    {
        if (!isset($settings) || !isset($settings[MfaSettings::PROVIDERS])) {
            throw new InternalErrorException('Invalid MFA org settings.');
        }
        $settings[MfaSettings::PROVIDERS] = $this->formatProviders($settings[MfaSettings::PROVIDERS]);
        $this->settings = $settings;
        $this->OrganizationSettings = TableRegistry::getTableLocator()->get('OrganizationSettings');
    }

    /**
     * Format Providers
     *
     * We accept both format ['providers' => ['totp' => true ]] and ['providers' => ['totp']]
     * This function format the former to the latter to ensure consistent format
     *
     * @param array $providers see above
     * @return array
     */
    private function formatProviders(array $providers): array
    {
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
     * @return self
     */
    public static function get(): MfaOrgSettings
    {
        $defaultSettings = ['providers' => []];
        $configureSettings = Configure::read('passbolt.plugins.multiFactorAuthentication');
        try {
            /** @var \App\Model\Table\OrganizationSettingsTable $orgSettings */
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
     * @return string[]
     */
    public function getEnabledProviders(): array
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
     * @return bool
     */
    public function isEnabled(): bool
    {
        return count($this->getEnabledProviders()) > 0;
    }

    /**
     * Return the settings
     *
     * @return array|null
     */
    public function getSettings(): ?array
    {
        return $this->settings;
    }

    /**
     * Return the settings
     *
     * @return \Passbolt\MultiFactorAuthentication\Service\MfaOrgSettings\MfaOrgSettingsDuoService
     */
    public function getDuoOrgSettings(): MfaOrgSettingsDuoService
    {
        return new MfaOrgSettingsDuoService($this->settings);
    }

    /**
     * Return a list of provider
     *
     * @return array with provider as key and al
     */
    public function getProvidersStatus(): array
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
    public function isProviderEnabled(string $provider): bool
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
                    $duoOrgSettings = $this->getDuoOrgSettings();
                    $duoOrgSettings->getDuoClientId();
                    $duoOrgSettings->getDuoClientSecret();
                    $duoOrgSettings->getDuoApiHostname();
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
    public function getConfig(): array
    {
        $duoOrgSettings = $this->getDuoOrgSettings();
        $providers = $this->getEnabledProviders();
        $results = ['providers' => $providers];
        foreach ($providers as $provider) {
            switch ($provider) {
                case MfaSettings::PROVIDER_DUO:
                    $results[MfaSettings::PROVIDER_DUO] = [
                        self::DUO_CLIENT_SECRET => $duoOrgSettings->getDuoClientSecret(),
                        self::DUO_API_HOSTNAME => $duoOrgSettings->getDuoApiHostname(),
                        self::DUO_CLIENT_ID => $duoOrgSettings->getDuoClientId(),
                    ];
                    break;
                case MfaSettings::PROVIDER_YUBIKEY:
                    $results[MfaSettings::PROVIDER_YUBIKEY] = [
                        self::YUBIKEY_CLIENT_ID => $this->getYubikeyOTPClientId(),
                        self::YUBIKEY_SECRET_KEY => $this->getYubikeyOTPSecretKey(),
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
     * @param \Duo\DuoUniversal\Client|null $client Duo SDK Client
     * @param array $options Options used to save & validate organisation settings
     * @throws \App\Error\Exception\CustomValidationException if the data does not validate
     * @return bool if data validates
     */
    public function validate(array $data, ?Client $client = null, array $options = []): bool
    {
        if (empty($data)) {
            $msg = __('The multi-factor authentication settings data should not be empty.');
            throw new CustomValidationException($msg);
        }
        if (!isset($data[MfaSettings::PROVIDERS]) || !is_array($data[MfaSettings::PROVIDERS])) {
            throw new CustomValidationException(__('The multi-factor authentication providers list is missing.'));
        }
        $results = [];
        foreach ($data[MfaSettings::PROVIDERS] as $provider) {
            $errors = null;
            switch ($provider) {
                case MfaSettings::PROVIDER_YUBIKEY:
                    try {
                        $this->validateYubikeySettings($data);
                    } catch (CustomValidationException $exception) {
                        $errors = $exception->getErrors();
                    }
                    break;
                case MfaSettings::PROVIDER_DUO:
                    $skipHealthcheck = $options['skipDuoHealtcheck'] ?? false;
                    try {
                        (new MfaOrgSettingsDuoService($data))->validateDuoSettings($client, $skipHealthcheck === false);
                    } catch (CustomValidationException $exception) {
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
            $msg = __('Could not validate multi-factor authentication provider configuration.');
            throw new CustomValidationException($msg, $results);
        }

        return true;
    }

    /**
     * Save a user provided org settings in database
     *
     * @throws \App\Error\Exception\CustomValidationException in case of validation error
     * @throws \Cake\Http\Exception\InternalErrorException
     * @param array $data user provided input
     * @param \App\Utility\UserAccessControl $uac user access control
     * @param \Duo\DuoUniversal\Client|null $client Duo SDK Client
     * @param array $options Options used to save & validate organisation settings
     * @return void
     */
    public function save(array $data, UserAccessControl $uac, ?Client $client = null, array $options = []): void
    {
        if (isset($data[MfaSettings::PROVIDERS])) {
            $data[MfaSettings::PROVIDERS] = $this->formatProviders($data[MfaSettings::PROVIDERS]);
        }
        $this->validate($data, $client, $options);
        $this->settings = $data;
        $json = json_encode($data);
        $this->OrganizationSettings->createOrUpdateSetting(MfaSettings::MFA, $json, $uac);
    }
}

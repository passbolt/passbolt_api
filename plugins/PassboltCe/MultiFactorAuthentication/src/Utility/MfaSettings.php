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

use App\Utility\UserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Routing\Router;
use Passbolt\MultiFactorAuthentication\Service\ActionLogs\MfaSortWithLastUsedProviderFirstService;

class MfaSettings
{
    public const MFA = 'mfa';
    public const PROVIDERS = 'providers';
    public const PROVIDER_TOTP = 'totp';
    public const PROVIDER_DUO = 'duo';
    public const PROVIDER_YUBIKEY = 'yubikey';

    public const ORG_SETTINGS = 'MfaOrganizationSettings';
    public const ACCOUNT_SETTINGS = 'MfaAccountSettings';

    /**
     * @var \Passbolt\MultiFactorAuthentication\Utility\MfaAccountSettings
     */
    protected $accountSettings;

    /**
     * @var \Passbolt\MultiFactorAuthentication\Utility\MfaOrgSettings
     */
    protected $orgSettings;

    /**
     * @var \App\Utility\UserAccessControl
     */
    protected $uac;

    /**
     * @var \Passbolt\MultiFactorAuthentication\Service\ActionLogs\MfaSortWithLastUsedProviderFirstService
     */
    protected MfaSortWithLastUsedProviderFirstService $sortProvidersService;

    /**
     * @var self|null
     */
    protected static $instance;

    /**
     * MfaSettings constructor.
     *
     * @param \Passbolt\MultiFactorAuthentication\Utility\MfaOrgSettings $orgSettings organization settings
     * @param \Passbolt\MultiFactorAuthentication\Utility\MfaAccountSettings|null $accountSettings account settings
     * @param \App\Utility\UserAccessControl $uac user access control
     * @param \Passbolt\MultiFactorAuthentication\Service\ActionLogs\MfaSortWithLastUsedProviderFirstService $sortProvidersService service sorting the providers with the last used first
     * @return void
     */
    public function __construct(
        MfaOrgSettings $orgSettings,
        ?MfaAccountSettings $accountSettings,
        UserAccessControl $uac,
        MfaSortWithLastUsedProviderFirstService $sortProvidersService
    ) {
        $this->accountSettings = $accountSettings;
        $this->orgSettings = $orgSettings;
        $this->uac = $uac;
        $this->sortProvidersService = $sortProvidersService;
    }

    /**
     * Get MfaSettings singleton
     *
     * @param \App\Utility\UserAccessControl $uac access control
     * @return self
     * @throws \Cake\Http\Exception\InternalErrorException if the UAC changed during the request (improbable)
     */
    public static function get(UserAccessControl $uac): MfaSettings
    {
        if (self::$instance !== null) {
            if (self::$instance->uac->getId() !== $uac->getId()) {
                throw new InternalErrorException('Invalid User Account Control ID.');
            }

            return self::$instance;
        }

        try {
            $orgSettings = MfaOrgSettings::get();
        } catch (InternalErrorException $exception) {
            // invalid configuration => no providers
            $orgSettings = new MfaOrgSettings([MfaSettings::PROVIDERS => []]);
        }
        try {
            $accountSettings = MfaAccountSettings::get($uac);
        } catch (RecordNotFoundException | InternalErrorException $exception) {
            $accountSettings = null;
        }

        self::$instance = new MfaSettings(
            $orgSettings,
            $accountSettings,
            $uac,
            new MfaSortWithLastUsedProviderFirstService()
        );

        return self::$instance;
    }

    /**
     * Clear the instance
     *
     * @return void
     */
    public static function clear(): void
    {
        self::$instance = null;
    }

    /**
     * Get an array of all possible providers
     *
     * @return string[]
     */
    public static function getProviders(): array
    {
        return [
            self::PROVIDER_TOTP,
            self::PROVIDER_DUO,
            self::PROVIDER_YUBIKEY,
        ];
    }

    /**
     * Return both the user and org provider configuration status
     *
     * @return array
     */
    public function getProvidersStatuses(): array
    {
        $result = $default = [];
        $providers = self::getProviders();
        foreach ($providers as $i => $provider) {
            $default[$provider] = false;
        }
        if ($this->orgSettings === null) {
            $result[MfaSettings::ORG_SETTINGS] = $default;
        } else {
            $result[MfaSettings::ORG_SETTINGS] = $this->orgSettings->getProvidersStatus();
        }
        if ($this->accountSettings === null) {
            $result[MfaSettings::ACCOUNT_SETTINGS] = $default;
        } else {
            $result[MfaSettings::ACCOUNT_SETTINGS] = $this->accountSettings->getProvidersStatus();
        }

        return $result;
    }

    /**
     * Get providers enabled both for org and the user
     * example:
     * org = ['totp', 'yubikey']
     * user = ['totp', 'duo']
     * result = ['totp']
     *
     * @return string[] of provider names
     */
    public function getEnabledProviders(): array
    {
        $result = [];
        if ($this->accountSettings === null) {
            return $result;
        }
        try {
            $userProviders = array_flip($this->accountSettings->getEnabledProviders());
            $orgProviders = array_flip($this->orgSettings->getEnabledProviders());
        } catch (RecordNotFoundException $exception) {
            return $result;
        }
        foreach ($orgProviders as $orgProvider => $i) {
            if (isset($userProviders[$orgProvider])) {
                $result[] = $orgProvider;
            }
        }

        return $result;
    }

    /**
     * Get a list of all enabled providers
     *
     * @return array
     */
    public function getEnabledProvidersWithLastUsedFirst(): array
    {
        $providers = $this->getEnabledProviders();

        return $this->sortProvidersService->sortWithLastUsedProviderFirst($this->uac, $providers);
    }

    /**
     * Return true if the provider is enabled for both the organization and user
     *
     * @param string $provider provider name
     * @return bool
     */
    public function isProviderEnabled(string $provider): bool
    {
        $providers = $this->getEnabledProviders();

        return array_search($provider, $providers) !== false;
    }

    /**
     * Return true if the user has at least one provider enabled, and this provider is enabled for the organization
     *
     * @return bool
     */
    public function hasEnabledProviders(): bool
    {
        return count($this->getEnabledProviders()) > 0;
    }

    /**
     * Get account settings
     *
     * @param bool|null $refresh if a new table find is required
     * @return \Passbolt\MultiFactorAuthentication\Utility\MfaAccountSettings|null
     */
    public function getAccountSettings(?bool $refresh = false): ?MfaAccountSettings
    {
        if ($this->accountSettings === null || $refresh) {
            try {
                $this->accountSettings = MfaAccountSettings::get($this->uac);
            } catch (RecordNotFoundException $exception) {
                return null;
            }
        }

        return $this->accountSettings;
    }

    /**
     * Get organization settings
     *
     * @return \Passbolt\MultiFactorAuthentication\Utility\MfaOrgSettings
     */
    public function getOrganizationSettings(): MfaOrgSettings
    {
        return $this->orgSettings;
    }

    /**
     * Get the list of verification url by enabled providers
     * Example: ['totp' => 'BASE_URL/verify/totp']
     *
     * @param bool|null $json if json extension required
     * @return array
     */
    public function getProvidersVerifyUrls(?bool $json = true): array
    {
        $providers = $this->getEnabledProviders();
        $data = [];
        foreach ($providers as $provider) {
            $data[$provider] = $this->getProviderVerifyUrl($provider, $json);
        }

        return $data;
    }

    /**
     * Get default provider verification url
     *
     * @param bool|null $json if json extension required
     * @return string
     */
    public function getDefaultVerifyUrl(?bool $json = true): string
    {
        $providers = $this->getEnabledProvidersWithLastUsedFirst();

        return $this->getProviderVerifyUrl($providers[0], $json);
    }

    /**
     * Return a given provider verification url
     *
     * @param string $provider provider name
     * @param bool|null $json if json extension required
     * @return string
     */
    public function getProviderVerifyUrl(string $provider, ?bool $json = true): string
    {
        if ($json) {
            $json = '.json';
        } else {
            $json = '';
        }

        return Router::url("/mfa/verify/$provider$json", true);
    }
}

<?php
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

class MfaSettings
{
    const MFA = 'mfa';
    const PROVIDERS = 'providers';
    const PROVIDER_TOTP = 'totp';
    const PROVIDER_DUO = 'duo';
    const PROVIDER_YUBIKEY = 'yubikey';

    const ORG_SETTINGS = 'MfaOrganizationSettings';
    const ACCOUNT_SETTINGS = 'MfaAccountSettings';

    /**
     * @var MfaAccountSettings
     */
    protected $accountSettings;

    /**
     * @var MfaOrgSettings
     */
    protected $orgSettings;

    /**
     * @var UserAccessControl
     */
    protected $uac;

    /**
     * MfaSettings constructor.
     *
     * @param MfaOrgSettings $orgSettings organization settings
     * @param MfaAccountSettings|null $accountSettings account settings
     * @param UserAccessControl $uac user access control
     * @return void
     */
    public function __construct(MfaOrgSettings $orgSettings, MfaAccountSettings $accountSettings = null, UserAccessControl $uac)
    {
        $this->accountSettings = $accountSettings;
        $this->orgSettings = $orgSettings;
        $this->uac = $uac;
    }

    /**
     * Get MfaSettings singleton
     *
     * @param UserAccessControl $uac access control
     * @return MfaSettings
     */
    public static function get(UserAccessControl $uac)
    {
        try {
            $orgSettings = MfaOrgSettings::get();
        } catch (InternalErrorException $exception) {
            // invalid configuration => no providers
            $orgSettings = new MfaOrgSettings([MfaSettings::PROVIDERS => []]);
        }
        try {
            $accountSettings = MfaAccountSettings::get($uac);
        } catch (RecordNotFoundException $exception) {
            $accountSettings = null;
        }

        return new MfaSettings($orgSettings, $accountSettings, $uac);
    }

    /**
     * Get an array of all possible providers
     *
     * @return array
     */
    public static function getProviders()
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
    public function getProvidersStatuses()
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
     * @return array of provider names
     */
    public function getEnabledProviders()
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
     * Return true if the provider is enabled for both the organization and user
     *
     * @param string $provider provider name
     * @return bool
     */
    public function isProviderEnabled(string $provider)
    {
        $providers = $this->getEnabledProviders();

        return (array_search($provider, $providers) !== false);
    }

    /**
     * Get account settings
     *
     * @param bool $refresh if a new table find is required
     * @return MfaAccountSettings
     */
    public function getAccountSettings(bool $refresh = false)
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
     * @return MfaOrgSettings
     */
    public function getOrganizationSettings()
    {
        return $this->orgSettings;
    }

    /**
     * Get the list of verification url by enabled providers
     * Example: ['totp' => 'BASE_URL/verify/totp']
     *
     * @param bool $json if json extension required
     * @return array
     */
    public function getProvidersVerifyUrls(bool $json = true)
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
     * @param bool $json if json extension required
     * @return string
     */
    public function getDefaultVerifyUrl(bool $json = true)
    {
        $providers = $this->getEnabledProviders();

        return $this->getProviderVerifyUrl($providers[0], $json);
    }

    /**
     * Return a given provider verification url
     *
     * @param string $provider provider name
     * @param bool $json if json extension required
     * @return string
     */
    public function getProviderVerifyUrl(string $provider, bool $json = true)
    {
        if ($json) {
            $json = '.json';
        } else {
            $json = '';
        }

        return Router::url("/mfa/verify/$provider$json", true);
    }
}

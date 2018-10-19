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
use Cake\Datasource\Exception\RecordNotFoundException;

class MfaSettings
{

    const MFA = 'mfa';
    const PROVIDERS = 'providers';
    const PROVIDER_TOTP = 'totp';
    const PROVIDER_DUO = 'duo';
    const PROVIDER_YUBIKEY = 'yubikey';

    protected $accountSettings;
    protected $orgSettings;


    public function __construct(MfaOrgSettings $orgSettings, MfaAccountSettings $accountSettings = null)
    {
        $this->accountSettings = $accountSettings;
        $this->orgSettings = $orgSettings;
    }

    /**
     * Get MfaSettings singleton
     *
     * @param UserAccessControl $uac
     * @return MfaSettings
     */
    static public function get(UserAccessControl $uac) {
        $orgSettings = MfaOrgSettings::get($uac);
        try {
            $accountSettings = MfaAccountSettings::get($uac);
        } catch (RecordNotFoundException $exception) {
            $accountSettings = null;
        }
        return new MfaSettings($orgSettings, $accountSettings);
    }

    /**
     * Get MfaSettings or fail if not present
     *
     * @throws RecordNotFoundException
     * @param UserAccessControl $uac
     * @return MfaSettings
     */
    static public function getOrFail(UserAccessControl $uac)
    {
        $orgSettings = MfaOrgSettings::get($uac);
        $accountSettings = MfaAccountSettings::get($uac);
        return new MfaSettings($orgSettings, $accountSettings);
    }

    /**
     * @return array
     */
    static public function getProviders()
    {
        return [
            self::PROVIDER_TOTP,
            self::PROVIDER_DUO,
            self::PROVIDER_YUBIKEY
        ];
    }

    /**
     * Return both the user and org provider configuration status
     *
     * @return array
     */
    public function getProvidersStatuses()
    {
        $result = [];
        $falsy = [
            self::PROVIDER_TOTP => false,
            self::PROVIDER_DUO => false,
            self::PROVIDER_YUBIKEY => false
        ];
        if ($this->orgSettings === null) {
            $result['MfaOrganizationSettings'] = $falsy;
        } else {
            $result['MfaOrganizationSettings'] = $this->orgSettings->getProvidersStatus();
        }
        if ($this->accountSettings === null) {
            $result['MfaAccountSettings'] = $falsy;
        } else {
            $result['MfaAccountSettings'] = $this->accountSettings->getProvidersStatus();
        }
        return $result;
    }

    /**
     * @return MfaAccountSettings
     */
    public function getAccountSettings()
    {
        return $this->accountSettings;
    }

    /**
     * @return MfaOrgSettings
     */
    public function getOrganizationSettings()
    {
        return $this->orgSettings;
    }
}
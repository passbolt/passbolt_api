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
namespace Passbolt\MultiFactorAuthentication\Test\Lib;

use Cake\I18n\FrozenTime;
use Passbolt\MultiFactorAuthentication\Utility\MfaAccountSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

trait MfaYubikeySettingsTestTrait
{

    /**
     * @param string $user
     * @param string $case
     */
    public function mockMfaYubikeySettings($user = 'ada', $case = 'valid')
    {
        $uac = $this->mockUserAccessControl('ada');
        $this->mockMfaOrgSettings([
            MfaSettings::PROVIDERS => [MfaSettings::PROVIDER_YUBIKEY => true],
            MfaSettings::PROVIDER_YUBIKEY => [
                'clientId' => '12345',
                'secretKey' => 'f4/ijjIAAO/bxO1k6hXmdgRRXUY='
            ]
        ]);
        $this->mockMfaAccountSettings('ada', [
            MfaSettings::PROVIDERS => [MfaSettings::PROVIDER_YUBIKEY],
            MfaSettings::PROVIDER_YUBIKEY => [
                MfaAccountSettings::VERIFIED => FrozenTime::now(),
                MfaAccountSettings::YUBIKEY_ID => 'ijfeijfeij'
            ]
        ]);
    }
}

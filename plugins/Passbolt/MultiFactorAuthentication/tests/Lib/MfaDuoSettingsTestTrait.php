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

trait MfaDuoSettingsTestTrait
{

    /**
     * @param string $user
     * @param string $case
     */
    public function mockMfaDuoSettings(string $user = 'ada', string $case = 'valid')
    {
        switch ($case) {
            case 'orgOnly':
                $this->mockMfaOrgSettings([
                    MfaSettings::PROVIDERS => [MfaSettings::PROVIDER_DUO => true],
                    MfaSettings::PROVIDER_DUO => [
                        'salt' => '__CHANGE_ME__THIS_MUST_BE_AT_LEAST_FOURTY_CHARACTERS_____',
                        'integrationKey' => 'CICDIC95F13URR1FW5SJ',
                        'secretKey' => '7tkYNgi8aGAuv3K31eq2sJLfIc1mJnHDYC1siNYX',
                        'hostName' => 'api-33e9f1fb.duosecurity.com'
                    ]
                ]);

                return;
            case 'valid':
                $this->mockMfaOrgSettings([
                    MfaSettings::PROVIDERS => [MfaSettings::PROVIDER_DUO => true],
                    MfaSettings::PROVIDER_DUO => [
                        'salt' => '__CHANGE_ME__THIS_MUST_BE_AT_LEAST_FOURTY_CHARACTERS_____',
                        'integrationKey' => 'CICDIC95F13URR1FW5SJ',
                        'secretKey' => '7tkYNgi8aGAuv3K31eq2sJLfIc1mJnHDYC1siNYX',
                        'hostName' => 'api-33e9f1fb.duosecurity.com'
                    ]
                ]);
                $this->mockMfaAccountSettings($user, [
                    MfaSettings::PROVIDERS => [MfaSettings::PROVIDER_DUO],
                    MfaSettings::PROVIDER_DUO => [
                        MfaAccountSettings::VERIFIED => FrozenTime::now()
                    ]
                ]);

                return;
        }
    }
}

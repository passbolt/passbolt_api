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
namespace Passbolt\MultiFactorAuthentication\Test\Lib;

use Cake\I18n\FrozenTime;
use Passbolt\MultiFactorAuthentication\Utility\MfaAccountSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaOtpFactory;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

trait MfaTotpSettingsTestTrait
{
    /**
     * @param string $user
     * @param string $case
     * @return string|null provisioning uri
     */
    public function mockMfaTotpSettings($user = 'ada', $case = 'valid')
    {
        $uac = $this->mockUserAccessControl('ada');
        $uri = null;
        switch ($case) {
            case 'orgOnly':
                $this->mockMfaOrgSettings([
                    MfaSettings::PROVIDERS => [MfaSettings::PROVIDER_TOTP => true],
                ]);
                break;
            case 'valid':
                $uri = MfaOtpFactory::generateTOTP($uac);
                $this->mockMfaOrgSettings([
                    MfaSettings::PROVIDERS => [MfaSettings::PROVIDER_TOTP => true],
                ]);
                $this->mockMfaAccountSettings('ada', [
                    MfaSettings::PROVIDERS => [MfaSettings::PROVIDER_TOTP],
                    MfaSettings::PROVIDER_TOTP => [
                        MfaAccountSettings::VERIFIED => FrozenTime::now(),
                        MfaAccountSettings::OTP_PROVISIONING_URI => $uri,
                    ],
                ]);
                break;
        }

        return $uri;
    }
}

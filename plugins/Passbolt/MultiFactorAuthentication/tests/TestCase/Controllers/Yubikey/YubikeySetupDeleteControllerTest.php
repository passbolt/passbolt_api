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
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Controllers\Yubikey;

use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class YubikeySetupDeleteControllerTest extends MfaIntegrationTestCase
{
    /**
     * @group mfa
     * @group mfaSetup
     * @group mfaSetupDelete
     * @group mfaSetupDeleteYubikey
     */
    public function testMfaSetupDeleteYubikeyNotAuthenticated()
    {
        $this->delete('/mfa/setup/yubikey.json?api-version=v2');
        $this->assertResponseError('You need to login to access this location.');
    }

    /**
     * @group mfa
     * @group mfaSetup
     * @group mfaSetupDelete
     * @group mfaSetupDeleteYubikey
     */
    public function testMfaSetupDeleteYubikeySuccessNothingToDelete()
    {
        $this->authenticateAs('ada');
        $this->delete('/mfa/setup/yubikey.json?api-version=v2');
        $this->assertResponseSuccess();
        $this->assertResponseContains('Nothing to delete');
    }

    /**
     * @group mfa
     * @group mfaSetup
     * @group mfaSetupDelete
     * @group mfaSetupDeleteYubikey
     */
    public function testMfaSetupDeleteYubikeySuccessDeleted()
    {
        $this->mockMfaVerified('ada', MfaSettings::PROVIDER_YUBIKEY);
        $this->mockMfaYubikeySettings('ada', 'valid');
        $this->authenticateAs('ada');
        $this->delete('/mfa/setup/yubikey.json?api-version=v2');
        $this->assertResponseSuccess();
        $this->assertResponseContains('The configuration was deleted.');
    }
}

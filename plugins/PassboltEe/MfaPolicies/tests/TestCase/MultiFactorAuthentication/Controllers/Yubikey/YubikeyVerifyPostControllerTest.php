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
 * @since         3.10.0
 */
namespace Passbolt\MfaPolicies\Test\TestCase\MultiFactorAuthentication\Controllers\Yubikey;

use Passbolt\MfaPolicies\Test\Factory\MfaPoliciesSettingFactory;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Yubikey\MfaYubikeyScenario;

class YubikeyVerifyPostControllerTest extends MfaIntegrationTestCase
{
    public function testMfaVerifyPostYubikey_OTPFieldMissing_RememberMeOptionDisabled()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaYubikeyScenario::class, $user, false);
        MfaPoliciesSettingFactory::make()->setRememberMeForAMonth(false)->persist();

        $this->post('/mfa/verify/yubikey.json?api-version=v2');

        $this->assertResponseCode(400);
        $this->assertResponseError('The OTP should not be empty.');
        $this->assertResponseNotContains('<input type="checkbox" name="remember"');
        $this->assertResponseNotContains('Remember this device for a month');
    }

    public function testMfaVerifyPostYubikey_OTP_Not_Valid_RememberMeOptionDisabled()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaYubikeyScenario::class, $user, false);
        MfaPoliciesSettingFactory::make()->setRememberMeForAMonth(false)->persist();

        $this->post('/mfa/verify/yubikey.json?api-version=v2', [
            'hotp' => 'invalid-otp',
        ]);

        $this->assertResponseCode(400);
        $this->assertResponseError('This OTP is not valid.');
        $this->assertResponseNotContains('<input type="checkbox" name="remember"');
        $this->assertResponseNotContains('Remember this device for a month');
    }
}

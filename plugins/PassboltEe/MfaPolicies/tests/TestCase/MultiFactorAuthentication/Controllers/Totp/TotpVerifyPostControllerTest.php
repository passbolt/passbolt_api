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
namespace Passbolt\MfaPolicies\Test\TestCase\MultiFactorAuthentication\Controllers\Totp;

use Passbolt\MfaPolicies\Test\Factory\MfaPoliciesSettingFactory;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Totp\MfaTotpScenario;

class TotpVerifyPostControllerTest extends MfaIntegrationTestCase
{
    public function testMfaVerifyPostTotpUriFailFieldValidation_RememberMeOptionDisabled()
    {
        $redirect = '/foo';
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaTotpScenario::class, $user);
        MfaPoliciesSettingFactory::make()->setRememberMeForAMonth(false)->persist();

        $this->post('/mfa/verify/totp?redirect=' . $redirect);

        $this->assertResponseOk();
        $this->assertResponseContains('The OTP should not be empty.');
        $this->assertResponseNotContains('<input type="checkbox" name="remember"');
        $this->assertResponseNotContains('Remember this device for a month');
    }

    public function testMfaVerifyPostTotpUriFailWhenWrongTotp_RememberMeOptionDisabled()
    {
        $redirect = '/foo';
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaTotpScenario::class, $user);
        MfaPoliciesSettingFactory::make()->setRememberMeForAMonth(false)->persist();

        $this->post('/mfa/verify/totp?redirect=' . $redirect, [
            'totp' => 'blah',
        ]);

        $this->assertResponseOk();
        $this->assertResponseContains('The OTP should be composed of numbers only.');
        $this->assertResponseNotContains('<input type="checkbox" name="remember"');
        $this->assertResponseNotContains('Remember this device for a month');
    }
}

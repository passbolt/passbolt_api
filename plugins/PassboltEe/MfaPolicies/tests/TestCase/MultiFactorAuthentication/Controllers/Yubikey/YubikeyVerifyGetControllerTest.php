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
use Passbolt\MultiFactorAuthentication\Form\Yubikey\YubikeyVerifyForm;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Yubikey\MfaYubikeyScenario;

class YubikeyVerifyGetControllerTest extends MfaIntegrationTestCase
{
    public function testMfaVerifyGetYubikey_Success_RememberMeOptionDisabled()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaYubikeyScenario::class, $user);
        $this->mockValidMfaFormInterface(YubikeyVerifyForm::class, $this->makeUac($user));
        MfaPoliciesSettingFactory::make()->setRememberMeForAMonth(false)->persist();

        $this->get('/mfa/verify/yubikey?api-version=v2&redirect=/app/users');

        $this->assertResponseSuccess();
        $this->assertResponseContains('<form');
        $this->assertResponseContains('<input type="password" name="hotp"');
        $this->assertResponseContains('/app/users');
        $this->assertResponseNotContains('<input type="checkbox" name="remember"');
        $this->assertResponseNotContains('Remember this device for a month');
    }
}

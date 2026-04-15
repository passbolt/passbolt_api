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
namespace Passbolt\MfaPolicies\Test\TestCase\MultiFactorAuthentication\Controllers\Totp;

use Passbolt\MfaPolicies\Test\Factory\MfaPoliciesSettingFactory;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Totp\MfaTotpScenario;

class TotpVerifyGetControllerTest extends MfaIntegrationTestCase
{
    public function testMfaVerifyGetTotpSuccess_RememberMeOptionDisabled()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaTotpScenario::class, $user);
        MfaPoliciesSettingFactory::make()->setRememberMeForAMonth(false)->persist();

        $this->get('/mfa/verify/totp?redirect=/app/users');

        $this->assertResponseOk();
        $this->assertResponseContains('<form');
        $this->assertResponseContains('<input type="text" name="totp"');
        $this->assertResponseContains('/app/users');
        $this->assertResponseNotContains('<input type="checkbox" name="remember"');
        $this->assertResponseNotContains('Remember this device for a month');
    }
}

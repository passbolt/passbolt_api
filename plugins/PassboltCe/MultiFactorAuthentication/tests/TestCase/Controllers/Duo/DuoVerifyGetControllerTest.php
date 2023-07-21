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
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Controllers\Duo;

use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Duo\MfaDuoScenario;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class DuoVerifyGetControllerTest extends MfaIntegrationTestCase
{
    /**
     * @group mfa
     * @group mfaVerify
     * @group mfaVerifyGet
     */
    public function testMfaVerifyGetDuoNotAuthenticated()
    {
        $this->get('/mfa/verify/duo.json?api-version=v2');
        $this->assertResponseError('You need to login to access this location.');
    }

    public function testMfaVerifyGetDuo_Mfa_Required_With_Redirect()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaDuoScenario::class, $user, true, 'Bar');
        $this->get('/mfa/verify/duo?api-version=v2&redirect=/app/users');
        $this->assertResponseSuccess();
        $this->assertResponseContains('/app/users');
    }

    public function testMfaVerifyGetDuo_Mfa_Not_Required()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaDuoScenario::class, $user);
        $this->mockMfaCookieValid($this->makeUac($user), MfaSettings::PROVIDER_DUO);
        $this->get('/mfa/verify/duo?api-version=v2');
        $this->assertResponseCode(400);
        $this->assertResponseContains('The multi-factor authentication is not required.');
    }
}

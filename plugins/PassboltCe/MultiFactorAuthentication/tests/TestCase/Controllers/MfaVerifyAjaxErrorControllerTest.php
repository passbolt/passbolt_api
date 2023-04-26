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
 * @since         3.3.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Controllers;

use Cake\Core\Configure;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Duo\MfaDuoScenario;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Totp\MfaTotpScenario;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Yubikey\MfaYubikeyScenario;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;

class MfaVerifyAjaxErrorControllerTest extends MfaIntegrationTestCase
{
    public function dataProviderForScenarios()
    {
        $fullBase = Configure::read('App.fullBaseUrl');

        return [
            [MfaDuoScenario::class, 'duo'],
            [MfaYubikeyScenario::class, 'yubikey'],
            [MfaTotpScenario::class, 'totp'],
        ];
    }

    /**
     * @group mfa
     * @group mfaVerify
     * @dataProvider dataProviderForScenarios
     */
    public function testMfaVerifyAjaxErrorController(string $scenario, string $expectedProvider)
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario($scenario, $user);
        $this->cookie(MfaVerifiedCookie::MFA_COOKIE_ALIAS, 'foo');
        $this->getJson('/mfa/verify/error.json?api-version=v2');
        $this->assertResponseCode(403);
        $response = $this->getResponseBodyAsArray();
        $expected = [
            'mfa_providers' => [$expectedProvider],
            'providers' => [
                $expectedProvider => Configure::read('App.fullBaseUrl') . "/mfa/verify/$expectedProvider.json",
            ],
        ];
        $this->assertSame($expected, $response);

        // Check that the MFA cookie is set and expired, but other cookie remain untouched.
        $this->assertCookieExpired(MfaVerifiedCookie::MFA_COOKIE_ALIAS);
    }
}

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

use App\Test\Factory\AuthenticationTokenFactory;
use Passbolt\MultiFactorAuthentication\Form\Duo\DuoSetupForm;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Duo\MfaDuoOrganizationOnlyScenario;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;

class DuoSetupPostControllerTest extends MfaIntegrationTestCase
{
    /**
     * @group mfa
     * @group mfaSetup
     */
    public function testMfaSetupPostDuoNotAuthenticated()
    {
        $this->post('/mfa/setup/duo.json?api-version=v2', []);
        $this->assertResponseError('You need to login to access this location.');
    }

    /**
     * @group mfa
     */
    public function testMfaSetupPostDuo_Success()
    {
        $user = $this->logInAsUser();
        $sessionID = 'Foo';
        $this->loadFixtureScenario(MfaDuoOrganizationOnlyScenario::class);
        $this->mockValidMfaFormInterface(DuoSetupForm::class, $this->makeUac($user));
        $this->mockSessionId($sessionID);
        $this->post('/mfa/setup/duo?api-version=v2');
        $this->assertResponseSuccess();
        /** @var \App\Model\Entity\AuthenticationToken $mfaToken */
        $mfaToken = AuthenticationTokenFactory::find()->firstOrFail();
        $this->assertTrue($mfaToken->checkSessionId($sessionID));
        $this->assertCookieIsSecure($mfaToken->token, MfaVerifiedCookie::MFA_COOKIE_ALIAS);
    }

    /**
     * @group mfa
     */
    public function testMfaSetupPostDuo_Invalid()
    {
        $user = $this->logInAsUser();
        $sessionID = 'Foo';
        $hostName = 'Bar';
        $this->loadFixtureScenario(MfaDuoOrganizationOnlyScenario::class, true, $hostName);
        $this->mockInvalidMfaFormInterface(DuoSetupForm::class, $this->makeUac($user));
        $this->mockSessionId($sessionID);
        $this->post('/mfa/setup/duo?api-version=v2');
        $this->assertResponseSuccess();
        $this->assertResponseContains('data-host="' . $hostName . '"');
        $this->assertSame(0, AuthenticationTokenFactory::count());
    }
}

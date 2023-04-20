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
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Controllers;

use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Duo\MfaDuoScenario;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Multi\MfaTotpDuoScenario;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Totp\MfaTotpUserOnlyScenario;

class MfaVerifyControllerTest extends MfaIntegrationTestCase
{
    /**
     * @group mfa
     * @group mfaVerify
     */
    public function testMfaVerifyGetWrongProvider()
    {
        $this->get('/mfa/verify/nope.json?api-version=v2');
        $this->assertResponseError();
    }

    /**
     * @group mfa
     * @group mfaVerify
     */
    public function testMfaVerifyPostWrongProvider()
    {
        $this->post('/mfa/verify/nope.json?api-version=v2', []);
        $this->assertResponseError();
    }

    public function testMfaVerifyControllerHandleInvalidSettings()
    {
        $this->logInAsUser();

        $this->get('/mfa/verify/totp');
        $this->assertRedirect('/');

        $this->getJson('/mfa/verify/totp.json');
        $this->assertInternalError('No valid multi-factor authentication settings found.');
    }

    public function testMfaVerifyControllerHandleInvalidSettings_MFASetForUserButNotForOrg()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaTotpUserOnlyScenario::class, $user);

        $this->get('/mfa/verify/totp');
        $this->assertRedirect('/');

        $this->get('/mfa/verify/totp.json');
        $this->assertResponseError('No valid multi-factor authentication settings found for this provider.');
    }

    public function testMfaVerifyControllerTest_Success()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaDuoScenario::class, $user);

        $this->get('/mfa/verify/duo?api-version=v2');
        $this->assertResponseSuccess();
        $this->assertResponseContains('Multi factor authentication verification'); // window title
        $this->assertResponseContains('Multi Factor Authentication Required'); // page title
        $this->assertResponseContains('Sign-in with Duo'); // submit button
    }

    public function testMfaVerifyControllerTest_testMultipleProviers()
    {
        $redirect = '/app/users';
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaTotpDuoScenario::class, $user, true);

        $this->get('/mfa/verify/duo?api-version=v2&redirect=' . $redirect);
        $this->assertResponseSuccess();
        $this->assertResponseContains('mfa/verify/duo/prompt?redirect=' . $redirect);
        $this->assertResponseContains('Or try with another provider');
        $this->assertResponseContains('mfa/verify/totp?redirect=' . $redirect);
    }
}

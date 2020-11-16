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
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;

class MfaMiddlewareTest extends MfaIntegrationTestCase
{
    /**
     * @group mfa
     * @group mfaMiddleware
     */
    public function testMfaMiddlewareVerifyNotNeededAnonymousUser()
    {
        $this->get('/app/users');
        $this->assertRedirect('/auth/login?redirect=%2Fapp%2Fusers');
    }

    /**
     * @group mfa
     * @group mfaMiddleware
     */
    public function testMfaMiddlewareVerifyNotNeededOnLogout()
    {
        $this->mockMfaDuoSettings('ada', 'valid');
        $this->authenticateAs('ada');
        $this->get('/auth/logout');
        $this->assertRedirect('/auth/login');
    }

    /**
     * @group mfa
     * @group mfaMiddleware
     */
    public function testMfaMiddlewareErrorNoVerifyCookie()
    {
        $this->mockMfaDuoSettings('ada', 'valid');
        $this->authenticateAs('ada');
        $this->get('/app/users');
        $this->assertRedirect('/mfa/verify/duo?redirect=/app/users');
    }

    /**
     * @group mfa
     * @group mfaMiddleware
     */
    public function testMfaMiddlewareErrorInvalidVerifyCookie()
    {
        $this->cookieEncrypted(MfaVerifiedCookie::MFA_COOKIE_ALIAS, 'Invalid secret');
        $this->mockMfaDuoSettings('ada', 'valid');
        $this->authenticateAs('ada');
        $this->delete('/mfa/setup/duo.json?api-version=v2');
        $this->assertRedirect('/mfa/verify/error.json');
    }
}

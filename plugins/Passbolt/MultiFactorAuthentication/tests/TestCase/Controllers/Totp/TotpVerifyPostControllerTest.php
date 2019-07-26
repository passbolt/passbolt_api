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
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Controllers\Totp;

use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use OTPHP\Factory;

class TotpVerifyPostControllerTest extends MfaIntegrationTestCase
{
    /**
     * @group mfa
     * @group mfaVerify
     * @group mfaVerifyPost
     */
    public function testMfaVerifyPostTotpNotAuthenticated()
    {
        $this->post('/mfa/verify/totp.json?api-version=v2', []);
        $this->assertResponseError('You need to login to access this location.');
    }

    /**
     * @group mfa
     * @group mfaVerify
     * @group mfaVerifyPost
     * @group mfaVerifyPostTotp
     */
    public function testMfaVerifyPostTotpUriSuccess()
    {
        $user = 'ada';
        $this->authenticateAs($user);
        $uri = $this->mockMfaTotpSettings($user, 'valid');
        $otp = Factory::loadFromProvisioningUri($uri);
        $this->post('/mfa/verify/totp?redirect=/app/users', [
            'totp' => $otp->now()
        ]);
        $this->assertRedirect('/app/users');
    }

    /**
     * @group mfa
     * @group mfaVerifys
     * @group mfaVerifyPost
     * @group mfaVerifyPostTotp
     */
    public function testMfaVerifyPostTotpUriSuccessJson()
    {
        $user = 'ada';
        $this->authenticateAs($user);
        $uri = $this->mockMfaTotpSettings($user, 'valid');
        $otp = Factory::loadFromProvisioningUri($uri);
        $this->post('/mfa/verify/totp.json?api-version=v2', [
            'totp' => $otp->now()
        ]);
        $this->assertResponseOk();
    }

}

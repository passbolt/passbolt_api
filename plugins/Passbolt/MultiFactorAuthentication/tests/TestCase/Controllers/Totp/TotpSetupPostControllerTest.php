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

use OTPHP\Factory;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Utility\MfaOtpFactory;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class TotpSetupPostControllerTest extends MfaIntegrationTestCase
{
    /**
     * @group mfa
     * @group mfaSetup
     * @group mfaSetupPost
     * @group mfaSetupPostTotp
     */
    public function testMfaSetupPostTotpNotAuthenticated()
    {
        $this->post('/mfa/setup/totp.json?api-version=v2');
        $this->assertResponseError('You need to login to access this location.');
    }

    /**
     * @group mfa
     * @group mfaSetup
     * @group mfaSetupPost
     * @group mfaSetupPostTotp
     */
    public function testMfaSetupPostTotpProviderNotSupportedByOrg()
    {
        $this->mockMfaOrgSettings(['providers' => [MfaSettings::PROVIDER_TOTP => 0]]);
        $user = 'ada';
        $this->authenticateAs($user);
        $uac = $this->mockUserAccessControl($user);
        $uri = MfaOtpFactory::generateTOTP($uac);
        $this->post('/mfa/setup/totp.json?api-version=v2', [
            'otpProvisioningUri' => $uri,
            'totp' => '12345'
        ]);
        $this->assertResponseError();
        $this->assertResponseContains('not enabled for your organization');
    }

    /**
     * @group mfa
     * @group mfaSetup
     * @group mfaSetupPost
     * @group mfaSetupPostTotp
     */
    public function testMfaSetupPostTotpProviderAlreadySetJson()
    {
        $user = 'ada';
        $this->authenticateAs($user);
        $this->mockMfaVerified($user, MfaSettings::PROVIDER_TOTP);
        $this->mockMfaTotpSettings($user, 'valid');
        $uac = $this->mockUserAccessControl($user);
        $uri = MfaOtpFactory::generateTOTP($uac);
        $this->post('/mfa/setup/totp.json?api-version=v2', [
            'otpProvisioningUri' => $uri,
            'totp' => '12345'
        ]);
        $this->assertResponseError();
        $this->assertResponseContains('already setup');
    }

    /**
     * @group mfa
     * @group mfaSetup
     * @group mfaSetupPost
     * @group mfaSetupPostTotp
     */
    public function testMfaSetupPostTotpInvalidOTP()
    {
        $user = 'ada';
        $this->authenticateAs($user);
        $this->mockMfaTotpSettings($user, 'orgOnly');
        $uac = $this->mockUserAccessControl($user);
        $uri = MfaOtpFactory::generateTOTP($uac);
        $this->post('/mfa/setup/totp.json?api-version=v2', [
            'otpProvisioningUri' => $uri,
            'totp' => '12345'
        ]);
        $this->assertResponseError();
        $this->assertResponseContains('This OTP is not valid.');
    }

    /**
     * @group mfa
     * @group mfaSetup
     * @group mfaSetupPost
     * @group mfaSetupPostTotp
     */
    public function testMfaSetupPostTotpEmptyOTP()
    {
        $user = 'ada';
        $this->authenticateAs($user);
        $this->mockMfaTotpSettings($user, 'orgOnly');
        $uac = $this->mockUserAccessControl($user);
        $uri = MfaOtpFactory::generateTOTP($uac);
        $this->post('/mfa/setup/totp.json?api-version=v2', [
            'otpProvisioningUri' => $uri,
            'totp' => ''
        ]);
        $this->assertResponseError();
        $this->assertResponseContains('The OTP should not be empty.');
    }

    /**
     * @group mfa
     * @group mfaSetup
     * @group mfaSetupPost
     * @group mfaSetupPostTotp
     */
    public function testMfaSetupPostTotpInvalidUri()
    {
        $user = 'ada';
        $this->authenticateAs($user);
        $this->mockMfaTotpSettings($user, 'orgOnly');
        $this->post('/mfa/setup/totp.json?api-version=v2', [
            'otpProvisioningUri' => 'not a valid uri',
            'totp' => '12345'
        ]);
        $this->assertResponseError();
        $this->assertResponseContains('This OTP is not valid.');
    }

    /**
     * @group mfa
     * @group mfaSetup
     * @group mfaSetupPost
     * @group mfaSetupPostTotp
     */
    public function testMfaSetupPostTotpEmptyUri()
    {
        $user = 'ada';
        $this->authenticateAs($user);
        $this->mockMfaTotpSettings($user, 'orgOnly');
        $this->post('/mfa/setup/totp.json?api-version=v2', [
            'otpProvisioningUri' => '',
            'totp' => '12345'
        ]);
        $this->assertResponseError();
        $this->assertResponseContains('This OTP is not valid.');
    }

    /**
     * @group mfa
     * @group mfaSetup
     * @group mfaSetupPost
     * @group mfaSetupPostTotp
     */
    public function testMfaSetupPostTotpSuccessSelfGeneratedUriSuccess()
    {
        $user = 'ada';
        $this->authenticateAs($user);
        $this->mockMfaTotpSettings($user, 'orgOnly');
        $uac = $this->mockUserAccessControl($user);
        $uri = MfaOtpFactory::generateTOTP($uac);
        $otp = Factory::loadFromProvisioningUri($uri);
        $this->post('/mfa/setup/totp.json?api-version=v2', [
            'otpProvisioningUri' => $uri,
            'totp' => $otp->now()
        ]);
        $this->assertResponseOk();
    }

    /**
     * @group mfa
     * @group mfaSetup
     * @group mfaSetupPost
     * @group mfaSetupPostTotp
     */
    public function testMfaSetupPostTotpUriFromGetSuccess()
    {
        $user = 'ada';
        $this->authenticateAs($user);
        $this->mockMfaTotpSettings($user, 'orgOnly');

        $this->getJson('/mfa/setup/totp.json?api-version=v2');
        $this->assertResponseOk();
        $this->assertNotEmpty($this->_responseJsonBody->otpProvisioningUri);
        $uri = $this->_responseJsonBody->otpProvisioningUri;
        $otp = Factory::loadFromProvisioningUri($uri);

        $this->post('/mfa/setup/totp.json?api-version=v2', [
            'otpProvisioningUri' => $uri,
            'totp' => $otp->now()
        ]);
        $this->assertResponseOk();
    }

    /**
     * @group mfa
     * @group mfaSetup
     * @group mfaSetupPost
     * @group mfaSetupPostTotp
     */
    public function testMfaSetupPostTotpSuccessContainDisableLink()
    {
        $user = 'ada';
        $this->authenticateAs($user);
        $this->mockMfaTotpSettings($user, 'orgOnly');
        $uac = $this->mockUserAccessControl($user);
        $uri = MfaOtpFactory::generateTOTP($uac);
        $otp = Factory::loadFromProvisioningUri($uri);
        $this->post('/mfa/setup/totp', [
            'otpProvisioningUri' => $uri,
            'totp' => $otp->now()
        ]);
        $this->assertResponseOk();
        $this->assertResponseContains('successAnimation');
        $this->assertResponseContains('js_mfa_provider_disable');
    }
}

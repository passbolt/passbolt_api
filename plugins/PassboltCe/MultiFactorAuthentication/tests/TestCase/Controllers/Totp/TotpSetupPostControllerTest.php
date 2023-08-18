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
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Controllers\Totp;

use App\Test\Factory\UserFactory;
use Cake\Core\Configure;
use OTPHP\Factory;
use Passbolt\MultiFactorAuthentication\Test\Factory\MfaAuthenticationTokenFactory;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Totp\MfaTotpOrganizationOnlyScenario;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Totp\MfaTotpScenario;
use Passbolt\MultiFactorAuthentication\Utility\MfaOtpFactory;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;

class TotpSetupPostControllerTest extends MfaIntegrationTestCase
{
    /**
     * @group mfa
     * @group mfaSetup
     * @group mfaSetupPost
     * @group mfaSetupPostTotp
     */
    public function testTotpSetupPostController_NotAuthenticated()
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
    public function testTotpSetupPostController_ProviderNotSupportedByOrg()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaTotpOrganizationOnlyScenario::class, false);
        $uri = MfaOtpFactory::generateTOTP($this->makeUac($user));
        $this->post('/mfa/setup/totp.json?api-version=v2', [
            'otpProvisioningUri' => $uri,
            'totp' => '12345',
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
    public function testTotpSetupPostController_ProviderAlreadySetJson()
    {
        $user = $this->logInAsUser();
        $uac = $this->makeUac($user);
        $this->loadFixtureScenario(MfaTotpScenario::class, $user);
        $this->mockMfaCookieValid($uac, MfaSettings::PROVIDER_TOTP);
        $uri = MfaOtpFactory::generateTOTP($uac);
        $this->post('/mfa/setup/totp.json?api-version=v2', [
            'otpProvisioningUri' => $uri,
            'totp' => '12345',
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
    public function testTotpSetupPostController_InvalidOTP()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaTotpOrganizationOnlyScenario::class);
        $uri = MfaOtpFactory::generateTOTP($this->makeUac($user));
        $this->post('/mfa/setup/totp.json?api-version=v2', [
            'otpProvisioningUri' => $uri,
            'totp' => '12345',
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
    public function testTotpSetupPostController_EmptyOTP()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaTotpOrganizationOnlyScenario::class);
        $uri = MfaOtpFactory::generateTOTP($this->makeUac($user));
        $this->post('/mfa/setup/totp.json?api-version=v2', [
            'otpProvisioningUri' => $uri,
            'totp' => '',
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
    public function testTotpSetupPostController_InvalidUri()
    {
        $this->logInAsUser();
        $this->loadFixtureScenario(MfaTotpOrganizationOnlyScenario::class);
        $this->post('/mfa/setup/totp.json?api-version=v2', [
            'otpProvisioningUri' => 'not a valid uri',
            'totp' => '12345',
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
    public function testTotpSetupPostController_EmptyUri()
    {
        $this->logInAsUser();
        $this->loadFixtureScenario(MfaTotpOrganizationOnlyScenario::class);
        $this->post('/mfa/setup/totp.json?api-version=v2', [
            'otpProvisioningUri' => '',
            'totp' => '12345',
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
    public function testTotpSetupPostController_SuccessSelfGeneratedUriSuccess()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaTotpOrganizationOnlyScenario::class);
        $uri = MfaOtpFactory::generateTOTP($this->makeUac($user));
        /** @var \OTPHP\TOTPInterface $otp */
        $otp = Factory::loadFromProvisioningUri($uri);
        $sessionId = 'some_session_id';
        $this->mockSessionId($sessionId);
        $this->post('/mfa/setup/totp.json?api-version=v2', [
            'otpProvisioningUri' => $uri,
            'totp' => $otp->now(),
        ]);
        $this->assertResponseOk();

        /** @var \App\Model\Entity\AuthenticationToken $mfaCookie */
        $mfaCookie = MfaAuthenticationTokenFactory::find()->first();
        $this->assertCookieIsSecure($mfaCookie->get('token'), MfaVerifiedCookie::MFA_COOKIE_ALIAS);
        $this->assertTrue($mfaCookie->checkSessionId($sessionId));
    }

    /**
     * @group mfa
     * @group mfaSetup
     * @group mfaSetupPost
     * @group mfaSetupPostTotp
     */
    public function testTotpSetupPostController_SuccessSelfGeneratedUriSuccess_JWT_Auth()
    {
        $user = UserFactory::make()->user()->persist();
        $accessToken = $this->createJwtTokenAndSetInHeader($user->id);
        $this->loadFixtureScenario(MfaTotpOrganizationOnlyScenario::class);
        $uri = MfaOtpFactory::generateTOTP($this->makeUac($user));
        /** @var \OTPHP\TOTPInterface $otp */
        $otp = Factory::loadFromProvisioningUri($uri);
        $this->post('/mfa/setup/totp.json?api-version=v2', [
            'otpProvisioningUri' => $uri,
            'totp' => $otp->now(),
        ]);
        $this->assertResponseOk();

        /** @var \App\Model\Entity\AuthenticationToken $mfaCookie */
        $mfaCookie = MfaAuthenticationTokenFactory::find()->first();
        $this->assertCookieIsSecure($mfaCookie->token, MfaVerifiedCookie::MFA_COOKIE_ALIAS);
        $this->assertTrue($mfaCookie->checkSessionId($accessToken));
    }

    /**
     * @group mfa
     * @group mfaSetup
     * @group mfaSetupPost
     * @group mfaSetupPostTotp
     */
    public function testTotpSetupPostController_UriFromGetSuccess()
    {
        $this->logInAsUser();
        $this->loadFixtureScenario(MfaTotpOrganizationOnlyScenario::class);

        $this->getJson('/mfa/setup/totp.json?api-version=v2');
        $this->assertResponseOk();
        $this->assertNotEmpty($this->_responseJsonBody->otpProvisioningUri);
        $uri = $this->_responseJsonBody->otpProvisioningUri;
        /** @var \OTPHP\TOTPInterface $otp */
        $otp = Factory::loadFromProvisioningUri($uri);

        $this->post('/mfa/setup/totp.json?api-version=v2', [
            'otpProvisioningUri' => $uri,
            'totp' => $otp->now(),
        ]);
        $this->assertResponseOk();
    }

    /**
     * @group mfa
     * @group mfaSetup
     * @group mfaSetupPost
     * @group mfaSetupPostTotp
     */
    public function testTotpSetupPostController_SuccessContainDisableLink()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaTotpOrganizationOnlyScenario::class);
        $uri = MfaOtpFactory::generateTOTP($this->makeUac($user));
        /** @var \OTPHP\TOTPInterface $otp */
        $otp = Factory::loadFromProvisioningUri($uri);
        $this->post('/mfa/setup/totp', [
            'otpProvisioningUri' => $uri,
            'totp' => $otp->now(),
        ]);
        $this->assertResponseOk();
        $this->assertResponseContains('success');
        $this->assertResponseContains('js_mfa_provider_disable');
    }

    /**
     * @group mfa
     * @group mfaSetup
     * @group mfaSetupPost
     * @group mfaSetupPostTotp
     */
    public function testTotpSetupPostController_Success_With_Legacy_Secret_Length()
    {
        $originalSecretLength = Configure::read(MfaOtpFactory::PASSBOLT_PLUGINS_MFA_TOTP_SECRET_LENGTH);
        $secretLength = 256;
        Configure::write(MfaOtpFactory::PASSBOLT_PLUGINS_MFA_TOTP_SECRET_LENGTH, $secretLength);

        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaTotpOrganizationOnlyScenario::class);
        $uri = MfaOtpFactory::generateTOTP($this->makeUac($user));
        /** @var \OTPHP\TOTPInterface $otp */
        $otp = Factory::loadFromProvisioningUri($uri);
        $this->post('/mfa/setup/totp', [
            'otpProvisioningUri' => $uri,
            'totp' => $otp->now(),
        ]);
        $this->assertResponseOk();
        $this->assertResponseContains('success');

        Configure::write(MfaOtpFactory::PASSBOLT_PLUGINS_MFA_TOTP_SECRET_LENGTH, $originalSecretLength);
    }
}

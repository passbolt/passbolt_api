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

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UserAction;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\I18n\DateTime;
use OTPHP\Factory;
use Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenAbstractService;
use Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenRenewalService;
use Passbolt\Log\Test\Factory\ActionLogFactory;
use Passbolt\MultiFactorAuthentication\Form\Totp\TotpVerifyForm;
use Passbolt\MultiFactorAuthentication\Service\MfaRateLimitDisableUserService;
use Passbolt\MultiFactorAuthentication\Test\Factory\MfaAccountSettingFactory;
use Passbolt\MultiFactorAuthentication\Test\Factory\MfaOrganizationSettingFactory;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Totp\MfaTotpScenario;
use Passbolt\MultiFactorAuthentication\Utility\MfaOtpFactory;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;

/**
 * @see \Passbolt\MultiFactorAuthentication\Controller\Totp\TotpVerifyPostController
 */
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
        $redirect = '/foo';
        $user = $this->logInAsUser();
        [$uri] = $this->loadFixtureScenario(MfaTotpScenario::class, $user);
        /** @var \OTPHP\TOTPInterface $otp */
        $otp = Factory::loadFromProvisioningUri($uri);
        $this->mockTotpMfaFormInterface(TotpVerifyForm::class, $this->makeUac($user));

        $this->post('/mfa/verify/totp?redirect=' . $redirect, [
            'totp' => $otp->now(),
        ]);

        $this->assertRedirect($redirect);
    }

    /**
     * @group mfa
     * @group mfaVerify
     * @group mfaVerifyPost
     * @group mfaVerifyPostTotp
     */
    public function testMfaVerifyPostTotpUriFail()
    {
        $redirect = '/foo';
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaTotpScenario::class, $user);
        $this->post('/mfa/verify/totp?redirect=' . $redirect, [
            'totp' => 'blah',
        ]);
        $this->assertResponseOk();
        $this->assertResponseContains('The OTP should be composed of numbers only.');
        $this->assertResponseContains('<input type="checkbox" name="remember"');
        $this->assertResponseContains('Remember this device for a month');
    }

    /**
     * @group mfa
     * @group mfaVerify
     * @group mfaVerifyPost
     * @group mfaVerifyPostTotp
     */
    public function testMfaVerifyPostTotpUriSuccessJson()
    {
        $user = $this->logInAsUser();
        [$uri] = $this->loadFixtureScenario(MfaTotpScenario::class, $user);
        /** @var \OTPHP\TOTPInterface $otp */
        $otp = Factory::loadFromProvisioningUri($uri);
        $sessionId = 'Foo';
        $this->mockSessionId($sessionId);
        $this->post('/mfa/verify/totp.json?api-version=v2', [
            'totp' => $otp->now(),
        ]);
        $this->assertResponseOk();

        /** @var AuthenticationToken $mfaToken */
        $mfaToken = AuthenticationTokenFactory::find()
            ->where([
                'type' => AuthenticationToken::TYPE_MFA,
                'user_id' => $user->id,
            ])->firstOrFail();

        $this->assertTrue($mfaToken->checkSessionId($sessionId));
        $this->assertCookieIsSecure($mfaToken->get('token'), MfaVerifiedCookie::MFA_COOKIE_ALIAS);
    }

    /**
     * @group mfa
     * @group mfaVerify
     * @group mfaVerifyPost
     * @group mfaVerifyPostTotp
     */
    public function testMfaVerifyPostTotpUriSuccessJson_JwtLogin()
    {
        $user = UserFactory::make()->user()->persist();
        $accessToken = $this->createJwtTokenAndSetInHeader($user->id);
        [$uri] = $this->loadFixtureScenario(MfaTotpScenario::class, $user);
        /** @var \OTPHP\TOTPInterface $otp */
        $otp = Factory::loadFromProvisioningUri($uri);

        $this->post('/mfa/verify/totp.json?api-version=v2', [
            'totp' => $otp->now(),
        ]);
        $this->assertResponseOk();

        /** @var AuthenticationToken $mfaToken */
        $mfaToken = AuthenticationTokenFactory::find()
            ->where([
                'type' => AuthenticationToken::TYPE_MFA,
                'user_id' => $user->id,
            ])->orderByDesc('created')->firstOrFail();

        $this->assertTrue($mfaToken->checkSessionId($accessToken));
        $this->assertCookieIsSecure($mfaToken->get('token'), MfaVerifiedCookie::MFA_COOKIE_ALIAS);
    }

    /**
     * @group mfa
     * @group mfaVerify
     * @group mfaVerifyPost
     * @group mfaVerifyPostTotp
     */
    public function testMfaVerifyPostTotpUriError_UserLoggedOutIfMaxFailedAttemptsExceeded()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaTotpScenario::class, $user);
        // action logs
        ActionLogFactory::make(['created' => $user->created])
            ->userId($user->id)
            ->loginAction()
            ->persist();
        ActionLogFactory::make(['created' => DateTime::now()], 4)
            ->setActionId('TotpVerifyPost.post')
            ->userId($user->id)
            ->persist();

        $this->post('/mfa/verify/totp', ['totp' => 'blah']);

        $this->assertRedirect('/auth/login');
        $this->assertSessionNotHasKey('Auth');
    }

    /**
     * @group mfa
     * @group mfaVerify
     * @group mfaVerifyPost
     * @group mfaVerifyPostTotp
     */
    public function testMfaVerifyPostTotpUriErrorJson_MaxFailedAttemptsExceeded()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaTotpScenario::class, $user);
        // action logs
        ActionLogFactory::make(['created' => $user->created])
            ->userId($user->id)
            ->loginAction()
            ->persist();
        ActionLogFactory::make(['created' => DateTime::now()], 4)
            ->setActionId('TotpVerifyPost.post')
            ->userId($user->id)
            ->persist();

        $this->postJson('/mfa/verify/totp.json?api-version=v2', ['totp' => 'blah']);

        $this->assertBadRequestError('You have been logged out due to too many failed attempts');
        $this->assertSessionNotHasKey('Auth');
    }

    /**
     * @group mfa
     * @group mfaVerify
     * @group mfaVerifyPost
     * @group mfaVerifyPostTotp
     */
    public function testMfaVerifyPostTotpUriErrorJsonJwt_MaxFailedAttemptsExceeded()
    {
        $user = $this->logInAsUser();
        $this->createJwtTokenAndSetInHeader($user->id);
        $this->loadFixtureScenario(MfaTotpScenario::class, $user);
        // action logs
        ActionLogFactory::make(['created' => $user->created])
            ->userId($user->id)
            ->setActionId('JwtLogin.loginPost')
            ->persist();
        ActionLogFactory::make(['created' => DateTime::now(), 'status' => 0], 4)
            ->setActionId('TotpVerifyPost.post')
            ->userId($user->id)
            ->persist();
        // create & set refresh token
        $refreshToken = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_REFRESH_TOKEN)
            ->userId($user->id)
            ->persist()
            ->token;
        $this->cookie(RefreshTokenRenewalService::REFRESH_TOKEN_COOKIE, $refreshToken);

        $this->postJson('/mfa/verify/totp.json?api-version=v2', ['totp' => 'blah']);

        $this->assertBadRequestError('You have been logged out due to too many failed attempts');
        $this->assertSessionNotHasKey('Auth');
        $this->assertCookieNotSet(RefreshTokenAbstractService::REFRESH_TOKEN_COOKIE);
        $this->assertCookieNotSet(MfaVerifiedCookie::MFA_COOKIE_ALIAS);
        // check refresh token is deactivated
        /** @var \App\Model\Entity\AuthenticationToken $updatedRefreshToken */
        $updatedRefreshToken = AuthenticationTokenFactory::find()->where(['token' => $refreshToken])->firstOrFail();
        $this->assertFalse($updatedRefreshToken->active);
    }

    /**
     * @group mfa
     * @group mfaVerify
     * @group mfaVerifyPost
     * @group mfaVerifyPostTotp
     */
    public function testTotpVerifyPostController_Error_DisabledTotpProviderDoesNotMintCookie(): void
    {
        $user = $this->logInAsUser();
        $uri = MfaOtpFactory::generateTOTP($this->makeUac($user));
        // User's MFA account settings for TOTP is present
        MfaAccountSettingFactory::make()
            ->setField('user_id', $user->id)
            ->totp($uri)
            ->persist();
        // Only DUO provider is enabled
        MfaOrganizationSettingFactory::make()->duo()->persist();
        /** @var \OTPHP\TOTPInterface $otp */
        $otp = Factory::loadFromProvisioningUri($uri);

        $this->post('/mfa/verify/totp', ['totp' => $otp->now()]);

        $this->assertRedirect('/');
        $this->assertCookieNotSet(MfaVerifiedCookie::MFA_COOKIE_ALIAS);
    }

    /**
     * @group mfa
     * @group mfaVerify
     * @group mfaVerifyPost
     * @group mfaVerifyPostTotp
     */
    public function testTotpVerifyPostController_Error_DisabledTotpProviderJsonReturnsBadRequest(): void
    {
        $user = $this->logInAsUser();
        $uri = MfaOtpFactory::generateTOTP($this->makeUac($user));
        MfaAccountSettingFactory::make()
            ->setField('user_id', $user->id)
            ->totp($uri)
            ->persist();
        MfaOrganizationSettingFactory::make()->duo()->persist();
        /** @var \OTPHP\TOTPInterface $otp */
        $otp = Factory::loadFromProvisioningUri($uri);

        $this->postJson('/mfa/verify/totp.json?api-version=v2', ['totp' => $otp->now()]);

        $this->assertBadRequestError('No valid multi-factor authentication settings found for this provider.');
        $this->assertCookieNotSet(MfaVerifiedCookie::MFA_COOKIE_ALIAS);
    }

    /**
     * @group mfa
     * @group mfaVerify
     * @group mfaVerifyPost
     * @group mfaVerifyPostTotp
     */
    public function testTotpVerifyPostController_Error_SessionAuthJsonFailedAttemptsAreRateLimited(): void
    {
        Configure::write('passbolt.plugins.log.enabled', true);
        Configure::write(MfaRateLimitDisableUserService::CONFIG_KEY, false);
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaTotpScenario::class, $user);
        ActionLogFactory::make(['created' => DateTime::now()->subMinutes(2)])
            ->userId($user->id)
            ->loginAction()
            ->persist();

        // Default maxAttempts is 4, so the 5th failure must lock the user out
        for ($i = 0; $i < 5; $i++) {
            UserAction::destroy();
            $this->postJson('/mfa/verify/totp.json?api-version=v2', ['totp' => '123456']);
        }

        $this->assertSame(5, ActionLogFactory::find()->where([
            'user_id' => $user->id,
            'action_id' => UuidFactory::uuid('TotpVerifyPost.post'),
            'status' => 0,
        ])->all()->count());
        $this->assertBadRequestError('You have been logged out due to too many failed attempts');
    }

    /**
     * @group mfa
     * @group mfaVerify
     * @group mfaVerifyPost
     * @group mfaVerifyPostTotp
     */
    public function testTotpVerifyPostController_Error_DisablesUserWhenMaxFailedAttemptsExceeded(): void
    {
        Configure::write(MfaRateLimitDisableUserService::CONFIG_KEY, true);
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaTotpScenario::class, $user);
        ActionLogFactory::make(['created' => $user->created])
            ->userId($user->id)
            ->loginAction()
            ->persist();
        ActionLogFactory::make(['created' => DateTime::now()], 4)
            ->setActionId('TotpVerifyPost.post')
            ->userId($user->id)
            ->persist();

        $this->postJson('/mfa/verify/totp.json?api-version=v2', ['totp' => 'blah']);

        $this->assertBadRequestError('You have been logged out due to too many failed attempts');
        $this->assertNotNull(UserFactory::get($user->id)->get('disabled'));
    }

    /**
     * @group mfa
     * @group mfaVerify
     * @group mfaVerifyPost
     * @group mfaVerifyPostTotp
     */
    public function testTotpVerifyPostController_Error_DoesNotDisableUserWhenOptionIsOff(): void
    {
        Configure::write(MfaRateLimitDisableUserService::CONFIG_KEY, false);
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaTotpScenario::class, $user);
        ActionLogFactory::make(['created' => $user->created])
            ->userId($user->id)
            ->loginAction()
            ->persist();
        ActionLogFactory::make(['created' => DateTime::now()], 4)
            ->setActionId('TotpVerifyPost.post')
            ->userId($user->id)
            ->persist();

        $this->postJson('/mfa/verify/totp.json?api-version=v2', ['totp' => 'blah']);

        $this->assertBadRequestError('You have been logged out due to too many failed attempts');
        $this->assertNull(UserFactory::get($user->id)->get('disabled'));
    }
}

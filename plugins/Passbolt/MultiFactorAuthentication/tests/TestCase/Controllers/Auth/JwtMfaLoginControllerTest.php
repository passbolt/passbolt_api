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
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Controllers\Auth;

use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Cake\Datasource\ModelAwareTrait;
use Passbolt\Log\Test\Lib\Traits\ActionLogsTrait;
use Passbolt\MultiFactorAuthentication\Form\Totp\TotpVerifyForm;
use Passbolt\MultiFactorAuthentication\Test\Factory\MfaAuthenticationTokenFactory;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Totp\MfaTotpScenario;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;

/**
 * Class JwtMfaLoginControllerTest
 *
 * @property \App\Model\Table\AuthenticationTokensTable $AuthenticationTokens
 * @property \App\Model\Table\UsersTable $Users
 * @property \Passbolt\Log\Model\Table\ActionLogsTable $ActionLogs
 */
class JwtMfaLoginControllerTest extends MfaIntegrationTestCase
{
    use ActionLogsTrait;
    use ModelAwareTrait;

    public function setUp(): void
    {
        parent::setUp();

        RoleFactory::make()->guest()->persist();
    }

    public function testJwtLoginControllerTest_Success_But_MFA_Cookie_Not_Valid()
    {
        $user = UserFactory::make()
            ->user()
            ->with('Gpgkeys', GpgkeyFactory::make()->validFingerprint())
            ->persist();

        $this->loadFixtureScenario(MfaTotpScenario::class, $user);
        $this->cookie(MfaVerifiedCookie::MFA_COOKIE_ALIAS, 'foo');

        $this->postJson('/auth/jwt/login.json', [
            'user_id' => $user->id,
            'challenge' => $this->makeChallenge($user, UuidFactory::uuid()),
        ]);

        $this->assertResponseOk('The authentication was a success.');
        $challenge = json_decode($this->decryptChallenge($user, $this->_responseJsonBody->challenge), true);
        $this->assertSame([MfaSettings::PROVIDER_TOTP], $challenge['providers']);
        $this->assertCookieExpired(MfaVerifiedCookie::MFA_COOKIE_ALIAS);
    }

    public function testJwtLoginControllerTest_Login_With_Mfa_No_Remember_Me_And_With_Valid_Access_Token()
    {
        $user = UserFactory::make()
            ->user()
            ->with('Gpgkeys', GpgkeyFactory::make()->validFingerprint())
            ->persist();

        $this->loadFixtureScenario(MfaTotpScenario::class, $user);

        $accessToken = $this->createJwtTokenAndSetInHeader($user->id);

        $mfaToken = $this->mockMfaCookieValid(
            $this->makeUac($user),
            MfaSettings::PROVIDER_TOTP,
            false,
            $accessToken
        );
        $this->mockValidMfaFormInterface(TotpVerifyForm::class, $this->makeUac($user));

        $this->postJson('/auth/jwt/login.json', [
            'user_id' => $user->id,
            'challenge' => $this->makeChallenge($user, UuidFactory::uuid()),
        ]);

        $this->assertResponseOk('The authentication was a success.');
        $challenge = json_decode($this->decryptChallenge($user, $this->_responseJsonBody->challenge), true);
        $newAccessToken = $challenge['access_token'];
        // MFA required and providers are set because the mfa cookie does not match the new access token
        $this->assertTrue(isset($challenge['providers']));

        /** @var \App\Model\Entity\AuthenticationToken $mfaToken */
        $mfaToken = MfaAuthenticationTokenFactory::find()->where(['token' => $mfaToken])->firstOrFail();
        $this->assertFalse($mfaToken->isActive());
        $this->assertFalse($mfaToken->isExpired());
        $this->assertTrue($mfaToken->checkSessionId($accessToken));
        // The MFA cookie does not match the newly produced access token
        $this->assertFalse($mfaToken->checkSessionId($newAccessToken));
    }

    /**
     * @Given a user has MFA activated and a valid MFA remember token
     * @When the user successfully logs in
     * @Then the MFA token should be passed in the response and the providers not set in the challenge
     */
    public function testJwtLoginControllerTest_Login_With_Valid_Mfa_Remember_Me()
    {
        $user = UserFactory::make()
            ->user()
            ->with('Gpgkeys', GpgkeyFactory::make()->validFingerprint())
            ->persist();

        $this->loadFixtureScenario(MfaTotpScenario::class, $user);

        $mfaToken = $this->mockMfaCookieValid(
            $this->makeUac($user),
            MfaSettings::PROVIDER_TOTP,
            true,
            'FooSession' // is not relevant because of remember me
        );

        $this->postJson('/auth/jwt/login.json', [
            'user_id' => $user->id,
            'challenge' => $this->makeChallenge($user, UuidFactory::uuid()),
        ]);

        $this->assertResponseSuccess('The authentication was a success.');
        $challenge = json_decode($this->decryptChallenge($user, $this->_responseJsonBody->challenge), true);
        // Providers are not set because the provided mfa cookie is valid
        $this->assertFalse(isset($challenge['providers']));

        /** @var \App\Model\Entity\AuthenticationToken $mfaToken */
        $mfaToken = MfaAuthenticationTokenFactory::find()->where(['token' => $mfaToken])->firstOrFail();
        $this->assertTrue($mfaToken->isActive());
        $this->assertFalse($mfaToken->isExpired());
        $this->assertCookie($mfaToken->token, MfaVerifiedCookie::MFA_COOKIE_ALIAS);
    }

    /**
     * @Given a user has MFA not activated and no MFA token in header
     * @When the user successfully logs in
     * @Then the providers shall not be set in the challenge
     * @And no MFA cookie is returned
     */
    public function testJwtLoginControllerTest_Success_But_No_MFA_Required()
    {
        $user = UserFactory::make()
            ->user()
            ->with('Gpgkeys', GpgkeyFactory::make()->validFingerprint())
            ->persist();

        $this->postJson('/auth/jwt/login.json', [
            'user_id' => $user->id,
            'challenge' => $this->makeChallenge($user, UuidFactory::uuid()),
        ]);

        $this->assertResponseOk('The authentication was a success.');
        $challenge = json_decode($this->decryptChallenge($user, $this->_responseJsonBody->challenge), true);
        $this->assertFalse(isset($challenge['providers']));
        $this->assertCookieNotSet(MfaVerifiedCookie::MFA_COOKIE_ALIAS);
    }
}

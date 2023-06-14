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
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Routing\Router;
use Passbolt\JwtAuthentication\Authenticator\GpgJwtAuthenticator;
use Passbolt\JwtAuthentication\Test\Utility\JwtTestTrait;
use Passbolt\Log\Test\Factory\ActionLogFactory;
use Passbolt\Log\Test\Lib\Traits\ActionLogsTestTrait;
use Passbolt\MultiFactorAuthentication\Test\Factory\MfaAuthenticationTokenFactory;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Multi\MfaTotpDuoScenario;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Totp\MfaTotpScenario;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Yubikey\MfaYubikeyScenario;
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
    use ActionLogsTestTrait;
    use JwtTestTrait;
    use LocatorAwareTrait;

    public function setUp(): void
    {
        parent::setUp();

        RoleFactory::make()->guest()->persist();
        EventManager::instance()->setEventList(new EventList());
    }

    public function testJwtLoginControllerTest_Success_But_MFA_Cookie_Not_Valid()
    {
        $user = UserFactory::make()
            ->user()
            ->with('Gpgkeys', GpgkeyFactory::make()->validFingerprint())
            ->persist();

        $this->loadFixtureScenario(MfaTotpDuoScenario::class, $user);
        $this->cookie(MfaVerifiedCookie::MFA_COOKIE_ALIAS, 'foo');

        // Add duo as user's latest (and favorite) login
        ActionLogFactory::make()
            ->userId($user->id)
            ->setActionId('DuoVerifyGet.get')
            ->persist();

        $this->postJson('/auth/jwt/login.json', [
            'user_id' => $user->id,
            'challenge' => $this->makeChallenge($user, UuidFactory::uuid()),
        ]);

        // Duo is first as it was already used in the past
        $expectedProviders = [MfaSettings::PROVIDER_DUO, MfaSettings::PROVIDER_TOTP];
        $this->assertResponseOk('The authentication was a success.');
        $challenge = json_decode($this->decryptChallenge($user, $this->_responseJsonBody->challenge), true);
        $this->assertSame($expectedProviders, $challenge['providers']);
        $this->assertCookieExpired(MfaVerifiedCookie::MFA_COOKIE_ALIAS);
    }

    /**
     * @Given a user has a valid access token and valid MFA token not remembered
     * @When the user logs in (which is not rational)
     * @Then a new access token is generated
     * @And the previous MFA token is deactivated
     */
    public function testJwtLoginControllerTest_Login_With_Mfa_No_Remember_Me_And_With_Valid_Access_Token()
    {
        $user = UserFactory::make()
            ->user()
            ->with('Gpgkeys', GpgkeyFactory::make()->validFingerprint())
            ->persist();

        $accessToken = $this->createJwtTokenAndSetInHeader($user->id);

        $this->loadFixtureScenario(MfaYubikeyScenario::class, $user);
        $mfaToken = $this->mockMfaCookieValid(
            $this->makeUac($user),
            MfaSettings::PROVIDER_YUBIKEY,
            false,
            $accessToken
        );

        /** @see JwtTokenCreateServiceTest::testJwtTokenCreateService_Multiple_Token_Within_One_Same_Second_Should_Be_Identical */
        sleep(1);

        $this->postJson('/auth/jwt/login.json', [
            'user_id' => $user->id,
            'challenge' => $this->makeChallenge($user, UuidFactory::uuid()),
        ]);

        $this->assertResponseOk('The authentication was a success.');
        $this->assertEventFired(GpgJwtAuthenticator::JWT_AUTHENTICATION_AFTER_IDENTIFY);

        $challenge = json_decode($this->decryptChallenge($user, $this->_responseJsonBody->challenge));
        $this->assertSame(Router::url('/', true), $challenge->domain);
        $newAccessToken = $challenge->access_token;
        $this->assertNotSame($accessToken, $newAccessToken);
        // MFA required and providers are set because the mfa cookie does not match the new access token
        $this->assertTrue(isset($challenge->providers));
        $this->assertSame([MfaSettings::PROVIDER_YUBIKEY], $challenge->providers);

        /** @var \App\Model\Entity\AuthenticationToken $mfaToken */
        $mfaToken = MfaAuthenticationTokenFactory::find()->where(['token' => $mfaToken])->firstOrFail();
        $this->assertTrue($mfaToken->isNotActive());
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
        $this->assertCookieIsSecure($mfaToken->token, MfaVerifiedCookie::MFA_COOKIE_ALIAS);
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

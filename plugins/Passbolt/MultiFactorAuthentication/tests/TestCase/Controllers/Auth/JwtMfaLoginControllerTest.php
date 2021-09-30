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

    /**
     * @Given a user has MFA activated and a valid MFA token
     * @When the user successfully logs in
     * @Then the MFA token should be passed in the response.
     */
    public function testJwtLoginControllerTest_Success_With_MFA_Cookie_Remember_Valid()
    {
        $user = UserFactory::make()
            ->user()
            ->with('Gpgkeys', GpgkeyFactory::make()->validFingerprint())
            ->persist();

        $this->loadFixtureScenario(MfaTotpScenario::class, $user);
        $cookie = $this->mockMfaCookieValid($this->makeUac($user), MfaSettings::PROVIDER_TOTP, true);

        $this->postJson('/auth/jwt/login.json', [
            'user_id' => $user->id,
            'challenge' => $this->makeChallenge($user, UuidFactory::uuid()),
        ]);

        $this->assertResponseOk('The authentication was a success.');
        $challenge = json_decode($this->decryptChallenge($user, $this->_responseJsonBody->challenge), true);
        $this->assertFalse(isset($challenge['providers']));
        $this->assertCookie($cookie, MfaVerifiedCookie::MFA_COOKIE_ALIAS);
    }
}

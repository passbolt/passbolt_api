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
 * @since         5.17.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Controllers\Webauthn;

use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\Role;
use App\Model\Entity\User;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Passbolt\JwtAuthentication\Service\AccessToken\JwtTokenCreateService;
use Passbolt\MultiFactorAuthentication\Service\MfaPolicies\RememberAMonthSettingInterface;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\Delegated\MfaWebauthnDelegatedStateService;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\Delegated\MfaWebauthnDelegatedTokenService;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Webauthn\MfaWebauthnScenario;

/**
 * @covers \Passbolt\MultiFactorAuthentication\Controller\Webauthn\WebauthnDelegatedCallbackPostController
 */
class WebauthnDelegatedCallbackPostControllerTest extends MfaIntegrationTestCase
{
    public function testWebauthnDelegatedCallbackPostController_Success_IssuesMfaCookie(): void
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnScenario::class, $user);
        $ceremony = $this->completeCeremony($user);

        $this->postJson('/mfa/verify/webauthn/delegated/callback.json', [
            'mfa_delegated_token' => $ceremony['token'],
            'mfa_delegated_secret' => $ceremony['secret'],
        ]);

        $this->assertResponseOk();
        $this->assertCookieIsSet('passbolt_mfa');
        /** @var \Cake\Http\Response $response */
        $response = $this->_response;
        $cookieValue = $response->getCookieCollection()->get('passbolt_mfa')->getValue();
        /** @var \App\Model\Entity\AuthenticationToken $mfaToken */
        $mfaToken = AuthenticationTokenFactory::find()
            ->where(['token' => $cookieValue, 'user_id' => $user->id, 'type' => AuthenticationToken::TYPE_MFA])
            ->firstOrFail();
        $this->assertTrue($mfaToken->active);
        /** @var \App\Model\Entity\AuthenticationToken $secretToken */
        $secretToken = AuthenticationTokenFactory::find()
            ->where(['user_id' => $user->id, 'type' => AuthenticationToken::TYPE_MFA_DELEGATED_TOKEN])
            ->firstOrFail();
        $this->assertFalse($secretToken->active);
    }

    public function testWebauthnDelegatedCallbackPostController_Success_DelegatedTokenIsConsumed(): void
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnScenario::class, $user);
        $ceremony = $this->completeCeremony($user);
        $body = ['mfa_delegated_token' => $ceremony['token'], 'mfa_delegated_secret' => $ceremony['secret']];

        $this->postJson('/mfa/verify/webauthn/delegated/callback.json', $body);
        $this->assertResponseOk();

        $this->postJson('/mfa/verify/webauthn/delegated/callback.json', $body);
        $this->assertBadRequestError('The delegated token is not valid.');
    }

    public function testWebauthnDelegatedCallbackPostController_Success_WithRememberMe(): void
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnScenario::class, $user);
        $ceremony = $this->completeCeremony($user);
        $policy = $this->createMock(RememberAMonthSettingInterface::class);
        $policy->method('isEnabled')->willReturn(true);
        $this->mockService(RememberAMonthSettingInterface::class, fn() => $policy);

        $this->postJson('/mfa/verify/webauthn/delegated/callback.json', [
            'mfa_delegated_token' => $ceremony['token'],
            'mfa_delegated_secret' => $ceremony['secret'],
            'remember' => true,
        ]);

        $this->assertResponseOk();
        /** @var \Cake\Http\Response $response */
        $response = $this->_response;
        $expiry = $response->getCookieCollection()->get('passbolt_mfa')->getExpiry();
        $this->assertTrue($expiry !== null);
    }

    public function testWebauthnDelegatedCallbackPostController_Error_SubDoesNotMatchAuthenticatedUser(): void
    {
        /** @var \App\Model\Entity\User $owner */
        $owner = UserFactory::make()->user()->persist();
        $ceremony = $this->completeCeremony($owner);
        $caller = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnScenario::class, $caller);

        $this->postJson('/mfa/verify/webauthn/delegated/callback.json', [
            'mfa_delegated_token' => $ceremony['token'],
            'mfa_delegated_secret' => $ceremony['secret'],
        ]);

        $this->assertBadRequestError('The delegated token is not valid.');
        $this->assertCookieNotSet('passbolt_mfa');
    }

    public function testWebauthnDelegatedCallbackPostController_Error_SecretOfAnotherCeremony(): void
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnScenario::class, $user);
        $mine = $this->completeCeremony($user);
        $other = $this->completeCeremony($user);

        $this->postJson('/mfa/verify/webauthn/delegated/callback.json', [
            'mfa_delegated_token' => $mine['token'],
            'mfa_delegated_secret' => $other['secret'],
        ]);

        $this->assertBadRequestError('The delegated token is not valid.');
        $this->assertCookieNotSet('passbolt_mfa');
    }

    public function testWebauthnDelegatedCallbackPostController_Error_AccessTokenPresentedAsDelegatedToken(): void
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnScenario::class, $user);

        $this->postJson('/mfa/verify/webauthn/delegated/callback.json', [
            'mfa_delegated_token' => (new JwtTokenCreateService())->createToken($user->id, '5 minutes'),
            'mfa_delegated_secret' => UuidFactory::uuid(),
        ]);

        $this->assertBadRequestError('The delegated token is not valid.');
        $this->assertCookieNotSet('passbolt_mfa');
    }

    public function testWebauthnDelegatedCallbackPostController_Error_MissingSecret(): void
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnScenario::class, $user);
        $ceremony = $this->completeCeremony($user);

        $this->postJson('/mfa/verify/webauthn/delegated/callback.json', ['mfa_delegated_token' => $ceremony['token']]);

        $this->assertBadRequestError('The delegated secret is not valid.');
        $this->assertCookieNotSet('passbolt_mfa');
    }

    public function testWebauthnDelegatedCallbackPostController_Error_NotAuthenticated(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $ceremony = $this->completeCeremony($user);

        $this->postJson('/mfa/verify/webauthn/delegated/callback.json', [
            'mfa_delegated_token' => $ceremony['token'],
            'mfa_delegated_secret' => $ceremony['secret'],
        ]);

        $this->assertResponseError('You need to login to access this location.');
    }

    // ---------------------------
    // Helper methods
    // ---------------------------

    /**
     * @param \App\Model\Entity\User $user Owner of the ceremony.
     * @return array The delegated token and the delegated secret.
     */
    private function completeCeremony(User $user): array
    {
        $stateService = new MfaWebauthnDelegatedStateService();
        $ceremony = $stateService->create(new UserAccessControl(Role::USER, $user->id, $user->username));
        $delegatedToken = (new MfaWebauthnDelegatedTokenService($stateService))->create($ceremony->state);

        return ['token' => $delegatedToken, 'secret' => $ceremony->secret];
    }
}

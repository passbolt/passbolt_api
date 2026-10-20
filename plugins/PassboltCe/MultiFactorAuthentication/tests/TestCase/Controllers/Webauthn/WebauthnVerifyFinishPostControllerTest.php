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
use App\Utility\UserAccessControl;
use Passbolt\MultiFactorAuthentication\Error\Exception\Webauthn\WebauthnAssertionInvalidException;
use Passbolt\MultiFactorAuthentication\Error\Exception\Webauthn\WebauthnCloneDetectedException;
use Passbolt\MultiFactorAuthentication\Error\Exception\Webauthn\WebauthnCredentialNotRecognisedException;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnChallengeTokenService;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnVerifyAssertionService;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Lib\Utility\WebauthnCredentialTestUtility;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Totp\MfaTotpScenario;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Webauthn\MfaWebauthnOrganizationOnlyScenario;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Webauthn\MfaWebauthnScenario;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class WebauthnVerifyFinishPostControllerTest extends MfaIntegrationTestCase
{
    private function createVerifyHandle(User $user): string
    {
        return (new MfaWebauthnChallengeTokenService())->create(
            new UserAccessControl(Role::USER, $user->id, $user->username),
            AuthenticationToken::TYPE_MFA_VERIFY,
            '{"challenge":"abc"}',
        )->token;
    }

    public function testWebauthnVerifyFinishPostController_NotAuthenticated(): void
    {
        $this->postJson('/mfa/verify/webauthn/finish.json?api-version=v2', []);
        $this->assertResponseError('You need to login to access this location.');
    }

    public function testWebauthnVerifyFinishPostController_NotJson(): void
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnScenario::class, $user);

        $this->post('/mfa/verify/webauthn/finish', []);
        $this->assertResponseError();
        $this->assertResponseContains('This functionality is only available using AJAX/JSON.');
    }

    public function testWebauthnVerifyFinishPostController_ProviderNotConfiguredForUser(): void
    {
        $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnOrganizationOnlyScenario::class);

        $this->postJson('/mfa/verify/webauthn/finish.json?api-version=v2', [
            'handle' => 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa',
            'credential' => [],
        ]);
        $this->assertBadRequestError('No valid multi-factor authentication settings found.');
    }

    public function testWebauthnVerifyFinishPostController_ProviderNotEnabledForUser_HasOtherProviders(): void
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaTotpScenario::class, $user);

        $this->postJson('/mfa/verify/webauthn/finish.json?api-version=v2', [
            'handle' => 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa',
            'credential' => [],
        ]);
        $this->assertBadRequestError('No valid multi-factor authentication settings found for this provider.');
    }

    public function testWebauthnVerifyFinishPostController_AlreadyVerified_ReturnsBadRequest(): void
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnScenario::class, $user);
        $this->mockMfaCookieValid($this->makeUac($user), MfaSettings::PROVIDER_WEBAUTHN);

        $this->postJson('/mfa/verify/webauthn/finish.json?api-version=v2', [
            'handle' => 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa',
            'credential' => [],
        ]);
        $this->assertBadRequestError('The multi-factor authentication is not required.');
    }

    public function testWebauthnVerifyFinishPostController_MalformedCredentialJson_ReturnsBadRequest(): void
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnScenario::class, $user);
        $handle = $this->createVerifyHandle($user);

        $this->postJson('/mfa/verify/webauthn/finish.json?api-version=v2', [
            'handle' => $handle,
            'credential' => [],
        ]);
        $this->assertBadRequestError('The WebAuthn verify payload is not valid.');
    }

    public function testWebauthnVerifyFinishPostController_HandleReuse_ReturnsBadRequest(): void
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnScenario::class, $user);
        $handle = $this->createVerifyHandle($user);

        $this->postJson('/mfa/verify/webauthn/finish.json?api-version=v2', [
            'handle' => $handle, 'credential' => WebauthnCredentialTestUtility::getDummyPayload(),
        ]);
        $this->assertBadRequestError('The credential payload is not a valid WebAuthn response.');
        $this->clearPlugins();
        $this->getTableLocator()->clear();

        $this->postJson('/mfa/verify/webauthn/finish.json?api-version=v2', [
            'handle' => $handle, 'credential' => WebauthnCredentialTestUtility::getDummyPayload(),
        ]);
        $this->assertBadRequestError('The authentication token is not valid.');
    }

    public function testWebauthnVerifyFinishPostController_Success_IssuesMfaCookie(): void
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnScenario::class, $user);
        $handle = $this->createVerifyHandle($user);

        $verifiedMock = $this->createMock(MfaWebauthnVerifyAssertionService::class);
        $verifiedMock->method('verify');
        $this->mockService(MfaWebauthnVerifyAssertionService::class, fn() => $verifiedMock);

        $this->postJson('/mfa/verify/webauthn/finish.json?api-version=v2', [
            'handle' => $handle,
            'credential' => WebauthnCredentialTestUtility::getDummyPayload(),
        ]);
        $this->assertResponseOk();
        $this->assertCookieIsSet('passbolt_mfa');

        /** @var \Cake\Http\Response $response */
        $response = $this->_response;
        $cookieValue = $response->getCookieCollection()->get('passbolt_mfa')->getValue();
        $mfaToken = AuthenticationTokenFactory::find()
            ->where(['token' => $cookieValue, 'user_id' => $user->id, 'type' => AuthenticationToken::TYPE_MFA])
            ->firstOrFail();
        $this->assertTrue($mfaToken->get('active'));
    }

    public function testWebauthnVerifyFinishPostController_Error_AssertionInvalid_LogsOutAndSetsSessionEmpty(): void
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnScenario::class, $user);
        $handle = $this->createVerifyHandle($user);
        $failing = $this->createMock(MfaWebauthnVerifyAssertionService::class);
        $failing->method('verify')->willThrowException(new WebauthnAssertionInvalidException('signature invalid'));
        $this->mockService(MfaWebauthnVerifyAssertionService::class, fn() => $failing);

        $this->postJson('/mfa/verify/webauthn/finish.json?api-version=v2', [
            'handle' => $handle,
            'credential' => WebauthnCredentialTestUtility::getDummyPayload(),
        ]);
        $this->assertResponseCode(401);
        $this->assertSessionNotHasKey('Auth');
    }

    public function testWebauthnVerifyFinishPostController_CloneDetected_LogsOutAndSetsSessionEmpty(): void
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnScenario::class, $user);
        $handle = $this->createVerifyHandle($user);
        $failing = $this->createMock(MfaWebauthnVerifyAssertionService::class);
        $failing->method('verify')->willThrowException(new WebauthnCloneDetectedException('clone detected'));
        $this->mockService(MfaWebauthnVerifyAssertionService::class, fn() => $failing);

        $this->postJson('/mfa/verify/webauthn/finish.json?api-version=v2', [
            'handle' => $handle,
            'credential' => WebauthnCredentialTestUtility::getDummyPayload(),
        ]);
        $this->assertResponseCode(401);
        $this->assertSessionNotHasKey('Auth');
    }

    public function testWebauthnVerifyFinishPostController_CredentialNotRecognised_DoesNotLogOut(): void
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnScenario::class, $user);
        $handle = $this->createVerifyHandle($user);
        $failing = $this->createMock(MfaWebauthnVerifyAssertionService::class);
        $failing->method('verify')
            ->willThrowException(new WebauthnCredentialNotRecognisedException('credential not recognised'));
        $this->mockService(MfaWebauthnVerifyAssertionService::class, fn() => $failing);

        $this->postJson('/mfa/verify/webauthn/finish.json?api-version=v2', [
            'handle' => $handle,
            'credential' => WebauthnCredentialTestUtility::getDummyPayload(),
        ]);
        $this->assertResponseCode(401);
        $this->assertSessionHasKey('Auth');
    }

    public function testWebauthnVerifyFinishPostController_CredentialNotOfferedByCeremony_DoesNotLogOut(): void
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnScenario::class, $user);
        $handle = $this->createVerifyHandle($user);
        $failing = $this->createMock(MfaWebauthnVerifyAssertionService::class);
        $failing->method('verify')->willThrowException(
            new WebauthnCredentialNotRecognisedException('The WebAuthn credential is not allowed for this ceremony.'),
        );
        $this->mockService(MfaWebauthnVerifyAssertionService::class, fn() => $failing);

        $this->postJson('/mfa/verify/webauthn/finish.json?api-version=v2', [
            'handle' => $handle,
            'credential' => WebauthnCredentialTestUtility::getDummyPayload(),
        ]);
        $this->assertResponseCode(401);
        $this->assertSessionHasKey('Auth');
    }
}

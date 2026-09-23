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
use App\Test\Factory\WebauthnCredentialFactory;
use App\Utility\UserAccessControl;
use Passbolt\MultiFactorAuthentication\Service\MfaPolicies\DefaultMfaWebauthnPolicyService;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnChallengeTokenService;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnEnableService;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnVerifyAttestationService;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Lib\Utility\WebauthnCredentialTestUtility;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Webauthn\MfaWebauthnOrganizationOnlyScenario;

class WebauthnSetupFinishPostControllerTest extends MfaIntegrationTestCase
{
    private function createSetupHandle(User $user): string
    {
        return (new MfaWebauthnChallengeTokenService())->create(
            new UserAccessControl(Role::USER, $user->id, $user->username),
            AuthenticationToken::TYPE_MFA_SETUP,
            '{"challenge":"abc"}',
        )->token;
    }

    public function testWebauthnSetupFinishPostController_NotAuthenticated(): void
    {
        $this->postJson('/mfa/setup/webauthn/finish.json?api-version=v2', []);
        $this->assertResponseError('You need to login to access this location.');
    }

    public function testWebauthnSetupFinishPostController_NotJson(): void
    {
        $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnOrganizationOnlyScenario::class);

        $this->post('/mfa/setup/webauthn/finish', []);
        $this->assertResponseError();
        $this->assertResponseContains('This functionality is only available using AJAX/JSON.');
    }

    public function testWebauthnSetupFinishPostController_DisabledUser(): void
    {
        $user = UserFactory::make()->user()->disabled()->persist();
        $this->logInAs($user);
        $this->loadFixtureScenario(MfaWebauthnOrganizationOnlyScenario::class);

        $this->postJson('/mfa/setup/webauthn/finish.json?api-version=v2', [
            'handle' => 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa',
            'credential' => WebauthnCredentialTestUtility::getDummyPayload(),
        ]);
        $this->assertAuthenticationError();
    }

    public function testWebauthnSetupFinishPostController_CsrfToken(): void
    {
        $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnOrganizationOnlyScenario::class);
        $this->disableCsrfToken();

        $this->postJson('/mfa/setup/webauthn/finish.json?api-version=v2', [
            'handle' => 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa',
            'credential' => WebauthnCredentialTestUtility::getDummyPayload(),
        ]);
        $this->assertForbiddenError('Missing or incorrect CSRF cookie type.');
    }

    public function testWebauthnSetupFinishPostController_CrossUserHandleRejected(): void
    {
        /** @var \App\Model\Entity\User $owner */
        $owner = UserFactory::make()->user()->persist();
        $intruder = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnOrganizationOnlyScenario::class);
        $handle = $this->createSetupHandle($owner);

        $this->postJson('/mfa/setup/webauthn/finish.json?api-version=v2', [
            'handle' => $handle,
            'credential' => WebauthnCredentialTestUtility::getDummyPayload(),
            'name' => 'Test key',
        ]);
        $this->assertNotFoundError('The authentication token could not be found.');
        $this->assertNotSame($intruder->id, $owner->id);
    }

    public function testWebauthnSetupFinishPostController_OrgProviderDisabled(): void
    {
        $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnOrganizationOnlyScenario::class, false);

        $this->postJson('/mfa/setup/webauthn/finish.json?api-version=v2', [
            'handle' => 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa',
            'credential' => [],
        ]);
        $this->assertResponseError();
        $this->assertResponseContains('not enabled for your organization');
    }

    public function testWebauthnSetupFinishPostController_MalformedCredentialJson_ReturnsBadRequest(): void
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnOrganizationOnlyScenario::class);
        $handle = $this->createSetupHandle($user);

        $this->postJson('/mfa/setup/webauthn/finish.json?api-version=v2', [
            'handle' => $handle,
            'credential' => [],
        ]);
        $this->assertBadRequestError('The WebAuthn setup payload is not valid.');
    }

    public function testWebauthnSetupFinishPostController_HandleReuse_ReturnsBadRequest(): void
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnOrganizationOnlyScenario::class);
        $handle = $this->createSetupHandle($user);

        $this->postJson('/mfa/setup/webauthn/finish.json?api-version=v2', [
            'handle' => $handle, 'credential' => WebauthnCredentialTestUtility::getDummyPayload(), 'name' => 'Test key',
        ]);
        $this->assertBadRequestError('The credential payload is not a valid WebAuthn response.');
        $this->clearPlugins();
        $this->getTableLocator()->clear();

        $this->postJson('/mfa/setup/webauthn/finish.json?api-version=v2', [
            'handle' => $handle, 'credential' => WebauthnCredentialTestUtility::getDummyPayload(), 'name' => 'Test key',
        ]);
        $this->assertBadRequestError('The authentication token is not valid.');
    }

    public function testWebauthnSetupFinishPostController_Success_IssuesMfaCookie(): void
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaWebauthnOrganizationOnlyScenario::class);
        $handle = $this->createSetupHandle($user);

        $verifiedMock = $this->createMock(MfaWebauthnVerifyAttestationService::class);
        $verifiedMock->method('verify')->willReturnCallback(
            fn(UserAccessControl $uac, string $h, array $c, ?string $name) => (new MfaWebauthnEnableService(new DefaultMfaWebauthnPolicyService()))
                ->enable($uac, WebauthnCredentialFactory::makeCredentialRecord('happy-cred'), $name),
        );
        $this->mockService(MfaWebauthnVerifyAttestationService::class, fn() => $verifiedMock);

        $this->postJson('/mfa/setup/webauthn/finish.json?api-version=v2', [
            'handle' => $handle,
            'credential' => WebauthnCredentialTestUtility::getDummyPayload(),
            'name' => 'Test key',
        ]);
        $this->assertResponseOk();
        $this->assertCookieIsSet('passbolt_mfa');
        $body = $this->getResponseBodyAsArray();
        $this->assertNotFalse($body['verified']);

        /** @var \Cake\Http\Response $response */
        $response = $this->_response;
        $cookieValue = $response->getCookieCollection()->get('passbolt_mfa')->getValue();
        $mfaToken = AuthenticationTokenFactory::find()
            ->where(['token' => $cookieValue, 'user_id' => $user->id, 'type' => AuthenticationToken::TYPE_MFA])
            ->firstOrFail();
        $this->assertTrue($mfaToken->get('active'));
    }
}

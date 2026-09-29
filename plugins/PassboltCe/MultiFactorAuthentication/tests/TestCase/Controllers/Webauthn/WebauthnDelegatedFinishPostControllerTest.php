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
use App\Model\Entity\User;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Passbolt\JwtAuthentication\Service\AccessToken\JwksGetService;
use Passbolt\JwtAuthentication\Service\AccessToken\JwtTokenCreateService;
use Passbolt\MultiFactorAuthentication\Error\Exception\Webauthn\WebauthnAssertionInvalidException;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\Delegated\MfaWebauthnDelegatedStateService;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnVerifyAssertionService;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Lib\Utility\WebauthnCredentialTestUtility;

/**
 * @covers \Passbolt\MultiFactorAuthentication\Controller\Webauthn\WebauthnDelegatedFinishPostController
 */
class WebauthnDelegatedFinishPostControllerTest extends MfaIntegrationTestCase
{
    public function testWebauthnDelegatedFinishPostController_Success_ReturnsDelegatedToken(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $state = $this->persistState($user);
        $this->mockAssertionAccepted();

        $this->postJson('/mfa/verify/webauthn/delegated/finish.json', $this->validPayload($state));

        $this->assertResponseOk();
        $claims = $this->claimsOf($this->getResponseBodyAsArray()['mfa_delegated_token']);
        $this->assertSame($user->id, $claims->sub);
        $this->assertSame($state, $claims->cid);
        // cookie is set
        $this->assertCookieNotSet('passbolt_mfa');
        // assert DB values
        /** @var \App\Model\Entity\AuthenticationToken $row */
        $row = AuthenticationTokenFactory::find()->where(['token' => $state])->firstOrFail();
        $this->assertFalse($row->active);
    }

    public function testWebauthnDelegatedFinishPostController_Error_UserIdInRequestBodyIsIgnored(): void
    {
        /** @var \App\Model\Entity\User $owner */
        $owner = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\User $other */
        $other = UserFactory::make()->user()->persist();
        $state = $this->persistState($owner);
        $this->mockAssertionAccepted();

        $this->postJson(
            '/mfa/verify/webauthn/delegated/finish.json',
            $this->validPayload($state) + ['user_id' => $other->id, 'sub' => $other->id],
        );

        $this->assertResponseOk();
        $this->assertSame(
            $owner->id,
            $this->claimsOf($this->getResponseBodyAsArray()['mfa_delegated_token'])->sub,
        );
    }

    public function testWebauthnDelegatedFinishPostController_Error_MissingState(): void
    {
        $this->postJson('/mfa/verify/webauthn/delegated/finish.json', [
            'handle' => UuidFactory::uuid(),
            'credential' => WebauthnCredentialTestUtility::getDummyPayload(),
        ]);

        $this->assertBadRequestError('A delegated state is required.');
    }

    public function testWebauthnDelegatedFinishPostController_Error_UnknownState(): void
    {
        $this->postJson('/mfa/verify/webauthn/delegated/finish.json', $this->validPayload(UuidFactory::uuid()));

        $this->assertBadRequestError('The delegated state is not valid.');
    }

    public function testWebauthnDelegatedFinishPostController_Error_MissingCredential(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $state = $this->persistState($user);

        $this->postJson('/mfa/verify/webauthn/delegated/finish.json', ['mfa_delegated_state' => $state, 'handle' => UuidFactory::uuid()]);

        $this->assertBadRequestError('The WebAuthn verify payload is not valid.');
    }

    public function testWebauthnDelegatedFinishPostController_Error_AssertionInvalid(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $state = $this->persistState($user);
        $failing = $this->createMock(MfaWebauthnVerifyAssertionService::class);
        $failing->method('verify')->willThrowException(new WebauthnAssertionInvalidException('signature invalid'));
        $this->mockService(MfaWebauthnVerifyAssertionService::class, fn() => $failing);

        $this->postJson('/mfa/verify/webauthn/delegated/finish.json', $this->validPayload($state));

        $this->assertResponseError();
        /** @var \App\Model\Entity\AuthenticationToken $row */
        $row = AuthenticationTokenFactory::find()->where(['token' => $state])->firstOrFail();
        $this->assertTrue($row->active);
    }

    // ---------------------------
    // Helper methods
    // ---------------------------

    /**
     * @param \App\Model\Entity\User $user Owner of the ceremony.
     * @param array $properties Properties to override.
     * @return string The delegated state value.
     */
    private function persistState(User $user, array $properties = []): string
    {
        /** @var \App\Model\Entity\AuthenticationToken $row */
        $row = AuthenticationTokenFactory::make($properties + [
                'user_id' => $user->id,
                'token' => UuidFactory::uuid(),
                'type' => AuthenticationToken::TYPE_MFA_DELEGATED_STATE,
                'active' => true,
                'data' => json_encode([MfaWebauthnDelegatedStateService::SECRET_KEY => UuidFactory::uuid()]),
            ])->persist();

        return $row->token;
    }

    /**
     * @return void
     */
    private function mockAssertionAccepted(): void
    {
        $service = $this->createMock(MfaWebauthnVerifyAssertionService::class);
        $service->method('verify');
        $this->mockService(MfaWebauthnVerifyAssertionService::class, fn() => $service);
    }

    /**
     * @param string $jwt Delegated token.
     * @return object Verified claims.
     */
    private function claimsOf(string $jwt): object
    {
        return JWT::decode($jwt, new Key((new JwksGetService())->getRawPublicKey(), JwtTokenCreateService::JWT_ALG));
    }

    /**
     * @return array Payload accepted by the verify form.
     */
    private function validPayload(string $state): array
    {
        return [
            'mfa_delegated_state' => $state,
            'handle' => UuidFactory::uuid(),
            'credential' => WebauthnCredentialTestUtility::getDummyPayload(),
        ];
    }
}

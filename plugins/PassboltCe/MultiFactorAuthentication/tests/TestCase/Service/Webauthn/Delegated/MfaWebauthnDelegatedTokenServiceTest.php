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
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Service\Webauthn\Delegated;

use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\User;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\Http\Exception\BadRequestException;
use Cake\Routing\Router;
use Cake\Validation\Validation;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Passbolt\JwtAuthentication\Service\AccessToken\JwksGetService;
use Passbolt\JwtAuthentication\Service\AccessToken\JwtEncodeService;
use Passbolt\JwtAuthentication\Service\AccessToken\JwtTokenCreateService;
use Passbolt\MultiFactorAuthentication\Model\Dto\MfaWebauthnDelegatedStateDto;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\Delegated\MfaWebauthnDelegatedStateService;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\Delegated\MfaWebauthnDelegatedTokenService;

/**
 * @covers \Passbolt\MultiFactorAuthentication\Service\Webauthn\Delegated\MfaWebauthnDelegatedTokenService
 */
class MfaWebauthnDelegatedTokenServiceTest extends AppTestCase
{
    private MfaWebauthnDelegatedTokenService $service;
    private MfaWebauthnDelegatedStateService $stateService;

    public function setUp(): void
    {
        parent::setUp();
        $this->stateService = new MfaWebauthnDelegatedStateService();
        $this->service = new MfaWebauthnDelegatedTokenService($this->stateService);
    }

    public function tearDown(): void
    {
        unset($this->service, $this->stateService);
        parent::tearDown();
    }

    public function testMfaWebauthnDelegatedTokenService_Create_Success(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $dto = $this->buildDelegatedStateDto($user);

        $jwt = $this->service->create($dto->state);

        $claims = $this->claimsOf($jwt);
        $this->assertSame(Router::url('/', true), $claims->iss);
        $this->assertSame(MfaWebauthnDelegatedTokenService::AUDIENCE, $claims->aud);
        $this->assertSame($user->id, $claims->sub);
        $this->assertSame($dto->state, $claims->cid);
        $this->assertTrue(Validation::uuid($claims->jti));
        $this->assertObjectNotHasAttribute('secret', $claims);
        // Assert database entry
        /** @var \App\Model\Entity\AuthenticationToken $delegatedAuthToken */
        $delegatedAuthToken = AuthenticationTokenFactory::firstOrFail([
            'token' => $claims->jti,
            'type' => AuthenticationToken::TYPE_MFA_DELEGATED_TOKEN,
        ]);
        $this->assertSame($user->id, $delegatedAuthToken->user_id);
        $this->assertTrue($delegatedAuthToken->active);
        $this->assertSame(
            $dto->secret,
            $delegatedAuthToken->getDataValue(MfaWebauthnDelegatedStateService::SECRET_KEY),
        );
        // Assert state is consumed
        /** @var \App\Model\Entity\AuthenticationToken $stateAuthToken */
        $stateAuthToken = AuthenticationTokenFactory::firstOrFail(['token' => $dto->state]);
        $this->assertFalse($stateAuthToken->active);
    }

    public function testMfaWebauthnDelegatedTokenService_Create_Error_RefusesToSignTheSameStateTwice(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $ceremony = $this->buildDelegatedStateDto($user);
        $this->service->create($ceremony->state);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The delegated state is not valid.');
        $this->service->create($ceremony->state);
    }

    public function testMfaWebauthnDelegatedTokenService_Consume_Success_ConsumesTheDelegatedTokenRow(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $ceremony = $this->buildDelegatedStateDto($user);
        $jwt = $this->service->create($ceremony->state);

        $this->service->consume($this->makeUac($user), $jwt, $ceremony->secret);

        /** @var \App\Model\Entity\AuthenticationToken $row */
        $row = AuthenticationTokenFactory::firstOrFail(['token' => $this->claimsOf($jwt)->jti]);
        $this->assertFalse($row->active);
    }

    public function testMfaWebauthnDelegatedTokenService_Consume_Error_RejectsReplay(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $ceremony = $this->buildDelegatedStateDto($user);
        $jwt = $this->service->create($ceremony->state);
        $this->service->consume($this->makeUac($user), $jwt, $ceremony->secret);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The delegated token is not valid.');
        $this->service->consume($this->makeUac($user), $jwt, $ceremony->secret);
    }

    public function testMfaWebauthnDelegatedTokenService_Consume_Error_RejectsSubOfAnotherUser(): void
    {
        /** @var \App\Model\Entity\User $owner */
        $owner = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\User $attacker */
        $attacker = UserFactory::make()->user()->persist();
        $ceremony = $this->buildDelegatedStateDto($owner);
        $jwt = $this->service->create($ceremony->state);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The delegated token is not valid.');
        $this->service->consume($this->makeUac($attacker), $jwt, $ceremony->secret);
    }

    public function testMfaWebauthnDelegatedTokenService_Consume_Error_RejectsSecretOfAnotherCeremony(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $mine = $this->buildDelegatedStateDto($user);
        $other = $this->buildDelegatedStateDto($user);
        $jwt = $this->service->create($mine->state);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The delegated token is not valid.');
        $this->service->consume($this->makeUac($user), $jwt, $other->secret);
    }

    public function testMfaWebauthnDelegatedTokenService_Consume_Error_RejectsAccessTokenAsDelegatedToken(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $accessToken = (new JwtTokenCreateService())->createToken($user->id, '5 minutes');

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The delegated token is not valid.');
        $this->service->consume($this->makeUac($user), $accessToken, UuidFactory::uuid());
    }

    public function testMfaWebauthnDelegatedTokenService_Consume_Error_RejectsSomethingThatIsNotAToken(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The delegated token is not valid.');
        $this->service->consume($this->makeUac($user), 'not-a-jwt', UuidFactory::uuid());
    }

    public function testMfaWebauthnDelegatedTokenService_Consume_Error_RejectsTamperedSignature(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $ceremony = $this->buildDelegatedStateDto($user);
        $jwt = $this->service->create($ceremony->state);
        // Replace the last two characters of the signature
        $tampered = substr($jwt, 0, -2) . 'xy';

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The delegated token is not valid.');
        $this->service->consume($this->makeUac($user), $tampered, $ceremony->secret);
    }

    public function testMfaWebauthnDelegatedTokenService_Consume_Error_RejectsForeignIssuer(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $jwt = $this->signToken([
            'iss' => 'https://attacker.test/',
            'aud' => MfaWebauthnDelegatedTokenService::AUDIENCE,
            'sub' => $user->id,
            'jti' => UuidFactory::uuid(),
            'exp' => time() + 60,
        ]);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The delegated token is not valid.');
        $this->service->consume($this->makeUac($user), $jwt, UuidFactory::uuid());
    }

    public function testMfaWebauthnDelegatedTokenService_Consume_Error_RejectsForeignAudience(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $jwt = $this->signToken([
            'iss' => Router::url('/', true),
            'aud' => 'some-other-service',
            'sub' => $user->id,
            'jti' => UuidFactory::uuid(),
            'exp' => time() + 60,
        ]);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The delegated token is not valid.');
        $this->service->consume($this->makeUac($user), $jwt, UuidFactory::uuid());
    }

    public function testMfaWebauthnDelegatedTokenService_Consume_Error_RejectsExpiredToken(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $jwt = $this->signToken([
            'iss' => Router::url('/', true),
            'aud' => MfaWebauthnDelegatedTokenService::AUDIENCE,
            'sub' => $user->id,
            'jti' => UuidFactory::uuid(),
            'exp' => time() - 1,
        ]);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The delegated token is not valid.');
        $this->service->consume($this->makeUac($user), $jwt, UuidFactory::uuid());
    }

    public function testMfaWebauthnDelegatedTokenService_Consume_Error_RejectsJtiThatIsNotAUuid(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $jwt = $this->signToken([
            'iss' => Router::url('/', true),
            'aud' => MfaWebauthnDelegatedTokenService::AUDIENCE,
            'sub' => $user->id,
            'jti' => 'not-a-uuid',
            'exp' => time() + 60,
        ]);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The delegated token is not valid.');
        $this->service->consume($this->makeUac($user), $jwt, UuidFactory::uuid());
    }

    public function testMfaWebauthnDelegatedTokenService_Consume_Error_RejectsJtiOfNoRow(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $jwt = $this->signToken([
            'iss' => Router::url('/', true),
            'aud' => MfaWebauthnDelegatedTokenService::AUDIENCE,
            'sub' => $user->id,
            'jti' => UuidFactory::uuid(),
            'exp' => time() + 60,
        ]);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The delegated token is not valid.');
        $this->service->consume($this->makeUac($user), $jwt, UuidFactory::uuid());
    }

    // ---------------------------
    // Helper methods
    // ---------------------------

    private function buildDelegatedStateDto(User $user): MfaWebauthnDelegatedStateDto
    {
        return $this->stateService->create($this->makeUac($user));
    }

    private function claimsOf(string $jwt): object
    {
        return JWT::decode($jwt, new Key((new JwksGetService())->getRawPublicKey(), JwtTokenCreateService::JWT_ALG));
    }

    /**
     * @param array $claims Claims to sign.
     * @return string
     */
    private function signToken(array $claims): string
    {
        return (new JwtEncodeService())->encode($claims);
    }
}

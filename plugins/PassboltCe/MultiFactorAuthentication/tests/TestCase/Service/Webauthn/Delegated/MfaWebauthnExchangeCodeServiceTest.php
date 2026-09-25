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

use App\Model\Entity\Role;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Http\Exception\BadRequestException;
use Cake\I18n\DateTime;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Passbolt\JwtAuthentication\Service\AccessToken\JwksGetService;
use Passbolt\JwtAuthentication\Service\AccessToken\JwtEncodeService;
use Passbolt\JwtAuthentication\Service\AccessToken\JwtTokenCreateService;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\Delegated\MfaWebauthnDelegatedCeremonyService;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\Delegated\MfaWebauthnExchangeCodeService;
use Passbolt\MultiFactorAuthentication\Test\Lib\Utility\DelegatedCeremonyTestUtility;

/**
 * @covers \Passbolt\MultiFactorAuthentication\Service\Webauthn\Delegated\MfaWebauthnExchangeCodeService
 */
class MfaWebauthnExchangeCodeServiceTest extends TestCase
{
    use TruncateDirtyTables;

    private MfaWebauthnExchangeCodeService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new MfaWebauthnExchangeCodeService();
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    private function seedCeremony(): array
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);
        $state = DelegatedCeremonyTestUtility::validState();
        $ceremonyService = new MfaWebauthnDelegatedCeremonyService();
        $ceremonyToken = $ceremonyService->create($uac, $state);

        return [$uac, $ceremonyToken, $state];
    }

    private function encodeClaims(array $claims): string
    {
        return (new JwtEncodeService())->encode($claims);
    }

    public function testMfaWebauthnExchangeCodeService_Create_ReturnsJwtWithExpectedClaims(): void
    {
        [$uac, $ceremonyToken, $state] = $this->seedCeremony();

        $jwt = $this->service->create($uac, $ceremonyToken, $state);
        $claims = JWT::decode(
            $jwt,
            new Key((new JwksGetService())->getRawPublicKey(), JwtTokenCreateService::JWT_ALG),
        );

        $this->assertSame(Router::url('/', true), $claims->iss);
        $this->assertSame(MfaWebauthnExchangeCodeService::AUDIENCE, $claims->aud);
        $this->assertSame($uac->getId(), $claims->sub);
        $this->assertSame($ceremonyToken, $claims->cid);
        $this->assertSame($state, $claims->state);
        $this->assertIsString($claims->jti);
        $this->assertLessThanOrEqual(time() + MfaWebauthnExchangeCodeService::TTL_SECONDS, $claims->exp);
    }

    public function testMfaWebauthnExchangeCodeService_Consume_MarksCeremonyRowInactive(): void
    {
        [$uac, $ceremonyToken, $state] = $this->seedCeremony();
        $jwt = $this->service->create($uac, $ceremonyToken, $state);

        $this->service->consume($uac, $jwt);

        /** @var \App\Model\Entity\AuthenticationToken $row */
        $row = AuthenticationTokenFactory::firstOrFail(['token' => $ceremonyToken]);
        $this->assertFalse($row->active);
    }

    public function testMfaWebauthnExchangeCodeService_Consume_ExpiredCeremonyRowRejected(): void
    {
        [$uac, $ceremonyToken, $state] = $this->seedCeremony();
        $jwt = $this->service->create($uac, $ceremonyToken, $state);

        TableRegistry::getTableLocator()
            ->get('AuthenticationTokens')
            ->updateAll(
                ['created' => DateTime::now()->modify('-6 minutes')],
                ['token' => $ceremonyToken],
            );

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The exchange code is not valid.');
        $this->service->consume($uac, $jwt);
    }

    public function testMfaWebauthnExchangeCodeService_Consume_Twice_ReplayRejected(): void
    {
        [$uac, $ceremonyToken, $state] = $this->seedCeremony();
        $jwt = $this->service->create($uac, $ceremonyToken, $state);
        $this->service->consume($uac, $jwt);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The exchange code is not valid.');
        $this->service->consume($uac, $jwt);
    }

    public function testMfaWebauthnExchangeCodeService_Consume_GarbageStringRejected(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The exchange code is not valid.');
        $this->service->consume($uac, 'not-a-jwt-at-all');
    }

    public function testMfaWebauthnExchangeCodeService_Consume_TamperedSignatureRejected(): void
    {
        [$uac, $ceremonyToken, $state] = $this->seedCeremony();
        $jwt = $this->service->create($uac, $ceremonyToken, $state);
        $tampered = substr($jwt, 0, -4) . 'XXXX';

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The exchange code is not valid.');
        $this->service->consume($uac, $tampered);
    }

    public function testMfaWebauthnExchangeCodeService_Consume_WrongIssuerRejected(): void
    {
        [$uac, $ceremonyToken, $state] = $this->seedCeremony();
        $jwt = $this->encodeClaims([
            'iss' => 'https://malicious.example.com/',
            'aud' => MfaWebauthnExchangeCodeService::AUDIENCE,
            'sub' => $uac->getId(),
            'cid' => $ceremonyToken,
            'jti' => bin2hex(random_bytes(16)),
            'state' => $state,
            'iat' => time(),
            'exp' => time() + 60,
        ]);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The exchange code is not valid.');
        $this->service->consume($uac, $jwt);
    }

    public function testMfaWebauthnExchangeCodeService_Consume_WrongAudienceRejected(): void
    {
        [$uac, $ceremonyToken, $state] = $this->seedCeremony();
        $jwt = $this->encodeClaims([
            'iss' => Router::url('/', true),
            'aud' => 'passbolt-access-token',
            'sub' => $uac->getId(),
            'cid' => $ceremonyToken,
            'jti' => bin2hex(random_bytes(16)),
            'state' => $state,
            'iat' => time(),
            'exp' => time() + 60,
        ]);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The exchange code is not valid.');
        $this->service->consume($uac, $jwt);
    }

    public function testMfaWebauthnExchangeCodeService_Consume_SubjectMismatchRejected(): void
    {
        [, $ceremonyToken, $state] = $this->seedCeremony();
        /** @var \App\Model\Entity\User $intruder */
        $intruder = UserFactory::make()->user()->persist();
        $intruderUac = new UserAccessControl(Role::USER, $intruder->id, $intruder->username);
        $jwt = $this->encodeClaims([
            'iss' => Router::url('/', true),
            'aud' => MfaWebauthnExchangeCodeService::AUDIENCE,
            'sub' => UuidFactory::uuid(),
            'cid' => $ceremonyToken,
            'jti' => bin2hex(random_bytes(16)),
            'state' => $state,
            'iat' => time(),
            'exp' => time() + 60,
        ]);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The exchange code is not valid.');
        $this->service->consume($intruderUac, $jwt);
    }

    public function testMfaWebauthnExchangeCodeService_Consume_StateMismatchRejected(): void
    {
        [$uac, $ceremonyToken] = $this->seedCeremony();
        $jwt = $this->encodeClaims([
            'iss' => Router::url('/', true),
            'aud' => MfaWebauthnExchangeCodeService::AUDIENCE,
            'sub' => $uac->getId(),
            'cid' => $ceremonyToken,
            'jti' => bin2hex(random_bytes(16)),
            'state' => DelegatedCeremonyTestUtility::validState(),
            'iat' => time(),
            'exp' => time() + 60,
        ]);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The exchange code is not valid.');
        $this->service->consume($uac, $jwt);
    }

    public function testMfaWebauthnExchangeCodeService_Consume_ExpiredJwtRejected(): void
    {
        [$uac, $ceremonyToken, $state] = $this->seedCeremony();
        $jwt = $this->encodeClaims([
            'iss' => Router::url('/', true),
            'aud' => MfaWebauthnExchangeCodeService::AUDIENCE,
            'sub' => $uac->getId(),
            'cid' => $ceremonyToken,
            'jti' => bin2hex(random_bytes(16)),
            'state' => $state,
            'iat' => time() - 120,
            'exp' => time() - 60,
        ]);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The exchange code is not valid.');
        $this->service->consume($uac, $jwt);
    }

    public function testMfaWebauthnExchangeCodeService_Consume_UnknownCeremonyRejected(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);
        $jwt = $this->encodeClaims([
            'iss' => Router::url('/', true),
            'aud' => MfaWebauthnExchangeCodeService::AUDIENCE,
            'sub' => $uac->getId(),
            'cid' => UuidFactory::uuid(),
            'jti' => bin2hex(random_bytes(16)),
            'state' => DelegatedCeremonyTestUtility::validState(),
            'iat' => time(),
            'exp' => time() + 60,
        ]);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The exchange code is not valid.');
        $this->service->consume($uac, $jwt);
    }
}

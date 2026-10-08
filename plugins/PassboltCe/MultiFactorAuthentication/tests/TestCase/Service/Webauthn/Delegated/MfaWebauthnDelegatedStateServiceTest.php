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
use App\Model\Entity\Role;
use App\Model\Entity\User;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\Http\Exception\BadRequestException;
use Cake\I18n\DateTime;
use Cake\Validation\Validation;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\Delegated\MfaWebauthnDelegatedStateService;

/**
 * @covers \Passbolt\MultiFactorAuthentication\Service\Webauthn\Delegated\MfaWebauthnDelegatedStateService
 */
class MfaWebauthnDelegatedStateServiceTest extends AppTestCase
{
    private MfaWebauthnDelegatedStateService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new MfaWebauthnDelegatedStateService();
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    /**
     * @param array $properties Row properties.
     * @return \App\Model\Entity\AuthenticationToken
     */
    private function persistToken(array $properties): AuthenticationToken
    {
        /** @var \App\Model\Entity\AuthenticationToken $row */
        $row = AuthenticationTokenFactory::make($properties)->persist();

        return $row;
    }

    /**
     * Persists a state row of the given user, so the finder path can be exercised without create().
     *
     * @param \App\Model\Entity\User $user Owner of the ceremony.
     * @param array $properties Row properties to override, e.g. active or created.
     * @return \App\Model\Entity\AuthenticationToken
     */
    private function persistState(User $user, array $properties = []): AuthenticationToken
    {
        return $this->persistToken($properties + [
            'user_id' => $user->id,
            'token' => UuidFactory::uuid(),
            'type' => AuthenticationToken::TYPE_MFA_DELEGATED_STATE,
            'active' => true,
            'data' => json_encode([MfaWebauthnDelegatedStateService::SECRET_KEY => UuidFactory::uuid()]),
        ]);
    }

    public function testMfaWebauthnDelegatedStateService_Create_Success_MintsStateRowBoundToTheCaller(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();

        $ceremony = $this->service->create($this->makeUac($user));

        /** @var \App\Model\Entity\AuthenticationToken $row */
        $row = AuthenticationTokenFactory::firstOrFail([
            'token' => $ceremony->state,
            'type' => AuthenticationToken::TYPE_MFA_DELEGATED_STATE,
        ]);
        $this->assertSame($user->id, $row->user_id);
        $this->assertTrue($row->active);
    }

    public function testMfaWebauthnDelegatedStateService_Create_Success_MintsBothValuesServerSide(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();

        $ceremony = $this->service->create($this->makeUac($user));

        $this->assertTrue(Validation::uuid($ceremony->state));
        $this->assertTrue(Validation::uuid($ceremony->secret));
        $this->assertNotSame($ceremony->state, $ceremony->secret);
    }

    public function testMfaWebauthnDelegatedStateService_Create_Success_StoresTheSecretOnTheStateRow(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();

        $ceremony = $this->service->create($this->makeUac($user));

        $this->assertSame($ceremony->secret, $this->service->getSecretForState($ceremony->state));
    }

    public function testMfaWebauthnDelegatedStateService_Create_Success_LeavesOtherCeremoniesAlone(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $earlier = $this->persistState($user);

        $this->service->create($this->makeUac($user));

        /** @var \App\Model\Entity\AuthenticationToken $row */
        $row = AuthenticationTokenFactory::firstOrFail(['token' => $earlier->token]);
        $this->assertTrue($row->active);
    }

    public function testMfaWebauthnDelegatedStateService_Resolve_Success_ReturnsTheCeremonyUser(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $state = $this->persistState($user);

        $uac = $this->service->resolve($state->token);

        $this->assertSame($user->id, $uac->getId());
        $this->assertSame($user->username, $uac->getUsername());
        $this->assertSame(Role::USER, $uac->roleName());
    }

    public function testMfaWebauthnDelegatedStateService_Resolve_Success_LeavesTheStateActive(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $state = $this->persistState($user);

        $this->service->resolve($state->token);

        /** @var \App\Model\Entity\AuthenticationToken $row */
        $row = AuthenticationTokenFactory::firstOrFail(['token' => $state->token]);
        $this->assertTrue($row->active);
    }

    public function testMfaWebauthnDelegatedStateService_Resolve_Success_AcceptsStateJustBeforeExpiry(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $state = $this->persistState($user, ['created' => DateTime::now()->subMinutes(4)]);

        $uac = $this->service->resolve($state->token);

        $this->assertSame($user->id, $uac->getId());
    }

    public function testMfaWebauthnDelegatedStateService_Resolve_Success_HonorsConfiguredExpiry(): void
    {
        Configure::write(
            'passbolt.auth.token.' . AuthenticationToken::TYPE_MFA_DELEGATED_STATE . '.expiry',
            '30 minutes',
        );
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $state = $this->persistState($user, ['created' => DateTime::now()->subMinutes(20)]);

        $uac = $this->service->resolve($state->token);

        $this->assertSame($user->id, $uac->getId());
    }

    /**
     * A state row the finder must refuse. Every one of these reports the same error, so a caller cannot tell from the
     * response whether a state exists.
     *
     * @return array
     */
    public static function invalidStateRowProvider(): array
    {
        return [
            'inactive' => [['active' => false]],
            'expired' => [['created' => DateTime::now()->subMinutes(6)]],
            'wrong type' => [['type' => AuthenticationToken::TYPE_MFA_VERIFY]],
        ];
    }

    /**
     * @dataProvider invalidStateRowProvider
     * @param array $properties Row properties that make the state invalid.
     * @return void
     */
    public function testMfaWebauthnDelegatedStateService_Resolve_Error_RejectsInvalidStateRow(array $properties): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $state = $this->persistState($user, $properties);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The delegated state is not valid.');
        $this->service->resolve($state->token);
    }

    public function testMfaWebauthnDelegatedStateService_Resolve_Error_RejectsStateThatIsNotAUuid(): void
    {
        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The delegated state is not valid.');
        $this->service->resolve('not-a-uuid');
    }

    public function testMfaWebauthnDelegatedStateService_Resolve_Error_RejectsUnknownState(): void
    {
        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The delegated state is not valid.');
        $this->service->resolve(UuidFactory::uuid());
    }

    public function testMfaWebauthnDelegatedStateService_Resolve_Error_RejectsStateOfDeletedUser(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->deleted()->persist();
        $state = $this->persistState($user);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The delegated state is not valid.');
        $this->service->resolve($state->token);
    }

    public function testMfaWebauthnDelegatedStateService_Resolve_Error_RejectsStateOfDisabledUser(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->disabled()->persist();
        $state = $this->persistState($user);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The delegated state is not valid.');
        $this->service->resolve($state->token);
    }

    public function testMfaWebauthnDelegatedStateService_Resolve_Error_RejectsStateOfInactiveUser(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->inactive()->persist();
        $state = $this->persistState($user);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The delegated state is not valid.');
        $this->service->resolve($state->token);
    }

    public function testMfaWebauthnDelegatedStateService_GetSecretForState_Error_RejectsUnknownState(): void
    {
        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The delegated state is not valid.');
        $this->service->getSecretForState(UuidFactory::uuid());
    }

    public function testMfaWebauthnDelegatedStateService_GetSecretForState_Success_ReturnsTheStoredSecret(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $state = $this->persistState($user);

        $secret = $this->service->getSecretForState($state->token);

        $this->assertSame($state->getDataValue(MfaWebauthnDelegatedStateService::SECRET_KEY), $secret);
    }

    public function testMfaWebauthnDelegatedStateService_GetSecretForState_Error_RejectsStateWithoutSecret(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $state = $this->persistState($user, ['data' => null]);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The delegated state is not valid.');
        $this->service->getSecretForState($state->token);
    }
}

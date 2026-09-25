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
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\I18n\DateTime;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\Delegated\MfaWebauthnDelegatedCeremonyService;

/**
 * @covers \Passbolt\MultiFactorAuthentication\Service\Webauthn\Delegated\MfaWebauthnDelegatedCeremonyService
 */
class MfaWebauthnDelegatedCeremonyServiceTest extends TestCase
{
    use TruncateDirtyTables;

    private MfaWebauthnDelegatedCeremonyService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new MfaWebauthnDelegatedCeremonyService();
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    private static function validState(): string
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }

    public function testMfaWebauthnDelegatedCeremonyService_Create_MintsActiveCeremonyToken(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);

        $token = $this->service->create($uac, self::validState());

        /** @var \App\Model\Entity\AuthenticationToken $row */
        $row = AuthenticationTokenFactory::firstOrFail(['token' => $token]);
        $this->assertSame(AuthenticationToken::TYPE_MFA_DELEGATED_CEREMONY, $row->type);
        $this->assertSame($user->id, $row->user_id);
        $this->assertTrue($row->active);
    }

    public function testMfaWebauthnDelegatedCeremonyService_Create_StoresStateInDataJson(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $state = self::validState();

        $token = $this->service->create(new UserAccessControl(Role::USER, $user->id, $user->username), $state);

        $this->assertSame($state, $this->service->getStateForToken($token));
    }

    public function testMfaWebauthnDelegatedCeremonyService_Create_EmptyStateRejected(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('A state is required.');
        $this->service->create($uac, '');
    }

    public function testMfaWebauthnDelegatedCeremonyService_Create_MalformedStateRejected(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The state is not a valid base64url-encoded 32-byte value.');
        $this->service->create($uac, 'not-base64!!');
    }

    public function testMfaWebauthnDelegatedCeremonyService_Create_WrongLengthStateRejected(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);
        $short = rtrim(strtr(base64_encode(random_bytes(16)), '+/', '-_'), '=');

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The state is not a valid base64url-encoded 32-byte value.');
        $this->service->create($uac, $short);
    }

    public function testMfaWebauthnDelegatedCeremonyService_Create_DeactivatesPreviousActiveCeremoniesForUser(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);
        $first = $this->service->create($uac, self::validState());

        $this->service->create($uac, self::validState());

        /** @var \App\Model\Entity\AuthenticationToken $firstRow */
        $firstRow = AuthenticationTokenFactory::firstOrFail(['token' => $first]);
        $this->assertFalse($firstRow->active);
    }

    public function testMfaWebauthnDelegatedCeremonyService_Create_DoesNotDeactivateOtherUsers(): void
    {
        /** @var \App\Model\Entity\User $other */
        $other = UserFactory::make()->user()->persist();
        $otherToken = $this->service->create(
            new UserAccessControl(Role::USER, $other->id, $other->username),
            self::validState(),
        );

        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $this->service->create(
            new UserAccessControl(Role::USER, $user->id, $user->username),
            self::validState(),
        );

        /** @var \App\Model\Entity\AuthenticationToken $otherRow */
        $otherRow = AuthenticationTokenFactory::firstOrFail(['token' => $otherToken]);
        $this->assertTrue($otherRow->active);
    }

    public function testMfaWebauthnDelegatedCeremonyService_Resolve_ReturnsUacAndLeavesTokenActive(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $token = $this->service->create(
            new UserAccessControl(Role::USER, $user->id, $user->username),
            self::validState(),
        );

        $uac = $this->service->resolve($token);

        $this->assertSame($user->id, $uac->getId());
        $this->assertSame($user->username, $uac->getUsername());
        $this->assertSame(Role::USER, $uac->roleName());

        /** @var \App\Model\Entity\AuthenticationToken $row */
        $row = AuthenticationTokenFactory::firstOrFail(['token' => $token]);
        $this->assertTrue($row->active);
    }

    public function testMfaWebauthnDelegatedCeremonyService_Resolve_ThrowsWhenTokenNotUuid(): void
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('The ceremony token is invalid.');
        $this->service->resolve('not-a-uuid');
    }

    public function testMfaWebauthnDelegatedCeremonyService_Resolve_ThrowsWhenTokenUnknown(): void
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('The ceremony token is invalid or has expired.');
        $this->service->resolve(UuidFactory::uuid());
    }

    public function testMfaWebauthnDelegatedCeremonyService_Resolve_ThrowsWhenTokenInactive(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\AuthenticationToken $row */
        $row = AuthenticationTokenFactory::make()
            ->userId($user->id)
            ->type(AuthenticationToken::TYPE_MFA_DELEGATED_CEREMONY)
            ->inactive()
            ->persist();

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('The ceremony token is invalid or has expired.');
        $this->service->resolve($row->token);
    }

    public function testMfaWebauthnDelegatedCeremonyService_Resolve_ThrowsWhenTokenExpired(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\AuthenticationToken $row */
        $row = AuthenticationTokenFactory::make()
            ->userId($user->id)
            ->type(AuthenticationToken::TYPE_MFA_DELEGATED_CEREMONY)
            ->active()
            ->expired()
            ->persist();

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('The ceremony token is invalid or has expired.');
        $this->service->resolve($row->token);
    }

    public function testMfaWebauthnDelegatedCeremonyService_Resolve_AcceptsTokenJustBeforeExpiry(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->user()
            ->with('AuthenticationTokens', AuthenticationTokenFactory::make()
                ->type(AuthenticationToken::TYPE_MFA_DELEGATED_CEREMONY)
                ->active()
                ->created(DateTime::now()->modify('-4 minutes 55 seconds')))
            ->persist();

        $uac = $this->service->resolve($user->authentication_tokens[0]->token);

        $this->assertSame($user->id, $uac->getId());
    }

    public function testMfaWebauthnDelegatedCeremonyService_Resolve_ThrowsWhenTokenJustPastExpiry(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->user()
            ->with('AuthenticationTokens', AuthenticationTokenFactory::make()
                ->type(AuthenticationToken::TYPE_MFA_DELEGATED_CEREMONY)
                ->active()
                ->created(DateTime::now()->modify('-6 minutes')))
            ->persist();

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('The ceremony token is invalid or has expired.');
        $this->service->resolve($user->authentication_tokens[0]->token);
    }

    public function testMfaWebauthnDelegatedCeremonyService_Resolve_HonorsConfiguredExpiry(): void
    {
        Configure::write(
            'passbolt.auth.token.' . AuthenticationToken::TYPE_MFA_DELEGATED_CEREMONY . '.expiry',
            '1 hour',
        );
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->user()
            ->with('AuthenticationTokens', AuthenticationTokenFactory::make()
                ->type(AuthenticationToken::TYPE_MFA_DELEGATED_CEREMONY)
                ->active()
                ->created(DateTime::now()->modify('-30 minutes')))
            ->persist();

        $uac = $this->service->resolve($user->authentication_tokens[0]->token);

        $this->assertSame($user->id, $uac->getId());
    }

    public function testMfaWebauthnDelegatedCeremonyService_Resolve_ThrowsWhenTokenWrongType(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\AuthenticationToken $row */
        $row = AuthenticationTokenFactory::make()
            ->userId($user->id)
            ->type(AuthenticationToken::TYPE_MFA_VERIFY)
            ->active()
            ->persist();

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('The ceremony token is invalid or has expired.');
        $this->service->resolve($row->token);
    }

    public function testMfaWebauthnDelegatedCeremonyService_Resolve_ThrowsWhenUserDeleted(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->user()
            ->setField('deleted', true)
            ->with('AuthenticationTokens', AuthenticationTokenFactory::make()
                ->type(AuthenticationToken::TYPE_MFA_DELEGATED_CEREMONY)
                ->active())
            ->persist();

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('The ceremony token is invalid or has expired.');
        $this->service->resolve($user->authentication_tokens[0]->token);
    }

    public function testMfaWebauthnDelegatedCeremonyService_Resolve_ThrowsWhenUserDisabled(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->user()
            ->disabled()
            ->with('AuthenticationTokens', AuthenticationTokenFactory::make()
                ->type(AuthenticationToken::TYPE_MFA_DELEGATED_CEREMONY)
                ->active())
            ->persist();

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('The ceremony token is invalid or has expired.');
        $this->service->resolve($user->authentication_tokens[0]->token);
    }

    public function testMfaWebauthnDelegatedCeremonyService_Resolve_ThrowsWhenUserInactive(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->user()
            ->inactive()
            ->with('AuthenticationTokens', AuthenticationTokenFactory::make()
                ->type(AuthenticationToken::TYPE_MFA_DELEGATED_CEREMONY)
                ->active())
            ->persist();

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('The ceremony token is invalid or has expired.');
        $this->service->resolve($user->authentication_tokens[0]->token);
    }

    public function testMfaWebauthnDelegatedCeremonyService_GetStateForToken_ReturnsStoredState(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $state = self::validState();
        $token = $this->service->create(new UserAccessControl(Role::USER, $user->id, $user->username), $state);

        $this->assertSame($state, $this->service->getStateForToken($token));
    }

    public function testMfaWebauthnDelegatedCeremonyService_GetStateForToken_ThrowsWhenNotUuid(): void
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('The ceremony token is invalid.');
        $this->service->getStateForToken('not-a-uuid');
    }

    public function testMfaWebauthnDelegatedCeremonyService_GetStateForToken_ThrowsWhenUnknown(): void
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('The ceremony token is invalid.');
        $this->service->getStateForToken(UuidFactory::uuid());
    }

    public function testMfaWebauthnDelegatedCeremonyService_GetStateForToken_ThrowsWhenInactive(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\AuthenticationToken $row */
        $row = AuthenticationTokenFactory::make()
            ->userId($user->id)
            ->type(AuthenticationToken::TYPE_MFA_DELEGATED_CEREMONY)
            ->inactive()
            ->data([MfaWebauthnDelegatedCeremonyService::STATE_KEY => self::validState()])
            ->persist();

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('The ceremony token is invalid.');
        $this->service->getStateForToken($row->token);
    }

    public function testMfaWebauthnDelegatedCeremonyService_GetStateForToken_ThrowsWhenExpired(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->user()
            ->with('AuthenticationTokens', AuthenticationTokenFactory::make()
                ->type(AuthenticationToken::TYPE_MFA_DELEGATED_CEREMONY)
                ->active()
                ->data([MfaWebauthnDelegatedCeremonyService::STATE_KEY => self::validState()])
                ->created(DateTime::now()->modify('-6 minutes')))
            ->persist();

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('The ceremony token is invalid.');
        $this->service->getStateForToken($user->authentication_tokens[0]->token);
    }
}

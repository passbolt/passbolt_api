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
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Service\Webauthn;

use App\Error\Exception\CustomValidationException;
use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\Role;
use App\Test\Factory\UserFactory;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use InvalidArgumentException;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnChallengeTokenService;

/**
 * @covers \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnChallengeTokenService
 */
class MfaWebauthnChallengeTokenServiceTest extends TestCase
{
    use TruncateDirtyTables;

    public function testMfaWebauthnChallengeTokenService_CreateThenConsume_ReturnsOptions(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);
        $service = new MfaWebauthnChallengeTokenService();

        $options = '{"challenge":"abc"}';
        $token = $service->create($uac, AuthenticationToken::TYPE_MFA_SETUP, $options);
        $this->assertSame(AuthenticationToken::TYPE_MFA_SETUP, $token->type);
        $this->assertTrue($token->active);

        $restored = $service->consume($uac, AuthenticationToken::TYPE_MFA_SETUP, $token->token);
        $this->assertSame($options, $restored);
    }

    public function testMfaWebauthnChallengeTokenService_Consume_Twice_Fails(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);
        $service = new MfaWebauthnChallengeTokenService();
        $token = $service->create($uac, AuthenticationToken::TYPE_MFA_VERIFY, '{"c":1}');

        $service->consume($uac, AuthenticationToken::TYPE_MFA_VERIFY, $token->token);

        $this->expectException(CustomValidationException::class);
        $service->consume($uac, AuthenticationToken::TYPE_MFA_VERIFY, $token->token);
    }

    public function testMfaWebauthnChallengeTokenService_Consume_WrongUser_Fails(): void
    {
        /** @var \App\Model\Entity\User $owner */
        $owner = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\User $intruder */
        $intruder = UserFactory::make()->user()->persist();
        $service = new MfaWebauthnChallengeTokenService();
        $token = $service->create(
            new UserAccessControl(Role::USER, $owner->id, $owner->username),
            AuthenticationToken::TYPE_MFA_SETUP,
            '{"c":2}',
        );

        $this->expectException(NotFoundException::class);
        $service->consume(
            new UserAccessControl(Role::USER, $intruder->id, $intruder->username),
            AuthenticationToken::TYPE_MFA_SETUP,
            $token->token,
        );
    }

    public function testMfaWebauthnChallengeTokenService_UnsupportedType_Throws(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);
        $this->expectException(InvalidArgumentException::class);
        (new MfaWebauthnChallengeTokenService())->create($uac, 'other_type', '{}');
    }

    public function testMfaWebauthnChallengeTokenService_ConsumeSetsInactive(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);
        $service = new MfaWebauthnChallengeTokenService();
        $token = $service->create($uac, AuthenticationToken::TYPE_MFA_SETUP, '{"c":3}');

        $service->consume($uac, AuthenticationToken::TYPE_MFA_SETUP, $token->token);

        /** @var \App\Model\Entity\AuthenticationToken $reloaded */
        $reloaded = TableRegistry::getTableLocator()
            ->get('AuthenticationTokens')
            ->find()->where(['id' => $token->id])->firstOrFail();
        $this->assertFalse($reloaded->active);
    }

    /**
     * A setup handle must not be consumable via the verify type — closes the
     * cross-wire attack where an attacker uses a legitimate setup handle to
     * bypass signature validation on the verify path.
     */
    public function testMfaWebauthnChallengeTokenService_Consume_SetupHandleRejectedAsVerify(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);
        $service = new MfaWebauthnChallengeTokenService();
        $handle = $service->create($uac, AuthenticationToken::TYPE_MFA_SETUP, '{"c":3}');

        $this->expectException(NotFoundException::class);
        $service->consume($uac, AuthenticationToken::TYPE_MFA_VERIFY, $handle->token);
    }

    public function testMfaWebauthnChallengeTokenService_Consume_VerifyHandleRejectedAsSetup(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);
        $service = new MfaWebauthnChallengeTokenService();
        $handle = $service->create($uac, AuthenticationToken::TYPE_MFA_VERIFY, '{"c":4}');

        $this->expectException(NotFoundException::class);
        $service->consume($uac, AuthenticationToken::TYPE_MFA_SETUP, $handle->token);
    }

    public function testMfaWebauthnChallengeTokenService_Consume_UnknownHandleFails(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();

        $this->expectException(NotFoundException::class);
        (new MfaWebauthnChallengeTokenService())->consume(
            new UserAccessControl(Role::USER, $user->id, $user->username),
            AuthenticationToken::TYPE_MFA_SETUP,
            UuidFactory::uuid(),
        );
    }
}

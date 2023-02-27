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
 * @since         3.11.0
 */

namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Service\Duo;

use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\Role;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Http\Exception\UnauthorizedException;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\MultiFactorAuthentication\Service\Duo\MfaDuoCallbackAuthenticationTokenService;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class MfaDuoCallbackAuthenticationTokenServiceTest extends TestCase
{
    use TruncateDirtyTables;

    public function testMfaDuoCallbackAuthenticationTokenServiceTest_Success()
    {
        $user = UserFactory::make()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id);
        $duoState = UuidFactory::uuid();
        $authToken = AuthenticationTokenFactory::make()->active()->data([
            'provider' => 'duo',
            'state' => $duoState,
            'redirect' => '',
            'user_agent' => 'PassboltUA',
        ])->userId($user->id)->type(AuthenticationToken::TYPE_MFA_SETUP)->persist();

        $service = new MfaDuoCallbackAuthenticationTokenService();
        $verifiedAuthToken = $service->consumeAndVerifyAuthenticationToken(
            $uac,
            AuthenticationToken::TYPE_MFA_SETUP,
            $authToken->token,
            $duoState
        );

        $this->assertEquals($verifiedAuthToken->id, $authToken->id);
        $this->assertEquals($verifiedAuthToken->token, $authToken->token);
        $this->assertEquals($verifiedAuthToken->type, AuthenticationToken::TYPE_MFA_SETUP);
        $this->assertEquals($verifiedAuthToken->active, true);
        $verifiedAuthTokenData = json_decode($verifiedAuthToken->data, true);
        $this->assertEquals($verifiedAuthTokenData['provider'], MfaSettings::PROVIDER_DUO);
        $this->assertEquals($verifiedAuthTokenData['state'], $duoState);
        $this->assertEquals($verifiedAuthTokenData['redirect'], '');
        $this->assertEquals($verifiedAuthTokenData['user_agent'], 'PassboltUA');
        $this->assertEquals(1, AuthenticationTokenFactory::count());
        $this->assertCount(0, AuthenticationTokenFactory::find()->where(['active' => true]));
    }

    public function testMfaDuoCallbackAuthenticationTokenServiceTest_Error_WrongDuoState()
    {
        $user = UserFactory::make()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id);
        $authToken = AuthenticationTokenFactory::make()->active()->data([
            'provider' => 'duo',
            'state' => UuidFactory::uuid(),
            'redirect' => '',
            'user_agent' => 'PassboltUA',
        ])->userId($user->id)->type(AuthenticationToken::TYPE_MFA_SETUP)->persist();

        $service = new MfaDuoCallbackAuthenticationTokenService();
        try {
            $service->consumeAndVerifyAuthenticationToken(
                $uac,
                AuthenticationToken::TYPE_MFA_SETUP,
                $authToken->token,
                UuidFactory::uuid()
            );
        } catch (\Throwable $e) {
        }

        $this->assertInstanceOf(UnauthorizedException::class, $e);
        $this->assertEquals(1, AuthenticationTokenFactory::count());
        $this->assertCount(0, AuthenticationTokenFactory::find()->where(['active' => true]));
    }

    public function testMfaDuoCallbackAuthenticationTokenServiceTest_Error_InvalidTokenFormat()
    {
        $service = new MfaDuoCallbackAuthenticationTokenService();
        try {
            $service->consumeAndVerifyAuthenticationToken(
                new UserAccessControl(Role::USER, UuidFactory::uuid()),
                AuthenticationToken::TYPE_MFA_SETUP,
                'not-a-valid-token',
                UuidFactory::uuid()
            );
        } catch (\Throwable $e) {
        }

        $this->assertInstanceOf(\InvalidArgumentException::class, $e);
        $this->assertTextContains('The authentication token should be a valid UUID.', $e->getMessage());
    }

    public function testMfaDuoCallbackAuthenticationTokenServiceTest_Error_InvalidAuthenticationTokenType()
    {
        $service = new MfaDuoCallbackAuthenticationTokenService();
        try {
            $service->consumeAndVerifyAuthenticationToken(
                new UserAccessControl(Role::USER, UuidFactory::uuid()),
                'invalid-token-type',
                UuidFactory::uuid(),
                UuidFactory::uuid()
            );
        } catch (\Throwable $e) {
        }

        $this->assertInstanceOf(\InvalidArgumentException::class, $e);
        $this->assertTextContains('The authentication token type should be one of the following: mfa_setup, mfa_verify.', $e->getMessage());
    }

    public function testMfaDuoCallbackAuthenticationTokenServiceTest_Error_AuthenticationNotFound()
    {
        $service = new MfaDuoCallbackAuthenticationTokenService();
        try {
            $service->consumeAndVerifyAuthenticationToken(
                new UserAccessControl(Role::USER, UuidFactory::uuid()),
                AuthenticationToken::TYPE_MFA_SETUP,
                UuidFactory::uuid(),
                UuidFactory::uuid()
            );
        } catch (\Throwable $e) {
        }

        $this->assertInstanceOf(UnauthorizedException::class, $e);
    }

    public function testMfaDuoCallbackAuthenticationTokenServiceTest_Error_AuthenticationExpired()
    {
        $user = UserFactory::make()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id);
        $duoState = UuidFactory::uuid();
        $authToken = AuthenticationTokenFactory::make()->active()->data([
            'provider' => 'duo',
            'state' => $duoState,
            'redirect' => '',
            'user_agent' => 'PassboltUA',
        ])->userId($user->id)
            ->type(AuthenticationToken::TYPE_MFA_SETUP)
            ->expired()
            ->persist();

        $service = new MfaDuoCallbackAuthenticationTokenService();
        try {
            $service->consumeAndVerifyAuthenticationToken(
                $uac,
                AuthenticationToken::TYPE_MFA_SETUP,
                $authToken->token,
                $duoState
            );
        } catch (\Throwable $e) {
        }

        $this->assertInstanceOf(UnauthorizedException::class, $e);
    }

    public function testMfaDuoCallbackAuthenticationTokenServiceTest_Error_AuthenticationInactive()
    {
        $user = UserFactory::make()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id);
        $duoState = UuidFactory::uuid();
        $authToken = AuthenticationTokenFactory::make()->active()->data([
            'provider' => 'duo',
            'state' => $duoState,
            'redirect' => '',
            'user_agent' => 'PassboltUA',
        ])->userId($user->id)
            ->type(AuthenticationToken::TYPE_MFA_SETUP)
            ->inactive()
            ->persist();

        $service = new MfaDuoCallbackAuthenticationTokenService();
        try {
            $service->consumeAndVerifyAuthenticationToken(
                $uac,
                AuthenticationToken::TYPE_MFA_SETUP,
                $authToken->token,
                $duoState
            );
        } catch (\Throwable $e) {
        }

        $this->assertInstanceOf(UnauthorizedException::class, $e);
    }

    public function testMfaDuoCallbackAuthenticationTokenServiceTest_Error_DuoStateNotMatching()
    {
        $user = UserFactory::make()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id);
        $authToken = AuthenticationTokenFactory::make()->active()->data([
            'provider' => 'duo',
            'state' => UuidFactory::uuid(),
            'redirect' => '',
            'user_agent' => 'PassboltUA',
        ])->userId($user->id)
            ->type(AuthenticationToken::TYPE_MFA_SETUP)
            ->persist();

        $service = new MfaDuoCallbackAuthenticationTokenService();
        try {
            $service->consumeAndVerifyAuthenticationToken(
                $uac,
                AuthenticationToken::TYPE_MFA_SETUP,
                $authToken->token,
                UuidFactory::uuid()
            );
        } catch (\Throwable $e) {
        }

        $this->assertInstanceOf(UnauthorizedException::class, $e);
        $this->assertTextContains('The Duo state should match the authentication token state.', $e->getMessage());
    }
}

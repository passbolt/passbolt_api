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
 * @since         2.5.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Utility;

use App\Authenticator\AbstractSessionIdentificationService;
use App\Error\Exception\ValidationException;
use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Cake\Http\ServerRequest;
use Cake\I18n\FrozenDate;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;
use Passbolt\MultiFactorAuthentication\Test\Factory\MfaAuthenticationTokenFactory;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedToken;

class MfaVerifiedTokenTest extends MfaIntegrationTestCase
{
    /**
     * @var \App\Model\Table\AuthenticationTokensTable
     */
    protected $AuthenticationTokens;

    /**
     * Setup.
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->AuthenticationTokens = TableRegistry::getTableLocator()->get('AuthenticationTokens');
    }

    /**
     * @group mfa
     * @group mfaVerifiedToken
     */
    public function testMfaVerifiedTokenSuccess()
    {
        $uac = UserFactory::make()->user()->persistedUAC();
        $sessionId = uniqid();
        $token = MfaVerifiedToken::get($uac, MfaSettings::PROVIDER_TOTP, $sessionId);
        $this->assertTrue(Validation::uuid($token));

        $valid = $this->AuthenticationTokens->isValid($token, $uac->getId(), AuthenticationToken::TYPE_MFA);
        $this->assertTrue($valid);

        $token = $this->AuthenticationTokens->find()->where(['token' => $token, 'active' => true ])->firstOrFail();
        $this->assertNotEmpty($token->get('data'));
        $data = json_decode($token->get('data'), true);
        $this->assertNotEmpty($data);
        $this->assertEquals(MfaSettings::PROVIDER_TOTP, $data['provider']);
    }

    /**
     * @group mfa
     * @group mfaVerifiedToken
     */
    public function testMfaVerifiedTokenFails()
    {
        $uac = $this->mockUserAccessControl('nope');
        $this->expectException(ValidationException::class);
        MfaVerifiedToken::get($uac, MfaSettings::PROVIDER_TOTP, uniqid());
    }

    /**
     * @group mfa
     * @group mfaVerifiedToken
     */
    public function testMfaVerifiedTokenCheckSuccess()
    {
        $uac = UserFactory::make()->user()->persistedUAC();
        $sessionId = uniqid();
        $stubSessionIdentifier = $this->getMockForAbstractClass(AbstractSessionIdentificationService::class);
        $stubSessionIdentifier->method('getSessionIdentifier')->willReturn($sessionId);
        $token = MfaVerifiedToken::get($uac, MfaSettings::PROVIDER_TOTP, $sessionId);
        $success = MfaVerifiedToken::check($uac, $token, $stubSessionIdentifier, new ServerRequest());
        $this->assertTrue($success);
    }

    /**
     * @group mfa
     * @group mfaVerifiedToken
     */
    public function testMfaVerifiedTokenCheck_With_Different_Session_Fails()
    {
        $uac = UserFactory::make()->user()->persistedUAC();
        $sessionId = 'Foo';
        $stubSessionIdentifier = $this->getMockForAbstractClass(AbstractSessionIdentificationService::class);
        $stubSessionIdentifier->method('getSessionIdentifier')->willReturn('Bar');
        $token = MfaVerifiedToken::get($uac, MfaSettings::PROVIDER_TOTP, $sessionId);
        $success = MfaVerifiedToken::check($uac, $token, $stubSessionIdentifier, new ServerRequest());
        $this->assertFalse($success);
    }

    /**
     * @group mfa
     * @group mfaVerifiedToken
     */
    public function testMfaVerifiedTokenCheckFailsTokenDoesNotExist()
    {
        // Token does not exist
        $uac = UserFactory::make()->user()->persistedUAC();
        $success = MfaVerifiedToken::check($uac, UuidFactory::uuid());
        $this->assertFalse($success);
    }

    /**
     * @group mfa
     * @group mfaVerifiedToken
     */
    public function testMfaVerifiedTokenCheckFailsTokenBelongToAnotherUser()
    {
        $uac = UserFactory::make()->user()->persistedUAC();
        $uac2 = UserFactory::make()->user()->persistedUAC();
        $sessionId = 'Foo';
        $stubSessionIdentifier = $this->getMockForAbstractClass(AbstractSessionIdentificationService::class);
        $stubSessionIdentifier->method('getSessionIdentifier')->willReturn($sessionId);
        $token = MfaVerifiedToken::get($uac, MfaSettings::PROVIDER_TOTP, $sessionId);
        $this->assertTrue(MfaVerifiedToken::check($uac, $token, $stubSessionIdentifier, new ServerRequest()));
        $this->assertFalse(MfaVerifiedToken::check($uac2, $token));
    }

    /**
     * @group mfa
     * @group mfaVerifiedToken
     */
    public function testMfaVerifiedTokenCheckFailsTokenIfDifferentSessionId()
    {
        $uac = UserFactory::make()->user()->persistedUAC();
        $stubSessionIdentifier = $this->getMockForAbstractClass(AbstractSessionIdentificationService::class);
        $stubSessionIdentifier->method('getSessionIdentifier')->willReturn('Foo');
        $token = MfaVerifiedToken::get($uac, MfaSettings::PROVIDER_TOTP, 'Bar');
        $success = MfaVerifiedToken::check($uac, $token, $stubSessionIdentifier, new ServerRequest());
        $this->assertFalse($success);
    }

    /**
     * @group mfa
     * @group mfaVerifiedToken
     */
    public function testMfaVerifiedTokenCheckFailsTokenIsDisabled()
    {
        $uac = UserFactory::make()->user()->persistedUAC();
        $token = MfaVerifiedToken::get($uac, MfaSettings::PROVIDER_TOTP, uniqid());
        $token = $this->AuthenticationTokens->find()->where(['token' => $token, 'active' => true ])->firstOrFail();
        $this->AuthenticationTokens->setInactive($token->get('token'));
        $success = MfaVerifiedToken::check($uac, $token->get('token'));
        $this->assertFalse($success);
    }

    /**
     * @group mfa
     * @group mfaVerifiedToken
     */
    public function testMfaVerifiedTokenCheckFailsTokenAnotherType()
    {
        // Token is from another type
        $uac = UserFactory::make()->user()->persistedUAC();
        $token = $this->AuthenticationTokens->generate($uac->getId(), AuthenticationToken::TYPE_LOGIN);
        $success = MfaVerifiedToken::check($uac, $token->token);
        $this->assertFalse($success);
    }

    /**
     * This test has been updated since the user agent is not checked anymore
     *
     * @group mfa
     * @group mfaVerifiedToken
     */
    public function testMfaVerifiedTokenCheckSuccessWithAnotherUserAgent()
    {
        // Token is associated with another user agent
        $uac = UserFactory::make()->user()->persistedUAC();
        $sessionId = 'Foo';
        $stubSessionIdentifier = $this->getMockForAbstractClass(AbstractSessionIdentificationService::class);
        $stubSessionIdentifier->method('getSessionIdentifier')->willReturn($sessionId);
        $entityData = [
            'user_id' => $uac->getId(),
            'token' => UuidFactory::uuid(),
            'active' => true,
            'type' => AuthenticationToken::TYPE_MFA,
            'data' => json_encode([
                'provider' => MfaSettings::PROVIDER_TOTP,
                'user_agent' => 'another user agent',
            ]),
        ];
        $accessibleFields = ['user_id' => true, 'token' => true, 'active' => true, 'type' => true, 'data' => true];
        $token = $this->AuthenticationTokens->newEntity($entityData, ['accessibleFields' => $accessibleFields]);
        $token->hashAndSetSessionId($sessionId);
        $this->assertEmpty($token->getErrors());
        $this->AuthenticationTokens->save($token);
        $this->assertEmpty($token->getErrors());
        $success = MfaVerifiedToken::check($uac, $token->token, $stubSessionIdentifier, new ServerRequest());
        $this->assertTrue($success);
    }

    public function testMfaVerifiedTokenCheckFails_On_Emtpy_Data()
    {
        $user = UserFactory::make()
            ->user()
            ->withAuthenticationTokens(MfaAuthenticationTokenFactory::make()->setField('data', null))
            ->persist();
        $failingToken = $user->authentication_tokens[0];

        $success = MfaVerifiedToken::check($this->makeUac($user), $failingToken->token);
        $this->assertFalse($success);
        // Check that the token was deactivated
        $failingToken = MfaAuthenticationTokenFactory::get($failingToken->id);
        $this->assertFalse($failingToken->get('active'));
    }

    /**
     * @group mfa
     * @group mfaVerifiedToken
     */
    public function testMfaVerifiedTokenCheckFailsWhenDataIsNotValidJson()
    {
        $user = UserFactory::make()
            ->user()
            ->withAuthenticationTokens(
                MfaAuthenticationTokenFactory::make()
                    ->active()
                    ->setField('data', '{This is no valid JSON!{')
            )
            ->persist();
        $token = $user->authentication_tokens[0];

        $success = MfaVerifiedToken::check($this->makeUac($user), $token->token);
        $this->assertFalse($success);
    }

    /**
     * @group mfa
     * @group mfaVerifiedToken
     */
    public function testMfaVerifiedTokenCheckFailsWhenExpired()
    {
        $user = UserFactory::make()
            ->user()
            ->withAuthenticationTokens(
                MfaAuthenticationTokenFactory::make()
                    ->active()
                    ->created((new FrozenDate())->addDays(-MfaVerifiedCookie::MAX_DURATION_IN_DAYS))
            )
            ->persist();
        $token = $user->authentication_tokens[0];

        $success = MfaVerifiedToken::check($this->makeUac($user), $token->token);
        $this->assertFalse($success);
        $token = AuthenticationTokenFactory::get($token->id);
        $this->assertFalse($token->isActive());
    }

    /**
     * @group mfa
     * @group mfaVerifiedToken
     */
    public function testMfaVerifiedTokenCheckFailsTokenIfIsExpiredWhenRememberIsTrue()
    {
        $uac = UserFactory::make()->user()->persistedUAC();
        $entityData = [
            'user_id' => $uac->getId(),
            'token' => UuidFactory::uuid(),
            'active' => true,
            'type' => AuthenticationToken::TYPE_MFA,
            'created' => (new FrozenDate())->addDays(-MfaVerifiedCookie::MAX_DURATION_IN_DAYS),
            'data' => json_encode([
                'provider' => MfaSettings::PROVIDER_TOTP,
                'user_agent' => null,
                'session_id' => uniqid(),
                'remember' => true,
            ]),
        ];
        $accessibleFields = ['user_id' => true, 'token' => true, 'active' => true, 'type' => true, 'data' => true, 'created' => true];
        $token = $this->AuthenticationTokens->newEntity($entityData, ['accessibleFields' => $accessibleFields]);
        $this->assertEmpty($token->getErrors());
        $this->AuthenticationTokens->save($token);
        $this->assertEmpty($token->getErrors());

        $success = MfaVerifiedToken::check($uac, $token->token);
        $this->assertFalse($success);
    }

    public function testMfaVerifiedTokenCheckSuccessTokenIfIsNotExpiredWhenRememberIsTrue()
    {
        $uac = UserFactory::make()->user()->persistedUAC();
        $entityData = [
            'user_id' => $uac->getId(),
            'token' => UuidFactory::uuid(),
            'active' => true,
            'type' => AuthenticationToken::TYPE_MFA,
            'created' => (new FrozenDate())->addDays(-MfaVerifiedCookie::MAX_DURATION_IN_DAYS + 1),
            'data' => json_encode([
                'provider' => MfaSettings::PROVIDER_TOTP,
                'user_agent' => null,
                'session_id' => uniqid(),
                'remember' => true,
            ]),
        ];
        $accessibleFields = ['user_id' => true, 'token' => true, 'active' => true, 'type' => true, 'data' => true, 'created' => true];
        $token = $this->AuthenticationTokens->newEntity($entityData, ['accessibleFields' => $accessibleFields]);
        $this->assertEmpty($token->getErrors());
        $this->AuthenticationTokens->save($token);
        $this->assertEmpty($token->getErrors());

        $success = MfaVerifiedToken::check($uac, $token->token);
        $this->assertTrue($success);
    }

    /**
     * @group mfa
     * @group mfaVerifiedToken
     */
    public function testMfaVerifiedTokenSetAllInactive()
    {
        $uac = UserFactory::make()->user()->persistedUAC();
        $sessionId = uniqid();
        MfaVerifiedToken::get($uac, MfaSettings::PROVIDER_TOTP, $sessionId);
        MfaVerifiedToken::get($uac, MfaSettings::PROVIDER_YUBIKEY, $sessionId);
        MfaVerifiedToken::get($uac, MfaSettings::PROVIDER_DUO, $sessionId);
        $tokensCount = $this->AuthenticationTokens->find()
            ->where(['user_id' => $uac->getId(), 'type' => MfaSettings::MFA, 'active' => true])
            ->count();
        $this->assertEquals($tokensCount, 3);

        MfaVerifiedToken::setAllInactive($uac);
        $tokensCount = $this->AuthenticationTokens->find()
            ->where(['user_id' => $uac->getId(), 'type' => MfaSettings::MFA, 'active' => true])
            ->count();
        $this->assertEquals($tokensCount, 0);
    }
}

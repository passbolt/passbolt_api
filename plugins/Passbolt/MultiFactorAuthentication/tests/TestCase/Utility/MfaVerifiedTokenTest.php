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

use App\Error\Exception\ValidationException;
use App\Model\Entity\AuthenticationToken;
use App\Utility\UuidFactory;
use Cake\Http\ServerRequestFactory;
use Cake\I18n\Date;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedToken;

class MfaVerifiedTokenTest extends MfaIntegrationTestCase
{
    /**
     * @var array
     */
    public $fixtures = [
        'app.Base/Users', 'app.Base/Profiles', 'app.Base/Roles',
    ];

    /**
     * @var AuthenticationTokensTable;
     */
    protected $AuthenticationTokens;

    /**
     * Setup.
     */
    public function setUp()
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
        $uac = $this->mockUserAccessControl('ada');
        $sessionId = uniqid();
        $token = MfaVerifiedToken::get($uac, MfaSettings::PROVIDER_TOTP, $sessionId);
        $this->assertTrue(Validation::uuid($token));

        $valid = $this->AuthenticationTokens->isValid($token, UuidFactory::uuid('user.id.ada'), AuthenticationToken::TYPE_MFA);
        $this->assertTrue($valid);

        $token = $this->AuthenticationTokens->getByToken($token);
        $this->assertNotEmpty($token->data);
        $data = json_decode($token->data, true);
        $this->assertNotEmpty($data);
        $this->assertEquals($data['provider'], MfaSettings::PROVIDER_TOTP);
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
        $uac = $this->mockUserAccessControl('ada');
        $sessionId = uniqid();
        $token = MfaVerifiedToken::get($uac, MfaSettings::PROVIDER_TOTP, $sessionId);
        $request = ServerRequestFactory::fromGlobals();
        $request->getSession()->id($sessionId);
        $success = MfaVerifiedToken::check($uac, $token, $sessionId);
        $this->assertTrue($success);
    }

    /**
     * @group mfa
     * @group mfaVerifiedToken
     */
    public function testMfaVerifiedTokenCheckFailsTokenDoesNotExist()
    {
        // Token does not exist
        $uac = $this->mockUserAccessControl('ada');
        $success = MfaVerifiedToken::check($uac, UuidFactory::uuid(), uniqid());
        $this->assertFalse($success);
    }

    /**
     * @group mfa
     * @group mfaVerifiedToken
     */
    public function testMfaVerifiedTokenCheckFailsTokenBelongToAnotherUser()
    {
        $uac = $this->mockUserAccessControl('ada');
        $uac2 = $this->mockUserAccessControl('betty');
        $sessionId = uniqid();
        $token = MfaVerifiedToken::get($uac, MfaSettings::PROVIDER_TOTP, $sessionId);
        $success = MfaVerifiedToken::check($uac2, $token, $sessionId);
        $this->assertFalse($success);
    }

    /**
     * @group mfa
     * @group mfaVerifiedToken
     */
    public function testMfaVerifiedTokenCheckFailsTokenIfDifferentSessionId()
    {
        $uac = $this->mockUserAccessControl('ada');
        $uac2 = $this->mockUserAccessControl('betty');
        $token = MfaVerifiedToken::get($uac, MfaSettings::PROVIDER_TOTP, uniqid());
        $success = MfaVerifiedToken::check($uac2, $token, uniqid());
        $this->assertFalse($success);
    }

    /**
     * @group mfa
     * @group mfaVerifiedToken
     */
    public function testMfaVerifiedTokenCheckFailsTokenIsDisabled()
    {
        $uac = $this->mockUserAccessControl('ada');
        $sessionId = uniqid();
        $token = MfaVerifiedToken::get($uac, MfaSettings::PROVIDER_TOTP, $sessionId);
        $token = $this->AuthenticationTokens->getByToken($token);
        $this->AuthenticationTokens->setInactive($token->token);
        $success = MfaVerifiedToken::check($uac, $token->token, $sessionId);
        $this->assertFalse($success);
    }

    /**
     * @group mfa
     * @group mfaVerifiedToken
     */
    public function testMfaVerifiedTokenCheckFailsTokenAnotherType()
    {
        // Token is from another type
        $uac = $this->mockUserAccessControl('ada');
        $token = $this->AuthenticationTokens->generate($uac->getId(), AuthenticationToken::TYPE_LOGIN);
        $success = MfaVerifiedToken::check($uac, $token->token, uniqid());
        $this->assertFalse($success);
    }

    /**
     * @group mfa
     * @group mfaVerifiedToken
     */
    public function testMfaVerifiedTokenCheckFailsAnotherUserAgent()
    {
        // Token is associated with another user agent
        $uac = $this->mockUserAccessControl('ada');
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
        $this->assertEmpty($token->getErrors());
        $this->AuthenticationTokens->save($token);
        $this->assertEmpty($token->getErrors());
        $success = MfaVerifiedToken::check($uac, $token->token, uniqid());
        $this->assertFalse($success);
    }

    /**
     * @group mfa
     * @group mfaVerifiedToken
     */
    public function testMfaVerifiedTokenCheckFailsTokenIfIsExpiredWhenRememberIsTrue()
    {
        $sessionId = uniqid();
        $uac = $this->mockUserAccessControl('ada');
        $entityData = [
            'user_id' => $uac->getId(),
            'token' => UuidFactory::uuid(),
            'active' => true,
            'type' => AuthenticationToken::TYPE_MFA,
            'created' => (new Date())->addDays(-MfaVerifiedCookie::MAX_DURATION_IN_DAYS),
            'data' => json_encode([
                'provider' => MfaSettings::PROVIDER_TOTP,
                'user_agent' => null,
                'session_id' => $sessionId,
                'remember' => true,
            ]),
        ];
        $accessibleFields = ['user_id' => true, 'token' => true, 'active' => true, 'type' => true, 'data' => true, 'created' => true];
        $token = $this->AuthenticationTokens->newEntity($entityData, ['accessibleFields' => $accessibleFields]);
        $this->assertEmpty($token->getErrors());
        $this->AuthenticationTokens->save($token);
        $this->assertEmpty($token->getErrors());

        $success = MfaVerifiedToken::check($uac, $token->token, $sessionId);
        $this->assertFalse($success);
    }

    public function testMfaVerifiedTokenCheckSuccessTokenIfIsNotExpiredWhenRememberIsTrue()
    {
        $sessionId = uniqid();
        $uac = $this->mockUserAccessControl('ada');
        $entityData = [
            'user_id' => $uac->getId(),
            'token' => UuidFactory::uuid(),
            'active' => true,
            'type' => AuthenticationToken::TYPE_MFA,
            'created' => (new Date())->addDays(-10),
            'data' => json_encode([
                'provider' => MfaSettings::PROVIDER_TOTP,
                'user_agent' => null,
                'session_id' => $sessionId,
                'remember' => true,
            ]),
        ];
        $accessibleFields = ['user_id' => true, 'token' => true, 'active' => true, 'type' => true, 'data' => true, 'created' => true];
        $token = $this->AuthenticationTokens->newEntity($entityData, ['accessibleFields' => $accessibleFields]);
        $this->assertEmpty($token->getErrors());
        $this->AuthenticationTokens->save($token);
        $this->assertEmpty($token->getErrors());

        $success = MfaVerifiedToken::check($uac, $token->token, $sessionId);
        $this->assertTrue($success);
    }

    /**
     * @group mfa
     * @group mfaVerifiedToken
     */
    public function testMfaVerifiedTokenSetAllInactive()
    {
        $uac = $this->mockUserAccessControl('ada');
        $sessionId = uniqid();
        MfaVerifiedToken::get($uac, MfaSettings::PROVIDER_TOTP, $sessionId);
        MfaVerifiedToken::get($uac, MfaSettings::PROVIDER_YUBIKEY, $sessionId);
        MfaVerifiedToken::get($uac, MfaSettings::PROVIDER_DUO, $sessionId);
        $tokens = $this->AuthenticationTokens->find()
            ->where(['user_id' => $uac->getId(), 'type' => MfaSettings::MFA, 'active' => true])
            ->all()
            ->toArray();
        $this->assertEquals(count($tokens), 3);

        MfaVerifiedToken::setAllInactive($uac);
        $tokens = $this->AuthenticationTokens->find()
            ->where(['user_id' => $uac->getId(), 'type' => MfaSettings::MFA, 'active' => true])
            ->all()
            ->toArray();
        $this->assertEquals(count($tokens), 0);
    }
}

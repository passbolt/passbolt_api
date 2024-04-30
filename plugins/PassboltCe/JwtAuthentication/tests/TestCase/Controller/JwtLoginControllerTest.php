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
 * @since         3.3.0
 */
namespace Passbolt\JwtAuthentication\Test\TestCase\Controller;

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Utility\UuidFactory;
use Cake\Database\Type\UuidType;
use Cake\Database\TypeFactory;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Routing\Router;
use Cake\Validation\Validation;
use Passbolt\JwtAuthentication\Authenticator\GpgJwtAuthenticator;
use Passbolt\JwtAuthentication\JwtAuthenticationPlugin;
use Passbolt\JwtAuthentication\Test\Utility\JwtAuthenticationIntegrationTestCase;
use Passbolt\Log\Test\Lib\Traits\ActionLogsTestTrait;

/**
 * Class AuthJwtLogoutControllerTest
 */
class JwtLoginControllerTest extends JwtAuthenticationIntegrationTestCase
{
    use ActionLogsTestTrait;
    use EmailQueueTrait;
    use LocatorAwareTrait;

    /**
     * @var \App\Model\Table\AuthenticationTokensTable
     */
    protected $AuthenticationTokens;

    /**
     * @var \App\Model\Table\UsersTable
     */
    protected $Users;

    /**
     * @var \Passbolt\Log\Model\Table\ActionLogsTable
     */
    protected $ActionLogs;

    public function setUp(): void
    {
        parent::setUp();

        $this->AuthenticationTokens = $this->fetchTable('AuthenticationTokens');
        $this->Users = $this->fetchTable('Users');
        $this->ActionLogs = $this->fetchTable('Passbolt/Log.ActionLogs');
        $this->enableFeaturePlugin('Log');
        $this->enableFeaturePlugin(JwtAuthenticationPlugin::class);
        RoleFactory::make()->guest()->persist();
        EventManager::instance()->setEventList(new EventList());
        TypeFactory::map('uuid', UuidType::class);
    }

    public function testJwtLoginControllerTest_Success_With_Uppercase_Verify_Token()
    {
        $user = UserFactory::make()
            ->user()
            ->with('Gpgkeys', GpgkeyFactory::make()->validFingerprint())
            ->persist();

        // The verify-token is on purpose here upper-cased to assert that it was not lower cased
        // during the login action. This is required by Apple mobile devices
        $verifyToken = strtoupper(UuidFactory::uuid());
        $this->postJson('/auth/jwt/login.json', [
            'user_id' => $user->id,
            'challenge' => $this->makeChallenge($user, $verifyToken),
        ]);

        $this->assertResponseOk('The authentication was a success.');
        $this->assertEmailQueueCount(0);
        $this->assertEventFired(GpgJwtAuthenticator::JWT_AUTHENTICATION_AFTER_IDENTIFY);

        $challenge = json_decode($this->decryptChallenge($user, $this->_responseJsonBody->challenge));
        $this->assertSame(Router::url('/', true), $challenge->domain);
        $this->assertSame(GpgJwtAuthenticator::PROTOCOL_VERSION, $challenge->version);
        $this->assertIsString($challenge->access_token);
        $this->assertTrue(Validation::uuid($challenge->refresh_token));
        $this->assertSame($verifyToken, $challenge->verify_token);
        $this->assertSame(1, AuthenticationTokenFactory::find()->where(['token' => $challenge->refresh_token, 'user_id' => $user->id])->count());
        $this->assertSame(1, AuthenticationTokenFactory::find()->where(['token' => $challenge->verify_token, 'user_id' => $user->id])->count());

        // Assert login action log
        $this->assertOneActionLog();
        $this->assertActionLogExists([
            'user_id' => $user->id,
            'context' => 'POST /auth/jwt/login.json',
        ]);
    }

    public function testJwtLoginControllerTest_Consumed_Verify_Token()
    {
        $user = UserFactory::make()
            ->user()
            ->with('Gpgkeys', GpgkeyFactory::make()->validFingerprint())
            ->with(
                'AuthenticationTokens',
                AuthenticationTokenFactory::make()->type(AuthenticationToken::TYPE_VERIFY_TOKEN)
            )
            ->persist();

        $verifyToken = $user->authentication_tokens[0]->token;

        $this->postJson('/auth/jwt/login.json', [
            'user_id' => $user->id,
            'challenge' => $this->makeChallenge($user, $verifyToken),
        ]);

        $this->assertResponseError('The credentials are invalid.');
        $this->assertEmailQueueCount(1);
        $this->assertEmailIsInQueue([
            'email' => $user->username,
            'subject' => 'Authentication security alert',
            'template' => 'Passbolt/JwtAuthentication.User/jwt_attack',
        ]);
        $this->assertEmailInBatchContains('Verify token has been already used in the past.');

        // Assert login action log
        $this->assertOneActionLog();
        $this->assertActionLogExists([
            'user_id IS' => null,
            'context' => 'POST /auth/jwt/login.json',
        ]);
    }

    public function testJwtLoginControllerTest_Failure_On_Deleted_User()
    {
        $user = UserFactory::make()
            ->user()
            ->with('Gpgkeys', GpgkeyFactory::make()->validFingerprint())
            ->persist();

        $challenge = $this->makeChallenge($user, UuidFactory::uuid());

        // Delete this user
        $this->Users->softDelete($user);

        $this->postJson('/auth/jwt/login.json', [
            'user_id' => $user->id,
            'challenge' => $challenge,
        ]);

        $this->assertResponseError('The user does not exist or has been deleted.');
    }

    public function testJwtLoginControllerTest_Failure_On_Inactive_User()
    {
        $user = UserFactory::make()
            ->user()
            ->with('Gpgkeys', GpgkeyFactory::make()->validFingerprint())
            ->persist();

        $challenge = $this->makeChallenge($user, UuidFactory::uuid());

        // Deactivate this user
        $this->Users->patchEntity($user, ['active' => false]);
        $this->Users->saveOrFail($user);

        $this->postJson('/auth/jwt/login.json', [
            'user_id' => $user->id,
            'challenge' => $challenge,
        ]);

        $this->assertResponseError('The user does not exist or has been deleted.');
    }

    public function testJwtLoginControllerTest_FAILURE_CREDENTIALS_MISSING()
    {
        $this->postJson('/auth/jwt/login.json');
        $this->assertResponseError('The credentials are missing.');
    }

    public function testJwtLoginControllerTest_FAILURE_IDENTITY_NOT_FOUND()
    {
        $this->postJson('/auth/jwt/login.json', [
            'user_id' => UuidFactory::uuid(),
        ]);
        $this->assertResponseError('The user does not exist or is not active or has been deleted.');
    }

    public function testJwtLoginControllerTest_User_Is_Already_LoggedIn_In_Session()
    {
        $user = UserFactory::make()
            ->user()
            ->with('Gpgkeys', GpgkeyFactory::make()->validFingerprint())
            ->persist();

        $this->logInAs($user);
        $this->getJson('/auth/is-authenticated.json');
        $this->assertResponseOk();

        $this->postJson('/auth/jwt/login.json', [
            'user_id' => $user->id,
            'challenge' => $this->makeChallenge($user, UuidFactory::uuid()),
        ]);
        $this->assertResponseSuccess();
        $challenge = json_decode($this->decryptChallenge($user, $this->_responseJsonBody->challenge));
        $accessToken = $challenge->access_token;

        $this->setJwtTokenInHeader($accessToken);
        $this->getJson('/auth/is-authenticated.json');
        $this->assertResponseOk();

        $this->assertResponseOk('The authentication was a success.');
    }

    public function testSessionLoginWithJwtTokenInHeaderIsNotPermitted()
    {
        $this->createJwtTokenAndSetInHeader();
        $this->getJson('/auth/login.json');
        $this->assertResponseError('The route /auth/login is not permitted with JWT authentication.');
    }

    public function testJwtLoginController_Authentication_Should_Even_If_Valid_Access_Token_Set_In_Header()
    {
        $user = UserFactory::make()
            ->user()
            ->with('Gpgkeys', GpgkeyFactory::make()->validFingerprint())
            ->persist();

        $this->createJwtTokenAndSetInHeader($user->id);

        $this->postJson('/auth/jwt/login.json', [
            'user_id' => $user->id,
            'challenge' => 'Bar',
        ]);

        $this->assertBadRequestError('The credentials are invalid.');
    }
}

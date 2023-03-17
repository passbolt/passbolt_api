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
use App\Test\Factory\UserFactory;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Test\Lib\Utility\Gpg\GpgAdaSetupTrait;
use App\Utility\UuidFactory;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenRenewalService;
use Passbolt\JwtAuthentication\Test\Utility\JwtAuthenticationIntegrationTestCase;

/**
 * Class AuthRefreshTokenControllerTest
 */
class RefreshTokenControllerTest extends JwtAuthenticationIntegrationTestCase
{
    use EmailQueueTrait;
    use GpgAdaSetupTrait;
    use LocatorAwareTrait;

    /**
     * @var \App\Model\Table\AuthenticationTokensTable
     */
    protected $AuthenticationTokens;

    /**
     * @var \App\Model\Table\UsersTable
     */
    protected $Users;

    public function setUp(): void
    {
        parent::setUp();
        $this->AuthenticationTokens = $this->fetchTable('AuthenticationTokens');
        $this->Users = $this->fetchTable('Users');
    }

    public function testAuthRefreshTokenController_No_Data()
    {
        $this->postJson('/auth/jwt/refresh.json');
        $this->assertBadRequestError('The refresh token should be a valid UUID.');
    }

    public function testAuthRefreshTokenControllerIncompletePayload()
    {
        $this->postJson('/auth/jwt/refresh.json', ['user_id' => UuidFactory::uuid()]);
        $this->assertResponseError();
    }

    public function testAuthRefreshTokenController_InvalidRefreshTokenCookie()
    {
        $this->enableCsrfToken();
        $this->cookie(RefreshTokenRenewalService::REFRESH_TOKEN_COOKIE, 'foo');
        $this->postJson('/auth/jwt/refresh.json');
        $this->assertBadRequestError('The refresh token should be a valid UUID.');
    }

    public function testAuthRefreshTokenController_RandomRefreshTokenCookie()
    {
        $nAdmins = 3;
        UserFactory::make($nAdmins)->admin()->persist();
        $this->enableCsrfToken();
        $this->cookie(RefreshTokenRenewalService::REFRESH_TOKEN_COOKIE, UuidFactory::uuid());
        $this->postJson('/auth/jwt/refresh.json');
        $this->assertBadRequestError('No active refresh token matching the request could be found.');
        $this->assertEmailQueueCount(0);
    }

    public function dataProviderWithAndWithoutAccessToken(): array
    {
        return [
            [true], [false],
        ];
    }

    /**
     * @dataProvider dataProviderWithAndWithoutAccessToken
     * @param bool $withAccessToken With valid access token in header or no access token
     */
    public function testAuthRefreshTokenControllerWithValidRefreshTokenCookie(bool $withAccessToken)
    {
        $user = UserFactory::make()->user()->persist();
        if ($withAccessToken) {
            $this->createJwtTokenAndSetInHeader($user->id);
        }
        $oldRefreshToken = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_REFRESH_TOKEN)
            ->userId($user->id)
            ->persist()
            ->token;

        $this->cookie(RefreshTokenRenewalService::REFRESH_TOKEN_COOKIE, $oldRefreshToken);

        // This route, with cookie, should have CSRF protection
        $this->enableCsrfToken();
        $this->postJson('/auth/jwt/refresh.json');
        $this->assertResponseOk();

        $jwt = $this->_responseJsonBody->access_token;

        $newRefreshToken = AuthenticationTokenFactory::find()->where([
            'active' => true,
            'user_id' => $user->id,
            'type' => AuthenticationToken::TYPE_REFRESH_TOKEN,
        ])->firstOrFail()->get('token');
        $this->assertCookieIsSecure($newRefreshToken, 'refresh_token');
        // Get a fresh request
        $this->cleanup();
        $this->setJwtTokenInHeader($jwt);
        // Check that the delivered access token is valid.
        $this->getJson('/auth/is-authenticated.json');
        $this->assertResponseOk();
    }

    /**
     * @dataProvider dataProviderWithAndWithoutAccessToken
     * @param bool $withAccessToken With valid access token in header or no access token
     */
    public function testAuthRefreshTokenControllerWithValidPayload(bool $withAccessToken)
    {
        $user = UserFactory::make()->user()->persist();
        if ($withAccessToken) {
            $this->createJwtTokenAndSetInHeader($user->id);
        }
        $oldRefreshToken = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_REFRESH_TOKEN)
            ->userId($user->id)
            ->persist()
            ->token;

        $this->postJson('/auth/jwt/refresh.json', [
            'user_id' => $user->id,
            'refresh_token' => $oldRefreshToken,
        ]);

        $this->assertResponseSuccess();
        $jwt = $this->_responseJsonBody->access_token;

        // Get a fresh request
        $this->cleanup();
        $this->setJwtTokenInHeader($jwt);
        // Check that the delivered JWT is valid.
        $this->getJson('/auth/is-authenticated.json');
        $this->assertResponseOk();
    }

    public function testAuthRefreshTokenControllerWithValidRefreshTokenCookie_ButNoCsrf()
    {
        $user = UserFactory::make()->user()->persist();
        $oldRefreshToken = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_REFRESH_TOKEN)
            ->userId($user->id)
            ->persist()
            ->token;

        $this->cookie(RefreshTokenRenewalService::REFRESH_TOKEN_COOKIE, $oldRefreshToken);

        $this->postJson('/auth/jwt/refresh.json');

        $this->assertResponseError('Missing or incorrect CSRF cookie type.');
    }

    public function testAuthRefreshTokenControllerWithExpiredCookie()
    {
        $nAdmins = 3;
        /** @var array $admins */
        $admins = UserFactory::make($nAdmins)->admin()->persist();
        // We suppose one of the admins is hacked, and will check that 3 mails, and not 4 get sent.
        $user = $admins[0];
        $oldRefreshToken = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_REFRESH_TOKEN)
            ->userId($user->id)
            ->expired()
            ->persist()
            ->token;

        // Set the refresh key in the cookies
        $this->cookie(RefreshTokenRenewalService::REFRESH_TOKEN_COOKIE, $oldRefreshToken);

        $this->enableCsrfToken();
        $this->postJson('/auth/jwt/refresh.json');
        $this->assertBadRequestError('Expired refresh token provided.');
        $this->assertEmailQueueCount($nAdmins);
        $this->assertEmailIsInQueue([
            'email' => $user->username,
            'subject' => 'Authentication security alert',
            'template' => 'Passbolt/JwtAuthentication.User/jwt_attack',
        ]);
        foreach ($admins as $i => $admin) {
            if ($i === 0) {
                $this->assertEmailInBatchContains('Please get in touch with one of your administrators.');
                continue;
            }
            $this->assertEmailIsInQueue([
                'email' => $admin->username,
                'subject' => 'Authentication security alert',
                'template' => 'Passbolt/JwtAuthentication.Admin/jwt_attack',
            ]);
            $this->assertEmailInBatchContains('This is a potential security issue. Please investigate!', $i);
        }
    }

    public function testAuthRefreshTokenControllerWithExpiredPayload()
    {
        UserFactory::make(3)->admin()->persist();
        $admins = $this->Users->findAdmins()->toArray();
        $user = $admins[0];
        $nAdmins = count($admins);

        $userId = $user->id;
        $oldRefreshToken = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_REFRESH_TOKEN)
            ->userId($userId)
            ->expired()
            ->persist()
            ->token;

        $this->postJson('/auth/jwt/refresh.json', [
            'user_id' => $userId,
            'refresh_token' => $oldRefreshToken,
        ]);
        $this->assertBadRequestError('Expired refresh token provided.');

        $this->assertEmailQueueCount($nAdmins);
        $this->assertEmailIsInQueue([
            'email' => $user->username,
            'subject' => 'Authentication security alert',
            'template' => 'Passbolt/JwtAuthentication.User/jwt_attack',
        ]);
        foreach ($admins as $i => $admin) {
            if ($i === 0) {
                continue;
            }
            $this->assertEmailIsInQueue([
                'email' => $admin->username,
                'subject' => 'Authentication security alert',
                'template' => 'Passbolt/JwtAuthentication.Admin/jwt_attack',
            ]);
        }
    }

    public function testAuthRefreshTokenControllerWithConsumedCookie()
    {
        $nAdmins = 3;
        $admins = UserFactory::make($nAdmins)->admin()->persist();
        // We suppose one of the admins is hacked, and will check that 3 mails, and not 4 get sent.
        /** @var array $admins */
        $user = $admins[0];
        $oldRefreshToken = AuthenticationTokenFactory::make()
            ->inactive()
            ->type(AuthenticationToken::TYPE_REFRESH_TOKEN)
            ->userId($user->id)
            ->expired()
            ->persist()
            ->token;

        // Set the refresh key in the cookies
        $this->cookie(
            RefreshTokenRenewalService::REFRESH_TOKEN_COOKIE,
            $oldRefreshToken
        );

        $this->enableCsrfToken();
        $this->postJson('/auth/jwt/refresh.json');
        $this->assertBadRequestError('The refresh token provided was already used.');
        $this->assertEmailQueueCount($nAdmins);
        $this->assertEmailIsInQueue([
            'email' => $user->username,
            'subject' => 'Authentication security alert',
            'template' => 'Passbolt/JwtAuthentication.User/jwt_attack',
        ]);
        foreach ($admins as $i => $admin) {
            if ($i === 0) {
                continue;
            }
            $this->assertEmailIsInQueue([
                'email' => $admin->username,
                'subject' => 'Authentication security alert',
                'template' => 'Passbolt/JwtAuthentication.Admin/jwt_attack',
            ]);
        }
    }
}

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
 * @since         3.6.0
 */
namespace App\Test\TestCase\Controller\Setup;

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Utility\UuidFactory;
use Passbolt\EmailDigest\Test\Factory\EmailQueueFactory;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;

class RecoverAbortControllerTest extends AppIntegrationTestCase
{
    use EmailQueueTrait;
    use EmailNotificationSettingsTestTrait;

    public function testRecoverAbortController_Success(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $token = AuthenticationTokenFactory::make()
            ->type(AuthenticationToken::TYPE_RECOVER)
            ->userId($user->id)
            ->active()
            ->persist();
        $admin = UserFactory::make()->admin()->active()->persist();

        $url = '/setup/recover/abort/' . $user->id . '.json';
        $data = [
            'authentication_token' => [
                'token' => $token->token,
            ],
        ];
        $this->postJson($url, $data);
        $this->assertSuccess();

        $this->assertEquals(1, EmailQueueFactory::count());
        $this->assertEmailIsInQueue([
            'email' => $admin->username,
        ]);
    }

    public function testRecoverAbortController_Success_EmailDisabled(): void
    {
        $this->setEmailNotificationSetting('send.admin.user.recover.abort', false);

        $user = UserFactory::make()->user()->active()->persist();
        $token = AuthenticationTokenFactory::make()
            ->type(AuthenticationToken::TYPE_RECOVER)
            ->userId($user->id)
            ->active()
            ->persist();
        UserFactory::make()->admin()->active()->persist();

        $url = '/setup/recover/abort/' . $user->id . '.json';
        $data = [
            'authentication_token' => [
                'token' => $token->token,
            ],
        ];
        $this->postJson($url, $data);
        $this->assertSuccess();

        $this->assertEmailQueueIsEmpty();
    }

    public function testRecoverAbortController_Error_AuthenticationTokenWrongUser(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $admin = UserFactory::make()->admin()->active()->persist();
        $token = AuthenticationTokenFactory::make()
            ->type(AuthenticationToken::TYPE_RECOVER)
            ->userId($admin->id)
            ->active()
            ->persist();

        $url = '/setup/recover/abort/' . $user->id . '.json';
        $data = [
            'authentication_token' => [
                'token' => $token->token,
            ],
        ];
        $this->postJson($url, $data);
        $this->assertError(400, 'The authentication token is not valid.');
    }

    public function testRecoverAbortController_Error_AuthenticationTokenInactive(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $admin = UserFactory::make()->admin()->active()->persist();
        $token = AuthenticationTokenFactory::make()
            ->type(AuthenticationToken::TYPE_RECOVER)
            ->userId($user->id)
            ->inactive()
            ->persist();

        $url = '/setup/recover/abort/' . $user->id . '.json';
        $data = [
            'authentication_token' => [
                'token' => $token->token,
            ],
        ];
        $this->postJson($url, $data);
        $this->assertError(400, 'The authentication token is not valid.');
    }

    public function testRecoverAbortController_Error_AuthenticationTokenExpired(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $admin = UserFactory::make()->admin()->active()->persist();
        $token = AuthenticationTokenFactory::make()
            ->type(AuthenticationToken::TYPE_RECOVER)
            ->userId($user->id)
            ->expired()
            ->persist();

        $url = '/setup/recover/abort/' . $user->id . '.json';
        $data = [
            'authentication_token' => [
                'token' => $token->token,
            ],
        ];
        $this->postJson($url, $data);
        $this->assertError(400, 'The authentication token is not valid.');
    }

    public function testRecoverAbortController_Error_AuthenticationTokenType(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $admin = UserFactory::make()->admin()->active()->persist();
        $token = AuthenticationTokenFactory::make()
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->userId($user->id)
            ->active()
            ->persist();

        $url = '/setup/recover/abort/' . $user->id . '.json';
        $data = [
            'authentication_token' => [
                'token' => $token->token,
            ],
        ];
        $this->postJson($url, $data);
        $this->assertError(400, 'The authentication token is not valid.');
    }

    public function testRecoverAbortController_Error_InvalidUserId(): void
    {
        $url = '/setup/recover/complete/nope.json';
        $data = [];
        $this->postJson($url, $data);
        $this->assertError(400);
    }

    public function testRecoverAbortController_Error_InvalidUserToken(): void
    {
        $url = '/setup/recover/complete/' . UuidFactory::uuid() . '.json';
        $data = [];
        $this->postJson($url, $data);
        $this->assertError(400, 'The user does not exist');
    }

    public function testRecoverAbortController_Error_DeletedUser(): void
    {
        $user = UserFactory::make()->user()->deleted()->persist();
        $url = '/setup/recover/complete/' . $user->id . '.json';
        $this->postJson($url, []);
        $this->assertError(400, 'The user does not exist');
    }

    public function testRecoverAbortController_Error_InactiveUser(): void
    {
        $user = UserFactory::make()->user()->inactive()->persist();
        $url = '/setup/recover/complete/' . $user->id . '.json';
        $this->postJson($url, []);
        $this->assertError(400, 'The user does not exist');
    }

    /**
     * Check that calling url without JSON extension throws a 404
     */
    public function testRecoverAbortController_Error_NotJson(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $token = AuthenticationTokenFactory::make()
            ->type(AuthenticationToken::TYPE_RECOVER)
            ->userId($user->id)
            ->active()
            ->persist();

        $url = '/setup/recover/abort/' . $user->id;
        $data = [
            'authentication_token' => [
                'token' => $token->token,
            ],
        ];
        $this->post($url, $data);
        $this->assertResponseCode(404);
    }
}

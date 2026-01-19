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
 * @since         2.0.0
 */
namespace App\Test\TestCase\Controller\Users;

use App\Controller\Users\UsersRecoverController;
use App\Model\Entity\User;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use Cake\Core\Configure;
use Cake\I18n\Date;
use Cake\Routing\Router;
use Passbolt\EmailDigest\Test\Factory\EmailQueueFactory;
use Passbolt\SelfRegistration\SelfRegistrationPlugin;

class UsersRecoverControllerTest extends AppIntegrationTestCase
{
    use EmailQueueTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(SelfRegistrationPlugin::class);
    }

    public function testUsersRecoverController_Get_Redirect(): void
    {
        $this->get('/recover');
        $this->assertResponseCode(301);
    }

    public function testUsersRecoverController_Get_Success(): void
    {
        $this->get('/users/recover');
        $this->assertResponseOk();
    }

    public function testUsersRecoverController_Get_JsonSuccess(): void
    {
        $this->getJson('/users/recover.json');
        $this->assertSuccess();
    }

    public function testUsersRecoverController_Post_Errors(): void
    {
        $fails = [
            'cannot recover with username that is empty' => [
                'form-data' => ['username' => ''],
                'error' => 'Please provide a valid email address.',
            ],
            'cannot recover with username is not an email' => [
                'form-data' => ['username' => 'notanemail'],
                'error' => 'Please provide a valid email address.',
            ],
        ];
        foreach ($fails as $case => $data) {
            $this->postJson('/users/recover.json', $data['form-data']);
            $result = $this->_getBodyAsString();
            $this->assertStringContainsString($data['error'], $result, 'Error case not respected: ' . $case);
        }
    }

    public function testUsersRecoverController_Post_Error_UserDeleted(): void
    {
        $user = UserFactory::make()->deleted()->persist();
        $data = ['username' => $user->username];
        $this->postJson('/users/recover.json', $data);
        $this->assertSuccess();
    }

    public function testUsersRecoverController_Post_Error_UserNotExist(): void
    {
        $data = ['username' => 'notauser@passbolt.com'];
        $this->postJson('/users/recover.json', $data);
        $this->assertSuccess();
    }

    public function testUsersRecoverController_Post_Error_UserDisabled(): void
    {
        $user = UserFactory::make()->disabled()->persist();
        $data = ['username' => $user->username];
        $this->postJson('/users/recover.json', $data);
        $this->assertSuccess();
    }

    public function testUsersRecoverController_Post_FalseSuccess_UserNotExist_PreventEnum(): void
    {
        Configure::write(UsersRecoverController::PREVENT_EMAIL_ENUMERATION_CONFIG_KEY, true);
        $data = ['username' => 'notauser@passbolt.com'];
        $this->postJson('/users/recover.json', $data);
        $this->assertResponseCode(200);
    }

    public function testUsersRecoverController_Post_Success_Active_User(): void
    {
        $user = UserFactory::make()->active()->persist();
        $username = $user->username;
        $this->postJson('/users/recover.json', compact('username'));
        $this->assertResponseSuccess('Recovery process started, check your email.');
        $this->assertSuccess();

        $this->assertEmailIsInQueue(['email' => $username, 'template' => 'AN/user_recover']);
        $this->assertEmailQueueCount(1);
        /** @var \App\Model\Entity\AuthenticationToken $token */
        $token = AuthenticationTokenFactory::find()->firstOrFail();
        $url = Router::url('/setup/recover/start/' . $token->user_id . '/' . $token->token, true);
        $this->assertEmailInBatchContains($url, $username);
    }

    public function testUsersRecoverController_Post_Success_User_That_Has_Not_Completed_Setup(): void
    {
        $user = UserFactory::make()->inactive()->persist();
        $username = $user->username;
        $this->postJson('/users/recover.json', compact('username'));
        $this->assertResponseSuccess('Recovery process started, check your email.');
        $this->assertSuccess();

        $this->assertEmailIsInQueue(['email' => $username, 'template' => 'AN/user_register_self']);
        $this->assertEmailQueueCount(1);
        /** @var \App\Model\Entity\AuthenticationToken $token */
        $token = AuthenticationTokenFactory::find()->firstOrFail();
        $url = Router::url('/setup/start/' . $token->user_id . '/' . $token->token, true);
        $this->assertEmailInBatchContains($url, $username);
    }

    public function testUsersRecoverController_Post_JsonError(): void
    {
        $fails = [
            'cannot recover with username that is empty' => [
                'form-data' => ['username' => ''],
                'error' => 'Please provide a valid email address.',
            ],
            'cannot recover with username is not an email' => [
                'form-data' => ['username' => 'notanemail'],
                'error' => 'Please provide a valid email address.',
            ],
        ];
        foreach ($fails as $data) {
            $this->postJson('/users/recover.json', $data['form-data']);
            $this->assertError(400, $data['error']);
        }
    }

    public function testUsersRecoverController_Post_JsonError_MissingCsrfTokenError(): void
    {
        $this->disableCsrfToken();
        $this->post('/users/recover.json');
        $this->assertResponseCode(403);
    }

    public function testUsersRecoverController_Post_JsonSuccess_For_User_With_Avatar(): void
    {
        $user = UserFactory::make()->withAvatar()
            ->user()
            ->with('Profiles', [
                'first_name' => 'Jane',
                'last_name' => 'Doe',
            ])
            ->persist();

        $this->postJson('/users/recover.json', ['username' => $user->username]);
        $this->assertSuccess();

        $this->assertEmailIsInQueue([
            'email' => $user->username,
            'subject' => "Your account recovery, {$user->profile->first_name}!",
            'template' => 'AN/user_recover',
        ]);
        $this->assertEmailInBatchContains('Jane Doe');
    }

    public function testUsersRecoverController_Post_JsonSuccess_CaseLostPassphrase(): void
    {
        $user = UserFactory::make()->withAvatar()->user()->persist();

        $this->postJson('/users/recover.json', ['username' => $user->username, 'case' => 'lost-passphrase']);
        $this->assertSuccess();

        $this->assertEmailIsInQueue([
            'email' => $user->username,
            'subject' => "Your account recovery, {$user->profile->first_name}!",
            'template' => 'AN/user_recover',
        ]);

        $email = EmailQueueFactory::find()->firstOrFail();
        $this->assertTextEquals('lost-passphrase', $email->template_vars['body']['case']);
    }

    public function testUsersRecoverController_Post_JsonSuccess_CaseDefault(): void
    {
        $user = UserFactory::make()->withAvatar()->user()->setField('created', Date::yesterday())->persist();

        $this->postJson('/users/recover.json', ['username' => $user->username]);
        $this->assertSuccess();

        $this->assertUserRecoverEmail($user);
    }

    public function testUsersRecoverController_Post_JsonSuccess_CaseDefault2(): void
    {
        $user = UserFactory::make()->withAvatar()->user()->setField('created', Date::yesterday())->persist();

        $this->postJson('/users/recover.json', ['username' => $user->username, 'case' => 'default']);
        $this->assertSuccess();

        $this->assertUserRecoverEmail($user);
    }

    private function assertUserRecoverEmail(User $user): void
    {
        $this->assertEmailIsInQueue([
            'email' => $user->username,
            'subject' => "Your account recovery, {$user->profile->first_name}!",
            'template' => 'AN/user_recover',
        ]);

        $email = EmailQueueFactory::find()->firstOrFail();
        $this->assertTextEquals('default', $email->template_vars['body']['case']);

        // Assert that the date displayed is now
        $this->assertEmailInBatchContains(Date::now()->toFormattedDateString());
    }

    public function testUsersRecoverController_Post_JsonSuccess_CaseError(): void
    {
        $user = UserFactory::make()->withAvatar()->user()->persist();
        $this->postJson('/users/recover.json', ['username' => $user->username, 'case' => 'nope']);
        $this->assertError(400);
    }
}

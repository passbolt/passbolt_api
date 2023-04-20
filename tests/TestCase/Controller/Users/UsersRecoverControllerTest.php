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

use App\Model\Entity\User;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use Cake\I18n\FrozenDate;
use Passbolt\EmailDigest\Test\Factory\EmailQueueFactory;

class UsersRecoverControllerTest extends AppIntegrationTestCase
{
    use EmailQueueTrait;

    public $fixtures = [
        'app.Base/Users', 'app.Base/Roles', 'app.Base/Profiles',
    ];

    public $fails = [
        'cannot recover with username that is empty' => [
            'form-data' => ['username' => ''],
            'error' => 'Please provide a valid email address.',
        ],
        'cannot recover with username is not an email' => [
            'form-data' => ['username' => 'notanemail'],
            'error' => 'Please provide a valid email address.',
        ],
    ];

    public function dataProviderForPostSuccess(): array
    {
        return [
            ['can recover an active user' => 'ada@passbolt.com', 'email template' => 'AN/user_recover'],
            ['can recover a user that has not completed setup' => 'ruth@passbolt.com', 'email template' => 'AN/user_register_self'],
        ];
    }

    public function testUsersRecoverController_Get_Redirect()
    {
        $this->get('/recover');
        $this->assertResponseCode(301);
    }

    public function testUsersRecoverController_Get_Success()
    {
        $this->get('/users/recover');
        $this->assertResponseOk();
    }

    public function testUsersRecoverController_Get_JsonSuccess()
    {
        $this->getJson('/users/recover.json');
        $this->assertSuccess();
    }

    public function testUsersRecoverController_Post_Errors()
    {
        foreach ($this->fails as $case => $data) {
            $this->postJson('/users/recover.json', $data['form-data']);
            $result = $this->_getBodyAsString();
            $this->assertStringContainsString($data['error'], $result, 'Error case not respected: ' . $case);
        }
    }

    public function testUsersRecoverController_Post_Error_UserDeleted()
    {
        $data = ['username' => 'sofia@passbolt.com'];
        $error = 'This user does not exist or has been deleted.';
        $this->postJson('/users/recover.json', $data);
        $this->assertResponseCode(404);
        $result = $this->_getBodyAsString();
        $this->assertStringContainsString($error, $result);
    }

    public function testUsersRecoverController_Post_Error_UserNotExist()
    {
        $data = ['username' => 'notauser@passbolt.com'];
        $error = 'This user does not exist or has been deleted.';
        $this->postJson('/users/recover.json', $data);
        $this->assertResponseCode(404);
        $result = $this->_getBodyAsString();
        $this->assertStringContainsString($error, $result);
    }

    /**
     * @dataProvider dataProviderForPostSuccess
     */
    public function testUsersRecoverController_Post_Success(string $username, string $emailTemplate)
    {
        $this->postJson('/users/recover.json', compact('username'));
        $this->assertResponseSuccess('Recovery process started, check your email.');
        $this->assertSuccess();

        $this->assertEmailIsInQueue(['email' => $username, 'template' => $emailTemplate]);
        $this->assertEmailQueueCount(1);
    }

    public function testUsersRecoverController_Post_JsonError()
    {
        foreach ($this->fails as $case => $data) {
            $this->postJson('/users/recover.json', $data['form-data']);
            $this->assertError(400, $data['error']);
        }
    }

    public function testUsersRecoverController_Post_JsonError_MissingCsrfTokenError()
    {
        $this->disableCsrfToken();
        $this->post('/users/recover.json');
        $this->assertResponseCode(403);
    }

    public function testUsersRecoverController_Post_JsonSuccess_For_User_With_Avatar()
    {
        $user = UserFactory::make()->withAvatar()->user()->persist();

        $this->postJson('/users/recover.json', ['username' => $user->username]);
        $this->assertSuccess();

        $this->assertEmailIsInQueue([
            'email' => $user->username,
            'subject' => "Your account recovery, {$user->profile->first_name}!",
            'template' => 'AN/user_recover',
        ]);
        $avatarTitle = htmlentities($user['profile']['first_name'] . ' ' . $user['profile']['last_name']);
        $this->assertEmailInBatchContains($avatarTitle);
    }

    public function testUsersRecoverController_Post_JsonSuccess_CaseLostPassphrase()
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

    public function testUsersRecoverController_Post_JsonSuccess_CaseDefault()
    {
        $user = UserFactory::make()->withAvatar()->user()->setField('created', FrozenDate::yesterday())->persist();

        $this->postJson('/users/recover.json', ['username' => $user->username]);
        $this->assertSuccess();

        $this->assertUserRecoverEmail($user);
    }

    public function testUsersRecoverController_Post_JsonSuccess_CaseDefault2()
    {
        $user = UserFactory::make()->withAvatar()->user()->setField('created', FrozenDate::yesterday())->persist();

        $this->postJson('/users/recover.json', ['username' => $user->username, 'case' => 'default']);
        $this->assertSuccess();

        $this->assertUserRecoverEmail($user);
    }

    private function assertUserRecoverEmail(User $user)
    {
        $this->assertEmailIsInQueue([
            'email' => $user->username,
            'subject' => "Your account recovery, {$user->profile->first_name}!",
            'template' => 'AN/user_recover',
        ]);

        $email = EmailQueueFactory::find()->firstOrFail();
        $this->assertTextEquals('default', $email->template_vars['body']['case']);

        // Assert that the date displayed is now
        $this->assertEmailInBatchContains(FrozenDate::now()->toFormattedDateString());
    }

    public function testUsersRecoverController_Post_JsonSuccess_CaseError()
    {
        $user = UserFactory::make()->withAvatar()->user()->persist();
        $this->postJson('/users/recover.json', ['username' => $user->username, 'case' => 'nope']);
        $this->assertError(400);
    }
}

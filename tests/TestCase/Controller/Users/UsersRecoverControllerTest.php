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

use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;

class UsersRecoverControllerTest extends AppIntegrationTestCase
{
    use EmailQueueTrait;

    public $autoFixtures = false;

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

    public function testRecoverGetRedirect()
    {
        $this->get('/recover');
        $this->assertResponseCode(301);
    }

    public function testRecoverGetSuccess()
    {
        $this->get('/users/recover');
        $this->assertResponseOk();
    }

    public function testRecoverGetJsonSuccess()
    {
        $this->getJson('/users/recover.json');
        $this->assertSuccess();
    }

    public function testRecoverPostErrors()
    {
        $this->loadFixtures();

        foreach ($this->fails as $case => $data) {
            $this->postJson('/users/recover.json', $data['form-data']);
            $result = $this->_getBodyAsString();
            $this->assertStringContainsString($data['error'], $result, 'Error case not respected: ' . $case);
        }
    }

    public function testRecoverPostError_UserDeleted()
    {
        $this->loadFixtures();

        $data = ['username' => 'sofia@passbolt.com'];
        $error = 'This user does not exist or has been deleted.';
        $this->postJson('/users/recover.json', $data);
        $this->assertResponseCode(404);
        $result = $this->_getBodyAsString();
        $this->assertStringContainsString($error, $result);
    }

    public function testRecoverPostError_UserNotExist()
    {
        $this->loadFixtures();

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
    public function testRecoverPostSuccess(string $username, string $emailTemplate)
    {
        $this->loadFixtures();

        $this->postJson('/users/recover.json', compact('username'));
        $result = $this->_getBodyAsString();
        $this->assertResponseSuccess('Recovery process started, check your email.');
        $this->assertSuccess();

        $this->assertEmailIsInQueue(['email' => $username, 'template' => $emailTemplate]);
        $this->assertEmailQueueCount(1);
    }

    public function testRecoverPostJsonError()
    {
        foreach ($this->fails as $case => $data) {
            $this->postJson('/users/recover.json', $data['form-data']);
            $this->assertError(400, $data['error']);
        }
    }

    public function testRecoverPostJsonError_MissingCsrfTokenError()
    {
        $this->disableCsrfToken();
        $this->post('/users/recover.json?api-version=v2');
        $this->assertResponseCode(403);
    }

    public function testRecoverPostJsonSuccess_For_User_With_Avatar()
    {
        $user = UserFactory::make()->withAvatar()->user()->persist();

        $this->postJson('/users/recover.json?api-version=v2', ['username' => $user->username]);
        $this->assertSuccess();

        $this->assertEmailIsInQueue([
            'email' => $user->username,
            'subject' => "Your account recovery, {$user->profile->first_name}!",
            'template' => 'AN/user_recover',
        ]);
    }
}

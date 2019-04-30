<?php
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

use App\Test\Lib\AppIntegrationTestCase;

class UsersRecoverControllerTest extends AppIntegrationTestCase
{
    public $fixtures = [
        'app.Base/Users', 'app.Base/Roles', 'app.Base/Profiles', 'app.Base/AuthenticationTokens',
        'app.Base/Avatars', 'app.Base/EmailQueue'
    ];

    public $fails = [
        'cannot recover with username that is empty' => [
            'form-data' => ['username' => ''],
            'error' => 'Please provide a valid email address.'
        ],
        'cannot recover with username is not an email' => [
            'form-data' => ['username' => 'notanemail'],
            'error' => 'Please provide a valid email address.'
        ],
        'cannot recover a user that does not exist' => [
            'form-data' => ['username' => 'notauser@passbolt.com'],
            'error' => 'This user does not exist or has been deleted.'
        ],
        'cannot recover a user that has been deleted' => [
            'form-data' => ['username' => 'sofia@passbolt.com'],
            'error' => 'This user does not exist or has been deleted.'
        ],
    ];

    public $successes = [
        'can recover an active user' => [
            'form-data' => ['username' => 'ada@passbolt.com'],
        ],
        'can recover a user that has not completed setup' => [
            'form-data' => ['username' => 'ruth@passbolt.com'],
        ],
        'legacy form data' => [
            'form-data' => ['User' => ['username' => 'ruth@passbolt.com']],
        ],
    ];

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
        foreach ($this->fails as $case => $data) {
            $this->post('/users/recover', $data['form-data']);
            $result = ($this->_getBodyAsString());
            $this->assertContains($data['error'], $result, 'Error case not respected: ' . $case);
        }
    }

    public function testRecoverPostError_MissingCsrfTokenError()
    {
        $this->disableCsrfToken();
        $this->post('/users/recover');
        $this->assertResponseCode(403);
        $result = ($this->_getBodyAsString());
        $this->assertContains('Missing CSRF token cookie', $result);
    }

    public function testRecoverPostSuccess()
    {
        foreach ($this->successes as $case => $data) {
            $this->post('/users/recover', $data['form-data']);
            $result = ($this->_getBodyAsString());
            $success = 'Email sent!';
            $this->assertContains($success, $result, 'Success case not respected: ' . $case);
        }
    }

    public function testRecoverPostJsonError()
    {
        foreach ($this->fails as $case => $data) {
            $this->postJson('/users/recover.json', $data['form-data']);
            $this->assertError(400, $data['error']);
        }
    }

    public function testRecoverPostJsonSuccess()
    {
        foreach ($this->successes as $case => $data) {
            $this->postJson('/users/recover.json', $data['form-data']);
            $this->assertSuccess();
        }
    }

    public function testRecoverPostJsonError_MissingCsrfTokenError()
    {
        $this->disableCsrfToken();
        $this->post('/users/recover.json?api-version=v1');
        $this->assertResponseCode(403);
    }
}

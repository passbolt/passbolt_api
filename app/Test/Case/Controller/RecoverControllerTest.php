<?php
/**
 * Users Controller Tests
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('AppController', 'Controller');
App::uses('UsersController', 'Controller');
App::uses('User', 'Model');
App::uses('Role', 'Model');

class RecoverControllerTest extends ControllerTestCase
{

    public $fixtures = array(
        'app.user',
        'app.role',
        'app.email_queue',
        'app.user_agent',
        'app.profile',
        'app.file_storage',
        'app.authenticationToken',
        'app.controller_log',
    );

    public function setUp()
    {
        parent::setUp();
        $this->User = Common::getModel('User');
        $this->Gpgkey = Common::getModel('Gpgkey');
    }

    public function tearDown()
    {
        parent::tearDown();
    }

/**
 * Test recovery with no username
 *
 * @return void
 */
    public function testRecoverNoUsername() {
        $data = [
            'User' => [
                'username' => '',
            ]
        ];
        $this->setExpectedException('BadRequestException', 'Please provide an email address.');
        $this->testAction('/recover.json', ['return' => 'view', 'method' => 'post', 'data' => $data], true);
    }

/**
 * Test recovery with invalid email
 *
 * @return void
 */
    public function testRecoverInvalidUsername() {
        $data = [
            'User' => [
                'username' => 'test@test',
            ]
        ];
        $this->setExpectedException('BadRequestException', 'Please provide a valid email address.');
        $this->testAction('/recover.json', ['return' => 'view', 'method' => 'post', 'data' => $data], true);
    }

/**
 * Test recovery with invalid email (NOT JSON)
 *
 * @return void
 */
    public function testRecoverInvalidUsernameNotJSON() {
        $data = [
            'User' => [
                'username' => 'test@test',
            ]
        ];
        $view = $this->testAction('/recover', ['return' => 'view', 'method' => 'post', 'data' => $data], true);
        $this->assertContains('Please provide a valid email address.', $view);
    }

/**
 * Test recovery with non existing user
 *
 * @return void
 */
    public function testRecoverUserDoesNotExist() {
        $data = [
            'User' => [
                'username' => 'not_an_existing_email@passbolt.com',
            ]
        ];
        $this->setExpectedException('BadRequestException', 'This user does not exist. Please register and complete the setup first.');
        $this->testAction('/recover.json', ['return' => 'view', 'method' => 'post', 'data' => $data], true);
    }

/**
 * Test recovery with deleted user
 *
 * @return void
 */
    public function testRecoverNonActiveUser() {
        $data = [
            'User' => [
                'username' => 'orna@passbolt.com',
            ]
        ];
        $this->setExpectedException('BadRequestException', 'This user is not active. Please complete the setup first.');
        $this->testAction('/recover.json', ['return' => 'view', 'method' => 'post', 'data' => $data], true);
    }

/**
 * Test recovery with non active user
 *
 * @return void
 */
    public function testRecoverDeletedUser() {
        $data = [
            'User' => [
                'username' => 'ruth@passbolt.com',
            ]
        ];
        $this->setExpectedException('BadRequestException', 'This user has been deleted. Please contact your administrator.');
        $this->testAction('/recover.json', ['return' => 'view', 'method' => 'post', 'data' => $data], true);
    }

/**
 * Test recovery with non active user
 *
 * @return void
 */
    public function testRecoverValidUser() {
        $data = [
            'User' => [
                'username' => 'ada@passbolt.com',
            ]
        ];
        $result = $this->testAction('/recover', ['return' => 'view', 'method' => 'post', 'data' => $data], true);
        // empty because of redirection
        $this->assertEmpty($result);
    }

/**
 * Test recovery with non active user
 *
 * @return void
 */
    public function testThankyou() {
        $result = $this->testAction('/recover/thankyou', ['return' => 'view', 'method' => 'GET'], true);
        $this->assertContains('Email sent', $result);
    }

}
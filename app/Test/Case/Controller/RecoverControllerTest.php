<?php
/**
 * Users Controller Tests
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
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
 * Test recovery with non active user
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
 * Test recovery with deleted user
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
 * Test recovery with a user that has a deleted account with the same username.
 *
 * @see GITHUB-33 (https://github.com/passbolt/passbolt_api/issues/33)
 *
 * @throws Exception if data set is not correct.
 *
 * @return void
 */
	public function testRecoverPreviouslyDeletedUser() {
		$username = 'ada@passbolt.com';

		// Delete the existing user with given username.
		$u = $this->User->findByUsername($username);
		$Profile = Common::getModel('Profile');
		$p = $Profile->findByUserId($u['User']['id']);
		if (!$u) {
			throw new Exception('user ruth should have been found in the data set');
		}

		// Delete Ada in database.
		$this->User->id = $u['User']['id'];
		$this->User->saveField('deleted', 1);

		// Register a new user with the same username.
		$register = $this->User->registerUser([
			'User' => [
				'username' => $username,
			],
			'Profile' => [
				'first_name' => $p['Profile']['first_name'],
				'last_name' => $p['Profile']['last_name'],
			]
		]);

		// Activate the user.
		$this->User->saveField('active', 1);

		// Test a recovery.
		$data = [
			'User' => [
				'username' => $username,
			]
		];
		$result = $this->testAction('/recover.json', ['return' => 'view', 'method' => 'post', 'data' => $data], true);
		// empty because of redirection
		$this->assertEmpty($result);
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
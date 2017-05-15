<?php
/**
 * InstallShellTest Test file
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

App::uses('ShellDispatcher', 'Console');
App::uses('ConsoleOutput', 'Console');
App::uses('ConsoleInput', 'Console');
App::uses('Shell', 'Console');
App::uses('CakeSchema', 'Model');
App::uses('SchemaShell', 'Console/Command');
App::uses('InstallShell', 'Console/Command');
App::uses('PassboltShell', 'Console/Command');
App::uses('RegisterUserTask', 'Console/Command/Task');
App::uses('User', 'Model');
App::uses('Role', 'Model');

/**
 * Class TestStringOutput
 *
 * @package       Cake.Test.Case.Console.Command
 */
class TestStringOutput extends ConsoleOutput {

	public $output = '';

	protected function _write($message) {
		$this->output .= $message;
	}

}

/**
 * InstallShellTest class
 *
 * @package      app.Test.Case.Console.Command.Task
 */
class RegisterUserTaskTest extends CakeTestCase {

	public $fixtures = array(
		'app.email_queue',
		'app.user',
		'app.group',
		'app.groups_user',
		'app.role',
		'app.profile',
		'app.file_storage',
		'app.gpgkey',
		'app.authenticationToken',
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->User = ClassRegistry::init('User');
		$this->Role = ClassRegistry::init('Role');

		$out = new TestStringOutput();
		$in = $this->getMock('ConsoleInput', [], [], '', false);

		$this->InstallShell = $this->getMock(
			'InstallShell',
			['in', 'hr', 'error', 'err', '_stop'],
			[$out, $out, $in]
		);
		$this->PassboltShell = $this->getMock(
			'PassboltShell',
			['in', 'hr', 'error', 'err', '_stop'],
			[$out, $out, $in]
		);
		$this->RegisterUserTask = $this->getMock(
			'RegisterUserTask',
			['in', 'hr', 'error', 'err', '_stop'],
			[$out, $out, $in]
		);

		$this->InstallShell->params['no-admin'] = true;
		$this->InstallShell->params['quiet'] = true;
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();
	}

/**
 * Test error register a user without username
 *
 * @return void
 */
	public function testRegisterUserWithoutUsername() {
		$this->InstallShell->params['no-admin'] = true;
		$this->InstallShell->params['connection'] = 'test';
		$this->InstallShell->main();
		$this->RegisterUserTask->execute();
		$output = $this->RegisterUserTask->stdout->output;
		$expected = "/A username is required/";
		$this->assertRegExp($expected, $output);
	}

/**
 * Test error register a user with wrong username
 *
 * @return void
 */
	public function testRegisterUserWrongUsername() {
		$this->InstallShell->params['no-admin'] = true;
		$this->InstallShell->params['connection'] = 'test';
		$this->InstallShell->main();
		$this->RegisterUserTask->params['username'] = 'test';
		$this->RegisterUserTask->execute();
		$output = $this->RegisterUserTask->stdout->output;
		$expected = "/The username should be a valid email address/";
		$this->assertRegExp($expected, $output);
	}

/**
 * Test error register a user without first name
 *
 * @return void
 */
	public function testRegisterUserWithoutFirstName() {
		$this->InstallShell->params['no-admin'] = true;
		$this->InstallShell->params['connection'] = 'test';
		$this->InstallShell->main();
		$this->RegisterUserTask->params['username'] = 'test@passbolt.com';
		$this->RegisterUserTask->params['lastname'] = 'lastname';
		$this->RegisterUserTask->execute();
		$output = $this->RegisterUserTask->stdout->output;
		$expected = "/First name should only contain alphabets and the special characters/";
		$this->assertRegExp($expected, $output);
	}

/**
 * Test error register a user with wrong first name
 *
 * @return void
 */
	public function testRegisterUserWrongFirstName() {
		$this->InstallShell->params['no-admin'] = true;
		$this->InstallShell->params['connection'] = 'test';
		$this->InstallShell->main();
		$this->RegisterUserTask->params['username'] = 'test@passbolt.com';
		$this->RegisterUserTask->params['firstname'] = 'firstname 2 (-)';
		$this->RegisterUserTask->params['lastname'] = 'lastname';
		$this->RegisterUserTask->execute();
		$output = $this->RegisterUserTask->stdout->output;
		$expected = "/First name should only contain alphabets and the special characters/";
		$this->assertRegExp($expected, $output);
	}

/**
 * Test error register a user without last name
 *
 * @return void
 */
	public function testRegisterUserWithoutLastName() {
		$this->InstallShell->params['no-admin'] = true;
		$this->InstallShell->params['connection'] = 'test';
		$this->InstallShell->main();
		$this->RegisterUserTask->params['username'] = 'test@passbolt.com';
		$this->RegisterUserTask->params['firstname'] = 'firstname';
		$this->RegisterUserTask->execute();
		$output = $this->RegisterUserTask->stdout->output;
		$expected = "/Last name should only contain alphabets and the special characters/";
		$this->assertRegExp($expected, $output);
	}

/**
 * Test error register a user without last name
 *
 * @return void
 */
	public function testRegisterUserWrongLastName() {
		$this->InstallShell->params['no-admin'] = true;
		$this->InstallShell->params['connection'] = 'test';
		$this->InstallShell->main();
		$this->RegisterUserTask->params['username'] = 'test@passbolt.com';
		$this->RegisterUserTask->params['firstname'] = 'firstname';
		$this->RegisterUserTask->params['lastname'] = 'lastname 2 (-)';
		$this->RegisterUserTask->execute();
		$output = $this->RegisterUserTask->stdout->output;
		$expected = "/Last name should only contain alphabets and the special characters/";
		$this->assertRegExp($expected, $output);
	}

/**
 * Test register user
 *
 * @return void
 */
	public function testRegisterUser() {
		// Retrieve the user role
		$findRoleParams = [
			'conditions' => [
				'name' => Role::USER
			],
		];
		$roleUser = $this->Role->find('first', $findRoleParams);

		// Install passbolt & register a user
		$this->InstallShell->params['no-admin'] = true;
		$this->InstallShell->params['connection'] = 'test';
		$this->InstallShell->main();
		$this->RegisterUserTask->params['username'] = 'test@passbolt.com';
		$this->RegisterUserTask->params['first-name'] = 'firstname';
		$this->RegisterUserTask->params['last-name'] = 'lastname';
		$this->RegisterUserTask->execute();

		// Check the command output
		$output = $this->RegisterUserTask->stdout->output;
		$expected = "/The user has been registered with success/";
		$this->assertRegExp($expected, $output);

		// Retrieve the registered user and check that it matches the data given in parameter
		$findUserParams = [
			'conditions' => [
				'username' => 'test@passbolt.com'
			],
			'fields' => [
				'User.*',
				'Profile.*'
			],
			'contain' => ['Profile']
		];
		$user = $this->User->find('first', $findUserParams);
		$this->assertEquals($user['User']['username'], $this->RegisterUserTask->params['username']);
		$this->assertEquals($user['User']['role_id'], $roleUser['Role']['id']);
		$this->assertEquals($user['Profile']['first_name'], $this->RegisterUserTask->params['first-name']);
		$this->assertEquals($user['Profile']['last_name'], $this->RegisterUserTask->params['last-name']);
	}

/**
 * Test register admin
 *
 * @return void
 */
	public function testRegisterAdmin() {
		// Retrieve the admin role
		$findRoleParams = [
			'conditions' => [
				'name' => Role::ADMIN
			],
		];
		$roleUser = $this->Role->find('first', $findRoleParams);

		// Install passbolt & register an admin
		$this->InstallShell->params['no-admin'] = true;
		$this->InstallShell->params['connection'] = 'test';
		$this->InstallShell->main();
		$this->RegisterUserTask->params['username'] = 'test@passbolt.com';
		$this->RegisterUserTask->params['first-name'] = 'firstname';
		$this->RegisterUserTask->params['last-name'] = 'lastname';
		$this->RegisterUserTask->params['role'] = 'admin';
		$this->RegisterUserTask->execute();

		// Check the command output
		$output = $this->RegisterUserTask->stdout->output;
		$expected = "/The user has been registered with success/";
		$this->assertRegExp($expected, $output);

		// Retrieve the registered user and check that it matches the data given in parameter
		$findUserParams = [
			'conditions' => [
				'username' => 'test@passbolt.com'
			],
			'fields' => [
				'User.*',
				'Profile.*'
			],
			'contain' => ['Profile']
		];
		$user = $this->User->find('first', $findUserParams);
		$this->assertEquals($user['User']['username'], $this->RegisterUserTask->params['username']);
		$this->assertEquals($user['User']['role_id'], $roleUser['Role']['id']);
		$this->assertEquals($user['Profile']['first_name'], $this->RegisterUserTask->params['first-name']);
		$this->assertEquals($user['Profile']['last_name'], $this->RegisterUserTask->params['last-name']);
	}

/**
 * Test error register a user with an existing username
 *
 * @return void
 */
	public function testRegisterUserExistingUsername() {
		$this->InstallShell->params['no-admin'] = true;
		$this->InstallShell->params['connection'] = 'test';
		$this->InstallShell->main();
		$this->RegisterUserTask->params['username'] = 'test@passbolt.com';
		$this->RegisterUserTask->params['first-name'] = 'firstname';
		$this->RegisterUserTask->params['last-name'] = 'lastname';
		$this->RegisterUserTask->params['role'] = 'admin';
		$this->RegisterUserTask->execute();
		$this->RegisterUserTask->execute();

		// Check the command output
		$output = $this->RegisterUserTask->stdout->output;
		$expected = "/The username has already been taken/";
		$this->assertRegExp($expected, $output);
	}

}

<?php
/**
 * User Model Test
 *
 * @copyright     Copyright 2012, Passbolt.com
 * @package       app.Test.Case.Model.UserTest
 * @since         version 2.12.7
 * @license       http://www.passbolt.com/license
 */
App::uses('User', 'Model');

// Uses sessions
// App::uses('CakeSession', 'Model/Datasource'); // doesn't work here
if (!class_exists('CakeSession')) {
	require CAKE . 'Model/Datasource/CakeSession.php';
}

class UserTest extends CakeTestCase {

	public $fixtures = array(
		'app.group',
		'app.groups_user',
		'app.user',
		'app.profile',
		'app.file_storage',
		'app.gpgkey',
		'app.role',
	);

	public $autoFixtures = true;

	/**
	 * Setup
	 *
	 * @return void
	 */
	public function setup() {
		parent::setUp();
		$this->User = ClassRegistry::init('User');
	}

	/**
	 * Test UserName Validation
	 *
	 * @return void
	 */
	public function testUsernameValidation() {
		$testcases = array(
			''              => false,
			'?!#'           => false,
			'test'          => false,
			'test@test.com' => true,
			'admin'         => false
		);
		foreach ($testcases as $testcase => $result) {
			$user = array('User' => array('username' => $testcase));
			$this->User->set($user);
			if ($result) {
				$msg = 'validation of the username with ' . $testcase . ' should validate';
			} else {
				$msg = 'validation of the username with ' . $testcase . ' should not validate';
			}
			$this->assertEqual($this->User->validates(array('fieldList' => array('username'))), $result, $msg);
		}
	}

	/**
	 * Test Password Validation
	 *
	 * @return void
	 */
	public function testPasswordValidation() {
		$testcases = array(
			''       => false,
			'?!#'    => false,
			'abcdefghijkl' => true,
			'32abcde,-fghijkl20' => true
		);
		foreach ($testcases as $testcase => $result) {
			$user = array('User' => array('password' => $testcase, 'password_confirm' => $testcase));
			$this->User->set($user);
			if ($result) {
				$msg = 'validation of user password with ' . $testcase . ' should validate';
			} else {
				$msg = 'validation of user password with ' . $testcase . ' should not validate';
			}
			$this->assertEqual($this->User->validates(array('fieldList' => array('password'))), $result, $msg);
		}
	}

	/**
	 * Test Password Encryption
	 *
	 * @return void
	 */
	public function testBeforeSave() {
		$user = array('User' => array('password' => 'test1'));
		$this->User->set($user);
		$this->assertEqual($this->User->beforeSave(), true, 'Before save should return true');
		$this->assertNotEqual(
			$this->User->data['User']['password'],
			$user['User']['password'],
			'Before save should return true'
		);
	}

	/**
	 * Test UserGet
	 *
	 * @return void
	 */
	public function testGetAnonymous() {
		// Make sure there is no active sessions
		App::import('Model', 'CakeSession');
		$Session = new CakeSession();
		$Session->delete(AuthComponent::$sessionKey);

		// Get an ANONYMOUS user
		$user = User::get();
		$this->assertEqual(is_array($user), true, 'User::get should return an array');
		$this->assertEqual(!isset($user['User']['password']), true, 'User::get should never return a password');
		$this->assertEqual(
			$user['User']['username'],
			User::ANONYMOUS,
			'User::get in default context should return an anonymous but returning ' . $user['User']['username']
		);
		$this->assertEqual(
			User::isAnonymous(),
			true,
			'User::get in default context should return an anonymous but returning ' . $user['User']['username']
		);
		$this->assertEqual(isset($user['Role']), true, 'User::get should return role');
		$this->assertEqual(
			$user['Role']['name'],
			Role::GUEST,
			'User::get in default context should return a user with guest role'
		);
		$this->assertEqual($user, $Session->read(AuthComponent::$sessionKey), 'User::get should set user in session');

		$this->assertEqual(
			User::get('id'),
			$user['User']['id'],
			'User::get(id) in default context should return a user with guest role'
		);
		$this->assertEqual(
			User::get('User.id'),
			$user['User']['id'],
			'User::get(user.id) in default context should return a user with guest role'
		);
		$this->assertEqual(
			User::get('Role.name'),
			Role::GUEST,
			'User::get(role.name) in default context should return a user with guest role'
		);
		$this->assertEqual(User::get('whatever'), false, 'User::get(whaterver) should return false');
		$this->assertEqual(User::get('Whatever.stuff'), false, 'User::get(whaterver.stuff) should return false');
	}

	public function testSetActive() {
		// Try to get a user that doesn't exist
		$user = User::setActive(String::UUID());
		$this->assertEqual($user, false, 'User::setActive should return false');
	}

	public function testisGuest() {
		// Make sure there is no active sessions
		App::import('Model', 'CakeSession');
		$Session = new CakeSession();
		$Session->delete(AuthComponent::$sessionKey);

		// Get an ANONYMOUS user
		$this->assertEqual(User::isGuest(), true, 'User::isGuest should return true');

		// Get admin user
		$param = array(
			'conditions' => array('username' => 'admin@passbolt.com')
		);
		$user = $this->User->find('first', $param);
		$this->User->setActive($user);
		$this->assertEqual(User::isGuest(), false, 'User::isGuest should return false, admin@passbolt.com is an admin');
	}

	public function testIsAdmin() {
		// Make sure there is no active sessions
		App::import('Model', 'CakeSession');
		$Session = new CakeSession();
		$Session->delete(AuthComponent::$sessionKey);

		// Get admin user
		$param = array(
			'conditions' => array('username' => 'admin@passbolt.com')
		);
		$user = $this->User->find('first', $param);
		$this->User->setActive($user);
		$this->assertEqual(User::isAdmin(), true, 'User::Admin should return true, admin@passbolt.com is an admin');
	}

	public function testGetFindConditions() {
		$f = $this->User->getFindConditions('User::view');
		$this->assertEquals(count($f), true, 'testGetFindCondition userView should return something');
		$f = $this->User->getFindConditions('User::index');
		$this->assertEquals(count($f), true, 'testGetFindCondition userIndex should return something');
		$f = $this->User->getFindConditions('User:activation');
		$this->assertEquals(count($f), true, 'testGetFindCondition User::activation should return something');
		$f = $this->User->getFindConditions('testoqwidoqdhwqohdowqhid');
		$this->assertEquals(count($f), true, 'testGetFindCondition bogus should return something');
	}

	public function testGetFindFields() {
		$f = $this->User->getFindFields('User::view');
		$this->assertEquals(count($f), true, 'testGetFindFields userView return something');
		$f = $this->User->getFindFields('User::index');
		$this->assertEquals(count($f), true, 'testGetFindFields userIndex return something');
		$f = $this->User->getFindFields('User::activation');
		$this->assertEquals(count($f), true, 'testGetFindFields User::activation return something');
		$f = $this->User->getFindFields('testdqwodjqodqodwjqidqjdow');
		$this->assertEquals(count($f), true, 'testGetFindFields bogus return something');
	}

	public function testCreatedBy() {
		// get an anon
		$anon = User::get();

		// insert a user
		$this->User->create();
		$this->User->set(
			array(
				'username' => 'testSave@passbolt.com',
				'role_id'  => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
				'password' => 'abcdefgh',
				'active'   => 1
			)
		);
		$this->User->save();
		$user = $this->User->find('all');

		// find the previously inserted user
		$param = array(
			'conditions' => array('username' => 'testSave@passbolt.com')
		);
		$user = $this->User->find('first', $param);

		$this->assertEqual(
			$anon['User']['id'],
			$user['User']['created_by'],
			'The user should be marked as being created by anonymous'
		);
	}

	public function testModifiedBy() {
		// get a user and set it as current user
		$param = array(
			'conditions' => array('username' => 'user@passbolt.com')
		);
		$kevin = $this->User->find('first', $param);
		$this->User->setActive($kevin);

		// change username and save
		$data = array(
			'User' => array(
				'id' => $kevin['User']['id'],
				'username' => 'kk@passbolt.com'
			)
		);
		$this->User->save($data);

		// find it again
		$param = array(
			'conditions' => array('username' => 'kk@passbolt.com')
		);
		$kk = $this->User->find('first', $param);

		$this->assertEqual(
			$kevin['User']['id'],
			$kk['User']['modified_by'],
			'user should be mark as the one who updated his record'
		);
	}

}

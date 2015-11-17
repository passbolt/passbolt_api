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
		'core.cakeSession',
		'app.authentication_token'
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
			$this->assertEquals($this->User->validates(array('fieldList' => array('username'))), $result, $msg);
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
			$this->assertEquals($this->User->validates(array('fieldList' => array('password'))), $result, $msg);
		}
	}

	/**
	 * Test Role validation.
	 */
	public function testRoleValidation() {
		//pr($this->User->Role->find('all'));
		$testcases = array(
			''                              => false,
			'?!#'                           => false,
			Common::uuid('role.id.user')    => true,
			Common::uuid('role.id.admin')   => true,
			Common::uuid('role.id.anonymous') => false,
			Common::uuid('role.id.root')    => false,
		);

		foreach ($testcases as $testcase => $result) {
			$user = array('User' => array('role_id' => $testcase));
			$this->User->set($user);
			if ($result) {
				$msg = 'validation of role with ' . $testcase . ' should validate.';
			} else {
				$msg = 'validation of role with ' . $testcase . ' should not validate';
			}
			$msg .= ('. Error returned : ' . print_r($this->User->validationErrors, true));
			$this->assertEquals($this->User->validates(array('fieldList' => array('role_id'))), $result, $msg);
		}
	}


	/**
	 * Test the custom validation rule that checks that a password is the same than the current one
	 * Used when editing passwords
	 */
	public function testIsCurrentPasswordValidationRule() {
		// check that current_password is set
		$check = array();
		$this->assertEquals($this->User->isCurrentPassword($check),false,'Empty password should not validate');
		$check = array('current_password' => null);
		$this->assertEquals($this->User->isCurrentPassword($check),false,'Empty password should not validate');
		$check = array('current_password' => '');
		$this->assertEquals($this->User->isCurrentPassword($check),false,'Empty password should not validate');

		// check the user id is set
		$check = array('current_password' => 'check');
		$this->assertEquals($this->User->isCurrentPassword($check),false,'Password should not validate when current user is not active');

		// check getting the right user based on id
		$param = array('conditions' => array('username' => 'user@passbolt.com'));
		$user = $this->User->find('first', $param);
		$this->User->set($user);
		$this->User->data = array();
		// check with wrong password
		$this->assertEquals($this->User->isCurrentPassword($check),false,'Password should not validate if it is not the current one');
		// check with good password
		$check = array('current_password' => 'password');
		$this->assertEquals($this->User->isCurrentPassword($check),true,'Password should validate if it is the current one');

		// check getting the right user based on data
		$param = array('conditions' => array('username' => 'user@passbolt.com'));
		$user = $this->User->find('first', $param);
		$this->User->set($user);
		$this->User->id = null;
		$check = array('current_password' => 'password');
		$this->assertEquals($this->User->isCurrentPassword($check),true,'Password should validate if it is the current one');

	}

	/**
	 * Test Password Encryption
	 *
	 * @return void
	 */
	public function testBeforeSave() {
		$user = array('User' => array('password' => 'test1'));
		$this->User->set($user);
		$this->assertEquals($this->User->beforeSave(), true, 'Before save should return true');
		$this->assertNotEquals(
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
		$this->assertEquals(is_array($user), true, 'User::get should return an array');
		$this->assertEquals(!isset($user['User']['password']), true, 'User::get should never return a password');
		$this->assertEquals(
			$user['User']['username'],
			User::ANONYMOUS,
			'User::get in default context should return an anonymous but returning ' . $user['User']['username']
		);
		$this->assertEquals(
			User::isAnonymous(),
			true,
			'User::get in default context should return an anonymous but returning ' . $user['User']['username']
		);
		$this->assertEquals(isset($user['Role']), true, 'User::get should return role');
		$this->assertEquals(
			$user['Role']['name'],
			Role::GUEST,
			'User::get in default context should return a user with guest role'
		);
		$this->assertEquals(null, $Session->read(AuthComponent::$sessionKey), 'User::get should not set anonymous user in session');

		$this->assertEquals(
			User::get('id'),
			$user['User']['id'],
			'User::get(id) in default context should return a user with guest role'
		);
		$this->assertEquals(
			User::get('User.id'),
			$user['User']['id'],
			'User::get(user.id) in default context should return a user with guest role'
		);
		$this->assertEquals(
			User::get('Role.name'),
			Role::GUEST,
			'User::get(role.name) in default context should return a user with guest role'
		);
		$this->assertEquals(User::get('whatever'), false, 'User::get(whaterver) should return false');
		$this->assertEquals(User::get('Whatever.stuff'), false, 'User::get(whaterver.stuff) should return false');
	}

	/**
	 * Test Set Active
	 */
	public function testSetActive() {
		// Try to get a user that doesn't exist
		$user = User::setActive(String::UUID());
		$this->assertEquals($user, false, 'User::setActive should return false');
	}

	/**
	 * Test Set Inactive
	 */
	public function testSetInactive() {
		// get anonymous user
		$u0 = $this->User->get();

		// get a user from the fixtures and set it as current user
		$param = array('conditions' => array('username' => 'user@passbolt.com'));
		$user = $this->User->find('first', $param);
		$this->User->setActive($user);
		$u1 = $this->User->get();

		// set u1 inactive and get a new one
		$this->User->setInactive();
		$u2 = $this->User->get();

		$this->assertNotEquals($u1, $u2, 'User::setInactive should work');
		$this->assertNotEquals($u1, $u0, 'User::setInactive should work');
		$this->assertEquals($u2, $u0, 'User::setInactive should work');

	}

	/**
	 * Test guest role check
	 */
	public function testisGuest() {
		// Make sure there is no active sessions
		App::import('Model', 'CakeSession');
		$Session = new CakeSession();
		$Session->delete(AuthComponent::$sessionKey);

		// Get an ANONYMOUS user
		$this->assertEquals(User::isGuest(), true, 'User::isGuest should return true');

		// Get admin user
		$param = array(
			'conditions' => array('username' => 'admin@passbolt.com')
		);
		$user = $this->User->find('first', $param);
		$this->User->setActive($user);
		$this->assertEquals(User::isGuest(), false, 'User::isGuest should return false, admin@passbolt.com is an admin');
	}

	/**
	 * Test admin role check
	 */
	public function testIsAdmin() {
		// Check if admin@passbolt have role admin
		$this->User->setInactive();
		$param = array('conditions' => array('username' => 'admin@passbolt.com'));
		$user = $this->User->find('first', $param);
		$this->User->setActive($user);
		$this->assertEquals(User::isAdmin(), true, 'User::Admin should return true, admin@passbolt.com is an admin');

		// Check if user@passbolt does not have role admin
		$this->User->setInactive();
		$param = array('conditions' => array('username' => 'user@passbolt.com'));
		$user = $this->User->find('first', $param);
		$this->User->setActive($user);
		$this->assertEquals(User::isAdmin(), false, 'User::Admin should return false, user@passbolt.com is not an admin');

	}

	/**
	 * Test Root role check
	 */
	function testIsRoot() {
		// Get root user and check role
		$this->User->setInactive();
		$param = array('conditions' => array('username' => 'root@passbolt.com'));
		$user = $this->User->find('first', $param);
		$this->User->setActive($user);
		$this->assertEquals(User::isRoot(), true, 'User::Root should return true, root@passbolt.com is root');

		// Get admin user and check it is not root
		$param = array('conditions' => array('username' => 'admin@passbolt.com'));
		$user = $this->User->find('first', $param);
		$this->User->setActive($user);
		$this->assertEquals(User::isRoot(), false, 'User::Root should return false, admin@passbolt.com is not root');

	}

	/**
	 * Test getting find conditions for guest role
	 */
	public function testGuestGetFindConditions() {
		$should_find = array(
			'Setup::userInfo', 'User::view', 'User::activation'
		);
		foreach ($should_find as $find) {
			$f = $this->User->getFindConditions($find, Role::GUEST);
			$this->assertEquals(count($f), true, 'testGetFindCondition '.$find.' for Guest should return something');
		}
		$should_not_find = array(
			'Bogus::stuff','User::index'
		);
		try {
			$f = $this->User->getFindConditions($find, Role::USER);
			$this->assertEquals(true, false, 'testGetFindCondition '.$find.' for Guest should throw an exception');
		} catch (Exception $e) {
			$this->assertEquals(true, true, 'testGetFindCondition '.$find.' for Guest should throw an exception');
		}
	}

	/**
	 * Test getting find conditions for guest role with additional data
	 */
	public function testGuestGetFindConditionsWithParameters() {
		$should_find = array(
			'User::view' => array('User.id' => String::uuid())
		);
		foreach ($should_find as $find => $data) {
			$f = $this->User->getFindConditions($find, Role::GUEST, $data);
			$this->assertEquals(count($f), true, 'testGetFindCondition '.$find.' for Guest should return something');
			$this->assertEquals($f['conditions'], array_merge($f['conditions'],$data), 'testGetFindCondition '.$find.' for Guest with parameter set should work');
		}
	}

	/**
	 * Test getting find conditions for user role with additional data
	 */
	public function testUserGetFindConditionsWithParameters() {
		$should_find = array(
			'User::view' => array('User.id' => String::uuid()),
			'User::view' => array('User.active' => true)
		);
		foreach ($should_find as $find => $data) {
			$f = $this->User->getFindConditions($find, Role::USER, $data);
			$this->assertEquals(count($f), true, 'testGetFindCondition '.$find.' for Guest should return something');
			$this->assertEquals($f['conditions'], array_merge($f['conditions'],$data), 'testGetFindCondition '.$find.' for Guest with parameter set should work');
		}
	}

	/**
	 * Test getting find conditions for user role
	 */
	public function testUserGetFindConditions() {
		$should_find = array(
			'User::index', 'User::view', 'User::activation'
		);
		foreach ($should_find as $find) {
			$f = $this->User->getFindConditions($find, Role::USER);
			$this->assertEquals(count($f), true, 'testGetFindCondition '.$find.' for Guest should return something');
		}
		$should_not_find = array(
			'Bogus::stuff', 'Setup::userInfo'
		);
		foreach ($should_not_find as $find) {
			try {
				$f = $this->User->getFindConditions($find, Role::USER);
				$this->assertEquals($f, false, 'testGetFindCondition '.$find.' for Guest should throw an exception');
			} catch (Exception $e) {
				$this->assertEquals(true, true, 'testGetFindCondition '.$find.' for Guest should throw an exception');
			}
		}
	}

	public function testGetFindFields() {

		$should_find = array(
			'User::index', 'User::view', 'User::activation', 'Bogus::stuff',
			'User::validateAccount', 'User::edit', 'User::save', 'User::delete', 'User::editPassword'
		);
		foreach ($should_find as $find) {
			$f = $this->User->getFindFields($find);
			$this->assertEquals(count($f), true, 'testGetFindFields ' . $find . ' should return something');
		}
	}

	public function testCreatedBy() {
		// get an anon
		$anon = User::get();

		// insert a user
		$this->User->create();
		$this->User->set(
			array(
				'username' => 'testSave@passbolt.com',
				'role_id'  => Common::uuid('role.id.user'),
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

		$this->assertEquals(
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
		$u = $this->User->find('first', $param);
		$this->User->setActive($u);

		// change username and save
		$data = array(
			'User' => array(
				'id' => $u['User']['id'],
				'username' => 'user_edited@passbolt.com'
			)
		);
		$this->User->save($data);

		// find it again
		$param = array(
			'conditions' => array('username' => 'user_edited@passbolt.com')
		);
		$user = $this->User->find('first', $param);

		$this->assertEquals(
            $u['User']['id'],
			$user['User']['modified_by'],
			'user should be mark as the one who updated his record'
		);
	}

	/**
	 * Test __add() function with valid parameters.
	 */
	public function testAdd() {
		$data = [
			'Profile' => [
				'first_name' => 'john',
				'last_name' => 'doe',
			],
			'User' => [
				'username' => 'john.doe@passbolt.com'
			]
		];
		$user = $this->User->__add($data);
		$this->assertEquals($user['User']['username'], $data['User']['username'], 'User should have been created with the same email');
	}

	/**
	 * Test __add() function with invalid parameters.
	 * An exception should be returned
	 * No user should be created in the database due to rollback.
	 */
	public function testAddWithValidationError() {
		$data = [
			'Profile' => [
				'first_name' => 'john1',
				'last_name' => 'doe',
			],
			'User' => [
				'username' => 'john.doe@passbolt.com'
			]
		];
		$this->setExpectedException('ValidationException', 'Could not validate profile');
		$this->User->__add($data);
		// Check that no user is inside the user table.
		$user = $this->User->find('all', [
				'conditions' => [
					'username' => $data['User']['username']
				]
			]);
		$this->assertEmpty($user, 'After a validation error, the user should not have been created in the database');
	}

	/**
	 * Test __add() function with an invalid role.
	 * An exception should be returned
	 * No user should be created in the database due to rollback.
	 */
	public function testAddInvalidRole() {
		$data = [
			'Profile' => [
				'first_name' => 'john',
				'last_name' => 'doe',
			],
			'User' => [
				'username' => 'john.doe@passbolt.com',
				'role_id'  => Common::uuid('role.id.anonymous')
			]
		];
		$this->setExpectedException('ValidationException', 'Could not validate user');
		$this->User->__add($data);
		// Check that no user is inside the user table.
		$user = $this->User->find('all', [
			'conditions' => [
				'username' => $data['User']['username']
			]
		]);
		$this->assertEmpty($user, 'After a validation error, the user should not have been created in the database');
	}

	/**
	 * Test that an admin cannot change its own admin role.
	 */
	public function testModifyOwnAdminRole() {
		$user = $this->User->find('first', array('conditions' => array('username' => 'admin@passbolt.com')));
		$this->User->setActive($user);

		$user['User']['role_id'] = $this->User->Role->field('id', ['name' => Role::USER]);
		$this->User->id = $user['User']['id'];
		$this->User->set($user);
		$validates = $this->User->validates();
		$this->assertFalse($validates);
		$this->assertTrue(array_key_exists('role_id', $this->User->validationErrors));
	}

}

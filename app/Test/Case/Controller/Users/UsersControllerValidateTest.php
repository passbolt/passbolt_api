<?php
/**
 * Users Controller Validate Action Tests
 *
 * @copyright (c) 2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('AppController', 'Controller');
App::uses('UsersController', 'Controller');
App::uses('User', 'Model');
App::uses('Group', 'Model');
App::uses('Role', 'Model');
App::uses('CakeSession', 'Model');
App::uses('CakeSession', 'Model/Datasource');
App::uses('CakeSessionFixture', 'Test/Fixture');

class UsersControllerValidateTest extends ControllerTestCase {

	public $fixtures = array(
		'app.groups_user',
		'app.group',
		'app.user',
		'app.gpgkey',
		'app.email_queue',
		'app.profile',
		'app.file_storage',
		'app.role',
		'app.authenticationToken',
		'core.cakeSession',
		'app.user_agent',
		'app.controller_log',
		'app.resource',
		'app.permission',
		'app.permissions_type',
		'app.permission_view',
	);

	public $user;

	public $session;

	public function setUp() {
		parent::setUp();
		$this->User = Common::getModel('User');
		$this->Gpgkey = Common::getModel('Gpgkey');
		$u = $this->User->get();
		$this->session = new CakeSession();
		$this->session->init();
	}

	public function tearDown() {
		parent::tearDown();
		// Make sure there is no session active after each test
		$this->User->setInactive();
	}

/**
 * Create a dummy account using a call to the controller.
 *
 * @param string $username requested.
 * @return array user data
 */
	private function __createAccount($username) {
		$userAdd = $this->testAction(
			'/users.json',
			array(
				'data' => array(
					'User' => array(
						'username' => $username,
						'role_id' => Common::uuid('role.id.user')
					),
					'Profile' => array(
						'first_name' => 'Jean',
						'last_name' => 'Gabin'
					),
				),
				'method' => 'post',
				'return' => 'contents'
			)
		);
		$json = json_decode($userAdd, true);
		return $json['body'];
	}

/**
 * Test account validation when user id is missing.
 *
 * @return void
 */
	public function testAccountValidationUserIdIsMissing() {
		$this->User->setInactive();
		$this->setExpectedException('BadRequestException', 'The user id is missing.');
		$this->testAction('/users/validateAccount.json', array('return' => 'contents', 'method' => 'put'), true);
	}

/**
 * Test account validation user id not valid.
 *
 * @return void
 */
	public function testAccountValidationUserIdNotValid() {
		$this->User->setInactive();
		$this->setExpectedException('BadRequestException', 'The user id is not valid.');
		$this->testAction('/users/validateAccount/badId.json', array('return' => 'contents', 'method' => 'put'), true);
	}

/**
 * Test account validation when the user does not exist.
 *
 * @return void
 */
	public function testAccountValidationUserDoesNotExist() {
		$this->User->setInactive();
		$id = Common::uuid('not-valid-reference');
		$this->setExpectedException('NotFoundException', 'The user does not exist.');
		$this->testAction(
			"/users/validateAccount/{$id}.json",
			array('return' => 'contents', 'method' => 'put'),
			true
		);
	}

/**
 * Test account validation.
 *
 * @return void
 */
	public function testAccountValidationNoDataProvided() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$user = $this->__createAccount('jean-gabin@gmail.com');
		$userId = $user['User']['id'];
			$this->User->setInactive();

		$this->setExpectedException('BadRequestException', 'No authentication token was provided.');
		$url = "/users/validateAccount/{$userId}.json";
		$this->testAction($url, array(
			'method' => 'put',
			'return' => 'contents'
		));
	}

/**
 * Test account validation when the authentication token is invalid.
 *
 * @return void
 */
	public function testAccountValidationInvalidToken() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$user = $this->__createAccount('jean-gabin@gmail.com');
		$userId = $user['User']['id'];
			$this->User->setInactive();

		$this->setExpectedException('BadRequestException', 'The authentication token is not valid.');
		$url = "/users/validateAccount/{$userId}.json";
		$this->testAction($url, array(
			'data' => array (
				'AuthenticationToken' => array (
					'token' => Common::uuid(),
				),
			),
			'method' => 'put',
			'return' => 'contents'
		));
	}

/**
 * Test account validation when the authentication token is expired.
 *
 * @return void
 */
	public function testAccountValidationExpiredToken() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$user = $this->__createAccount('jean-gabin@gmail.com');
		$userId = $user['User']['id'];
		$this->User->setInactive();

		$AuthenticationToken = Common::getModel('AuthenticationToken');
		$at = $AuthenticationToken->findByUserId($user['User']['id']);

		// Reduce the token expiracy date to 1 second.
		Configure::write('Auth.tokenExpiracy', 0.016);
		sleep(1);

		$this->setExpectedException('BadRequestException', 'The authentication token is expired.');
		$url = "/users/validateAccount/{$userId}.json";
		$this->testAction($url, array(
			'data' => array (
				'AuthenticationToken' => array (
					'token' => $at['AuthenticationToken']['token'],
				),
			),
			'method' => 'put',
			'return' => 'contents'
		));
	}

/**
 * Test account validation.
 *
 * @return void
 */
	public function testAccountValidation() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$user = $this->__createAccount('jean-gabin@gmail.com');
		$userId = $user['User']['id'];
			$this->User->setInactive();

		$AuthenticationToken = Common::getModel('AuthenticationToken');
		$at = $AuthenticationToken->findByUserId($user['User']['id']);

		$url = "/users/validateAccount/{$userId}.json";
		$validate = $this->testAction($url, array(
			'data' => array (
				'AuthenticationToken' => array (
					'token' => $at['AuthenticationToken']['token'],
				),
			),
			'method' => 'put',
			'return' => 'contents'
		));
		$json = json_decode($validate, true);
		$this->assertEquals(
			Status::SUCCESS,
			$json['header']['status'],
			"validateAccount /users/validateAccount/{$userId}.json : The test should return a success but is returning {$json['header']['status']}"
		);

		// Check that the user returned is the right one.
		$this->assertEquals($json['body']['User']['username'], $user['User']['username'], 'The authentication token should be deactivated after account activation, but it is not');

		// Get user and check if deactivated.
		$deactivatedUser = $this->User->findById($userId);
		$this->assertEquals($deactivatedUser['User']['active'], 1, 'The user should be activated after account validation, but is not');

		// Check Authentication Token is not active anymore.
		$at = $AuthenticationToken->findByUserId($userId);
		$this->assertEquals($at['AuthenticationToken']['active'], 0, 'The authentication token should be deactivated after account activation, but it is not');
	}

/**
 * Test account validation with profile edition.
 *
 * @return void
 */
	public function testAccountValidationWithProfileEdit() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$user = $this->__createAccount('jean-gabin@gmail.com');
		$userId = $user['User']['id'];
			$this->User->setInactive();

		$AuthenticationToken = Common::getModel('AuthenticationToken');
		$at = $AuthenticationToken->findByUserId($userId);

		$url = "/users/validateAccount/{$userId}.json";
		$validate = $this->testAction($url, array(
			'data' => array (
				'AuthenticationToken' => array (
					'token' => $at['AuthenticationToken']['token'],
				),
				'Profile' => array (
					'first_name' => 'Rene',
					'last_name' => 'Dupuit',
				),
			),
			'method' => 'put',
			'return' => 'contents'
		));
		$json = json_decode($validate, true);
		$this->assertEquals(
			Status::SUCCESS,
			$json['header']['status'],
			"validateAccount /users/validateAccount/{$userId}.json : The test should return a success but is returning {$json['header']['status']}"
		);

		// Get user and check if deactivated.
		$profile = $this->User->Profile->findByUserId($userId);
		$this->assertEquals($profile['Profile']['first_name'], 'Rene', "After account validation the user first_name should be rene, but is {$profile['Profile']['first_name']}");
	}

/**
 * Test account validation with a Gpgkey.
 *
 * @return void
 */
	public function testAccountValidationWithGpgkeyEdit() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$user = $this->__createAccount('jean-gabin@gmail.com');
		$userId = $user['User']['id'];
		$this->User->setInactive();

		$AuthenticationToken = Common::getModel('AuthenticationToken');
		$at = $AuthenticationToken->findByUserId($userId);

		// Dummy key taken from one generated by pgpjs.
		$dummyKey = array(
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Version: OpenPGP.js v0.7.2
Comment: http://openpgpjs.org

xsBNBFYyMckBCACJQrcRTd5PTrGY0KnJErmONKutJyoIF3KcTWa5glH5BLT6
htzYS5cSyuB4nTUwHXFh6EV+iX2fsfU+K4jNr4JmDYcolOsXmNM0V8Z5Di+d
IElqJRnINpQWjDOZ11KpqaqoChzGklji/TMv39Iic0yY2UO4tmexVI7lJTTa
3Omk/P6O6zjgq262JAjTcQLTFeuVLKYqhdiuWIyqVTS+zfQgqeoGDQ1c6UWr
wkHEDW966IOrOvG4w6ekRmXJQEHjqAK8N7x39z6jNxLYnKUlXSMAB3+Fm8b6
QhHHw2VJHjVtxe6ywSvyzbn7gh4gysg9vP7YOjSXeLjOlSoFjKzTiCpnABEB
AAHNH2FkYSBsb3ZlbGFjZSA8YWRhQHBhc3Nib2x0LmNvbT7CwHIEEAEIACYF
AlYyMcwGCwkIBwMCCRDPd2OSgbVHnwQVCAIKAxYCAQIbAwIeAQAAvzcIAIWL
BuGxx8RqYA5UDn2IDvqweKP6/ltBt65RYxHv/khp/daNyZVl17ovupsQ3x00
Ve8GqqDDz3w5+Qi84Ab5Fw9yAtgawhAqvME+HAjlBo762Wy1+wE9yJDN/633
UaSp+dRgnaEXNpWr285SVwj4UVxnPqkogGhOjR1oIqTWTCb5sx+sBU+HiJNL
RCZCtE/RhOmcUv8VMuDPeyu7Mjq0G+v3SVJeDvmhkEqyZpZYW3DnBTjHufIH
u/JOdt8vbbG9rtNZELfrFA4GtXwqskbkSGl0q1mi6ojT590RLInoDLle1BtG
KIGGi1k0Iru/UWtzFILegDelMf48aanUZJYw8U3OwE0EVjIxzAEH/1ijwZSF
gK4Sua0yMYYQfuw7Lf9wYIDUegFoyJrbhbYn0abjxCqcauE/EkAFJOyeRjnW
8ucOPfUB+MyMBUGmATktpbE1s7OLTaoq+ebK0QeYFyuvZfxLenZRVAYeA4UI
o+fkW4HuPs811wcnUeiHjXQ95ccKeG6F47lX8C/RyssMZd3SDrlw3qi4/2T5
LzAGSEidBro4P8v8okWkgo7Uzy5HVswb4PIr7mWGEw8qT/LO0CeWFDXdcneS
AXng7utbTlPZpqVNOamymsbNUHZCAUHg2zn356CVtAab/yBT/1bH0m08mkgQ
nPo79zs/81QL+UnTEqnuiuAz9JruOqu2Sh0AEQEAAcLAXwQYAQgAEwUCVjIx
0gkQz3djkoG1R58CGwwAACLiB/4xuU+mcn4nhY6t0A5tGtSNSIZ7u/mBgUS1
Z7TNcGiuJp6lpw3yrBXDB7pbVDRFWRhVVrTZJVbZzcwxjFbrQ8K8ahFkP3Fc
IUA9j8HFz2iONvuj3OqcD6GnBgP4tmDDaddEGdjdykumshZ9WU14VJ62AOvr
X7J0P1erI9FGiyN9x/Cr8wdsI2GcRapF0HENXkKnFu0iSnQRWICJP2KBtNw3
v14lYNb1TSfV2D0TUTnq03kh5GjXpudUoQGBChEXouUa+IJUVTDQWRQ4rgd0
0EWkG+mMPwNRAaDSDOgXVSr8po1xiKsdf/Ewj47y5u8HcEVEG2E68o215lUH
qGyky3/L
=CpLW
-----END PGP PUBLIC KEY BLOCK-----'
		);

		$url = "/users/validateAccount/{$userId}.json";
		$validate = $this->testAction(
			$url,
			array(
				'data' => array (
					'AuthenticationToken' => array (
						'token' => $at['AuthenticationToken']['token'],
					),
					'Gpgkey' => $dummyKey
				),
				'method' => 'put',
				'return' => 'contents'
			)
		);
		$json = json_decode($validate, true);
		$this->assertEquals(
			Status::SUCCESS,
			$json['header']['status'],
			"validateAccount /users/validateAccount/{$userId}.json : The test should return a success but is returning {$json['header']['status']}"
		);

		// Get user and check if deactivated.
		$gpkey = $this->Gpgkey->findByUserId($userId);
		$this->assertEquals($gpkey['Gpgkey']['key'], $dummyKey['key'], "After account validation the key was supposed to be set, but is not");
		$this->assertEquals($gpkey['Gpgkey']['bits'], 2048);
		$this->assertEquals($gpkey['Gpgkey']['uid'], 'ada lovelace &lt;ada@passbolt.com&gt;');
		$this->assertEquals($gpkey['Gpgkey']['type'], 'RSA');
		$this->assertEquals($gpkey['Gpgkey']['key_created'], '2015-10-29 14:48:41');
		$this->assertEquals($gpkey['Gpgkey']['fingerprint'], '051A166E300DAD845B255E37CF77639281B5479F');
		$this->assertEquals($gpkey['Gpgkey']['key_id'], '81B5479F');
	}

/**
 * Test account validation with a non unique Gpgkey.
 *
 * @return void
 */
	public function testAccountValidationWithNonUniqueGpgkey() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$user = $this->__createAccount('jean-gabin@gmail.com');
		$userId = $user['User']['id'];
		$this->User->setInactive();

		$AuthenticationToken = Common::getModel('AuthenticationToken');
		$at = $AuthenticationToken->findByUserId($userId);

		// Dummy key taken from one generated by pgpjs.

		// Get the previously used key
		$gpgkeyPath = Configure::read('GPG.testKeys.path');
		$adaPrivateKey = file_get_contents($gpgkeyPath . 'ada_public.key');
		$dummyKey = array(
			'key' => $adaPrivateKey
		);

		$this->setExpectedException('ValidationException', 'Could not validate the GPG key data.');
		$url = "/users/validateAccount/{$userId}.json";
		$this->testAction(
			$url,
			array(
				'data' => array (
					'AuthenticationToken' => array (
						'token' => $at['AuthenticationToken']['token'],
					),
					'Gpgkey' => $dummyKey
				),
				'method' => 'put',
				'return' => 'contents'
			)
		);
	}

/**
 * Test account validation with a non unique Gpgkey belonging to a deleted user.
 *
 * @return void
 */
	public function testAccountValidationWithNonUniqueGpgkeyBelongingToDeletedUser() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$user = $this->__createAccount('jean-gabin@gmail.com');
		$userId = $user['User']['id'];
		$this->User->setInactive();

		// Soft delete ada.
		$ada = $this->User->findByUsername('ada@passbolt.com');
		$this->User->id = $ada['User']['id'];
		$this->User->saveField('deleted', 1);

		$AuthenticationToken = Common::getModel('AuthenticationToken');
		$at = $AuthenticationToken->findByUserId($userId);

		// Get the previously used key
		$gpgkeyPath = Configure::read('GPG.testKeys.path');
		$key = file_get_contents($gpgkeyPath . 'ada_public.key');
		$dummyKey = array(
			'key' => $key
		);

		// Execute the action. We don't expect any exception.
		$url = "/users/validateAccount/{$userId}.json";
		$validate = $this->testAction(
			$url,
			array(
				'data' => array (
					'AuthenticationToken' => array (
						'token' => $at['AuthenticationToken']['token'],
					),
					'Gpgkey' => $dummyKey
				),
				'method' => 'put',
				'return' => 'contents'
			)
		);

		$json = json_decode($validate, true);
		$this->assertEquals(
			Status::SUCCESS,
			$json['header']['status'],
			"validateAccount /users/validateAccount/{$userId}.json : The test should return a success but is returning {$json['header']['status']}"
		);
	}

/**
 * Test account validation with a wrong user id.
 *
 * @return void
 */
	public function testAccountValidationWrongUserId() {
		$id = Common::uuid('not-valid-reference');
		$this->setExpectedException('HttpException', 'The user does not exist');
		$this->testAction(
			"/users/validateAccount/{$id}.json",
			array(
				'data' => array (
					'AuthenticationToken' => array (
						'token' => 'tokentoken',
					),
				),
				'method' => 'put',
				'return' => 'contents'
			)
		);
	}

/**
 * Test account validation with a wrong token.
 *
 * @return void
 */
	public function testAccountValidationWrongToken() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$user = $this->__createAccount('jean-gabin@gmail.com');
		$userId = $user['User']['id'];
		$this->User->setInactive();

		$this->setExpectedException('BadRequestException', 'The authentication token is not valid.');
		$url = "/users/validateAccount/{$userId}.json";
		$this->testAction(
			$url,
			array(
				'data' => array (
					'AuthenticationToken' => array (
						'token' => 'wrong token',
					),
				),
				'method' => 'put',
				'return' => 'contents'
			)
		);
	}

/**
 * Test that the rollback works correctly in case of exception.
 * basically test that the active fields in user and authenticationToken are back to their normal state.
 *
 * @return void
 */
	public function testAccountValidationExceptionRollback() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$user = $this->__createAccount('jean-gabin@gmail.com');
		$userId = $user['User']['id'];
		$this->User->setInactive();

		$AuthenticationToken = Common::getModel('AuthenticationToken');
		$at = $AuthenticationToken->findByUserId($userId);

		// Dummy key taken from one generated by pgpjs.
		$dummyKey = array(
			'key' => 'wrongKey'
		);

		try {
			$url = "/users/validateAccount/{$userId}.json";
			$this->testAction(
				$url,
				array(
					'data' => array (
						'AuthenticationToken' => array (
							'token' => $at['AuthenticationToken']['token'],
						),
						'Gpgkey' => $dummyKey
					),
					'method' => 'put',
					'return' => 'contents'
				)
			);
		} catch(Exception $e) {
			// Assert that user is not active.
			$notActivedUser = $this->User->findById($userId);
			$this->assertEquals($notActivedUser['User']['active'], 0, 'Account validation : After exception, user should still be inactive but is not');

			// Assert that token is deactivated.
			$at = $AuthenticationToken->findById($at['AuthenticationToken']['id']);
			$this->assertEquals($at['AuthenticationToken']['active'], 1, 'Account validation : After exception, token should still be active but is not');
		}
	}
}

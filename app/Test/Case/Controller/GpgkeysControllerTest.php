<?php
/**
 * Gpgkeys Controller Tests
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @package      app.Test.Case.Controller.GpgkeysController
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @since        version 2.13.3
 */
App::uses('AppController', 'Controller');
App::uses('GpgkeysController', 'Controller');
App::uses('Gpgkey', 'Model');
App::uses('User', 'Model');

// Uses sessions
// App::uses('CakeSession', 'Model/Datasource'); // doesn't work here
if (!class_exists('CakeSession')) {
	require CAKE . 'Model/Datasource/CakeSession.php';
}

class GpgkeysControllerTest extends ControllerTestCase {

	public $fixtures = array(
		'app.user',
		'app.role',
		'app.gpgkey',
		'app.group',
		'app.authenticationLog',
		'app.authenticationBlacklist',
		'core.cakeSession',
		'app.user_agent',
		'app.controller_log'
	);

	/**
	 * Setup.
	 */
	public function setUp() {
		$this->User = ClassRegistry::init('User');
		$this->Gpgkey = ClassRegistry::init('Gpgkey');
		parent::setUp();

		$user = $this->User->findById(common::uuid('user.id.user'));
		$this->User->setActive($user);
	}

	/**
	 * Test index.
	 */
	public function testIndex() {
		// Test normal index.
		$result = json_decode(
			$this->testAction('/gpgkeys.json',
				array(
					'return' => 'contents',
					'method' => 'GET'
				),
				true
			));
		$this->assertEquals(
			$result->header->status,
			Status::SUCCESS,
			'/gpgkeys.json return something'
		);

		// Test deleting everything, and calling index again.
		$this->Gpgkey->deleteAll(array(
				'Gpgkey.id <>' => null
			));
		$result = json_decode(
			$this->testAction(
				'/gpgkeys.json',
				array(
					'return' => 'contents',
					'method' => 'GET'
				),
				true
			));
		$this->assertEquals(
			$result->header->status,
			Status::NOTICE,
			'/gpgkeys.json return a warning'
		);
	}

	/**
	 * Test index with the filters.
	 */
	public function testIndexFilters() {
		// Test filter modified_after with a date in the past.
		$date = strtotime('1980-12-14 00:00:00');
		$result = json_decode(
			$this->testAction(
				"/gpgkeys.json",
				array(
					'return' => 'contents',
					'method' => 'GET',
					'data' => array(
						'modified_after' => strval($date)
					)
				),
				true
			));

		$this->assertEquals(
			$result->header->status,
			Status::SUCCESS,
			'Gpgkeys Controller should return something when modified after is in the past'
		);

		// Test filter modified_after with a date in the future.
		$date = strtotime('2020-12-14 00:00:00');

		$result2 = json_decode(
			$this->testAction(
				"/gpgkeys.json",
				array(
					'return' => 'contents',
					'method' => 'GET',
					'data' => array(
						'modified_after' => strval($date)
					)
				),
				true
			));

		$this->assertEquals(
			$result2->header->status,
			Status::NOTICE,
			'Gpgkeys Controller should return nothing when modified after filter date is in the future'
		);

	}

	/**
	 * Test view with a uuid not valid.
	 */
	public function testViewGpgkeyIdNotValid() {
		$this->setExpectedException('HttpException', 'The user id is invalid');
		$this->testAction(
			"/gpgkeys/badid.json",
			array('method' => 'get', 'return' => 'contents')
		);
	}

	/**
	 * Test view with uuid non existing.
	 */
	public function testViewGpgkeyDoesNotExist() {
		$id = Common::uuid('not-valid-reference');
		$this->setExpectedException('HttpException', 'The user id is invalid');
		$this->testAction(
			"/gpgkeys/{$id}.json",
			array('method' => 'get', 'return' => 'contents')
		);
	}

	/**
	 * Normal test view.
	 */
	public function testView() {
		$gpgkey = $this->Gpgkey->findByUserId(Common::uuid('user.id.ada'));
		$result = json_decode(
			$this->testAction("/gpgkeys/{$gpgkey['Gpgkey']['user_id']}.json",
				array(
					'return' => 'contents',
					'method' => 'GET'
				),
				true)
		);
		$this->assertEquals($result->header->status, Status::SUCCESS,'/gpgkey return something');
	}

	/**
	 * Test adding a key.
	 */
	public function testAdd() {
		$pubKey = file_get_contents( Configure::read('GPG.testKeys.path') . 'passbolt_dummy_key.asc');
		$user = $this->User->findById(common::uuid('user.id.user'));
		$this->User->setActive($user);
		$json = json_decode(
			$this->testAction('/gpgkeys.json',
			array(
				'data' => array(
					'Gpgkey' => array(
						'key' =>$pubKey
					),
				),
				'method' => 'post',
				'return' => 'contents'
			)),
			true
		);
		$this->assertEquals(
			Status::SUCCESS,
			$json['header']['status'],
			"Add : /gpgkeys.json : The test should return sucess but is returning " . print_r($json, true)
		);

		$gpgkey = $this->Gpgkey->find(
			'first',
			array (
				'conditions' => array(
					'Gpgkey.user_id' => $user['User']['id'],
					'Gpgkey.deleted' => false
				)
			));

		$this->assertEquals(
			$gpgkey['Gpgkey']['key'],
			$pubKey,
			"Add : /gpgkeys.json : after add, the content of the keys is not the same"
		);
	}

	/**
	 * Test that adding a key removes the user previous keys.
	 */
	public function testAddRemovePreviousKeys() {
		$pubKey = file_get_contents(Configure::read('GPG.testKeys.path') . 'passbolt_dummy_key.asc');
		$user = $this->User->findById(common::uuid('user.id.user'));
		$this->User->setActive($user);

		// Number of insertion rounds.
		$rounds = 3;
		for ($i = 0 ; $i < $rounds; $i++) {
			$result = json_decode(
				$this->testAction('/gpgkeys.json',
					array(
						'data' => array(
							'Gpgkey' => array(
								'key' => $pubKey
							),
						),
						'method' => 'post',
						'return' => 'contents'
					)),
					true
				);
			// For each round, test that the add was succesful.
			$this->assertEquals(
				Status::SUCCESS,
				$result['header']['status'],
				"Add : /gpgkeys.json : The test should return success but is returning " . print_r($result, true)
			);
		}

		// Count the number of deleted keys.
		// Logically, it should be equal to $round.
		$nbDeletedKeys = $this->Gpgkey->find(
			'count',
			array (
				'conditions' => array(
					'Gpgkey.user_id' => $user['User']['id'],
					'Gpgkey.deleted' => true
				)
			));

		// Assertion.
		$this->assertEquals (
			$nbDeletedKeys,
			$rounds,
			"Add : /gpgkeys.json : after add, the number of deleted keys in the db should be " . ($rounds)
		);
	}

	/**
	 * Test adding an invalid key.
	 */
	public function testAddInvalidKey() {
		$pubKey = 'invalidkey';
		$user = $this->User->findById(common::uuid('user.id.user'));
		$this->User->setActive($user);
		$this->setExpectedException('HttpException', 'The gpgkey provided could not be used');
		$this->testAction('/gpgkeys.json',
			array(
				'data' => array(
					'Gpgkey' => array(
						'key' =>$pubKey
					),
				),
				'method' => 'post',
				'return' => 'contents'
			)
		);
	}

}
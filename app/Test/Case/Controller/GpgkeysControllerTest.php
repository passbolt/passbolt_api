<?php
/**
 * Gpgkeys Controller Tests
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @package      app.Test.Case.Controller.GpgkeysController
 * @license      http://www.passbolt.com/license
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

	public $fixtures
		= array(
			'app.user',
			'app.role',
			'app.gpgkey',
			'app.group',
			'app.authenticationLog',
			'app.authenticationBlacklist'
		);

	/**
	 * Setup.
	 */
	public function setUp() {
		$this->User = ClassRegistry::init('User');
		$this->Gpgkey = ClassRegistry::init('Gpgkey');
		parent::setUp();

		// log the user as a manager to be able to access all categories
		$kk = $this->User->findByUsername('kevin@passbolt.com');
		$this->User->setActive($kk);
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
		$this->assertEqual(
			$result->header->status,
			Message::SUCCESS,
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
		$this->assertEqual(
			$result->header->status,
			Message::NOTICE,
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
						'modified_after' => $date
					)
				),
				true
			));
		$this->assertEqual(
			$result->header->status,
			Message::SUCCESS,
			'/gpgkeys.json return something'
		);

		// Test filter modified_after with a date in the future.
		$date = strtotime('2020-12-14 00:00:00');
		$result = json_decode(
			$this->testAction(
				"/gpgkeys.json",
				array(
					'return' => 'contents',
					'method' => 'GET',
					'data' => array(
						'modified_after' => $date
					)
				),
				true
			));
		$this->assertEqual(
			$result->header->status,
			Message::NOTICE,
			'/gpgkeys.json return something'
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
		$this->setExpectedException('HttpException', 'The user id is invalid');
		$this->testAction(
			"/gpgkeys/4ff6111b-efb8-4a26-aab4-2184cbdd56ca.json",
			array('method' => 'get', 'return' => 'contents')
		);
	}

	/**
	 * Normal test view.
	 */
	public function testView() {
		$gpgkey = $this->Gpgkey->findByUserId('bbd56042-cccc-11e1-a0c5-080027796c4a');
		$result = json_decode(
			$this->testAction("/gpgkeys/{$gpgkey['Gpgkey']['user_id']}.json",
				array(
					'return' => 'contents',
					'method' => 'GET'
				),
				true)
		);
		$this->assertEqual($result->header->status, Message::SUCCESS,'/gpgkey return something');
	}

	/**
	 * Test adding a key.
	 */
	public function testAdd() {
		$pubKey = file_get_contents(APP . 'Config' . DS . 'gpg' . DS . 'passbolt_dummy_key.asc');
		$kk = $this->User->findByUsername('kevin@passbolt.com');
		$this->User->setActive($kk);
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
			Message::SUCCESS,
			$json['header']['status'],
			"Add : /gpgkeys.json : The test should return sucess but is returning " . print_r($json, true)
		);

		$gpgkey = $this->Gpgkey->find(
			'first',
			array (
				'conditions' => array(
					'Gpgkey.user_id' => $kk['User']['id'],
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
		$pubKey = file_get_contents(APP . 'Config' . DS . 'gpg' . DS . 'passbolt_dummy_key.asc');
		$kk = $this->User->findByUsername('kevin@passbolt.com');
		$this->User->setActive($kk);

		// Number of insertion rounds.
		$rounds = 3;
		for ($i = 0 ; $i < $rounds; $i++) {
			$result = json_decode(
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
			// For each round, test that the add was succesful.
			$this->assertEquals(
				Message::SUCCESS,
				$result['header']['status'],
				"Add : /gpgkeys.json : The test should return sucess but is returning " . print_r($result, true)
			);
		}

		// Count the number of deleted keys.
		// Logically, it should be equal to $round - 1.
		$nbDeletedKeys = $this->Gpgkey->find(
			'count',
			array (
				'conditions' => array(
					'Gpgkey.user_id' => $kk['User']['id'],
					'Gpgkey.deleted' => true
				)
			));

		// Assertion.
		$this->assertEquals (
			$nbDeletedKeys,
			$rounds - 1,
			"Add : /gpgkeys.json : after add, the number of deleted keys in the db should be " . ($rounds - 1)
		);
	}

	/**
	 * Test adding an invalid key.
	 */
	public function testAddInvalidKey() {
		$pubKey = 'invalidkey';
		$kk = $this->User->findByUsername('kevin@passbolt.com');
		$this->User->setActive($kk);
		$this->setExpectedException('HttpException', 'Could not validate gpgkey data');
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
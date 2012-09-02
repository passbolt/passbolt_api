<?php
/**
 * Role Model Test
 *
 * @copyright	 Copyright 2012, Passbolt.com
 * @package	   app.Test.Case.Model.RoleTest
 * @since		 version 2.12.7
 * @license	   http://www.passbolt.com/license
 */
App::uses('User', 'Model');

class GpgKeyTest extends CakeTestCase {

	public $fixtures = array('app.gpgKey');

	public $autoFixtures = true;

	public $lisa;

/**
 * Setup
 * @return void
 */
	public function setup() {
		parent::setUp();
		$this->GpgKey = ClassRegistry::init('GpgKey');

		// setup lisa
		$k = $this->GpgKey->find('first', array('conditions' => array('key_id' => 'E513B181')));
		$this->assertEqual(!empty($k), true, 'Should find lisa key');
		$this->lisa = $k;
	}

/**
 * Test if the fixtures keys as set in the database
 * @return void
 */
	public function testSetup() {
		$k = $this->GpgKey->find('first', array('conditions' => array('key_id' => '00000000')));
		$this->assertEqual(empty($k), true, 'Should not find any key');
	}

/**
 * Test if importing the key from keyring works
 * @return void
 */
	public function testImport() {
		//$this->testRemove(); //make sure the key is removed if any
		$k = $this->lisa['GpgKey'];
		$this->GpgKey->import($k['key']);
		$f = $this->GpgKey->fingerprint($k['key_id']);
		$this->assertEqual(!empty($f), true, 'Should find lisa key');
		$this->assertEqual($f['uid'], $k['uid'], 'Key UID should be the same');
		$this->assertEqual($f['fingerprint'], $k['fingerprint'], 'Key fingerprint should be the same');
		$this->assertEqual($f['type'], $k['type'], 'Key type should be the same');
		$this->assertEqual($f['key_id'], $k['key_id'], 'Key UID should be the same');
		//$this->assertEqual($f['modified'], $k['modified'], 'Key modified dates should be the same');
	}

/**
 * Test if removing the key from keyring works
 * @return void
 */
	public function testRemove() {
		$k = $this->lisa;
		$o = $this->GpgKey->remove($k['GpgKey']['key_id']);
		$f = $this->GpgKey->fingerprint($k['GpgKey']['key_id']);
		$this->assertEqual(empty($f), true, 'Should not find lisa key');
	}
}

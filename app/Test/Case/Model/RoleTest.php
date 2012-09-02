<?php
/**
 * Role Model Test
 *
 * @copyright	 Copyright 2012, Passbolt.com
 * @license	   http://www.passbolt.com/license
 * @package	   app.Test.Case.Model.RoleTest
 * @since		   version 2.12.7
 */
App::uses('Role', 'Model');

class RoleTest extends CakeTestCase {

	public $fixtures = array('app.role');

	public $autoFixtures = true;

/**
 * Setup
 * @return void
 */
	public function setup() {
		parent::setUp();
		$this->Role = ClassRegistry::init('Role');
	}

/**
 * Test if the default roles as set in the database
 * @return void
 */
	public function testConstants() {
		$r = $this->Role->find('first', array('conditions' => array('name' => String::Uuid())));
		$this->assertEqual(empty($r), true, 'Shouldnt find a role that does not exist');
		$r = $this->Role->find('first', array('conditions' => array('name' => Role::ADMIN)));
		$this->assertEqual(is_array($r), true, 'Default admin role should be present in the database');
		$r = $this->Role->find('first', array('conditions' => array('name' => Role::GUEST)));
		$this->assertEqual(is_array($r), true, 'Default guest role should be present in the database');
		$r = $this->Role->find('first', array('conditions' => array('name' => Role::USER)));
		$this->assertEqual(is_array($r), true, 'Default user role should be present in the database');
		$r = $this->Role->find('first', array('conditions' => array('name' => Role::ROOT)));
		$this->assertEqual(is_array($r), true, 'Default root role should be present in the database');
	}
}

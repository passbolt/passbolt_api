<?php
/**
 * Tag Model Test
 *
 * @copyright	 Copyright 2012, Passbolt.com
 * @license	   http://www.passbolt.com/license
 * @package	   app.Test.Case.Model.TagTest
 * @since		   version 2.12.11
 */
App::uses('Tag', 'Model');

class TagTest extends CakeTestCase {

	public $fixtures = array('app.tag');

	public $autoFixtures = true;

/**
 * Setup
 * @return void
 */
	public function setup() {
		parent::setUp();
		$this->Tag = ClassRegistry::init('Tag');
	}

/**
 * Test if the default tags as set in the fixture
 * @return void
 */
	public function testFixtures() {
		$t = $this->Tag->find('first', array('conditions' => array('name' => String::Uuid())));
		$this->assertEqual(empty($t), true, 'Shouldnt find a tag that does not exist');
		$t = $this->Tag->find('first', array('conditions' => array('name' => 'facebook')));
		$this->assertEqual(is_array($t), true, 'Facebook Tag should be present in the database');
	}
}

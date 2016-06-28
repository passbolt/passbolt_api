<?php
/**
 * Application Model Test
 *
 * @copyright		(c) 2015-present Bolt Softwares Pvt Ltd
 * @package			app.Test.Case.Model.CommentTest
 * @since			version 2.12.12
 * @license			http://www.passbolt.com/license
 */
App::uses('AppModel', 'Model');
App::uses('AppTestCase', 'Test');

class AppModelTest extends AppTestCase {

	public $fixtures = array(
		'app.user',
		'app.role',
		'app.gpgkey',
		'app.file_storage',
		'core.cakeSession'
	);

	public $autoFixtures = false;

/**
 * Setup
 *
 * @return void
 */
	public function setup() {
		parent::setUp();
		$this->AppModel = ClassRegistry::init('AppModel');
	}

/**
 * Test UserExist validation rule
 *
 * @return void
 */
	public function testUserExist() {
		// TODO
	}
}
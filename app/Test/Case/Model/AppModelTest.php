<?php
/**
 * Application Model Test
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
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
<?php
/**
 * Validation Rules Controller Tests
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

App::uses('AppController', 'Controller');
App::uses('ValidationRulesController', 'Controller');

class ValidationRulesControllerTest extends ControllerTestCase {

	public $fixtures = array(
		'app.role',
		'app.user',
		'app.user_agent',
		'app.controller_log',
	);

/**
 * Test view endpoint access permission for anonymous users
 *
 * @return void
 */
	public function testViewAnonymousUserNotAllowed() {
		$this->setExpectedException('HttpException', 'You need to login to access this location');
		$result = $this->testAction('/validation/user.json');
	}

/**
 * Test view endpoint with wrong model name
 *
 * @return void
 */
	public function testViewWrongModelName() {
		$this->User = Common::getModel('User');
		$user = $this->User->findById(Common::uuid('user.id.user'));
		$this->User->setActive($user);

		$this->setExpectedException('HttpException', 'No validation rules defined');
		$this->testAction('/validation/bogus.json');
	}

/**
 * Test validation endpoint access permission
 *
 * @return void
 */
	public function testViewCorrectModelNotEmpty() {
		// test with normal user
		$this->User = Common::getModel('User');
		$user = $this->User->findById(Common::uuid('user.id.user'));
		$this->User->setActive($user);

		$allowedModels = Configure::read('Validation.shared');
		foreach($allowedModels as $model) {
			$json = $this->testAction('/validation/' . $model . '.json', array('return' => 'contents'), true);
			$result = json_decode($json, true);
			$this->assertEquals($result['header']['status'], Status::SUCCESS, '/validation/' . $model . '.json should return something');
			$this->assertNotEmpty($result['header']);
		}
	}
}

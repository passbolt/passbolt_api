<?php
/**
 * Controller Log Test
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('ControllerLog', 'Model');
App::uses('CakeRequest', 'Network');

class ControllerLogTest extends CakeTestCase {

	public $autoFixtures = true;

	public $fixtures = array(
		'app.group',
		'app.groups_user',
		'app.user',
		'app.profile',
		'app.file_storage',
		'app.gpgkey',
		'app.role',
		'app.user_agent',
		'app.controller_log',
		'core.cakeSession'
	);

/**
 * Setup
 * @return void
 */
	public function setup() {
		parent::setUp();
		$this->ControllerLog = ClassRegistry::init('ControllerLog');
	}

/**
 * Test if the default roles as set in the database
 * @return void
 */
	public function testValidHttpMethod() {
		$pass = array('put', 'PUT', 'GET', 'POST', 'DELETE');
		foreach($pass as $check) {
			$result = $this->ControllerLog->validHttpMethod(array('method' => $check));
			$this->assertTrue($result, 'HTTP Method should validate: ' . $check);
		}

		$fail = array('1', 'PUTSH', 'FAIL', '\UPDATE', 'UPDATE', null, '');
		foreach($fail as $check) {
			$result = $this->ControllerLog->validHttpMethod(array('method' => $check));
			$this->assertFalse($result, 'HTTP Method should not validate: ' . $check);
		}
		$result = $this->ControllerLog->validHttpMethod(array('level' => $check));
		$this->assertFalse($result, 'HTTP Method should not validate: ' . $check);
		$result = $this->ControllerLog->validHttpMethod(array($check));
		$this->assertFalse($result, 'HTTP Method should not validate: ' . $check);
		$result = $this->ControllerLog->validHttpMethod(array());
		$this->assertFalse($result, 'HTTP Method should not validate: ' . $check);
	}

/**
 * Test if the level is valid
 * @return void
 */
	public function testValidLogLevel() {
		$pass = array(Status::ERROR, 'error', 'notice', 'success', 'debug', 'warning');
		foreach($pass as $check) {
			$result = $this->ControllerLog->validLogLevel(array('level' => $check));
			$this->assertTrue($result, 'This log level should validate: '. $check);
		}

		$fail = array('ERROR','1', 'fail', 'error0', '\notice', null, '');
		foreach($fail as $check) {
			$result = $this->ControllerLog->validLogLevel(array('level' => $check));
			$this->assertFalse($result, 'This log level should not validate: ' . $check);
		}
		$result = $this->ControllerLog->validLogLevel(array('something' => $check));
		$this->assertFalse($result, 'This log level should not validate: ' . $check);
		$result = $this->ControllerLog->validLogLevel(array($check));
		$this->assertFalse($result, 'This log level should not validate: ' . $check);
		$result = $this->ControllerLog->validLogLevel(array());
		$this->assertFalse($result, 'This log level should not validate: ' . $check);
	}

/**
 * Check a ControllerLog::write()
 */
	public function testWriteSimple() {
		// Mock request object
		$request = new CakeRequest('controller/action/id.json?query=test', true);
		$request->params = array('controller' => 'controller', 'action' => 'action');
		$request->data = array('ControllerLog' => array('check' => 'this'));

		// Check if saving works
		$now = new DateTime('now');
		Configure::write('Log.' . Status::ERROR, true);
		Configure::write('Log.request_data', true);
		ControllerLog::write(Status::ERROR, $request, 'this is a test error', 'test');
		$result = $this->ControllerLog->find('first', array('conditions' => array('message' => 'this is a test error')));
		$this->assertNotEmpty($result, 'The controller log should be found in DB');
		$result = $result['ControllerLog'];

		// Check fields are autopopulated correctly
		$this->assertTrue(Common::isUuid($result['id']), 'ControllerLog id should be set');
		$then = new DateTime($result['created']);
		$this->assertTrue($then >= $now, 'Creation date should be set');
		$this->assertTrue($result['method'] == 'GET', 'The method should be set to get');
		$this->assertTrue($result['controller'] == 'controller', 'The controller name should be set');
		$this->assertTrue($result['action'] == 'action', 'The action name should be set');
		$this->assertTrue($result['user_id'] == Common::uuid('user.id.anonymous'), 'User should be anonymous');
		$this->assertTrue($result['role_id'] == Common::uuid('role.id.anonymous'), 'Role should be guest');
		$this->assertNotEmpty($result['ip'], 'The IP address should be set');

		// Check post data, url parameters and query are deserializable
		$data = json_decode($result['request_data'], true);
		$this->assertNotEmpty($data['params'], 'The request data should contain params');
		$this->assertNotEmpty($data['query'], 'The request data should contain query');
		$this->assertNotEmpty($data['data'], 'The request data should contain empty post data');

		// Check the user agent
		$this->assertTrue(Common::isUuid($result['user_agent_id']), 'User agent id should be set');
		$result = $this->ControllerLog->UserAgent->find('first', array('message' => 'this is a test error'));
		$this->assertNotEmpty($result['UserAgent'], 'The user agent should exist');
	}

/**
 * Check if a validation exception is triggered if wrong level is provided
 * @throws Exception
 */
	public function testWriteValidationExceptionBadLevel() {
		$this->setExpectedException('ValidationException');
		$request = new CakeRequest('something/something', true);
		$request->params = array('controller' => 'something', 'action' => 'something');
		ControllerLog::write('SOMETHING', $request, 'something');
	}

/**
 * Check if a validation exception is triggered if wrong contraller name is provided
 * @throws Exception
 */
	public function testWriteValidationExceptionBadControllerName() {
		$this->setExpectedException('ValidationException');
		$request = new CakeRequest('something/something', true);
		$request->params = array('controller' => 'something very long actually way too long so that it makes the validation fail', 'action' => 'something');
		Configure::write('Log.' . Status::ERROR, true);
		ControllerLog::write(Status::ERROR, $request, 'something');
	}

/**
 * Check if a validation exception is triggered if wrong contraller name is provided
 * @throws Exception
 */
	public function testWriteConfig() {
		$request = new CakeRequest('something/something', true);
		Configure::write('Log.' . Status::ERROR, false);
		$r = ControllerLog::write(Status::ERROR, $request, 'something');
		$this->assertFalse($r, 'Controller Log should not be created if config for that level is set to false');
	}

/**
 * Check IP address validation
 */
	public function testValidIP() {
		$testcases = array(
			// IPV4
			'127.0.0.1' => true,
			'192.168.1.111' => true,
			// IPV6
			'::1' => true,
			'2001:0db8:0a0b:12f0:0000:0000:0000:0001' => true,
			'2001:db8:a0b:12f0::1' => true,
			// Wrong IPs
			'267.1.1.0' => false,
			'localhost' => false,
			'zzz:db8:a0b:12f0::1' => false,
		);
		foreach ($testcases as $testcase => $expect) {
			$this->ControllerLog->set(['ControllerLog' => ['ip' => $testcase]]);
			$msg = 'The ip address ' . $testcase;
			$msg .= $expect ? ' should validate' : ' should not validate';
			$result = ($this->ControllerLog->validates(['fieldList' => ['ip']]) == $expect);
			$this->assertTrue($result, $msg);
		}
	}

	// @TODO More write tests:
	// Test write with non anonymous user
	// Test POST request
	// Test DELETE request
	// Test PUT request
	// Test ERROR with Log.request_data false
	// Test admin role / user

}

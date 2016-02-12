<?php
/**
 * Message Component Test
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @package      app.Test.Controller.PassboltAuthComponent
 * @since        version 2.13.03
 */

// Stuffs we use for the test
App::uses('Controller', 'Controller');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('Router', 'Routing');
App::uses('ComponentCollection', 'Controller');
App::uses('AuthComponent', 'Controller/Component');
App::uses('PassboltAuthComponent', 'Controller/Component');
App::uses('AuthenticationLog', 'Model');

// Uses sessions
if (!class_exists('CakeSession')) {
	require CAKE . 'Model/Datasource/CakeSession.php';
}

// A fake controller to test against
class TestUsersController extends Controller {

	public $components = array(
		'Session',
		'Message',
		'PassboltAuth' => array(
			'throttlingStrategies' => array(
				'throttle' => array(
					3 => array(
						'throttleTime' => '5'
					),
					4 => array(
						'throttleTime' => '15'
					),
					5 => array(
						'throttleTime' => '45'
					),
					6 => array(
						'throttleTime' => '60'
					)
				),
				'blacklist' => array(
					20 => array(
						'interval' => '60',
						'blacklistTime' => '600'
					),
					50 => array(
						'interval' => '1200',
						'blacklistTime' => '2400'
					),
					100 => array(
						'interval' => '3600',
						'blacklistTime' => '7200'
					)
				)
			)
		)
	);
}

class TestPassboltAuthComponent extends PassboltAuthComponent {

	public function _setContext($request) {
		return parent::_setContext($request);
	}

}

// Test Class
class PassboltAuthComponentTest extends CakeTestCase {

	public $MessageComponent = null;

	public $Controller = null;

	public $fixtures = array('app.authenticationBlacklist', 'app.authenticationLog');

	public function setUp($complete=true) {
		parent::setUp();
		// Setup our component and fake test controller
		$Collection = new ComponentCollection();
		$this->PassboltAuthComponent = new TestPassboltAuthComponent($Collection);
		// starts fresh session
		App::import('Model', 'CakeSession');
		$Session = new CakeSession();
		$this->Session = new CakeSession();
		// create request/response and init controller
		$CakeRequest = $this->getMock('CakeRequest');
		$CakeRequest->expects($this->any())->method('clientIp')
									->with()
									->will($this->returnValue('127.0.0.1'));
		$CakeResponse = new CakeResponse();
		$this->Controller = new TestUsersController($CakeRequest, $CakeResponse);
		if ($complete) {
			$this->Controller->Session = $this->Session;
			$this->PassboltAuthComponent->initialize($this->Controller);
		}
		$this->PassboltAuthComponent->startup($this->Controller);
	}

	public function testGetThrottleInterval() {
		$tests = array(
			'1' => 0,
			'2' => 0,
			'3' => 5,
			'4' => 15,
			'7' => 60
		);

		foreach ($tests as $attempt => $result) {
			$resultReturned = $this->PassboltAuthComponent->getThrottleInterval($attempt);
			$this->assertEquals($result, $resultReturned, "The function should have returned $result but returned $resultReturned");
		}
	}

	private function __populateAuthLogs($authenticationLogs) {
		$AuthenticationLog = ClassRegistry::init('AuthenticationLog');
		foreach ($authenticationLogs as $authenticationLog) {
			$AuthenticationLog->create();
			$AuthenticationLog->save($authenticationLog);
		}
	}

	public function testShouldBlacklist() {
		// Check when no entry is there in database
		$cr = new CakeRequest();
		$cr->data['User']['username'] = 'user@test.com';
		$this->PassboltAuthComponent->_setContext($cr);
		$res = $this->PassboltAuthComponent->shouldBlacklist();
		$this->assertEquals($res, false, "testShouldBlacklist : the test should have returned false but returned $res");

		// scenario 1 : populate database with 19 entries with an interval of one second each
		$logs = array();
		$now = time();
		for ($i = 1; $i < 20; $i++) {
			$logs[] = array(
				'ip' => '127.0.0.1',
				'username' => "user$i@test.com",
				'created' => date('Y-m-d H:i:s', $now + $i)
			);
		}
		$this->__populateAuthLogs($logs);
		$res = $this->PassboltAuthComponent->shouldBlacklist();
		$this->assertEquals($res, false, "testShouldBlacklist : the test should have returned false but returned $res");

		// scenario 2 : with one more entry (20), but out of the interval
		$logs = array(array(
			'ip' => '127.0.0.1',
			'username' => "user_more@test.com",
			'created' => date('Y-m-d H:i:s', $now - 100)
		));
		$this->__populateAuthLogs($logs);
		$res = $this->PassboltAuthComponent->shouldBlacklist();
		$this->assertEquals($res, false, "testShouldBlacklist : the test should have returned false but returned $res");

		// scenario 3 : with one more entry (21), but within the range this time
		$logs = array(array(
			'ip' => '127.0.0.1',
			'username' => "user_evenmore@test.com",
			'created' => date('Y-m-d H:i:s', $now + 10)
		));
		$this->__populateAuthLogs($logs);
		$res = $this->PassboltAuthComponent->shouldBlacklist();
		$this->assertEquals($res, 600, "testShouldBlacklist : the test should have returned 600 but returned $res");

		// scenario 4 : with 150 entries, in a one hour interval
		$logs = array();
		for ($i = 1; $i < 150; $i++) {
			$logs[] = array(
				'ip' => '127.0.0.1',
				'username' => "user_$i@test.com",
				'created' => date('Y-m-d H:i:s', $now - 3000)
			);
		}
		$this->__populateAuthLogs($logs);
		$res = $this->PassboltAuthComponent->shouldBlacklist();
		$this->assertEquals($res, 7200, "testShouldBlacklist : the test should have returned 7200 but returned $res");
	}

	public function tearDown() {
		parent::tearDown();
		// Clean up after we're done
		unset($this->PassboltAuthComponent);
		unset($this->Controller);
	}
}

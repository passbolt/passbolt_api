<?php
/**
 * Blacklist Component Test
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @package      app.Test.Controller.BlacklistComponentTest
 * @since        version 2.13.03
 */

// Stuffs we use for the test
App::uses('Controller', 'Controller');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('Router', 'Routing');
App::uses('ComponentCollection', 'Controller');
App::uses('IpAddressComponent', 'Controller/Component');
App::uses('BlacklistComponent', 'Controller/Component');
App::uses('AuthenticationBlacklist', 'Model');

// A fake controller to test against
class TestBlacklistController extends Controller {

	public $components = array(
		'IpAddress',
		'Blacklist'
	);
}

// Test Class
class BlacklistComponentTest extends CakeTestCase {

	public $fixtures = array('app.authenticationBlacklist');

	public $BlacklistComponent = null;

	public $Controller = null;

	public $AuthenticationBlackList = null;

	public function setUp($complete=true) {
		parent::setUp();
		// Generate mock controller without Authentication
		$Collection = new ComponentCollection();
		$this->BlacklistComponent = new BlacklistComponent($Collection);

		// create request/response and init controller
		//$CakeRequest = new CakeRequest();
		$CakeRequest = $this->getMock('CakeRequest');
		$CakeRequest->expects($this->any())->method('clientIp')
									->with()
									->will($this->returnValue('127.0.0.1'));
		$CakeResponse = new CakeResponse();
		$this->Controller = new TestBlacklistController($CakeRequest, $CakeResponse);

		// For some reason, the line below has to be added manually as the component is not instantiated automatically
		$this->Controller->IpAddress = new IpAddressComponent($Collection);

		if ($complete) {
			$this->BlacklistComponent->initialize($this->Controller);
		}
		//$this->BlacklistComponent->startup($this->Controller);
		$this->AuthenticationBlacklist = ClassRegistry::init('AuthenticationBlacklist');
	}

	public function testIsIpInBlackList() {
		// Test when ip is not blacklisted
		$this->assertFalse($this->BlacklistComponent->isIpInBlacklist(), "isBlacklist should have returned false but returned something else");

		// Test when ip is blacklisted
		$this->AuthenticationBlacklist->create();
		$bl = $this->AuthenticationBlacklist->save(array(
			'ip' => '127.0.0.1',
			'expiry' => date('Y-m-d H:i:s', time() + 100)
		));
		$ipAddress = $this->Controller->request->clientIp();
		$this->assertTrue($this->BlacklistComponent->isIpInBlacklist(), "isBlacklist with ip $ipAddress should have returned true but returned something else");
	}
}
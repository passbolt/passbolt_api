<?php
/**
 * IpAddress Component Test
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @package      app.Test.Controller.IpAddressComponentTest
 * @since        version 2.13.03
 */

// Stuffs we use for the test
App::uses('Controller', 'Controller');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('Router', 'Routing');
App::uses('ComponentCollection', 'Controller');
App::uses('IpAddressComponent', 'Controller/Component');

// A fake controller to test against
class TestIpAddressController extends Controller {

	public $components = array(
		'IpAddress'
	);
}

// Test Class
class IpAddressComponentTest extends CakeTestCase {

	public $MessageComponent = null;

	public $Controller = null;

	public function setUp($complete=true) {
		parent::setUp();
		// Setup our component and fake test controller
		$Collection = new ComponentCollection();
		$this->IpAddressComponent = new IpAddressComponent($Collection);

		// create request/response and init controller
		$CakeRequest = new CakeRequest();
		$CakeResponse = new CakeResponse();
		$this->Controller = new TestIpAddressController($CakeRequest, $CakeResponse);
		if ($complete) {
			$this->IpAddressComponent->initialize($this->Controller);
		}
		$this->IpAddressComponent->startup($this->Controller);
	}

	public function testInRange() {
		$tests = array(
			array('ip' => '80.140.2.2', 'range' => '80.140.*.*', 'result' => true),
			array('ip' => '80.141.2.2', 'range' => '80.140.*.*', 'result' => false),
			array('ip' => '80.140.2.3', 'range' => '80.140/16', 'result' => true),
			array('ip' => '1.2.3.4',  'range' => '1.2.3.0-1.2.255.255', 'result' => true),
			array('ip' => '90.35.6.12', 'range' => '80.140.0.0-80.140.255.255', 'result' => false),
			array('ip' => '80.76.201.37', 'range' => '80.76.201.32/27', 'result' => true),
			array('ip' => '81.76.201.37', 'range' => '80.76.201.32/27', 'result' => false),
			array('ip' => '80.76.201.38', 'range' => '80.76.201.32/255.255.255.224', 'result' => true),
			array('ip' => '80.76.201.39', 'range' => '80.76.201.32/255.255.255.*', 'result' => true),
			array('ip' => '80.76.201.40', 'range' => '80.76.201.64/27', 'result' => false),
			array('ip' => '192.168.1.42', 'range' => '192.168.3.0/24', 'result' => false),
			array('ip' => '128.0.0.0', 'range' => '127.0.0.0-129.0.0.0', 'result' => true)
		);
		$this->setUp();

		foreach ($tests as $test) {
			$result = $this->IpAddressComponent->inRange($test['ip'], $test['range']);
			$this->AssertEquals($result, $test['result'], "inRange with ip {$test['ip']} and range {$test['range']} should have returned {$test['result']}");
		}
	}
}
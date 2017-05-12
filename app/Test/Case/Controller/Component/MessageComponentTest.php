<?php
/**
 * Message Component Test
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @package      app.Test.Controller.MessageComponent
 * @since        version 2.12.7
 */

// Stuffs we use for the test
App::uses('Controller', 'Controller');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('Router', 'Routing');
App::uses('ComponentCollection', 'Controller');
App::uses('MessageComponent', 'Controller/Component');
App::uses('ControllerLog', 'Model');

// Uses sessions
if (!class_exists('CakeSession')) {
	require CAKE . 'Model/Datasource/CakeSession.php';
}

// A fake controller to test against
class TestMessageController extends Controller {

	public $components = array(
		'Session',
		'Message'
	);
}

// Test Class
class MessageComponentTest extends CakeTestCase {

	public $fixtures = array(
		'core.cakeSession',
		'app.controllerLog',
		'app.user',
		'app.role',
		'app.user_agent',
	);

	public $MessageComponent = null;

	public $Controller = null;
	public $ControllerLog = null;

	public function setUp($complete = true) {
		parent::setUp();
		// Setup our component and fake test controller
		$Collection = new ComponentCollection();
		$this->MessageComponent = new MessageComponent($Collection);
		// starts fresh session
		App::import('Model', 'CakeSession');
		$Session = new CakeSession();
		$this->Session = new CakeSession();
		// create request/response and init controller
		$CakeRequest = new CakeRequest();
		$CakeRequest->params['controller'] = 'TestController';
		$CakeRequest->params['action'] = 'TestAction';
		$CakeResponse = new CakeResponse();
		$this->Controller = new TestMessageController($CakeRequest, $CakeResponse);
		if ($complete) {
			$this->Controller->Session = $this->Session;
			$this->MessageComponent->initialize($this->Controller);
		}
		$this->MessageComponent->startup($this->Controller);

		$this->ControllerLog = Common::getModel('ControllerLog');
	}

	public function tearDown() {
		parent::tearDown();
		// Clean up after we're done.
		unset($this->MessageComponent);
		unset($this->Controller);
	}

	public function testSuccess() {
		Configure::write('Log.' . Status::SUCCESS, true);
		$this->MessageComponent->success('success test1', array('throw' => false));
		$this->assertEquals(count($this->MessageComponent->response), true, 'there should be one success message present');
		$result = $this->ControllerLog->find('first', array('conditions' => array('message' => 'success test1')));
		$this->assertNotEmpty($result, 'There should be a controller log entry for the success message');
	}
}

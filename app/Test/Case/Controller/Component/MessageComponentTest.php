<?php
/**
 * Message Component Test
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
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
		$this->MessageComponent->reset();

		$this->ControllerLog = Common::getModel('ControllerLog');
	}

	public function tearDown() {
		parent::tearDown();
		// Clean up after we're done.
		$this->MessageComponent->reset();
		unset($this->MessageComponent);
		unset($this->Controller);
	}

	public function testInitialize() {
		$this->setup(false);
		// Test session detection
		try {
			$this->MessageComponent->initialize($this->Controller);
			$this->assertEquals(true, false, 'Initialize should trow an exception');
		} catch (Exception $e) {
			$this->assertEquals(true, true, 'Initialize should trow an exception');
		}
		$this->setup();
		$this->assertEquals(isset($this->MessageComponent->Session), true, 'Session should be present');
	}

	public function testReusingMessageFromSession() {
		// test reusing message from sessions
		$this->MessageComponent->error('error test', array('throw' => false));
		$this->MessageComponent->beforeRedirect($this->Controller, '/');
		$msg = $this->Controller->Session->read($this->MessageComponent->sessionKey);
		$this->assertEquals($msg[0]['header']['message'], 'error test', 'there should be a message carried over using Session');
		$this->assertEquals(count($this->MessageComponent->messages), 1, 'there should be a message carried over using Session');
	}

	public function testErrorHttpException() {
		$this->setExpectedException('HttpException', 'error test1');
		$this->MessageComponent->error('error test1');
	}

	public function testErrorHttpException2() {
		$this->setExpectedException('HttpException', 'error test 500');
		$this->MessageComponent->error('error test 500', array('code' => '500'));
	}

	public function testErrorUnauthorizedException() {
		$this->setExpectedException('UnauthorizedException', 'error test 401');
		$this->MessageComponent->error('error test 401', array('code' => '401'));
	}

	public function testErrorForbiddenException() {
		$this->setExpectedException('ForbiddenException', 'error test 403');
		$this->MessageComponent->error('error test 403', array('code' => '403'));
	}

	public function testErrorNotFoundException() {
		$this->setExpectedException('NotFoundException', 'error test 404');
		$this->MessageComponent->error('error test 404', array('code' => '404'));
	}

	public function testErrorMethodNotAllowedException() {
		$this->setExpectedException('MethodNotAllowedException', 'error test 405');
		$this->MessageComponent->error('error test 405', array('code' => '405'));
	}

	public function testErrorNotImplementedException() {
		$this->setExpectedException('NotImplementedException', 'error test 501');
		$this->MessageComponent->error('error test 501', array('code' => '501'));
	}

	public function testErrorNoThrow() {
		Configure::write('Log.' . Status::ERROR, true);
		$this->MessageComponent->error('error test1', array('throw' => false));
		$this->assertEquals(count($this->MessageComponent->messages), true, 'there should be one message present');
		$result = $this->ControllerLog->find('first', array('conditions' => array('message' => 'error test1')));
		$this->assertNotEmpty($result, 'There should be a controller log entry for the error message');

		Configure::write('Log.' . Status::ERROR, false);
		$this->MessageComponent->error('error test 2', array('throw' => false));
		$this->assertEquals(count($this->MessageComponent->messages), 2, 'there should be two messages present');
		$result2 = $this->ControllerLog->find('first', array('conditions' => array('message' => 'error test 2')));
		$this->assertEmpty($result2, 'There should not be a controller log entry for the error message');
	}

	public function testErrorTitle() {
		$this->MessageComponent->error('error test1', array('throw' => false, 'title' => 'title test'));
		$this->assertEquals(count($this->MessageComponent->messages), true, 'there should be one message present');
		$this->assertEquals($this->MessageComponent->messages[0]['header']['title'], 'title test', 'title should be set if provided as option');
	}

	public function testErrorWithBody() {
		$this->MessageComponent->error('error test3', array('throw' => false, 'body' => 'body test'));
		$this->assertEquals($this->MessageComponent->messages[0]['body'], 'body test', 'body should be allowed to set using __add() $options parameter');
	}

	public function testErrorRedirect() {
		$this->MessageComponent->error('error test4', array('throw' => false, 'redirect' => true));
		$this->MessageComponent->controller->response = $this->getMock('CakeResponse', array('header', 'statusCode'));
	}

	public function testWarning() {
		Configure::write('Log.' . Status::WARNING, true);
		$this->MessageComponent->warning('warning test1', array('throw' => false));
		$this->assertEquals(count($this->MessageComponent->messages), true, 'there should be one message present');
		$result = $this->ControllerLog->find('first', array('conditions' => array('message' => 'warning test1')));
		$this->assertNotEmpty($result, 'There should be a controller log entry for the warning message');

		Configure::write('Log.' . Status::WARNING, false);
		$this->MessageComponent->warning('warning test 2', array('throw' => false));
		$this->assertEquals(count($this->MessageComponent->messages), 2, 'there should be two messages present');
		$result2 = $this->ControllerLog->find('first', array('conditions' => array('message' => 'warning test 2')));
		$this->assertEmpty($result2, 'There should not be a controller log entry for the warning message');
	}

	public function testNotice() {
		Configure::write('Log.' . Status::NOTICE, true);
		$this->MessageComponent->notice('notice test1', array('throw' => false));
		$this->assertEquals(count($this->MessageComponent->messages), true, 'there should be one notice message present');
		$result = $this->ControllerLog->find('first', array('conditions' => array('message' => 'notice test1')));
		$this->assertNotEmpty($result, 'There should be a controller log entry for the notice message');

		Configure::write('Log.' . Status::NOTICE, false);
		$this->MessageComponent->notice('notice test 2', array('throw' => false));
		$this->assertEquals(count($this->MessageComponent->messages), 2, 'there should be two notice messages present');
		$result2 = $this->ControllerLog->find('first', array('conditions' => array('message' => 'notice test 2')));
		$this->assertEmpty($result2, 'There should not be a controller log entry for the notice message');
	}

	public function testSuccess() {
		Configure::write('Log.' . Status::SUCCESS, true);
		$this->MessageComponent->success('success test1', array('throw' => false));
		$this->assertEquals(count($this->MessageComponent->messages), true, 'there should be one success message present');
		$result = $this->ControllerLog->find('first', array('conditions' => array('message' => 'success test1')));
		$this->assertNotEmpty($result, 'There should be a controller log entry for the success message');

		Configure::write('Log.' . Status::SUCCESS, false);
		$this->MessageComponent->success('success test 2', array('throw' => false));
		$this->assertEquals(count($this->MessageComponent->messages), 2, 'there should be two success messages present');
		$result2 = $this->ControllerLog->find('first', array('conditions' => array('message' => 'success test 2')));
		$this->assertEmpty($result2, 'There should not be a controller log entry for the success message');
	}

	public function testDebug() {
		Configure::write('Log.' . Status::DEBUG, true);
		$this->MessageComponent->debug('debug test1', array('throw' => false));
		$this->assertEquals(count($this->MessageComponent->messages), true, 'there should be one debug message present');
		$result = $this->ControllerLog->find('first', array('conditions' => array('message' => 'debug test1')));
		$this->assertNotEmpty($result, 'There should be a controller log entry for the debug message');

		Configure::write('Log.' . Status::DEBUG, false);
		$this->MessageComponent->debug('debug test 2', array('throw' => false));
		$this->assertEquals(count($this->MessageComponent->messages), 2, 'there should be two debug messages present');
		$result2 = $this->ControllerLog->find('first', array('conditions' => array('message' => 'debug test 2')));
		$this->assertEmpty($result2, 'There should not be a controller log entry for the debug message');
	}

	public function testSetBodyEmpty() {
		$this->assertEquals($this->MessageComponent->setBody(), false, 'set body should fail when empty');
		$this->MessageComponent->error('error test1', array('throw' => false));
		$this->MessageComponent->setBody();
		$this->assertEquals($this->MessageComponent->setBody(), false, 'set body should fail when empty');
	}

	public function testBeforeRender() {
		$this->MessageComponent->error('error test1', array('throw' => false));
		$this->Controller->set('data', array('additional data'));
		$this->MessageComponent->beforeRender($this->Controller);
		$this->assertEquals(count($this->Controller->viewVars[$this->MessageComponent->controllerVar]['body']), 1, 'there should be one message present in the controller viewvars');
	}
}

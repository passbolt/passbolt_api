<?php
/**
 * Message Component Test
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @license      http://www.passbolt.com/license
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
	);

	public $MessageComponent = null;

	public $Controller = null;

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
		$CakeResponse = new CakeResponse();
		$this->Controller = new TestMessageController($CakeRequest, $CakeResponse);
		if ($complete) {
			$this->Controller->Session = $this->Session;
			$this->MessageComponent->initialize($this->Controller);
		}
		$this->MessageComponent->startup($this->Controller);
		$this->MessageComponent->reset();
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

	public function testError() {
		$this->setup();
		$this->setExpectedException('HttpException', "error test1");
		$this->MessageComponent->error('error test1');
	}

	public function testErrorNoThrow() {
		$this->setup();
		$this->MessageComponent->error('error test1', array('throw' => false));
		$this->assertEquals(count($this->MessageComponent->messages), true, 'there should be one message present');
		$this->MessageComponent->error('error test2', array('throw' => false));
		$this->assertEquals(count($this->MessageComponent->messages), 2, 'there should be two messages present');
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
		$this->setup();
		$this->MessageComponent->warning('warning test');
		$this->assertEquals(count($this->MessageComponent->messages), true, 'there should be one message present');
	}

	public function testNotice() {
		$this->setup();
		$this->MessageComponent->notice('notice test');
		$this->assertEquals(count($this->MessageComponent->messages), true, 'there should be one message present');
	}

	public function testSuccess() {
		$this->setup();
		$this->MessageComponent->success('success test');
		$this->assertEquals(count($this->MessageComponent->messages), true, 'there should be one message present');
	}

	public function testDebug() {
		$this->setup();
		//@todo should only show when in debug mode
		$this->MessageComponent->debug('debug test');
		$this->assertEquals(count($this->MessageComponent->messages), true, 'there should be one message present');
	}

	public function testSetBodyEmpty() {
		$this->setup();
		$this->assertEquals($this->MessageComponent->setBody(), false, 'set body should fail when empty');
		$this->MessageComponent->error('error test1', array('throw' => false));
		$this->MessageComponent->setBody();
		$this->assertEquals($this->MessageComponent->setBody(), false, 'set body should fail when empty');
	}

	public function testBeforeRender() {
		$this->setup();
		$this->MessageComponent->error('error test1', array('throw' => false));
		$this->Controller->set('data', array('additional data'));
		$this->MessageComponent->beforeRender($this->Controller);
		$this->assertEquals(count($this->Controller->viewVars[$this->MessageComponent->controllerVar]['body']), 1, 'there should be one message present in the controller viewvars');
	}
}

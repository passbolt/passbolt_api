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
		'Session','Message'
	);
}

// Test Class
class MessageComponentTest extends CakeTestCase {

	public $MessageComponent = null;

	public $Controller = null;

	public function setUp($complete=true) {
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
			$this->MessageComponent->initialize(&$this->Controller);
		}
		$this->MessageComponent->startup($this->Controller);
	}

	public function testInitialize() {
		$this->setup(false);
		// Test session detection
		try {
			$this->MessageComponent->initialize($this->Controller);
			$this->assertEqual(true,false,'Initialize should trow an exception');
		} catch(Exception $e) {
			$this->assertEqual(true,true,'Initialize should trow an exception');
		}
		$this->setup();
		$this->assertEqual(isset($this->MessageComponent->Session),true,'Session should be present');
		// test reusing message from sessions

		$this->MessageComponent->error('error test');
		$this->MessageComponent->beforeRedirect($this->Controller,'/');
		$msg = $this->Controller->Session->read($this->MessageComponent->sessionKey);
		$this->assertEqual($msg[0]['header']['message'],'error test','there should be a message carried over using Session');
		//$this->assertEqual(count($this->MessageComponent->messages),1,'there should be a message carried over using Session');
	}

	public function testError() {
		$this->setup();
		$this->MessageComponent->error('error test1');
		$this->assertEqual(count($this->MessageComponent->messages), true, 'there should be one message present');
		$this->MessageComponent->error('error test2');
		$this->assertEqual(count($this->MessageComponent->messages), 2, 'there should be two messages present');
		$this->MessageComponent->error('error test3',array('body' => 'body test'));
		$this->assertEqual($this->MessageComponent->messages[2]['body'], 'body test', 'body should be allowed to set using __add() $options parameter');
		
		//$this->MessageComponent->Controller->redirect('http://cakephp.org', 301, false);
		$this->MessageComponent->error('error test4',array('redirect' => true));
		$this->MessageComponent->Controller->response = $this->getMock('CakeResponse', array('header', 'statusCode'));
		$this->MessageComponent->Controller->response
			->expects($this->any())
			->method('header')
			->with('Location', 'http://cakephp.org');
		$this->MessageComponent->error('error test4',array('redirect' => 'http://cakephp.org'));
		//$this->assertEqual($this->MessageComponent->messages[1]['body'], 'body test', 'body should be allowed to set usi  		
		//$this->MessageComponent->error('error test4',array('redirect' => '/ss'));
		
	}

	public function testWarning() {
		$this->setup();
		$this->MessageComponent->warning('warning test');
		$this->assertEqual(count($this->MessageComponent->messages), true, 'there should be one message present');
	}

	public function testNotice() {
		$this->setup();
		$this->MessageComponent->notice('notice test');
		$this->assertEqual(count($this->MessageComponent->messages), true, 'there should be one message present');
	}

	public function testSuccess() {
		$this->setup();
		$this->MessageComponent->success('success test');
		$this->assertEqual(count($this->MessageComponent->messages), true, 'there should be one message present');
	}

	public function testDebug() {
		$this->setup();
		//@todo should only show when in debug mode
		$this->MessageComponent->debug('debug test');
		$this->assertEqual(count($this->MessageComponent->messages), true, 'there should be one message present');
	}

	public function testSetBody() {
		$this->setup();
		$this->assertEqual($this->MessageComponent->setBody(), false, 'set body should fail with no messages');
		$this->MessageComponent->error('error test1');
		$this->MessageComponent->setBody();
		$this->assertEqual($this->MessageComponent->setBody(), false, 'set body should fail when empty');
	}

	public function testBeforeRender() {
		$this->setup();
		$this->MessageComponent->error('error test1');
		$this->Controller->set('data', array('additional data'));
		$this->MessageComponent->beforeRender($this->Controller);
		$this->assertEqual(count($this->Controller->viewVars[$this->MessageComponent->controllerVar]), 1, 'there should be one message present in the controller viewvars');
	}

	public function tearDown() {
		parent::tearDown();
		// Clean up after we're done
		unset($this->MessageComponent);
		unset($this->Controller);
	}
}

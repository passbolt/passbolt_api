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
		$this->MessageComponent->error('error test');
	}

	public function testWarning() {
		$this->MessageComponent->warning('warning test');
	}

	public function testNotice() {
		$this->MessageComponent->notice('notice test');
	}

	public function testFatal() {
		//$this->MessageComponent->error('fatal test',array(Message::FATAL => true));
	}

	public function testSuccess() {
		$this->MessageComponent->success('success test');
	}

	public function testDebug() {
		//@todo should only show when in debug mode
		$this->MessageComponent->debug('debug test');
	}

	public function tearDown() {
		parent::tearDown();
		// Clean up after we're done
		unset($this->MessageComponent);
		unset($this->Controller);
	}
}

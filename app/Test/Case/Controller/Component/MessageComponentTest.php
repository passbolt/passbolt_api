<?php
/**
 * Message Component Test
 *
 * @copyright     Copyright 2012, Passbolt.com
 * @license       http://www.passbolt.com/license
 * @package       app.Test.Controller.MessageComponent
 * @since         version 2.12.7
 */

// Stuffs we use for the test
App::uses('Controller', 'Controller');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('ComponentCollection', 'Controller');
App::uses('CakeSession', 'Network');
App::uses('MessageComponent', 'Controller/Component');

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

  public function setUp() {
    parent::setUp();
    // Setup our component and fake test controller
    $Collection = new ComponentCollection();
    $this->MessageComponent = new MessageComponent($Collection);
    $this->Session = new CakeSession();
    $CakeRequest = new CakeRequest();
    $CakeResponse = new CakeResponse();
    $this->Controller = new TestMessageController($CakeRequest, $CakeResponse);
    $this->MessageComponent->startup($this->Controller);
    $this->MessageComponent->initialize($this->Controller);
  }

  public function testInitialize() {
    $this->MessageComponent->warning('warning test');
    $this->MessageComponent->error('error test');
    $this->MessageComponent->notice('notice test');
    $this->MessageComponent->debug('success test');
    $this->MessageComponent->success('warning test');
    // Test our adjust method with different parameter settings
    $this->assertEqual(isset($this->MessageComponent->Session),true,'Session should be present');
  }

  public function tearDown() {
    parent::tearDown();
    // Clean up after we're done
    unset($this->MessageComponent);
    unset($this->Controller);
  }
}

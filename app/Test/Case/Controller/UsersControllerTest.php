<?php
/**
 * Users Controller Tests
 *
 * @copyright     Copyright 2012, Passbolt.com
 * @license       http://www.passbolt.com/license
 * @package       app.Test.Case.Controller.UsersControllerTest
 * @since         version 2.12.9
 */
App::uses('UsersController', 'Controller');
App::uses('User', 'Model');
App::uses('Role', 'Model');
App::uses('CakeSession', 'Model');
App::uses('CakeSession', 'Model/Datasource');


class UsersControllerTest extends ControllerTestCase {
    public $fixtures = array('app.user', 'app.role');
		public $user;
		public $session;

    public function setUp() {
      parent::setUp();
			$this->user = new User();
			$this->user->useDbConfig = 'test';
  		$this->session = new CakeSession();
    }

    public function tearDown() {
      parent::tearDown();
			$this->session->destroy();
    }

    public function testIndex() {
			// test with anonymous user
      $result = json_decode($this->testAction('/users.json',array('return' => 'contents'), true));
      $this->assertEqual($result->header->status, Message::ERROR, '/users should not be accessible without being logged in');

			// test with normal user
			$kk = $this->user->findByUsername('cedric@passbolt.com');
 		  $this->user->setActive($kk);

      $result = json_decode($this->testAction('/users.json',array('return' => 'contents'), true));
			//pr($result);die;
//      $this->assertEqual($result->header->status,Message::SUCCESS,'/users return something'); 


    }
}

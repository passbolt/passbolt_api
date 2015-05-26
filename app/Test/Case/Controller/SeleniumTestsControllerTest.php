<?php
/**
 * SeleniumTests Controller Tests
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @package      app.Test.Case.Controller.SeleniumTestsControllerTest
 * @license      http://www.passbolt.com/license
 * @since        version 2.12.12
 */
App::uses('AppController', 'Controller');
App::uses('ComponentCollection', 'Controller');
App::uses('EmailNotificatorComponent', 'Controller/Component');

/**
 * Class SeleniumTestsControllerTest
 */
class SeleniumTestsControllerTest extends ControllerTestCase {
	// Fixtures.
	public $fixtures
		= array(
			'app.user',
			'app.group',
			'app.groups_user',
			'app.role',
			'app.profile',
			'app.gpgkey',
			'app.file_storage',
			'app.authenticationLog',
			'app.authenticationBlacklist',
			'app.emailQueue',
			'core.cakeSession',
		);

	/**
	 * Build an EmailNotificatorComponent and return it.
	 * @return EmailNotificatorComponent
	 */
	private function __getEmailNotificatorComponent() {
		$CakeRequest = $this->getMock('CakeRequest');
		$CakeRequest->expects($this->any())->method('clientIp')
			->with()
			->will($this->returnValue('127.0.0.1'));
		$Collection = new ComponentCollection();
		$CakeResponse = new CakeResponse();
		$this->Controller = new Controller($CakeRequest, $CakeResponse);
		$EmailNotificatorComponent = new EmailNotificatorComponent($Collection);
		$EmailNotificatorComponent->initialize(new Controller(), array());
		return $EmailNotificatorComponent;
	}

	/**
	 * setUp()
	 */
	public function setUp() {
		parent::setUp();
		$this->EmailNotification = Common::getModel('EmailNotification');
		$this->User = Common::getModel('User');
		$this->myriam = $this->User->findByUsername('myriam@passbolt.com');
		$this->anonymous = $this->User->findByUsername('anonymous@passbolt.com');
		$this->EmailNotificatorComponent = $this->__getEmailNotificatorComponent();
	}

	/**
	 * Test showLastEmail entry point when selenimum is not active in the config.
	 * It should obviously not work and redirect the user.
	 */
	public function testShowLastEmailEntryPointNotAllowed() {
		// Deactivate selenium entry point.
		Configure::write('App.selenium.active', false);
		// Call the entry point.
		$this->testAction("/seleniumTests/showLastEmail/john@passbolt.com", array(
				'return' => 'contents',
			), true);
		$this->assertTrue(isset($this->headers['Location']) && !empty($this->headers['Location']));
	}

	/**
	 * Test showLastEmail entry point when the entry point is allowed and the username is not valid.
	 */
	public function testShowLastEmailEntryPointAllowedUsernameNotValid() {
		// Deactivate selenium entry point.
		Configure::write('App.selenium.active', true);
		// Expect exception
		$this->setExpectedException('HttpException', 'The username doesn\'t exist');
		// Call the entry point.
		$this->testAction("/seleniumTests/showLastEmail/john@passbolt.com", array(
				'return' => 'contents',
			), true);
	}

	/**
	 * Test showLastEmail entry point when the entry point is allowed and the username is valid.
	 * Test that the email is shown on the screen.
	 */
	public function testShowLastEmailEntryPointAllowedUsernameIsValid() {
		// Deactivate selenium entry point.
		Configure::write('App.selenium.active', true);
		$data = array(
			'creator_id' => $this->anonymous['User']['id'],
			'token' => 'xxx',
		);
		// Put an email in the queue.
		$this->EmailNotificatorComponent->accountCreationNotification($this->myriam['User']['id'], $data);
		// Call the entry point.
		$res = $this->testAction("/seleniumTests/showLastEmail/myriam@passbolt.com", array(
				'return' => 'contents',
			), true);
		// Assert that there is no redirection.
		$this->assertFalse(isset($this->headers['Location']) && !empty($this->headers['Location']));
		// Assert that there is a html tag.
		$this->assertRegexp('/<html/', $this->contents);
		// Assert that the page contains the text get started.
		$this->assertTextContains('get started', $this->contents);
	}
}

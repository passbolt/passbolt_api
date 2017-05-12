<?php
/**
 * SeleniumTests Controller Tests
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @package      app.Test.Case.Controller.SeleniumTestsControllerTest
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @since        version 2.12.12
 */
App::uses('AppController', 'Controller');
App::uses('ComponentCollection', 'Controller');
App::uses('EmailNotificatorComponent', 'Controller/Component');
App::uses('HttpSocket', 'Network/Http');

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
			'app.emailQueue',
			'core.cakeSession',
			'app.user_agent',
			'app.controller_log'
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
		$this->marlyn = $this->User->findById(Common::uuid('user.id.marlyn'));
		$this->anonymous = $this->User->findById(Common::uuid('user.id.anonymous'));
		$this->EmailNotificatorComponent = $this->__getEmailNotificatorComponent();
	}

	/**
	 * Test showLastEmail entry point when selenimum is not active in the config.
	 * It should obviously not work and redirect the user.
	 */
	public function testShowLastEmailEntryPointNotAllowed() {
		// Deactivate selenium entry point.
		Configure::write('App.selenium.active', false);
		Configure::write('debug', 0);
		// Call the entry point.
		$this->setExpectedException('ForbiddenException');
		$this->testAction("/seleniumTests/showLastEmail/john@passbolt.com", array(
				'return' => 'results',
		), true);
	}

	/**
	 * Test if Selenium entry point is marked as not allowed, and debug is set to true. (Should redirect too)
	 */
	public function testShowLastEmailEntryPointNotAllowed2()
	{
		Configure::write('App.selenium.active', true);
		Configure::write('debug', 0);
		// Call the entry point.
		$this->setExpectedException('ForbiddenException');
		$this->testAction("/seleniumTests/showLastEmail/john@passbolt.com", array(
				'return' => 'contents',
		), true);
	}

	/**
	 * Test if Selenium entry point is marked as allowed, and debug is set to false. (Should redirect too)
	 */
	public function testShowLastEmailEntryPointNotAllowed3() {
		Configure::write('App.selenium.active', false);
		Configure::write('debug', 1);
		// Call the entry point.
		$this->setExpectedException('ForbiddenException');
		$this->testAction("/seleniumTests/showLastEmail/john@passbolt.com", array(
				'return' => 'contents',
			), true);
	}

	/**
	 * Test showLastEmail entry point when the entry point is allowed and the username is not valid.
	 */
	public function testShowLastEmailEntryPointAllowedUsernameNotValid() {
		// Deactivate selenium entry point.
		Configure::write('App.selenium.active', true);
		Configure::write('debug', 1);
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
		Configure::write('debug', 1);
		$data = array(
			'creator_id' => $this->anonymous['User']['id'],
			'token' => 'xxx',
		);
		// Put an email in the queue.
		$this->EmailNotificatorComponent->accountCreationNotification($this->marlyn['User']['id'], $data);
		// Call the entry point.
		$res = $this->testAction("/seleniumTests/showLastEmail/marlyn@passbolt.com", array(
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

<?php
/**
 * SeleniumTests Controller
 *
 * @copyright		Copyright 2012, Passbolt.com
 * @license			http://www.passbolt.com/license
 * @package			app.Controller.SeleniumTestsController
 * @since			version 2.13.3
 */

// Uses EmailQueue.
App::uses('EmailQueue', 'Plugin/EmailQueue/Model');
App::uses('ShellDispatcher', 'Console');
App::import('Console/Command', 'AppShell');
App::import('Console/Command', 'InstallShell');

/**
 * Class SeleniumTestsController
 * All entry points of the selenium controller can only be called if the app is in debug mode
 * and the App.selenium.active key is set to true in the config.
 */
class SeleniumTestsController extends AppController {

	public $uses = array(
		'User',
		'EmailQueue',
	);

	// Configuration key to check if selenium entry points are configured.
	private $configKey = 'App.selenium.active';

	/**
	 * Check if the selenium entry point is allowed by the configuration.
	 * @return bool
	 */
	private function __isSeleniumAllowed() {
		$seleniumAllowed = Configure::read($this->configKey) === true
			&& Configure::read('debug') > 0;
		return $seleniumAllowed;
	}

	/**
	 * beforeFilter().
	 */
	function beforeFilter() {
		// If Selenium mode is not activated, we redirect to home page.
		$allowed = $this->__isSeleniumAllowed();
		if (!$allowed) {
			return $this->redirect('/');
		}
		// If selenium entry point is activated, we proceed.
		parent::beforeFilter();
		// Allow ShowLastEmail entry point.
		$this->Auth->allow(
			array(
				'showLastEmail',
				'resetInstance',
			)
		);
		// Use table email_queue. (seems that cakephp refuses to take the default of the class).
		$this->EmailQueue->useTable = 'email_queue';
	}

	/**
	 * Show last email sent to a particular user.
	 * Make sure you send email address URL encoded
	 * @param string $username
	 *
	 * @throws Exception
	 */
	public function showLastEmail($username = null) {
		// If Selenium mode is not activated, we redirect to home page.
		if (!$this->__isSeleniumAllowed()) {
			return $this->redirect('/');
		}
		// If username is null, we return an error.
		if (is_null($username)) {
			throw new HttpException(__('Username not correct'));
		}
		// If username doesn't exist, we return an error.
		$u = $this->User->findByUsername($username);
		if (empty($u)) {
			throw new HttpException(__('The username doesn\'t exist'));
		}
		// If email is not found, we return an error.
		$email = $this->EmailQueue->findByTo($username);
		if (empty($email)) {
			throw new HttpException(__('No email was sent to this user'));
		}
		// Get template used.
		$template = $email['EmailQueue']['template'];
		// Get vars.
		$vars = is_array($email['EmailQueue']['template_vars']) ?
			$email['EmailQueue']['template_vars'] : json_decode($email['EmailQueue']['template_vars'], true);
		// Get subject.
		$title = $email['EmailQueue']['subject'];
		// Get format.
		$format = $email['EmailQueue']['format'];

		// List variables.
		foreach ($vars as $varName => $varValue) {
			// Set variables to the view.
			$this->set($varName, $varValue);
		}
		// Set layout title same as email title.
		$this->set('title_for_layout', $title);
		// Uses the email layout.
		$this->layout = "Emails/$format/default";
		// Renders the view.
		$this->render("/Emails/$format/" . $template);
	}

	/**
	 * DANGEROUS !!!! Reset passbolt instance data.
	 * DO NOT CALL THIS ENTRY POINT IF YOU DON'T UNDERSTAND WHAT IT IS.
	 * WE USE IT ONLY FOR OUR SELENIUM TESTS.
	 * IF YOU CALL IT, YOU WILL LOSE ALL THE DATA OF YOUR DB. YOU ARE WARNED.
	 * This is same as calling the cake shell : cake install
	 * @param bool $dummy
	 */
	public function resetInstance($dummy = 'default') {
		// If Selenium mode is not activated, we redirect to home page.
		if (!$this->__isSeleniumAllowed()) {
			return $this->redirect('/');
		}
		// Install job shell.
		$job = new InstallShell();
		$job->startup();
		// If dummy data is requested.
		if ($dummy == 'default') {
			$job->params['data'] = 'default';
		}
		$job->dispatchMethod('main');
		die();
	}
}

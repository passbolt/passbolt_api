<?php
/**
 * SeleniumTests Controller
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
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

	public $uses = [
		'User',
		'EmailQueue',
	];

	// Configuration key to check if selenium entry points are configured.
	private $__configKey = 'App.selenium.active';

/**
 * Check if the selenium entry point is allowed by the configuration.
 *
 * @return bool
 */
	private function __isSeleniumAllowed() {
		$seleniumAllowed = Configure::read($this->__configKey) === true
			&& Configure::read('debug') > 0;
		return $seleniumAllowed;
	}

/**
 * Called before the controller action. Used to manage access right
 *
 * @throws ForbiddenException if selenium testing is not enabled on this instance
 * @return void
 * @link http://book.cakephp.org/2.0/en/controllers.html#request-life-cycle-callbacks
 */
	public function beforeFilter() {
		// If Selenium mode is not activated we throw and exception & redirect home
		if (!$this->__isSeleniumAllowed()) {
			throw new ForbiddenException();
		}

		// Allow ShowLastEmail entry point.
		$this->Auth->allow(
			[
				'showLastEmail',
				'resetInstance',
				'error404',
				'error500',
				'setExtraConfig',
				'resetExtraConfig',
			]
		);
		// Use table email_queue. (seems that cakephp refuses to take the default of the class).
		$this->EmailQueue->useTable = 'email_queue';

		parent::beforeFilter();
	}

/**
 * Set extra selenium config
 *
 * @return void
 */
	public function setExtraConfig() {
		$data = $this->request->input('json_decode', true);
		$seleniumExtraConfig = '<?php $config = ' . var_export($data, true) . ';';
		file_put_contents(TMP . DS . 'selenium' . DS . 'core_extra_config.php', $seleniumExtraConfig);
		die();
	}

/**
 * Reset extra selenium config
 *
 * @return void
 */
	public function resetExtraConfig() {
		unlink(TMP . DS . 'selenium' . DS . 'core_extra_config.php');
		die();
	}

/**
 * Show last email sent to a particular user.
 * Make sure you send email address URL encoded
 *
 * @param string $username the email of the user
 * @throws HttpException
 * @return void
 */
	public function showLastEmail($username = null) {
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
		$email = $this->EmailQueue->findByTo($username, null, 'created DESC');
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
 * Reset passbolt instance data. All data will be lost
 * This is same as calling the cake shell : cake install [--data=[default|...]]
 *
 * @param string $dummy data set name
 * @return void
 */
	public function resetInstance($dummy = 'seleniumtests') {
		// Install job shell.
		$job = new InstallShell();
		$job->startup();
		// If dummy data is requested.
		if ($dummy == 'default' || $dummy == 'seleniumtests' || $dummy == 'unittests') {
			$job->params['data'] = $dummy;
			$job->params['quick'] = 'true';
		}

		$job->dispatchMethod('main');
		die();
	}

/**
 * Convenience functions to test error pages
 *
 * @param string $case [message|exception] throw an exception directly or use the message component
 * @throws NotFoundException
 * @return void
 */
	public function error404($case = 'message') {
		$this->request->invalidateFields = 'stuffs';
		throw new NotFoundException();
	}

/**
 * Convenience function to test a 403 page
 *
 * @return void
 */
	public function error403() {
		echo 'test;';
		return;
		// nothing, forbidden because not part of allow list
	}

/**
 * Convenience function to test a 500 page
 *
 * @throws InternalErrorException
 * @return void
 */
	public function error500() {
		throw new InternalErrorException();
	}
}

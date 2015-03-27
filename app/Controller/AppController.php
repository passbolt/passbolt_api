<?php
/**
 * Application Controller
 * Application-wide methods, all controllers inherit them
 * 
 * @copyright    copyright 2012 Passbolt.com
 * @package      app.Controller.AppController
 * @since        version 2.12.7
 * @license      http://www.passbolt.com/license
 */

App::uses('Sanitize', 'Utility');
App::uses('Controller', 'Controller');
App::import('Model','User');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

	/**
	 * @var $component application wide components
	 */
	public $components = array(
		'Session',
		'Paginator',
		'Cookie',
		'Auth' => array(
			'className' => 'PassboltAuth',
			'throttlingStrategies' => array(
				'throttle' => array(
					1 => array(
						'throttleTime' => '5'
					),
					2 => array(
						'throttleTime' => '15'
					),
					3 => array(
						'throttleTime' => '45'
					),
					4 => array(
						'throttleTime' => '60'
					)
				),
				'blacklist' => array(
					20 => array(
						'interval' => '60',
						'blacklistTime' => '600'
					),
					50 => array(
						'interval' => '1200',
						'blacklistTime' => '2400'
					),
					100 => array(
						'interval' => '3600',
						'blacklistTime' => '7200'
					)
				)
			)
		),
		'Message',
		'Mailer',
		'IpAddress',
		'Blacklist'
	);

	public $helpers = array(
		'Html', 'Form', // default
		'MyForm'				// custom
	);

	/**
	 * Called before the controller action.	You can use this method to configure and customize components
	 * or perform logic that needs to happen before each controller action.
	 * @link http://book.cakephp.org/2.0/en/controllers.html#request-life-cycle-callbacks
	 * @return void
	 */
	public function beforeFilter() {
		// Paranoia - Hidding PHP version number
		$this->response->header('X-Powered-By', 'PHP');

		// Add a callback detector
		$this->request->addDetector('json', array('callback' => function ($request) {
					return (preg_match('/(.json){1,}$/', Router::url(null,true)) || $request->is('ajax'));
				}));

		// Set default layout
		if (isset($this->request->params['plugin']) && $this->request->params['plugin'] == 'api_generator') {
			$this->layout = 'html5';
		} else {
			if ($this->request->is('json')) {
				$this->layout = 'json';
				$this->view = '/Json/default';
			} else {
				$this->layout = 'html5';
			}
		}

		// Set active user Anonymous
		// or use what is in the session
		User::get();

		// Auth component initilization
		$this->Auth->authenticate = Configure::read('Auth.authenticate');
		//$this->Auth->loginAction = Configure::read('Auth.loginAction');
		$this->Auth->loginRedirect = Configure::read('Auth.loginRedirect');
		//$this->Auth->logoutRedirect = Configure::read('Auth.logoutRedirect');
		$this->Auth->authorize = array('Controller'); //@see AppController::isAuthorized

		// @todo this will be remove via the initial auth check
		// User::set() will load default config
		if ($this->Session->read('Config.language') != null) {
			Configure::write('Config.language', $this->Session->read('Config.language'));
		} else {
			$this->Session->write('Config.language', Configure::read('Config.language'));
		}

		// Store the original data.
		$this->request->rawData = $this->request->data;
		$this->request->rawQuery = $this->request->query;

		// Sanitize post data, except exceptions.
		if (isset($this->request->data) && !empty($this->request->data)) {
			$this->request->data = Sanitize::clean($this->request->data);
		}
		// sanitize any get data
		if (isset($this->request->query) && !empty($this->request->query)) {
			$this->request->query = Sanitize::clean($this->request->query);
		}
	}

	/**
	 * Authorization check main callback
	 * @link http://api20.cakephp.org/class/auth-component#method-AuthComponentisAuthorized
	 * @param mixed $user The user to check the authorization of. If empty the user in the session will be used.
	 * @return boolean True if $user is authorized, otherwise false
	 * @access public
	 */
	public function isAuthorized($user) {
		if ($this->isWhitelisted()) {
			return true;
		}
		if (User::isAnonymous()) {
			if ($this->request->is('Json')) {
				$this->Message->error(__('You need to login to access this location'), array('code' => 403));
				return true; // no need to redirect to login
			}
			return false;
		}
		return true;
	}

	/**
	 * Is the controller:action pair whitelisted in config? (see. App.auth.whitelist)
	 * @param string $controller, current is used if null
	 * @param string $action, current is used if null
	 * @return bool true if the controller action pair is whitelisted
	 * @access public
	 */
	public function isWhitelisted($controller=null, $action=null) {
		if ($controller == null) {
			$controller = strtolower($this->name);
		}
		if ($action == null) {
			$action = $this->action;
		}
		//echo $controller.':'.$action;
		$whitelist = Configure::read('Auth.whitelist');
		return (isset($whitelist[$controller][$action]));
	}
}

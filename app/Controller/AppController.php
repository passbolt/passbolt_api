<?php
/**
 * Application Controller
 * Application-wide methods, all controllers inherit them.
 *
 * @copyright    copyright 2012 Passbolt.com
 * @package      app.Controller.AppController
 * @since        version 2.12.7
 * @license      http://www.passbolt.com/license
 */
App::uses('Controller', 'Controller');
App::import('Model','User');
class AppController extends Controller {

/**
 * @var $component application wide components 
 */
	public $components = array(
		'Session', 'Paginator', 'Cookie', 'Auth', // default
		'Message', 'Mailer'											  // custom
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
		//if ( || $this->request->is('json')) {
			$this->layout = 'json';
			$this->view = '/Json/default';
		//} else {
		//	$this->layout = 'html5';
		//}

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
		$this->currentUser = $this->Auth->user();
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
				$this->Message->error(__('You are not authorized to access that location.'), array(
					'statusCode' => '403' // forbidden
				));
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

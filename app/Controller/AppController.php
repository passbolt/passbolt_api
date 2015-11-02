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

App::uses('Controller', 'Controller');
App::uses('Purifier', 'HtmlPurifier.Lib');
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
		'HtmlPurifier',
		'Cookie',
		'Auth',
		'Message',
		'Mailer',
		'IpAddress',
		'Blacklist'
	);

	public $helpers = array(
		'Html',
		'Form',
		'MyForm',
		'FileStorage.Image'
	);

	/**
	 * Called before the controller action.	You can use this method to configure and customize components
	 * or perform logic that needs to happen before each controller action.
	 * @link http://book.cakephp.org/2.0/en/controllers.html#request-life-cycle-callbacks
	 * @return void
	 */
	public function beforeFilter() {
		// Add a callback detector
		$this->request->addDetector('json', array('callback' => function ($request) {
			return (preg_match('/(.json){1,}$/', Router::url(null,true)) || $request->is('ajax'));
		}));

		// Set default layout
		if ($this->request->is('json')) {
			$this->layout = 'json';
			$this->view = '/Json/default';
		} else {
			// Get roles, to load in the layout js variables.
			// Only for admin and user.
			// TODO move to the view
			$Role = Common::getModel('Role');
			$this->set('roles', $Role->find('all', array(
				'conditions' => array(
					'name' => array(Role::ADMIN, Role::USER),
				),
			)));
			// default layout
			$this->layout = 'html5';
		}

		// Authentication initialization - set headers for gpg auth.
		$this->initAuth();

		// Check if user is logged in or not
		// and return a json error message if not logged in but requesting a non allowed json page
		$isLoggedIn = $this->Auth->user() !== null;
		$isAuthorized = $isLoggedIn || $this->isWhitelisted($this->request->controller, $this->request->action);
		if ( ! $isAuthorized ) {
			if ($this->request->is('Json')) {
				$this->Message->error(
					__('You need to login to access this location'),
					array('code' => 403)
				);
				return;
			}
		}


		// @todo this will be remove via the initial auth check
		// User::set() will load default config
//		if ($this->Session->read('Config.language') != null) {
//			Configure::write('Config.language', $this->Session->read('Config.language'));
//		} else {
//			$this->Session->write('Config.language', Configure::read('Config.language'));
//		}

		// Sanitize user input.
		$this->sanitize();
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
			$action = strtolower($this->action);
		}
		$whitelist = Configure::read('Auth.whitelist');
		return (isset($whitelist[$controller][$action]));
	}

	/**
	 * This perform the HTML sanitization of all user input
	 * @access public
	 */
	public function sanitize() {

		// Before sanitizing, keep the original data.
		$this->request->dataRaw = $this->request->data;
		//$this->request->queryRaw = $this->request->query;

		// Create a very restrictive configuration.
		Purifier::config('nohtml', array(
			'HTML.AllowedElements' => '',
			'Cache.SerializerPath' => APP . 'tmp' . DS . 'purifier',
		));

		// Sanitize any controller parameters.
		if (isset($this->request->params['pass']) && !empty($this->request->params['pass'])) {
			$this->request->params['pass'] = $this->HtmlPurifier->cleanRecursive($this->request->params['pass'], 'nohtml');
		}
		// Sanitize post data, except exceptions.
		if (isset($this->request->data) && !empty($this->request->data)) {
			$this->request->data = $this->HtmlPurifier->cleanRecursive($this->request->data, 'nohtml');
		}
		// Sanitize any get data.
		if (isset($this->request->query) && !empty($this->request->query)) {
			$this->request->query = $this->HtmlPurifier->cleanRecursive($this->request->query, 'nohtml');
		}
	}

	public function initAuth() {

		foreach (Configure::read('Auth') as $key => $authConf) {
			$this->Auth->{$key} = $authConf;
		}

		// Set the headers send in any case where GPG Auth is requested
		$this->response->header('X-GPGAuth-Version','1.3.0');
		$this->response->header('X-GPGAuth-Login-URL','/auth/login');
		$this->response->header('X-GPGAuth-Logout-URL','/auth/logout');
		$this->response->header('X-GPGAuth-Verify-URL','/auth/verify');
		$this->response->header('X-GPGAuth-Pubkey-URL','/auth/verify');
	}
}

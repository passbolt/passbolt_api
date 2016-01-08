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
App::import('Model', 'User');

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
 *
 * @link http://book.cakephp.org/2.0/en/controllers.html#request-life-cycle-callbacks
 * @return void
 */
	public function beforeFilter() {
		$this->initRequestDetectors();
		$this->initAuth();
		$this->setDefaultLayout();
		$this->disconnectUserIfAccountDisabled();
		$this->sanitize();
	}

/**
 * This perform the HTML sanitization of all user input
 *
 * @access public
 * @return void
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

/**
 * Init Authentication Component(s)
 *
 * @return void
 */
	public function initAuth() {
		foreach (Configure::read('Auth') as $key => $authConf) {
			$this->Auth->{$key} = $authConf;
		}

		// Set the headers send in any case where GPG Auth is requested
		$this->response->header('X-GPGAuth-Version', '1.3.0');
		$this->response->header('X-GPGAuth-Login-URL', '/auth/login');
		$this->response->header('X-GPGAuth-Logout-URL', '/auth/logout');
		$this->response->header('X-GPGAuth-Verify-URL', '/auth/verify');
		$this->response->header('X-GPGAuth-Pubkey-URL', '/auth/verify');
	}

/**
 * Define the default view layout depending on request type
 *
 * @return void
 */
	public function setDefaultLayout() {
		// Default is HTML5 layout
		$this->layout = 'html5';

		// JSON request get an empty layout and view
		if ($this->request->is('json')) {
			$this->layout = 'json';
			$this->view = '/Json/default';
		}
	}

/**
 * initRequestDetectors
 *
 * @return void
 */
	public function initRequestDetectors() {
		// Add a callback to the JSON detector
		$this->request->addDetector('json', array('callback' => function ($request) {
			return (preg_match('/(.json){1,}$/', Router::url(null, true)) || $request->is('ajax'));
		}));
	}

	/**
	 * Disconnect the user if his account gets disabled during a session.
	 */
	public function disconnectUserIfAccountDisabled() {
		// Check if user is logged in.
		$userId = $this->Auth->user('User.id');
		// If logged in.
		if ($userId != null) {
			// Retrieve user from db.
			$User = Common::getModel('User');
			$user = $User->findById($userId);
			// If user is disabled, or soft deleted, log out.
			if (empty($user) || $user['User']['deleted'] == true || $user['User']['active'] == false) {
				$User->setInactive();
			}
		}
	}
}

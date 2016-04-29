<?php
/**
 * Application Controller
 * Application-wide methods, all controllers inherit them
 * 
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

App::uses('Controller', 'Controller');
App::uses('Purifier', 'HtmlPurifier.Lib');
App::uses('ControllerLog', 'Model');
App::import('Model', 'User');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package        app.Controller
 * @link        http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

/**
 * @var $component application wide components
 */
	public $components = [
		'Session',
		'Paginator',
		'HtmlPurifier',
		'Cookie',
		'Auth',
		'Message',
		'Mailer',
		'IpAddress',
		//'Blacklist'
	];

	public $helpers = [
		'Html',
		'Form',
		'MyForm',
		'FileStorage.Image'
	];

/**
 * Called before the controller action.    You can use this method to configure and customize components
 * or perform logic that needs to happen before each controller action.
 *
 * @link http://book.cakephp.org/2.0/en/controllers.html#request-life-cycle-callbacks
 * @return void
 */
	public function beforeFilter() {
		$this->initRequestDetectors();
		$this->forceSSL();
		$this->initAuth();
		$this->setDefaultLayout();
		$this->processHeaders();
		$this->disconnectUserIfAccountDisabled();
		$this->sanitize();
	}

/**
 * Force ssl redirection.
 *
 * Will only work if debug is set to zero or selenium is set to active, and force_ssl is true.
 */
	public function forceSSL() {
		// If request is not ssl.
		if(!$this->request->is('ssl')) {
			// If debug mode is off, or selenium is active and request is not made on seleniumtests.
			// (We dont want to forcessl for selenium tests entry point).
			// And force_ssl is on.
			if ((Configure::read('debug') == 0 ||
					(Configure::read('App.selenium.active') == true && $this->request->controller != 'seleniumTests'))
				&& Configure::read('App.force_ssl') == true) {
				$this->redirect('https://' . env('SERVER_NAME') . $this->here, "301");
			}
		}
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

		// Sanitize any controller parameters.
		if (isset($this->request->params) && !empty($this->request->params)) {
			$this->request->params = $this->HtmlPurifier->cleanRecursive($this->request->params, 'nohtml');
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
		$auth = Configure::read('Auth');
		if(empty($auth)) {
			throw new InternalErrorException(__('Auth configuration not found. Is App config set?'));
		}
		foreach ($auth as $key => $authConf) {
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
		$this->layout = 'default';

		// JSON request get an empty layout and view
		if ($this->request->is('json')) {
			$this->layout = 'json';
			$this->view = '/Json/default';
		}
	}

/**
 * Process headers and set view variables accordingly.
 *
 * Mainly used for DO NOT TRACK header as of now.
 *
 * @return void
 */
	public function processHeaders() {
		if (isset($_SERVER['HTTP_DNT']) && $_SERVER['HTTP_DNT'] == 1) {
			$this->set('doNotTrack', true);
		}
	}

/**
 * initRequestDetectors
 *
 * @return void
 */
	public function initRequestDetectors() {
		// Add a callback to the JSON detector
		$this->request->addDetector('json', [
			'callback' => function ($request) {
				return (preg_match('/(.json){1,}$/', Router::url(null, true)) || $request->is('ajax'));
			}
		]);
	}

/**
 * Disconnect the user if his account gets disabled during a session.
 *
 * @return void
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

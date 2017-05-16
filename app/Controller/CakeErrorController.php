<?php
/**
 * Error Handling Controller
 * Controller used by ErrorHandler to render error views.
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

App::uses('AppController', 'Controller');
App::uses('ControllerLog', 'Model');

/**
 * Error Handling Controller
 *
 * Controller used by ErrorHandler to render error views.
 *
 * @package       Cake.Controller
 */
class CakeErrorController extends AppController {

/**
 * Uses Property
 *
 * @var array
 */
	public $uses = [];

/**
 * Constructor
 *
 * @param CakeRequest $request Request instance.
 * @param CakeResponse $response Response instance.
 */
	public function __construct($request = null, $response = null) {
		parent::__construct($request, $response);
		$this->constructClasses();
		if (count(Router::extensions()) && !$this->Components->loaded('RequestHandler')) {
			$this->RequestHandler = $this->Components->load('RequestHandler');
		}
		if ($this->Components->enabled('Auth')) {
			$this->Components->disable('Auth');
		}
		if ($this->Components->enabled('Security')) {
			$this->Components->disable('Security');
		}
		$this->_set(['cacheAction' => false, 'viewPath' => 'Errors']);
	}

/**
 * Render an Exception
 *
 * @param string $view view name
 * @param string $layout layout name
 * @return CakeResponse
 */
	public function render($view = null, $layout = null) {
		// if we're using the message component
		if (isset($this->Message->messages) && !empty($this->Message->messages)) {
			$response = array_pop($this->Message->messages);
		} else {
			// By default, the title is the controller name, action with an error type.
			$title = strtolower('app_' . $this->request->controller . '_' . $this->request->action . '_' . Status::ERROR);
			$response['header']['id'] = Common::uuid($title);
			$response['header']['status'] = 'error';
			$response['header']['title'] = $title;
			$response['header']['servertime'] = time();
			$response['header']['controller'] = $this->request->controller;
			$response['header']['action'] = $this->request->action;
			$response['header']['message'] = $this->viewVars['message'];

			if (isset($this->viewVars['error']->invalidFields)) {
				$response['body'] = $this->viewVars['error']->invalidFields;
			}
		}

		// Log the event if needed
		ControllerLog::write(Status::ERROR, $this->request, $response['header']['message'], 'CakeError');

		// Default treatment is request is not JSON
		if (!$this->request->is('json')) {
			$this->layout = 'error';
			return parent::render($view, $layout);
		} else {
			return $this->response->body(json_encode($response));
		}
	}
}

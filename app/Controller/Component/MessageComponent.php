<?php
/**
 * Message Component
 *
 * This class replace cake flash method. It offers options to qualify the end-user messages (e.g. an error, warning, etc.)
 * But more importantly this component is also used to format the JSON API responses
 *
 * @copyright 	(c) 2015-present Passbolt.com
 * @licence		GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

/**
 * Class Message constants
 */
class Message {
	const ERROR = 'error';
	const SUCCESS = 'success';
	const WARNING = 'warning';
	const NOTICE = 'notice';
	const DEBUG = 'debug';
}

/**
 * Class MessageComponent
 */
class MessageComponent extends Component {

/**
 * @var string $controllerVar key used to store messages in controller/view data
 */
	public $controllerVar = 'json';

/**
 * @var string key used to store message in sessions
 */
	public $sessionKey = 'Messages';

/**
 * @var Controller $controller convenience reference to the parent controller
 */
	public $controller;

/**
 * @var cakeSession convenience reference
 */
	public $Session;

/**
 * @var array $message queue of messages
 */
	public $messages;

/**
 * @var bool $autoRedirect true if referer redirection is needed. Default false
 * 	Such redirection is usefull for displaying messages when one action do not have a view
 */
	public $autoRedirect = false;

/**
 * Called before the Controller::beforeFilter().
 *
 * @param Controller $controller Controller with components to initialize
 * @throws CakeException if session component is not present
 * @return void
 * @link http://book.cakephp.org/2.0/en/controllers/components.html#Component::initialize
 */
	public function initialize(Controller $controller) {
		$this->controller = $controller;
		if (!isset($this->controller->Session)) {
			throw new CakeException('Session component not found (Message::initialize)');
		}
		// get an existing message from the session if any
		// this is used to gather and display queued messages after a redirection
		$this->Session = $controller->Session;
		if ($this->Session->check($this->sessionKey)) {
			$this->messages = $this->Session->read($this->sessionKey);
			$this->Session->delete($this->sessionKey);
		} else {
			$this->messages = array();
		}
		return true;
	}

/**
 * Reset the message component by flushing any messages
 *
 * @return void
 */
	public function reset() {
		unset($this->messages);
		$this->messages = array();
	}

/**
 * Add an error message in the message queue and optionally throws exception
 *
 * @param string $message title
 * @param array $options
 * 		mixed $options['redirect'] url(s) as string or array or true to redirect to the referrer
 * 		integer $options['code'] the code to use as http status code
 *		boolean $options['throw'] throw of not an Http Exception, by default true
 * 		string $options['body'] additional message information, usually in json format
 * @throws HttpException to deliver the error to the client
 * @return void
 */
	public function error($message, $options = array()) {
		$this->__add(Message::ERROR, $message, $options);

		// We throw an exception unless specifically requested otherwise
		if (!isset($options['throw']) || (isset($options['throw']) && $options['throw'] === true)) {

			$code = (isset($options['code'])) ? $options['code'] : 400;

			// Build exception, without forgetting to set the initial headers
			// Headers were already set for the response. We just carry forward the same headers in the exception.
			switch($code) {
				case '400':
					$error = new BadRequestException($message);
					break;
				case '401':
					$error = new UnauthorizedException($message);
					break;
				case '403':
					$error = new ForbiddenException($message);
					break;
				case '404':
					$error = new NotFoundException($message);
					break;
				case '405':
					$error = new MethodNotAllowedException($message);
					break;
				case '500':
					$error = new MethodNotAllowedException($message);
					break;
				case '501':
					$error = new NotImplementedException($message);
					break;
				default:
					$error = new HttpException($message, $code);
				break;
			}
			$error->responseHeader($this->controller->response->header());
			throw $error;
		}
	}

/**
 * Add a warning message to the queue
 *
 * @param string $message title
 * @param array $options
 * 		mixed $options['redirect'] url(s) as string or array or true to redirect to the referrer
 * 		string $options['body'] additional message information, usually in json format
 * @return void
 */
	public function warning($message, $options = array()) {
		$this->__add(Message::WARNING, $message, $options);
	}

/**
 * Add a debug message to the queue
 *
 * @param string $message title
 * @param array $options
 * 		mixed $options['redirect'] url(s) as string or array or true to redirect to the referrer
 * 		string $options['body'] additional message information, usually in json format
 * @return void
 */
	public function debug($message, $options = array()) {
		$this->__add(Message::DEBUG, $message, $options);
	}

/**
 * Add a notice message to the queue
 *
 * @param string $message title
 * @param array $options
 * 		mixed $options['redirect'] url(s) as string or array or true to redirect to the referrer
 * 		string $options['body'] additional message information, usually in json format
 * @return void
 */
	public function notice($message, $options = array()) {
		$this->__add(Message::NOTICE, $message, $options);
	}

/**
 * Add a success message to the queue
 *
 * @param string $message title (optional)
 * @param array $options
 * 		mixed $options['redirect'] url(s) as string or array or true to redirect to the referrer
 * 		string $options['body'] additional message information
 * @return void
 */
	public function success($message = '', $options = array()) {
		$this->__add(Message::SUCCESS, $message, $options);
	}

/**
 * Add a message to the queue
 *
 * @param string $type error|notice|warning|success
 * @param string $message title (optional)
 * @param array $options
 * 		mixed $options['redirect'] url(s) as string or array or true to redirect to the referrer
 * 		string $options['body'] additional message information, usually in json format
 * @access private
 * @return void
 */
	private function __add($type = Message::ERROR, $message = null, $options = null) {
		// The response message
		$response = array(
			'header' => array(),
			'body' => array()
		);
		$type = strtolower($type);

		// By default, the title is the controller name.
		$title = strtolower('app_' . $this->controller->name . '_' . $this->controller->action . '_' . $type);
		if (isset($options['title']) && !empty($options['title'])) {
			$title = $options['title'];
		}

		// Set the header of the message
		$response['header'] = array(
			// UUID is predictable
			'id' => Common::uuid($title),
			'status' => $type,
			'title' => $title,
			'servertime' => time(),
			'message' => $message,
			'controller' => $this->controller->name,
			'action' => $this->controller->action
		);

		// Set the body of the message
		// An optional body as been passed as an option
		if (isset($options['body'])) {
			$response['body'] = $options['body'];
		} elseif (isset($this->controller->viewVars['data'])) {
			// Or the controller views data has been set
			$response['body'] = $this->controller->viewVars['data'];
		} else {
			$response['body'] = array();
		}

		// Add the message to the queue of messages
		$this->messages[] = $response;

		// Need some directions?
		if (isset($options['redirect'])) {
			if (is_bool($options['redirect'])) {
				$options['redirect'] = $this->controller->referer();
			} elseif (is_string($options['redirect']) || is_array($options['redirect'])) {
				return $this->controller->redirect($options['redirect']);
			}
		}

		// If the session has been lost, save the messages into the new session
		// @see AppExceptionRenderer
		if (is_null($this->Session->read($this->sessionKey))) {
			$this->Session->write($this->sessionKey, $this->messages);
		}
	}

/**
 * Set the body of the last message in the queue
 *
 * @param string $body the content to append, usually in json format
 * @return bool true if success
 * @access public
 */
	public function setBody($body = null) {
		$nbMessages = count($this->messages);
		if ($body == null || $nbMessages == 0) {
			return false;
		}
		$this->messages[$nbMessages - 1]['body'] = $body;
		return true;
	}

/**
 * Called before Controller::redirect()
 *		Save pending messages in session to allow a display after a redirect
 *
 * @param Controller $controller Controller with components to beforeRedirect
 * @param string|array $url Either the string or URL array that is being redirected to.
 * @param int $status the status code of the redirect
 * @param bool $exit Will the script exit.
 * @return array|void Either an array or null.
 * @link http://book.cakephp.org/2.0/en/controllers/components.html#Component::beforeRedirect
 * @return void
 */
	public function beforeRedirect(Controller $controller, $url, $status = null, $exit = true) {
		if (isset($this->messages) && !empty($this->messages)) {
			$this->Session->write($this->sessionKey, $this->messages);
		}
	}

/**
 * Called before the Controller::beforeRender()
 *
 * @param Controller $controller Controller with components to beforeRender
 * @return void
 * @link http://book.cakephp.org/2.0/en/controllers/components.html#Component::beforeRender
 */
	public function beforeRender(Controller $controller) {
		// We set the body with the view data in case the body will be updated
		// between the moment messageComponent::__add is called and the view is rendered
		if (isset($this->controller->viewVars['data'])) {
			$this->setBody($this->controller->viewVars['data']);
		}
		// As messages are saved into session, pop the last message from the queue
		// and set it in case it needs to be used in the view
		if (!empty($this->messages)) {
			$message = array_pop($this->messages);
			$this->controller->set($this->controllerVar, $message);
		}
	}
}

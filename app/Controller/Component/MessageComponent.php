<?php
/**
 * Message Component
 *
 * This class replace cake flash method. It offers options to qualify the end-user messages (e.g. an error, warning, etc.)
 * But more importantly this component is also used to format the JSON API responses
 *
 * @copyright 	(c) 2015-present Passbolt.com
 * @licence			GNU Public Licence v3 - www.gnu.org/licenses/gpl-3.0.en.html
 */

// Translation
__('Error', true);
__('Notice', true);
__('Warning', true);
__('Success', true);
__('Debug', true);

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
 * @var controller $Controller convenience reference to the parent controller
 */
	public $Controller;

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
 * Initialize
 * @param Controller $controller
 * @param array $settings
 * @return bool status, proceed with component usage (true), or fail (false)
 * @throws exception is Session component is missing
 */
	public function initialize(Controller $controller, $settings = array()) {
		$this->Controller = $controller;
		if (!isset($this->Controller->Session)) {
			throw new exception('Session component not found (Message::initialize)');
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
 */
	public function reset() {
		unset($this->messages);
		$this->messages = array();
	}

/**
 * Add an error message in the message queue and optionally throws exception
 * @param string $message title
 * @param array $options
 * 		mixed $options['redirect'] url(s) as string or array or true to redirect to the referrer
 * 		integer $options['code'] the code to use as http status code
 *		boolean $options['throw'] throw of not an Http Exception, by default true
 * 		string $options['body'] additional message information, usually in json format
 * @throws HttpException to deliver the error to the client
 */
	public function error($message, $options = array()) {
		$this->__add(Message::ERROR, $message, $options);

		// We throw an exception unless specifically requested otherwise
		if (!isset($options['throw']) || (isset($options['throw']) && $options['throw'] === true)) {
			$code = 400;
			if (isset($options['code'])) {
				$code = $options['code'];
			}
			throw new HttpException($message, $code);
		}
	}

/**
 * Add a warning message to the queue
 *
 * @param string $message
 * @param array $options
 * 		mixed $options['redirect'] url(s) as string or array or true to redirect to the referrer
 * 		string $options['body'] additional message information, usually in json format
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
 */
	private function __add($type = Message::ERROR, $message = null, $options = null) {
		// The response message
		$response = array(
			'header' => array(),
			'body' => array()
		);
		$type = strtolower($type);
		$title = __($type, true);

		// By default, the title is the controller name.
		$title = strtolower('app_' . $this->Controller->name . '_' . $this->Controller->action . '_' . $type);
		if (isset($options['title']) && !empty($options['title'])) {
			$title = $options['title'];
		}

		// Set the header of the message
		$response['header'] = array(
			// UUID is predictable
			'id' => Common::uuid($this->Controller->name . $this->Controller->action . $type),
			'status' => $type,
			'title' => $title,
			'servertime' => time(),
			'message' => $message,
			'controller' => $this->Controller->name,
			'action' => $this->Controller->action
		);

		// Set the body of the message
		// An optional body as been passed as an option
		if (isset($options['body'])) {
			$response['body'] = $options['body'];
		} // Or the controller views data has been set
		else if (isset($this->Controller->viewVars['data'])) {
			$response['body'] = $this->Controller->viewVars['data'];
		} else {
			$response['body'] = array();
		}

		// Add the message to the queue of messages
		$this->messages[] = $response;

		// Need some directions?
		if (isset($options['redirect'])) {
			if (is_bool($options['redirect'])) {
				$options['redirect'] = $this->Controller->referer();
			} elseif (is_string($options['redirect']) || is_array($options['redirect'])) {
				return $this->Controller->redirect($options['redirect']);
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
 * @param Controller $controller
 * @param string|array $url Either the string or URL array that is being redirected to.
 * @param integer $status The status code of the redirect
 * @param boolean $exit Will the script exit.
 * @return array|void Either an array or null.
 * @link http://book.cakephp.org/2.0/en/controllers/components.html#Component::beforeRedirect
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
		// @todo why? instructions not clear / potentially too much data
		if (isset($this->Controller->viewVars['data'])) {
			$this->setBody($this->Controller->viewVars['data']);
		}
		// As messages are saved into session, pop the last message from the queue
		// and set it in case it needs to be used in the view
		// @todo why only the last one?
		if(!empty($this->messages)) {
			$message = array_pop($this->messages);
			$this->Controller->set($this->controllerVar, $message);
		}
	}

}

<?php
/**
 * Message Component
 * This class replace $session->flash and offers more functionalities to qualify
 * the messages that will be displayed to the user or returned as part of the JSON response
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Controller.MessageComponent
 * @since        version 2.12.7
 */

// Translation
__('Error', true);
__('Notice', true);
__('Warning', true);
__('Success', true);
__('Debug', true);

class Message {
	const ERROR = 'error';
	const SUCCESS = 'success';
	const WARNING = 'warning';
	const NOTICE = 'notice';
	const DEBUG = 'debug';
}

class MessageComponent extends Component {

	// key used to store messages in controller/view data
	public $controllerVar = 'json';

	// key used to store message in sessions
	public $sessionKey = 'Messages';

	// controller shortcut
	public $Controller;

	// session component shortcut
	public $Session;

	// message queue
	public $messages;

	// redirect to referer? (for actions without views)
	public $autoRedirect = false;

/**
 * Initialize
 *
 * @param object $controller Controller using this component
 * @return boolean Proceed with component usage (true), or fail (false)
 * @throws exception is Session component is missing
 * @access public
 */
	public function initialize(Controller $controller, $settings = array()) {
		$this->Controller = $controller;
		if (!isset($this->Controller->Session)) {
			throw new exception('Session component not found (Message::initilize)');
		}
		// get an existing message from the session if any
		// this is used to display messages after a redirection
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
 * Reset the message component.
 * @return {void}
 */
	public function reset() {
		unset($this->messages);
		$this->messages = array();
	}

	/**
 * Add an error message in the message queue. And throw an HttpException to deliver the error
 * to the client.
 *
 * @param string $message
 * @param array $options
 * @param mixed $options ['redirect'] url(s), string or array to redirect to, or boolean if true
 * to redirect to the controler referer
 * @param integer $options ['code'] the code to use as http status code
 * @param boolean $options ['throw'] throw of not an Http Exception, by default true
 * @return void
 * @access public
 */
	public function error($message, $options = array()) {
		$this->__add(Message::ERROR, $message, $options);

		// If we throw an http exception
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
 * @param mixed $options ['redirect'] url(s), string or array to redirect to, or boolean if true
 * to redirect to the controler referer
 * @access public
 */
	public function warning($message, $options = array()) {
		$this->__add(Message::WARNING, $message, $options);
	}

/**
 * Add a debug message to the queue
 *
 * @param string $message
 * @param mixed $options ['redirect'] url(s), string or array to redirect to, or boolean if true
 * to redirect to the controler referer
 * @access public
 */
	public function debug($message, $options = array()) {
		$this->__add(Message::DEBUG, $message, $options);
	}

/**
 * Add a notice/info message to the queue
 *
 * @param string $message
 * @param mixed $options ['redirect'] url(s), string or array to redirect to, or boolean if true
 * to redirect to the controler referer
 * @access public
 */
	public function notice($message, $options = array()) {
		$this->__add(Message::NOTICE, $message, $options);
	}

/**
 * Add a success message to the queue
 *
 * @param string $message
 * @param mixed $options ['redirect'] url(s), string or array to redirect to, or boolean if true
 * to redirect to the controler referer
 * @access public
 */
	public function success($message = '', $options = array()) {
		$this->__add(Message::SUCCESS, $message, $options);
	}

/**
 * Add a message to the queue
 *
 * @param mixed $message
 * @param string $type {error, notice, etc.}
 * @param mixed $options ['redirect'] url(s), string or array to redirect to, or boolean if true
 * to redirect to the controler referer
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

		// Set the header of the message
		$response['header'] = array(
			// UUID is predictable
			'id' => Common::uuid($this->Controller->name . $this->Controller->action . $type),
			'status' => ((empty($code)) ? $type : $type . ' ' . $code),
			'title' => $title,
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
 * Set body to the last message set
 *
 * @param text /json $body the content to append. Usually in json format
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
 * Before redirect callback
 *
 * @param object $controller
 * @param mixed $url
 * @param string $status
 * @param bool $exit
 * @return void
 * @access public
 */
	public function beforeRedirect(Controller $controller, $url, $status = null, $exit = true) {
		// save pending messages in session to display next
		if (isset($this->messages) && !empty($this->messages)) {
			$this->Session->write($this->sessionKey, $this->messages);
		}
	}

/**
 * Before render callback
 *
 * @param object $controller
 * @return void
 * @access public
 */
	public function beforeRender(Controller $controller) {
		// In case where the body will be updated between the call of the messageComponent function (success or whatever)
		// and the moment the view is rendered
		if (isset($this->Controller->viewVars['data'])) {
			$this->setBody($this->Controller->viewVars['data']);
		}
		// As messages are saved into session, pop the messages array and respond to the client with the last one
		$message = array_pop($this->messages);
		$this->Controller->set($this->controllerVar, $message);
	}

}

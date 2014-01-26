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
class Message {
	const ERROR = 'error';
	const SUCCESS = 'success';
	const WARNING = 'warning';
	const NOTICE = 'notice';
	const DEBUG = 'debug';
}
class MessageComponent extends Component {

	// key used to store messages in controller/view data
	public $controllerVar = 'flashMessages';

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
 * @param object $controller Controller using this component
 * @return boolean Proceed with component usage (true), or fail (false)
 * @throws exception is Session component is missing
 * @access public
 */
	public function initialize(Controller $controller, $settings=array()) {
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
 * Add an error message in the message queue
 * @param string $code error code
 * @param string $message
 * @param mixed $options['redirect'] url, string or array
 * @param boolean $fatal
 * @return void
 * @access public
 */
	public function error($message, $options=array()) {
		$this->__add(Message::ERROR,$message,$options);
	}

/**
 * Add a warning message to the queue
 * @param string $message
 * @param mixed $options['redirect'] url, string or array
 * @access public
 */
	public function warning($message, $options=array()) {
		$this->__add(Message::WARNING, $message, $options);
	}

/**
 * Add a debug message to the queue
 * @param string $message
 * @param mixed $options['redirect'] url, string or array
 * @access public
 */
	public function debug($message, $options=array()) {
		$this->__add(Message::DEBUG, $message, $options);
	}

/**
 * Add a notice/info message to the queue
 * @param string $message
 * @param mixed $options['redirect'] url, string or array
 * @access public
 */
	public function notice($message, $options=array()) {
		$this->__add(Message::NOTICE, $message, $options);
	}

/**
 * Add a success message to the queue
 * @param string $message
 * @param mixed $options['redirect'] url, string or array
 * @access public
 */
	public function success($message='', $options=array()) {
		$this->__add(Message::SUCCESS,$message,$options);
	}

/**
 * Add a message to the queue
 * @param mixed $message
 * @param string $type {error, notice, etc.}
 * @param mixed $options['redirect'] array, or string, or bollean
 * @param bollean die
 * @access private
 */
	private function __add($type = Message::ERROR, $message=null, $options=null) {
		$die = false;
		$title = '';
		$type = strtolower($type);
		// Cosmetics
		switch ($type) {
			case Message::ERROR :
				$title = __('Error',true);
				//$this->controller->statusCode(400); // bad request
			break;
			case Message::NOTICE :
				$title = __('Notice',true);
			break;
			case Message::WARNING :
				$title = __('Warning',true);
			break;
			case Message::SUCCESS :
				$title = __('Success',true);
				//$this->controller->statusCode(200); // OK
			break;
			case Message::DEBUG :
				$title = __('Debug',true);
			break;
		}
		if (!isset($options['code'])) {
			$options['code'] = String::uuid($message);
		}
		// message object for the view
		// header
		$m = array(
			'header' => array(
				// UUID is predictable
				'id' => Common::uuid($this->Controller->name . $this->Controller->action . $type),
				'status' => ((empty($code)) ? $type : $type . ' ' . $code ),
				'title' => $title,
				'message' => $message,
				'controller' => $this->Controller->name,
				'action' => $this->Controller->action
			)
		);
		// optional body (see also beforeRender)
		if (isset($options['body'])) {
			$m['body'] = $options['body'];
		} else {
			$m['body'] = array();
		}

		$this->messages[] = $m;

		// Need some directions?
		if (isset($options['redirect'])) {
			if (is_bool($options['redirect'])) {
				$options['redirect'] = $this->Controller->referer();
			} elseif (is_string($options['redirect']) || is_array($options['redirect'])) {
				return $this->Controller->redirect($options['redirect']);
			}
		}
	}

/**
 * Set body to the last message set
 * @param text/json $body the content to append. Usually in json format
 * @return bool true if success
 * @access public
 */
	public function setBody(&$body = null) {
		$nbMessages = count($this->messages);
		if ($body == null || $nbMessages == 0) {
			return false;
		}
		$this->messages[count($this->messages) - 1]['body'] = &$body;
		return true;
	}

/**
 * Before redirect callback
 * @param object $controller
 * @param mixed $url
 * @param string $status
 * @param bool $exit
 * @return void
 * @access public
 */
	public function beforeRedirect(Controller $controller, $url, $status=null, $exit=true) {
		// save pending messages in session to display next
		if (isset($this->messages) && !empty($this->messages)) {
			$this->Session->write($this->sessionKey, $this->messages);
		}
	}

/**
 * Before render callback
 * @param object $controller
 * @return void
 * @access public
 */
	public function beforeRender(Controller $controller) {
		if (isset($this->Controller->viewVars['data'])) {
			$this->setBody($this->Controller->viewVars['data']);
		}
		$this->Controller->set($this->controllerVar, $this->messages);
	}

}

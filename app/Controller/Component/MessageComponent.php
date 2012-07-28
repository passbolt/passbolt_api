<?php
/**
 * Message Component
 * This class replace $session->flash and offers more functionalities to qualify 
 * the messages that will be displayed to the user or returned as part of the JSON response
 *
 * @copyright		 Copyright 2012, Passbolt.com
 * @license			 http://www.passbolt.com/license
 * @package			 app.Controller.MessageComponent
 * @since				 version 2.12.7
 */
class Message {
	const notice = 'notice';
	const debug = 'debug';
	const error = 'error';
	const success = 'success';
	const fatal = 'fatal';
	const warning = 'warning';
	const info = 'info';
}
class MessageComponent extends Component {
	var $name = 'Message';
	var $controllerVar = 'flashMessages'; // key used to store messages in controller/view data
	var $sessionKey = 'Messages';				 // key used to store message in sessions
	var $Controller;											// controller shortcut
	var $Session;												 // session component shortcut
	var $messages;												// message queue
	var $autoRedirect = false;

/**
 * Initialize
 * @param object $controller Controller using this component
 * @return boolean Proceed with component usage (true), or fail (false)
 */
	function initialize(&$controller, $settings=array()) {
		$this->Controller = &$controller;
		if (isset($this->Controller->Session)) {
			$this->Session = &$controller->Session;
			if($this->Session->check($this->sessionKey)) {
				$this->messages = $this->Session->read($this->sessionKey);
				$this->Session->delete($this->sessionKey);
			} else {
				$this->messages = array();
			}
			return true;
	 } else {
			throw new exception('Session component not found (Message::initilize)');
		}
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
	function error($message, $options=array()) {
		$default_options = array(
			Message::fatal => false
		);
		$options = array_merge($default_options, $options);
		$type = $options[Message::fatal] ? Message::fatal : Message::error;
		$this->__add($type,$message,$options);
	}

/**
 * Add a notice message to the queue
 * @param string $message
 * @param mixed $options['redirect'] url, string or array
 */
	function warning($message, $options=array()) {
		$this->__add(Message::warning, $message, $options);
	}
	function info($message, $options=array()) {
		$this->__add(Message::info, $message, $options);
	}
	function debug($message, $options=array()) {
		$this->__add(Message::debug, $message, $options);
	}
	function notice($message, $options=array()) {
		$this->__add(Message::notice, $message, $options);
	}
	function success($message='', $options=array()) {
		$this->__add(Message::success,$message,$options);
	}

/**
 * Add a message to the queue
 * @param mixed $message
 * @param string $type {error, notice, etc.}
 * @param mixed $options['redirect'] array, or string, or bollean
 * @param bollean die
 * @access private
 */
	function __add($type=Message::error, $message=null, $options=null) {
		$die = false;
		$title = '';
		$type = strtolower($type);
		// Cosmetics
		switch ($type) {
			case Message::fatal :
				$title = __('Fatal',true);
				$die = true;
			break;
			case Message::error	: $title = __('Error',true); break;
			case Message::info	 : case 'hint' :
			case Message::notice : $title = __('Notice',true); break;
			case Message::warning: $title = __('Warning',true); break;
			case Message::success: $title = __('Success',true); break;
			case Message::debug	: $title = __('Debug',true); break;
		}
		if (!isset($options['code'])) {
			$options['code'] = String::uuid($message);
		}
		// message object for the view
		$this->messages[] = array(
			// UUID is predictable
			'id' => Common::uuid($this->Controller->name . $this->Controller->action . $type), 
			'status' => ((empty($code)) ? $type : $type.' '.$code ),
			'title' => $title,
			'message' => $message,
			'controller' => $this->Controller->name,
			'action' => $this->Controller->action
		);

		// Get the point or die trying
		if ($die) {
			trigger_error($title.': '.$message);
			exit;
		}

		// Need some directions?
		if (isset($options['redirect'])) {
			if (is_bool($options['redirect'])) {
				$options['redirect'] = $this->Controller->referer();
			} elseif (is_string($options['redirect']) || is_array($options['redirect'])) {
				//TODO use history component if no referrer
				$this->Controller->redirect($options['redirect']);
				exit;
			}
		}
	}


/**
 * Append a body to the last message set
 * @param text/json $body the content to append. Usually in json format
 * @access public
 */
	public function appendBody($body = null){
			$nbMessages = sizeof($this->messages);
			if($body == null || $nbMessages == 0)
				return;
			$this->messages[sizeof($this->messages) - 1]['body'] = $body;
	}

/**
 * Before redirect callback
 * @param object $controller
 * @param mixed $url
 * @param string $status
 * @param bool $exit
 * @return void
 */
	function beforeRedirect (&$controller, $url, $status=null, $exit=true) {
		// save pending messages in session to display next
		if (isset($this->messages) && !empty($this->messages)) {
			$this->Session->write($this->sessionKey, $this->messages);
		}
	}

/**
 * Before render callback
 * @param object $controller
 * @return void
 */
	function beforeRender (&$controller) {
		$this->Controller->set($this->controllerVar, $this->messages);
	}

}

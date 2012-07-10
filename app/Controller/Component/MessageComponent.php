<?php
/**
 * Message Component
 * This class replace $session->flash and offers more functionalities to qualify 
 * the messages that will be displayed to the user or returned as part of the JSON response
 *
 * @copyright     Copyright 2012, Passbolt.com
 * @license       http://www.passbolt.com/license
 * @package       app.Controller.MessageComponent
 * @since         version 2.12.7
 */
class MessageComponent extends Component {
  var $name = 'Message';
  var $controllerVar = 'flashMessages'; // key used to store messages in controller/view data
  var $sessionKey = 'Messages';         // key used to store message in sessions
  var $Controller;                      // controller shortcut
  var $Session;                         // session component shortcut
  var $messages;                        // message queue
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
      'fatal' => false
    );
    $options = array_merge($default_options, $options);
    $type = $options['fatal'] ? 'fatal' : 'error';
    $this->__add($type,$message,$options);
  }

  /**
   * Add a notice message to the queue
   * @param string $message
   * @param mixed $options['redirect'] url, string or array
   */
  function warning($message, $options=array()) {
    $this->__add('warning', $message, $options);
  }
  function info($message, $options=array()) {
    $this->__add('info', $message, $options);
  }
  function debug($message, $options=array()) {
    $this->__add('debug', $message, $options);
  }
  function notice($message, $options=array()) {
    $this->__add('notice', $message, $options);
  }
  function success($message, $options=array()) {
    $this->__add('success',$message,$options);
  }

  /**
   * Add a message to the queue
   * @param mixed $message
   * @param string $type {error, notice, etc.}
   * @param mixed $options['redirect'] array, or string, or bollean
   * @param bollean die
   * @access private
   */
  function __add($type='error', $message=null, $options=null) {
    $die = false;
    $title = '';
    $type = strtolower($type);
    // Cosmetics
    switch ($type) {
      case 'fatal' :
        $title = __('Fatal',true);
        $die = true;
      break;
      case 'error'  : $title = __('Error',true); break;
      case 'info'   : case 'hint' :
      case 'notice' : $title = __('Notice',true); break;
      case 'warning': $title = __('Warning',true); break;
      case 'success': $title = __('Success',true); break;
      case 'debug'  : $title = __('Debug',true); break;
    }
    if (!isset($options['code'])) {
      $options['code'] = String::uuid($message);
    }
    // message object for the view
    $this->messages[] = array(
      'id' => 'ctl'.Common::uuid(), // @todo make it predictable UUID using hashOf(ctrl.name + action)
      'type' => ((empty($code)) ? $type : $type.' '.$code ),
      'title' => $title,
      'text' => $message
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
        //TODO use history if no referrer
        $this->Controller->redirect($options['redirect']);
        exit;
      }
    }
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

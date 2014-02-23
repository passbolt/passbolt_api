<?php
/**
 * Mailer Component
 * Class used for debuging emails
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Controller.MailerComponent
 * @since        version 2.12.7
 */
class MailerComponent extends Component {

	// email object
	public $email;

	// controller shortcut
	public $Controller;

/**
 * Initialize
 * @param object $controller Controller using this component
 * @return boolean Proceed with component usage (true), or fail (false)
 */
	public function initialize(Controller $controller, $settings=array()) {
		$this->Controller = $controller;
		return true;
	}

/**
 * Send an email or help debug
 * @return bool true if successfull
 */
	public function send() {
		if (isset($this->email)) {
			if (Configure::read('App.emails.delivery') == 'debug') {
				$msg = $this->email->send();
				$debug = '<strong>Headers</strong><br/>';
				$debug .= $msg['headers'];
				$debug .= '<br/><br/><strong>Message</strong><br/>';
				$debug .= $msg['message'];
				$this->Controller->Message->debug($debug);
				return true;
			} else {
				return $this->email->send();
			}
		}
	}
}

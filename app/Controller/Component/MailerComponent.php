<?php
/**
 * Mailer Component
 * Class used for debugging emails
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class MailerComponent extends Component {

/**
 * @var string email
 */
	public $email;

/**
 * @var Controller $controller
 */
	public $controller;

/**
 * Called before the Controller::beforeFilter().
 *
 * @param Controller $controller Controller with components to initialize
 * @return void
 * @link http://book.cakephp.org/2.0/en/controllers/components.html#Component::initialize
 */
	public function initialize(Controller $controller) {
		$this->controller = $controller;
	}

/**
 * Send an email or help debug
 *
 * @return bool true if successful
 */
	public function send() {
		if (isset($this->email)) {
			if (Configure::read('App.emails.delivery') == 'debug') {
				$msg = $this->email->send();
				$debug = '<strong>Headers</strong><br/>';
				$debug .= $msg['headers'];
				$debug .= '<br/><br/><strong>Message</strong><br/>';
				$debug .= $msg['message'];
				$this->controller->Message->debug($debug);
				return true;
			} else {
				return $this->email->send();
			}
		}
	}
}

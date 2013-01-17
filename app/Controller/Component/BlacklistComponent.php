<?php
/**
 * Blacklist Component
 * Manages blacklisting of ip addresses in the system. 
 *
 * @copyright		 copyright 2012 Passbolt.com
 * @package			 app.Controller.BlacklistComponent
 * @since				 version 2.13.03
 * @license			 http://www.passbolt.com/license
 */
class BlacklistComponent extends Component {

/**
 * stores ip of user for current authentication
 */
	public $ip = null;

/**
 * stores the controller
 */
 public $controller = null;

/**
 * startup function
 * @param Controller $controller. the calling controller
 */
	public function initialize(&$controller) {
		$this->controller = $controller;
		$this->AuthenticationLog = ClassRegistry::init('AuthenticationLog');
		$this->AuthenticationBlacklist = ClassRegistry::init('AuthenticationBlacklist');

		$this->ip = $controller->request->clientIp();

		return parent::startup($controller);
	}

/**
 * startup function
 * @param Controller $controller. the calling controller
 */
	public function startup(&$controller) {
		// If address is blacklisted, gives a blackhole
		// http://book.cakephp.org/2.0/en/core-libraries/components/security-component.html#handling-blackhole-callbacks
		if ($this->isBlacklist()) {
			$this->controller->Security->blackHole($this->controller, "error");
			return;
		}
		return parent::startup($controller);
	}

/**
 * detect whether the current address is blacklisted
 */
	public function isBlacklist() {
		$bls = $this->AuthenticationBlacklist->find('all', array(
			'conditions' => array(
				'expiry >' => date('Y-m-d H:i:s')
			)
		));

		foreach ($bls as $bl) {
			if ($this->controller->IpAddress->inRange($this->ip, $bl['AuthenticationBlacklist']['ip'])) {
				return true;
			}
		}
		return false;
	}
}

 
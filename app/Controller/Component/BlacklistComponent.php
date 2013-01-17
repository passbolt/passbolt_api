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

		// If address is blacklisted, doesn't return anything and stops all operations
		if ($this->isBlacklist()) {
			exit(0);
		}

		return parent::startup($controller);
	}

/**
 * startup function
 * @param Controller $controller. the calling controller
 */
	public function startup(&$controller) {
		// If address is blacklisted, doesn't return anything and stops all operations
		if ($this->isBlacklist()) {
			exit(0);
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

 
<?php
/**
 * Blacklist Component
 * Manages blacklisting of ip addresses in the system. 
 *
 * @copyright 	(c) 2015-present Passbolt.com
 * @licence		GNU Public Licence v3 - www.gnu.org/licenses/gpl-3.0.en.html
 */
class BlacklistComponent extends Component {

/**
 * @var string $ip
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
 * @param Array $settings
 */
	public function initialize(Controller $controller, $settings=array()) {
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
	public function startup(Controller $controller) {
		// If address is blacklisted, gives a blackhole
		// http://book.cakephp.org/2.0/en/core-libraries/components/security-component.html#handling-blackhole-callbacks
		if ($this->isIpInBlacklist()) {
			$this->blackHole();
			return;
		}
		return parent::startup($controller);
	}

/**
 * detect whether the current address is blacklisted
 */
	public function isIpInBlacklist() {
		$bls = $this->AuthenticationBlacklist->find('all', array(
			'conditions' => array(
				'expiry >' => gmdate('Y-m-d H:i:s')
			)
		));

		foreach ($bls as $bl) {
			if ($this->controller->IpAddress->inRange($this->ip, $bl['AuthenticationBlacklist']['ip'])) {
				return true;
			}
		}
		return false;
	}

/**
 * makes a blackhole for the user.
 * this action is called when the ip is blacklisted
 */
	public function blackHole() {
		if ($this->controller->request->here != '/pages/blackhole' ) { // avoid loop redirection
			$this->controller->redirect('/pages/blackhole');
		}
		return true;
	}

}

 

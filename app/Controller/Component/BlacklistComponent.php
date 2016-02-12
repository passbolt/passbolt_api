<?php
/**
 * Blacklist Component
 * Manages blacklisting of ip addresses in the system. 
 *
 * @copyright 	(c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class BlacklistComponent extends Component {

/**
 * @var string $ip
 * stores ip of user for current authentication
 */
	public $ip = null;

/**
 * @var Controller $controller
 */
	public $controller = null;

/**
 * Called before the Controller::beforeFilter().
 *
 * @param Controller $controller Controller with components to initialize
 * @return void
 * @link http://book.cakephp.org/2.0/en/controllers/components.html#Component::initialize
 */
	public function initialize(Controller $controller) {
		$this->controller = $controller;
		$this->AuthenticationLog = ClassRegistry::init('AuthenticationLog');
		$this->AuthenticationBlacklist = ClassRegistry::init('AuthenticationBlacklist');
		$this->ip = $controller->request->clientIp();

		return parent::initialize($controller);
	}

/**
 * startup function
 *
 * @param Controller $controller the calling controller
 * @return void
 */
	public function startup(Controller $controller) {
		// If address is blacklisted, gives a blackhole
		if ($this->isIpInBlacklist()) {
			$this->blackHole();
			return;
		}
		return parent::startup($controller);
	}

/**
 * Detect whether the current address is blacklisted
 *
 * @return bool
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
 * Makes a blackhole for the user.
 * This action is called when the ip is blacklisted
 *
 * @return bool true if already in blackhole?
 */
	public function blackHole() {
		if ($this->controller->request->here != '/pages/blackhole') { // avoid loop redirection
			$this->controller->redirect('/pages/blackhole');
		}
		return true;
	}

}

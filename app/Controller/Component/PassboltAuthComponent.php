<?php
/**
 * PassboltAuth Component
 * Throttles the authentications. 
 * This component prevents a user to do bruteforce attacks on the login by introducing compulsory time intervals between two login
 *
 * @copyright		 copyright 2012 Passbolt.com
 * @package			 app.Controller.Common
 * @since				 version 2.12.7
 * @license			 http://www.passbolt.com/license
 */
class PassboltAuthComponent extends AuthComponent {

	public $throttle = array(3, 10, 20, 30, 60);

/**
 * startup function
 */
	public function startup(&$controller) {
		$this->controller = $controller;
		return parent::startup($controller);
	}

	public function getThrottleInterval($attempt) {
		$n = count($this->throttle);
		if ($attempt > $n) {
			return $this->throttle[$n - 1];
		} elseif ($attempt == 0) {
			return 0;
		} else {
			return $this->throttle[$attempt - 1];
		}
	}

/**
 * Get current attempt number
 * if no attempt was made, return 0
 * @return int attempt number
 */
	public function getAttempt() {
		$attempt = $this->controller->Session->read('Throttle.attempt');
		return ($attempt ? $attempt : 0);
	}

/**
 * Checks if the authentication can happen
 * will return false if it is throttled
 * @return bool true if no throttle is going on or false
 */
	public function isLoginAllowed() {
		$lastFailure = $this->controller->Session->read('Throttle.lastFailure');
		if ($lastFailure) {
			$interval = $this->getThrottleInterval($this->getAttempt());
			if (!$interval) {
				return true;
			}
			$now = time();
			// allowed time is the time when the user will be allowed to attempt to log on
			// it is equal to the last failure time + the interval time
			$allowedTime = $lastFailure + $interval;
			return $now > $allowedTime;
		}
		return true;
	}

/**
 * Override of identify method
 * Throttler functions have been added
 */
	public function identify(CakeRequest $request, CakeResponse $response) {
		// if the user is not allowed to attempt to login, we return false
		if(!$this->isLoginAllowed())
			return false;

		// get the status of authentication
		$identified = parent::identify($request, $response);
		// if there is a failed attempt of login
		if (!empty($request->data) && !$identified) {
			$attempt = $this->getAttempt();
			$attempt++;
			$this->controller->Session->write('Throttle.attempt', $attempt);
			$lastFailure = $this->controller->Session->read('Throttle.lastFailure');
			$this->controller->Session->write('Throttle.lastFailure', time());
			$this->controller->Session->write('Throttle.nextLogin', time() + $this->getThrottleInterval($attempt));
			return false;
		}
		// if login is succesful, we delete all the keys in the session
		$this->controller->Session->delete('Throttle.lastFailure');
		$this->controller->Session->delete('Throttle.attempt');
		return $identified;
	}
}
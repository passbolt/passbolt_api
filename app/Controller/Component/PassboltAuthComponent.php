<?php
/**
 * PassboltAuth Component
 * Throttles the authentications. 
 * This component prevents a user to do bruteforce attacks on the login by introducing compulsory time intervals between two login
 *
 * @copyright		 copyright 2012 Passbolt.com
 * @package			 app.Controller.Common
 * @since				 version 2.12.12
 * @license			 http://www.passbolt.com/license
 */
class PassboltAuthComponent extends AuthComponent {
	
	public $AuthenticationLog;
	public $controller;
	public $ip = null;
	public $username = null;
	public $request = null;
	public $lastSuccessfulAuth = null;
	public $lastFailedAuth = null;
	public $authenticationAttempt = 0;
	public $throttle = array(3, 10, 20, 30, 60);
	public $throttleStrategies = array(

	);

/**
 * startup function
 */
	public function startup(&$controller) {
		$this->controller = $controller;
		$this->AuthenticationLog = ClassRegistry::init('AuthenticationLog');
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
		$attempt = $this->authenticationAttempt;
		return ($attempt ? $attempt : 0);
	}

	private function __setContext($request) {
		$this->username = isset($request->data['User']['username']) ? $request->data['User']['username'] : null;
		$this->ip = $this->controller->request->clientIp();

		$this->lastFailedAuth = $this->AuthenticationLog->getLastFailedAuthenticationLog($this->username, $this->ip);
		// Get last successful authentication for the username
		$this->lastSuccessfulAuth = $this->AuthenticationLog->find('first', array(
			'conditions' => array(
				'username' => $this->username,
				'status' => true
			),
			'order' => array(
				'created' => 'DESC'
			)
		));
		$sinceTimestamp = $this->lastSuccessfulAuth ? strtotime($this->lastSuccessfulAuth['AuthenticationLog']['created']) : null;
		$this->authenticationAttempt = $this->AuthenticationLog->getFailedAuthenticationCount($this->username, $this->ip, $sinceTimestamp);
	}

/**
 * Checks if the authentication can happen according to the known context
 * (known context is username and ip that have to be set in the object)
 * will return false if it is throttled
 * @return bool true if no throttle is going on or false
 */
	private function __isAuthenticationAllowed() {
		if($this->authenticationAttempt == 0)
			return true;

		$now = time();
		$next = $this->nextAuthentication();
		return $now > $next;
	}

	public function nextAuthentication() {
		if($this->authenticationAttempt == 0)
			return false;

		$interval = $this->getThrottleInterval($this->authenticationAttempt);
		$now = time();
		// allowed time is the time when the user will be allowed to attempt to log on
		// it is equal to the last failure time + the interval time
		$lastFailureTimestamp = strtotime($this->lastFailedAuth['AuthenticationLog']['created']);
		$nextAuth = $lastFailureTimestamp + $interval;
		return $nextAuth;
	}

/**
 * Override of identify method
 * Throttler functions have been added
 */
	public function identify(CakeRequest $request, CakeResponse $response) {
		$this->__setContext($request);
		// if the user is not allowed to attempt to login, we return false
		if(!$this->__isAuthenticationAllowed())
			return false;

		// get the status of authentication
		$identified = parent::identify($request, $response);
		if (!empty($request->data)) {
			$status = $identified ? true : false;
			// Log the attempt
			$this->AuthenticationLog->log($request->data['User']['username'], $this->ip, $status);
			$this->__setContext($request); // After logging a new entry, we set the context again
		}

		// if there is a failed attempt of login
		if (!empty($request->data) && !$identified) {
			$this->controller->Session->write('Throttle.nextLogin', $this->nextAuthentication());
			return false;
		}
		$this->controller->Session->delete('Throttle.nextLogin');
		return $identified;
	}
}
<?php
/**
 * PassboltAuth Component
 * Throttles the authentications. 
 * This component prevents a user to do bruteforce attacks on the login by introducing compulsory time intervals between two login
 *
 * @copyright		 copyright 2012 Passbolt.com
 * @package			 app.Controller.Common
 * @since				 version 2.13.03
 * @license			 http://www.passbolt.com/license
 */
class PassboltAuthComponent extends AuthComponent {

/**
 * shortcut to AuthenticationLog model
 */
	public $AuthenticationLog;

/**
 * shortcut to controller
 */
	public $controller;

/**
 * stores ip of user for current authentication (is populated by __setContext)
 */
	public $ip = null;

/**
 * stores current username for authentication (is populated by __setContext)
 */
	public $username = null;

/**
 * stores request object (is populated by __setContext)
 */
	public $request = null;

/**
 * stores last successful authentication object (AuthenticationLog)(is populated by __setContext)
 */
	public $lastSuccessfulAuth = null;

/**
 * stores last failed authentication object (AuthenticationLog)(is populated by __setContext)
 */
	public $lastFailedAuth = null;

/**
 * stores authentication attempt number (is populated by __setContext)
 */
	public $authenticationAttempt = 0;

/**
 * defines the throttle strategy. 
 */
	public $throttle = array(5, 15, 45, 60);

/**
 * defines if the current ip should be blacklisted
 */
	public $doBlacklist = false;

/**
 * defines the time during which the current ip should be blacklisted (only if $doBlacklist is true)
 */
	public $blacklistTime = null;

/**
 * defines the throttle strategy. 
 */
	public $throttlingStrategies = array(
		'throttle' => array(
			3 => array(
				'throttleTime' => '5'
			),
			4 => array(
				'throttleTime' => '15'
			),
			5 => array(
				'throttleTime' => '45'
			),
			6 => array(
				'throttleTime' => '60'
			)
		),
		'blacklist' => array(
			20 => array(
				'interval' => '60',
				'blacklistTime' => '600'
			),
			50 => array(
				'interval' => '1200',
				'blacklistTime' => '2400'
			),
			100 => array(
				'interval' => '3600',
				'blacklistTime' => '7200'
			)
		)
	);

/**
 * startup function
 * @param Controller $controller. the calling controller
 */
	public function startup(Controller $controller) {
		$this->controller = $controller;
		$this->AuthenticationLog = ClassRegistry::init('AuthenticationLog');
		return parent::startup($controller);
	}

/**
 * get the throttle time interval corresponding to the attempt number
 * @param int $attempt, the attempt number
 * @return int $interval, the interval in seconds
 */
	public function getThrottleInterval($attempt) {
		$attemptMin = min(array_keys($this->throttlingStrategies['throttle']));
		$attemptMax = max(array_keys($this->throttlingStrategies['throttle']));
		if ($attempt < $attemptMin) {
			return 0;
		}
		if ($attempt > $attemptMax) {
			return $this->throttlingStrategies['throttle'][$attemptMax]['throttleTime'];
		} else {
			// check if the attempt number exists in the array, if not return the value lower
			if (isset($this->throttlingStrategies['throttle'][$attempt]) && !empty($this->throttlingStrategies['throttle'][$attempt])) {
				return $this->throttlingStrategies['throttle'][$attempt]['throttleTime'];
			}
			foreach ($this->throttlingStrategies['throttle'] as $key => $value) {
				if ($attempt > $key) return prev($this->throttlingStrategies['throttle']);
			}
			return end($this->throttlingStrategies['throttle']); // logically this should never happen
		}
	}

/**
 * check and tell whether the user should be blacklisted according to what is in the context
 * @return the blacklist interval as defined in the settings if blacklisting has to be done, false if no blacklisting should happen
 */
	public function shouldBlacklist() {
		$sinceTimestamp = $this->lastSuccessfulAuth ? strtotime($this->lastSuccessfulAuth['AuthenticationLog']['created']) : null;

		$i = 0;
		$count = array();
		$strategy = reset($this->throttlingStrategies['blacklist']);
		$sinceTimestamp = time() - $strategy['interval'];
		$count[] = $this->AuthenticationLog->getFailedAuthenticationCount(null, $this->ip, $sinceTimestamp);
		foreach ($this->throttlingStrategies['blacklist'] as $attempt => $strategy) {
			// No need to continue if first threshold is not even met
			if ($i == 0 && $count < $attempt) {
				break;
			}
			// if threshold is met (number of failed attempts = threshold)
			// we apply the blacklisting as defined

			// if last scenario criterias are met, we return
			if ($count[$i] >= $attempt && $i + 1 == count($this->throttlingStrategies['blacklist'])) {
				return $strategy['blacklistTime'];
			}
			// else, we need to get the data with next scenario, to make sure it belongs here and not in next
			$nextStrategy = next($this->throttlingStrategies['blacklist']);
			$sinceTimestamp = time() - $nextStrategy['interval'];
			$count[$i + 1] = $this->AuthenticationLog->getFailedAuthenticationCount(null, $this->ip, $sinceTimestamp);
			$nextAttempt = key($this->throttlingStrategies['blacklist']);
			if ($count[$i] >= $attempt) {
				if ($count[$i + 1] < $nextAttempt) {
					return $strategy['blacklistTime'];
				}
				prev($this->throttlingStrategies['blacklist']);
			}
			$i++;
		}
		return false;
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

/**
 * Set the  object context corresponding to the current request
 * @param Request $request object given by identify
 */
	protected function __setContext($request) {
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

		if ($interval = $this->shouldBlacklist()) {
			$this->doBlacklist = true;
			$this->blacklistTime = $interval;
		}
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

/**
 * gets the timestamp when the next authentication can be done
 * @return int $nextAuth, timestamp of next authentication
 */
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
 * @param CakeRequest $request
 * @param CakeResponse $response
 */
	public function identify(CakeRequest $request, CakeResponse $response) {
		// If no parameters are given, we return default value
		if(empty($request->data)) return parent::identify($request, $response);

		$this->__setContext($request);
		// if the user is not allowed to attempt to login, we return false
		if(!$this->__isAuthenticationAllowed())
			return false;

		// get the status of authentication
		$identified = parent::identify($request, $response);
		if (isset($request->data['User']['username'])) {
			$status = $identified ? true : false;
			// Log the attempt
			$this->AuthenticationLog->add($request->data['User']['username'], $this->ip, $status);
			$this->__setContext($request); // After logging a new entry, we set the context again
		}

		// if there is a failed attempt of login
		if (isset($request->data['User']['username']) && !$identified) {
			$nextAuth = $this->nextAuthentication();
			$this->controller->Session->write('Throttle.nextLoginTime', $nextAuth);
			// manage blacklist
			if ($this->doBlacklist) {
				$AuthenticationBlacklist = ClassRegistry::init('AuthenticationBlacklist');
				$bl = array(
					'ip' => $this->ip,
					'expiry' => gmdate('Y-m-d H:i:s', gmdate('U') + $this->blacklistTime)
				);
				// record blacklisting in database
				$AuthenticationBlacklist->save($bl);
			}
			return false;
		}
		$this->controller->Session->delete('Throttle.nextLoginTime');
		return $identified;
	}
}

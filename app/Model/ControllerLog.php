<?php
/**
 * ControllerLog Model
 * Redefine a log object that is more extensive than default cakephp system
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('AppModel', 'Model');
App::uses('User', 'Model');
App::uses('UserAgent', 'Model');

class ControllerLog extends AppModel {

	public $name = 'ControllerLog';

	public $hasOne = [
		'User',
		'Role',
		'UserAgent'
	];

/**
 * Get the validation rules for a given context
 *
 * @param string $case (optional) The target validation case if any.
 * @return array validation rules
 * @access public
 */
	public static function getValidationRules($case = null) {
		$default = [
			'id' => [
				'uuid' => [
					'rule' => 'uuid',
					'message' => __('The UUID must be in correct format')
				]
			],
			'user_id' => [
				'uuid' => [
					'required' => true,
					'allowEmpty' => false,
					'rule' => 'uuid',
					'message' => __('The UUID must be in correct format'),
				],
				'exist' => [
					'rule' => ['userExists', null],
					'message' => __('The user id provided does not exist')
				]
			],
			'role_id' => [
				'uuid' => [
					'required' => true,
					'allowEmpty' => false,
					'rule' => 'uuid',
					'message' => __('UUID must be in correct format')
				]
			],
			'level' => [
				'format' => [
					'required' => true,
					'allowEmpty' => false,
					'rule' => ['validLogLevel', null],
					'message' => __('The level does not exist')
				]
			],
			'method' => [
				'format' => [
					'required' => true,
					'allowEmpty' => false,
					'rule' => ['validHttpMethod', null],
					'message' => __('The HTTP method does not exist')
				]
			],
			'user_agent_id' => [
				'uuid' => [
					'required' => true,
					'allowEmpty' => false,
					'rule' => 'uuid',
					'message' => __('The UUID must be in correct format')
				]
			],
			'ip' => [
				'ip' => [
					'required' => true,
					'allowEmpty' => true,
					'rule' => ['ip', 'both'],
					'message' => __('The IP address must be in correct format')
				]
			],
			'controller' => [
				'size' => [
					'required' => true,
					'allowEmpty' => false,
					'rule' => ['lengthBetween', 1, 64],
					'message' => __('The controller name should be max 64 characters'),
				]
			],
			'action' => [
				'size' => [
					'required' => true,
					'allowEmpty' => false,
					'rule' => ['lengthBetween', 1, 64],
					'message' => __('The action name should be max 64 characters'),
				]
			],
			'scope' => [
				'size' => [
					'rule' => ['lengthBetween', 1, 64],
					'message' => __('The scope name should be max 64 characters'),
				]
			],
		];
		return $default;
	}

/**
 * Check if the log level is valid
 *
 * @param array $check an array with level to check as 'level' key
 * @return bool true if matching one of the supported level type
 */
	static public function validLogLevel($check) {
		if (!isset($check['level']) || empty($check['level'])) {
			return false;
		}

		return ($check['level'] == Status::ERROR ||
			$check['level'] == Status::WARNING ||
			$check['level'] == Status::NOTICE ||
			$check['level'] == Status::DEBUG ||
			$check['level'] == Status::SUCCESS);
	}

/**
 * Check if the http method name is valid (GET|PUT|POST|DELETE)
 *
 * @param array $check an array with level to check as 'method' key
 * @return bool true if matching one of the official method type
 */
	static public function validHttpMethod($check) {
		if (!isset($check['method']) || empty($check['method'])) {
			return false;
		}
		$check['method'] = strtoupper($check['method']);

		return ($check['method'] == 'GET' ||
			$check['method'] == 'PUT' ||
			$check['method'] == 'POST' ||
			$check['method'] == 'DELETE');
	}

/**
 * Write a controller log entry
 *
 * @param string $level see. Status::ERROR, Status::WARNING, etc.
 * @param CakeRequest $request the requestio nobject
 * @param string $message (optional)
 * @param string $scope additional message category (optional)
 * @return bool true if could write, false if config does not allow it
 * @throws ValidationException if any of the controller log parameters are not valid
 * @throws Exception if there is a system error that prevented the save operation
 */
	static public function write($level, $request, $message = null, $scope = null) {
		// Only log if the config allows it
		if (!ControllerLog::validLogLevel(['level' => $level])) {
			throw new ValidationException(__('Could not validate the controller log'), []);
		}
		$config = Configure::read('Log.' . $level);
		if (!isset($config) || empty($config) || $config == false) {
			return false;
		}

		$data['ControllerLog']['level'] = strtolower($level);

		// auto populate user role and id
		$user = User::get();
		$data['ControllerLog']['user_id'] = $user['User']['id'];
		$data['ControllerLog']['role_id'] = $user['Role']['id'];

		// auto populate user agent and IP
		$userAgent = UserAgent::get();
		$data['ControllerLog']['user_agent_id'] = $userAgent['UserAgent']['id'];
		$data['ControllerLog']['ip'] = $request->clientIp(false);
		if (empty($data['ControllerLog']['ip'])) {
			$data['ControllerLog']['ip'] = '127.0.0.1';
		}

		// auto populate http method
		foreach (['get', 'post', 'put', 'delete'] as $method) {
			if ($request->is($method)) {
				$data['ControllerLog']['method'] = strtoupper($method);
			}
		}
		if (empty($data['ControllerLog']['method'])) {
			$data['ControllerLog']['method'] = 'GET';
		}

		// set controller name and action
		$data['ControllerLog']['controller'] = $request->controller;
		$data['ControllerLog']['action'] = $request->action;

		// set message & scope if not empty
		if (isset($message) && !empty($message)) {
			$data['ControllerLog']['message'] = substr($message, 0, 512);
		}
		if (isset($scope) && !empty($scope)) {
			$data['ControllerLog']['scope'] = $scope;
		}

		// get interesting information from request if there was an error
		$logData = Configure::read('Log.request_data') === 'all' ||
			($level == Status::ERROR && (Configure::read('Log.request_data') === true || Configure::read('Log.request_data') === 'error'));
		if ($logData) {
			$tmp['data'] = $request->data;
			$tmp['query'] = $request->query;
			$tmp['params'] = $request->params;
			$data['ControllerLog']['request_data'] = (json_encode($tmp));
		}

		// And save
		$_this = Common::getModel('ControllerLog');
		if (!$_this->save($data)) {
			if (isset($_this->validationErrors)) {
				$msg = __('Could not validate the controller log');
				if (Configure::read('debug')) {
					$msg .= "\n" . json_encode($_this->validationErrors) . "\n" . json_encode($data);
				}
				throw new ValidationException($msg, $_this->validationErrors);
			}
			throw new Exception(__('System error, could not save'));
		}
		return true;
	}
}

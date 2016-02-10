<?php
/**
 * User Agent
 * Short string that web browsers and other applications send to identify themselves to web servers
 *
 * @copyright    (c) 2015-present Passbolt.com
 * @licence        GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('AppModel', 'Model');

class UserAgent extends AppModel {
	public $name = 'UserAgent';
	public $useTable = 'user_agents';

/**
 * Get the validation rules upon context
 *
 * @param string $case (optional) The target validation case if any.
 * @return array cakephp validation rules
 */
	public static function getValidationRules($case = 'default') {
		$rules = [
			'id' => [
				'uuid' => [
					'rule' => 'uuid',
					'required' => true,
					'allowEmpty' => false,
					'message' => __('UUID must be in correct format')
				]
			],
			'name' => [
				'size' => [
					'required' => true,
					'allowEmpty' => false,
					'rule' => ['lengthBetween', 1, 512],
					'message' => __('User agent name should be between %s and %s characters long'),
				]
			]
		];

		return $rules;
	}

/**
 * Find user agent id from name defined in environment variable
 *
 * @return string uuid if found false otherwise
 */
	static public function get() {
		// Produce a cleaned up user agent from environment
		// Create a very restrictive configuration.
		$userAgent['UserAgent']['name'] = Purifier::clean(env('HTTP_USER_AGENT'), 'nohtml');
		if (empty($userAgent['UserAgent']['name'])) {
			$userAgent['UserAgent']['name'] = 'Empty user agent';
		}
		if (strlen($userAgent['UserAgent']['name']) > 512) {
			$userAgent['UserAgent']['name'] = substr($userAgent['UserAgent']['name'], 0, 512);
		}
		$userAgent['UserAgent']['id'] = Common::uuid($userAgent['UserAgent']['name']);

		UserAgent::createIfDoesNotExist($userAgent);

		return $userAgent;
	}

/**
 * Create a User agent record if it does not exist yet
 *
 * @param array $userAgent
 * @return bool true if exist or save is successful, false if save failed
 * @throws ValidationException if the user agent is not valid
 * @throws Exception if a system error occurs during save
 */
	static public function createIfDoesNotExist($userAgent = null) {
		// Check if the name based uuid can be found
		$_this = Common::getModel('UserAgent');
		$exist = $_this->find('count', [
			'conditions' => ['UserAgent.id' => $userAgent['UserAgent']['id']]
		]);

		// If not insert it
		if (!$exist) {
			if (!$_this->save($userAgent)) {
				if (isset($_this->validationErrors)) {
					throw new ValidationException(__('Could not validate the user agent'), $_this->validationErrors);
				}
				throw new Exception(__('Could not save'));
			}
		}

		return true;
	}
}
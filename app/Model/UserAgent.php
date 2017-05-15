<?php
/**
 * User Agent
 * Short string that web browsers and other applications send to identify themselves to web servers
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
use UserAgentParser\Provider;

App::uses('AppModel', 'Model');

class UserAgent extends AppModel {

	public $name = 'UserAgent';

	public $useTable = 'user_agents';

/**
 * The parsed user agent string exploded in an array
 *
 * @var array
 */
	protected static $_userAgent = [];

/**
 * Get the user agent parser
 *
 * @return \UserAgentParser\Model\UserAgent
 * @throws \UserAgentParser\Exception\NoResultFoundException
 */
	protected static function _getParser() {
		// For now we use the simple DonatjUAParser which allow only a basic parsing to retrieve
		// browser information. Other parser are available, check out the project repository for more information:
		// https://github.com/ThaDafinser/UserAgentParser
		// This version of Provider\DonatjUAParser has been patched, see the bug :
		// https://github.com/ThaDafinser/UserAgentParser/issues/61
		$provider = new Provider\DonatjUAParser();
		$parser = $provider->parse(env('HTTP_USER_AGENT'));
		return $parser;
	}

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
 * Get user agent id from name defined in environment variable
 *
 * @return array
 * @throws Exception
 * @throws ValidationException
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
 * Get user agent details from name defined in environment variable
 *
 * @return array
 * @throws Exception
 * @throws ValidationException
 */
	static public function parse() {
		$userAgent = self::get();

		// If the user has already been parsed.
		if (isset($userAgent['Browser']['name'])) {
			return $userAgent;
		}

		// Parse the user agent string.
		try {
			$parser = self::_getParser();
			$userAgent['Browser']['name'] = $parser->getBrowser()->getName();
			$userAgent['Browser']['version'] = $parser->getBrowser()->getVersion()->getComplete();
		} catch (Exception $e) {
			$userAgent['Browser']['name'] = '';
			$userAgent['Browser']['version'] = '';
		}

		return $userAgent;
	}

/**
 * Create a User agent record if it does not exist yet
 *
 * @param array $userAgent data with id set (derived from name as predictable uuid)
 * @return bool true if exist or save is successful, false if save failed
 * @throws ValidationException if the user agent is not valid
 * @throws Exception if no user agent is provided
 * @throws Exception if a system error occurs during save
 */
	static public function createIfDoesNotExist($userAgent = null) {
		// Check provided data
		if (!isset($userAgent['UserAgent']) || empty($userAgent['UserAgent'])) {
			throw new Exception('No user agent provided');
		}

		// Set id if not found
		if (!isset($userAgent['UserAgent']['id'])) {
			if (!isset($userAgent['UserAgent']['name'])) {
				throw new Exception('No user agent provided');
			}
			$userAgent['UserAgent']['id'] = Common::uuid($userAgent['UserAgent']['name']);
		}

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

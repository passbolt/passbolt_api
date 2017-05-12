<?php
/**
 * AuthenticationToken Model
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class AuthenticationToken extends AppModel {

/**
 * Details of belongs to relationships
 *
 * @var array
 * @link http://book.cakephp.org/2.0/en/models/associations-linking-models-together.html#
 */
	public $belongsTo = [
		'User',
	];

/**
 * Model behaviors
 *
 * @link http://api20.cakephp.org/class/model#
 */
	public $actsAs = [
		'Trackable'
	];

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
					'message' => __('UUID must be in correct format')
				]
			],
			'user_id' => [
				'uuid' => [
					'rule' => 'uuid',
					'required' => true,
					'allowEmpty' => false,
					'message' => __('UUID must be in correct format')
				],
				'exist' => [
					'rule' => ['userExists', null],
					'message' => __('The user id provided does not exist')
				],
			],
			'token' => [
				'uuid' => [
					'rule' => 'uuid',
					'message' => __('This token has an invalid format')
				],
				'unique' => [
					'rule' => 'isUnique',
					'required' => 'create',
					'message' => __('This token already exists')
				]
			]
		];

		return $rules;
	}

/**
 * Return the find conditions to be used for a given context.
 *
 * @param null|string $case The target case.
 * @param null|string $role The user role.
 * @param null|array $data (optional) Optional data to build the find conditions.
 * @return array
 *
 * @throws Exception
 */
	public static function getFindConditions($case = null, $role = Role::GUEST, &$data = null) {
		$conditions = [];

		switch ($case) {
			case 'findValid':
				$conditions = [
					'conditions' => [
						'AuthenticationToken.user_id' => $data['AuthenticationToken.user_id'],
						'AuthenticationToken.token' => $data['AuthenticationToken.token'],
						'AuthenticationToken.active' => 1
					],
					'order' => [
						'created' => 'DESC'
					]
				];

				break;
		}

		return $conditions;
	}

/**
 * Check if a token expired
 *
 * @param string $token UUID
 * @return bool
 */
	static public function isNotExpired($token) {
		if (!Common::isUuid($token)) {
			return false;
		}

		$_this = Common::getModel('AuthenticationToken');
		$authToken = $_this->findFirstByToken($token);

		// If no authentication token found
		if (empty($authToken)) {
			return false;
		}

		// Check if the authentication token is expired
		$expiredTimestamp = time() - ( Configure::read('Auth.tokenExpiracy') * 60 );
		if ((strtotime($authToken['AuthenticationToken']['created']) - $expiredTimestamp) < 0) {
			return false;
		}

		return true;
	}

/**
 * Check if a token exist and is valid for a given user.
 *
 * A valid token :
 *  - belongs to the given user ;
 *  - is active ;
 *  - is not expired ;
 *
 * @param string $token the token to check
 * @param string $userId uuid of the user
 * @return array or null if doesn't exist.
 */
	static public function isValid($token, $userId) {
		// Is token in valid format
		if (!Common::isUuid($token) || !Common::isUuid($userId)) {
			return false;
		}

		// Does token exist?
		$_this = Common::getModel('AuthenticationToken');
		$data = [
			'AuthenticationToken.user_id' => $userId,
			'AuthenticationToken.token' => $token,
		];
		$o = self::getFindOptions('findValid', User::get('Role.name'), $data);
		$token = $_this->find('first', $o);
		if (empty($token)) {
			return false;
		}

		// Is it expired?
		$t1 = strtotime($token['AuthenticationToken']['created']);
		$t0 = time() - (Configure::read('Auth.tokenExpiracy') * 60);
		if ($t0 > $t1) {
			return false;
		}
		return true;
	}

/**
 * Create a unique token for a given user.
 *
 * @param string $userId uuid of the user
 * @return array result of the save function for token or false if there was an issue
 */
	static public function generate($userId) {
		$_this = Common::getModel('AuthenticationToken');
		do {
			$token = [
				'user_id' => $userId,
				'token' => Common::uuid(),
			];
			$unique = $_this->find('count', ['conditions' => ['id' => $token['token']]]);
		} while ($unique);

		// Set the data for validation and save
		$_this->set($token);

		// Validate the token data
		if (!$_this->validates()) {
			return false;
		}
		$_this->create();
		return $_this->save($token);
	}

/**
 * Set a valid token to inactive
 *
 * @param string $token a uuid
 * @return bool true if success
 * @throws Exception if save failed
 */
	static public function setInactive($token) {
		if (!Common::isUuid($token)) {
			return false;
		}

		$_this = Common::getModel('AuthenticationToken');
		$authToken = $_this->findFirstByToken($token);
		$authToken['AuthenticationToken']['active'] = 0;

		if (!$_this->save($authToken)) {
			throw new Exception(__('Could not save the token'));
		}

		return true;
	}
}

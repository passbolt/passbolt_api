<?php
/**
 * AuthenticationToken Model
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
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
 * Check if a token exist and is valid for a given user.
 *
 * @param string $token the token to check
 * @param string $userId uuid of the user
 * @return array or null if doesn't exist.
 */
	static public function isValid($token, $userId) {
		if (!Common::isUuid($token) || !Common::isUuid($userId)) {
			return null;
		}
		// PASSBOLT-1234 check token expiracy
		$_this = Common::getModel('AuthenticationToken');
		$token = $_this->find('first', [
			'conditions' => [
				'AuthenticationToken.user_id' => $userId,
				'AuthenticationToken.token' => $token,
				'AuthenticationToken.active' => true,
			],
			'order' => [
				'created' => 'DESC'
			],
		]);

		return $token;
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
 * @param string $userId a uuid
 * @return bool true if success
 * @throws ValidationException if token id or user id are not valid
 * @throws Exception if save failed
 */
	static public function setInactive($token, $userId) {
		$data = AuthenticationToken::isValid($token, $userId);
		if (empty($data)) {
			throw new ValidationException('This is not a valid token id');
		}
		$data['AuthenticationToken']['active'] = false;
		$_this = Common::getModel('AuthenticationToken');
		if (!$_this->save($data)) {
			throw new Exception(__('System error, could not save'));
		}
		return true;
	}
}

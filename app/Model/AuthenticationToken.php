<?php
/**
 * AuthenticationToken Model
 *
 * Copyright 2012, Passbolt
 * Passbolt(tm), the simple password management solution
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright		Copyright 2012, Passbolt.com
 * @package			app.Model.authenticationToken
 * @since			version 2.12.7
 * @license			http://www.passbolt.com/license
 */
class AuthenticationToken extends AppModel {

	/**
	 * Details of belongs to relationships
	 *
	 * @var array
	 * @link http://book.cakephp.org/2.0/en/models/associations-linking-models-together.html#
	 */
	public $belongsTo = array(
		'User',
	);

	/**
	 * Get the validation rules upon context
	 *
	 * @param string $case (optional) The target validation case if any.
	 * @return array cakephp validation rules
	 */
	public static function getValidationRules($case = 'default') {
		$rules = array(
			'id' => array(
				'uuid' => array(
					'rule' => 'uuid',
					'message' => __('UUID must be in correct format')
				)
			),
			'user_id' => array(
				'uuid' => array(
					'rule' => 'uuid',
					'required' => true,
					'allowEmpty' => false,
					'message'	=> __('UUID must be in correct format')
				),
				'exist' => array(
					'rule' => array('userExists', null),
					'message' => __('The user id provided does not exist')
				),
			),
			'token' => array(
				'uuid' => array(
					'rule' => 'uuid',
					'message' => __('Token has an invalid format')
				)
			)
		);
		return $rules;
	}

	/**
	 * Check if a user with same id exists
	 * @param $check
	 * @return bool
	 */
	public function userExists($check) {
		if ($check['user_id'] == null) {
			return false;
		} else {
			$exists = $this->User->find('count', array(
				'conditions' => array('User.id' => $check['user_id'])
			));
			return $exists > 0;
		}
	}

	/**
	 * Generate a token.
	 * @return string
	 */
	public static function generateToken() {
		return Common::uuid();
	}

	/**
	 * Check if a token exist and is valid for a given user.
	 *
	 * @param string $token
	 * @param uuid $userId
	 *
	 * @return array or null if doesn't exist.
	 */
	public function checkTokenIsValidForUser($token, $userId) {
		// @todo PASSBOLT-1234 check token expiracy
		$token = $this->find('first', array(
				'conditions' => array(
					'AuthenticationToken.user_id' => $userId,
					'AuthenticationToken.token' => $token,
					'AuthenticationToken.active' => TRUE,
				),
				'order' => array(
					'created' => 'DESC'
				),
			));
		return $token;
	}

	/**
	 * Create a token for a given user.
	 * @param uuid $userId
	 * @return array result of the save function for token
	 */
	public function createToken($userId) {
		$token = array(
			'user_id' => $userId,
			'token' => self::generateToken(),
		);

		// Set the data for validation and save
		$this->set($token);

		// Validate the token data
		if (!$this->validates()) {
			return false;
		}
		$this->create();
		return $this->save($token);
	}
}

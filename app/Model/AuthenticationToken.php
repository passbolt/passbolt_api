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
	 * Length of the base random string used to generate tokens.
	 */
	const TOKEN_STRING_LENGTH = 30;

	/**
	 * Token Types
	 */
	const MD5 = 'md5';
	const UUID = 'UUID';

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
		);
		switch ($case) {
			default:
			case 'default':
			case self::MD5 :
				$rules['token'] = array(
					'validMD5' => array(
						'rule' => array('checkValidMd5', true),
						'required' => true,
						'allowEmpty' => false,
						'message' => __('Token has an invalid format')
					)
				);
				break;
			case self::UUID :
				$rules['token'] = array(
					'uuid' => array(
						'rule' => 'uuid',
						'message' => __('UUID must be in correct format')
					)
				);
				break;
		}
		return $rules;
	}

	/**
	 * Check validation rule, whether a md5 is valid.
	 * @param $check
	 * @return bool
	 */
	public function checkValidMd5($check) {
		if ($check['token'] == null) {
			return false;
		} else {
			return strlen($check['token']) == 32 && ctype_xdigit($check['token']);
		}
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
	public static function generateToken($type = self::MD5) {
		switch($type) {
			default:
			case self::MD5:
				$rdStr = Common::randomString(self::TOKEN_STRING_LENGTH);
				$token = md5($rdStr + time());
				break;
			case self::UUID:
				$token = Common::uuid();
				break;
		}
		return $token;
	}

	/**
	 * Check if a token is valid.
	 *
	 * @param string $token
	 * @param uuid $userId
	 *
	 * @return array or null if doesn't exist.
	 */
	public function checkTokenIsValid($token, $userId) {
		// @todo check token expiracy
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
	 * @param string $type MD5 or UUID
	 * @return array result of the save function for token
	 */
	public function createToken($userId, $type = self::MD5) {
		$token = array(
			'user_id' => $userId,
			'token' => self::generateToken($type),
		);

		$this->set($token);
		$this->setValidationRules($type);
		$v = $this->validates();
		if (!$v) {
			return false;
		}
		$this->create();
		$s = $this->save($token);
		return $s;
	}
}

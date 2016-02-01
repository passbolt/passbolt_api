<?php

App::uses('ProfileAvatar', 'Model');

/**
 * Profile Model
 *
 * Copyright 2012, Passbolt
 * Passbolt(tm), the simple password management solution
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright          Copyright 2012, Passbolt.com
 * @package            app.Model.profile
 * @since              version 2.12.7
 * @license            http://www.passbolt.com/license
 */
class Profile extends AppModel {

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
 * Details of has one relationships
 *
 * @var array
 * @link http://book.cakephp.org/2.0/en/models/associations-linking-models-together.html#
 */
	public $hasOne = array(
		'Avatar' => array(
			'className' => 'ProfileAvatar',
			'foreignKey' => 'foreign_key',
		),
	);

/**
 * Get the validation rules upon context
 *
 * @param string context
 *
 * @return array validation rules
 * @throws exception if case is undefined
 * @access public
 */
	public static function getValidationRules($case = 'default') {
		$default = array(
			'user_id' => array(
				'uuid' => array(
					'rule' => 'uuid',
					'required' => 'create',
					'allowEmpty' => false,
					'message'	=> __('UUID must be in correct format')
				),
				'exist' => array(
					'rule' => array('userExists', null),
					'message' => __('The user id provided does not exist')
				),
			),
			'gender' => array(
				'required' => array(
					'allowEmpty' => false,
					'rule' => array('notEmpty'),
					'message' => __('Gender cannot be empty')
				),
				'inList' => array(
					'rule' => array('inList', array('m', 'f')),
					'message' => __('Gender can be only "m" or "f"')
				)
			),
			'date_of_birth' => array(
				'date' => array(
					'rule' => array('date', 'ymd'),
					'message' => 'Enter a valid date of birth in YY-MM-DD format.',
					'allowEmpty' => false
				)
			),
			'title' => array(
				'inList' => array(
					'rule' => array('inList', array('Mr', 'Ms', 'Mrs', 'Dr')),
					'message' => __('A valid title has to be provided'),
					'allowEmpty' => false
				),
			),
			'first_name' => array(
				'alphaNumericAndSpecial' => array(
					'rule' => "/^[\p{L} \-']*$/u",
					'required' => true,
					'allowEmpty' => false,
					'message'	=> __('First name should only contain alphabets and the special characters : - \'')
				),
				'size' => array(
					'rule' => array('lengthBetween', 3, 64),
					'message' => __('First name should be between %s and %s characters long'),
				)
			),
			'last_name' => array(
				'alphaNumericAndSpecial' => array(
					'rule' => "/^[\p{L} \-']*$/u",
					'required' => true,
					'allowEmpty' => false,
					'message'	=> __('Last name should only contain alphabets and the special characters : - \'')
				),
				'size' => array(
					'rule' => array('lengthBetween', 3, 64),
					'message' => __('Last name should be between %s and %s characters long'),
				)
			),
		);
		switch ($case) {
			default:
			case 'default' :
				$rules = $default;
		}

		return $rules;
	}

	public static function getFindFields($case = '', $role = Role::USER) {
		$returnValue = array();
		switch ($case) {
			case 'view':
				$returnValue = array(
					'fields' => array(
						'Role.id',
						'Role.name'
					)
				);
				break;
			case 'User::save':
			case 'User::edit':
				$returnValue = array(
					'fields' => array(
						'user_id',
						'first_name',
						'last_name',
					)
				);
				break;
			default:
				$returnValue = array(
					'fields' => array()
				);
				break;
		}

		return $returnValue;
	}

	/**
	 * AfterFind callback.
	 *
	 * Used mainly to initialize default avatars.
	 * It is added here, because ProfileAvatar after Find is not executed if the result is empty.
	 *
	 * @param mixed $results
	 * @param bool  $primary
	 *
	 * @return mixed
	 */
	public function afterFind($results, $primary = false) {
		if ($primary === false) {
			foreach ($results as $key => $result) {
				if(empty($result['Profile']['Avatar'])) {
					$result['Profile']['Avatar'] = array();
				}
				$results[$key]['Profile']['Avatar'] = $this->Avatar->addPathsInfo($result['Profile']['Avatar']);
			}
		}
		else {
			$results['Avatar'] = empty($results['Avatar']) ? array() : $results['Avatar'];
			$results['Avatar'] = $this->Avatar->addPathsInfo($results['Avatar']);
		}
		return $results;
	}
}

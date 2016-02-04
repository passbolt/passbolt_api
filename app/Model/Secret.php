<?php
/**
 * Secret model
 *
 * @copyright     Copyright 2012 Passbolt.com
 * @license       http://www.passbolt.com/license
 * @package       app.Model.Secret
 * @since         version 2.12.7
 */

App::uses('User', 'Model');
App::uses('Resource', 'Model');

class Secret extends AppModel {

	public $actsAs = array(
		'Containable',
		'Trackable'
	);

	public $belongsTo = array(
		'Resource',
		'User',
	);

/**
 * Get the validation rules upon context
 *
 * @param string case (optional) The target validation case if any.
 * @return array cakephp validation rules
 */
	public static function getValidationRules($case = 'default') {
		$default = array(
			'user_id' => array(
				'uuid' => array(
					'rule' => 'uuid',
					'required' => true,
					'allowEmpty' => false,
					'message'	=> __('The secret uid must be provided and in correct format')
				),
				'exist' => array(
					'rule' => array('userExists', null),
					'message' => __('The user provided does not exist')
				),
			),
			'resource_id' => array(
				'uuid' => array(
					'rule' => 'uuid',
					'required' => true,
					'allowEmpty' => false,
						'message'	=> __('The resource uid must be in correct format')
				),
				'exist' => array(
					'rule' => array('resourceExists', null),
					'message' => __('The resource provided does not exist')
				),
			),
			'data' => array(
				'isNotEmpty' => array(
					'required' => 'create',
					'rule' => 'notEmpty',
					'message' => __('The secret must be provided')
				),
				'isGpgFormat' => array(
					'rule'    => array('checkGpgMessageIsValid', null),
					'message' => __('The message provided is not in the right format'),
				),
			),
		);
		switch ($case) {
			default:
			case 'default':
				$rules = $default;
			break;
		}
		return $rules;
	}

/**
 * Check if a resource with same id exists
 * @param check
 * @return bool
 */
	public function resourceExists($check) {
		if ($check['resource_id'] == null) {
			return false;
		} else {
			$exists = $this->Resource->find('count', array(
				'conditions' => array(
					'Resource.id' => $check['resource_id']
				),
				'recursive' => -1
			));
			return $exists > 0;
		}
	}

/**
 * Check a gpg message is valid
 * @param $check
 * @return bool
 */
	public function checkGpgMessageIsValid($check) {
		if ($check['data'] == null) {
			return false;
		} else {
			$isMarker = preg_match('/-(BEGIN )*([A-Z0-9 ]+)-/', $check['data'], $values);
			if (!$isMarker || !isset($values[2])) {
				return false;
			}
			$marker = $values[2];
			$msgUnarmored = OpenPGP::unarmor($check['data'], $marker);
			if ($msgUnarmored != false) {
				// Message in right format.
				$msg = OpenPGP_Message::parse($msgUnarmored);
				return true;
			}
			return false;
		}
	}

/**
 * Return the find conditions to be used for a given context.
 *
 * @param null|string $case The target case.
 * @param null|string $role The user role.
 * @param null|array $data (optional) Optional data to build the find conditions.
 * @return array
 */
	public static function getFindConditions($case = 'view', $role = Role::USER, $data = null) {
		$conditions = array();

		switch ($case) {
			case 'add':
			case 'edit':
			case 'view':
				$conditions = array(
					'conditions' => array(
					)
				);
			break;

			default:
				$conditions = array(
					'conditions' => array()
				);
		}

		return $conditions;
	}

/**
 * Return the list of field to fetch for given context
 * @param string $case context ex: login, activation
 * @return array $condition
 */
	public static function getFindFields($case = 'view', $role = Role::USER) {
		switch($case){
			case 'view':
				$fields = array(
					'fields' => array(
						'id',
						'user_id',
						'resource_id',
						'data',
						'created',
						'modified',
						'created_by',
						'modified_by'
					));
			break;
			case 'save':
				$fields = array(
					'fields' => array(
						'user_id',
						'resource_id',
						'data',
						'created',
						'modified',
						'created_by',
						'modified_by'
					));
            break;
			case 'update':
				$fields = array(
					'fields' => array(
						'user_id',
						'resource_id',
						'data',
						'created',
						'modified',
						'created_by',
						'modified_by'
					));
            break;
			default:
				$fields = array(
					'fields' => array()
				);
			break;
		}
		return $fields;
	}
}

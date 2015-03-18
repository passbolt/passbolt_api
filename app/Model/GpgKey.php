<?php
/**
 * Gpg Key Model
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Model.GpgKey
 * @since        version 2.12.9
 */
class Gpgkey extends AppModel {

	public $name = 'Gpgkey';

	public $useTable = 'gpgkeys';

/**
 * Import a key in the keyring
 *
 * @param string $key 
 * @return void
 * @access public
 */
	public function import($key) {
		$res = gnupg_init();
		$import = gnupg_import($res, $key);
		return $import;
	}

/**
 * Remove a key from the keyring
 *
 * @param string $fingerprint
 * @return void
 * @access public
 */
	public function remove($fingerprint) {
		$res = gnupg_init();
		$remove = gnupg_deletekey($res, $fingerprint);
		return $remove;
	}

/**
 * Check the fingerprint of a key
 *
 * @param string $fingerprint
 * @return mixed array or false if error
 * @access public
 */
	public function info($fingerprint) {
		$res = gnupg_init();
		$info = gnupg_keyinfo($res, $fingerprint);

		$i = FALSE;
		if ($info) {
			$i = array(
				'fingerprint' => $fingerprint,
				'bits' => null,
				'type' => null,
				'key_id' => substr($fingerprint, -8),
				'key_created' => $info[0]['subkeys'][0]['timestamp'],
				'uid' => $info[0]['uids'][0]['uid'],
				'expires' => $info[0]['subkeys'][0]['expires'],
			);
		}
		return $i;
	}

	/**
	 * Get the validation rules upon context
	 *
	 * @param string case (optional) The target validation case if any.
	 * @return array CakePHP validation rules
	 */
	public static function getValidationRules($case = 'default') {
		$default = array(
			'id' => array(
				'uuid' => array(
					'rule' => 'uuid',
					'required' => 'update',
					'message' => __('Id must be in correct format'),
				)
			),
			'user_id' => array(

			),
			'key' => array(

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
	 * Analyze key before saving it, and extract key information.
	 *
	 * @param array $options
	 *
	 * @return bool
	 */
	public function beforeSave($options = array()) {
		if (!empty($this->data['Gpgkey']['key']) &&
			empty($this->data['Gpgkey']['fingerprint'])
		) {
			$info = $this->import($this->data['Gpgkey']['key']);
			$info = $this->info($info['fingerprint']);
			$this->data['Gpgkey'] = array_merge(
				$this->data['Gpgkey'],
				$info
			);
		}
		return true;
	}


	/**
	 * Return the find conditions to be used for a given context.
	 *
	 * @param null|string $case The target case.
	 * @param null|string $role The user role.
	 * @param null|array $data (optional) Optional data to build the find conditions.
	 * @return array
	 */
	public static function getFindConditions($case = 'view', $role = Role::ANONYMOUS, $data = null) {
		switch ($case) {
			case 'index':
				$conditions = array('Gpgkey.deleted' => 0);
				if (isset($data['modified_after'])) {
					$conditions['Gpgkey.modified >='] = $data['modified_after'];
				}
				$conditions = array('conditions' => $conditions);
				break;
			case 'view':
				$conditions = array('conditions' => array('Gpgkey.deleted' => 0, 'Gpgkey.user_id' => $data['Gpgkey.user_id']));
				break;
			default:
				$conditions = array('conditions' => array());
				break;
		}
		return $conditions;
	}

	/**
	 * Return the list of field to fetch for given context.
	 *
	 * @param string $case context ex: login, activation
	 * @return array
	 */
	public static function getFindFields($case = 'view', $role = Role::USER) {
		switch ($case) {
			case 'view':
			case 'index':
				$fields = array('fields' => array(
					'user_id',
					'key',
					'bits',
					'uid',
					'key_id',
					'fingerprint',
					'type', 'expires',
					'modified'
				));
				break;
			case 'delete':
				$fields = array('fields' => array(
					'deleted'
				));
				break;
			case 'save':
				$fields = array('fields' => array(
					'user_id',
					'key',
					'bits',
					'uid',
					'key_id',
					'fingerprint',
					'type',
					'expires',
					'parent_id',
					'key_created'
				));
				break;
		}
		return $fields;
	}
}

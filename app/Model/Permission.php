<?php

/**
 * Permission  model
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class Permission extends AppModel {

/**
 * Model behaviors
 *
 * @link http://api20.cakephp.org/class/model#
 */
	public $actsAs = ['Containable', 'Trackable'];

/**
 * Details of belongs to relationships
 *
 * @link http://book.cakephp.org/2.0/en/models/associations-linking-models-together.html#
 */
	public $belongsTo = [
		'PermissionType' => [
			'foreignKey' => 'type'
		],
		'Resource' => [
			'foreignKey' => 'aco_foreign_key'
		],
		'User' => [
			'foreignKey' => 'aro_foreign_key',
		],
		'Group' => [
			'foreignKey' => 'aro_foreign_key',
		]
	];

/**
 * Get the validation rules upon context
 *
 * @param string $case 'default' or 'type' (optional)
 * @return array cakephp validation rules
 */
	public static function getValidationRules($case = 'default') {
		$default = [
			'aco' => [
				'rule' => ['validateAco']
			],
			'aco_foreign_key' => [
				'uuid' => [
					'rule' => 'uuid',
					'required' => true,
					'allowEmpty' => false,
					'message' => __('aco_foreign_key must be an uuid in correct format')
				],
				'aco_foreign_key' => [
					'rule' => ['validateAcoForeignKey'],
					'message' => __('the aco_foreign_key must be relative to an existing instance of aco model')
				]
			],
			'aro' => [
				'rule' => ['validateAro']
			],
			'aro_foreign_key' => [
				'uuid' => [
					'rule' => 'uuid',
					'required' => true,
					'allowEmpty' => false,
					'message' => __('aro_foreign_key must be an uuid in correct format')
				],
				'aro_foreign_key' => [
					'rule' => ['validateAroForeignKey'],
					'message' => __('the aro_foreign_key must be relative to an existing instance of aro model')
				]
			],
			'type' => [
				'rule' => 'validatePermissionType',
				'required' => true,
				'allowEmpty' => false,
				'message' => __('The given permission type is not valid')
			],
		];
		switch ($case) {
			case 'edit':
				$rules['type'] = $default['type'];
				break;
			default:
			case 'default' :
				$rules = $default;
				break;
		}

		return $rules;
	}

/**
 * Details of after save method
 *
 * @param array $options Options passed from Model::save().
 * @return bool True if the operation should continue, false if it should abort
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#beforesave
 * @see Model::save()
 */
	public function beforeSave($options = []) {
		// If the debug mode is enabled.
		// Generate a permission id based on the aco foreign key and the aro foreign key.
		// It will help us to retrieve permission for debugging or testing.
		if (Configure::read('debug') > 0) {
			if (empty($this->data['Permission']['id'])) {
				$this->data['Permission']['id'] = Common::uuid('permission.id.' . $this->data['Permission']['aco_foreign_key'] . '-' . $this->data['Permission']['aro_foreign_key']);
			}
		}
	}

/**
 * Validation Rule : Check if the given ACO key is an allowed ACO model
 *
 * @param array $check the data to test
 * @return bool
 */
	public function validateAco($check) {
		return $this->isValidAco($check['aco']);
	}

/**
 * Validation Rule : Check if the given ARO key is an allowed ARO model
 *
 * @param array $check the data to test
 * @return bool
 */
	public function validateAro($check) {
		return $this->isValidAro($check['aro']);
	}

/**
 * Validation Rule : check if the given aco foreign key is relative to an existing instance
 *
 * @param array $check the data to test
 * @return bool
 */
	public function validateAcoForeignKey($check) {
		return $this->validateExists($check, 'aco_foreign_key', $this->data[$this->alias]['aco']);
	}

/**
 * Validation Rule : Check if the given aro foreign key is relative to an existing instance
 *
 * @param array $check the data to test
 * @return bool
 */
	public function validateAroForeignKey($check) {
		return $this->validateExists($check, 'aro_foreign_key', $this->data[$this->alias]['aro']);
	}

/**
 * Validation Rule : Check if the given permission type is valid
 *
 * @param array $check with 'type' key set
 * @return bool
 */
	public function validatePermissionType($check) {
		return $this->PermissionType->isValidSerial($check['type']);
	}

/**
 * Validation Rule : Check if a permission with same parameters already exists
 *
 * @return bool
 */
	public function validateUnique() {
		return $this->isUniqueByFields(
			$this->data[$this->alias]['aco'],
			$this->data[$this->alias]['aco_foreign_key'],
			$this->data[$this->alias]['aro'],
			$this->data[$this->alias]['aro_foreign_key']);
	}

/**
 * Check if the given ACO key is an allowed ACO model
 *
 * @param string $aco The aco key to test
 * @return bool
 */
	public function isValidAco($aco) {
		return in_array($aco, Configure::read('Permission.acoModels'));
	}

/**
 * Check if the given ARO key is an allowed ACO model
 *
 * @param string $aro The aro key to test
 * @return bool
 */
	public function isValidAro($aro) {
		return in_array($aro, Configure::read('Permission.aroModels'));
	}

/**
 * Check if a permission with same parameters already exists
 *
 * @param string $aco name
 * @param string $acoForeignKey uuid
 * @param string $aro name
 * @param string $aroForeignKey uuid
 * @return bool
 */
	public function isUniqueByFields($aco, $acoForeignKey, $aro, $aroForeignKey) {
		$combination = [
			'Permission.aco' => $aco,
			'Permission.aco_foreign_key' => $acoForeignKey,
			'Permission.aro' => $aro,
			'Permission.aro_foreign_key' => $aroForeignKey
		];

		return $this->isUnique($combination, false);
	}

}

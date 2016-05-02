<?php
/**
 * Group  model
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('User', 'Model');

class Group extends AppModel {

	public $actsAs = ['Trackable'];

	public $hasMany = ['GroupUser'];

/**
 * Get the validation rules upon context
 *
 * @param string $case (optional) The target validation case if any.
 * @return array cakephp validation rules
 */
	public static function getValidationRules($case = 'default') {
		$default = [
			'id' => [
				'uuid' => [
					'rule' => 'uuid',
					'message' => __('UUID must be in correct format')
				]
			],
			'name' => [
				'alphaNumeric' => [
					'rule' => '/^[a-zA-Z0-9\-_ ]{1,64}$/i',
					'required' => true,
					'allowEmpty' => false,
					'message' => __('Alphanumeric only')
				]
			]
		];
		switch ($case) {
			default:
			case 'default':
				$rules = $default;
				break;
		}

		return $rules;
	}

/**
 * Return the find conditions to be used for a given context.
 *
 * @param null|string $case The target case.
 * @param null|string $role The user role.
 * @param null|array $data (optional) Optional data to build the find conditions.
 * @return array
 */
	public static function getFindConditions($case = 'view', $role = null, $data = null) {
		$conditions = [];

		switch ($case) {
			case 'add':
			case 'edit':
			case 'view':
				$conditions = ['conditions' => ['Group.deleted' => 0, 'Group.id' => $data['Group.id']]];
				break;
			case 'index':
				$conditions = ['conditions' => []];
				if (isset($data['keywords'])) {
					$keywords = explode(' ', $data['keywords']);
					foreach ($keywords as $keyword) {
						$conditions['conditions']["AND"][] = ['Group.name LIKE' => '%' . $keyword . '%'];
					}
				}
				break;
			default:
				$conditions = ['conditions' => []];
		}

		return $conditions;
	}

/**
 * Return the list of field to fetch for given context
 *
 * @param string $case context ex: login, activation
 * @return $condition array
 */
	public static function getFindFields($case = 'view', $role = null) {
		switch ($case) {
			case 'view':
			case 'index':
				$fields = ['fields' => ['id', 'name', 'created', 'modified']];
				break;
			case 'delete':
				$fields = ['fields' => ['deleted']];
				break;
			case 'save':
				$fields = ['fields' => ['name', 'created', 'modified', 'created_by', 'modified_by', 'deleted']];
				break;
			default:
				$fields = ['fields' => []];
				break;
		}

		return $fields;
	}
}

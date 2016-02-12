<?php
/**
 * Group  model
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('User', 'Model');

class Group extends AppModel {

	public $actsAs = array('Trackable');

	public $hasMany = array('GroupUser');

/**
 * Get the validation rules upon context
 *
 * @param string $case (optional) The target validation case if any.
 * @return array cakephp validation rules
 */
	public static function getValidationRules($case = 'default') {
		$default = array(
			'id' => array(
				'uuid' => array(
					'rule' => 'uuid',
					'message' => __('UUID must be in correct format')
				)
			),
			'name' => array(
				'alphaNumeric' => array(
					'rule' => '/^[a-zA-Z0-9\-_ ]{1,64}$/i',
				    'required' => true,
				    'allowEmpty' => false,
				    'message' => __('Alphanumeric only')
				)
			)
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
 * Return the find conditions to be used for a given context.
 *
 * @param null|string $case The target case.
 * @param null|string $role The user role.
 * @param null|array $data (optional) Optional data to build the find conditions.
 * @return array
 */
	public static function getFindConditions($case = 'view', $role = Role::ANONYMOUS, $data = null) {
		$conditions = array();

		switch ($case) {
			case 'add':
			case 'edit':
			case 'view':
				$conditions = array('conditions' => array('Group.deleted' => 0, 'Group.id' => $data['Group.id']));
				break;
			case 'index':
				$conditions = array('conditions' => array());
				if (isset($data['keywords'])) {
					$keywords = explode(' ', $data['keywords']);
					foreach ($keywords as $keyword) {
						$conditions['conditions']["AND"][] = array('Group.name LIKE' => '%' . $keyword . '%');
					}
				}
				break;
			default:
				$conditions = array('conditions' => array());
		}

		return $conditions;
	}

/**
 * Return the list of field to fetch for given context
 *
 * @param string $case context ex: login, activation
 * @return $condition array
 */
	public static function getFindFields($case = 'view', $role = Role::USER) {
		switch ($case) {
			case 'view':
			case 'index':
				$fields = array('fields' => array('id', 'name', 'created', 'modified'));
				break;
			case 'delete':
				$fields = array('fields' => array('deleted'));
				break;
			case 'save':
				$fields = array('fields' => array('name', 'created', 'modified', 'created_by', 'modified_by', 'deleted'));
				break;
			default:
				$fields = array('fields' => array());
				break;
		}
		return $fields;
	}
}

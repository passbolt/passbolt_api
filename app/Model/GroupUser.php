<?php
/**
 * GroupUser Model
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

App::uses('Group', 'Model');
App::uses('User', 'Model');

class GroupUser extends AppModel {

	public $useTable = "groups_users";

	public $belongsTo = array(
		'Group',
		'User',
	);

	public $actsAs = array('Trackable');

/**
 * Get the validation rules upon context
 * @param string $case (optional) The target validation case if any.
 * @return array cakephp validation rules
 */
	public static function getValidationRules($case = 'default') {
		$default = array(
			'group_id' => array(
				'uuid' => array(
					'rule' => 'uuid',
					'required' => true,
					'allowEmpty' => false,
					'message'	=> __('UUID must be in correct format')
				),
				'exist' => array(
					'rule' => array('validateExists', 'group_id', 'Group'),
					'message' => __('The group provided does not exist')
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
					'rule' => array('validateExists', 'user_id', 'User'),
					'message' => __('The user provided does not exist')
				),
				'uniqueCombi' => array(
					'rule' => array('uniqueCombi', null),
					'message' => __('The GroupUser entered is a duplicate')
				)
			)
		);
		switch ($case) {
			default:
			case 'default' :
				$rules = $default;
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
	public static function getFindConditions($case = 'view', $role = Role::USER, $data = null) {
		$conditions = array();
		switch ($case) {
			case 'add':
				$conditions = array();
				break;
			case 'view':
				$conditions = array(
					'conditions' => array(
						'GroupUser.id' => $data['GroupUser.id']
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
	 * @return $condition array
	 */
	public static function getFindFields($case = 'view', $role = Role::USER) {
		switch($case){
			case 'view':
			case 'add':
			case 'edit':
				$fields = array(
					'fields' => array(
						'id', 'group_id', 'user_id'
					)
				);
				break;
			case 'delete':
				$fields = array();
				break;
			default:
				$fields = array(
					'fields' => array()
				);
				break;
		}
		return $fields;
	}

	/**
	 * Check if a category with same id exists
	 * @param check
	 */
	public function uniqueCombi($check = false) {
		$cr = $this->data['GroupUser'];
		$combi = array(
			'GroupUser.group_id' => $cr['group_id'],
			'GroupUser.user_id' => $cr['user_id']
		);
		return $this->isUnique($combi, false);
	}
}

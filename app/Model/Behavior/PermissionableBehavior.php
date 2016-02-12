<?php
/**
 * Permission Behavior
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

App::uses('Permission', 'Model');
App::uses('PermissionType', 'Model');

class PermissionableBehavior extends ModelBehavior {

/**
 * Details of before find method
 *
 * @link http://api20.cakephp.org/class/model#method-ModelbeforeFind
 *
 * The permissionnable before find method is used to augment the query to find reccords
 * functions of the User and the User[AroModelName]Permission model
 */
	public function beforeFind(Model $model, $queryData = array()) {
		// If the current user is a normal user or an anonymous user,
		// all his requests will be augmented with the permissionable behavior
		// to ensure he can only access records he has permission for.
		if (User::get('Role.name') == Role::USER || User::get('Role.name') == Role::GUEST || User::get('Role.name') == Role::ADMIN) {

			// Depending on the target model the user wants to access,
			// the permissions are managed by a specific model.
			// ex : UserCategoryPermission, UserResourcePermission, GroupCategoryPermission, GroupResourcePermission
			$userPermissionModelName = 'User' . $model->alias . 'Permission';
			$foreignModelPrimaryKey = Inflector::underscore($model->alias) . '_id';

			// Filter options.
			$permOptions = array(
				'fields' => array(
					$userPermissionModelName . '.permission_id',
					$userPermissionModelName . '.permission_type'
				),
				'conditions' => array(
					// We're looking for permissions for the current user.
					$userPermissionModelName . '.user_id' => User::get('id'),
					// The user should have a permission greater than DENY.
					$userPermissionModelName . '.permission_type >' => PermissionType::DENY,
				),
				'contain' => array($userPermissionModelName)
			);

			// Bind the model the user is performing a find to our permissions model system.
			$model->bindModel(
				array(
					'hasOne' => array(
						// @todo automatically bind the [AroModelName][targetAcoModelName]Permission model to the model
						// Je ne comprends plus ce todo !
						// The target permision
						$userPermissionModelName => array(
							'foreignKey' => $foreignModelPrimaryKey
						),
						'GroupCategoryPermission' => array(
							'foreignKey' => $foreignModelPrimaryKey
						),
						'Permission' => array(
							'foreignKey' => false,
							'conditions' => array('Permission.id' => $userPermissionModelName . '.permission_id '),
							'type' => 'LEFT'
						)
					)
				), false);

			// Augment the request to add the fields we want to get
			if (!empty($queryData['fields'])) {
				if (!is_array($queryData['fields'])) {
					$queryData['fields'] = array($queryData['fields']);
				}
				$queryData['fields'] = array_merge($queryData['fields'], $permOptions['fields']);
			}

			// Augment the request to add the condition to filter the request
			if (empty($queryData['conditions'])) {
				$queryData['conditions'] = array();
			}
			if (!is_array($queryData['conditions'])) {
				$queryData['conditions'] = array($queryData['conditions']);
			}
			$queryData['conditions'] = array_merge($queryData['conditions'], $permOptions['conditions']);

			// Augment the request to add the model we want included in the request results.
			if (empty($queryData['contain'])) {
				$queryData['contain'] = array();
			}
			if (!is_array($queryData['contain'])) {
				$queryData['contain'] = array($queryData['contain']);
			}
			$queryData['contain'] = array_merge($queryData['contain'], $permOptions['contain']);
		}

		return $queryData;
	}

/**
 * Details of after find method
 *
 * @link http://api20.cakephp.org/class/model#method-ModelafterFind
 */
	public function afterFind(Model $model, $results, $primary = false) {
		if (User::get('Role.name') == Role::USER || User::get('Role.name') == Role::GUEST || User::get('Role.name') == Role::ADMIN) {

			$model->unbindModel(array(
				'hasOne' => array(
					'UserCategoryPermission',
					'Permission',
					'GroupCategoryPermission'
				)
			), false);
		}
		return $results;
	}

/**
 * Details of after save method
 *
 * @link http://api20.cakephp.org/class/model#method-ModelafterSave
 *
 * The permissionnable after save method is used to automatically give to the user
 * the ADMIN right to the records he has just inserted
 */
	public function afterSave(Model $model, $created, $options = Array()) {
		if ($created) {
			$Permission = Common::getModel('Permission');
			$userId = null;

			// When a model is marked as permissionable.
			// A permission will be automatically created for each owner of that new instance.
			// The created_by field is so compulsory.
			if (! isset($model->data[$model->alias]['created_by'])) {
				throw new Exception('The permissionable behavior requires the created_by field to be filled');
			}
			$userId = $model->data[$model->alias]['created_by'];

			// make the creator administrator of the created instance
			$data = array(
				'Permission' => array(
					'aco' => $model->alias,
					'aco_foreign_key' => $model->id,
					'aro' => 'User',
					'aro_foreign_key' => $userId,
					'type' => PermissionType::OWNER
				)
			);
			// If the debug mode is enabled.
			// Generate a permission id based on the inserted instance id and the user id.
			// It will help us to guess the permission id in the test.
			if (Configure::read('debug') > 0) {
				$data['Permission']['id'] = Common::uuid('permission.id.' . $model->id . '-' . $userId);
			}

			$Permission->create();
			$Permission->set($data);
			if (!$Permission->validates()) {
				// @todo treat this error.
				var_dump(User::get('User.id'));
				var_dump($Permission->validationErrors);
				$this->Message->error($Permission->validationErrors);
				return;
			}

			$Permission->save($data);
		}
	}

/**
 * Get the permission to an instance for a given user.
 * @param $model The type of record.
 * @param $id Id of the record.
 * @param null $aroId The target user/group to get the permission for, by default the current user.
 * @param string $aroType The target aro model to get the permission for, by default the User.
 * @return Permission
 */
	public function getPermission(&$model, $id, $aroId = null, $aroType = 'User') {
		$aroId = !is_null($aroId) ? $aroId : User::get('id');
		$targetPermissionModelName = $aroType . $model->alias . 'Permission';
		$TargetPermissionModel = Common::getModel($targetPermissionModelName);

		$findOptions = array(
			'fields' => array(
				'permission_id',
				'permission_type'
			),
			'conditions' => array(
				$targetPermissionModelName . '.' . strtolower($aroType) . '_id' => $aroId,
				$targetPermissionModelName . '.' . Inflector::underscore($model->alias) . '_id' => $id,
			),
			'contain' => array(
				'Permission(id, type)'
			)
		);
		$result = $TargetPermissionModel->find('first', $findOptions);
		return $result;
	}

/**
 * Check a user is authorized to access a reccord
 *
 * @param $model The type of record.
 * @param $id Id of the record.
 * @param string $permissionType The permission type to verify.
 * @param null $aroId The target user/group to check the permission for, by default the current user.
 * @param string $aroType The target aro model to check the permission for, by default the User.
 * @return bool
 */
	public function isAuthorized(&$model, $id, $permissionType = PermissionType::READ, $aroId = null, $aroType = 'User') {
		$aroId = !is_null($aroId) ? $aroId : User::get('id');
		$permission = $this->getPermission($model, $id, $aroId, $aroType);
		if ($permission && $permission['Permission']['type'] >= $permissionType) {
			return true;
		}
		return false;
	}

	/**
	 * Get a list of users who have permissions to access the given instance.
	 *
	 * @param      $model
	 * @param null $acoInstanceId
	 *
	 * @return array|null
	 */
	public function getAuthorizedUsers(&$model, $acoInstanceId = null) {
		// Get aco key name.
		$acoKeyName = strtolower($model->alias) . '_id';

		// If instance id is not provided as parameter, we get it from the model.
		if (is_null($acoInstanceId)) {
			$acoInstanceId = $this->id;
		}

		// Build corresponding model.
		$model = Common::getModel("User{$model->alias}Permission");

		// Retrieve the list of users.
		$users = $model->find('all', array(
				'conditions' => array(
					$acoKeyName => $acoInstanceId,
					'permission_type <>' => null
				),
				'contain' => array(
					'User' => array(
						'fields' => array(
							'User.id'
						)
					)
				)
			));

		return $users;
	}
}

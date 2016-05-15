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
 * beforeFind can be used to cancel find operations, or modify the query that will be executed.
 * By returning null/false you can abort a find. By returning an array you can modify/replace the query
 * that is going to be run.
 *
 * @param Model $model Model using this behavior
 * @param array $queryData Data used to execute this query, i.e. conditions, order, etc.
 * @return bool|array False or null will abort the operation. You can return an array to replace the
 *   $query that will be eventually run.
 */
	public function beforeFind(Model $model, $queryData = []) {
		// If the current user is a normal user (all roles except root),
		// all his requests will be augmented with the permissionable behavior
		// to ensure he can only access records he has permission for.
		if (User::get('Role.name') != Role::ROOT) {

			// Depending on the target model the user wants to access,
			// the permissions are managed by a specific model.
			// ex : UserCategoryPermission, UserResourcePermission, GroupCategoryPermission, GroupResourcePermission
			$userPermissionModelName = 'User' . $model->alias . 'Permission';
			$foreignModelPrimaryKey = Inflector::underscore($model->alias) . '_id';

			// Filter options.
			$permOptions = [
				'fields' => [
					$userPermissionModelName . '.permission_id',
					$userPermissionModelName . '.permission_type'
				],
				'conditions' => [
					// We're looking for permissions for the current user.
					$userPermissionModelName . '.user_id' => User::get('id'),
					// The user should have a permission greater than DENY.
					$userPermissionModelName . '.permission_type >' => PermissionType::DENY,
				],
				'contain' => [$userPermissionModelName]
			];

			// Bind the model the user is performing a find to our permissions model system.
			$model->bindModel(
				[
					'hasOne' => [
						$userPermissionModelName => [
							'foreignKey' => $foreignModelPrimaryKey
						],
						'GroupCategoryPermission' => [
							'foreignKey' => $foreignModelPrimaryKey
						],
						'Permission' => [
							'foreignKey' => false,
							'conditions' => ['Permission.id' => $userPermissionModelName . '.permission_id '],
							'type' => 'LEFT'
						]
					]
				], false);

			// Augment the request to add the fields we want to get
			if (!empty($queryData['fields'])) {
				if (!is_array($queryData['fields'])) {
					$queryData['fields'] = [$queryData['fields']];
				}
				$queryData['fields'] = array_merge($queryData['fields'], $permOptions['fields']);
			}

			// Augment the request to add the condition to filter the request
			if (empty($queryData['conditions'])) {
				$queryData['conditions'] = [];
			}
			if (!is_array($queryData['conditions'])) {
				$queryData['conditions'] = [$queryData['conditions']];
			}
			$queryData['conditions'] = array_merge($queryData['conditions'], $permOptions['conditions']);

			// Augment the request to add the model we want included in the request results.
			if (empty($queryData['contain'])) {
				$queryData['contain'] = [];
			}
			if (!is_array($queryData['contain'])) {
				$queryData['contain'] = [$queryData['contain']];
			}
			$queryData['contain'] = array_merge($queryData['contain'], $permOptions['contain']);
		}

		return $queryData;
	}

/**
 * After find callback. Can be used to modify any results returned by find.
 *
 * @param Model $model Model using this behavior
 * @param mixed $results The results of the find operation
 * @param bool $primary Whether this model is being queried directly (vs. being queried as an association)
 * @return mixed An array value will replace the value of $results - any other value will be ignored.
 */
	public function afterFind(Model $model, $results, $primary = false) {
		if (User::get('Role.name') == Role::USER || User::get('Role.name') == Role::GUEST || User::get('Role.name') == Role::ADMIN) {

			$model->unbindModel([
				'hasOne' => [
					'UserCategoryPermission',
					'Permission',
					'GroupCategoryPermission'
				]
			], false);
		}

		return $results;
	}

/**
 * afterSave is called after a model is saved.
 * The permissionnable after save method is used to automatically give to the user
 * the ADMIN right to the records he has just inserted
 *
 * @param Model $model Model using this behavior
 * @param bool $created True if this save created a new record
 * @param array $options Options passed from Model::save().
 * @throws ValidationException if validation rules failed for permission model
 * @throws Exception if no created_by field is availabled in the related model
 * @return bool
 */
	public function afterSave(Model $model, $created, $options = []) {
		if ($created) {
			$Permission = Common::getModel('Permission');
			$userId = null;

			// When a model is marked as permissionable.
			// A permission will be automatically created for each owner of that new instance.
			// The created_by field is so compulsory.
			if (!isset($model->data[$model->alias]['created_by'])) {
				throw new Exception('The permissionable behavior requires the created_by field to be filled');
			}
			$userId = $model->data[$model->alias]['created_by'];

			// make the creator administrator of the created instance
			$data = [
				'Permission' => [
					'aco' => $model->alias,
					'aco_foreign_key' => $model->id,
					'aro' => 'User',
					'aro_foreign_key' => $userId,
					'type' => PermissionType::OWNER
				]
			];
			// If the debug mode is enabled.
			// Generate a permission id based on the aco foreign key and the aro foreign key.
			// It will help us to retrieve permission for debugging or testing.
			if (Configure::read('debug') > 0) {
				$data['Permission']['id'] = Common::uuid('permission.id.' . $model->id . '-' . $userId);
			}

			$Permission->create();
			$Permission->set($data);
			if (!$Permission->validates()) {
				throw new ValidationException(__('Could not save permissions, validation failed'), $Permission->validationErrors);
			}

			$Permission->save($data);
		}
	}

/**
 * Get the permission to an instance for a given user.
 *
 * @param Model &$model The type of record.
 * @param string $id uuid of the record.
 * @param string|null $aroId The target user/group to get the permission for, by default the current user.
 * @param string $aroType The target aro model to get the permission for, by default the User.
 * @return Permission
 */
	public function getPermission(Model &$model, $id, $aroId = null, $aroType = 'User') {
		$aroId = !is_null($aroId) ? $aroId : User::get('id');
		$targetPermissionModelName = $aroType . $model->alias . 'Permission';
		$TargetPermissionModel = Common::getModel($targetPermissionModelName);

		$findOptions = [
			'fields' => [
				'permission_id',
				'permission_type'
			],
			'conditions' => [
				$targetPermissionModelName . '.' . strtolower($aroType) . '_id' => $aroId,
				$targetPermissionModelName . '.' . Inflector::underscore($model->alias) . '_id' => $id,
			],
			'contain' => [
				'Permission(id, type)'
			]
		];
		$result = $TargetPermissionModel->find('first', $findOptions);

		return $result;
	}

/**
 * Check a user is authorized to access a reccord
 *
 * @param Model &$model reference to the type of record.
 * @param string $id uuid of the record.
 * @param string $permissionType The permission type to verify.
 * @param null $aroId The target user/group to check the permission for, by default the current user.
 * @param string $aroType The target aro model to check the permission for, by default the User.
 * @return bool
 */
	public function isAuthorized(Model &$model, $id, $permissionType = PermissionType::READ, $aroId = null, $aroType = 'User') {
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
 * @param Model &$model reference to the type of record.
 * @param string|null $acoInstanceId uuid
 * @return array|null
 */
	public function getAuthorizedUsers(Model &$model, $acoInstanceId = null) {
		// Get aco key name.
		$acoKeyName = strtolower($model->alias) . '_id';

		// If instance id is not provided as parameter, we get it from the model.
		if (is_null($acoInstanceId)) {
			$acoInstanceId = $this->id;
		}

		// Build corresponding model.
		$model = Common::getModel("User{$model->alias}Permission");

		// Retrieve the list of users.
		$users = $model->find('all', [
			'conditions' => [
				$acoKeyName => $acoInstanceId,
				'permission_type <>' => null
			],
			'contain' => [
				'User' => [
					'fields' => [
						'User.id'
					]
				]
			]
		]);

		return $users;
	}
}

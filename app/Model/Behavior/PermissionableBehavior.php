<?php
/**
 * Permission Behavior
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

App::uses('Permission', 'Model');
App::uses('PermissionType', 'Model');

class PermissionableBehavior extends ModelBehavior {

/**
 * beforeFind callback.
 * Filter all the requests to return only the resources that the current users is authorized to access.
 *
 * @param Model $model Model using this behavior
 * @param array $queryData Data used to execute this query, i.e. conditions, order, etc.
 * @return bool|array False or null will abort the operation. You can return an array to replace the
 *   $query that will be eventually run.
 */
	public function beforeFind(Model $model, $queryData = []) {
		// Augment the request to return only acos users are authorized to access.
		if (User::get('Role.name') != Role::ROOT) {

			// Permissions are pre-calculated by a view functions of the acos/aros.
			// For the users and resources the permissions are provided by the model UserResourcePermission.
			$userPermissionModelName = 'User' . $model->alias . 'Permission';
			$foreignModelPrimaryKey = Inflector::underscore($model->alias) . '_id';

			// Bind the permissions models in order to check access.
			$model->bindModel([
				'hasOne' => [
					$userPermissionModelName => [
						'foreignKey' => $foreignModelPrimaryKey,
						'conditions' => [
							$userPermissionModelName . '.user_id' => User::get('id'),
							$userPermissionModelName . '.permission_type >=' => PermissionType::READ,
						]
					],
					'Permission' => [
						'foreignKey' => false,
						'conditions' => ['Permission.id = '. $userPermissionModelName . '.permission_id ']
					]
				]
			], false);

			// Return permission data.
			if (empty($queryData['contain'])) {
				$queryData['contain'] = [];
			}
			if (!is_array($queryData['contain'])) {
				$queryData['contain'] = [$queryData['contain']];
			}
			$contain = [
				$userPermissionModelName => [ 'fields' => [ $userPermissionModelName . '.*' ] ],
				'Permission' => [ 'fields' => [  'Permission.*' ] ],
			];
			$queryData['contain'] = array_merge($queryData['contain'], $contain);

			// Filter only acos the user is authorized to access.
			if (empty($queryData['conditions'])) {
				$queryData['conditions'] = [];
			}
			if (!is_array($queryData['conditions'])) {
				$queryData['conditions'] = [$queryData['conditions']];
			}
			$conditions = [
				'Permission.id <>' => null,
			];
			$queryData['conditions'] = array_merge($queryData['conditions'], $conditions);
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
					'Permission',
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
				// we need to specify created_by too since setting an ID counts as an edit in trackable behavior
				// and created_by will then not be set
				$data['Permission']['created_by'] = User::get('id');
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
		$UserResourcePermission = Common::getModel('UserResourcePermission');

		$findOptions = [
			'conditions' => [
				'UserResourcePermission.user_id' => $aroId,
				'UserResourcePermission.resource_id' => $id,
			],
			'contain' => [
				'Permission(id, type)'
			]
		];
		$result = $UserResourcePermission->find('first', $findOptions);

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
 * Find users who have the permission to at least read the resource
 *
 * @param Model &$model reference to the type of record.
 * @param string|null $acoInstanceId uuid
 * @param array (optional) $findOptions The users find options
 * @return array|null
 */
	public function findAuthorizedUsers(Model &$model, $acoInstanceId = null, $findOptions = array()) {
		$model = Common::getModel("UserResourcePermission");

		// If instance id is not provided as parameter, we get it from the model.
		if (is_null($acoInstanceId)) {
			$acoInstanceId = $this->id;
		}

		// acoInstanceId has to be a valid uuid.
		if (!Common::isUuid($acoInstanceId)) {
			throw new InvalidArgumentException('The acoInstanceId is invalid');
		}

		// If no find options given, return all users.
		if (empty($findOptions)) {
			$findOptions = $model->User->getFindOptions('User::index', User::get('Role.name'));
		}

		// Retrieve only users who have the permissions to read the resource.
		$findOptions['joins'][] = [
			'table' => 'users_resources_permissions',
			'alias' => 'UserResourcePermission',
			'type' => 'inner',
			'conditions' => [
				"UserResourcePermission.user_id = User.id
				AND UserResourcePermission.resource_id = '$acoInstanceId'"
			]
		];

		$users = $model->User->find('all', $findOptions);
		return $users;
	}
}

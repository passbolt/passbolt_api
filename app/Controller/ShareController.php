<?php
/**
 * Share Controller
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class ShareController extends AppController {

/**
 * @var array components used in this controller
 */
	public $components = [
		'EmailNotificator',
	];

/**
 * @var array models used in this controller
 */
	public $uses = [
		'Secret',
		'Resource',
		'Permission',
		'User',
		'Group'
	];

/**
 * Update permissions for the objects that have been modified or deleted.
 *
 * @param string $acoModelName aco model name
 * @param string $acoInstanceId aco instance uuid
 * @param array $permissions a collection of permission
 * @throws Exception
 * @return void
 */
	private function __updatePermissions($acoModelName, $acoInstanceId, $permissions) {
		// the aco instance instance is not allowed.
		$this->loadModel($acoModelName);
		$acoExist = $this->$acoModelName->exists($acoInstanceId);
		if (!$acoExist) {
			throw new Exception(__('The Resource id is invalid'));
		}

		// Check if a user is authorized.
		$isAuthorized = $this->$acoModelName->isAuthorized($acoInstanceId, PermissionType::OWNER);
		if (!$isAuthorized) {
			throw new Exception(__('Your are not allowed to add a permission to the %s', $acoModelName));
		}

		foreach ($permissions as $permission) {
			$deleteCase = isset($permission['Permission']['id']) && isset($permission['Permission']['delete']);
			$updateCase = isset($permission['Permission']['id']) && isset($permission['Permission']['type']);
			$saveCase = !isset($permission['Permission']['id']) && isset($permission['Permission']['aro_foreign_key']);
			$aroModelName = null;
			$aroInstanceId = null;

			// For all the cases apart from delete, we need to validate aro.
			if ($deleteCase || $updateCase) {
				// Validate Permission id is a uuid.
				if (!Common::isUuid($permission['Permission']['id'])) {
					throw new Exception(__('The permission with id %s is invalid', $permission['Permission']['id']));
				}

				// Validate Permission Id.
				$exist = $this->Permission->exists($permission['Permission']['id']);
				if (!$exist) {
					throw new Exception(__('The permission with id %s does not exist',
						$permission['Permission']['id']));
				}

				// Check that the permission belongs to the aco instance.
				$permissionBelongsToAcoInstance = $this->Permission->find('first', [
					'conditions' => [
						'Permission.id' => $permission['Permission']['id'],
						'Permission.aco_foreign_key' => $acoInstanceId
					]
				]);
				if (empty($permissionBelongsToAcoInstance)) {
					throw new Exception(__('Could not delete permission id %s', $permission['Permission']['id']));
				}

				// Everything ok, we process with saving the data.
				if ($deleteCase) {
					$del = $this->Permission->delete($permission['Permission']['id']);
					if (!$del) {
						throw new Exception(__('Could not delete permission id %s', $permission['Permission']['id']));
					}
				} elseif ($updateCase) {
					// Update.
					$this->Permission->id = $permission['Permission']['id'];
					$update = $this->Permission->saveField('type', $permission['Permission']['type'], true);
					if (!$update) {
						throw new Exception(__('Could not save permission id %s', $permission['Permission']['id']));
					}
				}
			} elseif ($saveCase) {
				$aroModelName = isset($permission['Permission']['aro']) ? $permission['Permission']['aro'] : 'User';
				$acoModelName = 'Resource';
				$aroInstanceId = isset($permission['Permission']['aro_foreign_key']) ? $permission['Permission']['aro_foreign_key'] : null;

				// check if the target ACO model is permissionable
				if (!$this->Permission->isValidAro($aroModelName)) {
					throw new Exception(__('The aro model %s is not permissionable', $aroModelName));
				}

				// no aco instance id given
				if (is_null($aroInstanceId)) {
					throw new Exception(__('The %s id is missing', $aroModelName));
				}

				// the aro instance id is invalid
				if (!Common::isUuid($aroInstanceId)) {
					throw new Exception(__('The %s id is invalid', $aroModelName));
				}

				// Check aro exists.
				$this->loadModel($aroModelName);
				$exists = $this->$aroModelName->exists($aroInstanceId);

				// If aro is a user, we make sure it is also active, and not deleted.
				if ($exists && $aroModelName == 'User') {
					$exists = $this->$aroModelName->find('first', ['conditions' => ['id' => $aroInstanceId, 'active' => 1, 'deleted' => 0]]);
				}
				// If aro is a group, we make sure it is not deleted.
				else if ($exists && $aroModelName == 'Group') {
					$exists = $this->$aroModelName->find('first', ['conditions' => ['id' => $aroInstanceId, 'deleted' => 0]]);
				}

				if (!$exists) {
					throw new Exception(__('The ARO instance %s for the model %s doesn\'t exist or the user is not allowed to access it',
						$aroInstanceId, $aroModelName));
				}

				// Make sure the same permission doesn't already exist.
				$p = $this->Permission->find('first', [
					'conditions' => [
						'aco' => $acoModelName,
						'aco_foreign_key' => $acoInstanceId,
						'aro' => $aroModelName,
						'aro_foreign_key' => $aroInstanceId
					]
				]);
				if ($p) {
					throw new Exception(__('The permission to be created already exists'));
				}

				// Everything clear, we save permission.
				$data = [
					'aco' => $acoModelName,
					'aco_foreign_key' => $acoInstanceId,
					'aro' => $aroModelName,
					'aro_foreign_key' => $aroInstanceId,
					'type' => $permission['Permission']['type'],
				];

				$this->Permission->set($data);
				$v = $this->Permission->validates();
				if (!$v) {
					throw new Exception(__('Could not validate model Permission during creation'));
				}
				$this->Permission->create();
				$s = $this->Permission->save($data, ['atomic' => false]);
				if (!$s) {
					throw new Exception(__('Could not save model Permission'));
				}
			}
		}
	}

/**
 * Process added secrets.
 *
 * When we make a share operation, if a new user is added to access an ACO, we need to add his secret.
 * this function takes care of processing the secrets provided in the data, and make sure we have everything we need.
 *
 * @param uuid $acoInstanceId the resource id
 * @param array $addedUsers an array of users uuid
 * @param array $secrets an array of secrets
 * @throws Exception
 * @return void
 */
	private function __processAddedSecrets($acoInstanceId, $addedUsers, $secrets) {
		// Add secrets for added users.
		if (count($addedUsers) != count($secrets)) {
			throw new Exception(__("The number of secrets provided doesn't match the %s users who have now access to the resources",
				count($addedUsers)));
		}

		foreach ($addedUsers as $userId) {
			$secretProvided = false;
			foreach ($secrets as $secret) {
				$secretProvided = $secret['Secret']['user_id'] == $userId
					&& $secret['Secret']['resource_id'] == $acoInstanceId;
				if ($secretProvided) {
					break;
				}
			}
			// If a user doesn't have its secret provided, we throw an exception.
			if (!$secretProvided) {
				throw new Exception(__("The secret for user id %s is not provided", $userId));
			}

			// Save secret.
			$data = [
				'user_id' => $userId,
				'resource_id' => $acoInstanceId,
				'data' => $secret['Secret']['data'],
			];
			// Validates data.
			$this->Secret->set($data);
			$v = $this->Secret->validates();
			if (!$v) {
				throw new Exception(__("Invalid secret provided for user %s and resource %s", $userId, $acoInstanceId));
			}

			// Save secret.
			$this->Secret->create();
			$s = $this->Secret->save($data);
			if (!$s) {
				throw new Exception(__("Could not save secret for user %s and resource %s", $userId, $acoInstanceId));
			}
		}
	}

/**
 * Process removed secrets. See similar function __processAddedSecrets().
 *
 * @param string $acoInstanceId uuid of the aco instance
 * @param array $removedUsers list of removed users
 * @throws Exception
 * @return void
 */
	private function __processRemovedSecrets($acoInstanceId, $removedUsers) {
		// If there are users that have been removed.
		if (!empty($removedUsers)) {
			// Delete Secrets for removed permissions.
			$del = $this->Secret->deleteAll(
				[
					'user_id' => $removedUsers,
					'resource_id' => $acoInstanceId,
				]
			);
			if (!$del) {
				throw new Exception(__("Could not delete secrets"));
			}
		}
	}

/**
 * Simulate entry point.
 *
 * @param string $acoModelName aco model name
 * @param string $acoInstanceId aco instance uuid
 * @throws Exception
 * @return void
 */
	public function simulate($acoModelName = '', $acoInstanceId = null) {
		// Get permissions from request.
		$permissions = isset($this->request->data['Permissions']) ? $this->request->data['Permissions'] : null;

		// Aco Model name should be capitalized
		$acoModelName = ucfirst($acoModelName);
		$AcoModel = Common::getModel($acoModelName);

		// check the HTTP request method
		if (!$this->request->is('post')) {
			return $this->Message->error(__('Invalid request method, should be POST'));
		}

		// check if the target ACO model is permissionable
		if (!$this->Permission->isValidAco($acoModelName)) {
			return $this->Message->error(__('The call to entry point with parameter %s is not allowed', $acoModelName));
		}

		// no aco instance id given
		if (is_null($acoInstanceId)) {
			return $this->Message->error(__('The %s id is missing', $acoModelName));
		}

		// the aco instance id is invalid.
		if (!Common::isUuid($acoInstanceId)) {
			return $this->Message->error(__('The %s id is invalid', $acoModelName));
		}

		// If permissions are not provided, call is useless.
		if (empty($permissions) || is_null($permissions)) {
			return $this->Message->error(__('No permissions were provided'));
		}

		// Retrieve authorized users before applying the changes.
		$authorizedUsers = $AcoModel->findAuthorizedUsers($acoInstanceId);

		$this->Permission->begin();
		try {
			$this->__updatePermissions($acoModelName, $acoInstanceId, $permissions);
		} catch (Exception $e) {
			$this->Permission->rollback();
			return $this->Message->error($e->getMessage());
		}

		// Retrieve the users after changes.
		$authorizedUsersAfterChanges = $AcoModel->findAuthorizedUsers($acoInstanceId);

		// Abort the modification made for the simulation.
		$this->Permission->rollback();

		// Extract user ids from array.
		$usersCurrent = Hash::extract($authorizedUsers, '{n}.User.id');
		$usersAfterChanges = Hash::extract($authorizedUsersAfterChanges, '{n}.User.id');
		// Users who have been added will show with the diff between before and after changes.
		$addedUsers = array_diff($usersAfterChanges, $usersCurrent);
		// Users who have been removed will show with the diff between before and after changes.
		$removedUsers = array_diff($usersCurrent, $usersAfterChanges);

		// Prepare output.
		$added = $this->User->find(
			'all',
			array_merge(
				['conditions' => ['User.id' => $addedUsers]],
				$this->User->getFindFields('User::edit')['fields']['User']
			));
		$removed = $this->User->find(
			'all',
			array_merge(
				['conditions' => ['User.id' => $removedUsers]],
				$this->User->getFindFields('User::edit')['fields']['User']
			));
		$data = [
			'changes' => ['added' => $added, 'removed' => $removed]
		];

		$this->set('data', $data);
		$this->Message->success(__('Simulate successful'));
	}

/**
 * Update entry point.
 *
 * @param string $acoModelName aco model name
 * @param string $acoInstanceId aco instance uuid
 * @return void
 */
	public function update($acoModelName = '', $acoInstanceId = null) {
		// Should be capitalized
		$acoModelName = ucfirst($acoModelName);
		// Get permissions from request.
		$permissions = isset($this->request->data['Permissions']) ?
			$this->request->data['Permissions'] : null;
		// Get secrets.
		$secrets = isset($this->request->data['Secrets']) ?
			$this->request->data['Secrets'] : null;

		// check the HTTP request method
		if (!$this->request->is('put')) {
			return $this->Message->error(__('Invalid request method, should be PUT'));
		}

		// check if the target ACO model is permissionable
		if (!$this->Permission->isValidAco($acoModelName)) {
			return $this->Message->error(__('The call to entry point with parameter %s is not allowed', $acoModelName));
		}

		// no aco instance id given
		if (is_null($acoInstanceId)) {
			return $this->Message->error(__('The %s id is missing', $acoModelName));
		}

		// the aco instance id is invalid.
		if (!Common::isUuid($acoInstanceId)) {
			return $this->Message->error(__('The %s id is invalid', $acoModelName));
		}

		// If permissions are not provided, call is useless.
		if (empty($permissions) || is_null($permissions)) {
			return $this->Message->error(__('No permissions were provided'));
		}

		// Get list of current permissions for the given ACO.
		$AcoModel = Common::getModel($acoModelName);
		$authorizedUsers = $AcoModel->findAuthorizedUsers($acoInstanceId);

		// Begin transaction.
		$this->Permission->begin();
		try {
			$this->__updatePermissions($acoModelName, $acoInstanceId, $permissions);
		} catch (Exception $e) {
			$this->Permission->rollback();
			return $this->Message->error($e->getMessage());
		}

		// Get new permissions after all changes.
		$AcoModel = Common::getModel($acoModelName);
		$authorizedUsersAfterChanges = $AcoModel->findAuthorizedUsers($acoInstanceId);

		// Extract user ids from array.
		$usersCurrent = Hash::extract($authorizedUsers, '{n}.User.id');
		$usersAfterChanges = Hash::extract($authorizedUsersAfterChanges, '{n}.User.id');
		// Users who have been added will show with the diff between simulated and current.
		$addedUsers = array_diff($usersAfterChanges, $usersCurrent);
		// Users who have been removed will show with the diff between current and simulated.
		$removedUsers = array_diff($usersCurrent, $usersAfterChanges);

		// Manage added users and removed users.
		try {
			$this->__processRemovedSecrets($acoInstanceId, $addedUsers);
			$this->__processAddedSecrets($acoInstanceId, $addedUsers, $secrets);
		} catch (Exception $e) {
			$this->Permission->rollback();
			return $this->Message->error($e->getMessage());
		}

		// Everything ok, we can commit.
		$this->Permission->commit();

		// Send an email notification.
		foreach ($addedUsers as $userId) {
			$this->EmailNotificator->passwordSharedNotification(
				$userId,
				[
					'resource_id' => $acoInstanceId,
					'sharer_id' => User::get('id'),
				]);
		}

		// Prepare output.
		$added = $this->User->find(
			'all',
			array_merge(
				['conditions' => ['User.id' => $addedUsers]],
				$this->User->getFindFields('User::edit')['fields']['User']
			));
		$removed = $this->User->find(
			'all',
			array_merge(
				['conditions' => ['User.id' => $removedUsers]],
				$this->User->getFindFields('User::edit')['fields']['User']
			));

		// Get new permissions.
		$updatedPermissions = [];
		$updatedAcoInstance = null;

		// Get the updated resource.
		$findResourceData = ['Resource.id' => $acoInstanceId];
		$findResourceOptions = $this->Resource->getFindOptions('view', User::get('Role.name'), $findResourceData);
		$updatedAcoInstance = $this->Resource->find('first', $findResourceOptions);

		// If the user has still access to the instance, get the permissions of the users who have access to the resource
		if (!empty($updatedAcoInstance)) {
			// Get the permissions of the users who have access to the resource
			$UserResourcePermission = Common::getModel('UserResourcePermission');
			$findPermissionData = [
				'UserResourcePermission' => [
					'resource_id' => $acoInstanceId
				]
			];
			$findPermissionOptions = $UserResourcePermission->getFindOptions('viewByResource', User::get('Role.name'), $findPermissionData);
			$updatedPermissions = $UserResourcePermission->find('all', $findPermissionOptions);

		} else {
			// the find one return an empty array if not found, but a null is more relevant.
			$updatedAcoInstance = null;
		}

		$this->set('data', [
			'Permissions' => $updatedPermissions,
			'changes' => ['added' => $added, 'removed' => $removed],
			'acoInstance' => $updatedAcoInstance
		]);
		$this->Message->success(__('Share operation successful'));
	}

/**
 * Search users who can be granted for a target aco instance
 *
 * @param string $model the aco model name
 * @param string $id the aco instance uuid
 * @return void
 */
	public function searchUsers($model = null, $id = null) {
		$data = [];
		$model = ucfirst($model);

		// check the HTTP request method
		if (!$this->request->is('get')) {
			$this->Message->error(__('Invalid request method, should be GET'));
			return;
		}

		// check if the target ACO model is permissionable
		if (!$this->Permission->isValidAco($model)) {
			$this->Message->error(__('The model %s is not permissionable', $model));
			return;
		}

		// the instance id is missing
		if (is_null($id)) {
			$this->Message->error(__('The %s id is missing', strtolower($model)));
			return;
		}

		// the instance id is invalid
		if (!Common::isUuid($id)) {
			$this->Message->error(__('The %s id is invalid', strtolower($model)));
			return;
		}

		// find the instance
		$resource = $this->Permission->$model->findById($id);
		if (empty($resource)) {
			$this->Message->error(__('The %s does not exist', strtolower($model)), ['code' => 404]);
		}

		// check if user is authorized to share the resource
		// the user can share a resource only if he is owner of this resource
		if (!$this->Permission->$model->isAuthorized($id, PermissionType::OWNER)) {
			$this->Message->error(__('You are not authorized to share this %s', strtolower($model)), ['code' => 403]);
			return;
		}

		// If the search should be filtered by keywords.
		if (isset($this->request->query['keywords'])) {
			$data['keywords'] = $this->request->query['keywords'];
			$data['filter']['keywords'] = $this->request->query['keywords'];
		}

		// Find all the users and all the groups who can receive a direct permission.
		$data['aco_foreign_key'] = $id;
		$data['aco'] = $model;
		$findUsersOptions = $this->Permission->User->getFindOptions('Share::searchUsers', User::get('Role.name'), $data);
		$users = $this->Permission->User->find('all', $findUsersOptions);
		$findGroupsOptions = $this->Permission->Group->getFindOptions('Share::searchUsers', User::get('Role.name'), $data);
		$groups = $this->Permission->Group->find('all', $findGroupsOptions);

		// Count the number of users for each group.
		foreach($groups as $i => $group) {
			$count = $this->Permission->Group->GroupUser->find('count', [
				'conditions' => ['GroupUser.group_id' => $group['Group']['id']]
			]);
			$groups[$i]['Group']['user_count'] = $count;
		}

		// Sort alphabetically returned users and groups.
		$returnValue = array_merge($users, $groups);
		usort($returnValue, function($first_aco, $second_aco){
			$first_aco_meaning_value = isset($first_aco['Profile']) ? $first_aco['Profile']['first_name'] : $first_aco['Group']['name'];
			$second_aco_meaning_value = isset($second_aco['Profile']) ? $second_aco['Profile']['first_name'] : $second_aco['Group']['name'];
			return $first_aco_meaning_value > $second_aco_meaning_value;
		});
		$this->set('data', $returnValue);
		$this->Message->success();
	}
}
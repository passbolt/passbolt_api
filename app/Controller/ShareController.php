<?php
/**
 * Share Controller
 *
 * @copyright     Copyright 2012 Passbolt.com
 * @license       http://www.passbolt.com/license
 * @package       app.Controller.ShareController
 * @since         version 2.12.7
 */
class ShareController extends AppController {

	// Components.
	public $components = array(
		'PermissionHelper',
		'EmailNotificator',
	);

	// Used models.
	public $uses = array(
		'Secret',
		'Resource',
		'Permission',
		'User',
	);


	/**
	 * Update permissions for the objects that have been modified or deleted.
	 *
	 * @param $acoModelName
	 * @param $acoInstanceId
	 * @param $permissions
	 *
	 * @throws Exception
	 */
	private function _updatePermissions($acoModelName, $acoInstanceId, $permissions) {
		// check if the target ACO model is permissionable
		if (!$this->Permission->isValidAco($acoModelName)) {
			throw new Exception(__('The aco model %s is not permissionable', $acoModelName));
		}

		// no aco instance id given
		if (is_null($acoInstanceId)) {
			throw new Exception(__('The %s id is missing', $acoModelName));
		}

		// the aco instance id is invalid.
		if (!Common::isUuid($acoInstanceId)) {
			throw new Exception(__('The %s id is invalid', $acoModelName));
		}

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
					throw new Exception(__('The permission with id %s does not exist', $permission['Permission']['id']));
				}

				// Everything ok, we process with saving the data.
				if ($deleteCase) {
					$del = $this->Permission->delete($permission['Permission']['id']);
					if (!$del) {
						throw new Exception(__('Could not delete permission id %s', $permission['Permission']['id']));
					}
				}
				elseif ($updateCase) {
					// Update.
					$this->Permission->id = $permission['Permission']['id'];
					$update = $this->Permission->saveField('type', $permission['Permission']['type'], true);
					if (!$update) {
						throw new Exception(__('Could not save permission id %s', $permission['Permission']['id']));
					}
				}
			}
			elseif ($saveCase) {
				$aroModelName = 'User';
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

				// the aco instance id is invalid
				if (!Common::isUuid($aroInstanceId)) {
					throw new Exception(__('The %s id is invalid', $aroModelName));
				}

				// Check aro exists.
				$this->loadModel($aroModelName);
				if (!$this->$aroModelName->exists($aroInstanceId)) {
					throw new Exception(__('The ARO instance %s for the model %s doesn\'t exist or the user is not allowed to access it', $aroInstanceId, $aroModelName));
				}

				// Make sure the same permission doesn't already exist.
				$p = $this->Permission->find('first', array(
						'conditions' => array(
							'aco' => $acoModelName,
							'aco_foreign_key' => $acoInstanceId,
							'aro' => $aroModelName,
							'aro_foreign_key' => $aroInstanceId
						)
					));
				if ($p) {
					throw new Exception(__('The permission to be created already exists'));
				}

				// Everything clear, we save permission.
				$data = array(
					'aco' => $acoModelName,
					'aco_foreign_key' => $acoInstanceId,
					'aro' => $aroModelName,
					'aro_foreign_key' => $aroInstanceId,
					'type' => $permission['Permission']['type'],
				);

				$this->Permission->set($data);
				$v = $this->Permission->validates();
				if (!$v) {
					throw new Exception(__('Could not validate model Permission during creation'));
				}
				$this->Permission->create();
				$s = $this->Permission->save($data, array('atomic' => false));
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
	 *
	 * @throws Exception
	 */
	private function _processAddedSecrets($acoInstanceId, $addedUsers, $secrets) {
		// Add secrets for added users.
		if (count($addedUsers) != count($secrets)) {
			throw new Exception(__("The number of secrets provided doesn't match the %s users who have now access to the resources", count($addedUsers)));
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
			$data = array(
				'user_id' => $userId,
				'resource_id' => $acoInstanceId,
				'data' => $secret['Secret']['data'],
			);
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
	 * Process removed secrets. See similar function _processAddedSecrets().
	 *
	 * @param uuid $acoInstanceId
	 * @param array $removedUsers
	 *
	 * @throws Exception
	 */
	private function _processRemovedSecrets($acoInstanceId, $removedUsers) {
		// If there are users that have been removed.
		if (!empty($removedUsers)) {
			// Delete Secrets for removed permissions.
			$del = $this->Secret->deleteAll(
				array(
					'user_id' => $removedUsers,
					'resource_id' => $acoInstanceId,
				)
			);
			if (!$del) {
				throw new Exception(__("Could not delete secrets"));
			}
		}
	}

	/**
	 * Simulate entry point.
	 *
	 * @param string $acoModelName
	 * @param uuid   $acoInstanceId
	 *
	 * @throws Exception
	 */
	public function simulate($acoModelName = '', $acoInstanceId = null) {
		// Should be capitalized
		$acoModelName = ucfirst($acoModelName);
		// Get permissions from request.
		$permissions = isset($this->request->data['Permissions']) ? $this->request->data['Permissions'] : null;

		$this->Permission->begin();
		try {
			$this->_updatePermissions($acoModelName, $acoInstanceId, $permissions);
		}
		catch (Exception $e) {
			$this->Permission->rollback();
			throw new Exception($e->getMessage());
		}
		$users = $this->PermissionHelper->findAcoUsers($acoModelName, $acoInstanceId);
		$permissions = $this->PermissionHelper->findAcoPermissions($acoModelName, $acoInstanceId);
		$this->Permission->rollback();

		$data = array(
			'UserResourcePermissions' => $users,
			'Permissions' => $permissions,
		);
		$this->set('data', $data);
		$this->Message->success(__('Simulate successful'));
	}

	/**
	 * Update entry point.
	 * @param string $acoModelName
	 * @param null   $acoInstanceId
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
			$this->Message->error(__('Invalid request method, should be PUT'));
			return;
		}

		// check if the target ACO model is permissionable
		if (!$this->Permission->isValidAco($acoModelName)) {
			$this->Message->error(__('The call to entry point with parameter %s is not allowed', $acoModelName));
			return;
		}

		// If permissions are not provided, call is useless.
		if (empty($permissions) || is_null($permissions)) {
			$this->Message->error(__('No permissions were provided'));
			return;
		}

		// Get list of current permissions for the given ACO.
		$permsCurrent = $this->PermissionHelper->findAcoUsers($acoModelName, $acoInstanceId);

		// Begin transaction.
		$this->Permission->begin();
		try {
			$this->_updatePermissions($acoModelName, $acoInstanceId, $permissions);
		}
		catch (Exception $e) {
			$this->Permission->rollback();
			$this->Message->error($e->getMessage());
			return;
		}

		// Get new permissions after all changes.
		$permsAfterChanges = $this->PermissionHelper->findAcoUsers($acoModelName, $acoInstanceId);
		// Extract user ids from array.
		$usersCurrent = Hash::extract($permsCurrent, '{n}.User.id');
		$usersAfterChanges = Hash::extract($permsAfterChanges, '{n}.User.id');
		// Users who have been added will show with the diff between simulated and current.
		$addedUsers = array_diff($usersAfterChanges, $usersCurrent);
		// Users who have been removed will show with the diff between current and simulated.
		$removedUsers = array_diff($usersCurrent, $usersAfterChanges);

		// Manage added users and removed users.
		try {
			$this->_processRemovedSecrets($acoInstanceId, $addedUsers);
			$this->_processAddedSecrets($acoInstanceId, $addedUsers, $secrets);
		}
		catch (Exception $e) {
			$this->Permission->rollback();
			$this->Message->error($e->getMessage());
			return;
		}

		// Everything ok, we can commit.
		$this->Permission->commit();

		// Send an email notification.
		foreach ($addedUsers as $userId) {
			$this->EmailNotificator->passwordSharedNotification(
				$userId,
				array(
					'resource_id' => $acoInstanceId,
					'sharer_id' => User::get('id'),
				));
		}

		// Prepare output.
		$added = $this->User->find(
			'all',
			array_merge(
				array('conditions' => array('User.id' => $addedUsers)),
				$this->User->getFindFields('User::edit')['fields']['User']
			));
		$removed = $this->User->find(
			'all',
			array_merge(
				array('conditions' => array('User.id' => $removedUsers)),
				$this->User->getFindFields('User::edit')['fields']['User']
			));

		// Get new permissions.
		$perms = $this->PermissionHelper->findAcoPermissions($acoModelName, $acoInstanceId);
		$this->set('data', array('Permissions' => $perms, 'changes' => array('added' => $added, 'removed' => $removed)));
		$this->Message->success(__('Share operation successful'));
	}

/**
 * Search users who can be granted for a target aco instance
 * @param null $id The aco model to search users for
 * @param null $id The aco instance to search users for
 */
	public function searchUsers($model = null, $id = null) {
		$data = array();
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
			$this->Message->error(__('The %s does not exist', strtolower($model)), array('code' => 404));
		}

		// check if user is authorized to share the resource
		// the user can share a resource only if he is owner of this resource
		if (!$this->Permission->$model->isAuthorized($id, PermissionType::OWNER)) {
			$this->Message->error(__('You are not authorized to share this %s', strtolower($model)), array('code' => 403));
			return;
		}

		// If the search should be filtered by keywords.
		if (isset($this->request->query['keywords'])) {
			$data['keywords'] = $this->request->query['keywords'];
		}

		// Find all the users who can receive a direct permission.
		$data['aco_foreign_key'] = $id;
		$data['aco'] = $model;
		$o = $this->Permission->User->getFindOptions('Share::searchUsers', User::get('Role.name'), $data);
		$returnVal = $this->Permission->User->find('all', $o);

		$this->set('data', $returnVal);
		$this->Message->success();
	}
}
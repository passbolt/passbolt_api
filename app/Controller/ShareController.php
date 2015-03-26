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

	public $components = array(
		'PermissionHelper',
	);

	public $uses = array(
		'Secret',
		'Resource',
		'Permission',
	);

	/**
	 * Update permissions for the objects that have been modified or deleted.
	 *
	 * @param string $acoModelName
	 * @param $acoInstanceId
	 * @param $permissions
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
		$isAuthorized = $this->$acoModelName->isAuthorized($acoInstanceId, PermissionType::ADMIN);
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
				$s = $this->Permission->save($data);
				if (!$s) {
					throw new Exception(__('Could not save model Permission'));
				}
			}
		}
	}

	private function _simulateUpdate($acoModelName = '', $acoInstanceId = null, $permissions) {
		$this->Permission->begin();

		try {
			$this->_updatePermissions($acoModelName, $acoInstanceId, $permissions);
		}
		catch (Exception $e) {
			$this->Permission->rollback();
			throw new Exception($e->getMessage());
		}
		$users = $this->PermissionHelper->findAcoUsers($acoModelName, $acoInstanceId);
		$this->Permission->rollback();
		return $users;
	}

	/**
	 * Simulation entry point.
	 * @param string $acoModelName
	 * @param null   $acoInstanceId
	 */
	public function simulate($acoModelName = '', $acoInstanceId = null) {
		// Should be capitalized
		$acoModelName = ucfirst($acoModelName);
		// Get permissions from request.
		$permissions = isset($this->request->data['Permissions']) ? $this->request->data['Permissions'] : null;

		$res = $this->_simulateUpdate($acoModelName, $acoInstanceId, $permissions);

		return $res;
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

		// TODO : remove the simulate update and do the diff sequentially.
		// Get list of current permissions for the given ACO.
		$permsCurrent = $this->PermissionHelper->findAcoUsers($acoModelName, $acoInstanceId);
		try {
			// Get permissions with simulation, so we can get the diff of the users, and create the missing keys.
			$permsSimulated = $this->_simulateUpdate($acoModelName, $acoInstanceId, $permissions);
		}
		catch (Exception $e) {
			$this->Message->error($e->getMessage());
			return;
		}

		// Extract user ids from array.
		$usersCurrent = Hash::extract($permsCurrent, '{n}.User.id');
		$usersSimulated = Hash::extract($permsSimulated, '{n}.User.id');
		// Users who have been added will show with the diff between simulated and current.
		$addedUsers = array_diff($usersSimulated, $usersCurrent);
		// Users who have been removed will show with the diff between current and simulated.
		$removedUsers = array_diff($usersCurrent, $usersSimulated);

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

		// If there are users that have been removed.
		if (!empty($removedUsers)) {
			// Delete Secrets for removed permissions.
			$this->Secret->deleteAll(
				array(
					'user_id' => $removedUsers,
					'resource_id' => $acoInstanceId,
				)
			);
		}

		// Add secrets for added users.
		if (count($addedUsers) != count($secrets)) {
			$this->Permission->rollback();
			$this->Message->error(__("The number of secrets provided doesn't match the %s users who have now access to the resources", count($addedUsers)));
			return;
		}
		// TODO : check that each user has its item entered.
		foreach ($addedUsers as $userId) {
			$userSecret = null;
			foreach($secrets as $secret) {
				$secretProvided = $secret['Secret']['user_id'] == $userId
					&& $secret['Secret']['resource_id'] == $acoInstanceId;
				if (!$secretProvided) {
					$this->Permission->rollback();
					$this->Message->error(__("The secret for user id %s is not provided", $userId));
					return;
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
					$this->Permission->rollback();
					$this->Message->error(__("Invalid secret provided for user %s and resource %s", $userId, $acoInstanceId));
					return;
				}

				// Save secret.
				$this->Secret->create();
				$s = $this->Secret->save($data);
				if (!$s) {
					$this->Permission->rollback();
					$this->Message->error(__("Could not save secret for user %s and resource %s", $userId, $acoInstanceId));
					return;
				}
			}
		}
		// Everything ok, we can commit.
		$this->Permission->commit();

		// Manage email alerts.
		// TODO :

		$this->Message->success();
	}
}
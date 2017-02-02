<?php
/**
 * Permissions controller
 * This file will define how permissions are managed
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('Permission', 'Model');

class PermissionsController extends AppController {

/**
 * Add Aco Permissions
 *
 * @param string $acoModelName aco model name
 * @param string $acoInstanceId aco instance uuid
 * @param string $aroModelName aro model name
 * @param string $aroInstanceId aro instance uuid
 * @param string $permission permission type
 * @return void|bool void if error, true if save was a success
 */
	private function __addAcoPermissions($acoModelName = '', $acoInstanceId = null, $aroModelName = '', $aroInstanceId = null, $permission = null) {
		// The given permission type
		$permissionType = isset($permission) ? $permission : null;

		// check if the target ACO model is permissionable
		if (!$this->Permission->isValidAco($acoModelName)) {
			$this->Message->error(__('The model %s is not permissionable', $acoModelName));
			return false;
		}

		// no aco instance id given
		if (is_null($acoInstanceId)) {
			$this->Message->error(__('The %s id is missing', $acoModelName));
			return false;
		}

		// the aco instance id is invalid
		if (!Common::isUuid($acoInstanceId)) {
			$this->Message->error(__('The %s id is invalid', $acoModelName));
			return false;
		}

		// the aco instance instance does not exist
		$this->loadModel($acoModelName);
		if (!$this->$acoModelName->isAuthorized($acoInstanceId, PermissionType::OWNER)) {
			$this->Message->error(__('Your are not allowed to add a permission to the %s', $acoModelName),
				['code' => 403]);
			return false;
		}

		// not allowed aro model
		if (is_null($aroModelName)) {
			$this->Message->error(__('The target ARO model is not allowed'));
			return false;
		}

		// the ARO instance id is invalid
		if (!Common::isUuid($aroInstanceId)) {
			$this->Message->error(__('The id %s is invalid', $aroInstanceId));
			return false;
		}

		// the ARO instance does not exist
		$this->loadModel($aroModelName);
		if (!$this->$aroModelName->exists($aroInstanceId)) {
			$this->Message->error(__('The ARO instance %s for the model %s doesn\'t exist or the user is not allowed to access it',
				$aroInstanceId, $aroModelName));
			return false;
		}

		// no permission type given
		if (is_null($permissionType)) {
			$this->Message->error(__('No permission type given'));
			return false;
		}

		// the permission type is invalid
		if (!$this->Permission->PermissionType->isValidSerial($permissionType)) {
			$this->Message->error(__('The given permission type is not valid'));
			return false;
		}

		// Check that the permission if a permission already exists
		if (!$this->Permission->isUniquePermission($acoModelName, $acoInstanceId, $aroModelName, $aroInstanceId, $permissionType)) {
			$this->Message->error(__('A direct permission already exists'));
			return false;
		}

		// add the new permission
		$data = [
			'Permission' => [
				'aco' => $acoModelName,
				'aco_foreign_key' => $acoInstanceId,
				'aro' => $aroModelName,
				'aro_foreign_key' => $aroInstanceId,
				'type' => $permissionType
			]
		];
		$this->Permission->create();
		$this->Permission->set($data);
		if (!$this->Permission->validates()) {
			$this->Message->error($this->Permission->validationErrors);
			return false;
		}

		$ret = $this->Permission->save($data, ['atomic' => false]);

		return $ret;
	}

/**
 * Add permission to a target instance of a given model
 *
 * @param string $acoModelName the model of the target aco model instance
 * @param string $acoInstanceId the uuid of the target aco model instance
 * @return array
 */
	public function addAcoPermissions($acoModelName = '', $acoInstanceId = null) {
		// Should be capitalized
		$acoModelName = ucfirst($acoModelName);
		// The target ARO Model name to use
		$aroModelName = null;
		// The ARO instance id.
		$aroInstanceId = null;
		// The given permission type
		$permissionType = isset($this->request->data['Permission']['type']) ? $this->request->data['Permission']['type'] : null;

		// check the HTTP request method
		if (!$this->request->is('post')) {
			$this->Message->error(__('Invalid request method, should be POST'));
			return;
		}

		// Treat the posted data
		// Get the target ARO model and instance id
		foreach ($this->request->data as $key => $val) {
			// if the current data key is an allowed ARO model
			if ($this->Permission->isValidAro($key)) {
				$aroModelName = $key;
				if (isset($val['id'])) {
					$aroInstanceId = $val['id'];
				}
				break;
			}
		}

		$save = $this->__addAcoPermissions($acoModelName, $acoInstanceId, $aroModelName, $aroInstanceId,
			$permissionType);
		if (!$save) {
			$this->Message->error(__('Could not save the permission'));
			return;
		}

		// Get back the permission to return to the client
		$viewName = $aroModelName . $acoModelName . 'Permission'; // ex: UserResourcePermission
		$viewCase = 'viewBy' . $acoModelName; // ex: viewByCategory
		$foreignKey = Inflector::underscore($acoModelName) . '_id'; // ex: resource_id
		$this->loadModel($viewName);
		$findData = [
			$viewName => [ // UserResourcePermission
				$foreignKey => $acoInstanceId // category_id = $acoInstanceId
			]
		];
		$findOptions = $this->$viewName->getFindOptions($viewCase, User::get('Role.name'), $findData);
		$this->set('data', $this->$viewName->find('first', $findOptions));
		$this->Message->success(__('The permission was successfully added'));
	}

/**
 * View applied permissions on an instance
 *
 * @param string $acoModelName the model of the target aco model instance
 * @param string $acoInstanceId the uuid of the target aco model instance
 * @return array
 */
	public function viewAcoPermissions($acoModelName = '', $acoInstanceId = null) {
		$returnValue = [];

		// check the HTTP request method
		if (!$this->request->is('get')) {
			$this->Message->error(__('Invalid request method, should be GET'));
			return;
		}

		// check if the target ACO model is permissionable
		if (!$this->Permission->isValidAco($acoModelName)) {
			$this->Message->error(__('The model %s is not permissionable', $acoModelName));
			return;
		}

		// no aco instance id given
		if (is_null($acoInstanceId)) {
			$this->Message->error(__('The %s id is missing', $acoModelName));
			return;
		}

		// the aco instance id is invalid
		if (!Common::isUuid($acoInstanceId)) {
			$this->Message->error(__('The %s id is invalid', $acoModelName));
			return;
		}

		// the aco instance exists and the user is allowed to access it
		$this->loadModel($acoModelName);
		$acoInstance = $this->$acoModelName->findById($acoInstanceId);
		if (empty($acoInstance)) {
			$this->Message->error(__('The %s does not exist', $acoModelName));
			return;
		}

		// Get the list of permissions.
		$findData = [
			'Permission' => [
				'aco' => $acoModelName,
				'aco_foreign_key' => $acoInstanceId
			]
		];
		$findOptions = $this->Permission->getFindOptions('viewByAco', User::get('Role.name'), $findData);
		$returnValue = $this->Permission->find('all', $findOptions);

		// Return data.
		$this->Message->success();
		$this->set('data', $returnValue);
	}

/**
 * Edit a permission
 *
 * @param string $id the uuid of the target permission to edit
 * @return void
 */
	public function edit($id = null) {
		// The permission to edit
		$permission = null;
		// The permission aco model name
		$acoModelName = null;
		// The permission aco instance id
		$acoInstanceId = null;
		// The permission aro model name
		$aroModelName = null;
		// The permission arp instance id
		$aroInstanceId = null;

		// check the HTTP request method
		if (!$this->request->is('put')) {
			$this->Message->error(__('Invalid request method, should be PUT'));
			return;
		}

		// the permission id is missing
		if (is_null($id)) {
			$this->Message->error(__('The permission id is missing'));
			return;
		}

		// the permission id is invalid
		if (!Common::isUuid($id)) {
			$this->Message->error(__('The permission id is invalid'));
			return;
		}

		// find the permission
		$permission = $this->Permission->findById($id);
		if (empty($permission)) {
			$this->Message->error(__('The permission does not exist'), ['code' => 404]);
		}
		$acoModelName = $permission['Permission']['aco'];
		$acoInstanceId = $permission['Permission']['aco_foreign_key'];
		$aroModelName = $permission['Permission']['aro'];
		$aroInstanceId = $permission['Permission']['aro_foreign_key'];

		// check the user has ADMIN right on the aco foreign instance
		$this->loadModel($acoModelName);
		if (!$this->$acoModelName->isAuthorized($acoInstanceId, PermissionType::OWNER)) {
			$this->Message->error(__('You are not allowed to edit this permission'));
		}

		// Treat the posted data
		$pushData = $this->request->data;
		$pushData['Permission']['id'] = $id;
		$this->Permission->create();
		$this->Permission->setValidationRules('edit');
		$this->Permission->set($pushData);

		// check if the data is valid
		if (!$this->Permission->validates()) {
			$this->Message->error(__('Unable to validate the pushed data'));
			return;
		}

		// try to save
		$fields = $this->Permission->getFindFields('edit', User::get('Role.name'));
		$permission = $this->Permission->save($pushData, true, $fields['fields']);
		if ($permission === false) {
			$this->Message->error(__('The permission could not be updated'));
			return;
		}

		// case used by find options functions
		// this view case has to be defined in model which represent the views
		$viewCase = 'viewBy' . $acoModelName;
		// the permission view to attack
		$viewName = $aroModelName . $acoModelName . 'Permission';
		$ModelView = ClassRegistry::init($viewName);
		$acoForeignKey = strtolower($acoModelName) . '_id';
		$aroForeignKey = strtolower($aroModelName) . '_id';
		$findData = [
			$viewName => [
				$acoForeignKey => $acoInstanceId,
				$aroForeignKey => $aroInstanceId
			]
		];
		$findOptions = $ModelView->getFindOptions($viewCase, User::get('Role.name'), $findData);
		$result = $ModelView->find('first', $findOptions);

		// Result can be null, if the user change his right to deny by instance

		$this->Message->success();
		$this->set('data', $result);
	}

/**
 * Delete a permission
 *
 * @param string $id the uuid of the target permission to edit
 * @return array
 */
	public function delete($id = null) {
		// The permission to edit
		$permission = null;
		// The permission aco model name
		$acoModelName = null;
		// The permission aco instance id
		$acoInstanceId = null;
		// The permission aro model name
		$aroModelName = null;
		// The permission arp instance id
		$aroInstanceId = null;

		// check the HTTP request method
		if (!$this->request->is('delete')) {
			$this->Message->error(__('Invalid request method, should be DELETE'));
			return;
		}

		// the permission id is missing
		if (is_null($id)) {
			$this->Message->error(__('The permission id is missing'));
			return;
		}

		// the instance id is invalid
		if (!Common::isUuid($id)) {
			$this->Message->error(__('The permission id is invalid'));
			return;
		}

		// find the permission
		$permission = $this->Permission->findById($id);
		if (empty($permission)) {
			$this->Message->error(__('The permission does not exist'), ['code' => 404]);
		}
		$acoModelName = $permission['Permission']['aco'];
		$acoForeignKey = $permission['Permission']['aco_foreign_key'];

		// check the user has ADMIN right on the aco foreign instance
		$this->loadModel($acoModelName);
		if (!$this->$acoModelName->isAuthorized($acoForeignKey, PermissionType::OWNER)) {
			$this->Message->error(__('You are not allowed to delete this permission'));
		}

		// Delete the target permission
		$this->Permission->delete($id);
		$this->Message->success(__('The permission was successfully deleted'));
	}
}

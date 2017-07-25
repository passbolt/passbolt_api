<?php
/**
 * Permissions controller
 * This file will define how permissions are managed
 *
 * @copyright (c) 2015-2016 Bolt Softwares Pvt Ltd
 * 				  2017-present Passbolt SARL
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
 * @return void
 */
	private function __addAcoPermissions($acoModelName = '', $acoInstanceId = null, $aroModelName = '', $aroInstanceId = null, $permission = null) {
		// The given permission type
		$permissionType = isset($permission) ? $permission : null;

		// check if the target ACO model is permissionable
		if (!$this->Permission->isValidAco($acoModelName)) {
			throw new BadRequestException(__('The model %s does not support permissions.', $acoModelName));
		}
		// no aco instance id given
		if (is_null($acoInstanceId)) {
			throw new BadRequestException(__('The id is missing for model %s.', $acoModelName));
		}
		// the aco instance id is invalid
		if (!Common::isUuid($acoInstanceId)) {
			throw new BadRequestException(__('The id is not valid for model %s.', $acoModelName));
		}
		// the aco instance instance does not exist
		$this->loadModel($acoModelName);
		if (!$this->{$acoModelName}->isAuthorized($acoInstanceId, PermissionType::OWNER)) {
			throw new ForbiddenException(__('Your are not allowed to add a permission to the %s.', $acoModelName));
		}
		// not allowed aro model
		if (is_null($aroModelName)) {
			throw new BadRequestException(__('The target ARO model is not allowed.'));
		}
		// the ARO instance id is invalid
		if (!Common::isUuid($aroInstanceId)) {
			throw new BadRequestException(__('The id %s is not valid.', $aroInstanceId));
		}
		// the ARO instance does not exist
		$this->loadModel($aroModelName);
		if (!$this->{$aroModelName}->exists($aroInstanceId)) {
			$msg  = __('The ARO instance %s for the model %s does not exist or the user is not allowed to access it.', $aroInstanceId, $aroModelName);
			throw new BadRequestException($msg);
		}
		// no permission type given
		if (is_null($permissionType)) {
			throw new BadRequestException(__('No permission type given.'));
		}
		// the permission type is invalid
		if (!$this->Permission->PermissionType->isValidSerial($permissionType)) {
			throw new BadRequestException(__('The given permission type is not valid.'));
		}
		// Check that the permission if a permission already exists
		if (!$this->Permission->isUniquePermission($acoModelName, $acoInstanceId, $aroModelName, $aroInstanceId, $permissionType)) {
			throw new BadRequestException(__('A direct permission already exists.'));
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
			throw new ValidationException(__('The permission data are not valid.'));
		}
		$result = $this->Permission->save($data, ['atomic' => false]);
		if(!$result) {
			throw new InternalErrorException(__('Could not save the permission.'));
		}
	}

/**
 * Add permission to a target instance of a given model
 *
 * @param string $acoModelName the model of the target aco model instance
 * @param string $acoInstanceId the uuid of the target aco model instance
 * @return array
 */
	public function addAcoPermissions($acoModelName = '', $acoInstanceId = null) {
		$acoModelName = ucfirst($acoModelName); // Should be capitalized
		$aroModelName = null; // The target ARO Model name to use
		$aroInstanceId = null; // The ARO instance id.
		$permissionType = null; // The given permission type

		if (isset($this->request->data['Permission']['type'])) {
			$permissionType = $this->request->data['Permission']['type'];
		}

		// check the HTTP request method
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException(__('Invalid request method, should be POST'));
		}
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

		// Try to add the permission
		$this->__addAcoPermissions($acoModelName, $acoInstanceId, $aroModelName, $aroInstanceId, $permissionType);

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
		$findOptions = $this->{$viewName}->getFindOptions($viewCase, User::get('Role.name'), $findData);
		$this->set('data', $this->{$viewName}->find('first', $findOptions));
		$this->Message->success(__('The permission was successfully added'));
	}

/**
 * View permissions applied to an aco
 *
 * @param string $acoModelName the model of the target aco model instance
 * @param string $acoInstanceId the uuid of the target aco model instance
 * @return array
 */
	public function viewAcoPermissions($acoModelName = '', $acoInstanceId = null) {
		// check the request sanity
		if (!$this->request->is('get')) {
			throw new MethodNotAllowedException(__('Invalid request method, should be GET'));
		}
		if (!$this->Permission->isValidAco($acoModelName)) {
			throw new BadRequestException(__('The model %s does not support permissions.', $acoModelName));
		}
		if (is_null($acoInstanceId)) {
			throw new BadRequestException(__('The id is missing for model %s.', $acoModelName));
		}
		if (!Common::isUuid($acoInstanceId)) {
			throw new BadRequestException(__('The id is not valid for model %s.', $acoModelName));
		}

		// the aco instance exists and the user is allowed to access it
		$this->loadModel($acoModelName);
		$acoInstance = $this->$acoModelName->findById($acoInstanceId);
		if (empty($acoInstance)) {
			throw new BadRequestException(__('The model %s does not exist.', $acoModelName));
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
		$this->set('data', $returnValue);
		$this->Message->success();
	}

/**
 * Edit a permission
 *
 * @param string $id the uuid of the target permission to edit
 * @return void
 */
	public function edit($id = null) {
		$permission = null; // The permission to edit
		$acoModelName = null; // The permission aco model name
		$acoInstanceId = null; // The permission aco instance id
		$aroModelName = null; // The permission aro model name
		$aroInstanceId = null; // The permission arp instance id

		// check the HTTP request method
		if (!$this->request->is('put')) {
			throw new MethodNotAllowedException(__('Invalid request method, should be PUT.'));
		}
		if (is_null($id)) {
			throw new BadRequestException(__('The permission id is missing.'));
		}
		if (!Common::isUuid($id)) {
			throw new BadRequestException(__('The permission id is not valid.'));
		}

		// find the permission
		$permission = $this->Permission->findById($id);
		if (empty($permission)) {
			throw new NotFoundException(__('The permission does not exist.'));
		}
		$acoModelName = $permission['Permission']['aco'];
		$acoInstanceId = $permission['Permission']['aco_foreign_key'];
		$aroModelName = $permission['Permission']['aro'];
		$aroInstanceId = $permission['Permission']['aro_foreign_key'];

		// check the user has ADMIN right on the aco foreign instance
		$this->loadModel($acoModelName);
		if (!$this->{$acoModelName}->isAuthorized($acoInstanceId, PermissionType::OWNER)) {
			throw new ForbiddenException(__('You are not allowed to edit this permission.'));
		}

		// Treat the posted data
		$pushData = $this->request->data;
		$pushData['Permission']['id'] = $id;
		$this->Permission->create();
		$this->Permission->setValidationRules('edit');
		$this->Permission->set($pushData);

		// validate and try to save
		if (!$this->Permission->validates()) {
			throw new BadRequestException(__('Unable to validate the permission data.'));
		}
		$fields = $this->Permission->getFindFields('edit', User::get('Role.name'));
		$permission = $this->Permission->save($pushData, true, $fields['fields']);
		if ($permission === false) {
			throw new InternalErrorException(__('The permission could not be updated'));
		}

		// case used by find options functions
		// this view case has to be defined in model which represent the views
		$viewCase = 'viewBy' . $acoModelName;
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
		$this->set('data', $result);
		$this->Message->success();
	}

/**
 * Delete a permission
 *
 * @param string $id the uuid of the target permission to edit
 * @return array
 */
	public function delete($id = null) {
		$permission = null; // The permission to edit
		$acoModelName = null; // The permission aco model name=
		$acoInstanceId = null; // The permission aco instance id
		$aroModelName = null; // The permission aro model name
		$aroInstanceId = null; // The permission arp instance id

		// check the request sanity
		if (!$this->request->is('delete')) {
			throw new MethodNotAllowedException(__('Invalid request method, should be DELETE.'));
		}
		if (is_null($id)) {
			throw new BadRequestException(__('The permission id is missing.'));
		}
		if (!Common::isUuid($id)) {
			throw new BadRequestException(__('The permission id is not valid.'));
		}

		// find the permission
		$permission = $this->Permission->findById($id);
		if (empty($permission)) {
			throw new NotFoundException(__('The permission does not exist.'));
		}
		$acoModelName = $permission['Permission']['aco'];
		$acoForeignKey = $permission['Permission']['aco_foreign_key'];

		// check the user has ADMIN right on the aco foreign instance
		$this->loadModel($acoModelName);
		if (!$this->$acoModelName->isAuthorized($acoForeignKey, PermissionType::OWNER)) {
			throw new ForbiddenException(__('You are not allowed to delete this permission.'));
		}

		// Delete the target permission
		$this->Permission->delete($id);
		$this->Message->success(__('The permission was successfully deleted.'));
	}
}

<?php
/**
 * Groups Controller
 *
 * @copyright (c) 2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::import('Model', 'PermissionType');

class GroupsController extends AppController {

/**
 * @var array list of supported components
 */
	public $components = [
		'QueryString',
		'EmailNotificator',
	];

/**
 * Group Index
 *
 * @throws MethodNotAllowedException if the http request method is not GET
 * @return void
 */
	public function index() {
		// Check request sanity
		if (!$this->request->is('get')) {
			throw new MethodNotAllowedException(__('Invalid request method, should be GET.'));
		}

		// Extract parameters from query string
		$allowedQueryItems = [
			'filter' => ['has-users', 'has-managers', 'modified-after'],
			'contain' => ['user', 'resource', 'modifier'],
			'order' => $this->Group->getFindAllowedOrder('GroupsController::index'),
		];
		$params = $this->QueryString->get($allowedQueryItems);

		// Get find options and get all groups.
		$o = $this->Group->getFindOptions('Group::index', User::get('Role.name'), $params);
		$groups = $this->Group->find('all', $o);

		// Send response.
		$this->set('data', $groups);
		$this->Message->success();
	}

/**
 * Group View
 *
 * @param string $id the uuid of the group
 * @throws MethodNotAllowedException if the http request method is not GET
 * @throws BadRequestException if the group id is missing or not a valid uuid
 * @throws NotFoundException if the group does not exist or has been deleted
 * @return void
 */
	public function view($id = null) {
		// Check request sanity
		if (!$this->request->is('get')) {
			throw new MethodNotAllowedException(__('Invalid request method, should be GET.'));
		}
		if (!isset($id)) {
			throw new BadRequestException(__('The group id is missing.'));
		}
		if (!Common::isUuid($id)) {
			throw new BadRequestException(__('The group id is not valid.'));
		}
		if (!$this->Group->exists($id)) {
			throw new NotFoundException(__('The group does not exist.'));
		}
		if ($this->Group->isSoftDeleted($id)) {
			throw new NotFoundException(__('The group does not exist.'));
		}

		// Build response taking query items into account
		$allowedQueryItems = [
			'contain' => ['user', 'resource', 'modifier'],
		];
		$data = $this->QueryString->get($allowedQueryItems);
		$data['Group.id'] = $id;
		$o = $this->Group->getFindOptions('Group::view', User::get('Role.name'), $data);
		$group = $this->Group->find('first', $o);

		// Send response.
		$this->set('data', $group);
		$this->Message->success();
	}

/**
 * Add a group
 *
 * @throws MethodNotAllowedException if the http request method is not GET
 * @throws ForbiddenException if the user role is not admin
 * @throws BadRequestException if no group manager is provided
 * @throws BadRequestException if the group data cannot be validated
 * @throws InternalErrorException if the group data could not be saved
 */
	public function add() {
		// check the request sanity
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException(__('Invalid request method, should be GET.'));
		}
		if (User::get('Role.name') != Role::ADMIN) {
			throw new ForbiddenException(__('You are not authorized to access this endpoint.'));
		}

		// Validate group users.
		$postData = $this->request->data;
		$groupManagers = Hash::extract($postData['GroupUsers'], '{n}.GroupUser.is_admin');
		if (!in_array('1', $groupManagers)) {
			throw new BadRequestException(__('A group manager must be provided.'));
		}

		// Begin transaction & try to create group
		$this->Group->begin();
		$this->Group->create();
		$this->Group->set($postData);
		if (!$this->Group->validates()) {
			$this->Group->rollback();
			throw new ValidationException(
				__('Data validation error. This is not a valid group.'),
				['Group' => $this->Group->validationErrors]
			);
		}

		// Save group.
		$fields = $this->Group->getFindFields('Group::add', User::get('Role.name'));
		$groupSaved = $this->Group->save($postData, true, $fields['fields']);
		if ($groupSaved == false) {
			$this->Group->rollback();
			throw new InternalErrorException(__('Group could not be saved.'));
		}

		// Validate GroupUsers.
		if(!isset($postData['GroupUsers']) || empty($postData['GroupUsers'])) {
			$this->Group->rollback();
			throw new BadRequestException(__('Data validation error.'));
		}

		$savedGroupUsers = [];
		foreach($postData['GroupUsers'] as $groupUser) {
			if(!isset($groupUser['GroupUser']) || empty($groupUser['GroupUser'])) {
				$this->Group->rollback();
				throw new BadRequestException(__('Data validation error.'));
			}

			// Validates GroupUser
			$this->Group->GroupUser->create();
			$groupUser['GroupUser']['group_id'] = $groupSaved['Group']['id'];
			$this->Group->GroupUser->set($groupUser['GroupUser']);
			if (!$this->Group->GroupUser->validates()) {
				$this->Group->rollback();
				throw new BadRequestException(__('Data validation error.'));
			}

			// Save GroupUser
			$fields = $this->Group->GroupUser->getFindFields('add', User::get('Role.name'));
			$savedGroupUser = $this->Group->GroupUser->save($postData, true, $fields['fields']);
			$savedGroupUsers[] = $savedGroupUser;
			if ($savedGroupUser == false) {
				$this->Group->rollback();
				throw new InternalErrorException(__('GroupUser could not be saved.'));
			}
		}

		// End transaction
		$this->Group->commit();

		// Get find options and get all groups.
		$options = ['contain' => ['user'], 'Group.id' => $groupSaved['Group']['id']];
		$options = $this->Group->getFindOptions('Group::view', User::get('Role.name'), $options);
		$group = $this->Group->find('first', $options);

		// Email notification.
		if (!empty($savedGroupUsers)) {
			$this->EmailNotificator->groupAddUsers(User::get('id'), $group, $savedGroupUsers);
		}

		// Success response.
		$this->set('data', $group);
		$this->Message->success(__('The group has been added successfully.'));
	}

/**
 * Edit group
 *
 * @param string $id group uuid
 * @throws BadRequestException if the group id is missing or invalid
 * @throws NotFoundException if the group does not exist
 * @throws ForbiddenException if the user role is not admin or is not a group admin
 * @throws BadRequestException if the group data fails to validate
 * @return mixed
 */
	public function edit($id = null) {
		// Check request sanity
		if (!isset($id)) {
			throw new BadRequestException(__('The group id is missing.'));
		}
		if (!Common::isUuid($id)) {
			throw new BadRequestException(__('The group id is invalid.'));
		}
		$o = $this->Group->getFindOptions('Group::view', User::get('Role.name'), ['Group.id' => $id]);
		$group = $this->Group->find('first', $o);
		if (!$group) {
			throw new NotFoundException(__('The group does not exist.'));
		}

		// Only admin users or group managers can update groups.
		// Get role for current group.
		$groupUser = $this->Group->GroupUser->find('first', [
			'conditions' => [
				'user_id' => User::get('id'),
				'group_id' => $id,
			]
		]);

		// Check that the user is either an administrator, or a group administrator.
		$isGroupAdmin = (!empty($groupUser) && $groupUser['GroupUser']['is_admin'] == 1);
		$isAdmin = (User::get('Role.name') == Role::ADMIN);
		if (!$isAdmin && !$isGroupAdmin) {
			throw new ForbiddenException(__('You are not authorized to access to this group.'));
		}

		$isDryRun = $this->__isDryRun();
		$this->Group->begin();

		// If name if provided, and user is an admin, process name change.
		$groupData = $this->request->data;
		$groupName = Hash::extract($groupData, 'Group.name');
		$isNameProvided = !empty($groupName) ? true : false;
		if ($isAdmin == true && $isNameProvided == true && $isDryRun == false) {
			try {
				$this->__updateGroupName($id, $groupData['Group']['name']);
			}
			catch(ValidationException $e) {
				$this->Group->rollback();
				throw new ValidationException(__('Validation error'), $e->getInvalidFields());
			}
			catch(Exception $e) {
				$this->Group->rollback();
				throw new BadRequestException($e->getMessage());
			}
		}

		// Edit GroupUsers if provided.
		$isGroupUsersProvided = isset($groupData['GroupUsers']) && !empty($groupData['GroupUsers']) ? true : false;

		// Default output.
		$changes = [
			'count' => 0,
			'created' => [],
			'updated' => [],
			'deleted' => [],
		];
		$dryRunOutput = [];

		if ($isGroupUsersProvided && ($isGroupAdmin || $isAdmin)) {

			// In case of dry-run, calculate the list of secrets that need to be provided.
			if ($isDryRun) {
				$dryRunOutput = $this->__getEditDryRunOutput($id, $groupData['GroupUsers']);
			} else {
				// If secrets is not provided, define one by default.
				if (!isset($groupData['Secrets']) || empty($groupData['Secrets'])) {
					$groupData['Secrets'] = [];
				}
				try {
					$changes = $this->__updateGroupUsers($id, $groupData['GroupUsers'], $groupData['Secrets']);
				} catch (ValidationException $e) {
					$this->Group->rollback();
					throw new ValidationException($e->getMessage(), $e->getInvalidFields());
				} catch (Exception $e) {
					$this->Group->rollback();
					throw new BadRequestException($e->getMessage());
				}
			}
		}

		// Everything ok. Commit transaction.
		$this->Group->commit();

		// Get group and merge changes with group result.
		$o = $this->Group->getFindOptions(
			'Group::view', User::get('Role.name'), ['contain' => ['user'], 'Group.id' => $id]
		);
		$group = $this->Group->find('first', $o);
		$res = array_merge($group, ['changes' => $changes]);

		// In case of dry run, add output.
		if ($isDryRun) {
			$res['dry-run'] = $dryRunOutput;
		} else {
			// Notify by email the users who have been added to the group.
			if (!empty($changes['created'])) {
				$this->EmailNotificator->groupAddUsers(User::get('id'), $group, $changes['created']);
			}
			// Notify by email the users who have been removed from the group.
			if (!empty($changes['deleted'])) {
				$this->EmailNotificator->groupDeleteUsers(User::get('id'), $group, $changes['deleted']);
			}
			// Notify by email other group managers about the changes.
			if (!empty($changes['created']) || !empty($changes['deleted']) || !empty($changes['updated'])) {
				$this->EmailNotificator->groupUpdatedSummary(User::get('id'), $group, $changes);
			}
		}

		// Success response.
		$this->set('data', $res);
		$this->Message->success(__("The group has been updated successfully."));
	}

/**
 * @param null $id
 *
 * @return mixed
 */
	public function delete($id = null) {
		// Check request sanity
		if (!$this->request->is('delete')) {
			throw new BadRequestException(__('Invalid request method, should be DELETE.'));
		}
		if (!isset($id)) {
			throw new BadRequestException(__('The group id is missing.'));
		}
		if (!Common::isUuid($id)) {
			throw new BadRequestException(__('The group id is not valid.'));
		}
		// Check if the group exists.
		$o = $this->Group->getFindOptions('Group::view', User::get('Role.name'), ['Group.id' => $id]);
		$group = $this->Group->find('first', $o);
		if (!$group) {
			throw new NotFoundException(__('The group does not exist.'));
		}
		// Check that the user is either an administrator, or a group administrator.
		$isAdmin = (User::get('Role.name') == Role::ADMIN);
		if (!$isAdmin) {
			throw new ForbiddenException(__('You are not authorized to perform this operation on this group.'));
		}

		// Get the list of resources solely owned by the group.
		$resourcesSolelyOwned = $this->Group->GroupResourcePermission->findSoleOwnerResources($id);
		$resourceIds = Hash::extract($resourcesSolelyOwned, '{n}.Resource.id');
		$Resource = Common::getModel('Resource');

		if (count($resourcesSolelyOwned) > 0) {
			$options = $Resource->getFindOptions(
				'Resource::view',
				User::get('Role.name'),
				[
					'Resource.id' => $resourceIds,
					'contain' => [
						'Creator' => 0,
						'Modifier' => 0,
						'Secret' => 0,
						'Favorite' => 0
					]
				]
			);
			// Unload permissionable to be able to retrieve the list of resources even
			// without direct access to it.
			$Resource->Behaviors->unload('Permissionable');
			$resources = $Resource->find('all', $options);
			throw new ValidationException(
				__('The group is sole owner of some passwords. Transfer the ownership before deleting.'),
				$resources
			);
		}

		// In case of dry-run, list the resources that will not be accessible anymore, and return them.
		$isDryRun = $this->__isDryRun();
		if ($isDryRun) {
			// Get the list of resources shared with the group.
			$this->Group->GroupResourcePermission->Resource->Behaviors->unload('Permissionable');
			$resourcesShared = $this->Group->GroupResourcePermission->findAuthorizedResources($id);
			$resourceIds = Hash::extract($resourcesShared, '{n}.Resource.id');
			$options = $Resource->getFindOptions(
				'Resource::view',
				User::get('Role.name'),
				[
					'Resource.id' => $resourceIds,
					'contain' => [
						'Creator' => 0,
						'Modifier' => 0,
						'Secret' => 0,
						'Favorite' => 0
					]
				]
			);
			$Resource->Behaviors->unload('Permissionable');
			$resources = $Resource->find('all', $options);
			// Success response.
			$this->set('data', $resources);
			return $this->Message->success(__("The group can be deleted."));
		}

		// Everything alright. We can delete.
		try {
			$this->Group->softDelete($id);
		} catch(Exception $e) {
			throw new InternalErrorException(__('Could not delete group.'));
		}

		return $this->Message->success(__("The group has been deleted."));
	}

/**
 * Update a group name.
 *
 * @param $groupId string uuid
 * @param $name string group name
 * @throws ValidationException if the group name could not be validated
 * @throws InternalErrorException if the group could not be saved
 * @return mixed
 */
	private function __updateGroupName($groupId, $name) {
		// Validate data
		$this->Group->id = $groupId;
		$data = ['name' => $name];
		$this->Group->set($data);
		$validates = $this->Group->validates(['fieldList' => ['name']]);
		if (!$validates) {
			throw new ValidationException(
				__('The group name could not be validated.'),
				['Group' => $this->Group->validationErrors]
			);
		}

		// Get the fields for the edit operation and save
		$fields = $this->Group->getFindFields('Group::edit', User::get('Role.name'));
		$groupSaved = $this->Group->save($data, false, $fields['fields']);
		if (!$groupSaved) {
			throw new InternalErrorException(__('Could not save group.'));
		}

		return $groupSaved;
	}

/**
 * Update a list of group users.
 *
 * @param $groupId
 * @param $groupUsers
 * @param $secrets
 * @return array
 */
	private function __updateGroupUsers($groupId, $groupUsers, $secrets) {
		$res = [
			'count' => 0,
			'created' => [],
			'updated' => [],
			'deleted' => [],
		];

		// Build list of secrets grouped by user.
		$userSecrets = [];
		foreach($secrets as $secret) {
			if (isset($secret['Secret']['user_id'])) {
				if (!isset($userSecrets[$secret['Secret']['user_id']])) {
					$userSecrets[$secret['Secret']['user_id']] = [];
				}
				$userSecrets[$secret['Secret']['user_id']][] = $secret;
			}
		}

		$changes = $this->Group->GroupUser->prepareBulkUpdate($groupId, $groupUsers);

		// Only group managers can add users into groups.
		// Get role for current group.
		$groupUser = $this->Group->GroupUser->find('first', [
			'conditions' => [
				'user_id' => User::get('id'),
				'group_id' => $groupId,
			]
		]);

		$isGroupAdmin = !empty($groupUser) && $groupUser['GroupUser']['is_admin'] == 1;

		// Add new users can only be done by the group admin, not by the admin (unless he is a group admin).
		// TODO: need to implement the admin being able to request a user to be added.
		if ($isGroupAdmin) {
			foreach ($changes['create'] as $create) {
				if(!isset($userSecrets[$create['GroupUser']['user_id']])) {
					$userSecrets[$create['GroupUser']['user_id']] = [];
				}
				$res['created'][] = $this->Group->GroupUser->createGroupUser($create, $userSecrets[$create['GroupUser']['user_id']]);
				$res['count']++;
			}
		}
		foreach ($changes['update'] as $update) {
			$res['updated'][] = $this->Group->GroupUser->updateGroupUser($update);
			$res['count']++;
		}
		foreach ($changes['delete'] as $delete) {
			$res['deleted'][] = $this->Group->GroupUser->deleteGroupUser($delete);
			$res['count']++;
		}

		return $res;
	}

/**
 * Get output for edit::dry-run.
 *
 * Provides the list of secrets that will have to be encrypted.
 *
 * @param $groupId
 * @param $groupUsers
 * @return array
 */
	private function __getEditDryRunOutput($groupId, $groupUsers) {

		$secretsToEncrypt = [
			'SecretsNeeded' => [],
			'Secrets' => [],
		];

		$changes = $this->Group->GroupUser->prepareBulkUpdate($groupId, $groupUsers);
		if (count($changes['create']) > 0) {
			$userIds = Hash::extract($changes['create'], '{n}.GroupUser.user_id');
			$userResources = $this->Group->GroupResourcePermission->findUnauthorizedResourcesForUsers($groupId, $userIds);
			foreach($userResources as $userId => $resources) {
				foreach($resources as $resource) {
					$secretsToEncrypt['SecretsNeeded'][] = [
						'Secret' => [
							'user_id' => $userId,
							'resource_id' => $resource['Resource']['id'],
						]
					];
				}
			}

			$resourceIds = array_unique(Hash::extract($secretsToEncrypt['SecretsNeeded'], '{n}.Secret.resource_id'));
			// Get the same resources with secret for user.
			$Resource = Common::getModel('Resource');
			$options = $Resource->getFindOptions('Group::edit', User::get('Role.name'), ['Resource.id' => $resourceIds]);
			//throw new Exception(print_r($options, true));
			$resources = $Resource->find('all', $options);

			// Remove unwanted output. (this is disgusting and should be implemented more elegantly when we'll shift to CakePHP 3.x).
			foreach($resources as $key => $resource) {
				unset($resources[$key]['UserResourcePermission']);
				unset($resources[$key]['Permission']);
			}

			$secretsToEncrypt['Secrets'] = $resources;
		}

		return $secretsToEncrypt;
	}

/**
 * Check if the call is made with the dry-run parameter.
 * @return bool
 */
	private function __isDryRun() {
		return (in_array('dry-run', $this->params['pass']));
	}
}
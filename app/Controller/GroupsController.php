<?php
/**
 * Groups Controller
 *
 * @copyright (c) 2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class GroupsController extends AppController {

/**
 * @var array components used by this controller
 */
	public $components = [
		'Filter',
	];

/**
 * Tidy up output of a group.
 *
 * Remove useless GroupsUsers indexes in the output
 * mainly due to the use of superjoin behaviour.
 *
 * @param $group
 * @return mixed
 */
	private function __tidyOutput($group) {
		if (isset($group['User'])) {
			foreach($group['User'] as $keyUser => $user) {
				unset($group['User'][$keyUser]['GroupsUser']);
			}
		}
		return $group;
	}

/**
 * Index entry point.
 *
 * List all groups.
 *
 * contain available:
 * - user: return the list of User and UserGroup for each group
 *   example: groups.json?contain[user]=1 or groups.json?contain=user
 *
 * - resource: return the list of Resource and Secrets (for current user) that can be accessed by the group.
 *   example: groups.json?contain[resource]=1 or groups.json?contain=resource
 *
 * filter available
 * - To document (doc available here: https://docs.google.com/document/d/1eQk9niUKnLAyIJ9y-NYtzIOuZOwMYmrIEG4rQmqJ86c/edit)
 */
	public function index() {
		// Get find options.
		$o = $this->Group->getFindOptions(
			'Group::index',
			User::get('Role.name'),
			[
				'contain' => isset($this->request->params['contain']) ? $this->request->params['contain'] : [],
				'filter' => isset($this->request->params['filter']) ? $this->request->params['filter'] : [],
			]
		);

		// Get all groups.
		$groups = $this->Group->find('all', $o);

		// If filter 'has-users' is applied, remove entries where all the users are not listed.
		if (isset($this->request->params['filter']) && isset($this->request->params['filter']['has-users'])) {
			$groups = $this->Group->filterGroupWithAllUsers($groups, $this->request->params['filter']['has-users']);
		}

		// Remove useless elements due to super join.
		// #dirty, but it's a superjoin behaviour and we don't have an alternative for now.
		// Let's wait for Cake3.x migration to fix this.
		foreach($groups as $key => $group) {
			$groups[$key] = $this->__tidyOutput($group);
		}

		// Send response.
		$this->set('data', $groups);
		$this->Message->success();
	}

/**
 * View entry point.
 *
 * To view a group.
 */
	public function view($id = null) {
		// Check if the id is provided
		if (!isset($id)) {
			return $this->Message->error(__('The group id is missing'), ['code' => 400]);
		}

		// Check if the id is valid.
		if (!Common::isUuid($id)) {
			return $this->Message->error(__('The group id is invalid'), ['code' => 400]);
		}

		// Get find options.
		$o = $this->Group->getFindOptions(
			'Group::view',
			User::get('Role.name'),
			[
				'contain' => isset($this->request->params['contain']) ? $this->request->params['contain'] : [],
				'Group.id' => $id,
			]
		);

		// Get all groups.
		$group = $this->Group->find('first', $o);

		// If group doesn't exist, return 404.
		if(empty($group)) {
			return $this->Message->error(__('The group doesn\'t exist'), ['code' => 404]);
		}

		// If group exists, tidy output.
		$group = $this->__tidyOutput($group);

		// Send response.
		$this->set('data', $group);
		$this->Message->success();
	}

/**
 * Add entry point.
 *
 * Add a group.
 */
	public function add() {
		$postData = $this->request->data;

		// check the HTTP request method
		if (!$this->request->is('post')) {
			$this->Message->error(__('Invalid request method, should be POST'));
			return;
		}

		// Check that user is admin. (only admin can create groups).
		if (User::get('Role.name') != Role::ADMIN) {
			return $this->Message->error(__('You are not authorized to access this endpoint'), ['code' => '401']);
		}

		// Validate group users.
		$groupManagers = Hash::extract($postData['GroupUsers'], '{n}.GroupUser.is_admin');
		if (!in_array('1', $groupManagers)) {
			$this->Message->error(__('A group manager must be provided'), ['code' => '400']);
			return;
		}

		// Begin transaction
		$this->Group->begin();

		// Create group.
		$this->Group->create();
		$this->Group->set($postData);

		// Check if the data is valid.
		if (!$this->Group->validates()) {
			$this->Group->rollback();
			$this->Message->error(__('Data validation error'), [
				'code' => '400',
				'body' => ['Group' => $this->Group->validationErrors],
			]);
			return;
		}

		// Save group.
		$fields = $this->Group->getFindFields('Group::add', User::get('Role.name'));
		$groupSaved = $this->Group->save($postData, true, $fields['fields']);
		if ($groupSaved == false) {
			$this->Group->rollback();
			$this->Message->error(__('Group could not be saved.'));
			return;
		}

		// Validate GroupUsers.
		if(!isset($postData['GroupUsers']) || empty($postData['GroupUsers'])) {
			$this->Group->rollback();
			$this->Message->error(__('Data validation error'));
			return;
		}

		foreach($postData['GroupUsers'] as $groupUser) {
			if(!isset($groupUser['GroupUser']) || empty($groupUser['GroupUser'])) {
				$this->Group->rollback();
				$this->Message->error(__('Data validation error'));
				return;
			}

			// Set group id.
			$groupUser['GroupUser']['group_id'] = $groupSaved['Group']['id'];

			// Validates GroupUser.
			$this->Group->GroupUser->create();
			$this->Group->GroupUser->set($groupUser['GroupUser']);
			if (!$this->Group->GroupUser->validates()) {
				$this->Group->rollback();
				$this->Message->error(__('Data validation error'));
				return;
			}

			// Save GroupUser.
			$fields = $this->Group->GroupUser->getFindFields('add', User::get('Role.name'));
			$savedGroupUser = $this->Group->GroupUser->save($postData, true, $fields['fields']);
			if ($savedGroupUser == false) {
				$this->Group->rollback();
				$this->Message->error(__('GroupUser could not be saved.'));
				return;
			}
		}

		// Begin transaction
		$this->Group->commit();

		// Return results.

		// Get find options.
		$o = $this->Group->getFindOptions(
			'Group::view',
			User::get('Role.name'),
			[
				'contain' => ['user'],
				'Group.id' => $groupSaved['Group']['id']
			]
		);

		// Get all groups.
		$group = $this->Group->find('first', $o);
		// Tidy up.
		$group = $this->__tidyOutput($group);

		// Success response.
		$this->set('data', $group);
		$this->Message->success(__("The group has been added successfully."));
	}

/**
 * Edit entry point.
 *
 * Edit a Group and its GroupUsers.
 *
 * @param null $id
 * @return mixed
 */
	public function edit($id = null) {
		// Check if the id is provided
		if (!isset($id)) {
			return $this->Message->error(__('The group id is missing'), ['code' => 400]);
		}

		// Check if the id is valid
		if (!Common::isUuid($id)) {
			return $this->Message->error(__('The group id is invalid'), ['code' => 400]);
		}

		// Check if the group exists.
		$o = $this->Group->getFindOptions('Group::view', User::get('Role.name'), ['Group.id' => $id]);
		$group = $this->Group->find('first', $o);
		if (!$group) {
			return $this->Message->error(__('The group does not exist'), ['code' => 404]);
		}

		// Only admin users or group managers can update groups.
		// Get role for current group.
		$groupUser = $this->Group->GroupUser->find('first', [
			'conditions' => [
				'user_id' => User::get('id'),
				'group_id' => $id,
			]
		]);

		$isGroupAdmin = !empty($groupUser) && $groupUser['GroupUser']['is_admin'] == 1;
		$isAdmin = User::get('Role.name') == Role::ADMIN;

		// Check that the user is either an administrator, or a group administrator.
		if (!$isAdmin && !$isGroupAdmin) {
			return $this->Message->error(__('You are not authorized to access to this group'), ['code' => '401']);
		}

		$this->Group->begin();

		// If name if provided, and user is an admin, process name change.
		$groupData = $this->request->data;

		$isNameProvided = !empty(Hash::extract($groupData, 'Group.name')) ? true : false;
		// Process request.
		if ($isAdmin == true && $isNameProvided == true) {
			$this->Group->id = $id;
			$data = [
				'name' => $groupData['Group']['name'],
			];
			// Update name.
			$this->Group->set($data);

			// Validate data.
			$validates = $this->Group->validates(['fieldList' => ['name']]);
			if( ! $validates) {
				return $this->Message->error(__('The group name could not be validated'), ['body' => $this->Group->validationErrors]);
			}

			// Get the fields for the edit operation.
			$fields = $this->Group->getFindFields('Group::edit', User::get('Role.name'));

			// Save group.
			$groupSaved = $this->Group->save($data, false, $fields['fields']);
			if ( ! $groupSaved) {
				$this->Group->rollback();
				return $this->Message->error(__('The group name could not be saved'));
			}
		}

		// Edit Group admins if provided.
		$groupUsers = Hash::extract($groupData, 'GroupUsers');
		$isGroupUsersProvided = !empty($groupUsers) ? true : false;

		$changes = [];
		if ($isGroupUsersProvided && $isGroupAdmin) {
			try {
				$changes = $this->Group->GroupUser->bulkUpdate($id, $groupUsers);
			}
			catch(ValidationException $e) {
				$this->Group->rollback();
				return $this->Message->error($e->getMessage(), ['body' => $e->getInvalidFields()]);
			}
			catch (Exception $e) {
				$this->Group->rollback();
				return $this->Message->error($e->getMessage());
			}

			// If GroupUsers have been added.
			if (!empty($changes['created'])) {
				// Process all added secrets.
				try {
					$this->__processAddedSecrets($id, $changes['created'], $groupData['Secrets']);
				}
				catch (Exception $e) {
					$this->Group->rollback();
					return $this->Message->error($e->getMessage());
				}
			}

			// Save secrets.
			// Process all deleted.
			// 1) Get all secrets accessible by the group for which the user doesn't have any special direct permission.
			// 2) Delete all the secrets for which the user is not supposed to access.
		}

		// Everything ok. Commit transaction.
		$this->Group->commit();

		// Return results.

		// Get find options.
		$o = $this->Group->getFindOptions(
			'Group::view',
			User::get('Role.name'),
			[
				'contain' => ['user'],
				'Group.id' => $id
			]
		);

		// Get group.
		$group = $this->Group->find('first', $o);
		// Tidy up.
		$group = $this->__tidyOutput($group);

		// If changes is empty, set default values.
		if (empty($changes)){
			$changes = [
				'changes' => [
					'count' => 0,
					'updated' => [],
					'created' => [],
					'deleted' => [],
				],
			];
		}
		else {
			$changes = [
				'changes' => $changes,
			];
		}

		// Merge changes with group result.
		$res = array_merge($group, $changes);

		// Success response.
		$this->set('data', $res);
		$this->Message->success(__("The group has been updated successfully."));
	}

/**
 * Process added secrets while updating a group.
 *
 * This function makes sure that all necessary secrets are provided for all
 * the added users, and resources that the group can access.
 *
 * @param uuid $groupId
 * @param array $addedUsers
 * @param array $secrets
 * @throws Exception
 */
	protected function __processAddedSecrets($groupId, $addedUsers, $secrets) {

		// Add secrets for added users.
		if (count($addedUsers) != count($secrets)) {
			throw new Exception(__("The number of secrets provided don't match the %s new users who have now access to the resources",
				count($addedUsers)));
		}

		// Get the list of resources accessible by the group.
		$resources = $this->Group->GroupResourcePermission->findAuthorizedResources($groupId);

		foreach ($addedUsers as $userId) {
			foreach($resources as $resource) {
				// TODO : add exception for secrets that are already encrypted for the given user.
				$secretProvided = false;
				foreach ($secrets as $secret) {
					$secretProvided = $secret['Secret']['user_id'] == $userId
						&& $secret['Secret']['resource_id'] == $resource['Resource']['id'];
					if ($secretProvided) {
						break;
					}
				}
				// If a user doesn't have its secret provided, we throw an exception.
				if (!$secretProvided) {
					throw new Exception(__("The secret for user id %s and resource id %s is not provided", $userId, $resource['Resource']['id']));
				}

				// Save secret.
				$data = [
					'user_id' => $userId,
					'resource_id' => $resource['Resource']['id'],
					'data' => $secret['Secret']['data'],
				];
				// Validates data.
				$this->Secret->set($data);
				$v = $this->Secret->validates();
				if (!$v) {
					throw new Exception(__("Invalid secret provided for user %s and resource %s", $userId, $resource['Resource']['id']));
				}

				// Save secret.
				$this->Secret->create();
				$s = $this->Secret->save($data);
				if (!$s) {
					throw new Exception(__("Could not save secret for user %s and resource %s", $userId, $resource['Resource']['id']));
				}
			}
		}
	}
}

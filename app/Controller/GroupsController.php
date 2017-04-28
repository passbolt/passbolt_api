<?php
/**
 * Groups Controller
 *
 * @copyright (c) 2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class GroupsController extends AppController {

/**
 * @var array list of supported components
 */
	public $components = [
		'QueryString'
	];

/**
 * Get all groups
 * Renders a json object of the groups.
 *
 * @return void
 *
 * @SWG\Get(
 *   path="/groups.json",
 *   summary="Find groups",
 * @SWG\Parameter(
 *     name="filter",
 *     in="query",
 *     description="A list of filter",
 *     required=false,
 *     type="string",
 * 	   enum={
 * 		 "has-users",
 * 		 "has-managers"
 * 	   }
 *   ),
 * @SWG\Parameter(
 *     name="contain",
 *     in="query",
 *     description="A list of associated models",
 *     required=false,
 *     type="string",
 * 	   enum={
 * 		 "user",
 *     	 "resource",
 *       "modifier"
 * 	   }
 *   ),
 * @SWG\Parameter(
 *     name="order",
 *     in="query",
 *     description="A list of order",
 *     required=false,
 *     type="string",
 * 	   enum={
 * 	     "Group.name",
 * 	   }
 *   ),
 * @SWG\Response(
 *     response=200,
 *     description="An array of groups",
 *     @SWG\Schema(
 *       type="object",
 *       properties={
 *         @SWG\Property(
 *           property="header",
 *           ref="#/definitions/Header"
 *         ),
 *         @SWG\Property(
 *           property="body",
 *           type="array",
 *           items={
 * 				"$ref"="#/definitions/Group"
 *           }
 *         )
 *       }
 *     )
 *   )
 * )
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
 * Get a group
 * Renders a json object of the group
 *
 * @param string $id the uuid of the group
 * @return void
 *
 * @SWG\Get(
 *   path="/groups/{uuid}.json",
 *   summary="Find a group by ID",
 * @SWG\Parameter(
 *     name="id",
 *     in="path",
 *     required=true,
 *     type="string",
 *     description="the uuid of the group",
 *   ),
 * @SWG\Parameter(
 *     name="contain",
 *     in="query",
 *     description="A list of associated models",
 *     required=false,
 *     type="string",
 * 	   enum={
 * 		 "user",
 *     	 "resource",
 *       "modifier"
 * 	   }
 *   ),
 * @SWG\Response(
 *     response=200,
 *     description="The details of the group",
 *     @SWG\Schema(
 *       type="object",
 *       properties={
 *         @SWG\Property(
 *           property="header",
 *           ref="#/definitions/Header"
 *         ),
 *         @SWG\Property(
 *           property="body",
 *           ref="#/definitions/Group"
 *         )
 *       }
 *     )
 *   )
 * )
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

		// check if it exists
		if (!$this->Group->exists($id)) {
			return $this->Message->error(__('The group does not exist'), ['code' => 404]);
		}
		// check if it has been soft deleted
		if ($this->Group->isSoftDeleted($id)) {
			return $this->Message->error(__('The group does not exist'), ['code' => 404]);
		}

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

		// Get find options and get all groups.
		$o = $this->Group->getFindOptions(
			'Group::view',
			User::get('Role.name'),
			[
				'contain' => ['user'],
				'Group.id' => $groupSaved['Group']['id']
			]
		);
		$group = $this->Group->find('first', $o);

		// Success response.
		$this->set('data', $group);
		$this->Message->success(__("The group has been added successfully."));
	}

/**
 * Update a group name.
 *
 * @param $groupId
 * @param $name
 * @return mixed
 * @throws Exception
 * @throws ValidationException
 */
	private function __updateGroupName($groupId, $name) {
		$this->Group->id = $groupId;
		$data = [
			'name' => $name,
		];
		// Update name.
		$this->Group->set($data);

		// Validate data.
		$validates = $this->Group->validates(['fieldList' => ['name']]);
		if( ! $validates) {
			throw new ValidationException(__('The group name could not be validated'), $this->Group->validationErrors);
		}

		// Get the fields for the edit operation.
		$fields = $this->Group->getFindFields('Group::edit', User::get('Role.name'));

		// Save group.
		$groupSaved = $this->Group->save($data, false, $fields['fields']);

		if(!$groupSaved) {
			throw new Exception('Could not save group');
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
		foreach ($changes['create'] as $create) {
			if(!isset($userSecrets[$create['GroupUser']['user_id']])) {
				$userSecrets[$create['GroupUser']['user_id']] = [];
			}
			$res['created'][] = $this->Group->GroupUser->createGroupUser($create, $userSecrets[$create['GroupUser']['user_id']]);
			$res['count']++;
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
		if (in_array('dry-run', $this->params['pass'])) {
			return true;
		}
		return false;
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

		$isDryRun = $this->__isDryRun();

		$this->Group->begin();

		// If name if provided, and user is an admin, process name change.
		$groupData = $this->request->data;

		$isNameProvided = !empty(Hash::extract($groupData, 'Group.name')) ? true : false;
		// Process request.
		if ($isAdmin == true && $isNameProvided == true && $isDryRun == false) {
			try {
				$this->__updateGroupName($id, $groupData['Group']['name']);
			}
			catch(ValidationException $e) {
				$this->Group->rollback();
				return $this->Message->error(__('Validation error'), ['body' => $e->getInvalidFields()]);
			}
			catch(Exception $e) {
				$this->Group->rollback();
				return $this->Message->error($e->getMessage());
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

		if ($isGroupUsersProvided && $isGroupAdmin) {

			// In case of dry-run, calculate the list of secrets that need to be provided.
			if ($isDryRun) {
				$dryRunOutput = $this->__getEditDryRunOutput($id, $groupData['GroupUsers']);
			}
			else {
				// If secrets is not provided, define one by default.
				if (!isset($groupData['Secrets']) || empty($groupData['Secrets'])) {
					$groupData['Secrets'] = [];
				}

				try {
					$changes = $this->__updateGroupUsers($id, $groupData['GroupUsers'], $groupData['Secrets']);
				} catch (ValidationException $e) {
					$this->Group->rollback();
					return $this->Message->error($e->getMessage(), ['body' => $e->getInvalidFields()]);
				} catch (Exception $e) {
					$this->Group->rollback();
					return $this->Message->error($e->getMessage());
				}
			}
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

		// Merge changes with group result.
		$res = array_merge($group, [ 'changes' => $changes ]);

		// In case of dry run, add output.
		if ($isDryRun) {
			$res['dry-run'] = $dryRunOutput;
		}

		// Success response.
		$this->set('data', $res);
		$this->Message->success(__("The group has been updated successfully."));
	}
}

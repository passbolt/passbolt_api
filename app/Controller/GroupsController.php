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
 * 		 "has-managers",
 * 		 "has-resources",
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
 *     	 "resource"
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
		// Add filters, contains and order data to the get find options data.
		$findData['contain'] = $this->request->params['contain'];
		$findData['filter'] = $this->request->params['filter'];
		$findData['order'] = $this->request->params['order'];

		// Get find options.
		$o = $this->Group->getFindOptions('Group::index', User::get('Role.name'), $findData);

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
}
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
		foreach($groups as $key => $group) {
			$groups[$key] = $this->__tidyOutput($group);
		}

		// Send response.
		$this->set('data', $groups);
		$this->Message->success();
	}


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
			$this->Message->error(__('Data validation error'));
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
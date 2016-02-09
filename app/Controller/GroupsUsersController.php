<?php

/**
 * Groups Users controller
 * This file will define how groups_users are managed.
 *
 * @copyright    (c) 2015-present Passbolt.com
 * @licence        GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class GroupsUsersController extends AppController {

/**
 * @var $uses array containing the class names of models this controller uses.
 */
	public $uses = ['GroupUser'];

/**
 * Get a groupUser
 * Renders a json object of the resource
 *
 * @param string $id the uuid of the resource
 * @return void
 */
	public function view($id = null) {
		// check if the group id is provided
		if (!isset($id)) {
			$this->Message->error(__('The groupUser id is missing'));

			return;
		}
		// check if the id is valid
		if (!Common::isUuid($id)) {
			$this->Message->error(__('The groupUser id is invalid'));

			return;
		}
		// check if it exists
		$data = [
			'GroupUser.id' => $id
		];
		$options = $this->GroupUser->getFindOptions('view', User::get('Role.name'), $data);
		$cr = $this->GroupUser->find('all', $options);
		if (!count($cr)) {
			$this->Message->error(__('The groupUser does not exist'), ['code' => 404]);

			return;
		}
		$this->set('data', $cr[0]);
		$this->Message->success();
	}

/**
 * Delete a groupUser
 *
 * @param string $id the uuid of the resource to delete
 * @return void
 */
	public function delete($id = null) {
		// check if the group id is provided
		if (!isset($id)) {
			$this->Message->error(__('The groupUser id is missing'));

			return;
		}
		// check if the id is valid
		if (!Common::isUuid($id)) {
			$this->Message->error(__('The groupUser id is invalid'));

			return;
		}
		$resource = $this->GroupUser->findById($id);
		if (!$resource) {
			$this->Message->error(__('The groupUser does not exist'), ['code' => 404]);

			return;
		}

		if (!$this->GroupUser->delete($id)) {
			$this->Message->error(__('Error while deleting'));

			return;
		}
		$this->Message->success(__('The groupUser was successfully deleted'));
	}

/**
 * Add a GroupUser
 *
 * @return void
 */
	public function add() {
		// check the HTTP request method
		if (!$this->request->is('post')) {
			$this->Message->error(__('Invalid request method, should be POST'));

			return;
		}
		// check if data was provided
		if (!isset($this->request->data['GroupUser'])) {
			$this->Message->error(__('No data were provided'));

			return;
		}

		// set the data for validation and save
		$gupost = $this->request->data;
		$this->GroupUser->set($gupost);

		$fields = $this->GroupUser->getFindFields('add', User::get('Role.name'));

		// check if the data is valid
		if (!$this->GroupUser->validates()) {
			$this->Message->error(__('Could not validate data'));

			return;
		}

		$cr = $this->GroupUser->save($gupost, false, $fields['fields']);
		if ($cr === false) {
			$this->Message->error(__('The GroupUser could not be saved'));

			return;
		}
		$fields = $this->GroupUser->getFindFields('add', User::get('Role.name'));
		$this->set('data', $this->GroupUser->findById($cr['GroupUser']['id'], $fields['fields']));
		$this->Message->success(__('The groupUser was successfully added'));
	}
}
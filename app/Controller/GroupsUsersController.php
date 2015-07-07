<?php
/**
 * Groups Users controller
 * This file will define how groups_users are managed.
 *
 * @copyright    Copyright 2014, Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Controller.GroupsUsersController
 * @since        version 2.12.7
 */

class GroupsUsersController extends AppController {

	public $uses = array('GroupUser');

	/**
	 * Get a groupUser
	 * Renders a json object of the resource
	 *
	 * @param integer $id the id of the resource
	 * @return void
	 */
	public function view($id=null) {
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
		$data = array(
			'GroupUser.id' => $id
		);
		$options = $this->GroupUser->getFindOptions('view', User::get('Role.name'), $data);
		$cr = $this->GroupUser->find('all', $options);
		if (!count($cr)) {
			$this->Message->error(__('The groupUser does not exist'), array('code' => 404));
			return;
		}
		$this->set('data', $cr[0]);
		$this->Message->success();
	}

	/**
	 * Delete a groupUser
	 * @param integer id the id of the resource to delete
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
			$this->Message->error(__('The groupUser does not exist'), array('code' => 404));
			return;
		}

		if (!$this->GroupUser->delete($id)) {
			$this->Message->error(__('Error while deleting'));
			return;
		}
		$this->Message->success(__('The groupUser was sucessfully deleted'));
	}

	/**
	 * Add a GroupUser
	 * @param post : the posted data
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
		$this->Message->success(__('The groupUser was sucessfully added'));
	}
}
<?php
/**
 * Users Controller
 *
 * @copyright   Copyright 2012, Passbolt.com
 * @license     http://www.passbolt.com/license
 * @package     app.Controller.UsersController
 * @since       version 2.12.9
 */
class UsersController extends AppController {

	public $helpers = array('PassboltAuth');

	/**
	 * Login
	 *
	 * @access public
	 */
	public function login() {
		// check if the user Authentication worked
		// someone can not remain anonymous forever
		if (!$this->Auth->login() || User::isAnonymous()) {
			$this->layout = 'login';
			$this->view = '/Users/login';
			if ($this->request->is('post')) {
				$this->request->data['User']['password'] = null;
				$this->Message->error(__('Invalid username or password, try again'));
			}
			return;
		}
		// avoid looping if the requested URL is logout
		if ($this->Auth->redirect() == '/logout' || $this->Auth->redirect() == '/login') {
			$this->redirect('/');
		} else {
			$this->redirect($this->Auth->redirect());
		}
	}

	/**
	 * Logout
	 *
	 * @access public
	 */
	public function logout() {
		$this->redirect($this->Auth->logout());
	}

	/**
	 * Index entry point
	 *
	 * @access public
	 */
	public function index() {
		$keywords = isset($this->request->query['keywords']) ? $this->request->query['keywords'] : '';

		$data = array();
		// if keywords provided build the model request with
		if (!empty($keywords)) {
			$data['keywords'] = $keywords;
		}
		$o = $this->User->getFindOptions('User::index', User::get('Role.name'), $data);
		$returnVal = $this->User->find('all', $o);
		if (empty($returnVal)) {
			$this->Message->notice(__('There is no user to display'));
			return;
		}
		$this->set('data', $returnVal);
		$this->Message->success();
	}

	/**
	 * View
	 *
	 * @param $id UUID of the user
	 *
	 * @access public
	 */
	public function view($id = null) {
		// check if the id is provided
		if (!isset($id)) {
			$this->Message->error(__('The user id is missing'));
			return;
		}
		// check if the id is valid
		if (!Common::isUuid($id)) {
			$this->Message->error(__('The user id is invalid'));
			return;
		}
		// not sql needed if a user is asking for his own data
		if (User::get('id') == $id) {
			$user = User::get();
		} else {
			$data = array('User.id' => $id);
			$o = $this->User->getFindOptions('User::view', User::get('Role.name'), $data);
			$user = $this->User->find('all', $o);
			$user = isset($user[0]) ? $user[0] : null;
		}

		if (!$user) {
			$this->Message->error(__('The user does not exist'), array('code' => 404));
			return;
		}

		$this->set('data', $user);
		$this->Message->success();
	}

	/**
	 * add a user entry point
	 */
	public function add() {
		// TODO : add associations management

		// First of all, check if the user is an administrator
		if (User::get('Role.name') != Role::ADMIN) {
			$this->Message->error(__('You are not authorized to access that location'));
			return;
		}

		// check the HTTP request method
		if (!$this->request->is('post')) {
			$this->Message->error(__('Invalid request method, should be POST'));
			return;
		}
		// check if data was provided
		if (!isset($this->request->data['User'])) {
			$this->Message->error(__('No data were provided'));
			return;
		}

		// set the data for validation and save
		$userData = $this->request->data;

		// If role id is not provided, we assign a default one
		if(!isset($userData['User']['role_id']) || empty($userData['User']['role_id'])) {
			$userData['User']['role_id'] = $this->User->Role->field('Role.id', array('name' => Role::USER));
		}

		$this->User->set($userData);

		$fields = $this->User->getFindFields('User::save', User::get('Role.name'));

		// check if the data is valid
		if (!$this->User->validates()) {
			$this->Message->error(__('Could not validate user data'));
			return;
		}

		$this->User->begin();
		$user = $this->User->save($userData, false, $fields['fields']);

		if ($user == false) {
			$this->User->rollback();
			$this->Message->error(__('The user could not be saved'));
			return;
		}
		$this->User->commit();
		$data = array('User.id' => $this->User->id);
		$options = $this->User->getFindOptions('User::view', User::get('Role.name'), $data);
		print_r($options);
		$users = $this->User->find('all', $options);

		$this->Message->success(__("The user has been saved successfully"));
		print_r($users);
		$this->set('data', $users[0]);

		return;
	}

	/**
	 * edit entry point for users
	 *
	 * @param uuid $id the id of the user we want to edit
	 */
	public function edit($id = null) {
		// First of all, check if the user is an administrator
		if (User::get('Role.name') != Role::ADMIN) {
			$this->Message->error(__('You are not authorized to access that location'));
			return;
		}

		// check if the id is provided
		if (!isset($id)) {
			$this->Message->error(__('The user id is missing'));
			return;
		}
		
		// check if the id is valid
		if (!Common::isUuid($id)) {
			$this->Message->error(__('The user id is invalid'));
			return;
		}
		
		// get the resource id
		$resource = $this->User->findById($id);
		if (!$resource) {
			$this->Message->error(__('The user does not exist'), array('code' => 404));
			return;
		}

		// check the HTTP request method
		if (!$this->request->is('put')) {
			$this->Message->error(__('Invalid request method, should be PUT'));
			return;
		}

		// check if data was provided
		if (!isset($this->request->data['User'])) {
			$this->Message->error(__('No data were provided'));
			return;
		}

		// set the data for validation and save
		$userData = $this->request->data;

		if (isset($userData['User'])) {
			$this->User->id = $id;

			$this->User->set($userData);
			if (!$this->User->validates()) {
				$this->Message->error(__('Could not validate User'));
				return;
			}

			$fields = $this->User->getFindFields('User::edit', User::get('Role.name'));
			$this->User->begin();
			$save = $this->User->save($userData, false, $fields['fields']);
			if (!$save) {
				$this->User->rollback();
				$this->Message->error(__('The user could not be updated'));
				return;
			}
			$this->User->commit();

			$data = array('User.id' => $this->User->id);
			$options = $this->User->getFindOptions('User::view', User::get('Role.name'), $data);
			$user = $this->User->find('all', $options);

			$this->Message->success(__("The user has been updated successfully"));
			$this->set('data', $user);

			return;
		}
	}

	/**
	 * Delete a user
	 *
	 * @param uuid id the id of the user to delete
	 */
	public function delete($id = null) {
		// First of all, check if the user is an administrator
		if (User::get('Role.name') != Role::ADMIN) {
			$this->Message->error(__('You are not authorized to access that location'));
			return;
		}

		// check if the category id is provided
		if (!isset($id)) {
			$this->Message->error(__('The user id is missing'));
			return;
		}
		
		// check if the id is valid
		if (!Common::isUuid($id)) {
			$this->Message->error(__('The user id is invalid'));
			return;
		}
		
		$user = $this->User->findById($id);
		if (!$user) {
			$this->Message->error(__('The user does not exist'), array('code' => 404));
			return;
		}

		$this->User->id = $id;
		$user['User']['deleted'] = true;

		$fields = $this->User->getFindFields('User::delete', User::get('Role.name'));
		$this->User->begin();
		if (!$this->User->save($user, true, $fields['fields'])) {
			$this->User->rollback();
			$this->Message->error(__('Error while deleting user'));
			return;
		}
		$this->User->commit();
		$this->Message->success(__('The user was sucessfully deleted'));
	}

}

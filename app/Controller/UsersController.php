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

	public $components = array(
		'Filter',
		'EmailNotificator',
	);

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
				$this->Message->error(__('Invalid username or password, try again'), array('throw' => false));
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
		$filter = $this->Filter->fromRequest($this->request->query);

		// Merge the filter into the additional information to pass to the model request
		$data = array_merge($data, $filter);

		// If group filter is used.
		if (isset($data['foreignModels']['Group.id'])) {
			// Tmp array to store the target groups
			$groups = array();

			foreach ($data['foreignModels']['Group.id'] as $groupId) {
				// if a category id is provided check it is well an uid
				if (!Common::isUuid($groupId)) {
					return $this->Message->error(__('The group id is invalid'));
				}
				// check if the group exists
				$Group = Common::getModel('Group');
				$group = $Group->findById($groupId);
				if (!$group) {
					return $this->Message->error(__('The group doesn\t exist'));
				}
				$groups[] = $group['Group']['id'];
			}
			$data['foreignModels']['Group.id'] = $groups;
		}

		// if keywords provided build the model request with
		if (!empty($keywords)) {
			$data['keywords'] = $keywords;
		}
		$o = $this->User->getFindOptions('User::index', User::get('Role.name'), $data);
		$returnVal = $this->User->find('all', $o);
		if (empty($returnVal)) {
			return $this->Message->notice(__('There is no user to display'));
		}
		$this->set('data', $returnVal);
		$this->Message->success();
	}

	/**
	 * Recursive ksort() implementation
	 *
	 * @param array $array
	 * @param integer
	 * @return void
	 * @link https://gist.github.com/601849
	 */
	public function ksortRecursive(&$array, $sortFlags = SORT_REGULAR) {
		if (!is_array($array)) return false;
		ksort($array, $sortFlags);
		foreach ($array as &$arr) {
			$this->ksortRecursive($arr, $sortFlags);
		}
		return true;
	}

/**
 * View
 *
 * @param $id UUID of the user
 *
 * @access public
 */
	public function view($id = null) {
		// asking for me returns the currently logged-in user.
		if ($id == 'me') {
			$id = User::get('id');
		}

		// check if the id is provided
		if (!isset($id)) {
			return $this->Message->error(__('The user id is missing'));
		}
		// check if the id is valid
		if (!Common::isUuid($id)) {
			return $this->Message->error(__('The user id is invalid'));
		}

		// Retrieve the user
		$data = array('User.id' => $id);
		$o = $this->User->getFindOptions('User::view', User::get('Role.name'), $data);
		$users = $this->User->find('all', $o);
		$user = null;
		if ($users) {
			$user = reset($users);
		} else {
			return $this->Message->error(__('The user does not exist'), array('code' => 404));
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
			return $this->Message->error(__('You are not authorized to access that location'));
		}

		// check the HTTP request method
		if (!$this->request->is('post')) {
			return $this->Message->error(__('Invalid request method, should be POST'));
		}
		// check if data was provided
		if (!isset($this->request->data['User'])) {
			return $this->Message->error(__('No data were provided'));
		}

		// set the data for validation and save
		$userData = $this->request->data;

		// If role id is not provided, we assign a default one
		if(!isset($userData['User']['role_id']) || empty($userData['User']['role_id'])) {
			$userData['User']['role_id'] = $this->User->Role->field('Role.id', array('name' => Role::USER));
		}
		// Validates user information
		$this->User->set($userData);
		$fields = $this->User->getFindFields('User::save', User::get('Role.name'));
		// check if the data is valid
		if (!$this->User->validates()) {
			return $this->Message->error(__('Could not validate user data'));
		}

		$this->User->begin();
		$user = $this->User->save($userData, false, $fields['fields']);
		if ($user == false) {
			$this->User->rollback();
			return $this->Message->error(__('The user could not be saved'));
		}

		if(isset($userData['Profile']) && !empty($userData['Profile'])) {
			// Validates profile information
			$userData['Profile']['user_id'] = $this->User->id;
			$this->User->Profile->set($userData);
			if (!$this->User->Profile->validates()) {
				return $this->Message->error(__('Could not validate profile data'));
			}

			$fields = $this->User->Profile->getFindFields('User::save', User::get('Role.name'));
			$profile = $this->User->Profile->save($userData['Profile'], false, $fields['fields']);
			if ($profile == false) {
				$this->User->rollback();
				return $this->Message->error(__('The profile could not be saved'));
			}
		}
		// Everything fine, we commit.
		$this->User->commit();

		// Send notification email.
		$this->EmailNotificator->accountCreationNotification(
			$this->User->id,
			array(
				'creator_id' => User::get('id'),
			));

		// Return data.
		$data = array('User.id' => $this->User->id);
		$options = $this->User->getFindOptions('User::view', User::get('Role.name'), $data);

		$users = $this->User->find('all', $options);

		$this->Message->success(__("The user has been saved successfully"));
		$this->set('data', $users[0]);
	}

	/**
	 * edit entry point for users
	 *
	 * @param uuid $id the id of the user we want to edit
	 */
	public function edit($id = null) {
		// First of all, check if the user is an administrator
		// Or the user is editing is own account
		if (User::get('Role.name') != Role::ADMIN && $id != User::get('id')) {
			return $this->Message->error(__('You are not authorized to access that location'));
		}

		// check if the id is provided
		if (!isset($id)) {
			return $this->Message->error(__('The user id is missing'));
		}

		// check if the id is valid
		if (!Common::isUuid($id)) {
			return $this->Message->error(__('The user id is invalid'));
		}

		// get the resource id
		$resource = $this->User->findById($id);
		if (!$resource) {
			return $this->Message->error(__('The user does not exist'), array('code' => 404));
		}

		// check the HTTP request method
		if (!$this->request->is('put')) {
			return $this->Message->error(__('Invalid request method, should be PUT'));
		}

		// check if data was provided
		if (!isset($this->request->data['User'])) {
			return $this->Message->error(__('No data were provided'));
		}

		// Set the data for validation and save.
		$userData = $this->request->data;
		// Begin transaction.
		$this->User->begin();

		// Save user.
		if (isset($userData['User'])) {
			// Manage empty password.
			// Is the current password empty ?
			$currentPasswordEmpty = isset($userData['User']['current_password'])
				&& empty($userData['User']['current_password']);
			// If no current password is provided, then we remove password and current_password field from the data.
			if ($currentPasswordEmpty) {
				unset($userData['User']['current_password']);
				unset($userData['User']['password']);
			}
			// Validates data.
			$this->User->id = $id;
			$this->User->set($userData);
			$fields = $this->User->getFindFields('User::edit', User::get('Role.name'));
			if (!$this->User->validates(array('fieldList' => array($fields['fields'])))) {
				$invalidFields = $this->User->invalidFields();
				// Format invalid fields.
				// Add 'User' index in the array.
				$finalInvalidFields = array();
				$i = 0;
				foreach($invalidFields as $key => $if) {
					$finalInvalidFields[$i++]['User'][$key] = $if;
				}
				// Return error message, with list of invalid fields.
				return $this->Message->error(__('Could not validate User'), array('body' => $finalInvalidFields));
			}
			// Save data.
			$save = $this->User->save($userData, false, $fields['fields']);
			// Didn't save, we rollback and return an error.
			if (!$save) {
				$this->User->rollback();
				return $this->Message->error(__('The user could not be updated'));
			}
		}

		// Save profile for user.
		if (isset($userData['Profile'])) {
			$profile = $this->User->Profile->findByUserId($id);
			if(!$profile) {
				$this->User->rollback();
				return $this->Message->error(__('Could not retrieve profile'));
			}
			$profile['Profile'] = array_merge($profile['Profile'], $userData['Profile']);
			// Reformat date of birth properly to pass validation
			$profile['Profile']['date_of_birth'] = date('Y-m-d', strtotime($profile['Profile']['date_of_birth']));

			$fields = $this->User->Profile->getFindFields('User::edit', User::get('Role.name'));
			$fields = Hash::expand($fields);
			$this->User->Profile->set($profile);
			if (!$this->User->Profile->validates(array('fieldList' => array($fields['fields'])))) {
				$this->User->rollback();
				return $this->Message->error(__('Could not validate Profile'));
			}

			$save = $this->User->Profile->save($profile, false, $fields['fields']);
			if (!$save) {
				$this->User->rollback();
				return $this->Message->error(__('The profile could not be updated'));
			}
		}

		$this->User->commit();

		$data = array('User.id' => $this->User->id);
		$options = $this->User->getFindOptions('User::view', User::get('Role.name'), $data);
		$users = $this->User->find('all', $options);
		$user = reset($users);

		$this->Message->success(__("The user has been updated successfully"));
		$this->set('data', $user);
	}

/**
 * edit password entry point for users
 *
 * @param uuid $id the id of the user we want to edit
 */
	public function editAvatar($id = null) {
		// check the HTTP request method
		if (!$this->request->is('post')) {
			return $this->Message->error(__('Invalid request method, should be PUT'));
		}

		// First of all, check if the user is an administrator
		// Or the user is editing is own account
		if (User::get('Role.name') != Role::ADMIN && $id != User::get('id')) {
			return $this->Message->error(__('You are not authorized to access that location'));
		}

		// check if the id is provided
		if (!isset($id)) {
			return $this->Message->error(__('The user id is missing'));
		}

		// check if the id is valid
		if (!Common::isUuid($id)) {
			return $this->Message->error(__('The user id is invalid'));
		}

		// get the resource id
		$resource = $this->User->findById($id);
		if (!$resource) {
			return $this->Message->error(__('The user does not exist'), array('code' => 404));
		}

		// check if data was provided
		if (empty($_FILES)) {
			return $this->Message->error(__('No data were provided'));
		}

		$file = reset($_FILES);
		$data = array(
			'Avatar' => array(
				'file' => $file
			)
		);

		// Retrieve the user.
		$findConditions = array('User.id' => $id);
		$options = $this->User->getFindOptions('User::view', User::get('Role.name'), $findConditions);
		$users = $this->User->find('all', $options);
		$user = reset($users);

		// Update the user avatar.
		if (!$this->User->Profile->Avatar->upload($user['Profile']['id'], $data)) {
			return $this->Message->error(__('The avatar hasn\'t been uploaded'), array('code' => 404));
		}

		// Retrieve and return the updated user.
		$data = array('User.id' => $id);
		$options = $this->User->getFindOptions('User::view', User::get('Role.name'), $data);
		$users = $this->User->find('all', $options);
		$user = reset($users);

		$this->Message->success(__("The avatar has been updated successfully"));
		$this->set('data', $user);
	}

/**
 * edit password entry point for users
 *
 * @param uuid $id the id of the user we want to edit
 */
	public function editPassword($id = null) {
		// check the HTTP request method
		if (!$this->request->is('put')) {
			return $this->Message->error(__('Invalid request method, should be PUT'));
		}

		// First of all, check if the user is an administrator
		// Or the user is editing is own account
		if (User::get('Role.name') != Role::ADMIN && $id != User::get('id')) {
			return $this->Message->error(__('You are not authorized to access that location'));
		}

		// check if the id is provided
		if (!isset($id)) {
			return $this->Message->error(__('The user id is missing'));
		}

		// check if the id is valid
		if (!Common::isUuid($id)) {
			return $this->Message->error(__('The user id is invalid'));
		}

		// get the resource id
		$resource = $this->User->findById($id);
		if (!$resource) {
			return $this->Message->error(__('The user does not exist'), array('code' => 404));
		}

		// check if data was provided
		if (!isset($this->request->data['User'])) {
			return $this->Message->error(__('No data were provided'));
		}

		// set the data for validation and save
		$userData = $this->request->data;
		$this->User->begin();

		if (isset($userData['User'])) {
			$this->User->id = $id;

			$this->User->set($userData);
			$this->User->setValidationRules('editPassword');
			if (!$this->User->validates()) {
				return $this->Message->error(__('Could not validate User'));
			}

			$fields = $this->User->getFindFields('User::editPassword', User::get('Role.name'));
			$save = $this->User->save($userData, false, $fields['fields']);
			if (!$save) {
				$this->User->rollback();
				return $this->Message->error(__('The user could not be updated'));
			}
		}

		$this->User->commit();

		$data = array('User.id' => $this->User->id);
		$options = $this->User->getFindOptions('User::view', User::get('Role.name'), $data);
		$user = $this->User->find('all', $options);

		$this->Message->success(__("The password has been updated successfully"));
		$this->set('data', $user);
	}

/**
 * Delete a user
 *
 * @param uuid id the id of the user to delete
 */
	public function delete($id = null) {
		// First of all, check if the user is an administrator
		if (User::get('Role.name') != Role::ADMIN) {
			return $this->Message->error(__('You are not authorized to access that location'));
		}

		// check if the category id is provided
		if (!isset($id)) {
			return $this->Message->error(__('The user id is missing'));
		}
		
		// check if the id is valid
		if (!Common::isUuid($id)) {
			return $this->Message->error(__('The user id is invalid'));
		}

		if (User::get('id') == $id) {
			return $this->Message->error(__('You are not allowed to delete yourself'));
		}
		
		$user = $this->User->findById($id);
		if (!$user) {
			return $this->Message->error(__('The user does not exist'), array('code' => 404));
		}

		$this->User->id = $id;
		$user['User']['deleted'] = true;

		$fields = $this->User->getFindFields('User::delete', User::get('Role.name'));
		$this->User->begin();
		if (!$this->User->save($user, true, $fields['fields'])) {
			$this->User->rollback();
			return $this->Message->error(__('Error while deleting user'));
		}
		$this->User->commit();
		$this->Message->success(__('The user was sucessfully deleted'));
	}

}

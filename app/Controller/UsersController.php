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
 * beforeFilter
 */
	function beforeFilter(){
		parent::beforeFilter();
		$allow = array(
			'validateAccount'
		);
		if (Configure::read('Registration.public')) {
			$allow[] = 'register';
			$allow[] = 'register_thankyou';
		}
		$this->Auth->allow($allow);
	}

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
			return $this->redirect($this->Auth->redirectUrl());
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
 * Register page.
 */
	public function register() {
		$this->layout = 'login';
		// if data is provided.
		if (!empty ($this->request->data)) {
			$userData = $this->request->data;
			try {
				$this->__add($userData);
			}
			catch (ValidationException $e) {
				return;
			}

			// Redirect to thank you page.
			$this->redirect("/register/thankyou");
			return;
		}
	}

	/**
	 * Add a user to the app, create a token and send a notification.
	 * @param $data
	 *   user and profile data.
	 *
	 * @return array
	 *   a user object
	 *
	 * @throws Exception
	 * @throws ValidationException
	 */
	private function __add($data) {
		// Save user data.
		$user = $this->User->__add($data);

		// Send notification email.
		$this->EmailNotificator->accountCreationNotification(
			$user['Profile']['user_id'],
			array(
				'token' => $user['AuthenticationToken']['token'],
				'creator_id' => User::get('id'),
			));
	}

/**
 * Thank you page after registration.
 */
	public function register_thankyou() {
		// Check referer.
		$referer = $this->referer();
		// If no referer, we redirect to register page.
		if (empty($referer)) {
			$this->redirect("/register");
			return;
		}
		$url = parse_url($referer);
		// If the referer was not the register url, we also redirect to /register page.
		if (!isset($url['path']) || empty($url['path']) || $url['path'] !== '/register') {
			$this->redirect("/register");
			return;
		}
		$this->layout = 'login';
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

		try {
			$this->__add($userData);
		}
		catch (ValidationException $ve) {
			return $this->Message->error(__('Could not validate profile'), array('body' => $ve->getInvalidFields()));
		}
		catch (Exception $e) {
			return $this->Message->error($e->getMessage());
		}

		// Return data.
		$data = array(
			'User.id' => $this->User->id,
			'User.active' => FALSE,
		);
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
			// if password field is present but empty, we simply ignore it.
			if (isset($userData['User']['password']) && empty($userData['User']['password'])) {
				unset($userData['User']['password']);
			}
			// If the password is provided.
			if (isset($userData['User']['password'])) {
				// Manage password fields depending on roles and data provided.
				// Is user's own password.
				$isOwn = ($id == User::get('id'));
				// Is current password required.
				$currentPasswordRequired = $isOwn;
				// Is current password provided.
				$currentPasswordProvided = isset($userData['User']['current_password'])
					&& !empty($userData['User']['current_password']);
				// If no current password is provided, then we return an error.
				if ($currentPasswordRequired && !$currentPasswordProvided) {
					$finalInvalidFields = Common::formatInvalidFields('User', array('current_password' => array(__("Current password is required"))));
					return $this->Message->error(__('Current Password must be provided'), array('body' => $finalInvalidFields));
				}
			}

			// Validates data.
			$this->User->set($userData);
			$this->User->id = $id;
			$fields = $this->User->getFindFields('User::edit', User::get('Role.name'));
			if (!$this->User->validates(array('fieldList' => array($fields['fields'])))) {
				$invalidFields = $this->User->validationErrors;
				$finalInvalidFields = Common::formatInvalidFields('User', $invalidFields);
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
			$profile['Profile'] = $userData['Profile'];
			if (isset($profile['Profile']['date_of_birth'])) {
				// Reformat date of birth properly to pass validation
				$profile['Profile']['date_of_birth'] = date('Y-m-d', strtotime($profile['Profile']['date_of_birth']));
			}

			$fields = $this->User->Profile->getFindFields('User::edit', User::get('Role.name'));
			$fields = Hash::expand($fields);
			$this->User->Profile->set($profile);
			if (!$this->User->Profile->validates(array('fieldList' => array($fields['fields'])))) {
				$this->User->rollback();
				$invalidFields = $this->User->Profile->validationErrors;
				$finalInvalidFields = Common::formatInvalidFields('Profile', $invalidFields);
				return $this->Message->error(__('Could not validate Profile'), array('body' => $finalInvalidFields));
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
 * edit avatar entry point for users
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

// @todo cleanup after #PASSBOLT-360
///**
// * edit password entry point for users
// *
// * @param uuid $id the id of the user we want to edit
// */
//	public function editPassword($id = null) {
//		// check the HTTP request method
//		if (!$this->request->is('put')) {
//			return $this->Message->error(__('Invalid request method, should be PUT'));
//		}
//
//		// First of all, check if the user is an administrator
//		// Or the user is editing is own account
//		if (User::get('Role.name') != Role::ADMIN && $id != User::get('id')) {
//			return $this->Message->error(__('You are not authorized to access that location'));
//		}
//
//		// check if the id is provided
//		if (!isset($id)) {
//			return $this->Message->error(__('The user id is missing'));
//		}
//
//		// check if the id is valid
//		if (!Common::isUuid($id)) {
//			return $this->Message->error(__('The user id is invalid'));
//		}
//
//		// get the resource id
//		$resource = $this->User->findById($id);
//		if (!$resource) {
//			return $this->Message->error(__('The user does not exist'), array('code' => 404));
//		}
//
//		// check if data was provided
//		if (!isset($this->request->data['User'])) {
//			return $this->Message->error(__('No data were provided'));
//		}
//
//		// set the data for validation and save
//		$userData = $this->request->data;
//		$this->User->begin();
//
//		if (isset($userData['User'])) {
//			$this->User->id = $id;
//
//			$this->User->set($userData);
//			$this->User->setValidationRules('editPassword');
//			if (!$this->User->validates()) {
//				return $this->Message->error(__('Could not validate User'));
//			}
//
//			$fields = $this->User->getFindFields('User::editPassword', User::get('Role.name'));
//			$save = $this->User->save($userData, false, $fields['fields']);
//			if (!$save) {
//				$this->User->rollback();
//				return $this->Message->error(__('The user could not be updated'));
//			}
//		}
//
//		$this->User->commit();
//
//		$data = array('User.id' => $this->User->id);
//		$options = $this->User->getFindOptions('User::view', User::get('Role.name'), $data);
//		$user = $this->User->find('all', $options);
//
//		$this->Message->success(__("The password has been updated successfully"));
//		$this->set('data', $user);
//	}

	/**
	 * Validate a user account.
	 * @param uuid $id the user id
	 * @method PUT
	 *  @param AuthenticationToken['token'] (compulsory) the token
	 *  @param Profile['first_name'] (optional) the first_name
	 *  @param Profile['last_name'] (optional) the last_name
	 *  @param Gpgkey['key'] (optional) the key
	 */
	public function validateAccount($id) {
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: PUT, OPTIONS');

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
		$data = $this->request->data;
		if (!isset($data['AuthenticationToken'])) {
			return $this->Message->error(__('No data were provided'));
		}

		// Set the data for validation and save.
		if (!isset($data['AuthenticationToken']['token'])) {
			return $this->Message->error(__('Token not provided') . 'test' . print_r($this->request, true));
		}

		// Check that token is valid.
		$isValid = $this->User->AuthenticationToken->checkTokenIsValid($data['AuthenticationToken']['token'], $id);
		if (!$isValid) {
			return $this->Message->error(__('Invalid token'));
		}

		// Token is valid, we begin transaction.
		$this->User->begin();

		// Activate user.
		$this->User->id = $id;
		$s = $this->User->saveField('active', TRUE);
		if (!$s) {
			$this->User->rollback();
			return $this->Message->error(__('Could not update user'));
		}

		// Deactivate Token.
		$this->User->AuthenticationToken->id = $isValid['AuthenticationToken']['id'];
		$s = $this->User->AuthenticationToken->saveField('active', FALSE);
		if (!$s) {
			$this->User->rollback();
			return $this->Message->error(__('Could not update token'));
		}

		// If Profile information are provided, we update.
		if (isset($data['Profile'])) {
			$profileData = $data['Profile'];
			// Get user profile (for profile_id).
			$profile = $this->User->Profile->findByUserId($id);
			// Get fields.
			$fields = $this->User->getFindFields('User::validateAccount');
			// Set profile id.
			$this->User->Profile->id = $profile['Profile']['id'];
			// Validate Profile data.
			$this->User->Profile->set($profileData);
			$v = $this->User->Profile->validates(array('fieldList' => array($fields['fields'])));
			// If validation failed.
			if (!$v) {
				$this->User->rollback();
				$invalidFields = $this->User->Profile->validationErrors;
				$finalInvalidFields = Common::formatInvalidFields('Profile', $invalidFields);
				return $this->Message->error(__('Could not validate Profile'), array('body' => $finalInvalidFields));
			}
			// Save (update) profile.
			$s = $this->User->Profile->save($profileData, false, array('fieldList' => $fields['fields']));
			// If update failed.
			if (!$s) {
				$this->User->rollback();
				return $this->Message->error(__('Could not save Profile'));
			}
		}

		// If User information are provided, we update.
		if (isset($data['User'])) {
			$userData = $data['User'];
			// Get fields.
			$fields = $this->User->getFindFields('User::validateAccount');
			// Set user id.
			$this->User->id = $id;
			// Validate User data.
			$this->User->set($userData);
			$v = $this->User->validates(array('fieldList' => array($fields['fields'])));
			// If validation failed.
			if (!$v) {
				$this->User->rollback();
				$invalidFields = $this->User->validationErrors;
				$finalInvalidFields = Common::formatInvalidFields('User', $invalidFields);
				return $this->Message->error(__('Could not validate User'), array('body' => $finalInvalidFields));
			}
			// Save (update) profile.
			$s = $this->User->save($userData, false, array('fieldList' => $fields['fields']));
			// If update failed.
			if (!$s) {
				$this->User->rollback();
				return $this->Message->error(__('Could not save User'));
			}
		}

		// If User information are provided, we update.
		if (isset($data['Gpgkey'])) {
			$gpgkeyData = $data['Gpgkey'];

			// Extract data from the key.
			$gpgkeyData = $this->User->Gpgkey->buildGpgkeyDataFromKey($gpgkeyData['key']);
			if ($gpgkeyData == false) {
				$this->User->rollback();
				return $this->Message->error(__('The key provided couldn\'t be used'));
			}

			// Set actual user id.
			$gpgkeyData['Gpgkey']['user_id'] = $id;

			// Set data.
			$this->User->Gpgkey->set($gpgkeyData);

			// Get fields.
			$fields = $this->User->getFindFields('User::validateAccount');

			// Check if the data is valid.
			if (!$this->User->Gpgkey->validates(array('fieldList' => array($fields['fields'])))) {
				$this->User->Gpgkey->rollback();
				$this->Message->error(__('Could not validate gpgkey data'));
				return;
			}
			// Save the key.
			$this->User->Gpgkey->create();
			$gpgkey= $this->User->Gpgkey->save($gpgkeyData, false, array('fieldList' => $fields['fields']));

			// If saving the key failed.
			if (!$gpgkey) {
				$this->User->Gpgkey->rollback();
				$this->Message->error(__('The gpgkey could not be saved'));
				return;
			}
		}

        // Everything ok, we commit the transaction.
		$this->User->commit();

		// Return information in case of success.
		$data = array('User.id' => $id);
		$options = $this->User->getFindOptions('User::view', User::get('Role.name'), $data);
		$user = $this->User->find('first', $options);

		$this->Message->success(__("The user has been updated successfully"));
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
		$this->Message->success(__('The user was successfully deleted'));
	}

}

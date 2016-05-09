<?php
/**
 * Users Controller
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class UsersController extends AppController {

/**
 * @var array helpers used by the view when rendering from this controller
 */
	public $helpers = ['PassboltAuth'];

/**
 * @var array components used by this controller
 */
	public $components = [
		'Filter',
		'EmailNotificator',
	];

/**
 * Called before the controller action. Used to manage access right
 *
 * @return void
 * @link http://book.cakephp.org/2.0/en/controllers.html#request-life-cycle-callbacks
 */
	public function beforeFilter() {
		$allow = [
			'validateAccount'
		];
		if (Configure::read('Registration.public')) {
			$allow[] = 'register';
			$allow[] = 'register_thankyou';
		}
		$this->Auth->allow($allow);
		parent::beforeFilter();
	}

/**
 * Helper function to help with account registration.
 *
 * Create the user, its associated profile and an Authentication token to allow the user
 * to access the setup and finalize the registration process.
 *
 * Notify the user by email.
 *
 * @param array $data User and profile data.
 * @param bool $self . whether it's a self registration.
 *
 * @return array the created user
 */
	private function __registerUser($data, $self = false) {
		// Save user data
		$user = $this->User->registerUser($data);

		// Send notification email
		$this->EmailNotificator->accountCreationNotification(
			$user['Profile']['user_id'],
			[
				'token' => $user['AuthenticationToken']['token'],
				'creator_id' => User::get('id'),
				'self' => $self,
			]);
	}

/**
 * Register page
 *
 * @throws ValidationException
 * @return void
 */
	public function register() {
		$this->layout = 'login';

		// if data is provided.
		if (!empty($this->request->data)) {
			$userData = $this->request->data;
			try {
				$this->__registerUser($userData, true);
			} catch (ValidationException $e) {
				// we do not want CakeErrorController to handle the validation error
				// if the request is not in JSON format
				// since we need to render the validation error in the original view/layout
				if ($this->request->is('json')) {
					throw $e;
				}
				return;
			}
			// Redirect to thank you page.
			$this->redirect('/register/thankyou');
		}
	}

/**
 * Thank you page after registration
 *
 * @return void
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
 * Get all users
 * Renders a json object of the users
 *
 * @return void
 */
	public function index() {
		// The additional information to pass to the model request
		$data = [];

		// Extract the filter from the request
		$data = $this->Filter->fromRequest($this->request->query);

		// If a filter by group is provided
		if (isset($data['foreignModels']['Group.id'])) {
			// Check the validity of each provided group
			foreach ($data['foreignModels']['Group.id'] as $groupId) {
				// Check if the group id is valid
				if (!Common::isUuid($groupId)) {
					return $this->Message->error(__('The group id is invalid'));
				}
				// Check if the group exists
				if (!$this->User->Group->findById($groupId)) {
					return $this->Message->error(__('The group doesn\'t exist', $groupId));
				}
			}
		}

		// Find the users.
		$o = $this->User->getFindOptions('User::index', User::get('Role.name'), $data);
		$users = $this->User->find('all', $o);

		$this->set('data', $users);
		$this->Message->success();
	}

/**
 * Get a user
 * Renders a json object of the user
 *
 * @param string $id UUID of the user
 * @return void
 */
	public function view($id = null) {
		// The additional information to pass to the model request
		$data = [];

		// Asking for me returns the currently logged-in user
		if ($id == 'me') {
			$id = User::get('id');
		}

		// Check if the id is provided
		if (!isset($id)) {
			return $this->Message->error(__('The user id is missing'));
		}
		// Check if the id is valid
		if (!Common::isUuid($id)) {
			return $this->Message->error(__('The user id is invalid'));
		}

		// Retrieve the user
		$data['User.id'] = $id;
		$o = $this->User->getFindOptions('User::view', User::get('Role.name'), $data);
		$user = $this->User->find('first', $o);
		if (!$user) {
			return $this->Message->error(__('The user does not exist'), ['code' => 404]);
		}

		$this->set('data', $user);
		$this->Message->success();
	}

/**
 * Add a user
 *
 * @return void
 */
	public function add() {
		// Only admin users can access this entry point
		if (User::get('Role.name') != Role::ADMIN) {
			return $this->Message->error(__('You are not authorized to access that location'));
		}

		// Check the HTTP request method
		if (!$this->request->is('post')) {
			return $this->Message->error(__('Invalid request method, should be POST'));
		}
		// Check if data was provided
		if (!isset($this->request->data['User'])) {
			return $this->Message->error(__('No data were provided'));
		}

		// Set the data for validation and save
		$userData = $this->request->data;

		// Try to add the user
		try {
			$this->__registerUser($userData);
		} // Something went wrong with the validation
		catch (ValidationException $e) {
			return $this->Message->error(__('Could not validate profile'), [
				'body' => $e->getInvalidFields()
			]);
		} // Something else went wrong
		catch (Exception $e) {
			return $this->Message->error($e->getMessage());
		}

		// Retrieve the just inserted user
		$data = [
			'User.id' => $this->User->id,
			'User.active' => false,
		];
		$options = $this->User->getFindOptions('User::view', User::get('Role.name'), $data);
		$user = $this->User->find('first', $options);

		$this->Message->success(__("The user has been saved successfully"));
		$this->set('data', $user);
	}

/**
 * Update a user
 *
 * @param string $id the uuid of the user we want to edit
 * @return void
 */
	public function edit($id = null) {
		// Only admin users can update users
		// Except user editing their own account
		if (User::get('Role.name') != Role::ADMIN && $id != User::get('id')) {
			return $this->Message->error(__('You are not authorized to access that location'));
		}

		// Check if the id is provided
		if (!isset($id)) {
			return $this->Message->error(__('The user id is missing'));
		}

		// Check if the id is valid
		if (!Common::isUuid($id)) {
			return $this->Message->error(__('The user id is invalid'));
		}

		// The user exists
		$o = $this->User->getFindOptions('User::view', User::get('Role.name'), ['User.id' => $id]);
		$user = $this->User->find('first', $o);
		if (!$user) {
			return $this->Message->error(__('The user does not exist'), ['code' => 404]);
		}

		// Check the HTTP request method
		if (!$this->request->is('put')) {
			return $this->Message->error(__('Invalid request method, should be PUT'));
		}

		// Check if data was provided
		if (!isset($this->request->data['User'])) {
			return $this->Message->error(__('No data were provided'));
		}

		// Begin transaction
		$this->User->begin();

		// Set the data for validation and save
		$userData = $this->request->data;

		// Use the url id parameter as User id
		$userData['User']['id'] = $id;

		$editOwn = $id == User::get('id');

		// Update the user, only if not editing itself.
		if (isset($userData['User']) && !$editOwn) {
			// Get the meaningful fields for this operation
			$fields = $this->User->getFindFields('User::edit', User::get('Role.name'));

			// Validate the user data
			$this->User->set($userData);

			if (!$this->User->validates(['fieldList' => [$fields['fields']]])) {
				// Return error message, with list of invalid fields.
				$this->Message->error(__('Could not validate User'), ['body' => $this->User->validationErrors]);

				return;
			}

			// Update the user
			$save = $this->User->save($userData, false, $fields['fields']);
			// Didn't save, we rollback and return an error.
			if (!$save) {
				$this->User->rollback();
				return $this->Message->error(__('The user could not be updated'));
			}
		}

		// Update the user profile
		if (isset($userData['Profile'])) {

			// Retrieve the profile associated to the user account
			$profile = $this->User->Profile->findByUserId($id);

			// If no profile associated to the user found
			if (!$profile) {
				$this->User->rollback();
				return $this->Message->error(__('Could not retrieve profile'));
			}

			// Extract the profile data from the request user data
			$profileData['Profile'] = $userData['Profile'];

			// Force the profile to be updated to be the one associated to the user
			$profileData['Profile']['id'] = $profile['Profile']['id'];

			// Reformat date of birth properly to pass validation
			if (isset($profileData['Profile']['date_of_birth'])) {
				$profileData['Profile']['date_of_birth'] = date('Y-m-d',
					strtotime($profileData['Profile']['date_of_birth']));
			}

			// Get the meaningful fields for this operation
			$fields = $this->User->Profile->getFindFields('User::edit', User::get('Role.name'));
			$fields = Hash::expand($fields);

			// Set the data for validation and save
			$this->User->Profile->set($profileData);

			// Validate the profile data
			if (!$this->User->Profile->validates(['fieldList' => [$fields['fields']]])) {
				$this->User->rollback();

				return $this->Message->error(__('Could not validate Profile'),
					['body' => $this->User->Profile->validationErrors]);
			}

			// Update the profile
			$save = $this->User->Profile->save($profileData, false, $fields['fields']);
			if (!$save) {
				$this->User->rollback();
				return $this->Message->error(__('The profile could not be updated'));
			}
		}

		// Everything went fine, commit the changes
		$this->User->commit();

		// Retrieve the just updated user
		$data = ['User.id' => $this->User->id];
		$options = $this->User->getFindOptions('User::view', User::get('Role.name'), $data);
		$user = $this->User->find('first', $options);

		$this->Message->success(__("The user has been updated successfully"));
		$this->set('data', $user);
	}

/**
 * edit avatar entry point for users
 *
 * @param string $id the uuid of the user we want to edit
 * @return void
 */
	public function editAvatar($id = null) {
		// Check the HTTP request method
		if (!$this->request->is('post')) {
			return $this->Message->error(__('Invalid request method, should be PUT'));
		}

		// Only admin users can update users avatars
		// Except user editing their own avatar
		if (User::get('Role.name') != Role::ADMIN && $id != User::get('id')) {
			return $this->Message->error(__('You are not authorized to access that location'));
		}

		// Check if the id is provided
		if (!isset($id)) {
			return $this->Message->error(__('The user id is missing'));
		}

		// Check if the id is valid
		if (!Common::isUuid($id)) {
			return $this->Message->error(__('The user id is invalid'));
		}

		// Check the user exists
		$o = $this->User->getFindOptions('User::view', User::get('Role.name'), ['User.id' => $id]);
		$user = $this->User->find('first', $o);
		if (!$user) {
			return $this->Message->error(__('The user does not exist'), ['code' => 404]);
		}

		// Check if data was provided
		if (empty($_FILES)) {
			return $this->Message->error(__('No data were provided'));
		}

		$file = reset($_FILES);
		$data = [
			'Avatar' => [
				'file' => $file
			]
		];

		// Retrieve the user.
		$findConditions = ['User.id' => $id];
		$options = $this->User->getFindOptions('User::view', User::get('Role.name'), $findConditions);
		$user = $this->User->find('first', $options);

		// Update the user avatar.
		try {
			$this->User->Profile->Avatar->upload($user['Profile']['id'], $data);
		}
		catch (ValidationException $ve) {
			return $this->Message->error($ve->getMessage(), ['body' => ['User' => ['Profile' => $ve->getInvalidFields()]]]);
		}
		catch (Exception $e) {
			return $this->Message->error(__('The avatar couldn\'t be uploaded'), ['code' => 404]);
		}


		// Retrieve and return the updated user.
		$data = ['User.id' => $id];
		$options = $this->User->getFindOptions('User::view', User::get('Role.name'), $data);
		$users = $this->User->find('all', $options);
		$user = reset($users);

		$this->Message->success(__("The avatar has been updated successfully"));
		$this->set('data', $user);
	}

/**
 * Validate a user account.
 *
 * Put parameters:
 * AuthenticationToken['token'] (compulsory) the token
 * Profile['first_name'] (optional) the first_name
 * Profile['last_name'] (optional) the last_name
 * Gpgkey['key'] (optional) the key
 *
 * @param uuid $id the user id
 * @method PUT
 * @return void
 */
	public function validateAccount($id = null) {
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: PUT, OPTIONS');

		// check the HTTP request method
		if (!$this->request->is('put')) {
			return $this->Message->error(__('Invalid request method, should be PUT'));
		}

		// Check if the id is provided
		if (!isset($id)) {
			return $this->Message->error(__('The user id is missing'));
		}

		// Check if the id is valid
		if (!Common::isUuid($id)) {
			return $this->Message->error(__('The user id is invalid'));
		}

		// Get the resource
		$user = $this->User->findById($id);
		if (!$user) {
			return $this->Message->error(__('The user does not exist'), ['code' => 404]);
		}

		// Store request data in data
		$data = $this->request->data;

		if (!isset($data['AuthenticationToken'])) {
			return $this->Message->error(__('No data were provided'));
		}

		// Set the data for validation and save
		if (!isset($data['AuthenticationToken']['token'])) {
			return $this->Message->error(__('Token not provided'));
		}

		// Check that the token exists
		$authToken = $this->User->AuthenticationToken->findFirstByToken($data['AuthenticationToken']['token']);
		if (empty($authToken)) {
			return $this->Message->error(__('Invalid token'));
		}

		// Check that token is not expired
		$isNotExpiredToken = $this->User->AuthenticationToken->isNotExpired($data['AuthenticationToken']['token']);
		if (!$isNotExpiredToken) {
			return $this->Message->error(__('Expired token'));
		}

		// Check that token is valid
		$isValidToken = $this->User->AuthenticationToken->isValid($data['AuthenticationToken']['token'], $id);
		if (!$isValidToken) {
			return $this->Message->error(__('Invalid token'));
		}

		// Token is valid, we begin transaction
		$dataSource = $this->User->getDataSource();
		$dataSource->begin();

		// Activate user
		$this->User->id = $id;
		$result = $this->User->saveField('active', true, ['atomic' => false]);
		if (!$result) {
			$dataSource->rollback();
			return $this->Message->error(__('Could not update user'));
		}

		// Deactivate Token.
		$result = AuthenticationToken::setInactive($data['AuthenticationToken']['token'], $id);
		if (!$result) {
			$dataSource->rollback();
			return $this->Message->error(__('Could not update token'));
		}

		// If Profile data are provided, we update
		if (isset($data['Profile'])) {
			$profileData = $data['Profile'];
			// Get user profile (for profile_id)
			$profile = $this->User->Profile->findByUserId($id);
			// Get meaningful fields for this operation
			$fields = $this->User->getFindFields('User::validateAccount');

			// Set the data for validation and save
			$this->User->Profile->id = $profile['Profile']['id'];
			$this->User->Profile->set($profileData);

			// Validate the profile data
			if (!$this->User->Profile->validates(['fieldList' => [$fields['fields']]])) {
				$dataSource->rollback();
				return $this->Message->error(__('Could not validate Profile'), array('body' => $this->User->Profile->validationErrors));
			}

			// Save/Update the profile
			$result = $this->User->Profile->save($profileData, false, ['fieldList' => $fields['fields']]);
			// If update failed
			if (!$result) {
				$dataSource->rollback();
				return $this->Message->error(__('Could not save Profile'));
			}
		}

		// If User data are provided, we update
		if (isset($data['Gpgkey'])) {
			$gpgkeyData = $data['Gpgkey'];

			// Extract data from the key
			$gpgkeyData = $this->User->Gpgkey->buildGpgkeyDataFromKey($gpgkeyData['key']);
			if ($gpgkeyData == false) {
				$dataSource->rollback();
				return $this->Message->error(__('The key provided couldn\'t be used'));
			}

			// Set actual user id
			$gpgkeyData['Gpgkey']['user_id'] = $id;
			// Set data.
			$this->User->Gpgkey->set($gpgkeyData);

			// Get fields
			$fields = $this->User->getFindFields('User::validateAccount');

			// Check if the data is valid
			if (!$this->User->Gpgkey->validates(['fieldList' => [$fields['fields']]])) {
				$dataSource->rollback();
				return $this->Message->error(__('Could not validate gpgkey data'),
					['body' => $this->User->Gpgkey->validationErrors]);
			}
			// Save the key
			$this->User->Gpgkey->create();
			$gpgkey = $this->User->Gpgkey->save($gpgkeyData, false, ['fieldList' => $fields['fields']]);

			// If saving the key failed
			if (!$gpgkey) {
				$dataSource->rollback();
				$this->Message->error(__('The gpgkey could not be saved'));
				return;
			}
		}

		// Everything ok, we commit the transaction.
		$dataSource->commit();

		// Return information in case of success.
		$data = ['User.id' => $id];
		$options = $this->User->getFindOptions('User::view', User::get('Role.name'), $data);
		$user = $this->User->find('first', $options);

		$this->Message->success(__("The user has been updated successfully"));
		$this->set('data', $user);
	}

/**
 * Delete a user
 *
 * @param string $id the uuid of the user to delete
 * @return void
 */
	public function delete($id = null) {
		// Only admin users can delete users
		if (User::get('Role.name') != Role::ADMIN) {
			return $this->Message->error(__('You are not authorized to access that location'));
		}

		// Check if the user id is provided
		if (!isset($id)) {
			return $this->Message->error(__('The user id is missing'));
		}

		// Check if the id is valid
		if (!Common::isUuid($id)) {
			return $this->Message->error(__('The user id is invalid'));
		}

		// Cannot delete self
		if (User::get('id') == $id) {
			return $this->Message->error(__('You are not allowed to delete yourself'));
		}

		// The user exists
		$o = $this->User->getFindOptions('User::view', User::get('Role.name'), ['User.id' => $id]);
		$user = $this->User->find('first', $o);
		if (!$user) {
			return $this->Message->error(__('The user does not exist'), ['code' => 404]);
		}

		// Soft delete the user
		try {
			$this->User->softDelete($id);
		} catch(Exception $e) {
			return $this->Message->error(__('Error while deleting user'));
		}

		// Everything went fine, commit the changes
		$this->Message->success(__('The user was successfully deleted'));
	}

}

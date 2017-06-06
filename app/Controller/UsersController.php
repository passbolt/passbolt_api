<?php
/**
 * Users Controller
 *
 * @copyright (c) 2015-2016 Bolt Softwares Pvt Ltd
 *                2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class UsersController extends AppController {

/**
 * @var array components used by this controller
 */
	public $components = [
		'QueryString',
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
		if (Configure::read('App.registration.public')) {
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
 * @param bool $self is it a self registration.
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
			return;
		}
	}

/**
 * Thank you page after registration
 *
 * @return void
 */
	public function register_thankyou() {
		// If no referer, we redirect to register page.
		$referer = $this->referer();
		if (empty($referer)) {
			$this->redirect("/register");
			return;
		}

		$url = parse_url($referer);
		// If the referer was not the register url, we also redirect to /register page.
		if (!isset($url['path']) || empty($url['path']) || $url['path'] !== Router::url('/register')) {
			$this->redirect("/register");
			return;
		}

		$this->layout = 'login';
	}

/**
 * Get all users
 * Renders a json object of the users.
 *
 * @throws MethodNotAllowedException if http request method is not GET
 * @return void
 */
	public function index() {
		// Check request sanity
		if (!$this->request->is('get')) {
			throw new MethodNotAllowedException(__('Invalid request method, should be GET.'));
		}

		// Allowed query filters.
		$allowedQueryFilters = ['keywords', 'has-groups'];

		// Admin is allowed to use the filter is-active
		if (User::isAdmin()) {
			$allowedQueryFilters[] = 'is-active';
		}

		// Extract parameters from query string
		$allowedQueryItems = [
			'filter' => $allowedQueryFilters,
			'order' => $this->User->getFindAllowedOrder('UsersController::index'),
		];
		$params = $this->QueryString->get($allowedQueryItems);

		// Find the users.
		$o = $this->User->getFindOptions('User::index', User::get('Role.name'), $params);
		$users = $this->User->find('all', $o);

		$this->set('data', $users);
		$this->Message->success();
	}

/**
 * Get a user
 * Renders a json object of the user
 *
 * @param string $id UUID of the user
 * @throws MethodNotAllowedException if http request method is not GET
 * @throws BadRequestException if the user id is missing or invalid
 * @throws NotFoundException if the user does not exist
 * @return void
 */
	public function view($id = null) {
		// Check request sanity
		if (!$this->request->is('get')) {
			throw new MethodNotAllowedException(__('Invalid request method, should be GET.'));
		}
		if (!isset($id)) {
			throw new BadRequestException(__('The user id is missing.'));
		}
		if ($id === 'me') {
			$id = User::get('id'); // me returns the currently logged-in user
		}
		if (!Common::isUuid($id)) {
			throw new BadRequestException(__('The user id is not valid.'));
		}

		// Retrieve the user
		$data = [];
		$data['User.id'] = $id;
		$o = $this->User->getFindOptions('User::view', User::get('Role.name'), $data);
		$user = $this->User->find('first', $o);
		if (!$user) {
			throw new NotFoundException(__('The user does not exist.'));
		}

		$this->set('data', $user);
		$this->Message->success();
	}

/**
 * Add a user
 *
 * @throws MethodNotAllowedException if http request method is not GET
 * @throws ForbiddenException if the user role is not admin
 * @throws BadRequestException if no user data is provided
 * @throws BadRequestException if the profile data can not be validated
 * @return void
 */
	public function add() {
		// Check request sanity
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException(__('Invalid request method, should be POST.'));
		}
		if (User::get('Role.name') != Role::ADMIN) {
			throw new ForbiddenException(__('You are not authorized to access that location.'));
		}
		if (!isset($this->request->data['User'])) {
			throw new BadRequestException(__('No user data was provided.'));
		}

		// Set the data for validation and try to save
		$userData = $this->request->data;
		try {
			$this->__registerUser($userData);
		} catch (ValidationException $e) {
			throw new ValidationException(__('Could not validate user profile data.'), $e->getInvalidFields());
		} catch (Exception $e) {
			throw new BadRequestException($e->getMessage());
		}

		// Retrieve the just inserted user
		$data = [
			'User.id' => $this->User->id,
			'User.active' => false,
		];
		$options = $this->User->getFindOptions('User::view', User::get('Role.name'), $data);
		$user = $this->User->find('first', $options);

		$this->set('data', $user);
		$this->Message->success(__('The user has been saved successfully.'));
	}

/**
 * Update a user
 *
 * @param string $id the uuid of the user we want to edit
 * @return void
 */
	public function edit($id = null) {
		// Check request sanity
		if (!$this->request->is('put')) {
			throw new BadRequestException(__('Invalid request method, should be PUT'));
		}
		if (User::get('Role.name') != Role::ADMIN && $id != User::get('id')) {
			// Only admin users can update users
			// Except user editing their own account
			throw new ForbiddenException(__('You are not authorized to access that location.'));
		}
		if (!isset($id)) {
			throw new BadRequestException(__('The user id is missing.'));
		}
		if (!Common::isUuid($id)) {
			throw new BadRequestException(__('The user id is not valid.'));
		}
		if (!isset($this->request->data['User']) && !isset($this->request->data['Profile'])) {
			throw new BadRequestException(__('No user data was provided.'));
		}

		// The user exists
		$options = $this->User->getFindOptions('User::view', User::get('Role.name'), ['User.id' => $id]);
		$user = $this->User->find('first', $options);
		if (!$user) {
			throw new NotFoundException(__('The user does not exist.'));
		}

		// Set the data for validation and save
		$this->User->begin();
		$userData = $this->request->data;

		// Use the url id parameter as User id
		$userData['User']['id'] = $id;
		$editOwn = ($id == User::get('id'));

		// Update the user, only if not editing itself.
		if (isset($userData['User']) && !$editOwn) {
			// Get the meaningful fields for this operation
			$fields = $this->User->getFindFields('User::edit', User::get('Role.name'));

			// Validate the user data
			$this->User->set($userData);

			if (!$this->User->validates(['fieldList' => [$fields['fields']]])) {
				// Return error message, with list of invalid fields.
				throw new ValidationException(__('Could not validate User'), $this->User->validationErrors);
			}

			// Update the user
			$save = $this->User->save($userData, false, $fields['fields']);
			// Didn't save, we rollback and return an error.
			if (!$save) {
				$this->User->rollback();
				throw new InternalErrorException(__('The user could not be updated.'));
			}
		}

		// Update the user profile
		if (isset($userData['Profile'])) {

			// Retrieve the profile associated to the user account
			$profile = $this->User->Profile->findByUserId($id);

			// If no profile associated to the user found
			if (!$profile) {
				$this->User->rollback();
				throw new InternalErrorException(__('Could not retrieve profile'));
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
			if (!$this->User->Profile->validates(['fieldList' => [$fields['fields']]])) {
				$this->User->rollback();
				throw new ValidationException(__('Could not validate Profile'), $this->User->validationErrors);
			}

			// Update the profile
			$save = $this->User->Profile->save($profileData, false, $fields['fields']);
			if (!$save) {
				$this->User->rollback();
				throw new InternalErrorException(__('The profile could not be updated'));
			}
		}

		// Everything went fine, commit the changes
		$this->User->commit();

		// Retrieve the just updated user
		$data = ['User.id' => $this->User->id];
		$options = $this->User->getFindOptions('User::view', User::get('Role.name'), $data);
		$user = $this->User->find('first', $options);

		$this->set('data', $user);
		$this->Message->success(__("The user has been updated successfully"));
	}

/**
 * edit avatar entry point for users
 *
 * @param string $id the uuid of the user we want to edit
 * @return void
 */
	public function editAvatar($id = null) {
		// Check the request sanity
		if (!$this->request->is('post')) {
			throw new BadRequestException(__('Invalid request method, should be POST.'));
		}
		if (User::get('Role.name') != Role::ADMIN && $id != User::get('id')) {
			// Only admin users can update users avatars
			// Except user editing their own avatar
			throw new ForbiddenException(__('You are not authorized to access that location.'));
		}
		if (!isset($id)) {
			throw new BadRequestException(__('The user id is missing.'));
		}
		if (!Common::isUuid($id)) {
			throw new BadRequestException(__('The user id is not valid.'));
		}
		if (empty($_FILES)) {
			throw new BadRequestException(__('No avatar data was provided.'));
		}

		// Check the user exists
		$o = $this->User->getFindOptions('User::view', User::get('Role.name'), ['User.id' => $id]);
		$user = $this->User->find('first', $o);
		if (!$user) {
			throw new NotFoundException(__('The user does not exist.'));
		}

		// Update the user avatar.
		try {
			$file = reset($_FILES);
			$data = ['Avatar' => ['file' => $file]];
			$this->User->Profile->Avatar->upload($user['Profile']['id'], $data);
		} catch (ValidationException $ve) {
			throw new ValidationException($ve->getMessage(), ['User' => ['Profile' => $ve->getInvalidFields()]]);
		} catch (Exception $e) {
			throw new InternalErrorException(__('The avatar could not be uploaded'));
		}

		// Retrieve and return the updated user.
		$data = ['User.id' => $id];
		$options = $this->User->getFindOptions('User::view', User::get('Role.name'), $data);
		$users = $this->User->find('all', $options);
		$user = reset($users);

		$this->set('data', $user);
		$this->Message->success(__('The avatar has been updated successfully'));
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

		// Check the request sanity
		if (!$this->request->is('put')) {
			throw new BadRequestException(__('Invalid request method, should be PUT.'));
		}
		if (!isset($id)) {
			throw new BadRequestException(__('The user id is missing.'));
		}
		if (!Common::isUuid($id)) {
			throw new BadRequestException(__('The user id is not valid.'));
		}
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('The user does not exist.'));
		}

		// Store request data in data
		$data = $this->request->data;
		if(!isset($data) || empty($data)) {
			// if data is not accessible via form data, check for json input
			$data = $this->request->input('json_decode');
			// convert object to associative array to match formdata format
			$data = json_decode(json_encode($data), true);
		}

		// Check token
		$Auth = $this->User->AuthenticationToken;
		if (!isset($data['AuthenticationToken']) || !isset($data['AuthenticationToken']['token'])) {
			throw new BadRequestException(__('No authentication token was provided.'));
		}
		$token = $Auth->findFirstByToken($data['AuthenticationToken']['token']);
		if (empty($token)) {
			throw new BadRequestException(__('The authentication token is not valid.'));
		}
		if (!$Auth->isNotExpired($data['AuthenticationToken']['token'])) {
			throw new BadRequestException(__('The authentication token is expired.'));
		}
		if (!$Auth->isValid($data['AuthenticationToken']['token'], $id)) {
			throw new BadRequestException(__('The authentication token is not valid.'));
		}

		// Token is valid, we begin transaction
		$dataSource = $this->User->getDataSource();
		$dataSource->begin();

		// Activate user
		$this->User->id = $id;
		$result = $this->User->saveField('active', true, ['atomic' => false]);
		if (!$result) {
			$dataSource->rollback();
			throw new InternalErrorException(__('Could not update user.'));
		}

		// Deactivate Token.
		$result = AuthenticationToken::setInactive($data['AuthenticationToken']['token'], $id);
		if (!$result) {
			$dataSource->rollback();
			throw new InternalErrorException(__('Could not update authentication token.'));
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
				throw new ValidationException(__('Could not validate profile.'), $this->User->Profile->validationErrors);
			}

			// Save/Update the profile
			$result = $this->User->Profile->save($profileData, false, ['fieldList' => $fields['fields']]);
			// If update failed
			if (!$result) {
				$dataSource->rollback();
				throw new InternalErrorException(__('Could not save profile.'));
			}
		}

		// If User data are provided, we update
		if (isset($data['Gpgkey'])) {
			$gpgkeyData = $data['Gpgkey'];

			// Extract data from the key
			$gpgkeyData = $this->User->Gpgkey->buildGpgkeyDataFromKey($gpgkeyData['key']);
			if ($gpgkeyData == false) {
				$dataSource->rollback();
				throw new BadRequestException(__('The key provided could not be used.'));
			}

			// Set actual user id
			$gpgkeyData['Gpgkey']['user_id'] = $id;

			// Sanitize gpg key data.
			$gpgkeyDataSanitized = $this->HtmlPurifier->cleanRecursive($gpgkeyData, 'nohtml');
			// UID should not be sanitized at this stage, or will not pass the validation.
			// Due to '<' being transformed to html entities
			$gpgkeyDataSanitized['Gpgkey']['uid'] = $gpgkeyData['Gpgkey']['uid'];

			// Set data.
			$this->User->Gpgkey->create();
			$this->User->Gpgkey->set($gpgkeyDataSanitized);

			// Get fields.
			$fields = $this->User->getFindFields('User::validateAccount');

			// Check if the key data is valid.
			if (!$this->User->Gpgkey->validates(['fieldList' => [$fields['fields']]])) {
				$dataSource->rollback();
				throw new ValidationException(__('Could not validate the GPG key data.'), $this->User->Gpgkey->validationErrors);
			}

			// Sanitize the UID info
			$gpgkeyDataSanitized['Gpgkey']['uid'] = htmlentities($gpgkeyDataSanitized['Gpgkey']['uid']);

			// Since current user is anonymous we need to set created_by and modified_by manually
			// to match the new expected user
			$gpgkeyDataSanitized['Gpgkey']['created_by'] = $id;
			$gpgkeyDataSanitized['Gpgkey']['modified_by'] = $id;

			// Save the key
			$gpgkey = $this->User->Gpgkey->save($gpgkeyDataSanitized, false, ['fieldList' => $fields['fields']]);

			// If saving the key failed
			if (!$gpgkey) {
				$dataSource->rollback();
				throw new InternalErrorException(__('The GPG key could not be saved.'));
			}
		}

		// Everything ok, we commit the transaction.
		$dataSource->commit();

		// Return information in case of success.
		$data = ['User.id' => $id];
		$options = $this->User->getFindOptions('User::view', User::get('Role.name'), $data);
		$user = $this->User->find('first', $options);

		$this->set('data', $user);
		$this->Message->success(__("The user has been updated successfully"));
	}

/**
 * Delete a user
 *
 * @param string $id the uuid of the user to delete
 * @throws BadRequestException if the request method is not delete
 * @throws ForbiddenException if the user is not admin
 * @throws BadRequestException if the user id is missing or invalid
 * @throws BadRequestException if the user id is the same as the current user
 * @throws NotFoundException if the user does not exist
 * @throws InternalErrorException if the user could not be deleted
 * @throws ValidationException if the user is the sole owner of some shared passwords
 * @return void
 */
	public function delete($id = null) {
		// Check request sanity
		if (!$this->request->is('delete')) {
			throw new BadRequestException(__('Invalid request method, should be DELETE.'));
		}
		if (!User::isAdmin()) {
			throw new ForbiddenException(__('You are not authorized to access that location.'));
		}
		if (!isset($id)) {
			throw new BadRequestException(__('The user id is missing.'));
		}
		if (!Common::isUuid($id)) {
			throw new BadRequestException(__('The user id is not valid.'));
		}
		if (User::get('id') == $id) {
			throw new BadRequestException(__('You are not allowed to delete yourself.'));
		}

		// Retrieve the user to delete.
		$o = $this->User->getFindOptions('User::view', User::get('Role.name'), ['User.id' => $id]);
		$user = $this->User->find('first', $o);
		if (!$user) {
			throw new NotFoundException(__('The user does not exist.'));
		}

		// Is dry run ?
		$isDryRun = in_array('dry-run', $this->params['pass']);

		// Does the user the sole owner of resources shared with others.
		$resourcesIds = $this->User->UserResourcePermission->findSoleOwnerSharedResourcesIds($id);
		if (!empty($resourcesIds)) {
			$Resource = Common::getModel('Resource');

			// Retrieve the resources that require an ownership transfer.
			$Resource->Behaviors->unload('Permissionable');
			$resourcesFindData = ['has-resource_id' => $resourcesIds];
			$resourcesFindOptions = $Resource->getFindOptions('Resource::index', User::get('Role.name'), $resourcesFindData);
			$resources = $Resource->find('all', $resourcesFindOptions);
			$Resource->Behaviors->load('Permissionable');

			throw new ValidationException(
				__('The user is sole owner of some passwords. Transfer the ownership before deleting.'),
				$resources
			);
		}

		// In case of dry-run, notify the requester that the user can be deleted.
		if ($isDryRun) {
			return $this->Message->success(__("The user can be deleted."));
		}

		try {
			$this->User->softDelete($id);
		} catch(Exception $e) {
			throw new InternalErrorException(__('Could not delete the user.'));
		}

		// Everything went fine, commit the changes
		$this->Message->success(__('The user was successfully deleted.'));
	}
}

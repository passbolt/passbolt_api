<?php
/**
 * Resources Controller
 *
 * @copyright (c) 2015-2016 Bolt Softwares Pvt Ltd
 *                2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

class ResourcesController extends AppController {

/**
 * @var array $component application wide components
 */
	public $components = [
		'QueryString',
		'EmailNotificator',
	];

/**
 * Get all resources
 * Filters: is-favorite, is-owned-by-me
 * @throws MethodNotAllowedException if the HTTP request is not GET
 * @return void
 */
	public function index() {
		// Check request sanity
		if (!$this->request->is('get')) {
			throw new MethodNotAllowedException(__('Invalid request method, should be GET.'));
		}

		// Extract parameters from query string
		$allowedQueryItems = [
			'filter' => ['is-favorite', 'is-owned-by-me', 'is-shared-with-me'],
			'contain' => [ 'Creator', 'Favorite', 'Modifier', 'Secret'],
			'order' => $this->Resource->getFindAllowedOrder('ResourcesController::index'),
		];
		$params = $this->QueryString->get($allowedQueryItems);

		// Retrieve the resources
		$findOptions = $this->Resource->getFindOptions('Resource::index', User::get('Role.name'), $params);
		$resources = $this->Resource->find('all', $findOptions);
		if (!$resources) {
			$resources = [];
		}
		$this->set('data', $resources);
		$this->Message->success();
	}

/**
 * Get a resource
 *
 * @param string $id the uuid of the resource
 * @throws MethodNotAllowedException if the HTTP request method is not GET
 * @throws BadRequestException if the resource id is missing or not valid
 * @throws NotFoundException if the resource does not exist or have been deleted
 * @throws NotFoundException if the the user does not have the right to access the resource
 * @return void
 */
	public function view($id = null) {
		// Check the request sanity
		if (!$this->request->is('get')) {
			throw new MethodNotAllowedException(__('Invalid request method, should be GET.'));
		}
		if (!isset($id)) {
			throw new BadRequestException(__('The resource id is missing.'));
		}
		if (!Common::isUuid($id)) {
			throw new BadRequestException(__('The resource id is not valid.'));
		}
		if (!$this->Resource->exists($id)) {
			throw new NotFoundException(__('The resource does not exist.'));
		}
		if ($this->Resource->isSoftDeleted($id)) {
			throw new NotFoundException(__('The resource does not exist.'));
		}
		if (!$this->Resource->isAuthorized($id, PermissionType::READ)) {
			throw new NotFoundException(__('The resource does not exist.'));
		}

		// Get the resource.
		$data = ['Resource.id' => $id];
		$o = $this->Resource->getFindOptions('Resource::view', User::get('Role.name'), $data);
		$this->set('data', $this->Resource->find('first', $o));
		$this->Message->success();
	}

/**
 * Delete a resource
 *
 * @param string $id the uuid of the resource to delete
 * @throws MethodNotAllowedException if the HTTP request method is not DELETE
 * @throws BadRequestException if the resource id is missing or not valid
 * @throws NotFoundException if the resource does not exist or have been deleted
 * @throws ForbiddenException if the user does not have the right to access the resource
 * @return void
 */
	public function delete($id = null) {
		// check the request sanity
		if (!$this->request->is('delete')) {
			throw new MethodNotAllowedException(__('Invalid request method, should be DELETE.'));
		}
		if (!isset($id)) {
			throw new BadRequestException(__('The resource id is missing.'));
		}
		if (!Common::isUuid($id)) {
			throw new BadRequestException(__('The resource id is not valid.'));
		}
		if (!$this->Resource->exists($id)) {
			throw new NotFoundException(__('The resource does not exist.'));
		}
		if ($this->Resource->isSoftDeleted($id)) {
			throw new NotFoundException(__('The resource does not exist.'));
		}
		if (!$this->Resource->isAuthorized($id, PermissionType::READ)) {
			throw new NotFoundException(__('The resource does not exist.'));
		}
		if (!$this->Resource->isAuthorized($id, PermissionType::UPDATE)) {
			throw new ForbiddenException(__('You are not authorized to delete this resource.'));
		}

		// Retrieve the resource before soft deleting it, information can be user after the deletion
		$resource = $this->Resource->findById($id);
		// Soft delete the resource
		try {
			$this->Resource->softDelete($id);
		} catch(Exception $e) {
			throw new InternalErrorException(__('Error while deleting resource.'));
		}

		// Email notification.
		$authorizedUsers = $this->Resource->findAuthorizedUsers($id);

		// Extract user ids from array.
		$authorizedUsersIds = Hash::extract($authorizedUsers, '{n}.User.id');
		foreach ($authorizedUsersIds as $userId) {
			$this->EmailNotificator->passwordDeletedNotification(
				$userId,
				[
					'resource_name' => $resource['Resource']['name'],
					'deleter_id' => User::get('id'),
					'own' => User::get('id') == $userId ? true : false,
				]);
		}
		$this->Message->success(__('The resource was successfully deleted'));
	}

/**
 * Add a resource
 *
 * @throws MethodNotAllowedException if the http request method is not POST
 * @throws BadRequestException if no resource data was provided
 * @throws BadRequestException if the resource data could not be validated
 * @throws InternalErrorException if the resource could not be saved
 * @return void
 */
	public function add() {
		// Begin transaction and allow nested transactions.
		$dataSource = $this->Resource->getDataSource();
		$dataSource->begin();

		// check the request sanity
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException(__('Invalid request method, should be POST.'));
		}
		if (!isset($this->request->data['Resource'])) {
			throw new BadRequestException(__('No data was provided.'));
		}

		// set the data for validation and save
		$resourcepost = $this->request->data;
		$this->Resource->set($resourcepost);

		// Get fields to validate.
		$fields = $this->Resource->getFindFields('Resource::save', User::get('Role.name'));

		// check if the data is valid.
		if (!$this->Resource->validates(['fieldList' => $fields['fields']])) {
			throw new ValidationException(__('Could not validate resource data'), $this->Resource->validationErrors);
		}

		// Save resource.
		$resource = $this->Resource->save(
			$resourcepost,
			[
				'validate' => false,
				'atomic' => false,
				'fieldList' => $fields['fields']
			]);

		// If something went wrong while saving the resource.
		if ($resource === false) {
			$dataSource->rollback();
			throw new InternalErrorException(__('The resource could not be saved.'));
		}

		// Check if there is at least one secret given.
		if (empty($resourcepost['Secret'])) {
			$dataSource->rollback();
			throw new BadRequestException(__('No secret provided.'));
		}

		// Save the secrets.
		$secretpost = $resourcepost['Secret'][0];
		$secretpost['user_id'] = User::get('User.id');
		$secretpost['resource_id'] = $resource['Resource']['id'];

		// Validate the secret.
		$secretFields = $this->Resource->Secret->getFindFields('save', User::get('Role.name'));
		$this->Resource->Secret->set($secretpost);
		if (!$this->Resource->Secret->validates(['fieldList' => $secretFields['fields']])) {
			$dataSource->rollback();
			throw new ValidationException(
				__('Could not validate secret model'), $this->Resource->Secret->validationErrors
			);
		}

		// Save the secret.
		$secret = $this->Resource->Secret->save($secretpost, [
			'validate' => false,
			'atomic' => false,
			'fieldList' => $secretFields['fields']
		]);

		// If something wrong happened while saving the secrets.
		if ($secret == false) {
			$dataSource->rollback();
			throw new InternalErrorException(__('Could not save the secret.'));
		}

		// Email notification.
		$this->EmailNotificator->passwordCreatedNotification(
			User::get('User.id'),
			[
				'resource_id' => $resource['Resource']['id'],
			]);

		// Everything went fine.
		$dataSource->commit();

		// Return the added resource.
		$addedResourceFindOptions = $this->Resource->getFindOptions('Resource::view', User::get('Role.name'), [
			'Resource.id' => $resource['Resource']['id']
		]);
		$addedResource = $this->Resource->find('first', $addedResourceFindOptions);
		$this->set('data', $addedResource);
		$this->Message->success(__('The resource was successfully saved.'));
	}

/**
 * Update a resource
 *
 * @param string $id the uuid of the resource to edit
 * @throws MethodNotAllowedException if the http request method is not PUT
 * @throws BadRequestException if the resource id is missing or invalid
 * @throws NotFoundException if the resource does not exist or have been deleted
 * @throws ForbiddenException if the user does not have the right to edit the resource
 * @throws InternalErrorException if the resource could not be saved
 * @return void
 */
	public function edit($id = null) {
		// check the HTTP request method
		if (!$this->request->is('put')) {
			throw new MethodNotAllowedException(__('Invalid request method, should be PUT.'));
		}
		if (!isset($id)) {
			throw new BadRequestException(__('No resource id provided.'));
		}
		if (!Common::isUuid($id)) {
			throw new BadRequestException(__('The resource id is not valid.'));
		}
		if (!$this->Resource->exists($id)) {
			throw new NotFoundException(__('The resource does not exist.'));
		}
		if ($this->Resource->isSoftDeleted($id)) {
			throw new NotFoundException(__('The resource does not exist.'));
		}
		if (!$this->Resource->isAuthorized($id, PermissionType::UPDATE)) {
			throw new ForbiddenException(__('You are not authorized to edit this resource.'));
		}

		// Retrieve the resource
		$resource = $this->Resource->findById($id);

		// set the data for validation and save
		$resourcepost = $this->request->data;
		// Use the url id parameter as Resource id
		$resourcepost['Resource']['id'] = $id;

		// Begin transaction, and set useNestedTransaction to true.
		$dataSource = $this->Resource->getDataSource();
		$dataSource->useNestedTransactions = true;
		$dataSource->begin();

		// check if data was provided
		if (!isset($resourcepost['Resource'])) {
			throw new BadRequestException(__('No resource data was provided.'));
		}

		// Update the resource
		if (isset($resourcepost['Resource'])) {

			// Get the meaningful fields for this operation
			$fields = $this->Resource->getFindFields('Resource::edit', User::get('Role.name'));

			// Validate the resource data
			$this->Resource->set($resourcepost);
			if (!$this->Resource->validates(['fieldList' => $fields['fields']])) {
				$dataSource->rollback();
				throw new ValidationException(
					__('Could not validate resource.'),
					['body' => $this->Resource->validationErrors]
				);
			}

			// Save data.
			$save = $this->Resource->save(
				$resourcepost, [
				'validate' => false,
				'fieldList' => $fields['fields'],
				'atomic' => false
			]);

			if (!$save) {
				$dataSource->rollback();
				throw new InternalErrorException(__('The resource could not be updated.'));
			}
		}

		// Update the associated secrets.
		if (isset($resourcepost['Secret']) && !empty($resourcepost['Secret'])) {
			try {
				$this->Resource->saveSecrets($id, $resourcepost['Secret']);
			} catch (ValidationException $e) {
				$dataSource->rollback();
				throw $e;
			} catch (Exception $e) {
				$dataSource->rollback();
				throw new BadRequestException($e->getMessage());
			}
		}

		// Commit all the changes.
		$dataSource->commit();

		// Email notification.
		$authorizedUsers = $this->Resource->findAuthorizedUsers($id);

		// Extract user ids from array.
		$authorizedUsersIds = Hash::extract($authorizedUsers, '{n}.User.id');
		foreach ($authorizedUsersIds as $userId) {
			$this->EmailNotificator->passwordUpdatedNotification(
				$userId,
				[
					'resource_id' => $resource['Resource']['id'],
					'sender_id' => User::get('id'),
					'resource_old_name' => $resource['Resource']['name'],
					'own' => User::get('id') == $userId ? true : false,
				]);
		}

		// Retrieve the updated resource.
		$data = [
			'Resource.id' => $resource['Resource']['id']
		];
		$options = $this->Resource->getFindOptions('Resource::view', User::get('Role.name'), $data);
		$resource = $this->Resource->find('first', $options);

		$this->set('data', $resource);
		$this->Message->success(__('The resource was successfully updated'));
	}

/**
 * Get a list of users who have access to a resource
 * Renders a json object of users
 *
 * @param string $id the uuid of the resource
 * @throws BadRequestException if the resource id is not provided
 * @throws BadRequestException if the resource id is not valid
 * @throws NotFoundException if the resource does not exist or has been deleted
 * @throws ForbiddenException if the user is not authorized to view this resource
 * @return void
 */
	public function users($id = null) {
		// check if the resource id is provided
		if (!isset($id)) {
			throw new BadRequestException(__('No resource id provided.'));
		}
		if (!Common::isUuid($id)) {
			throw new BadRequestException(__('The resource id is not valid.'));
		}
		if (!$this->Resource->exists($id)) {
			throw new NotFoundException(__('The resource does not exist.'));
		}
		if ($this->Resource->isSoftDeleted($id)) {
			throw new NotFoundException(__('The resource does not exist.'));
		}
		if (!$this->Resource->isAuthorized($id, PermissionType::READ)) {
			throw new NotFoundException(__('The resource does not exist.'));
		}

		// Get the permissions the users who have access to the resource
		$UserResourcePermission = Common::getModel('UserResourcePermission');
		$findPermissionData = [
			'UserResourcePermission' => [
				'resource_id' => $id
			]
		];
		$findPermissionOptions = $UserResourcePermission->getFindOptions('viewByResource', User::get('Role.name'), $findPermissionData);
		$userResourcePermissions = $UserResourcePermission->find('all', $findPermissionOptions);

		// Retrieve the users
		$usersIds = Hash::extract($userResourcePermissions, '{n}.UserResourcePermission.user_id');
		$User = Common::getModel('User');
		$findUserData = [
			'User.ids' => $usersIds
		];
		$findUserOptions = $User->getFindOptions('Resource::users', User::get('Role.name'), $findUserData);
		$users = $User->find('all', $findUserOptions);

		// Response
		$this->set('data', $users);
		$this->Message->success();
	}
}

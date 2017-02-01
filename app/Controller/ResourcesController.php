<?php
/**
 * Resources Controller
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

class ResourcesController extends AppController {

/**
 * @var array $component application wide components
 */
	public $components = [
		'Filter',
		'PermissionHelper',
		'EmailNotificator',
	];

/**
 * Get all resources
 * Renders a json object of the resources.
 *
 * @return void
 *
 * @SWG\Get(
 *   path="/resources.json",
 *   summary="Find resources",
 * @SWG\Parameter(
 *     name="filter_keywords",
 *     in="query",
 *     description="Keywords to filter by",
 *     required=false,
 *     type="string"
 *   ),
 * @SWG\Parameter(
 *     name="filter_case",
 *     in="query",
 *     description="Case to filter by",
 *     required=false,
 *     type="string",
 * 	   enum={
 * 		 "favorite",
 * 		 "shared"
 * 	   }
 *   ),
 * @SWG\Parameter(
 *     name="filter_order",
 *     in="query",
 *     description="Field to order by",
 *     required=false,
 *     type="string",
 * 	   enum={
 * 		 "modified",
 * 		 "expiry_date"
 * 	   }
 *   ),
 * @SWG\Response(
 *     response=200,
 *     description="An array of resources",
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
 * 				"$ref"= "#/definitions/Resource"
 *           }
 *         )
 *       }
 *     )
 *   )
 * )
 */
	public function index() {
		// Extract the filter from the request
		$findData = $this->Filter->fromRequest($this->request->query);

		// Retrieve the resources
		$findOptions = $this->Resource->getFindOptions('index', User::get('Role.name'), $findData);
		$resources = $this->Resource->find('all', $findOptions);

		if (!$resources) {
			$resources = [];
		}

		$this->set('data', $resources);
		$this->Message->success();
	}

/**
 * Get a resource
 * Renders a json object of the resource
 *
 * @param string $id the uuid of the resource
 * @return void
 *
 * @SWG\Get(
 *   path="/resources/{uuid}.json",
 *   summary="Find a resource by ID",
 * @SWG\Parameter(
 * 		name="id",
 * 		in="path",
 * 		required=true,
 * 		type="string",
 * 		description="the uuid of the resource",
 *   ),
 * @SWG\Response(
 *     response=200,
 *     description="The details of the resource",
 *     @SWG\Schema(
 *       type="object",
 *       properties={
 *         @SWG\Property(
 *           property="header",
 *           ref="#/definitions/Header"
 *         ),
 *         @SWG\Property(
 *           property="body",
 *           ref="#/definitions/Resource"
 *         )
 *       }
 *     )
 *   )
 * )
 */
	public function view($id = null) {
		// check if the resource id is provided
		if (!isset($id)) {
			return $this->Message->error(__('The resource id is missing'));
		}
		// check if the id is valid
		if (!Common::isUuid($id)) {
			return $this->Message->error(__('The resource id is invalid'));
		}
		// check if it exists
		if (!$this->Resource->exists($id)) {
			return $this->Message->error(__('The resource does not exist'), ['code' => 404]);
		}
		// check if it has been soft deleted
		if ($this->Resource->isSoftDeleted($id)) {
			return $this->Message->error(__('The resource does not exist'), ['code' => 404]);
		}
		// check if the current user is authorized to access the resource
		if (!$this->Resource->isAuthorized($id, PermissionType::READ)) {
			return $this->Message->error(__('You are not authorized to access this resource'), ['code' => 403]);
		}

		// Get the resource.
		$data = [
			'Resource.id' => $id
		];
		$o = $this->Resource->getFindOptions('view', User::get('Role.name'), $data);
		$this->set('data', $this->Resource->find('first', $o));
		$this->Message->success();
	}

/**
 * Delete a resource
 *
 * @param string $id the uuid of the resource to delete
 * @return void
 */
	public function delete($id = null) {
		// check if the resource id is provided
		if (!isset($id)) {
			return $this->Message->error(__('The resource id is missing'));
		}
		// check if the id is valid
		if (!Common::isUuid($id)) {
			return $this->Message->error(__('The resource id is invalid'));
		}
		// check the resource exists
		if (!$this->Resource->exists($id)) {
			return $this->Message->error(__('The resource does not exist'), ['code' => 404]);
		}
		// check if it has been soft deleted
		if ($this->Resource->isSoftDeleted($id)) {
			return $this->Message->error(__('The resource does not exist'), ['code' => 404]);
		}
		// check if the current user is authorized to access the resource
		if (!$this->Resource->isAuthorized($id, PermissionType::UPDATE)) {
			return $this->Message->error(__('You are not authorized to delete this resource'), ['code' => 403]);
		}

		// Retrieve the resource before soft deleting it, information can be user after the deletion
		$resource = $this->Resource->findById($id);
		// Soft delete the resource
		try {
			$this->Resource->softDelete($id);
		} catch(Exception $e) {
			return $this->Message->error(__('Error while deleting resource'));
		}

		// Email notification.
		$resourcePermissions = $this->PermissionHelper->findAcoUsers('Resource', $id);
		// Extract user ids from array.
		$resourceUsers = Hash::extract($resourcePermissions, '{n}.User.id');
		foreach ($resourceUsers as $userId) {
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
 * @return void
 */
	public function add() {
		// Begin transaction and allow nested transactions.
		$dataSource = $this->Resource->getDataSource();
		$dataSource->begin();

		// check the HTTP request method
		if (!$this->request->is('post')) {
			return $this->Message->error(__('Invalid request method, should be POST'));
		}
		// check if data was provided
		if (!isset($this->request->data['Resource'])) {
			return $this->Message->error(__('No data were provided'));
		}

		// set the data for validation and save
		$resourcepost = $this->request->data;
		$this->Resource->set($resourcepost);

		// Get fields to validate.
		$fields = $this->Resource->getFindFields('save', User::get('Role.name'));

		// check if the data is valid.
		if (!$this->Resource->validates(['fieldList' => $fields['fields']])) {
			return $this->Message->error(__('Could not validate resource data'),
				['body' => $this->Resource->validationErrors]);
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
			return $this->Message->error(__('The resource could not be saved'));
		}

		// Check if there is at least one secret given.
		if (empty($resourcepost['Secret'])) {
			$dataSource->rollback();
			return $this->Message->error(__('No secret provided'));
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
			return $this->Message->error(__('Could not validate secret model'),
				['body' => $this->Resource->Secret->validationErrors]);
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
			return $this->Message->error(__('Could not save the secret'));
		}

		// Email notification.
		$this->EmailNotificator->passwordCreatedNotification(
			User::get('User.id'),
			[
				'resource_id' => $resource['Resource']['id'],
			]);

		// Everything went fine.
		$dataSource->commit();
		$this->Message->success(__('The resource was successfully saved'));

		// Return the added resource.
		$addedResourceFindOptions = $this->Resource->getFindOptions('view', User::get('Role.name'), [
			'Resource.id' => $resource['Resource']['id']
		]);
		$addedResource = $this->Resource->find('first', $addedResourceFindOptions);
		$this->set('data', $addedResource);
	}

/**
 * Update a resource
 *
 * @param string $id the uuid of the resource to edit
 * @return void
 */
	public function edit($id = null) {
		// check the HTTP request method
		if (!$this->request->is('put')) {
			return $this->Message->error(__('Invalid request method, should be PUT'));
		}

		// check if data was provided
		if ($id == null) {
			return $this->Message->error(__('No valid id was provided'));
		}

		// check if the id is valid
		if (!Common::isUuid($id)) {
			return $this->Message->error(__('The resource id invalid'));
		}

		// check if the resource exists
		if (!$this->Resource->exists($id)) {
			return $this->Message->error(__('The resource does not exist'));
		}
		// check if it has been soft deleted
		if ($this->Resource->isSoftDeleted($id)) {
			return $this->Message->error(__('The resource does not exist'), ['code' => 404]);
		}
		// check if the current user is authorized to access the resource
		if (!$this->Resource->isAuthorized($id, PermissionType::UPDATE)) {
			return $this->Message->error(__('You are not authorized to edit this resource'), ['code' => 403]);
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
			return $this->Message->error(__('No data were provided'));
		}

		// Update the resource
		if (isset($resourcepost['Resource'])) {

			// Get the meaningful fields for this operation
			$fields = $this->Resource->getFindFields('Resource::edit', User::get('Role.name'));

			// Validate the resource data
			$this->Resource->set($resourcepost);
			if (!$this->Resource->validates(['fieldList' => $fields['fields']])) {
				$dataSource->rollback();
				return $this->Message->error(
					__('Could not validate Resource'),
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
				return $this->Message->error(__('The resource could not be updated'));
			}
		}

		// Update the associated secrets.
		if (isset($resourcepost['Secret']) && !empty($resourcepost['Secret'])) {
			try {
				$this->Resource->saveSecrets($id, $resourcepost['Secret']);
			} catch (ValidationException $e) {
				$dataSource->rollback();

				return $this->Message->error($e->getMessage(), ['body' => $e->getInvalidFields()]);
			} catch (Exception $e) {
				$dataSource->rollback();
				return $this->Message->error($e->getMessage());
			}
		}

		// Commit all the changes.
		$dataSource->commit();

		// Email notification.
		$resourcePermissions = $this->PermissionHelper->findAcoUsers('Resource', $id);
		// Extract user ids from array.
		$resourceUsers = Hash::extract($resourcePermissions, '{n}.User.id');
		foreach ($resourceUsers as $userId) {
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
		$options = $this->Resource->getFindOptions('view', User::get('Role.name'), $data);
		$resource = $this->Resource->find('first', $options);

		$this->Message->success(__('The resource was successfully updated'));
		$this->set('data', $resource);
	}

	/**
	 * Get a list of users who have access to a resource
	 * Renders a json object of users
	 *
	 * @param string $id the uuid of the resource
	 * @return void
	 *
	 * @SWG\Get(
	 *   path="/resources/{uuid}/users.json",
	 *   summary="Find the users who have access to a resource",
	 * @SWG\Parameter(
	 * 		name="id",
	 * 		in="path",
	 * 		required=true,
	 * 		type="string",
	 * 		description="the uuid of the resource",
	 *   ),
	 * @SWG\Response(
	 *     response=200,
	 *     description="The list of users",
	 *     @SWG\Schema(
	 *       type="object",
	 *       properties={
	 *         @SWG\Property(
	 *           property="header",
	 *           ref="#/definitions/Header"
	 *         ),
	 *         @SWG\Property(
	 *           property="body",
	 *           ref="#/definitions/Resource"
	 *         )
	 *       }
	 *     )
	 *   )
	 * )
	 */
	public function users($id = null) {
		// check if the resource id is provided
		if (!isset($id)) {
			return $this->Message->error(__('The resource id is missing'));
		}
		// check if the id is valid
		if (!Common::isUuid($id)) {
			return $this->Message->error(__('The resource id is invalid'));
		}
		// check if it exists
		if (!$this->Resource->exists($id)) {
			return $this->Message->error(__('The resource does not exist'), ['code' => 404]);
		}
		// check if it has been soft deleted
		if ($this->Resource->isSoftDeleted($id)) {
			return $this->Message->error(__('The resource does not exist'), ['code' => 404]);
		}
		// check if the current user is authorized to access the resource
		if (!$this->Resource->isAuthorized($id, PermissionType::READ)) {
			return $this->Message->error(__('You are not authorized to access this resource'), ['code' => 403]);
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
		$usersIds = Hash::extract($userResourcePermissions, '{n}.Permission.User.id');
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

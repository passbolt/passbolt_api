<?php
/**
 * Resources Controller
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('Category', 'Model');
App::uses('CategoryResource', 'Model');

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
 */
	public function index() {
		// The additional information to pass to the model request
		$findData = [];
		// Whether we want also the resources of all subcategories
		$recursive = false;

		// Extract the filter from the request
		$filter = $this->Filter->fromRequest($this->request->query);
		// Merge the filter into the additional information to pass to the model request
		$findData = array_merge($findData, $filter);
		if (isset($this->request->query['recursive']) && $this->request->query['recursive'] === 'true') {
			$recursive = true;
		}

		// A filter on category is provided
		// - check the validity of the given categories uid
		// - if recursive, filter also on sub-categories
		if (isset($findData['foreignModels']['Category.id'])) {
			// Tmp array to store the target categories and subcategories (if recursive provided)
			$categories = [];

			foreach ($findData['foreignModels']['Category.id'] as $categoryId) {
				// if a category id is provided check it is well an uid
				if (!Common::isUuid($categoryId)) {
					return $this->Message->error(__('The category id is invalid'));
				}
				// check if the category exists
				$category = $this->Resource->CategoryResource->Category->findById($categoryId);
				if (!$category) {
					return $this->Message->error(__('The category doesn\'t exist'));
				}

				// The request is not a recursive request
				if (!$recursive) {
					$categories[] = $categoryId;
				} else {
					// Else get the sub categories and add them to the target categories to filter on
					// If the category has yet been added to the additional parameters
					if (in_array($categoryId, $categories)) {
						continue;
					} else {
						// Else Get the subcategories of the current category
						$subCategories = $this->Resource->CategoryResource->Category->getSubCategories($category);
						// Add the subcategories to the request conditions
						foreach ($subCategories as $subCategory) {
							$categories[] = $subCategory['Category']['id'];
						}
					}
				}
			}

			// replace the categories to filter on with the computed array of categories & subcategories
			$findData['foreignModels']['Category.id'] = $categories;
		}

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
		$resource = $this->Resource->findById($id);
		if (!$resource) {
			return $this->Message->error(__('The resource does not exist'), ['code' => 404]);
		}

		// check if user is authorized
		// the permissionable after find executed on the previous operation findById should drop
		// any record the user is not authorized to access. This test should always be true.
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
		// check if the category id is provided
		if (!isset($id)) {
			return $this->Message->error(__('The resource id is missing'));
		}
		// check if the id is valid
		if (!Common::isUuid($id)) {
			return $this->Message->error(__('The resource id is invalid'));
		}
		$resource = $this->Resource->findById($id);
		if (!$resource) {
			return $this->Message->error(__('The resource does not exist'), ['code' => 404]);
		}

		// check if user is authorized
		if (!$this->Resource->isAuthorized($id, PermissionType::UPDATE)) {
			return $this->Message->error(__('You are not authorized to delete this resource'), ['code' => 403]);
		}

		$resource['Resource']['deleted'] = '1';
		$fields = $this->Resource->getFindFields('delete', User::get('Role.name'));
		if (!$this->Resource->save($resource, true, $fields['fields'])) {
			return $this->Message->error(__('Error while deleting'));
		}

		// Email notification.
		$resourcePermissions = $this->PermissionHelper->findAcoUsers('Resource', $id);
		// Extract user ids from array.
		$resourceUsers = Hash::extract($resourcePermissions, '{n}.User.id');
		foreach ($resourceUsers as $userId) {
			$this->EmailNotificator->passwordDeletedNotification(
				$userId,
				[
					'resource_id' => $id,
					'deleter' => User::get('id'),
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

		// Save the corresponding categories.
		if (isset($resourcepost['Category'])) {
			$categoryResourceFields = $this->Resource->CategoryResource->getFindFields('save', User::get('Role.name'));

			foreach ($resourcepost['Category'] as $cat) {
				$this->Resource->CategoryResource->create();
				$crdata = [
					'CategoryResource' => [
						'category_id' => $cat['id'],
						'resource_id' => $resource['Resource']['id']
					]
				];

				// check if the data is valid
				$this->Resource->CategoryResource->set($crdata);
				if (!$this->Resource->CategoryResource->validates(['fieldList' => $categoryResourceFields['fields']])) {
					$dataSource->rollback();
					return $this->Message->error(__('Could not validate CategoryResource',
						['body' => $this->Resource->CategoryResource->validationErrors]));
				}

				// Check that the user is well authorized to create a resource into the given category.
				if (!$this->Resource->CategoryResource->Category->isAuthorized($cat['id'], PermissionType::CREATE)) {
					$dataSource->rollback();
					return $this->Message->error(__('You are not authorized to create a resource into the category'),
						['code' => 403]);
				}

				// Save the data.
				$categoryResource = $this->Resource->CategoryResource->save(
					$crdata,
					[
						'validate' => false,
						'atomic' => false,
						'fieldList' => $categoryResourceFields['fields']
					]);

				if ($categoryResource == false) {
					$dataSource->rollback();
					return $this->Message->error(__('Could not save the association'));
				}
			}
		}

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
		$resource = $this->Resource->findById($id);
		if (!$resource) {
			return $this->Message->error(__('The resource doesn\'t exist'));
		}

		// check if user is authorized
		if (!$this->Resource->isAuthorized($id, PermissionType::UPDATE)) {
			return $this->Message->error(__('You are not authorized to edit this resource'), ['code' => 403]);
		}

		// set the data for validation and save
		$resourcepost = $this->request->data;
		// Use the url id parameter as Resource id
		$resourcepost['Resource']['id'] = $id;

		// Begin transaction, and set useNestedTransaction to true.
		$dataSource = $this->Resource->getDataSource();
		$dataSource->useNestedTransactions = true;
		$dataSource->begin();

		// check if data was provided
		if (!isset($resourcepost['Resource']) && !isset($resourcepost['Category'])) {
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

		// Save the relations
		if (isset($resourcepost['Category']) && !empty($resourcepost['Category'])) {
			// If relations are given with the resource
			// we start by deleting previous associations
			$delete = $this->Resource->CategoryResource->deleteAll([
				'resource_id' => $id
			]);
			if (!$delete) {
				$dataSource->rollback();
				return $this->Message->error(__('Could not delete Categories'));
			}
			// Save the new relations
			foreach ($resourcepost['Category'] as $cat) {
				$crdata = [
					'CategoryResource' => [
						'category_id' => $cat['id'],
						'resource_id' => $id
					]
				];

				$this->Resource->CategoryResource->create();
				// check if the data is valid
				$this->Resource->CategoryResource->set($crdata);
				if (!$this->Resource->CategoryResource->validates()) {
					$dataSource->rollback();
					return $this->Message->error(__('Could not validate CategoryResource'));
				}
				// if validation passes, then save the data
				$res = $this->Resource->CategoryResource->save();
				if (!$res) {
					$dataSource->rollback();
					return $this->Message->error(__('Could not save the association'));
				}
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
}

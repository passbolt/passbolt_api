<?php
/**
 * Resources Controller
 *
 * @copyright     Copyright 2012 Passbolt.com
 * @license       http://www.passbolt.com/license
 * @package       app.Controller.ResourcesController
 * @since         version 2.12.7
 */

App::uses('Category', 'Model');
App::uses('CategoryResource', 'Model');

class ResourcesController extends AppController {

/**
 * @var $component application wide components
 */
	public $components = array(
		'Filter',
		'PermissionHelper'
	);


/**
 * Get all resources
 * Renders a json object of the resources
 *
 * @return void
 */
	public function index() {
		// The additional information to pass to the model request
		$data = array();
		// Whether we want also the resources of all subcategories
		$recursive = false;

		// Extract the filter from the request
		$filter = $this->Filter->fromRequest($this->request->query);
		// Merge the filter into the additional information to pass to the model request
		$data = array_merge($data, $filter);
		if (isset($this->request->query['recursive']) && $this->request->query['recursive'] === 'true') {
			$recursive = true;
		}

		// if a filter by category are provided
		// - check the valildity of the given uids
		// - if recursive, filter also on sub-categories
		if (isset($data['foreignModels']['Category.id'])) {
			// Tmp array to store the target categories and subcategories (if recursive provided)
			$categories = array();

			foreach ($data['foreignModels']['Category.id'] as $categoryId) {
				// if a category id is provided check it is well an uid
				if (!Common::isUuid($categoryId)) {
					$this->Message->error(__('The category id is invalid'));
					return;
				}
				// check if the category exists
				$category = $this->Resource->CategoryResource->Category->findById($categoryId);
				if (!$category) {
					$this->Message->error(__('The category doesn\'t exist'));
					return;
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
			$data['foreignModels']['Category.id'] = $categories;
		}

		$options = $this->Resource->getFindOptions('index', User::get('Role.name'), $data);
		$resources = $this->Resource->find('all', $options);

		if (!$resources) {
			$resources = array();
		}

		$this->set('data', $resources);
		$this->Message->success();
	}

/**
 * Get a resource
 * Renders a json object of the resource
 *
 * @param uuid $id the id of the resource
 * @return void
 */
	public function view($id = null) {
		// check if the resource id is provided
		if (!isset($id)) {
			$this->Message->error(__('The resource id is missing'));
			return;
		}
		// check if the id is valid
		if (!Common::isUuid($id)) {
			$this->Message->error(__('The resource id is invalid'));
			return;
		}
		// check if it exists
		$resource = $this->Resource->findById($id);
		if (!$resource) {
			$this->Message->error(__('The resource does not exist'), array('code' => 404));
			return;
		}

		// check if user is authorized
		// the permissionable after find executed on the previous operation findById should drop
		// any record the user is not authorized to access. This test should always be true.
		if (!$this->Resource->isAuthorized($id, PermissionType::READ)) {
			$this->Message->error(__('You are not authorized to access this resource'), array('code' => 403));
			return;
		}

		// Get the resource.
		$data = array(
			'Resource.id' => $id
		);
		$o = $this->Resource->getFindOptions('view', User::get('Role.name'), $data);
		$this->set('data', $this->Resource->find('first', $o));
		$this->Message->success();
	}

/**
 * Delete a resource
 *
 * @param uuid id the id of the resource to delete
 */
	public function delete($id = null) {
		// check if the category id is provided
		if (!isset($id)) {
			$this->Message->error(__('The resource id is missing'));
			return;
		}
		// check if the id is valid
		if (!Common::isUuid($id)) {
			$this->Message->error(__('The resource id is invalid'));
			return;
		}
		$resource = $this->Resource->findById($id);
		if (!$resource) {
			$this->Message->error(__('The resource does not exist'), array('code' => 404));
			return;
		}

		// check if user is authorized
		if (!$this->Resource->isAuthorized($id, PermissionType::UPDATE)) {
			$this->Message->error(__('You are not authorized to delete this resource'), array('code' => 403));
			return;
		}

		$resource['Resource']['deleted'] = '1';
		$fields = $this->Resource->getFindFields('delete', User::get('Role.name'));
		if (!$this->Resource->save($resource, true, $fields['fields'])) {
			$this->Message->error(__('Error while deleting'));
			return;
		}
		$this->Message->success(__('The resource was successfully deleted'));
	}

/**
 * Add a resource
 */
	public function add() {
		$datasource = ConnectionManager::getDataSource('default');
		$datasource->begin();

		// check the HTTP request method
		if (!$this->request->is('post')) {
			$datasource->rollback();
			return $this->Message->error(__('Invalid request method, should be POST'));
		}
		// check if data was provided
		if (!isset($this->request->data['Resource'])) {
			$datasource->rollback();
			return $this->Message->error(__('No data were provided'));
		}

		// set the data for validation and save
		$resourcepost = $this->request->data;
		$this->Resource->set($resourcepost);

		$fields = $this->Resource->getFindFields('save', User::get('Role.name'));

		// check if the data is valid
		if (!$this->Resource->validates()) {
			$datasource->rollback();
			return $this->Message->error(__('Could not validate resource data'));
		}

		$resource = $this->Resource->save($resourcepost, false, $fields['fields']);

		if ($resource === false) {
			$datasource->rollback();
			return $this->Message->error(__('The resource could not be saved'));
		}

		// Insert the given secret.
		if (!empty($resourcepost['Secret'])) {
			// Concat the resource infos.
			$secret = $resourcepost['Secret'][0];
			$secret['user_id'] = User::get('User.id');
			$secret['resource_id'] = $resource['Resource']['id'];

			// Validate the secret.
			$this->Resource->Secret->set($secret);
			if (!$this->Resource->Secret->validates()) {
				return $this->Message->error(__('Could not validate secret model'));
			}

			// Save the secret.
			$fields = $this->Resource->Secret->getFindFields('save', User::get('Role.name'));
			if (!$this->Resource->Secret->save($secret, false, $fields)) {
				return $this->Message->error(__('Could not save the secret'));
			}
		}

		// Save the relations
		if (isset($resourcepost['Category'])) {
			foreach ($resourcepost['Category'] as $cat) {
				$crdata = array(
					'CategoryResource' => array(
						'category_id' => $cat['id'],
						'resource_id' => $resource['Resource']['id']
					)
				);
				$this->Resource->CategoryResource->create();
				// check if the data is valid
				$this->Resource->CategoryResource->set($crdata);
				if (!$this->Resource->CategoryResource->validates()) {
					$datasource->rollback();
					return $this->Message->error(__('Could not validate CategoryResource'));
				}
				// Check that the user is well authorized to create a resource into the given category.
				if (!$this->Resource->CategoryResource->Category->isAuthorized($cat['id'], PermissionType::CREATE)) {
					$datasource->rollback();
					return $this->Message->error(__('You are not authorized to create a resource into the category'), array('code' => 403));
				}
				// if validation passes, then save the data
				$res = $this->Resource->CategoryResource->save();
				if (!$res) {
					$datasource->rollback();
					return $this->Message->error(__('Could not save the association'));
				}
			}
		}

		$datasource->commit();
		$this->Message->success(__('The resource was successfully saved'));

		// Return the just created resource
		$data = array(
			'Resource.id' => $resource['Resource']['id']
		);
		$options = $this->Resource->getFindOptions('view', User::get('Role.name'), $data);
		$resources = $this->Resource->find('all', $options);
		$this->set('data', $resources[0]);
	}

/**
 * Update a resource
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
			return $this->Message->error(__('You are not authorized to edit this resource'), array('code' => 403));
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
				return $this->Message->error(
					__('Could not validate Resource'),
					array('body' => $this->Resource->validationErrors)
				);
			}

			// Save data.
			$save = $this->Resource->save(
				$resourcepost,
				[
					'validate' => false,
					'fieldList' => $fields['fields'],
					'atomic' => false
				]);

			if (!$save) {
				return $this->Message->error(__('The resource could not be updated'));
			}
		}

		// Update the associated secrets.
		if (isset($resourcepost['Secret']) && !empty($resourcepost['Secret'])) {
			try {
				$this->Resource->saveSecrets($id, $resourcepost['Secret']);
			}
			catch(ValidationException $e) {
				$dataSource->rollback();
				return $this->Message->error($e->getMessage(), [ 'body' => $e->getInvalidFields() ]);
			}
			catch (Exception $e) {
				$dataSource->rollback();
				return $this->Message->error($e->getMessage());
			}
		}

		// Save the relations
		if (isset($resourcepost['Category']) && !empty($resourcepost['Category'])) {
			// If relations are given with the resource
			// we start by deleting previous associations
			$delete = $this->Resource->CategoryResource->deleteAll(array(
				'resource_id' => $id
			));
			if (!$delete) {
				$dataSource->rollback();
				return $this->Message->error(__('Could not delete Categories'));
			}
			// Save the new relations
			foreach ($resourcepost['Category'] as $cat) {
				$crdata = array(
					'CategoryResource' => array(
						'category_id' => $cat['id'],
						'resource_id' => $id
					)
				);

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

		// Retrieve the updated resource.
		$data = array(
			'Resource.id' => $resource['Resource']['id']
		);
		$options = $this->Resource->getFindOptions('view', User::get('Role.name'), $data);
		$resource = $this->Resource->find('first', $options);

		$this->Message->success(__('The resource was successfully updated'));
		$this->set('data', $resource);
	}

}


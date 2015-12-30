<?php
/**
 * Resources Controller
 *
 * @copyright     Copyright 2012 Passbolt.com
 * @license       http://www.passbolt.com/license
 * @package       app.Controller.ResourcesController
 * @since         version 2.12.7
 *
 */

/**
 * @SWG\Swagger(
 *     schemes={"http"},
 *     host="www.passbolt.com",
 *     basePath="/",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="Swagger passbolt",
 *         description="Passbolt is an open source password manager based on opengpg.js that allows you to share secrets with your team! Passbolt is coming soon, let's keep in touch!",
 *         termsOfService="http://www.passbolt.com/terms/",
 *         @SWG\Contact(
 *             email="contact@passbolt.com"
 *         ),
 *         @SWG\License(
 *             name="GPL 3",
 *             url="http://www.gnu.org/licenses/gpl-3.0.en.html"
 *         )
 *     ),
 *     @SWG\ExternalDocumentation(
 *         description="Find out more about Passbolt",
 *         url="http://passbolt.com/help"
 *     )
 * )
 */
/**
 * @SWG\Definition(
 *   definition="Header",
 *   @SWG\Xml(name="Header"),
 *   @SWG\Property(
 *     property="id",
 *     type="string",
 *     description="The id of the request"
 *   ),
 *   @SWG\Property(
 *     property="status",
 *     type="string",
 *     description="The status of the request"
 *   ),
 *   @SWG\Property(
 *     property="title",
 *     type="string",
 *     description="The title of the request"
 *   ),
 *   @SWG\Property(
 *     property="servertime",
 *     type="integer",
 *     description="The server time"
 *   ),
 *   @SWG\Property(
 *     property="message",
 *     type="string",
 *     description="Additional message"
 *   ),
 *   @SWG\Property(
 *     property="description",
 *     type="string",
 *     description="The description of the resource"
 *   ),
 *   @SWG\Property(
 *     property="controller",
 *     type="string",
 *     description="The controller name that have been called"
 *   ),
 *   @SWG\Property(
 *     property="action",
 *     type="string",
 *     description="The action name that have been called on the controller"
 *   )
 * )
 */
App::uses('Category', 'Model');
App::uses('CategoryResource', 'Model');

class ResourcesController extends AppController {

/**
 * @var $component application wide components
 */
	public $components = array(
		'Filter'
	);

/**
 * Get all resources
 * Renders a json object of the resources.
 *
 * @SWG\Get(
 *   path="/resources.json",
 *   summary="list resources",
 *   @SWG\Parameter(
 *     name="fltr_keywords",
 *     in="query",
 *     description="Keywords to filter by",
 *     required=false,
 *     type="string"
 *   ),
 *   @SWG\Parameter(
 *     name="fltr_case",
 *     in="query",
 *     description="Case to filter by",
 *     required=false,
 *     type="string",
 * 	   enum={
 * 		 "favorite",
 * 		 "shared"
 * 	   }
 *   ),
 *   @SWG\Parameter(
 *     name="fltr_order",
 *     in="query",
 *     description="Field to order by",
 *     required=false,
 *     type="string",
 * 	   enum={
 * 		 "modified",
 * 		 "expiry_date"
 * 	   }
 *   ),
 *   @SWG\Response(
 *     response=200,
 *     description="List with resources",
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
		// The additional information to pass to the model request
		$findData = array();
		// Whether we want also the resources of all subcategories
		$recursive = false;

		// Extract the filter from the request
		$filter = $this->Filter->fromRequest($this->request->query);
		// Merge the filter into the additional information to pass to the model request
		$data = array_merge($findData, $filter);
		if (isset($this->request->query['recursive']) && $this->request->query['recursive'] === 'true') {
			$recursive = true;
		}

		// A filter on category is provided
		// - check the validity of the given categories uid
		// - if recursive, filter also on sub-categories
		if (isset($findData['foreignModels']['Category.id'])) {
			// Tmp array to store the target categories and subcategories (if recursive provided)
			$categories = array();

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
 *
 * @SWG\Get(
 *   path="/resources/{id}.json",
 *   summary="Fetch resource details",
 *   @SWG\Parameter(
 * 		name="id",
 * 		in="path",
 * 		required=true,
 * 		type="string",
 * 		description="the id of the resource",
 *   ),
 *   @SWG\Response(
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
		$resource = $this->Resource->findById($id);
		if (!$resource) {
			return $this->Message->error(__('The resource does not exist'), array('code' => 404));
		}

		// check if user is authorized
		// the permissionable after find executed on the previous operation findById should drop
		// any record the user is not authorized to access. This test should always be true.
		if (!$this->Resource->isAuthorized($id, PermissionType::READ)) {
			return $this->Message->error(__('You are not authorized to access this resource'), array('code' => 403));
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
			return $this->Message->error(__('The resource id is missing'));
		}
		// check if the id is valid
		if (!Common::isUuid($id)) {
			return $this->Message->error(__('The resource id is invalid'));
		}
		$resource = $this->Resource->findById($id);
		if (!$resource) {
			return $this->Message->error(__('The resource does not exist'), array('code' => 404));
		}

		// check if user is authorized
		if (!$this->Resource->isAuthorized($id, PermissionType::UPDATE)) {
			return $this->Message->error(__('You are not authorized to delete this resource'), array('code' => 403));
		}

		$resource['Resource']['deleted'] = '1';
		$fields = $this->Resource->getFindFields('delete', User::get('Role.name'));
		if (!$this->Resource->save($resource, true, $fields['fields'])) {
			return $this->Message->error(__('Error while deleting'));
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
 * @param uuid $id The resource to update
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

		// @todo Begin transaction.

		// check if data was provided
		if (!isset($resourcepost['Resource']) && !isset($resourcepost['Category'])) {
			return $this->Message->error(__('No data were provided'));
		}

		// Update the resource
		if (isset($resourcepost['Resource'])) {

			// Get the meaningful fields for this operation
			$fields = $this->Resource->getFindFields('edit', User::get('Role.name'));

			// Validate the resource data
			$this->Resource->set($resourcepost);
			// @todo validate only the fields required by this operation
			if (!$this->Resource->validates()) {
				return $this->Message->error(
					__('Could not validate Resource'),
					array('body' => $this->Resource->validationErrors)
				);
			}
			$save = $this->Resource->save($resourcepost, false, $fields['fields']);
			if (!$save) {
				return $this->Message->error(__('The resource could not be updated'));
			}
		}

		// Update the associated secrets.
		if (isset($resourcepost['Secret']) && !empty($resourcepost['Secret'])) {
			$secrets = array();

			// Delete all the previous secrets.
			$this->Resource->Secret->deleteAll(array(
				'Secret.resource_id' => $id
			), false);

			// Validate the given resources.
			foreach ($resourcepost['Secret'] as $i => $secret) {
				// Force the resource id if empty.
				if (empty($secret['resource_id'])) {
					$secret['resource_id'] = $resource['Resource']['id'];
				}
				// Force the user id if empty.
				if (empty($secret['user_id'])) {
					return $this->Message->error(__('user id was not provided for the secret'));
				}
				// Validate the data.
				$this->Resource->Secret->set($secret);
				if (!$this->Resource->Secret->validates()) {
					return $this->Message->error(
						__('Could not validate secret model'),
						array('body' => $this->Resource->Secret->validationErrors)
					);
				}
				$secrets[] = $secret;
			}

			// Save the secrets.
			$fields = $this->Resource->Secret->getFindFields('update', User::get('Role.name'));
			if (!$this->Resource->Secret->saveMany($secrets, $fields)) {
				return $this->Message->error(__('Could not save the secrets'));
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
					$this->Message->error(__('Could not validate CategoryResource'));
					return;
				}
				// if validation passes, then save the data
				$res = $this->Resource->CategoryResource->save();
				if (!$res) {
					$this->Message->error(__('Could not save the association'));
					return;
				}
			}
		}

		// Retrieve the just updated resource
		$data = array(
			'Resource.id' => $resource['Resource']['id']
		);
		$options = $this->Resource->getFindOptions('view', User::get('Role.name'), $data);
		$resource = $this->Resource->find('first', $options);

		$this->Message->success(__('The resource was successfully updated'));
		$this->set('data', $resource);
	}

}


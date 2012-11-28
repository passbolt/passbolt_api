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
 * Get all resources
 * Renders a json object of the resources
 *
 * @param uuid $categoryId the id of the category
 * @param bool recursive, whether we want also the resources of all subcategories
 * @return void
 */
	public function index() {
		$keywords = isset($this->request->query['keywords']) ? $this->request->query['keywords'] : '';
		$categoriesId = isset($this->request->query['categories_id']) ? explode(',', $this->request->query['categories_id']) : array();
		$recursive = isset($this->request->query['recursive']) ? $this->request->query['recursive'] === 'true' : false;
		$data = array(
			'CategoryResource.category_id' => array()
		);
		
		// if categories id are provided check their validity, and build model request with
		$categoriesIdLength = count($categoriesId);
		for($i=0; $i<$categoriesIdLength; $i++) {
			$categoryId = $categoriesId[$i];

			// if a category id is provided check is validity
			if ($categoryId != null && !Common::isUuid($categoryId)) {
				$this->Message->error(__('The category id is invalid'));
				return;
			}

			// check if the category exists
			$category = $this->Resource->CategoryResource->Category->findById($categoryId);
			if (!$category) {
				$this->Message->error(__('The category doesn\t exist'));
				return;
			}

			if ($recursive == false) {
				$data['CategoryResource.category_id'][] = $categoryId;
			} else {
				// If the category has yet been added to the model request, continue
				if(in_array($categoryId, $data['CategoryResource.category_id'])) {
					continue;
				}
				$cats = $this->Resource->CategoryResource->Category->find(
					'all',
					array(
						'conditions' => array(
							'Category.lft >=' => $category['Category']['lft'],
							'Category.rght <=' => $category['Category']['rght']
							),
						'order' => array(
							'Category.lft' => 'ASC'
							)
					)
				);
				foreach ($cats as $cat) {
					$data['CategoryResource.category_id'][] = $cat['Category']['id'];
				}
			}
		}

		// if keywords provided build the model request with
		if(!empty($keywords)) {
			$data['Resource.name'] = "$keywords";
		}
		
		$this->Resource->bindModel(array('hasOne' => array('CategoryResource')));
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

	public function view($id=null) {
		// check if the category id is provided
		if (!isset($id)) {
			$this->Message->error(__('The resource id is missing'));
			return;
		}
		// check if the id is valid
		if (!Common::isUuid($id)) {
			$this->Message->error(__('The resource id invalid'));
			return;
		}
		// check if it exists
		$data = array(
			'Resource.id' => $id
		);
		$options = $this->Resource->getFindOptions('view', User::get('Role.name'), $data);
		$resources = $this->Resource->find('all', $options);
		if (!count($resources)) {
			$this->Message->error(__('The resource does not exist'));
			return;
		}
		$this->set('data', $resources[0]);
		$this->Message->success();
	}

/**
 * Get all resources in a category id
 * Renders a json object of the resources
 *
 * @param uuid $categoryId the id of the category
 * @param bool recursive, whether we want also the resources of all subcategories
 * @return void
 */
	public function viewByCategory($categoryId=null, $recursive=false) {
			// check if the category id is provided
		if (!isset($categoryId)) {
			$this->Message->error(__('The category id is missing'));
			return;
		}
			// check if the id is valid
		if (!Common::isUuid($categoryId)) {
			$this->Message->error(__('The category id invalid'));
			return;
		}

		// check if the category exists
		$category = $this->Resource->CategoryResource->Category->findById($categoryId);
		if (!$category) {
			$this->Message->error(__('The category doesn\t exist'));
			return;
		}

		if ($recursive == false) {
			$data = array('CategoryResource.category_id' => $categoryId);
		} else {
			$cats = $this->Resource->CategoryResource->Category->find(
				'all',
				array(
					'conditions' => array(
						'Category.lft >=' => $category['Category']['lft'],
						'Category.rght <=' => $category['Category']['rght']
						),
					'order' => array(
						'Category.lft' => 'ASC'
						)
				)
			);
			foreach ($cats as $cat) {
				$data['CategoryResource.category_id'][] = $cat['Category']['id'];
			}
		}
		$this->Resource->bindModel(array('hasOne' => array('CategoryResource')));
		$options = $this->Resource->getFindOptions('viewByCategory', User::get('Role.name'), $data);
		$resources = $this->Resource->find('all', $options);

		if (!$resources) {
			$resources = array();
		}

		$this->set('data', $resources);
		$this->Message->success();
	}

/**
 * Delete a resource
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
			$this->Message->error(__('The resource id invalid'));
			return;
		}
		$resource = $this->Resource->findById($id);
		if (!$resource) {
			$this->Message->error(__('The resource doesn\'t exist'));
			return;
		}
		$resource['Resource']['deleted'] = '1';
		$fields = $this->Resource->getFindFields('delete', User::get('Role.name'));
		if (!$this->Resource->save($resource, true, $fields['fields'])) {
			$this->Message->error(__('Error while deleting'));
			return;
		}
		$this->Message->success(__('The resource was sucessfully deleted'));
	}

/**
 * Add a resource
 */
	public function add() {
		// check the HTTP request method
		if (!$this->request->is('post')) {
			$this->Message->error(__('Invalid request method, should be POST'));
			return;
		}
		// check if data was provided
		if (!isset($this->request->data['Resource'])) {
			$this->Message->error(__('No data were provided'));
			return;
		}

		// set the data for validation and save
		$resourcepost = $this->request->data;
		$this->Resource->set($resourcepost);

		$fields = $this->Resource->getFindFields('save', User::get('Role.name'));

		// check if the data is valid
		if (!$this->Resource->validates()) {
			$this->Message->error(__('Could not validate resource data'));
			return;
		}

		//$this->Resource->contain(array('Secret'));
		$resource = $this->Resource->save($resourcepost, false, $fields['fields']);

		if ($resource === false) {
			$this->Message->error(__('The resource could not be saved'));
			return;
		}
		// Save the associated secret
		if (isset($resourcepost['Secret'])) {
			$resourcepost['Secret']['resource_id'] = isset($resourcepost['Secret']['resource_id']) ? $resourcepost['Secret']['resource_id'] : $resource['Resource']['id'];
			$resourcepost['Secret']['user_id'] = isset($resourcepost['Secret']['user_id']) ? $resourcepost['Secret']['user_id'] : User::get('User.id');
			$this->Resource->Secret->set($resourcepost['Secret']);
			if (!$this->Resource->Secret->validates()) {
				$this->Message->error(__('Could not validate secret model'));
				return;
			}
			$fields = $this->Resource->Secret->getFindFields('save', User::get('Role.name'));
			// TODO : Encrypt data and save it once per user
			if (!$this->Resource->Secret->save($resourcepost['Secret'], false, $fields['fields'])) {
				$this->Message->error(__('Could not save secret'));
				return;
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
		$this->Message->success(__('The resource was sucessfully saved'));
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
		// check if data was provided
		if ($id == null) {
			$this->Message->error(__('No valid id was provided'));
			return;
		}

		// check the HTTP request method
		if (!$this->request->is('put')) {
			$this->Message->error(__('Invalid request method, should be PUT'));
			return;
		}
		// check if data was provided
		if (!isset($this->request->data['Resource']) && !isset($this->request->data['Category'])) {
			$this->Message->error(__('No data were provided'));
			return;
		}

		// set the data for validation and save
		$resourcepost = $this->request->data;

		if (isset($resourcepost['Resource'])) {
			// check if the id is valid
			if (!Common::isUuid($resourcepost['Resource']['id'])) {
				$this->Message->error(__('The resource id invalid'));
				return;
			}
			// get the resource id
			$resource = $this->Resource->findById($id);
			if (!$resource) {
				$this->Message->error(__('The resource doesn\'t exist'));
				return;
			}

			$this->Resource->set($resourcepost);
			if (!$this->Resource->validates()) {
				$this->Message->error(__('Could not validate Resource'));
				return;
			}
			$fields = $this->Resource->getFindFields('edit', User::get('Role.name'));
			$save = $this->Resource->save($resourcepost, false, $fields['fields']);
			if (!$save) {
				$this->Message->error(__('The resource could not be updated'));
				return;
			}
		}
		// Save the relations
		if (isset($resourcepost['Category'])) {
			// If relations are given with the resource
			// we start by deleting previous associations
			$delete = $this->Resource->CategoryResource->deleteAll(
				array (
					'resource_id' => $id
				)
			);
			if (!$delete) {
				$this->Message->error(__('Could not delete Categories'));
				return;
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
		$this->Message->success(__('The resource was sucessfully updated'));
		$data = array(
			'Resource.id' => $resource['Resource']['id']
		);
		$options = $this->Resource->getFindOptions('view', User::get('Role.name'), $data);
		$resources = $this->Resource->find('all', $options);
		$this->set('data', $resources[0]);
	}
}


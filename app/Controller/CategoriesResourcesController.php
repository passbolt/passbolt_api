<?php
/**
 * Categories Resources controller
 * This file will define how categories_resources are managed. only crud functions
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Controller.CategoriesResourcesController
 * @since        version 2.12.7
 */

class CategoriesResourcesController extends AppController {

	public $uses = array('CategoryResource');

/**
 * Get a categoryResource
 * Renders a json object of the resource
 *
 * @param int $id the id of the resource
 * @return void
 */
	public function view($id=null) {
		// check if the category id is provided
		if (!isset($id)) {
			$this->Message->error(__('The categoryResource id is missing'));
			return;
		}
		// check if the id is valid
		if (!is_int((int)$id)) {
			$this->Message->error(__('The categoryResource id invalid'));
			return;
		}
		// check if it exists
		$data = array(
			'CategoryResource.id' => $id
		);
		$options = $this->CategoryResource->getFindOptions('view', $data);
		$cr = $this->CategoryResource->find('all', $options);
		if (!count($cr)) {
			$this->Message->error(__('The CategoryResource does not exist'));
			return;
		}
		$this->set('data', $cr[0]);
		$this->Message->success();
	}

/**
 * Delete a categoryResource
 * @param int id the id of the resource to delete
 */
	public function delete($id = null) {
		// check if the category id is provided
		if (!isset($id)) {
			$this->Message->error(__('The categoryResource id is missing'));
			return;
		}
		// check if the id is valid
		if (!is_int((int)$id)) {
			$this->Message->error(__('The categoryResource id is invalid'));
			return;
		}
		$resource = $this->CategoryResource->findById($id);
		if (!$resource) {
			$this->Message->error(__('The categoryResource doesn\'t exist'));
			return;
		}

		if (!$this->CategoryResource->delete($id)) {
			$this->Message->error(__('Error while deleting'));
			return;
		}
		$this->Message->success(__('The categoryResource was sucessfully deleted'));
	}

/**
 * Add a CategoriResource
 * @param post : the posted data
 */
	public function add() {
		// check the HTTP request method
		if (!$this->request->is('post')) {
			$this->Message->error(__('Invalid request method, should be POST'));
			return;
		}
		// check if data was provided
		if (!isset($this->request->data['CategoryResource'])) {
			$this->Message->error(__('No data were provided'));
			return;
		}

		// set the data for validation and save
		$crpost = $this->request->data;
		$this->CategoryResource->set($crpost);

		$fields = $this->CategoryResource->getFindFields('add');

		// check if the data is valid
		if (!$this->CategoryResource->validates()) {
			$this->Message->error(__('Could not validate resource data'));
			return;
		}

		$cr = $this->CategoryResource->save($crpost, false, $fields['fields']);
		if ($cr === false) {
			$this->Message->error(__('The CategoryResource could not be saved'));
			return;
		}
		$fields = $this->CategoryResource->getFindFields('add');
		$this->set('data', $this->CategoryResource->findById($cr['CategoryResource']['id'], $fields['fields']));
		$this->Message->success(__('The categoryResource was sucessfully added'));
	}
}

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
class CategoryResourcesController extends AppController {

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
		if (!is_int($id)) {
			$this->Message->error(__('The categoryResource id invalid'));
			return;
		}
		// check if it exists
		$data = array(
			'CategoryResource.id' => $id
		);
		$options = $this->Resource->getFindOptions('view', $data);
		$cr = $this->Resource->find('all', $options);
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
		if (!is_int($id)) {
			$this->Message->error(__('The categoryResource id is invalid'));
			return;
		}
		$resource = $this->CategoryResource->findById($id);
		if (!$resource) {
			$this->Message->error(__('The resource doesn\'t exist'));
			return;
		}
		$resource['Resource']['deleted'] = '1';
		$fields = $this->Resource->getFindFields('delete');
		if (!$this->Resource->save($resource, true, $fields['fields'])) {
			$this->Message->error(__('Error while deleting'));
			return;
		}
		$this->Message->success(__('The resource was sucessfully deleted'));
	}

}
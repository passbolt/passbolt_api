<?php
/**
 * Categories controller
 * This file will define how categories are managed
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Controller.CategoriesController
 * @since        version 2.12.7
 */

App::uses('CategoryType', 'Model');
App::uses('Sanitize', 'Utility');

class CategoriesController extends AppController {

/**
 * index - get the list of categories
 */
	public function index($children = false) {
		$data = array();

		$o = $this->Category->getFindOptions('getRoots');
		$categories = $this->Category->find('threaded', $o);

		if (!$children) {
			$data = $categories;
		} else {
			foreach ($categories as $category) {
				$o = $this->Category->getFindOptions('getWithChildren', $category);
				$result = $this->Category->find('threaded', $o);
				$data[] = $result[0];
			}
		}
		$this->set('data', $data);
		$this->Message->success();
	}

/**
 * Get a category
 * Renders a json object with the nested categories
 *
 * @param uuid $id the id of the category
 * @param bool $children whether or not we want the children returned
 * @return void
 *
 */
	public function view($id=null, $children=false) {
		// check if the category id is provided
		if (!isset($id)) {
			$this->Message->error(__('The category id is missing'));
			return;
		}
		// check if the id is valid
		if (!Common::isUuid($id)) {
			$this->Message->error(__('The category id invalid'));
			return;
		}
		// check if it exists
		$category = $this->Category->findById($id);
		if (!$category) {
			$this->Message->error(__('The category does not exist'));
			return;
		}

		// get the thread of children
		if ($children == true) {
			$o = $this->Category->getFindOptions('getWithChildren', $category);
			$data = $this->Category->find('threaded', $o);
			$this->set('data', $data[0]);
		} else {
			$data = array('Category.id' => $id);
			$o = $this->Category->getFindOptions('view', $data);
			$this->set('data', $this->Category->find('first', $o));
		}
		$this->Message->success();
	}

/**	
 * get the children for a corresponding category
 * @param $id, the id of the parent category
 * @return void
 * @todo Rest mapping in routes
 */
	public function children($id=null) {
		// check if the id is provided
		if (!isset($id)) {
			$this->Message->error(__('The category id is missing'));
			return;
		}
		// check if the id is valid
		if (!Common::isUuid($id)) {
			$this->Message->error(__('The category id invalid'));
			return;
		}
		// check if the category exists
		$category = $this->Category->findById($id);
		if (!$category) {
			$this->Message->error(__('The category does not exist'));
			return;
		}
		// find children thread and return
		$o = $this->Category->getFindOptions('getChildren', $category);
		$this->set('data', $this->Category->find('threaded', $o));
		$this->Message->success();
	}

/**
 * Add a category inside the tree, and return a success object with the added category
 *
 * Post Variables
 *   $parentId, the parent id of the category
 *   $name, the name of the category
 *   $position (optional), the position of the category from the parent (Counting starts from 1, not from 0)
 *     if position is not available (example : position 2 when there are no children,
 *			the category will be inserted in last)
 *     if position is 0, it will not be handled. Count starts from 1.
 *   $type (optional), the type of the category (default is set is missing)
 *
 * @return void
 */
	public function add() {
		// check the HTTP request method
		if (!$this->request->is('post')) {
			$this->Message->error(__('Invalid request method, should be POST'));
			return;
		}
		// check if data was provided
		if (!isset($this->request->data['Category'])) {
			$this->Message->error(__('No data were provided'));
			return;
		}

		// set the data for validation and save
		$catpost = $this->request->data;
		// Sanitize
		$catpost = Sanitize::clean($catpost);
		$this->Category->set($catpost);

		// check if the data is valid
		if (!$this->Category->validates()) {
			$this->Message->error(__('Could not validate category data'));
			return;
		}

		// try to save
		$fields = $this->Category->getFindFields("add");
		$this->Category->create();
		$category = $this->Category->save($catpost, true, $fields['fields']);
		if ($category === false) {
			$this->Message->error(__('The category could not be saved'));
			return;
		}

		// Manage the position
		if (isset($catpost['Category']['position']) && $catpost['Category']['position'] > 0) {
			$nbChildren = $this->Category->childCount($catpost['Category']['parent_id'], true);
			if ($catpost['Category']['position'] < $nbChildren) {
				$steps = ($catpost['Category']['position'] == 1 ? true : $nbChildren - $catpost['Category']['position']);
				$this->Category->moveUp($category['Category']['id'], $steps);
			}
		}
		$fields = $this->Category->getFindFields('addResult');
		$this->set('data', $this->Category->findById($category['Category']['id'], $fields['fields']));
		$this->Message->success(__('The category was sucessfully added'));
	}

/**
 * Edit a category
 */
	public function edit($id) {
		// check the HTTP request method
		if (!$this->request->is('put')) {
			$this->Message->error(__('Invalid request method, should be PUT'));
			return;
		}
		// check if data was provided
		if (!isset($this->request->data['Category'])) {
			$this->Message->error(__('No data were provided'));
			return;
		}

		// sanitize
		$this->request->data = Sanitize::clean($this->request->data);

		// check if the id is valid
		if (!Common::isUuid($id)) {
			$this->Message->error(__('The category id invalid'));
			return;
		}
		// check if the category exists
		$category = $this->Category->findById($id);
		if (!$category) {
			$this->Message->error(__('The category does not exist'));
			return;
		}
		$this->Category->set($this->request->data);
		// check if the data is valid
		if (!$this->Category->validates()) {
			$this->Message->error(__('Could not validate category data'));
			return;
		}
		// try to save
		$fields = $this->Category->getFindFields("edit");
		$this->request->data['Category']['id'] = $id;
		$category = $this->Category->save($this->request->data, true, $fields['fields']);
		if ($category === false) {
			$this->Message->error(__('The category could not be updated'));
			return;
		}

		$this->Message->success(__('The category was sucessfully updated'));
	}

/**	
 * Delete a category in the tree
 * @param $id, the Category id
 * @return void
 */
	public function delete($id=null) {
		// check if the id is provided
		if (!isset($id)) {
			$this->Message->error(__('The category id is missing'));
			return;
		}
		// check if the id is valid
		if (!Common::isUuid($id)) {
			$this->Message->error(__('The category id invalid'));
			return;
		}
		// delete
		if ($this->Category->delete($id)) {
			$this->Message->success(__('The category was succesfully deleted'));
		} else {
			$this->Message->error(__('The category could not be deleted.'));
		}
	}

/**
 * Rename a category
 * @param $id, the id of the category
 * @param $name, the name of the category
 * @return void
 */
	public function rename($id=null, $name="") {
		// check if the category id is provided
		if (!isset($id)) {
			$this->Message->error(__('The category id is not provided'));
			return;
		}
		// check if the id is valid
		if (!Common::isUuid($id)) {
			$this->Message->error(__('The category id invalid'));
			return;
		}

		// check if the category exist
		$category = $this->Category->findById($id);
		if (empty($category)) {
			$this->Message->error(__('The category could not be found'));
			return;
		}

		// save the new name only
		$c['Category'] = array(
			'id'		=> $id,
			'name'	=> $name
		);

		// sanitize the data
		$c['Category'] = Sanitize::clean($c['Category']);

		$this->Category->set($c);
		if (!$this->Category->validates()) {
			$this->Message->error(__('Could not validate category data'));
			return;
		}

		$fields = $this->Category->getFindFields("rename");
		if ($this->Category->save($c, true, $fields['fields'])) {
			$this->Message->success(__('The category have been renamed'));
		} else {
			$this->Message->error(__('The category could not be saved'));
		}
	}

/**
 * Move a category in the tree
 * @param $id, the id of the category to move
 * @param $position, the position among the sieblings
 * @param $parentId, the new parent
 * @return void
 */
	public function move($id=null, $position=null, $parentId=null) {
		$position = Sanitize::clean($position);
		$parentId = Sanitize::clean($parentId);
		// check if the category is provided
		if (!isset($id)) {
			$this->Message->error(__('The category id is not provided'));
			return;
		}

		// check if the id is valid
		if (!Common::isUuid($id)) {
			$this->Message->error(__('The category id invalid'));
			return;
		}

		// check if it exist
		$category = $this->Category->findById($id);
		if (!$category) {
			$this->Message->error(__('The category does not exist'));
			return;
		}

		// check if the position is ok
		if ($position < 0) {
			$this->Message->error(__('It is not possible to move the category at this position'));
			return;
		}

		$result = $this->Category->move($id, $position, $parentId);
		// deliver some results
		if ($result) {
			$this->Message->success(__('The category was sucessfully moved'));
		} else {
			$this->Message->error(__('The category could not be moved'));
		}
	}

/**
 * Set the type of a category
 * @param uuid $id the id of the category
 * @param varchar $typeName, the name of the type
 * @return 1 if success, 0 if failure
 */
	public function type($id=null, $typeName=null) {
		$typeName = Sanitize::clean($typeName);
		// check if the category is provided
		if (!isset($id)) {
			$this->Message->error(__('The category id is not provided'));
			return;
		}

		// check if the id is valid
		if (!Common::isUuid($id)) {
			$this->Message->error(__('The category id invalid'));
			return;
		}

		$type = $this->Category->CategoryType->findByName($typeName);
		if (!$type) {
			$this->Message->error(__('The type does not exist'));
			return;
		}

		$category = $this->Category->findById($id);
		if (!$category) {
			$this->Message->error(__('The category does not exist'));
			return;
		}

		$category['Category']['category_type_id'] = $type['CategoryType']['id'];
		$category = $this->Category->save($category);

		if (!$category) {
			 $this->Message->error(__('The type could not be changed'));
				return;
		}
		$this->Message->success(__('The type was succesfully set'));
	}
}

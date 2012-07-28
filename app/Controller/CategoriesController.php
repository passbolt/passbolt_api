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
class CategoriesController extends AppController {

/**
 * get a category
 * Renders a json object with the nested categories
 * @param uuid $id the id of the category
 * @param bool $children whether or not we want the children returned
 * @return void
 */
	public function get($id=null, $children=false) {
		// check if the category id is provided
		if (!isset($id)) {
			$this->Message->error(__('The category id is missing'));
			return;
		}

		// check if it exists
		$fields = $this->Category->getFindFields('get');
		$category = $this->Category->findById($id);
		if (empty($category)) {
			$this->Message->error(__('The category doesn\'t exist'));
			return;
		}

		// get the thread of children
		if ($children == true) {
			$conditions = $this->Category->getFindConditions('get', $category);
			$data = $this->Category->find('threaded', array_merge($conditions, $fields));
			$this->set('data', $data);
		} else {
			$this->set('data', $this->Category->findById($id, $fields['fields']));
		}
		$this->Message->success();
	}

/**	
 * get the children for a corresponding category
 * @param $id, the id of the parent category
 * @return void
 */
	public function getChildren($id=null) {
		// check if the id is provided
		if (!isset($id)) {
			$this->Message->error(__('The category id is missing'));
			return;
		}

		// check if the category exist
		$category = $this->Category->findById($id);
		if ($category) {
			$this->Message->error(__('The category doesn\'t exist'));
			return;
		}

		// find children thread and return
		$o = Category::getFindOptions('getChildren', $category);
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
		if (!isset($this->request->data)) {
			$this->Message->error(__('No data were provided'));
			return;
		}

		// set the data for validation and save
		$catpost = $this->request->data;
		$this->Category->set($catpost);

		// check if the data is valid
		if (!$this->Category->validates()) {
			$this->Message->error(__('Could not validate category data'));
			return;
		}

		// trye to save
		$this->Category->create();
		$category = $this->Category->save($catpost);
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
		$fields = Category::getFindFields('add');
		$this->set('data', $this->Category->findById($category['Category']['id'], $fields['fields']));
		$this->Message->success(__('The category was sucessfully added'));
	}

/**	
 * Delete a category in the tree
 * @param $id, the Category id
 * @return void
 */
	public function delete($id=null) {
		if (!isset($id)) {
			$this->Message->error(__('The category id is missing'));
		} else {
			if (!$this->Category->delete($id)) {
				$this->Message->error(__('The category could not be deleted.'));
			} else {
				$this->Message->success(__('The category was succesfully deleted'));
			}
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

		// check if the category exist
		$category = $this->Category->findById($id);
		if (empty($category)) {
			$this->Message->error(__('The category could not be found'));
			return;
		}

		// save the new name only
		$c['Category'] = array(
			'id'   => $id;
			'name' => $name;
		);
		if (!$this->Category->save($c)) {
			$this->Message->error(__('The category could not be saved'));
			return;
		}
		$this->Message->success(__('The category have been renamed'));
	}

/**
 * Move a category in the tree
 * @param $id, the id of the category to move
 * @param $position, the position among the sieblings
 * @param $parentId, the new parent
 * @return void
 */
	public function move($id=null, $position=null, $parentId=null) {
		// check if the category is provided
		if (!isset($id)) {
			$this->Message->error(__('The category id is not provided'));
			return;
		}

		// check if it exist
		$category = $this->Category->findById($id);
		if (empty($category)) {
			$this->Message->error(__('The category does not exist'));
			return;
		}

		// check if the position is ok
		if ($position < 0) {
			$this->Message->error(__('It is not possible to move the category at this position'));
			return;
		}

		// @todo remy: can some of this be moved to the model intead?
		// First, manage the parent
		$parentId = ($parentId == null ? $category['Category']['parent_id'] : $parentId);
		if ($category['Category']['parent_id'] != $parentId) {
			$category['Category']['parent_id'] = $parentId;
			$category = $this->Category->save($category);
			if (!$category) {
				$this->Message->error(__('The category could not be moved'));
				return;
			}
		}
		// then, manage the position
		$nbChildren = $this->Category->childCount($parentId, true);
		// if the position is first one or last one
		if ($position == 1) {
			$result = $this->Category->moveUp($id, true);
		} elseif ($position >= $nbChildren) {
			$result = $this->Category->moveDown($id, true);
		} else {
			$currentPosition = $this->Category->getPosition($id);
			$steps = $currentPosition - $position;
			echo "position = $currentPosition, steps = $steps";
			if ($steps > 0) {
				$result = $this->Category->moveUp($id, $steps);
			} else {
				$result = $this->Category->moveDown($id, -($steps));
			}
		}
		// deliver some results
		if ($result) {
			$this->Message->success(__('The category was sucessfully moved'));
		} else {
			$this->Message->error(__('The category could not be moved'));
		}
	}

/**
 * Set the type of a category
 * @param $id, the id of the category
 * @param $type, the type
 * @return 1 if success, 0 if failure
 */
	public function setType($id=null, $type=null) {
	}
}

<?php
/**
 * Categories controller
 * This file will define how categories are managed
 *
 * @copyright	(c) 2015-present Passbolt.com
 * @licence		GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

App::uses('CategoryType', 'Model');
App::uses('User', 'Model');

class CategoriesController extends AppController {

/**
 * Index get the list of top categories
 *
 * @return void
 */
	public function index() {
		$data = array();
		$children = false;

		if (isset($this->request->query['children']) && $this->request->query['children'] === 'true') {
			$children = true;
		}

		// foreach roots categories, find its children ! Done with the tree behavior
		if (!$children) {
			$o = $this->Category->getFindOptions('index', User::get('Role.name'));
			$data = $this->Category->find('all', $o);
		} else {
			// Find roots categories.
			// We disable the permissionnable behavior to get all the top categories, whatever
			// the user is not able to READ them. We'll go through each top categories and return
			// all the categories the user is allowed to READ.
			$this->Category->Behaviors->disable('Permissionable');
			$o = $this->Category->getFindOptions('index', User::get('Role.name'));
			$categories = $this->Category->find('all', $o);
			$this->Category->Behaviors->enable('Permissionable');

			foreach ($categories as $category) {
				$o = $this->Category->getFindOptions('getWithChildren', User::get('Role.name'), $category);
				$result = $this->Category->find('threaded', $o);
				$data = array_merge($data, $result);
			}
		}

		$this->set('data', $data);
		$this->Message->success();
	}

/**
 * Get a category.
 * If children options is found in the request parameters, the function will return
 * the Category with its children.
 *
 * @param uuid $id the id of the category
 * @return void
 */
	public function view($id = null) {
		$children = isset($this->request->query['children']) ? ($this->request->query['children'] === 'true') : false;

		// check if the category id is provided
		if (!isset($id)) {
			$this->Message->error(__('The category id is missing'));
			return;
		}
		// check if the id is valid
		if (!Common::isUuid($id)) {
			$this->Message->error(__('The category id is invalid'));
			return;
		}

		// check if it exists
		$category = $this->Category->findById($id);
		if (!$category) {
			$this->Message->error(__('The category does not exist'), array('code' => 404));
			return;
		}

		// check if user is authorized
		// the permissionable after find executed on the previous operation findById should drop
		// any record the user is not authorized to access. This test should always be true.
		if (!$this->Category->isAuthorized($id, PermissionType::READ)) {
			$this->Message->error(__('You are not authorized to access this category'), array('code' => 403));
			return;
		}

		// get the thread of children
		if ($children == true) {
			$o = $this->Category->getFindOptions('getWithChildren', User::get('Role.name'), $category);
			$data = $this->Category->find('threaded', $o);
			$this->set('data', $data[0]);
		} else {
			$data = array('Category' => array('id' => $id));
			$o = $this->Category->getFindOptions('view', User::get('Role.name'), $data);
			$this->set('data', $this->Category->find('first', $o));
		}
		$this->Message->success();
	}

/**
 * Get the children for a corresponding category
 *
 * @param string $id the uuid of the parent category
 * @return void
 */
	public function children($id = null) {
		// check if the id is provided
		if (!isset($id)) {
			$this->Message->error(__('The category id is missing'));
			return;
		}
		// check if the id is valid
		if (!Common::isUuid($id)) {
			$this->Message->error(__('The category id is invalid'));
			return;
		}
		// check if the category exists
		$category = $this->Category->findById($id);
		if (!$category) {
			$this->Message->error(__('The category does not exist'), array('code' => 404));
			return;
		}
		// find children thread and return
		$o = $this->Category->getFindOptions('getChildren', User::get('Role.name'), $category);
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
 *            the category will be inserted in last)
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

		$catpost = $this->request->data;

		// Check if the user is allowed to create in the parent category.
		if (isset($catpost['Category']['parent_id'])) {
			if (!$this->Category->isAuthorized($catpost['Category']['parent_id'], PermissionType::CREATE)) {
				$this->Message->error(__('You are not authorized to create a category into the given parent category'), array('code' => 403));
				return;
			}
		}

		// set the data for validation and save
		$this->Category->set($catpost);

		// check if the data is valid
		if (!$this->Category->validates()) {
			$this->Message->error(__('Could not validate category data'));
			return;
		}

		// try to save
		$fields = $this->Category->getFindFields("add", User::get('Role.name'));
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

		// Get back the category data to return to the client
		$data = array('Category' => array('id' => $category['Category']['id']));
		$options = $this->Category->getFindOptions('addResult', User::get('Role.name'), $data);
		$this->set('data', $this->Category->find('first', $options));
		$this->Message->success(__('The category was successfully added'));
	}

/**
 * Edit a category
 *
 * @param string $id the uuid of the category
 * @return void
 */
	public function edit($id = null) {
		// check the HTTP request method
		if (!$this->request->is('put')) {
			$this->Message->error(__('Invalid request method, should be PUT'));
			return;
		}

		// check if the id is provided
		if (!isset($id)) {
			$this->Message->error(__('The category id is missing'));
			return;
		}

		// check if the id is valid
		if (!Common::isUuid($id)) {
			$this->Message->error(__('The category id is invalid'));
			return;
		}

		// check if the category exists
		$category = $this->Category->findById($id);
		if (!($category)) {
			$this->Message->error(__('The category does not exist'), array('code' => 404));
			return;
		}

		// Check if the user is allowed to update the category.
		if (!$this->Category->isAuthorized($id, PermissionType::UPDATE)) {
			$this->Message->error(__('You are not authorized to edit this category'), array('code' => 403));
			return;
		}

		// check if data was provided
		if (!isset($this->request->data['Category'])) {
			$this->Message->error(__('No data were provided'));
			return;
		}

		// If the parent Category provided has changed.
		// Check if the user is allowed to move the Category inside the given Category.
		if (isset($this->request->data['Category']['parent_id']) && $category['Category']['parent_id'] != $this->request->data['Category']['parent_id']) {
			if (!$this->Category->isAuthorized($this->request->data['Category']['parent_id'], PermissionType::CREATE)) {
				$this->Message->error(__('You are not authorized to create a category into the given parent category'), array('code' => 403));
				return;
			}
		}

		// Check if the data is valid.
		$this->Category->set($this->request->data);
		if (!$this->Category->validates()) {
			$this->Message->error(__('Could not validate category data'));
			return;
		}

		// try to save
		$fields = $this->Category->getFindFields('edit', User::get('Role.name'));
		$this->request->data['Category']['id'] = $id;
		$category = $this->Category->save($this->request->data, true, $fields['fields']);
		if ($category === false) {
			$this->Message->error(__('The category could not be updated'));
			return;
		}

		$this->Message->success(__('The category was successfully updated'));
	}

/**
 * Delete a category in the tree
 *
 * @param string $id the category uuid
 * @return void
 */
	public function delete($id = null) {
		// check if the id is provided
		if (!isset($id)) {
			$this->Message->error(__('The category id is missing'));
			return;
		}

		// check if the id is valid
		if (!Common::isUuid($id)) {
			$this->Message->error(__('The category id is invalid'));
			return;
		}

		// check if the category exists
		$category = $this->Category->findById($id);
		if (!$category) {
			$this->Message->error(__('The category does not exist'), array('code' => 404));
			return;
		}

		// Check if the user is allowed to delete the category.
		if (!$this->Category->isAuthorized($id, PermissionType::UPDATE)) {
			$this->Message->error(__('You are not authorized to delete this category'), array('code' => 403));
			return;
		}

		// delete
		if ($this->Category->delete($id)) {
			$this->Message->success(__('The category was successfully deleted'));
		} else {
			$this->Message->error(__('The category could not be deleted.'));
		}
	}

/**
 * Move a category in the tree
 *
 * @param string $id the uuid of the category to move
 * @param int $position the position among the siblings
 * @param string $parentId the uuid of new parent
 * @return void
 */
	public function move($id = null, $position = null, $parentId = null) {
		// check if the category is provided
		if (!isset($id)) {
			$this->Message->error(__('The category id is missing'));
			return;
		}

		// check if the category id is valid
		if (!Common::isUuid($id)) {
			$this->Message->error(__('The category id is invalid'));
			return;
		}

		// check if the category exists
		if (!$this->Category->exists($id)) {
			$this->Message->error(__('The category does not exist'), array('code' => 404));
			return;
		}

		// if a parent id is provided
		if (!is_null($parentId)) {
			// check if the parent category id is valid
			if (!Common::isUuid($parentId)) {
				$this->Message->error(__('The parent category id invalid'));
				return;
			}

			// check if the parent category exists
			if (!$this->Category->exists($parentId)) {
				$this->Message->error(__('The parent category does not exist'), array('code' => 404));
				return;
			}
		}

		// check if the position is ok
		if ($position < 0) {
			$this->Message->error(__('It is not possible to move the category at this position'));
			return;
		}

		$result = $this->Category->move($id, $position, $parentId);
		// deliver some results
		if ($result) {
			$this->Message->success(__('The category was successfully moved'));
		} else {
			$this->Message->error(__('The category could not be moved'));
		}
	}

/**
 * Set the type of a category
 *
 * @param string $id the uuid of the category
 * @param string $typeName , the name of the type
 * @return void
 */
	public function type($id = null, $typeName = null) {
		// check if the category is provided
		if (!isset($id)) {
			$this->Message->error(__('The category id is missing'));
			return;
		}

		// check if the id is valid
		if (!Common::isUuid($id)) {
			$this->Message->error(__('The category id is invalid'));
			return;
		}

		$category = $this->Category->findById($id);
		if (!$category) {
			$this->Message->error(__('The category does not exist'), array('code' => 404));
			return;
		}

		$type = $this->Category->CategoryType->findByName($typeName);
		if (!$type) {
			$this->Message->error(__('The type does not exist'), array('code' => 404));
			return;
		}

		// Check if the user is allowed to update the category.
		if (!$this->Category->isAuthorized($id, PermissionType::UPDATE)) {
			$this->Message->error(__('You are not authorized to change the type of this category'), array('code' => 403));
			return;
		}

		$category['Category']['category_type_id'] = $type['CategoryType']['id'];
		$category = $this->Category->save($category);

		if (!$category) {
			$this->Message->error(__('The type could not be changed'));
			return;
		}
		$this->Message->success(__('The type was successfully set'));
	}
}

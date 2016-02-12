<?php
/**
 * Categories Resources controller
 * This file will define how categories_resources are managed. only crud functions
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

class CategoriesResourcesController extends AppController {

/**
 * @var $uses array containing the class names of models this controller uses.
 */
	public $uses = array('CategoryResource');

/**
 * Get a categoryResource
 * Renders a json object of the resource
 *
 * @param string $id the uuid of the resource
 * @return void
 */
	public function view($id = null) {
		// check if the category id is provided
		if (!isset($id)) {
			$this->Message->error(__('The categoryResource id is missing'));
			return;
		}
		// check if the id is valid
		if (!Common::isUuid($id)) {
			$this->Message->error(__('The categoryResource id is invalid'));
			return;
		}
		// check if it exists
		$data = array(
			'CategoryResource.id' => $id
		);
		$options = $this->CategoryResource->getFindOptions('view', User::get('Role.name'), $data);
		$cr = $this->CategoryResource->find('all', $options);
		if (!count($cr)) {
			$this->Message->error(__('The categoryResource does not exist'), array('code' => 404));
			return;
		}
		$this->set('data', $cr[0]);
		$this->Message->success();
	}

/**
 * Delete a the relationship between a category and a resource
 *
 * @param string $id the uuid of the resource to delete
 * @return void
 */
	public function delete($id = null) {
		// check if the category id is provided
		if (!isset($id)) {
			$this->Message->error(__('The categoryResource id is missing'));
			return;
		}
		// check if the id is valid
		if (!Common::isUuid($id)) {
			$this->Message->error(__('The categoryResource id is invalid'));
			return;
		}
		$resource = $this->CategoryResource->findById($id);
		if (!$resource) {
			$this->Message->error(__('The categoryResource does not exist'), array('code' => 404));
			return;
		}

		if (!$this->CategoryResource->delete($id)) {
			$this->Message->error(__('Error while deleting'));
			return;
		}
		$this->Message->success(__('The categoryResource was successfully deleted'));
	}

/**
 * Add a resource to a category
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
		if (!isset($this->request->data['CategoryResource'])) {
			$this->Message->error(__('No data were provided'));
			return;
		}

		// set the data for validation and save
		$crpost = $this->request->data;
		$this->CategoryResource->set($crpost);

		$fields = $this->CategoryResource->getFindFields('add', User::get('Role.name'));

		// check if the data is valid
		if (!$this->CategoryResource->validates()) {
			$this->Message->error(__('Could not validate data'));
			return;
		}

		$cr = $this->CategoryResource->save($crpost, false, $fields['fields']);
		if ($cr === false) {
			$this->Message->error(__('The CategoryResource could not be saved'));
			return;
		}
		$fields = $this->CategoryResource->getFindFields('add', User::get('Role.name'));
		$this->set('data', $this->CategoryResource->findById($cr['CategoryResource']['id'], $fields['fields']));
		$this->Message->success(__('The categoryResource was successfully added'));
	}
}
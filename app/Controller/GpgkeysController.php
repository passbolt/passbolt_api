<?php
/**
 * Gpgkeys controller
 * This file will define how gpg keys are managed
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class GpgkeysController extends AppController {

/**
 * @var $component application wide components
 */
	public $components = array(
		'Filter'
	);

/**
 * Index entry point.
 *
 * @return void
 */
	public function index() {
		$filter = $this->Filter->fromRequest($this->request->query);
		$data = array();
		// if keywords provided build the model request with
		if (isset($filter['modified_after']) && !empty($filter['modified_after'])) {
			$data['modified_after'] = date('Y-m-d H:i:s', $filter['modified_after']);
		}
		$o = $this->Gpgkey->getFindOptions('index', User::get('Role.name'), $data);
		$returnVal = $this->Gpgkey->find('all', $o);
		if (empty($returnVal)) {
			$this->Message->notice(__('There is no gpg keys to display'));
			return;
		}

		$this->Message->success();
		$this->set('data', $returnVal);
	}

/**
 * View
 *
 * @param string $id UUID of the user
 * @return void
 */
	public function view($id = null) {
		// check if the id is provided
		if (!isset($id)) {
			$this->Message->error(__('The user id is missing'));
			return;
		}

		// check if the id is valid
		if (!Common::isUuid($id)) {
			$this->Message->error(__('The user id is invalid'));
			return;
		}

		$data = array('Gpgkey.user_id' => $id);
		$o = $this->Gpgkey->getFindOptions('view', User::get('Role.name'), $data);
		$gpgkey = $this->Gpgkey->find('first', $o);
		if (!$gpgkey) {
			$this->Message->error(__('The user id is invalid'), array('code' => 404));
			return;
		}
		$this->set('data', $gpgkey);
		$this->Message->success();
	}

/**
 * Gpgkey add entry point
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
		if (!isset($this->request->data['Gpgkey'])) {
			$this->Message->error(__('No data were provided'));
			return;
		}

		$userId = User::get('id');

		// Begin transaction.
		$this->Gpgkey->begin();

		// Check if a key already exists for the given user.
		$existingKey = $this->Gpgkey->find('first', array(
				'conditions' => array(
					'Gpgkey.deleted' => 0,
					'Gpgkey.user_id' => $userId,
				)
			));

		// If key already exists, soft delete them.
		if ($existingKey) {
			$this->Gpgkey->updateAll(
				array('Gpgkey.deleted' => 1),
				array(
					'Gpgkey.user_id' => $userId,
					'Gpgkey.deleted' => 0
				)
			);
		}

		// set the data for validation and save
		$gpgkeyData = $this->request->data;

		// Force the user id of the user. We are not concerned about what was given.
		$gpgkeyData = $this->Gpgkey->buildGpgkeyDataFromKey($gpgkeyData['Gpgkey']['key']);

		// If the information could not be extracted.
		if ($gpgkeyData === false) {
			$this->Gpgkey->rollback();
			$this->Message->error(__('The gpgkey provided could not be used'));
			return;
		}
		$gpgkeyData['Gpgkey']['user_id'] = $userId;

		// Set data.
		$this->Gpgkey->set($gpgkeyData);

		// Check if the data is valid.
		if (!$this->Gpgkey->validates()) {
			$this->Gpgkey->rollback();
			$this->Message->error(__('Could not validate gpgkey data'));
			return;
		}

		// Get fields to save.
		$fields = $this->Gpgkey->getFindFields('save', User::get('Role.name'));

		// Everything alright, we save.
		$gpgkey = $this->Gpgkey->save($gpgkeyData, false, $fields['fields']);

		// If there was a problem during a save, return error message.
		if ($gpgkey == false) {
			$this->Gpgkey->rollback();
			$this->Message->error(__('The gpgkey could not be saved'));
			return;
		}

		// We are good. Commit everything.
		$this->Gpgkey->commit();

		// Retrieve the data to return inthe response.
		$data = array('Gpgkey.user_id' => $gpgkeyData['Gpgkey']['user_id']);
		$options = $this->Gpgkey->getFindOptions('view', User::get('Role.name'), $data);
		$gpgkey = $this->Gpgkey->find('first', $options);

		// Send response.
		$this->Message->success(__("The gpgkey has been saved successfully"));
		$this->set('data', $gpgkey);
	}
}

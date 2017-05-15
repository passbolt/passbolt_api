<?php
/**
 * Gpgkeys controller
 * This file will define how gpg keys are managed
 *
 * @copyright (c) 2015-2016 Bolt Softwares Pvt Ltd
 *                2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class GpgkeysController extends AppController {

/**
 * @var array list of supported components
 */
	public $components = [
		'QueryString'
	];

/**
 * Get all gpg public keys
 * Renders a json object of the gpg keys.
 *
 * @throws MethodNotAllowedException if the request method is not GET
 * @throws BadRequestException if the modified-after filter is not a valid timestamp
 * @return void
 */
	public function index() {
		// Check request sanity
		if (!$this->request->is('get')) {
			throw new MethodNotAllowedException(__('Invalid request method, should be GET.'));
		}

		// Extract query parameters
		$allowedQueryItems = ['filter' => ['modified-after']];
		$params = $this->QueryString->get($allowedQueryItems);

		// Find an return keys if any (empty is fine)
		$findOptions = $this->Gpgkey->getFindOptions('GpgKey::index', User::get('Role.name'), $params);
		$returnVal = $this->Gpgkey->find('all', $findOptions);
		$this->set('data', $returnVal);
		$this->Message->success();
	}

/**
 * View the gpg key of a given user
 *
 * @param string $id UUID of a user
 * @throws MethodNotAllowedException if request method is not GET
 * @throws BadRequestException if the user id is missing
 * @throws BadRequestException if the user is not a valid uuid
 * @throws NotFoundException if the user does not exist
 * @throws NotFoundException if there is no key associated with that user
 * @return void
 */
	public function view($id = null) {
		// Check request sanity
		if (!$this->request->is('get')) {
			throw new MethodNotAllowedException(__('Invalid request method, should be GET.'));
		}
		if (!isset($id)) {
			throw new BadRequestException(__('The user id is missing.'));
		}
		if (!Common::isUuid($id)) {
			throw new BadRequestException(__('The user id is not valid.'));
		}
		if (!$this->Gpgkey->User->exists($id)) {
			throw new NotFoundException(__('The user does not exist.'));
		}

		// Try to find the key
		$options = $this->Gpgkey->getFindOptions('GpgKey::view', User::get('Role.name'), ['Gpgkey.user_id' => $id]);
		$gpgkey = $this->Gpgkey->find('first', $options);
		if (!$gpgkey) {
			throw new NotFoundException(__('The key does not exist.'));
		}
		$this->set('data', $gpgkey);
		$this->Message->success();
	}

/**
 * Gpgkey add entry point
 *
 * @throws MethodNotAllowedException is the request method is not POST
 * @throws BadRequestException if no key data is provided
 * @throws BadRequestException if Gpgkey.key is not a string
 * @throws BadRequestException if Gpgkey.key does not validate
 * @throws InternalErrorException if the key could not be saved
 * @return void
 */
	public function add() {
		// check request sanity
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException(__('Invalid request method, should be POST.'));
		}
		if (!isset($this->request->data['Gpgkey'])) {
			throw new BadRequestException(__('No key data provided.'));
		}
		if (!isset($this->request->data['Gpgkey']['key'])) {
			throw new BadRequestException(__('No key data provided.'));
		}
		if (!is_string($this->request->data['Gpgkey']['key'])) {
			throw new BadRequestException(__('No key data provided.'));
		}

		// Begin transaction.
		$userId = User::get('id');
		$this->Gpgkey->begin();

		// Check if a key already exists for the given user.
		$conditions = ['conditions' => ['Gpgkey.deleted' => 0, 'Gpgkey.user_id' => $userId]];
		$existingKey = $this->Gpgkey->find('first', $conditions);

		// If key already exists, soft delete them.
		if (isset($existingKey)) {
			$this->Gpgkey->updateAll(['Gpgkey.deleted' => 1], ['Gpgkey.user_id' => $userId, 'Gpgkey.deleted' => 0]);
		}

		// Set the data for validation and save
		// We are not concerned about what was given apart from key data.
		$gpgkeyData = $this->Gpgkey->buildGpgkeyDataFromKey($this->request->data['Gpgkey']['key']);

		// If the information could not be extracted.
		if ($gpgkeyData === false) {
			$this->Gpgkey->rollback();
			throw new BadRequestException(__('The gpgkey provided could not be used'));
		}

		// Set user id to current user
		$gpgkeyData['Gpgkey']['user_id'] = $userId;

		// Sanitize gpg key data.
		// UID should not be sanitized at this stage, or will not pass the RFC validation.
		$gpgkeyDataSanitized = $this->HtmlPurifier->cleanRecursive($gpgkeyData, 'nohtml');
		$gpgkeyDataSanitized['Gpgkey']['uid'] = $gpgkeyData['Gpgkey']['uid'];

		// Set and check if data validates
		$this->Gpgkey->set($gpgkeyDataSanitized);
		if (!$this->Gpgkey->validates()) {
			$this->Gpgkey->rollback();
			throw new BadRequestException(__('Could not validate gpgkey data'));
		}

		// Sanitize the UID for our own future use
		$gpgkeyDataSanitized['Gpgkey']['uid'] = htmlentities($gpgkeyDataSanitized['Gpgkey']['uid']);

		// Get fields to save and try to save.
		$fields = $this->Gpgkey->getFindFields('GpgKey::save', User::get('Role.name'));
		$gpgkey = $this->Gpgkey->save($gpgkeyDataSanitized, false, $fields['fields']);
		if ($gpgkey == false) {
			$this->Gpgkey->rollback();
			throw new InternalErrorException('The gpgkey could not be saved');
		}

		// We are good. Commit everything.
		$this->Gpgkey->commit();

		// Retrieve the data to return with the response.
		$data = ['Gpgkey.user_id' => $gpgkeyData['Gpgkey']['user_id']];
		$options = $this->Gpgkey->getFindOptions('GpgKey::view', User::get('Role.name'), $data);
		$gpgkey = $this->Gpgkey->find('first', $options);

		// Send response.
		$this->Message->success(__("The gpgkey has been saved successfully"));
		$this->set('data', $gpgkey);
	}
}

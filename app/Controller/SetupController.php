<?php
/**
 * Setup Controller
 *
 * @copyright (c) 2015-2016 Bolt Softwares Pvt Ltd
 * 				  2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

App::uses('UserAgent', 'Model');

class SetupController extends AppController {

/**
 * @var array Models to be used in this controller
 */
	public $uses = [
		'User',
		'AuthenticationToken',
	];

/**
 * Called before the controller action. Used to manage access right
 *
 * @return void
 * @link http://book.cakephp.org/2.0/en/controllers.html#request-life-cycle-callbacks
 */
	public function beforeFilter() {
		$this->Auth->allow('install');
		$this->Auth->allow('recover');
		$this->Auth->allow('completeRecovery');
		parent::beforeFilter();
	}

/**
 * Install the plugin for the first time.
 *
 * @param string $userId user uuid
 * @param string $token AuthenticationToken uuid
 * @throws BadRequestException
 * @throws NotFoundException
 * @return void
 */
	public function install($userId = null, $token = null) {
		$this->layout = 'default';

		// Check request sanity
		if (!isset($userId)) {
			throw new BadRequestException(__('The user id is missing.'));
		}
		if (!Common::isUuid($userId)) {
			throw new BadRequestException(__('The user id is not valid.'));
		}
		if (!isset($token)) {
			throw new BadRequestException(__('The authentication token is missing.'));
		}

		// Check that the token exists
		$authToken = $this->User->AuthenticationToken->findFirstByToken($token);
		if (empty($authToken)) {
			throw new BadRequestException(__('The authentication token is not valid.'));
		}

		// Check that token is not expired
		$isNotExpiredToken = $this->User->AuthenticationToken->isNotExpired($token);
		if (!$isNotExpiredToken) {
			throw new BadRequestException(__('The authentication token is expired.'));
		}

		// Check if token is valid.
		$isValidToken = $this->AuthenticationToken->isValid($token, $userId);
		if (!$isValidToken) {
			throw new NotFoundException(__('The authentication token is not valid.'));
		}

		// Retrieve the user.
		$data = ['User.id' => $userId];
		$o = $this->User->getFindOptions('Setup::userInfo', Role::GUEST, $data);
		$user = $this->User->find('first', $o);
		if (empty($user)) {
			throw new NotFoundException(__('The user does not exist.'));
		}
		$this->set('user', $user);

		// Parse the user agent
		$userAgent = UserAgent::parse();
		$this->set('userAgent', $userAgent);
	}

/**
 * Account recovery start.
 *
 * @param string $userId user uuid
 * @param string $token AuthenticationToken uuid
 * @throws BadRequestException
 * @throws NotFoundException
 * @return void
 */
	public function recover($userId = null, $token = null) {
		$this->layout = 'default';

		// Check request sanity
		if (!isset($userId)) {
			throw new BadRequestException(__('The user id is missing.'));
		}
		if (!Common::isUuid($userId)) {
			throw new BadRequestException(__('The user id is not valid.'));
		}
		if (!isset($token)) {
			throw new BadRequestException(__('The authentication token is missing.'));
		}

		// Check if token is valid.
		$token = $this->AuthenticationToken->isValid($token, $userId);
		if (empty($token)) {
			throw new BadRequestException(__('The authentication token is not valid.'));
		}

		// Retrieve the user.
		$data = ['User.id' => $userId];
		$o = $this->User->getFindOptions('Recovery::userInfo', Role::GUEST, $data);
		$user = $this->User->find('first', $o);

		if (empty($user)) {
			throw new NotFoundException(__('The user does not exist.'));
		}

		$this->set('user', $user);

		// Parse the user agent
		$userAgent = UserAgent::parse();
		$this->set('userAgent', $userAgent);
	}

/**
 * Complete account recovery.
 * Verify all information and deactivate the token.
 *
 * @param string $id user uuid
 * @return void
 */
	public function completeRecovery($id = null) {
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: PUT, OPTIONS');

		// Check the request sanity
		if (!$this->request->is('put')) {
			throw new MethodNotAllowedException(__('Invalid request method, should be PUT.'));
		}
		if (!isset($id)) {
			throw new BadRequestException(__('The user id is missing.'));
		}
		if (!Common::isUuid($id)) {
			throw new BadRequestException(__('The user id is not valid.'));
		}

		// Instantiate user model.
		$this->User = Common::getModel('User');

		// Get the resource
		$user = $this->User->findById($id);
		if (!$user) {
			throw new NotFoundException(__('The user does not exist.'));
		}

		// Store request data in data.
		$data = $this->request->data;
		if(!isset($data) || empty($data)) {
			// if data is not accessible via form data, check for json input
			$data = $this->request->input('json_decode');
			// convert object to associative array to match formdata format
			$data = json_decode(json_encode($data), true);
		}

		// Check that token is valid.
		if (!isset($data['AuthenticationToken']) || !isset($data['AuthenticationToken']['token'])) {
			throw new BadRequestException(__('No authentication token data provided.'));
		}
		$validToken = $this->User->AuthenticationToken->isValid($data['AuthenticationToken']['token'], $id);
		if (!$validToken) {
			throw new BadRequestException(__('The authentication token is not valid.'));
		}

		// Check that key provided belongs to user.
		// If User data are provided, we update.
		if (isset($data['Gpgkey'])) {
			$gpgkeyData = $data['Gpgkey'];

			// Extract data from the key
			$gpgkeyData = $this->User->Gpgkey->buildGpgkeyDataFromKey($gpgkeyData['key']);
			if ($gpgkeyData == false) {
				throw new BadRequestException(__('The key provided could not be used.'));
			}

			$userKey = $this->User->Gpgkey->find('first', [
					'conditions' => [
						'user_id' => $id,
						'fingerprint' => strtoupper($gpgkeyData['Gpgkey']['fingerprint'])
					],
				]);
			if (empty($userKey)) {
				throw new BadRequestException(__('The key provided does not belong to given user.'));
			}
		}

		// Everything is alright, we can complete the setup, and return the user.
		// Deactivate Token.
		try {
			$this->User->AuthenticationToken->setInactive($data['AuthenticationToken']['token']);
		} catch (Exception $e) {
			throw new InternalErrorException(__('Could not update token.'));
		}

		// Return information in case of success.
		$data = ['User.id' => $id];
		$options = $this->User->getFindOptions('User::view', User::get('Role.name'), $data);
		$user = $this->User->find('first', $options);

		$this->set('data', $user);
		$this->Message->success(__("The recovery has been completed successfuly."));
	}
}

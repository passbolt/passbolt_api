<?php
/**
 * Setup Controller
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
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
		$this->Auth->allow('ping');
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

		// check if the id is provided
		if (is_null($userId)) {
			throw new BadRequestException(__('User id not provided'));
		}

		// check if the id is valid
		if (!Common::isUuid($userId)) {
			throw new BadRequestException(__('User id is incorrect'));
		}

		// Check if token is provided.
		if (is_null($token)) {
			throw new BadRequestException(__('Token not provided'));
		}

		// Check if token is valid.
		$token = $this->AuthenticationToken->isValid($token, $userId);
		if (empty($token)) {
			throw new NotFoundException(__('Token not found'));
		}

		// Retrieve the user.
		$data = ['User.id' => $userId];
		$o = $this->User->getFindOptions('Setup::userInfo', Role::GUEST, $data);
		$user = $this->User->find('first', $o);

		if (empty($user)) {
			throw new NotFoundException(__('User not found'));
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

		// check if the id is provided
		if (is_null($userId)) {
			throw new BadRequestException(__('User id not provided'));
		}

		// check if the id is valid
		if (!Common::isUuid($userId)) {
			throw new BadRequestException(__('User id is incorrect'));
		}

		// Check if token is provided.
		if (is_null($token)) {
			throw new BadRequestException(__('Token not provided'));
		}

		// Check if token is valid.
		$token = $this->AuthenticationToken->isValid($token, $userId);
		if (empty($token)) {
			throw new NotFoundException(__('Token not found'));
		}
		
		// Retrieve the user.
		$data = ['User.id' => $userId];
		$o = $this->User->getFindOptions('Recovery::userInfo', Role::GUEST, $data);
		$user = $this->User->find('first', $o);

		if (empty($user)) {
			throw new NotFoundException(__('User not found'));
		}

		$this->set('user', $user);


		// Parse the user agent
		$userAgent = UserAgent::parse();
		$this->set('userAgent', $userAgent);
	}

/**
 * Complete account recovery.
 *
 * Verify all information and deactivate the token.
 *
 * TODO: logging of the operation.
 *
 * @param string $userId user uuid
 * @param string $token AuthenticationToken uuid
 * @throws BadRequestException
 * @throws NotFoundException
 * @return void
 */
	public function completeRecovery($id = null) {
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: PUT, OPTIONS');

		// Check the HTTP request method.
		if (!$this->request->is('put')) {
			return $this->Message->error(__('Invalid request method, should be PUT'));
		}

		// Check if the id is provided
		if (!isset($id)) {
			return $this->Message->error(__('The user id is missing'));
		}

		// Check if the id is valid
		if (!Common::isUuid($id)) {
			return $this->Message->error(__('The user id is invalid'));
		}

		// Instantiate user model.
		$this->User = Common::getModel('User');

		// Get the resource
		$user = $this->User->findById($id);
		if (!$user) {
			return $this->Message->error(__('The user does not exist'), ['code' => 404]);
		}

		// Store request data in data.
		$data = $this->request->data;

		if (!isset($data['AuthenticationToken'])) {
			return $this->Message->error(__('No data were provided'));
		}

		// Check if token is provided.
		if (!isset($data['AuthenticationToken']['token'])) {
			return $this->Message->error(__('Token not provided'));
		}

		// Check that token is valid.
		$validToken = $this->User->AuthenticationToken->isValid($data['AuthenticationToken']['token'], $id);
		if (!$validToken) {
			return $this->Message->error(__('Invalid token'));
		}

		// Check that key provided belongs to user.
		// If User data are provided, we update.
		if (isset($data['Gpgkey'])) {
			$gpgkeyData = $data['Gpgkey'];

			// Extract data from the key
			$gpgkeyData = $this->User->Gpgkey->buildGpgkeyDataFromKey($gpgkeyData['key']);
			if ($gpgkeyData == false) {
				return $this->Message->error(__('The key provided couldn\'t be used'));
			}

			$userKey = $this->User->Gpgkey->find('first', [
					'conditions' => [
						'user_id' => $id,
						'fingerprint' => strtoupper($gpgkeyData['Gpgkey']['fingerprint'])
					],
				]);
			if ( empty($userKey) ) {
				return $this->Message->error(__('The key provided doesn\'t belong to given user'));
			}
		}

		// Everything is alright, we can complete the setup, and return the user.
		// Deactivate Token.
		$this->User->AuthenticationToken->id = $validToken['AuthenticationToken']['id'];
		$result = $this->User->AuthenticationToken->saveField('active', false, ['atomic' => false]);
		if (!$result) {
			return $this->Message->error(__('Could not update token'));
		}


		// Return information in case of success.
		$data = ['User.id' => $id];
		$options = $this->User->getFindOptions('User::view', User::get('Role.name'), $data);
		$user = $this->User->find('first', $options);

		$this->Message->success(__("The recovery has been completed successfuly"));
		$this->set('data', $user);
	}

/**
 * Ping passbolt.
 *
 * @return void
 */
	public function ping() {
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: PUT, OPTIONS');
		$this->Message->success(__("Affirmative, Dave. I read you."));
	}
}

<?php
/**
 * Setup Controller
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
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
		$this->layout = 'html5';

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

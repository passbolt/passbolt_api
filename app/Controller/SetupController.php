<?php
/**
 * Setup Controller
 *
 * @copyright   Copyright 2015, Passbolt.com
 * @license     http://www.passbolt.com/license
 * @package     app.Controller.SetupController
 * @since       version 2.12.9
 */
class SetupController extends AppController {
	public $helpers = array();
	public $components = array();

	public $uses = array(
		'User',
		'AuthenticationToken',
	);

	function beforeFilter(){
		$this->Auth->allow('install');
		$this->Auth->allow('ping');
		parent::beforeFilter();
	}

/**
 * Install the plugin for the first time.
 *
 * @param string $token
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
		$token = $this->AuthenticationToken->checkTokenIsValidForUser($token, $userId);
		if (empty($token)) {
			throw new NotFoundException(__('Token not found'));
		}

		// Retrieve the user.
		$data = array('User.id' => $userId);
		$o = $this->User->getFindOptions('Setup::userInfo', Role::GUEST, $data);
		$user = $this->User->find('first', $o);

		if (empty($user)) {
			throw new NotFoundException(__('User not found'));
		}

		$this->set('user', $user);
	}

	/**
	 * Ping passbolt.
	 */
	public function ping() {
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: PUT, OPTIONS');
		$this->Message->success(__("Affirmative, Dave. I read you."));
	}
}

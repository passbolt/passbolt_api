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
		parent::beforeFilter();
		$this->Auth->allow('install');
	}

/**
 * Install the plugin for the first time.
 *
 * @param string $token
 */
	public function install($userId = null, $token = null, $step = null) {
		$this->layout = 'html5';

		// check if the id is provided
		if (!$userId) {
			throw new BadRequestException(__('User id not provided'));
		}

		// check if the id is valid
		if (!Common::isUuid($userId)) {
			throw new BadRequestException(__('User id is incorrect'));
		}

		// get the user
		$user = $this->User->findById($userId);
		if (!$user) {
			throw new NotFoundException(__('User not found'));
		}

		// Check if token is provided.
		if (!$token) {
			throw new BadRequestException(__('Token not provided'));
		}

		// Check if token is valid.
		$token = $this->AuthenticationToken->checkTokenIsValid($token, $userId);
		if (!$token) {
			throw new NotFoundException(__('Token not found'));
		}
	}
}

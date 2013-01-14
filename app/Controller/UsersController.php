<?php
/**
 * Users Controller
 * 
 * @copyright   Copyright 2012, Passbolt.com
 * @license     http://www.passbolt.com/license
 * @package     app.Controller.UsersController
 * @since       version 2.12.9
 */
class UsersController extends AppController {

	public $helpers = array('PassboltAuth');

/**
 * Login
 * @access public
 */
	public function login() {
		// check if the user Authentication worked
		// someone can not remain anonymous forever
		if (!$this->Auth->login() || User::isAnonymous()) {
			$this->layout = 'login';
			$this->view = '/Users/login';
			if ($this->request->is('post')) {
				$this->request->data['User']['password'] = null;
				$this->Message->error(__('Invalid username or password, try again'));
			}
			return;
		}
		// avoid looping if the requested URL is logout
		if ($this->Auth->redirect() == '/logout' || $this->Auth->redirect() == '/login') {
			$this->redirect('/');
		} else {
			$this->redirect($this->Auth->redirect());
		}
	}

/**
 * Logout
 * @access public
 */
	public function logout() {
		$this->redirect($this->Auth->logout());
	}

/**
 * Index
 * @access public
 */
	public function index() {
		$o = $this->User->getFindOptions('userIndex', User::get('Role.name'));
		$data = $this->User->find('all', $o);
		if (!empty($data)) {
			$this->Message->success();
			$this->set('data', $data);
		} else {
			$this->Message->notice(__('There is no user to display'));
		}
	}

/**
 * View
 * @param $id UUID of the user
 * @access public
 */
	public function view($id = null) {
		// check if the id is provided
		if (!isset($id)) {
			$this->Message->error(__('The user id is missing'));
			return;
		}
		// check if the id is valid
		if (!Common::isUuid($id)) {
			$this->Message->error(__('The user id invalid'));
			return;
		}
		// not sql needed if a user is asking for his own data
		if (User::get('id') == $id) {
			$resource = User::get();
		} else {
			$o = $this->User->getFindFields('userView', User::get('Role.name'));
			$resource = $this->User->findById($id, $o['fields']);
			if (!$resource) {
				$this->Message->error(__('The user does not exist'));
				return;
			}
		}
		$this->set('data', $resource);
		$this->Message->success();
	}

}

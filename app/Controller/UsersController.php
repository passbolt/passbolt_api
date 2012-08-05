<?php
/**
 * Users Controller
 * 
 * @copyright    Copyright 2012, Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Controller.UsersController
 * @since        version 2.12.9
 */
class UsersController extends AppController {

/**
 * Login
 * @access public
 */
	public function login() {
		if ($this->Auth->login() && !User::isAnonymous()) {
			// stupid bug in cakephp auth component
		  $r = ($this->Auth->redirect() == '/logout') ? $this->Auth->redirect() : '/';
			$this->redirect($r);
		} else {
			$this->layout = 'html5';
			$this->view = '/Users/login';
			if ($this->request->is('post')) {
				$this->request->data['User']['password'] = null;
				$this->Message->error(__('Invalid username or password, try again'));
			}
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
		$data = $this->paginate();
		if (!empty($data)) {
			$this->Message->success();
			$this->set('data', $data);
		} else {
			$this->Message->notice(__('There is no user to display'));
		}
	}
}

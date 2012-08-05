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
		if ($this->Auth->login()) {
			 $this->redirect($this->Auth->redirect());
		} else {
			if ($this->request->is('post')) {
				$this->request->data['User']['password'] = null;
				$this->Message->error(__('Invalid username or password, try again'));
			}
		}
	}

/**
 * Admin Logout
 * @access public
 */
	public function logout() {
		$this->redirect($this->Auth->logout());
	}

}

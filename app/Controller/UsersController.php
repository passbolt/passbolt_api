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
	    $keywords = isset($this->request->query['keywords']) ? $this->request->query['keywords'] : '';
    
        $data = array();
        // if keywords provided build the model request with
        if (!empty($keywords)) {
          $data['keywords'] = $keywords;
        }
        $o = $this->User->getFindOptions('index', User::get('Role.name'), $data);
        $returnVal = $this->User->find('all', $o);
        if (empty($returnVal)) {
            $this->Message->notice(__('There is no user to display'));
            return;
        }
        $this->set('data', $returnVal);
        $this->Message->success();
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

    public function add(){
      // First of all, check if the user is an administrator
      $role = $this->Auth->user('Role.name');
      if($role != Role::ADMIN){
        $this->Message->error(__('You are not allowed to access this entry point'));
        return;
      }

      // check the HTTP request method
      if (!$this->request->is('post')) {
        $this->Message->error(__('Invalid request method, should be POST'));
        return;
      }
      // check if data was provided
      if (!isset($this->request->data['User'])) {
        $this->Message->error(__('No data were provided'));
        return;
      }

      // set the data for validation and save
      $userData = $this->request->data;
      $this->User->set($userData);

      $fields = $this->User->getFindFields('userSave', User::get('Role.name'));

      // check if the data is valid
      if (!$this->User->validates()) {
        $this->Message->error(__('Could not validate user data'));
        return;
      }

      //$this->User->begin();
      $user = $this->User->save($userData, false, $fields['fields']);

      if ($user == false) {
        $this->User->rollback();
        $this->Message->error(__('The user could not be saved'));
        return;
      }
      $this->User->commit();

      $data = array('User.id' => $this->User->id);
      $options = $this->User->getFindOptions('userView', User::get('Role.name'), $data);
      $users = $this->User->find('all', $options);

      $this->Message->success(__("The user has been saved successfully"));
      $this->set('data', $users[0]);
      return;
    }

}

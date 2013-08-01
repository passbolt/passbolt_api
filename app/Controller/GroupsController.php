<?php
/**
 * Groups Controller
 * 
 * @copyright   Copyright 2012, Passbolt.com
 * @license     http://www.passbolt.com/license
 * @package     app.Controller.GroupsController
 * @since       version 2.13.6
 */
class GroupsController extends AppController {

	public $helpers = array('PassboltAuth');

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

      $o = $this->Group->getFindOptions('index', User::get('Role.name'), $data);
      $returnVal = $this->Group->find('all', $o);
      if (!empty($data)) {
          $this->Message->success();
          $this->set('data', $returnVal);
      } else {
          $this->Message->notice(__('There is no group to display'));
      }
	}

/**
 * View
 * @param $id UUID of the user
 * @access public
 */
	// public function view($id = null) {
		// // check if the id is provided
		// if (!isset($id)) {
			// $this->Message->error(__('The user id is missing'));
			// return;
		// }
		// // check if the id is valid
		// if (!Common::isUuid($id)) {
			// $this->Message->error(__('The user id invalid'));
			// return;
		// }
		// // not sql needed if a user is asking for his own data
		// if (User::get('id') == $id) {
			// $resource = User::get();
		// } else {
			// $o = $this->User->getFindFields('User::view', User::get('Role.name'));
			// $resource = $this->User->findById($id, $o['fields']);
			// if (!$resource) {
				// $this->Message->error(__('The user does not exist'));
				// return;
			// }
		// }
		// $this->set('data', $resource);
		// $this->Message->success();
	// }

}

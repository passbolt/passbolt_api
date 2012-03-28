<?php
App::import('model','PasswordHistory');
App::import('Component', 'Auth');


class Password extends AppModel {
	var $name = 'Password';

	var $validate = array(
		'title' => array(
			'rule' => array('minLength', '3'),
			'message' => 'Please enter a title that contains at least 3 characters'
		),
		'username' => array(
			'rule' => array('minLength', '3'),
			'message' => 'Please enter a title that contains at least 3 characters'
		),
		'password' => array(
			'rule' => array('minLength', '4'),
			'message' => 'Mimimum 4 characters long'
		),
		'passwordRepeat' => array(
			'rule' => array('minLength', '4'),
			'message' => 'Mimimum 4 characters long'
		)
	);
	
	/**
	 * Hook afterSave
	 * Store the password in the history when it is modified so we can track the changes later if needed
	 */
	function afterSave($created){
		if(!empty($this->data['Password']['password'])){
			$passwordH = new PasswordHistory();
			App::import('Component','Session');
  			$Session = new SessionComponent();   
  			$user = $Session->read('Auth.User');
  			
			$data =  array('PasswordHistory'=>array(
				'value'=>$this->data['Password']['password'],
				'user_id'=>$user['id'],
				'password_id'=>$this->id,
				'created'=>null  // to save the current time
			));
			$passwordH->create();
			$passwordH->save($data);
		}
	}
}

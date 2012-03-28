<?php
class Event extends AppModel {
	var $name = 'Event';
    var $hasOne = array(
		'Category' => array(
			'className' => 'Category',
    		'foreignKey'=>false,
            'conditions'=>array('Category.id = Event.aco_ref_id')
		),
		'Password'=>array(
			'className' => 'Password',
			'foreignKey'=>false,
            'conditions'=>array('Password.id = Event.aco_ref_id')
		),
		'User'=>array(
			'className' => 'User',
			'foreignKey'=>false,
            'conditions'=>array('User.id = Event.created_by')
		)
	);  
	
	function beforeSave() {
		App::import('Component', 'RequestHandler');
		$rq = new RequestHandlerComponent(); 
		$this->data['Event']['user_ip'] = $rq->getClientIP();
		$this->data['Event']['user_browser'] = $_SERVER['HTTP_USER_AGENT'];
		return true;
	}
}

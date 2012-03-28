<?php 
define('WATCHER_CAT_READ_PASSWORDS', 1);
define('WATCHER_GET_PASSWORD', 2);
define('WATCHER_CAT_RENAME', 3);
define('WATCHER_CAT_DELETE', 4);
define('WATCHER_CAT_DELETE_FOREVER', 5);
define('WATCHER_CAT_MOVE', 6);
define('WATCHER_CAT_COPY', 7);
define('WATCHER_CAT_CREATE', 8);
define('WATCHER_CAT_CREATE_DATABASE', 9);
define('WATCHER_CAT_OPEN_DATABASE', 10);
define('WATCHER_PASSWORD_CREATE', 20);
define('WATCHER_PASSWORD_DELETE', 21);
define('WATCHER_PASSWORD_DELETE_FOREVER', 22);
define('WATCHER_PASSWORD_RESTORE', 23);
define('WATCHER_PASSWORD_MODIFY', 24);
define('WATCHER_PERMISSION_ADD', 40);
define('WATCHER_PERMISSION_DELETE', 41);
define('WATCHER_PERMISSION_MODIFY', 41);
		
class WatcherComponent extends Object {
 
	var $cache = Array(); // cache will contain sections we want to keep to access it later
	
	var $components = array("Session", "Auth");
	
	
	function initialize(&$controller, $settings = array()) {
		$this->controller =& $controller;
	}
	
	function startup(&$controller) {
		
	}
	
	/**
	 * logAction
	 * logs an action in the events table
	 * @param $action
	 * @param $aco_id
	 * @param $aco_ref_id
	 * @param $details
	 */
	function logAction($action_id, $aco_id=null, $aco_ref_id=null, $details=null, $category_rel_id=null){
		$user = $this->controller->Auth->user();
		$user_id = $user["User"]["id"];
		
		$eventModel = ClassRegistry::init('Event');
		if($aco_id == ACO_PASSWORD && $category_rel_id == null){
			$passwordModel = ClassRegistry::init('Password');
			$password = $passwordModel->findById($aco_ref_id);
			$category_rel_id = $password['Password']['category_id'];
		}
		elseif($aco_id == ACO_CATEGORY){
			$category_rel_id = $aco_ref_id;
		}
		$event = array("Event"=>array(
				'action_id'=>$action_id,
				'category_rel_id'=>$category_rel_id,
				'aco_id'=>$aco_id,
				'aco_ref_id'=>$aco_ref_id,
				'details'=>($details == null ? null : json_encode($details)),
				'created_by'=>$user_id
			)
		);
		if($eventModel->save($event)){
			return true;
		}
		else{
			return false;
		}
	}
	
}

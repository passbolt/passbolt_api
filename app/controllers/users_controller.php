<?php
App::import('Model', 'Group');
App::import('Model', 'Category');
App::import('Model', 'Permission');

class UsersController extends AppController {

    var $name = 'Users';   
    var $helpers = array("Tree"); 
 
    function beforeFilter(){
    	if ($this->action != 'logout' && $this->action != 'ajaxDeactivate'){ // on these actions, we don't need to check anything
    		parent::beforeFilter();
    	}
    	$this->Auth->autoRedirect = false; 
    }
    
    /**
     *  The AuthComponent provides the needed functionality
     *  for login, so you can leave this function blank.
     */
    function login() {
    	if (!empty($this->data) && $this->Auth->user()) {
            $this->User->id = $this->Auth->user('id');
            $this->User->saveField('last_login_date', date('Y-m-d H:i:s'));
            $this->redirect($this->Auth->redirect());
        }
        elseif($this->Auth->user()){ // if the user is logged in and tries to access the login page, we redirect him to the home page
        	$this->redirect($this->Auth->redirect());
        }
    	$this->layout = 'plain'; 
    }

    function logout() {
    	$this->Session->destroy(); 
        $this->redirect($this->Auth->logout());
    }
    
    function ajaxDeactivate(){
    	$res = array('status'=>0, 'data'=>array('error_code'=>'user_deactivated'));
	   	$this->set('json', $res);
		$this->render('/javascript/json');
    }
    
    function setGroup(){
    	$user_id =  $this->params['form']['user_id']; //17
    	$groups_id =  $this->params['form']['groups_id']; //51
    	
    	//045 9
    	//$user_id = 9;
    	//$group_id = 45;
    	//$belongs = 0;
    	
    	//$user_id = 9;
    	if($this->Session->read('Auth.User.admin')){
	    	$user = $this->User->findById($user_id);
			unset($user["Group"]);
			
	    	if(!empty($groups_id)){
				$user["Group"]["Group"] = $groups_id;
	    	}
	    	else{
	    		$user["Group"]["Group"] = array();
	    	}
	    	
	    	if($this->User->save($user)){
	    		$res = 1;
	    	}
	    	else{
	    		$res = 0;
	    	}
    		$result = array("status"=>$res);
    	}
    	else{
    		$result = array('status'=>0, 'data'=>array('error_code'=>'permission_not_valid'));
    	}

		$this->set('json', $result);
		$this->render('/javascript/json');
    }
    
	function insertDummy(){
		$user = array(
			'User'=>array(
				'username'=>'vidhat2',
				'password'=>'test'
				),
			'Group'=>array(
					'Group'=>array('16')
					)
			
		);
		$this->User->save($user);
		
		$user = array(
			'User'=>array(
				'username'=>'Alka',
				'password'=>'test4'
				),
			'Group'=>array(
					'Group'=>array('15')
					)
			
		);
		$this->User->create();
		$this->User->save($user);
	}
	
	function see(){
		pr($this->User->findById(9));
	}
	
	function add(){
		if (!empty($this->data)) {
			if($this->data["User"]['admin']){
				$this->data['Group']['Group'] = array();
			}
			else{
				// get the groups and format it in the right format
				$groups = explode(",", $this->data["Group"]['group_id'], -1);
				$this->data['Group']['Group'] = $groups;
				unset($this->data["Group"]['group_id']);
			}
			
			// retrieve the current user (the creator)
			$curr_user = $this->Auth->user();
			$curr_user_id = $curr_user["User"]["id"];
			$this->data["User"]["created_by"] = $curr_user_id;
			
			// now, save the user
			if ($this->User->save($this->data)) {
				$categoryModel = new Category();
				$this->data["User"]["id"] = $this->User->id;
				unset($this->data["User"]["password"]);
				unset($this->data["User"]["passwordRepeat"]);
				if($categoryModel->save(array('Category'=>array('name'=>'::'. $this->User->id .'::', 'parent_id'=>null, 'shared'=>0)))){		// save the category for the personal passwords of the user
					$data['Permission'] = array(
						'aco_id' =>  '1',
						'aro_id' =>  '2',
						'aco_ref_id' =>  $categoryModel->id,
						'aro_ref_id' =>  $this->User->id,
						'_read' =>  '1',
						'_write' =>  '1',
						'_manage' => '0');
					$permissionModel = new Permission();
					$permissionModel->save($data);			// save the corresponding permission (read and write. nobody can share his personal passwords)
					
					$this->set('json', array('status'=>'1', 'data'=>$this->data));
					$this->render('/javascript/json');
				}
				else{
					$this->set('json', array('status'=>'0', 'data'=>array('error_code'=>'database_problem')));
					$this->render('/javascript/json');
				}
			}
			else{
				$this->set('json', array('status'=>'0', 'data'=>array('error_code'=>'database_problem')));
				$this->render('/javascript/json');
			}
		}
		else{
			$groupModel = new Group();
			$nodes = $groupModel->getTree();
			foreach($nodes as $key=>$node){
				$nodes[$key]['Group']['rel'] = 'group';
			}
	    	$this->set('groups', $nodes);
		}
	}
	
	function getInfo($id=null){
		$user = $this->User->findById($id);
		$user["User"]["created_by"] = $this->User->findById($user["User"]["created_by"]);
		$res["created_by"] = $user["User"]["created_by"]["User"]["name"];
		$res["nbgroups"] = sizeof($user["Group"]);
		$res["created"] = $user["User"]["created"];
		$this->set('json', array('status'=>'1', 'data'=>$res));
		$this->render('/javascript/json');
	}
	
	function edit($id=null){
		if($id != null){
			$this->User->id = $id;
			$this->data = $this->User->read(array('id', 'name', 'email', 'username', 'address', 'admin'));
			$ug = $this->data['Group'];
			foreach($ug as $k=>$g){
				$ug[$k] = $g['id'];
			}
			$user_groups = implode(",", $ug);
			if($user_groups != "") $this->set('user_groups', $user_groups.",");
		}
		$groupModel = new Group();
		$nodes = $groupModel->getTree();
		foreach($nodes as $key=>$node){
			$nodes[$key]['Group']['rel'] = 'group';
		}
    	$this->set('groups', $nodes);
	}
	
	function account(){
		if (!empty($this->data)) {
			$user = $this->Auth->user();
			$id = $user["User"]["id"];
			$this->User->id = $id;
			$this->data['User']['id'] = $id; 
			// get the groups and format it in the right format
			
			// now, save the user
			if($this->data["User"]['new_password'] != ""){
				$this->data["User"]["password"] = $this->Auth->password($this->data['User']['new_password']);
			}
			if ($this->User->save($this->data)) {
				$this->set('json', array('status'=>'1', 'data'=>$this->data));
				$this->render('/javascript/json');
			}
		}
		else{
			$user = $this->Auth->user();
			$this->data = $this->User->findById($user["User"]["id"]);
		}
	}
	
	function accountChangePicture(){
		$user = $this->Auth->user();
		if (!empty($this->data) && !empty($this->data["User"]["avatar"])) {
			$res = 0;
			$this->data["Image"] = $this->data["User"];
			$image = $this->Image->upload_image_and_thumbnail($this->data, 'avatar', 150, 55, 'users', true);
			$user = $this->Auth->User();
			$user_id = $user['User']['id'];
			$user = $this->User->findById($user_id);
			if($this->User->save(array("User"=>array("id"=>$user_id, "avatar"=>$image)))){
				$res = 1;
			}
			if(isset($user["User"]['avatar']) && !empty($user["User"]['avatar'])){
				$this->Image->delete_image($user["User"]['avatar'], 'users');
			}
			$user["User"]["avatar"] = $image;
			$this->data = $user;
			
			$this->layout="ajax";
			$this->set('json', array('status'=>$res, 'data'=>$this->data));
			$this->render('/javascript/json');
		}
		else{
			$this->data = $this->User->findById($user['User']['id']);
		}
	}
	
	/**
	 * 
	 * Save a user that has been edited (the user already existed)
	 * @param $id : the user id
	 */
	function save($id=null){
		if (!empty($this->data)) {
			$this->User->id = $id;
			$this->data['User']['id'] = $id; 
			//pr($this->data);
			
			if($this->data["User"]['admin']){
				$this->data['Group']['Group'] = array();
			}
			else{
				// get the groups and format it in the right format
				$groups = explode(",", $this->data["Group"]['group_id'], -1);
				$this->data['Group']['Group'] = $groups;
				unset($this->data["Group"]['group_id']);
			}
			
			
			// now, save the user
			if($this->data["User"]['new_password'] != ""){
				$this->data["User"]["password"] = $this->Auth->password($this->data['User']['new_password']);
			}
			if ($this->User->save($this->data)) {
				$categoryModel = new Category();
				$this->set('json', array('status'=>'1', 'data'=>$this->data));
				$this->render('/javascript/json');
			}
		}
	}
	
	
	function listGroups(){
		$user_id =  $this->params['form']['id'];

		$user = $this->User->findById($user_id);
		
		$result = array("user_groups"=>$user['Group'], 'admin'=>$user['User']['admin']);
		$this->set('json', $result);
		$this->render('/javascript/json');
	}
	
	function delete(){
		$id = $this->params['form']['id'];
		$res = 0;
		if($this->Session->read('Auth.User.admin')){
			$data = $this->User->findById($id);
			$data["User"]["live"] = 0;
			if($this->User->save($data)){
				$res = 1;
			}
		}
		$result = array("result"=>$res);
		$this->set('json', $result);
		$this->render('/javascript/json');
	}
	
	function deactivate(){
		$id = $this->params['form']['id'];
		$res = 0;
		if($this->Session->read('Auth.User.admin')){
			$data = $this->User->findById($id);
			$data["User"]["active"] = 0;
			if($this->User->save($data)){
				$res = 1;
			}
		}
		$result = array("result"=>$res);
		$this->set('json', $result);
		$this->render('/javascript/json');
	}
	function activate(){
		$id = $this->params['form']['id'];
		$res = 0;
		if($this->Session->read('Auth.User.admin')){
			$data = $this->User->findById($id);
			$data["User"]["active"] = 1;
			if($this->User->save($data)){
				$res = 1;
			}
		}
		$result = array("result"=>$res);
		$this->set('json', $result);
		$this->render('/javascript/json');
	}
	
	function index(){
		$categoryModel = new Category();
		
		$groupModel = new Group();
		$groups = $groupModel->getTree();
		// add a rel attribute to the tree (for the groups look and feel)
		foreach($groups as $key=>$group){
			$groups[$key]['Group']['rel'] = 'group';
		}
		
		$this->set('groups', $groups);
		$this->set('users', $this->User->find('all', array("conditions"=>array("live"=>"1"/*, "id !="=>$this->user['User']['id']*/))));
		$this->section = 'people';
	}
	
	function ajaxSearch(){
		$str = strtolower($this->params['url']['q']);
		$conditions = array("LOWER(User.name) LIKE"=>$str.'%', "User.live"=>"1");
		$users = $this->User->find('all', array('conditions'=>$conditions, 'fields'=>array('User.id', 'User.name')));
		$result = array();
		foreach($users as $user){
			$result[] = $user["User"];
		}
		
		$this->set('json', $result);
		$this->render('/javascript/json');
	}
	
	function createOwnCategory(){
		$users = $this->User->find('all');
		$categoryModel = new Category();
		$permissionModel = new Permission();
		//print_r($users);
		foreach($users as $user){
			$id = $user['User']['id'];
			$categoryModel->create();
			if($categoryModel->save(array('Category'=>array('name'=>'::'. $id .'::', 'parent_id'=>null, 'shared'=>0)))){		// save the category for the personal passwords of the user
				$data['Permission'] = array(
					'aco_id' =>  '1',
					'aro_id' =>  '2',
					'aco_ref_id' =>  $categoryModel->id,
					'aro_ref_id' =>  $id,
					'_read' =>  '1',
					'_write' =>  '1',
					'_manage' => '0');
				
				$permissionModel->create();
				$permissionModel->save($data);			// save the corresponding permission (read and write. nobody can share his personal passwords)
			}
		}
	}
}

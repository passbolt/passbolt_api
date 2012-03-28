<?php
App::import('Model', 'Permission');
App::import('Model', 'Group');
App::import('Model', 'User');
App::import('Model', 'Password');


class CategoriesController extends AppController {
	var $name = 'Categories';
	var $helpers = array("Tree");
	
	/**
	 * index
	 * Generates the initial category tree, with its permission and send it to the view. 
	 * Permissions are stored in the shape of classnames once in the view
	 */
	function index() {
		$user = $this->Auth->user();
		$user_id = $user["User"]["id"]; 
		
		// define which database we open
		$database_id = $this->Session->read('database_id');


   		$nodes = $this->Category->getChildren($database_id);
   		$item = $this->Category->findById($database_id);
   		if($item['Category']['shared'] == '0' && $item['Category']['name'] == '::'. $user_id .'::'){
   			$item['Category']['name'] = "My passwords";
   		}
    	$nodes = array_merge(array(0=>$item),$nodes);
    	
    	$nodes = $this->PermissionManager->getTreePermissions($nodes);	// get the permissions for the tree of categories
    	
    	$nodes[0]['Category']['rel'] = 'root';
    	$nodes[0]['Category']['class'] = ("troot " . $nodes[0]['Category']['class']);
    	
    	// Add class corresponding to the category type (if not default)
		foreach($nodes as $k=>$n){
   			$ctype = $nodes[$k]['Category']['category_type_id'];
   			if($ctype != '0'){
   				$nodes[$k]['Category']['class'] = ("t$ctype " . $n['Category']['class']);
   			}
   		}
    	
    	$this->set('categories', $nodes);
    	$this->section = 'passwords';
   	}
   	
   	function getDatabaseTree(){
   		// TODO : Check permissions
   		$user_id = $this->user['User']['id'];
   		$database_id =  $this->params['form']['id'];
   		
   		if(!$this->PermissionManager->isDatabaseAccessible($database_id, $user_id)){
   			echo "no permissions";
   			exit(0);
   		}
   		else{
	   		$nodes = $this->Category->getChildren($database_id);
	   		$item = $this->Category->findById($database_id);
	   		if($item['Category']['shared'] == '0' && $item['Category']['name'] == '::'. $user_id .'::'){
	   			$item['Category']['name'] = "My passwords";
	   		}
	    	$nodes = array_merge(array(0=>$item),$nodes);
	    	
	    	$nodes = $this->PermissionManager->getTreePermissions($nodes);	// get the permissions for the tree of categories
	    	
	    	$nodes[0]['Category']['rel'] = 'root';
	    	$nodes[0]['Category']['class'] = ("troot " . $nodes[0]['Category']['class']);
	    	
	    	// set the cookies and the session
	    	$cookiedb = $this->Cookie->read("database_id");
	   		$cookiedb[$user_id] = $database_id;
	    	$this->Cookie->write("database_id", $cookiedb, false, 3600 * 24 * 365); // cookie will be persistent for one year
	   		$this->Session->write('database_id', $database_id);
	    	
	   		// Add class corresponding to the category type (if not default)
	   		foreach($nodes as $k=>$n){
	   			$ctype = $nodes[$k]['Category']['category_type_id'];
	   			if($ctype != '0'){
	   				$nodes[$k]['Category']['class'] = ("t$ctype " . $n['Category']['class']);
	   			}
	   		}
	   		
    		$this->set('categories', $nodes);
   		}
   	}
   	
   	function create(){
   		$user = $this->Auth->user();
	   	$data['Category']['parent_id'] =  $this->params['form']['id'];
	   	$data['Category']['name'] =  $this->params['form']['title'];
	   	$data['Category']['created_by'] =  $user['User']['id'];
	   	
	   	$perms = $this->PermissionManager->check(1, $data['Category']['parent_id']);
		if($perms['write'] == 1){
		   	if($this->Category->save($data)){
		   		$this->Watcher->logAction(WATCHER_CAT_CREATE, ACO_CATEGORY, $this->Category->id); // logs the action
		   		$res = array('status'=>1, 'id'=>$this->Category->id);
		   	}
		   	else{
		   		$res = array('status'=>0, 'data'=>array('error_code'=>'database_problem'));
		   	}
		}
		else{
			$res = array('status'=>0, 'data'=>array('error_code'=>'permission_not_valid'));
		}
	   	$this->set('json', $res);
		$this->render('/javascript/json');
   	}
   	
   	
   	function createDatabase(){
   		if (!empty($this->data)) {
   			if($this->Session->read('Auth.User.admin')){
		   		$user = $this->Auth->user();
		   		$this->data['Category']['parent_id'] =  null;
			   	$this->data['Category']['created_by'] =  $user['User']['id'];
		   	
			   	if(strlen($this->data['Category']['name']) > 0){
				   	if($this->Category->save($this->data)){
				   		$this->Watcher->logAction(WATCHER_CAT_CREATE_DATABASE, ACO_CATEGORY, $this->Category->id); // logs the action
				   		$res = array('status'=>1, 'data'=>array('id'=>$this->Category->id, 'name'=>$this->data['Category']['name']));
				   	}
				   	else{
				   		$res = array('status'=>0, 'data'=>array('error_code'=>'database_problem'));
				   	}
			   	}
			   	else{
			   		$res = array('status'=>0, 'data'=>array('error_code'=>'The database name must consist of at least 1 character'));
			   	}
   			}
   			else{
	   			$res = array('status'=>0, 'data'=>array('error_code'=>'permission_not_valid'));
	   		}
	   		$this->set('json', $res);
			$this->render('/javascript/json');
	   	}
   	}
   	
   	function changeDatabase($id=null){
   		$user = $this->Auth->user();
   		if($id != null){
   			$cookiedb = $this->Cookie->read("database_id");
	   		$cookiedb[$user['User']['id']] = $id;
	   		$this->Cookie->write("database_id", $cookiedb, false, 3600 * 24 * 365); // cookie will be persistent for one year
	   		$this->Session->write('database_id', $id);
	   		$this->Watcher->logAction(WATCHER_CAT_OPEN_DATABASE, ACO_CATEGORY, $id); // logs the action
   		}
   		//exit(0);
   		$this->redirect('/categories');
   	}
   	
   	function delete(){
   		// TODO : partial delete followed by a delete forever ?
   		// TODO : delete forever = deleting also the passwords
   		$category_id = $this->params['form']['id'];
   		
   		$permissionModel = new Permission();
   		$passwordModel = new Password();
   		
   		$perms = $this->PermissionManager->check(1, $data['Category']['parent_id']);
		if($perms['manage'] == 1){
	   		if($this->Category->delete($category_id)){
	   			$permissionModel->deleteAll(array("aco_id"=>1, "aco_ref_id"=>$category_id));  // delete the corresponding permissions
	   			$passwordModel->deleteAll(array("category_id"=>$category_id));
	   			$this->Watcher->logAction(WATCHER_CAT_DELETE, ACO_CATEGORY, $category_id); // logs the action
				$res = array('status'=>1, 'id'=>$category_id);
			}
	   		else{
		   		$res = array('status'=>0, 'data'=>array('error_code'=>'database_problem'));
		   	}
		}
		else{
			$res = array('status'=>0, 'data'=>array('error_code'=>'permission_not_valid'));
		}
		$this->set('json', $res);
		$this->render('/javascript/json');
   	}
   	
   	function rename(){
    	$this->Category->id = $this->params['form']['id']; 
    	$perms = $this->PermissionManager->check(1, $this->Category->id);
    	$cat = $this->Category->findById($this->Category->id);
    	if($perms['write'] == 1){
	   		if($this->Category->save(array('name' =>$this->params['form']['title']))){
	   			$this->Watcher->logAction(WATCHER_CAT_RENAME, ACO_CATEGORY, $this->Category->id, array('o'=>$cat['Category']['name'], 'n'=>$this->params['form']['title'])); // logs the action
	   			$res = array("status"=>1);
			}
	   		else{
		   		$res = array("status"=>0);
		   	}
    	}
    	$this->set('json', $res);
		$this->render('/javascript/json');
   	}
   	
	function changeType(){
    	$this->Category->id = $this->params['form']['cid']; 
    	$perms = $this->PermissionManager->check(1, $this->Category->id);
    	$cat = $this->Category->findById($this->Category->id);
    	if($perms['write'] == 1){
	   		if($this->Category->save(array('category_type_id' =>$this->params['form']['tid']))){
	   			//$this->Watcher->logAction(WATCHER_CAT_RENAME, ACO_CATEGORY, $this->Category->id, array('o'=>$cat['Category']['name'], 'n'=>$this->params['form']['title'])); // logs the action
	   			$res = array("status"=>1);
			}
	   		else{
		   		$res = array("status"=>0);
		   	}
    	}
    	$this->set('json', $res);
		$this->render('/javascript/json');
   	}
   	
	function move(){
		$id = $this->params['form']['id']; // element id
		$parent_id = $this->params['form']['ref']; // new parent id
		$pos = $this->params['form']['position'];
		$ref = $this->params['form']['ref']; // new parent id
		$copy = (isset($this->params['form']['copy']) && $this->params['form']['copy'] == '1' ? '1' : '0');
		
		
		/*$id = 10;
		$parent_id = 8;
		$pos = 0;
		$ref = 8;
		$copy = 1;*/
		
		$permissionModel = new Permission();
		$passwordModel = new Password();
		
		$perms = $this->PermissionManager->getAllowedUsersGroups(1, $id);
		$list_inherited = array();
		$list_direct = array();
		// establish a list of permissons : which ones are direct, which ones are inherited
		if(isset($perms['Group'])){
			foreach($perms['Group'] as $group_id => $group){
				if($group['permission_details']['inherited'] == '1'){   // if the permission is inherited, we'll need to apply it again directly after the copy
					$list_inherited[]['Group'] = array("group_id"=>$group_id, "permissions"=>$group["permissions"]);
				}
				else{
					$list_direct[]['Group'] = array("group_id"=>$group_id, "permissions"=>$group["permissions"]);
				}
			}
		}
		if(isset($perms['User'])){
			foreach($perms['User'] as $user_id=>$user){
				if($user['permission_details']['inherited'] == '1'){   // if the permission is inherited, we'll need to apply it again directly after the copy
					$list_inherited[]['User'] = array("user_id"=>$user_id, "permissions"=>$user["permissions"]);
				}
				else{
					$list_direct[]['User'] = array("user_id"=>$user_id, "permissions"=>$user["permissions"]);
				}
			}
		}
		
		if($copy == '1'){ //in case of copy
			$perms_src = $this->PermissionManager->check(1, $id);
			$perms_dst = $this->PermissionManager->check(1, $parent_id);
			if($perms_src['write'] == 1 && $perms_dst['write'] == 1){
				// check if the user is allowed to read and write in all subcategories
				if($this->PermissionManager->checkSubcats($id, "write")){
					// first, copy the parent at the right place
					$nodes = $this->Category->getChildren($id);
					$item = $this->Category->findById($id);
			   		$nodes = array_merge(array(0=>$item),$nodes);
					
					$new_parent_id = $parent_id;
					$processed = array();
					
					foreach($nodes as $n => $node){
						$cat = array();
						$node = $node['Category'];
						$cat['name'] = $node['name'];
						$cat['comment'] = $node['comment'];
						$cat['category_type_id'] = $node['category_type_id'];
						$user = $this->Auth->user();
						$cat['created_by'] = $user['User']['id'];
						
						if($n == 0){
							$cat['parent_id'] = $new_parent_id;
						}
						else{
							$cat['parent_id'] = $processed[$node['parent_id']];
						}
						$this->Category->create();
						$this->Category->save($cat);
						
						$processed[$node['id']] = $this->Category->id; // store the new id in the process table
						
						if($n == 0){ // Finally adjust the position of the parent in the list (is always at the end)
							$this->Category->move($this->Category->id, $new_parent_id, 'lastChild');
						}
						
						// now, copy all the passwords from the previous category to the new one
						$passwords = $passwordModel->find('all', array('conditions'=>array('category_id'=>$id)));
						foreach($passwords as $password){
							unset($password["Password"]["id"]);
							$password["Password"]["category_id"] = $this->Category->id;
							$passwordModel->create();
							$passwordModel->save($password);
						}
						// all passwords are copied
					}
					if(!empty($list_inherited)){
						foreach($list_inherited as $k=>$p){
							$key = isset($p['Group']) ? 'Group' : 'User';
							$aro_id = ($key == 'Group' ? 1 : 2);
							$aro_ref_id = ($key == 'Group' ? $p[$key]['group_id'] : $p[$key]['user_id']);
							$permissionModel->create();
							$permissionModel->save(array('Permission'=>array('aco_id'=>1, 'aco_ref_id'=>$this->Category->id, 'aro_id'=>$aro_id,'aro_ref_id'=>$aro_ref_id, '_read'=>$p[$key]['permissions']['_read'], '_write'=>$p[$key]['permissions']['_write'], '_manage'=>$p[$key]['permissions']['_manage'])));
						}
					}
					if(!empty($list_direct)){
						foreach($list_direct as $k=>$p){
							$key = isset($p['Group']) ? 'Group' : 'User';
							$aro_id = ($key == 'Group' ? 1 : 2);
							$aro_ref_id = ($key == 'Group' ? $p[$key]['group_id'] : $p[$key]['user_id']);
							$permissionModel->create();
							$permissionModel->save(array('Permission'=>array('aco_id'=>1, 'aco_ref_id'=>$this->Category->id, 'aro_id'=>$aro_id,'aro_ref_id'=>$aro_ref_id, '_read'=>$p[$key]['permissions']['_read'], '_write'=>$p[$key]['permissions']['_write'], '_manage'=>$p[$key]['permissions']['_manage'])));
						}
					}
					$this->Watcher->logAction(WATCHER_CAT_COPY, ACO_CATEGORY, $id, array('copy'=>$this->Category->id)); // logs the copy action
					$this->Watcher->logAction(WATCHER_CAT_CREATE, ACO_CATEGORY, $this->Category->id); // logs the create new category action
			   		$res = array("status"=>1, "id"=>$processed[$nodes[0]['Category']['id']], "keys"=>$processed);
				}
				else{ // will reach here if one subcategory is not writeable for the user
					$res = array('status'=>0, 'data'=>array('error_code'=>'subcat_not_allowed'));
				}
			}
			else{
				$res = array('status'=>0, 'data'=>array('error_code'=>'permission_not_valid'));
			}
		}
		else{
			$perms_src = $this->PermissionManager->check(1, $id);
			$perms_dst = $this->PermissionManager->check(1, $parent_id);
			$category = $this->Category->findById($id);
			if($perms_src['write'] == 1 && $perms_dst['write'] == 1){
				$this->Category->id = $id; 
				// check if the user is allowed to read and write in all subcategories
				if($this->PermissionManager->checkSubcats($id, "write")){
					$this->Category->move($this->Category->id, $parent_id, 'firstChild');
					if($pos > 0){
						$this->Category->moveDown($this->Category->id, $pos);
					}
					// assign all the inherited permissions as direct permissions to keep the same level of privileges
					// we only need to copy the inherited permissions, coz the direct permissions are already assigned to the particular category id
					if(!empty($list_inherited)){
						foreach($list_inherited as $k=>$p){
							$key = isset($p['Group']) ? 'Group' : 'User';
							$aro_id = ($key == 'Group' ? 1 : 2);
							$aro_ref_id = ($key == 'Group' ? $p[$key]['group_id'] : $p[$key]['user_id']);
							$permissionModel->create();
							$permissionModel->save(array('Permission'=>array('aco_id'=>1, 'aco_ref_id'=>$this->Category->id, 'aro_id'=>$aro_id,'aro_ref_id'=>$aro_ref_id, '_read'=>$p[$key]['permissions']['_read'], '_write'=>$p[$key]['permissions']['_write'], '_manage'=>$p[$key]['permissions']['_manage'])));
						}
					}
					$this->Watcher->logAction(WATCHER_CAT_MOVE, ACO_CATEGORY, $id, array('new_parent_id'=>$parent_id, 'old_parent_id'=>$category['Category']['id'])); // logs the copy action
					$res = array("status"=>1, "id"=>$id);
				}
				else{ 	// will reach here if one subcategory is not writeable for the user
					$res = array('status'=>0, 'data'=>array('error_code'=>'subcat_not_allowed'));
				}
			}
			else{
				$res = array('status'=>0, 'data'=>array('error_code'=>'permission_not_valid'));
			}
		}
		$this->set('json', $res);
		$this->render('/javascript/json');
   	}
   	
   	/**
   	 * Get the list of groups and users allowed to access the category / password
   	 */
   	function getPermissions(){
   		$cat_id=21;
   		if($this->PermissionManager->checkSubcats($cat_id, 'write')){
   			echo "passe";
   		}
   		else{
   			echo "passe pas";
   		}
   	}
   	
   	function getInformation(){
   		$id = $this->params['form']['id']; 
   		//$id = 31;
   		$category = $this->Category->findById($id);
   		$userModel = new User();
   		$user = $userModel->findById($category['Category']['created_by']);
    	$this->set('json', array_merge($category, $user));
		$this->render('/javascript/json');
   	}
   	
   	
   	function insertDummy(){
   		$format = array(
	    'name' => '::9::',
	    'parent_id' => null,
   		'shared'=>'0'
	    );
		$this->Category->save($format);
		$root_id = $this->Category->id;
		
		/*$format = array(
		    'name' => 'category 1',
		    'parent_id' => $root_id
		    );
		$this->Category->create();
		$this->Category->save($format);
		$cat_id = $this->Category->id;
		
		$format = array(
		    'name' => 'category 2',
		    'parent_id' => $root_id
		    );
		$this->Category->create();
		$this->Category->save($format);
		
		$format = array(
		    'name' => 'SubCat 1',
		    'parent_id' => $cat_id
		    );
		$this->Category->create();
		$this->Category->save($format);*/
   	}
   	
   	function repair(){
   		$this->Category->repair();
   	}
   	
}

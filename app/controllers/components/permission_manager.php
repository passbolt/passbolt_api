<?php 
class PermissionManagerComponent extends Object {
	var $cache = Array(); // cache will contain sections we want to keep to access it later
	
	var $components = array("Session", "Auth");
	
	function initialize(&$controller, $settings = array()) {
		$this->controller =& $controller;
	}
	
	function startup(&$controller) {
		
	}
	
	
	/**
	 * mkPerm
	 * transform an array of permission into short form (ex : wrm)
	 * @param $permission_arr
	 */
	function mkPerm($permission_arr){
		return ($permission_arr['read'] == '1' ? 'r':'-') . ($permission_arr['write'] == '1' ? 'w':'-') . ($permission_arr['manage'] == '1' ? 'm':'-');
	}
	
	/**
	 * Checks the whole subcategories of the tree to check if the given permission is respected everywhere
	 * @param $id
	 * @param $perm_needed : the permission wanted (can be read, write or manage)
	 * @return true if the check is clear (no permissions missing), or false otherwise
	 */
	function checkSubcats($id, $perm_needed){
		$categoryModel = ClassRegistry::init('Category');
		$subcats = $categoryModel->getChildren($id);
		$perm_missing = false;
		foreach($subcats as $subcat){
			$perm = $this->check(1, $subcat['Category']['id']);
			if($perm[$perm_needed] == 0){
				$perm_missing = true;
				break;
			}
		}
		return !$perm_missing;
	}
	
	/**
	 * 
	 * Check the permissions for a password/category and for a user
	 * Careful : cache is not implemented yet
	 * Careful : password level permission check is not implemented yet. Right now it works only for categories
	 * @param $aco_id
	 * @param $aco_ref_id
	 * @param $user_id
	 * @param $cache
	 */
	function check($aco_id, $aco_ref_id, $user_id = null, $cache = false){   //TODO: implement cache and password level permission management
		// Instanciate the necessary models
		$userModel = ClassRegistry::init('User');
		$groupModel = ClassRegistry::init('Group');
		$permissionModel = ClassRegistry::init('Permission');
		$passwordModel = ClassRegistry::init('Password');
		$categoryModel = ClassRegistry::init('Category');
		
		if($user_id == null){		// gets the user id if not provided
			$curr_user = $this->Auth->user();
			$user_id = $curr_user["User"]["id"];
		}
		
		// if the user is an admin, we return directly the results (all permissions allowed)
		if($curr_user["User"]["admin"]){
			return array("read"=>1, "write"=>1, "manage"=>1);
		}
		
		if($aco_id == '1'){
			$cat_id = $aco_ref_id;
			$categories = $categoryModel->getRootFromChild($cat_id);
		}
		else{
			$password = $passwordModel->findById($aco_ref_id);
			$cat_id = $password['Password']['category_id'];
			$categories = $categoryModel->getRootFromChild($cat_id);
    		$categories = array_merge($categories, array($password));	
		}
		
    	$user = $userModel->findById($user_id);
    	$user_copy = $user; // $user_merge will be used to add in the group array
    	unset($user_copy['Group']); // we remove the Group index to avoid confusion
    	$i = 0;
    	if(!empty($user['Group'])){
	    	foreach($user['Group'] as $group){
	    		$groups_tree = $groupModel->getRootFromChild($group['id']);
	    		$groups[$i] = array_merge($groups_tree, array($user_copy));
	    		$i++;
	    	}
    	}
    	else{
    		$groups[$i] = array($user_copy);
    	}
    	
		// create an array of group ids for the query condition
    	$group_id_arr = array();
    	for($i=0; $i<sizeof($groups);$i++){
    		foreach($groups[$i] as $group){
    			if(isset($group['Group'])){ // if it's a group and not a user
    				$group_id_arr[] = $group['Group']['id'];
    			}
    		}
    	}
    	
		// create a group of categories id for the query condition
		$cat_id_arr = array();
    	foreach($categories as $category){
    		if(isset($category['Category']))
    			$cat_id_arr[] = $category['Category']["id"];
    	}
    	
	   	// retrieve all the permissions for our case (corresponding group ids, user id, and categories id)
    	$permissions_group = $permissionModel->find('all', array('conditions'=>(array('Permission.aco_ref_id'=>$cat_id_arr, 'Permission.aco_id'=>'1', 'Permission.aro_ref_id'=>$group_id_arr, 'Permission.aro_id'=>'1')), 'recursive'=>'-1'));
    	$permissions_user = $permissionModel->find('all', array('conditions'=>(array('Permission.aco_ref_id'=>$cat_id_arr, 'Permission.aco_id'=>'1', 'Permission.aro_ref_id'=>array($user_id), 'Permission.aro_id'=>'2')), 'recursive'=>'-1'));
    	$permissions = array_merge($permissions_group, $permissions_user);
    	/*if($aco_id == "2"){
    		$permissions_group_password = $permissionModel->find('all', array('conditions'=>(array('Permission.aco_ref_id'=>$cat_id_arr, 'Permission.aco_id'=>'2', 'Permission.aro_ref_id'=>$group_id_arr, 'Permission.aro_id'=>'1')), 'recursive'=>'-1'));
    		$permissions_user_password = $permissionModel->find('all', array('conditions'=>(array('Permission.aco_ref_id'=>$cat_id_arr, 'Permission.aco_id'=>'2', 'Permission.aro_ref_id'=>array($user_id), 'Permission.aro_id'=>'2')), 'recursive'=>'-1'));
    		$permissions = array_merge($permissions, $permissions_group_password, $permissions_user_password);
    	}*/
    	
    	$perms_arr = array();
    	// store the permissions using this structure :
    	// $perms[$cat_id]['Group'][$group_id] = $permission; ('User' can replace 'Group')
    	foreach($permissions as $permission){
    		$perms_arr[$permission['Permission']['aco_ref_id']][($permission['Permission']['aro_id'] == '1' ? 'Group' : 'User')][$permission['Permission']['aro_ref_id']] = $permission['Permission'];
    	}
    	
    	$perms = array("_read"=>0, "_write"=>0, "_manage"=>0);
    	$groups_perm = null;
    	// Browse UP the tree to find permissions
    	for($i = (sizeof($categories) - 1); $i >= 0; $i--){
    		$category = $categories[$i];
    		for($j=0; $j<sizeof($groups);$j++){	
	    		for($k = (sizeof($groups[$j]) - 1); $k >= 0; $k--){ 
	    			$group = $groups[$j][$k];
		    		$curr_aro_key = (array_key_exists('Group', $group) ? 'Group' : 'User');								// $curr_aro_key defines what key to use in the permissions array
		    		//echo "checking group : {$group[$curr_aro_key]['name']} category : {$category['Category']['name']} <br/>";	    		
		    		if(isset($perms_arr[$category['Category']['id']][$curr_aro_key][$group[$curr_aro_key]['id']])){ 	// is there a corresponding permission for the current category and group
		    			$groups_perm[$j] = $perms_arr[$category['Category']['id']][$curr_aro_key][$group[$curr_aro_key]['id']];
		    		}
		    		if(sizeof($groups_perm) > 0) break;
	    		}
    		}
    		if(sizeof($groups_perm) > 0) break;
    	}
    	
		if($groups_perm != null){ // if there is one / several new permissions
    		$tmp = array('read'=>0, 'write'=>0, 'manage'=>0);
    		foreach($groups_perm as $group_perm){
    			$tmp['read'] = ($group_perm['_read'] == 1 ? '1' : $tmp['read']);
    			$tmp['write'] = ($group_perm['_write'] == 1 ? '1' : $tmp['write']);
    			$tmp['manage'] = ($group_perm['_manage'] == 1 ? '1' : $tmp['manage']);
    		}
    		$permissions = $tmp;
    	}
    	else{
    		$permissions = array("read"=>0, "write"=>0, "manage"=>0);	
    	}
    	
    	return $permissions;
	}
	
	/**
	 * getTreePermissions
	 * Return the list of permissions for the given categories list or category id
	 * The function will add inside each category an array called "perms" containing the corresponding permissions
	 * @param $root_id : either a tree of categories, or the id of the parent
	 * @param $user_id : the user for which to get the list of permissions
	 */
	function getTreePermissions($root_id, $user_id=null){
		$userModel = ClassRegistry::init('User');
		if($user_id == null){		// gets the user id if not provided
			$curr_user = $this->Auth->user();
			$user_id = $curr_user["User"]["id"];
		}
		else{
			$curr_user = $userModel->findById($user_id);
		}

		// initialize $nodes
		if(!is_array($root_id)){	// if $root_id is not an aray but the parent id, we get the tree structure
			$categoryModel = ClassRegistry::init('Category');
			
			$nodes = $categoryModel->getChildren($root_id);
   			$item = $categoryModel->findById($root_id);
    		$nodes = array_merge(array(0=>$item),$nodes);
    		$nodes[0]['Category']['rel'] = 'root';
		}
		else{
			$nodes = $root_id;
		}
		
		// Instanciate the necessary models
		$groupModel = ClassRegistry::init('Group');
		$permissionModel = ClassRegistry::init('Permission');
		
		// Get the corresponding groups for the user
    	$user = $userModel->findById($user_id);
    	$user_copy = $user; // $user_merge will be used to add in the group array
    	unset($user_copy['Group']); // we remove the Group index to avoid confusion
    	$i = 0;
    	$groups = array();
    	if(!empty($user['Group'])){
	    	foreach($user['Group'] as $group){
	    		$group_tree = $groupModel->getRootFromChild($group['id']);
	    		$groups[$i] = array_merge($group_tree, array($user_copy));
	    		$i++;
	    	}    
    	}
    	else{
    		$groups[$i] = array($user_copy);
    	}
    	
		// create an array of group ids for the query condition
    	$group_id_arr = array();
    	for($i=0; $i<sizeof($groups);$i++){
    		foreach($groups[$i] as $group){
    			if(isset($group['Group'])){ // if it's a group and not a user
    				$group_id_arr[] = $group['Group']['id'];
    			}
    		}
    	}
    	
    	// create a group of categories id for the query condition
		$cat_id_arr = array();
    	foreach($nodes as $node){
    		$cat_id_arr[] = $node['Category']["id"];
    	}
		
    	// retrieve all the permissions for our case (corresponding group ids, user id, and categories id)
    	$permissions_group = $permissionModel->find('all', array('conditions'=>(array('Permission.aco_ref_id'=>$cat_id_arr, 'Permission.aco_id'=>'1', 'Permission.aro_ref_id'=>$group_id_arr, 'Permission.aro_id'=>'1')), 'recursive'=>'-1'));
    	$permissions_user = $permissionModel->find('all', array('conditions'=>(array('Permission.aco_ref_id'=>$cat_id_arr, 'Permission.aco_id'=>'1', 'Permission.aro_ref_id'=>array($user_id), 'Permission.aro_id'=>'2')), 'recursive'=>'-1'));
    	$permissions = array_merge($permissions_group, $permissions_user);

    	$perms_arr = array();
    	// store the permissions using this structure :
    	// $perms[$cat_id]['Group'][$group_id] = $permission; ('User' can replace 'Group')
    	foreach($permissions as $permission){
    		$perms_arr[$permission['Permission']['aco_ref_id']][($permission['Permission']['aro_id'] == '1' ? 'Group' : 'User')][$permission['Permission']['aro_ref_id']] = $permission['Permission'];
    	}
    	
    	// Set the initial set of permissions : nothing is allowed unless explicitely defined
    	$permissions = array('read'=>0, 'write'=>0, 'manage'=>0);
    	$categories = $nodes;
    	
    	////////////////////////////////////////////////////////////////////////////////////////////
    	// STEP 2 : browse the categories and for each determinate the permissions of the group  ///
    	////////////////////////////////////////////////////////////////////////////////////////////
    	$inheritance = array(); // keeps inheritance permissions for each level. We memorize at for each category the latest permission for the level, so the children can inherit
    	$cat_level = 0;
    	$prev_cat_level = 0;
    	
    	// let's go
		foreach($categories as $catkey=>$category){								// first, we browse the categories
			$cat_level = $category['Category']['level'];

  			$groups_perm = null;
    		if(!$curr_user["User"]["admin"]){ 									// if the user is not admin, go through the normal process (and of course, if he is an admin, ha can access to everything without restrictions)
    			for($i=0; $i<sizeof($groups);$i++){								// foreach group thet the user belongs to
		    		//echo "passe";
    				foreach($groups[$i] as $group){ 							// browse the group tree (from root to child) one by one to calculate the permission (for the given category)
		    			$curr_aro_key = (array_key_exists('Group', $group) ? 'Group' : 'User');			// $curr_aro_key defines what key to use in the permissions array
		    			//echo "perms_arr[{$category['Category']['id']}][$curr_aro_key][{$group[$curr_aro_key]['id']}]";
		    			if(isset($perms_arr[$category['Category']['id']][$curr_aro_key][$group[$curr_aro_key]['id']])){ 	// is there a corresponding permission for the current category and group
		    				$groups_perm[$i] = $perms_arr[$category['Category']['id']][$curr_aro_key][$group[$curr_aro_key]['id']];
		    			}
		    		}
	    		}
    
	    		if($groups_perm != null){ // if there is one / several new permissions
	    			$tmp = array('read'=>0, 'write'=>0, 'manage'=>0);
	    			foreach($groups_perm as $group_perm){
		    			$tmp['read'] = ($group_perm['_read'] == 1 ? '1' : $tmp['read']);
		    			$tmp['write'] = ($group_perm['_write'] == 1 ? '1' : $tmp['write']);
		    			$tmp['manage'] = ($group_perm['_manage'] == 1 ? '1' : $tmp['manage']);
		    		}
		    		$permissions = $tmp;
				    $groups_perm = null;
	    		}
	    		elseif($cat_level < $prev_cat_level && isset($inheritance[$cat_level - 1])){
	    			//echo "inhritance";
	    			$permissions = $inheritance[$cat_level - 1];
	    		}
	    		elseif($cat_level > 0){   // if there are no permissions set at the end, automatically take the parent permission (last one is stored for each level)
	    			$permissions = $inheritance[$cat_level - 1];
	    		}
    		}
    		else{
    			if($category['Category']['shared']){
    				$permissions = array('read'=>1, 'write'=>1, 'manage'=>1);						// if the user is admin, he has all the permissions, unless database is not meant to be shared
    			}
    			else{
    				$permissions = array('read'=>1, 'write'=>1, 'manage'=>0);						// if not a shared database, even is the user is admin he doesn't have the admin permissions
    			}
    		}

    		$inheritance[$cat_level] = $permissions;											// update $inheritance with the last value for the current level
    		$categories[$catkey]['perms'] = $permissions;

    		$prev_cat_level = $cat_level;
    		$rights = $this->mkPerm($permissions);
    		//$categories[$catkey]['Category']['name'] .= (' ' . $rights );  // to remove : only for debugging
    		//$categories[$catkey]['Category']['name'] .= (' ' . $this->mkperm($this->check('1', $categories[$catkey]['Category']['id'], $user_id)));  // to remove : only for debugging
    		$categories[$catkey]['Category']['class'] = ($rights == '---' ? 'norights' : $rights);
    	}
    	return $categories;
	}
	
	/**
	 * Browse the database from the top to the bottom and see if the user can access at least to one directory
	 * @param $user_id
	 * @param $database_id
	 */
	function isDatabaseAccessible($database_id, $user_id){
		//echo $user_id;
		$treeperms = $this->getTreePermissions($database_id, $user_id);
		//pr($treeperms);
		foreach($treeperms as $tp){
			if($tp['perms']['read'])
				return true;
		}
		return false;
	}
	
	function getAllowedUsersGroups($aco_id, $aco_ref_id){
    	
    	$categoryModel = ClassRegistry::init('Category');
    	$userModel = ClassRegistry::init('User');
    	$groupModel = ClassRegistry::init('Group');
    	$passwordModel = ClassRegistry::init('Password');
    	$permissionModel = ClassRegistry::init('Permission');
    	
    	//$aco_id = 1;
    	//$aco_ref_id=75;
    	
    	// STEP 1: GET THE CATEGORY HIERARCHY FROM THE TOP ROOT
    	if($aco_id == 2){ // password
    		$pass = $passwordModel->findById($aco_ref_id);
    		$categories = $categoryModel->getRootFromChild($pass['Password']['category_id']);
    		$categories = array_merge($categories, array($pass));	
    	}
    	else{ // category
    		$categories = $categoryModel->getRootFromChild($aco_ref_id);	
    	}
    	
    	$res = array();
    	
    	// STEP 2 : GET THE LIST OF USERS ALLOWED WITH THEIR PERMISSIONS
    	foreach($categories as $category){
    		$curr_aco_id = (array_key_exists('Category', $category) ? '1' : '2');
    		$curr_aco_key = (array_key_exists('Category', $category) ? 'Category' : 'Password');
    		$cond = array('aco_id'=>$curr_aco_id, 'aco_ref_id'=>$category[$curr_aco_key]['id']);
    		
    		//echo "/////////////// CATEGORY PERMISSION {$category['Category']['name']} ////////////////<br/>";
    		$permissions = $permissionModel->find('all', array('conditions'=>$cond));
    		if($permissions){
	    		foreach($permissions as $permission){
	    			unset($permission['Aco']);
	    			unset($permission['Aro']);
	    			if($permission['Permission']['aro_id'] == '1'){
	    				$key = 'Group';
	    				$modelName = 'groupModel';
	    				$modelFields = array('id', 'name');
	    			}
	    			else{
	    				$key = 'User';
	    				$modelName = 'userModel';
	    				$modelFields = array('id', 'name', 'email');
	    			}
	    			$id = $permission['Permission']['aro_ref_id'];
	    			//get the corresponding group or user
	    			//echo "print permission<br/>";
	    			//pr($permission);
	    			if(!isset($res[$key]) || !array_key_exists($id, $res[$key])){ // check if the user or group already exists in the array
	    				// if doesn't exist, insert the new value
		    			$res[$key][$id]['details'] = $$modelName->findById($id, $modelFields);
		    			if($key == 'User') unset($res[$key][$id]['details']['Group']); //if model is user, we don't need to keep the group information
	    			}
	    			$res[$key][$id]['permissions'] = array(
	    				'_read'=>$permission['Permission']['_read'], 
	    				'_write'=>$permission['Permission']['_write'], 
	    				'_manage'=>$permission['Permission']['_manage']
	    				);
	    			$res[$key][$id]['permission_details'] = array(
	    				'type'=>strtolower($curr_aco_key), 
	    				'id'=>$category[$curr_aco_key]['id'], 
	    				'inherited'=>($curr_aco_id == $aco_id && $category[$curr_aco_key]['id'] == $aco_ref_id ? '0' : '1'),
	    				'perm_id'=>$permission['Permission']['id']
	    			);
	    		}
    		}
    		//echo "//////////////// END PERMISSION ///////////////////<br/>";
    	}
    	//pr($res);
    	$groups = $groupModel->generateTreeList();
    	
    	// we use the loop to remove the first element. We don't want the parent node (has to remain invisible in the UI)
    	$i = 0;
    	$groupsf = $groups;
    	$groups = array();
    	foreach($groupsf as $k=>$group){
    		if($i > 0 ) {
    			$groups[$k] = substr($group, 1); // remove one "_" at the beginning (consequence of keeping the parent invisible)
    		}
    		$i++;
    	}

    	foreach($groups as $key=>$group){
    		if(isset($res['Group'][$key])){
    			unset($groups[$key]);
    		}
    	}
    	
    	$res = array_merge($res, array('gl'=> $groups));
    	$users = $userModel->find('all', array('conditions'=>array('active'=>1, 'live'=>1), 'fields'=>array('id', 'name'))); // select all the active users (active and not deleted)
    	//pr($users);
    	$ul = array();
    	foreach($users as $key=>$user){
    		if(!isset($res['User'][$user['User']['id']])){
	    		$ul[$user['User']['id']]['id'] = $user['User']['id']; 
	    		$ul[$user['User']['id']]['name'] = $user['User']['name'];
	    		foreach($user['Group'] as $group){
	    			$ul[$user['User']['id']]['groups'][] = $group['id'];
	    		}
    		}
    	}
    	$res = array_merge($res, array('ul'=>$ul));
    	return $res;
	}
	
}

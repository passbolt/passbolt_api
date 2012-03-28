<?php
App::import('Model', 'Category');
App::import('Model', 'Group');
App::import('Model', 'User');
App::import('Model', 'Password');

class PermissionsController extends AppController {
    var $name = 'Permissions';    
    
    
    function save(){
    	/*$data['Permission']['aro_id'] =  '2';
	   	$data['Permission']['aco_id'] =  '1';
	   	$data['Permission']['aro_ref_id'] =  '18';
	   	$data['Permission']['aco_ref_id'] =  '18';
	   	$data['Permission']['_read'] =  '18';
	   	$data['Permission']['_write'] =  '18';
	   	*/
    	
    	$perms = $this->params['form']['permissions'];
    	$group_id = $this->params['form']['perm_group_id'];
    	$user_id = $this->params['form']['perm_user_id'];
    	
	   	
    	if($group_id != '-1' || $user_id != '-1'){
	    	if($user_id != '-1'){
	    		$data['Permission']['aro_id'] = '2';
	    		$data['Permission']['aro_ref_id'] = $user_id;
	    	}
	    	else{
	    		$data['Permission']['aro_id'] = '1';
	    		$data['Permission']['aro_ref_id'] = $group_id;
	    	}
	    	
	    	$_perms = array();
	    	if($perms == '---'){
	    		$_perms = array('_read'=>0, '_write'=>0, '_manage'=>0);
	    	}
	    	elseif($perms == 'r--'){
	    		$_perms = array('_read'=>1, '_write'=>0, '_manage'=>0);
	    	}
	    	elseif($perms == 'rw-'){
	    		$_perms = array('_read'=>1, '_write'=>1, '_manage'=>0);
	    	}
	   		elseif($perms == 'rwm'){
	    		$_perms = array('_read'=>1, '_write'=>1, '_manage'=>1);
	    	}
	    	$data['Permission']['aco_id'] =  $this->params['form']['perm_aco_id'];
		   	$data['Permission']['aco_ref_id'] =  $this->params['form']['perm_aco_ref_id'];
		   	$data['Permission'] = array_merge($data['Permission'], $_perms);
		   	
	    	if($this->Permission->save($data)){
	    		$this->Watcher->logAction(
	    			WATCHER_PERMISSION_ADD, 
	    			$data['Permission']['aco_id'], 
	    			$data['Permission']['aco_ref_id'], 
	    			array(
	    				'aro_id'=>$data['Permission']['aro_id'],
	    				'aro_ref_id'=>$data['Permission']['aro_ref_id'],
	    				'perms'=>$_perms
	    			)
	    		); // logs the action
	    		$result = '1';
	    	}
	    	else{
	    		$result = '0';
	    	}
    	}
    	else{
    		$result = '0';
    	}
    	
    	$res = array('result'=>$result);
    	$this->set('json', $res);
		$this->render('/javascript/json');
    }
    
    function modify(){
    	$data['Permission']['aro_id'] =  $this->params['form']['aro_id'];
	   	$data['Permission']['aco_id'] =  $this->params['form']['aco_id'];
	   	$data['Permission']['aro_ref_id'] =  $this->params['form']['aro_ref_id'];
	   	$data['Permission']['aco_ref_id'] =  $this->params['form']['aco_ref_id'];
	   	
	   	// check if the permission already exist. If doesn't exist, will create a new permission
	   	$perm = $this->Permission->find('first', array('conditions'=>$data['Permission']));
	   	if(isset($perm['Permission']['id'])){
	   		$data['Permission']['id'] = $perm['Permission']['id'];
	   	}
	   	else{
	   		$this->Permission->create();
	   	}
	   	
	   	$perms = $this->params['form']['permissions'];
    	$_perms = array();
    	if($perms == '---'){
    		$_perms = array('_read'=>0, '_write'=>0, '_manage'=>0);
    	}
    	elseif($perms == 'r--'){
    		$_perms = array('_read'=>1, '_write'=>0, '_manage'=>0);
    	}
    	elseif($perms == 'rw-'){
    		$_perms = array('_read'=>1, '_write'=>1, '_manage'=>0);
    	}
   		elseif($perms == 'rwm'){
    		$_perms = array('_read'=>1, '_write'=>1, '_manage'=>1);
    	}
    	$data['Permission'] = array_merge($data['Permission'], $_perms);
    	if($this->Permission->save($data)){
    		if(isset($perm['Permission']['id'])){
	    		$this->Watcher->logAction(
		    			WATCHER_PERMISSION_MODIFY, 
		    			$data['Permission']['aco_id'], 
		    			$data['Permission']['aco_ref_id'], 
		    			array(
		    				'id'=>$perm['Permission']['id'],
		    				'aro_id'=>$data['Permission']['aro_id'],
		    				'aro_ref_id'=>$data['Permission']['aro_ref_id'],
		    				'perms'=>$_perms
		    			)
		    		); // logs the action
    		}
    		else{
    			$this->Watcher->logAction(
		    			WATCHER_PERMISSION_ADD, 
		    			$data['Permission']['aco_id'], 
		    			$data['Permission']['aco_ref_id'], 
		    			array(
		    				'aro_id'=>$data['Permission']['aro_id'],
		    				'aro_ref_id'=>$data['Permission']['aro_ref_id'],
		    				'perms'=>$_perms
		    			)
		    		); // logs the action
    		}
    		$result = '1';
    	}
    	else{
    		$result = '0';
    	}
    	$res = array('result'=>$result);
    	$this->set('json', $res);
		$this->render('/javascript/json');
    }
    
    function delete(){
    	$id = $this->params['form']['id'];
    	if($this->Permission->delete($id)){
    		$this->Watcher->logAction(
		    			WATCHER_PERMISSION_DELETE, 
		    			null, 
		    			null, 
		    			array(
		    				'id'=>$id
		    			)
		    		); // logs the action
    		$res = 1;
    	}
    	else $res = 0;
    	$this->set('json', array('result'=>$res));
		$this->render('/javascript/json');
    }
    
    function getAllowedUsersGroups(){
    	$aco_ref_id = $this->params['form']['aco_ref_id'];
    	$aco_id = $this->params['form']['aco_id'];
    	
    	$res = $this->PermissionManager->getAllowedUsersGroups($aco_id, $aco_ref_id);
    	
    	$this->set('json', $res);
		$this->render('/javascript/json');
    }
    
    function check(){
    	$this->PermissionManager->check(1, 14);
    }
}
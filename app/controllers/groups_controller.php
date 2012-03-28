<?php
App::import('Model', 'Category');
App::import('Model', 'User');

class GroupsController extends AppController {
	var $name = 'Groups';
	var $helpers = array("Tree");
	
	function see(){
		echo "aro";
		pr($this->Group->generateTreeList(array('root_id'=>54)));
		$category = new Category();
		echo "aco";
		pr($category->generateTreeList(array('root_id'=>1)));
		exit(0);
	}
	
	function index(){
		$groups = $this->Group->getTree();
		//pr($groupsres);
		/*foreach($groupsres as $group){  // this was removed coz there was a bug due to the multitree. now, we use an invisible parent category
			$node = $group;
			$groups = array_merge($groups, array_merge(array(0=>$node), $this->Group->getChildren($node['Group']['id'])));
		}*/
		//pr($groups);
		// add a rel attribute to the tree (for the groups look and feel)
		foreach($groups as $key=>$group){
			$groups[$key]['Group']['rel'] = 'group';
		}
		$this->set('groups', $groups);
	}
	
	
	function create(){
   		$user = $this->Auth->user();
   		
   		$grouproot = $this->Group->findByLevel(0);
   		$grouproot_id = $grouproot['Group']['id'];
   		
	   	$data['Group']['parent_id'] =  $this->params['form']['id'] != 0 ? $this->params['form']['id'] : $grouproot_id;
	   	$data['Group']['name'] =  $this->params['form']['title'];
	   	$data['Group']['created_by'] =  $user['User']['id'];
	   	
	   	//todo : manage position and parent
	   	$this->Group->create();
	   	if($this->Group->save($data)){
   			$res = array("status"=>"1", "id"=>$this->Group->id);
	   	}
	   	else{
	   		$res = array("status"=>"0");
	   	}
	   	$this->set('json', $res);
		$this->render('/javascript/json');
   	}
   	
   	function addtest(){
   		$data['Group']['parent_id'] =  null;
	   	$data['Group']['name'] =  'testauto1';
	   	
	   	//todo : manage position and parent
	   	
	   	if($this->Group->save($data)){
   			echo "ok";
	   	}
   	}
   	
	function delete(){
		if($this->Group->removeFromTree($this->params['form']['id'], true)){
			$res = array("status"=>"1");
		}
   		else{
	   		$res = array("status"=>"0");
	   	}
	   	$this->set('json', $res);
		$this->render('/javascript/json');
   	}
   	
   	function rename(){
    	$this->Group->id = $this->params['form']['id']; 
   		if($this->Group->save(array('name' =>$this->params['form']['title']))){
   			$res = array("status"=>"1");
		}
   		else{
	   		$res = array("status"=>"0");
	   	}
	   	$this->set('json', $res);
		$this->render('/javascript/json');
   	}
	
   	function repair(){
   		$this->Group->repair();
   	}
   	
	function insertDummy(){
   		$format = array(
	    'name' => 'groups',
	    'parent_id' => null
	    );
		$this->Group->save($format);
		$root_id = $this->Group->id;
		
		/*$format = array(
	    'name' => 'group2',
	    'parent_id' => null
	    );
	    $this->Group->create();
		$this->Group->save($format);
		$root2_id = $this->Group->id;
		
		$format = array(
		    'name' => 'subgroup1',
		    'parent_id' => $root_id
		    );
		$this->Group->create();
		$this->Group->save($format);
		$cat_id = $this->Group->id;
		
		$format = array(
		    'name' => 'subgroup2',
		    'parent_id' => $root2_id
		    );
		$this->Group->create();
		$this->Group->save($format);
		$designer_id = $this->Group->id;
		
		$format = array(
		    'name' => 'Team Leaders',
		    'parent_id' => null
		    );
		$this->Group->create();
		$this->Group->save($format);
		$root3_id = $this->Group->id;

		
		$this->Group->create();
		$format = array(
	    'name' => 'test1',
	    'parent_id' => $root3_id
	    );
		$this->Group->save($format);*/
   	}
   	
   	function getTree(){
   		$groups = $this->Group->getTree();
		// add a rel attribute to the tree (for the groups look and feel)
		foreach($groups as $key=>$group){
			$groups[$key]['Group']['rel'] = 'group';
		}
		$this->set('groups', $groups);
		$this->layout = null;
   	}
   	
    function setPermission(){
    	$this->Acl->allow(array('model' => 'Group', 'foreign_key' => 4), array('model' => 'Category', 'foreign_key' => 57), 'create');
    }
}

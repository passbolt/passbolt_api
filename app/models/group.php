<?php
class Group extends AppModel {
	var $name = 'Group';
	//var $actsAs = array('GroupTree');
	var $actsAs = array(
        'MultiTree' => array(
            'root' =>'root_id',
            'level' =>'level'
            )
        ); 
        
     function getTree(){
     	$grouproot = $this->findByLevel(0);
     	$children = $this->getChildren($grouproot['Group']['id']);
		$groups = $children;
		return $groups;
     }
     
}

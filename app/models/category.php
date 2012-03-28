<?php
class Category extends AppModel {
	var $name = 'Category';
	//var $actsAs = array('GroupTree');
	var $actsAs = array(
        'MultiTree' => array(
            'root' =>'root_id',
            'level' =>'level'
            )
        ); 
}

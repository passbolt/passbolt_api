<?php
App::uses('CategoryType', 'Model');

class CategoryTypeFixture extends CakeTestFixture {
	public $useDbConfig = 'test';
	public $import = 'CategoryType';
	 
	public function init(){
		$this->records = array(
	        array('id'=>'50152793-9efc-4a7f-b79e-1358b4e000c3', 'name'=>'default'),
	        array('id'=>'50152793-52b4-47aa-940d-1358b4e000c3', 'name'=>'ssh'),
	        array('id'=>'50152793-aa7c-466d-9934-1358b4e000c3', 'name'=>'database'),
	    );
		parent::init();
	}

    
}
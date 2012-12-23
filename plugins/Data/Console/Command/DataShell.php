<?php

class DataShell extends AppShell {

	public $dataModels = array(
		'Role',
		'User',
		'CategoryType',
		'Category',
		'Group',
		'GroupUser',
		'Tag',
		'ResourceTag',
		'PermissionType',
		'Permission',
	);
	
	public function generate() {
		
	}
	
/**
 * Install data test for the defined dataModel 
 * @return void
 */	
	public function install() {
		foreach($this->dataModels as $dataModel){
			$Task = $this->Tasks->load('Data.' . $dataModel);
			$Task->execute();
			$this->out('Data for model ' . $dataModel . ' inserted');
		}
	}

}

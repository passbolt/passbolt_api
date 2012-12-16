<?php
/**
 * RandomResourceBehavior Behavior - autopopulate created_by, modified_by
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Test.Case.Model.Behavior.RandomResourceBehavior
 * @since        version 2.12.7
 */
App::uses('Resource', 'Model');
App::uses('Category', 'Model');
App::uses('RandomTool', 'Vendor');
class ModelTestCaseBehavior extends ModelBehavior {

/**
 * Set test data
 * @return void
 * @access public
 */
	public function setTestData(&$model, $options = array()) {	
		
		// Set Test Data for model Resource
		if (is_a($model, 'Resource')) {
			$model->set(array(
				'name' => RandomTool::randomString(),
				'username' => RandomTool::randomString(),
				'uri' => RandomTool::randomString(),
				'description' => RandomTool::randomString()
			));
		}
		
		// Set Test Data for model Category
		else if (is_a($model, 'Category')) {
			$parent_id = null;
			$Category = ClassRegistry::init('Category');
			
			// define parent following options
			if (isset($options['parentCategory'])) {
				if (is_a($options['parentCategory'], 'Category')) {
					$parentId = $options['parentCategory']['Category']['id'];
				} else if ($options['parentCategory'] == 'RAND') {
					$randCat = $Category->find('first', array('order' => 'rand()'));
					$parentId = $randCat['Category']['id'];
				}
			}
			
			$model->set(array(
				'name' => RandomTool::randomString(),
				'parent_id' => $parent_id
			));
		}
		

	}
}
<?php
/**
 * Model Test Behavior
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
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
				'name' => RandomTool::string(),
				'username' => RandomTool::string(),
				'uri' => RandomTool::string(),
				'description' => RandomTool::string()
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
				'name' => RandomTool::string(),
				'parent_id' => $parent_id
			));
		}
		

	}
}
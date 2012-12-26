<?php
/**
 * Permission Component
 * This class is used to manage permissions from controller class.
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Controller.PermissionComponent
 * @since        version 2.12.7
 */

App::uses('Permission', 'Model');

class PermissionComponent extends Component {

/**
 * Initialize
 * @param object $controller Controller using this component
 * @return boolean Proceed with component usage (true), or fail (false)
 * @throws exception is Session component is missing
 * @access public
 */
	public function initialize(&$controller, $settings=array()) {
		$this->Permission = ClassRegistry::init('Permission');
//		$this->Category = ClassRegistry::init('Category');
//		$this->Category->hasOne = array();
		return parent::initialize($controller, $settings);
	}
	
/**
 * Add a permission to an element
 * @param AppModel target The target to apply permission
 * @param AppModel who Who will benefit the permission
 * @param integer type The permission type to apply
 * @return boolean 
 */
	public function add($targetType, $target, $whoType, $who, $type) {
		$data = array('Permission'=>array(
			'aco' => $targetType,
			'aco_foreign_key' => $target[$targetType]['id'],
			'aro' => $whoType,
			'aro_foreign_key' => $who[$whoType]['id'],
			'type' => $type
		));
		$this->Permission->create();
		$permission = $this->Permission->save($data);
		
		return !is_null($permission);
	}
	
}

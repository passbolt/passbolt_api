<?php
/**
 * Permission Benchmark Test
 *
 * @copyright     Copyright 2012, Passbolt.com
 * @package       app.Test.Case.Model.PermissionBenchmarkTest
 * @since         version 2.12.7
 * @license       http://www.passbolt.com/license
 */
App::uses('Permission', 'Model');
App::uses('PermissionType', 'Model');

class UserResourcePermissionTest extends CakeTestCase {

	private $config = array(
		'users' => 10,
	);
	
	public $fixtures = array('app.resource', 'app.category', 'app.userPermission', 'app.role', 'app.group', 'app.groupsUser', 'app.categoryType', 'app.categoriesResource', 'app.permissionsType', 'app.permission', 'app.dummyResource');

	public function setUp() {
		parent::setUp();
		$this->Permission = ClassRegistry::init('Permission');
		$this->User = ClassRegistry::init('User');
		$this->Role = ClassRegistry::init('Role');
		$this->Category = ClassRegistry::init('Category');
		$this->Resource = ClassRegistry::init('Resource');
		$this->CategoryResource = ClassRegistry::init('CategoryResource');
		$this->Group = ClassRegistry::init('Group');
		$this->PermissionType = ClassRegistry::init('PermissionType');
		$this->PermissionCache = ClassRegistry::init('PermissionCache');
		$this->UserCategoryPermission = ClassRegistry::init('UserCategoryPermission');
		$this->UserResourcePermission = ClassRegistry::init('UserResourcePermission');
	}

	public function testUserResourcePermission() {
		$users = $this->User->find('all', array('order'=>'RAND()', 'limit'=>$this->config['users']));
		foreach($users as $user) {
			$this->Resource->bindModel(array(
				'hasOne' => array(
					'UserResourcePermission' => array(
						"foreignKey" => "resource_id",
						"conditions" => array( "user_id" => $user['User']['id'])
					),
					'Permission' => array(
						'foreignKey' => false,
						'conditions' => array( ' `Permission`.`id` = `UserResourcePermission`.`permission_id` ' ),
						'type' => 'LEFT'
					)
				)
			));
			$this->Resource->find('all', array("contain" => array("UserResourcePermission", "Permission")));
		}
	}

}


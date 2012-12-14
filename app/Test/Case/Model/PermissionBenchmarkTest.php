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

class PermissionBenchmarkTest extends CakeTestCase {

	public $fixtures = array('app.resource', 'app.userPermission', 'app.role', 'app.group', 'app.groupUser', 'app.categoryType', 'app.dummyCategory', 'app.categoryResource', 'app.permissionType', 'app.permission');

	public function setUp() {
		parent::setUp();
		$this->Permission = ClassRegistry::init('Permission');
		$this->User = ClassRegistry::init('User');
		$this->Role = ClassRegistry::init('Role');
		$this->Category = ClassRegistry::init('Category');
		$this->Resource = ClassRegistry::init('Resource');
		$this->Group = ClassRegistry::init('Group');
		$this->PermissionType = ClassRegistry::init('PermissionType');
		$this->PermissionCache = ClassRegistry::init('PermissionCache');
		$this->UserCategoryPermission = ClassRegistry::init('UserCategoryPermission');
	}

	public function microtimeFloat() {
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}

	public function testCompareViewsExecution() {
		$user = $this->User->findByUsername('remy.bertot@test.com');

//		$this->Category->bindModel(array('hasOne' => array(
//			'PermissionCache'
//		)));
//
//		$timeStart = $this->microtimeFloat();
//		$cats  = $this->Category->find('all', array("order" => "Category.lft ASC", "contain" => array(
//			"PermissionCache" => array(
//				"foreignKey" => "aco_foreign_key",
//				"conditions" => array( "aco" => "Category", "aro" => "User", "aro_foreign_key" => $user['User']['id'])
//			)
//		)));
//
//		$timeEnd = $this->microtimeFloat();
//		$time1 = $timeEnd - $timeStart;

		$this->Category->bindModel(array(
			'hasOne' => array(
				'UserCategoryPermission' => array(
					"foreignKey" => "category_id",
					"conditions" => array( "user_id" => $user['User']['id'])
				),
				'Permission' => array(
					'foreignKey' => false,
					'conditions' => array( ' `Permission`.`id` = `UserCategoryPermission`.`permission_id` ' ),
					'type' => 'LEFT'
				)
			),
		));

		$timeStart = $this->microtimeFloat();

		$cats  = $this->Category->find('all', array("order" => "Category.lft ASC", "contain" => array("UserCategoryPermission", "Permission")));
		$timeEnd = $this->microtimeFloat();
//		$time2 = $timeEnd - $timeStart;
//
//		$this->Category->bindModel(array(
//			'hasOne' => array(
//				'UserCategoryPermission' => array(
//					"foreignKey" => "category_id",
//					"conditions" => array( "user_id" => $user['User']['id'])
//				),
//				'Permission' => array(
//					'foreignKey' => false,
//					'conditions' => array( ' `Permission`.`id` = `UserCategoryPermission`.`permission_id` ' ),
//					'type' => 'LEFT'
//				)
//			),
//		));
//
//		$timeStart = $this->microtimeFloat();
//
//		$cats  = $this->Category->find('all', array("order" => "Category.lft ASC", "contain" => array("UserCategoryPermission", "Permission")));
		
		// Stupid test. We should find a way to display more relevant information in the test
		//$this->assertTrue($time2 < $time1, "The first test should have taken longer than the second one ($time2 < $time1)");
	}
}


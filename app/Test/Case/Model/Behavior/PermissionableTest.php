<?php
/**
 * Permissionable Behavior Test
 *
 * @copyright    Copyright 2014, Passbolt.com
 * @package      app.Test.Case.Model.PermossionableTest
 * @since        version 2.14.6
 * @license      http://www.passbolt.com/license
 */
App::uses('Category', 'Model');
App::uses('User', 'Model');

class PermissionnableTest extends CakeTestCase {

	public $autoFixtures = true;

	public $fixtures = array('app.resource', 'app.user', 'app.role', 'app.group', 'app.groupsUser', 'app.categoryType', 'app.category', 'app.categoriesResource', 'app.permissionsType', 'app.permission', 'app.permission_view');

	public function setUp() {
		parent::setUp();
		$this->Category = ClassRegistry::init('Category');
		$this->Resource = ClassRegistry::init('Resource');
		$this->Group = ClassRegistry::init('Group');
		$this->User = ClassRegistry::init('User');
	}

	public function testGetPermission() {
		// log the user as a manager to be able to access all the db.
		$adminUser = $this->User->findByUsername('dark.vador@passbolt.com');
		$this->User->setActive($adminUser);

		$permissionsMatrix = require (dirname(__FILE__) . DS . '../../../Data/permissionsMatrix.php');
		foreach ($permissionsMatrix as $aroType => $aroTestcases) {
			foreach ($aroTestcases as $acoType => $testcases) {
				foreach ($testcases as $testcase) {
					$aroFindFunc = 'findByName';
					$acoFindFunc = 'findByName';
					if ($aroType == 'User') {
						$aroFindFunc = 'findByUsername';
					}
					$aroInstance = $this->$aroType->$aroFindFunc($testcase['aroname']);
					$acoInstance = $this->$acoType->$acoFindFunc($testcase['aconame']);

					// Get the permission.
					$permission = $this->$acoType->getPermission($acoInstance[$acoType]['id'], $aroInstance[$aroType]['id'], $aroType);
					$permission = $permission ? $permission['Permission']['type'] : null;
					$this->assertTrue($testcase['result'] == $permission,
						"permissions for {$acoType} {$testcase['aconame']} and category {$aroType} {$testcase['aroname']} returned {$permission} but should have returned {$testcase['result']}"
					);
				}
			}
		}
	}

	public function testIsAuthorized() {
		// log the user as a manager to be able to access all the db.
		$adminUser = $this->User->findByUsername('dark.vador@passbolt.com');
		$this->User->setActive($adminUser);

		$permissionsMatrix = require (dirname(__FILE__) . DS . '../../../Data/permissionsMatrix.php');
		foreach ($permissionsMatrix as $aroType => $aroTestcases) {
			foreach ($aroTestcases as $acoType => $testcases) {
				foreach ($testcases as $testcase) {
					$aroFindFunc = 'findByName';
					$acoFindFunc = 'findByName';
					if ($aroType == 'User') {
						$aroFindFunc = 'findByUsername';
					}
					$aroInstance = $this->$aroType->$aroFindFunc($testcase['aroname']);
					$acoInstance = $this->$acoType->$acoFindFunc($testcase['aconame']);

					// Check the user authorization.
					foreach (PermissionType::getAll() as $permissionName => $permissionType) {
						$isAuthorized = $this->$acoType->isAuthorized($acoInstance[$acoType]['id'], $permissionType, $aroInstance[$aroType]['id'], $aroType);
						$expect = $testcase['result'] >= $permissionType;
						$not = $expect ? '' : 'not';
						$this->assertTrue($expect == $isAuthorized,
							"{$aroType} {$testcase['aroname']} should {$not} be authorized to {$permissionName} {$acoType} {$testcase['aconame']}"
						);
					}
				}
			}
		}
	}
}

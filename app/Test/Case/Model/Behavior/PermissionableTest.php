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
App::uses('Resource', 'Model');
App::uses('Category', 'Model');
App::uses('PermissionType', 'Model');

class PermissionnableTest extends CakeTestCase {

	//public $autoFixtures = true;

	public $fixtures = array(
		'app.resource',
		'app.user',
		'app.role',
		'app.profile',
		'app.gpgkey',
		'app.file_storage',
		'app.group',
		'app.groups_user',
		'app.category_type',
		'app.category',
		'app.categories_resource',
		'app.permissions_type',
		'app.permission',
		'app.permission_view',
		'core.cakeSession'
	);

	public function setUp() {
		parent::setUp();
		$this->User = ClassRegistry::init('User');
		$this->Group = ClassRegistry::init('Group');
		$this->Category = ClassRegistry::init('Category');
		$this->Resource = ClassRegistry::init('Resource');
	}

	public function testGetPermission() {

		// log the user as a manager to be able to access all the db.
		$adminUser = $this->User->findByUsername('admin@passbolt.com');
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
					$aroInstance = $this->{$aroType}->{$aroFindFunc}($testcase['aroname']);
					$acoInstance = $this->{$acoType}->{$acoFindFunc}($testcase['aconame']);

					if(empty($aroInstance)) {
						$this->assertTrue(false, 'Aro:' . $testcase['aroname'] . ' (type:' . $aroType .') could not be found.');
					}
					else if(empty($acoInstance)) {
						var_dump($acoType, $acoFindFunc, $testcase['aconame']);
						var_dump($testcase);
						var_dump($acoInstance);
						die();
						$this->assertTrue(false, 'Aro:' . $testcase['aconame'] . ' (type:' . $acoType .') could not be found.');
					} else {
						// Get the permission.
						$permission = $this->{$acoType}->getPermission(
							$acoInstance[$acoType]['id'],
							$aroInstance[$aroType]['id'], $aroType
						);
						$permission = $permission ? $permission['Permission']['type'] : null;
						$this->assertTrue($testcase['result'] == $permission,
							"permissions for {$acoType} {$testcase['aconame']} and category {$aroType} {$testcase['aroname']} returned {$permission} but should have returned {$testcase['result']}"
						);
					}
				}
			}
		}
	}

	public function testIsAuthorized() {
		// log the user as a manager to be able to access all the db.
		$adminUser = $this->User->findByUsername('dame@passbolt.com');
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

					if(empty($aroInstance)) {
						$this->assertTrue(false, 'Aro:' . $testcase['aroname'] . ' (type:' . $aroType .') could not be found.');
					}
					else if(empty($acoInstance)) {
						$this->assertTrue(false, 'Aro:' . $testcase['aconame'] . ' (type:' . $acoType .') could not be found.');
					} else {
						// Check the user authorization.
						foreach (PermissionType::getAll() as $permissionName => $permissionType) {
							$isAuthorized = $this->$acoType->isAuthorized(
								$acoInstance[$acoType]['id'], $permissionType,
								$aroInstance[$aroType]['id'], $aroType
							);
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
}

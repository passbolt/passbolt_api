<?php
/**
 * GroupUser Model Test
 *
 * @copyright     Copyright 2012, Passbolt.com
 * @package       app.Test.Case.Model.GroupUserTest
 * @since         version 2.12.7
 * @license       http://www.passbolt.com/license
 */
App::uses('GroupUser', 'Model');
App::uses('User', 'Model');

if (!class_exists('CakeSession')) {
	require CAKE . 'Model/Datasource/CakeSession.php';
}

class GroupUserTest extends CakeTestCase {

	public $fixtures = array(
		'app.category',
		'app.resource',
		'app.categories_resource',
		'app.user',
		'app.role',
		'app.group',
		'app.groups_user',
		'app.gpgkey',
		'core.cakeSession'
	);

	public function setUp() {
		parent::setUp();
		$this->GroupUser = ClassRegistry::init('GroupUser');
		$this->GroupUser->useDb = 'test';
	}

	/**
	 * Test GroupId Validation
	 * @return void
	 */
	public function testGroupIdValidation() {
		$testcases = array(
			'' => false,
			'?!#' => false,
			'test' => false,
			'4ff6111b-efb8-4a26-aab4-2184cbdd56c' => false,
			Common::uuid('group.id.human') => true
		);

		foreach ($testcases as $testcase => $result) {
			$cr = array(
				'GroupUser' => array(
					'group_id' => $testcase,
					'user_id' => Common::uuid('user.id.carol') // user_id is passed here because when we don't pass it test fails for obscure reasons
				)
			);
			$this->GroupUser->create();
			$this->GroupUser->set($cr);
			if ($result) {
				$msg = 'validation of the group_user "group id" with "' . $testcase . '" should validate';
			} else {
				$msg = 'validation of the group_user "group id" with "' . $testcase . '" should not validate';
			}
			$validation = $this->GroupUser->validates(array('fieldList' => array('group_id')));
			if (!$validation) {
				$msg .= print_r($this->GroupUser->validationErrors, true);
			}
			$this->assertEquals($validation, $result, "$msg");
		}
	}

	/**
	 * Test UserId Validation
	 * @return void
	 */
	public function testUserIdValidation() {
		$testcases = array(
			'' => false,
			'?!#' => false,
			'test' => false,
			'4ff6111b-efb8-4a26-aab4-2184cbdd56c' => false,
			Common::uuid('user.id.carol') => true
		);
		foreach ($testcases as $testcase => $result) {
			$cr = array(
				'GroupUser' => array(
					'user_id' => $testcase,
					'group_id' => Common::uuid('group.id.human')
				)
			);
			$this->GroupUser->create();
			$this->GroupUser->set($cr);
			if ($result) {
				$msg = 'validation of the group_user "user id" with "' . $testcase . '" should validate';
			} else {
				$msg = 'validation of the group_user "user id" with "' . $testcase . '" should not validate';
			}
			$validation = $this->GroupUser->validates(array('fieldList' => array('user_id')));
			if (!$validation) {
				$msg .= print_r($this->GroupUser->validationErrors, true);
			}
			$this->assertEquals($validation, $result, $msg);
		}
	}

	/**
	 * Test Duplicates
	 * @return void
	 */
	public function testDuplicatesValidation() {
		$cr = $this->GroupUser->findById('1');
		$cr['GroupUser']['id'] = '';
		// test duplicates
		$this->GroupUser->create();
		$this->GroupUser->set($cr);
		$validation = $this->GroupUser->validates(array('fieldList' => array('group_id', 'user_id')));
		$this->assertEquals($validation, false, print_r($this->GroupUser->validationErrors, true));
	}

}

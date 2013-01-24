<?php
/**
 * ResourceTag Model Test
 *
 * @copyright     Copyright 2012, Passbolt.com
 * @package       app.Test.Case.Model.ResourceTagTest
 * @since         version 2.12.11
 * @license       http://www.passbolt.com/license
 */
App::uses('Tag', 'Model');
App::uses('Resource', 'Model');
App::uses('ResourceTag', 'Model');
App::uses('User', 'Model');

class ResourceTagTest extends CakeTestCase {

	public $fixtures = array('app.tag', 'app.resource', 'app.resourcesTag', 'app.user', 'app.role');

	public function setUp() {
		parent::setUp();
		$this->ResourceTag = ClassRegistry::init('ResourceTag');
		$this->ResourceTag->useDb = 'test';
		$this->ResourceTag->Resource->Behaviors->disable('Permissionable');
	}

/**
 * Test Unicity Validation
 * @return void
 */
	public function testUnicityValidation() {
		$tr = array(
			'ResourceTag' => array(
				'tag_id' => 'bbb00001-c5cd-11e1-a0c5-080027796c4c',
				'resource_id' => 'aaa00003-c5cd-11e1-a0c5-080027796c4c'
			)
		);
		$this->ResourceTag->create();
		$this->ResourceTag->set($tr);
		$validation = $this->ResourceTag->validates(array('fieldList' => array('tag_id', 'resource_id')));
		$this->assertEqual($validation, false, 'It should not be possible to associate a resource and a tag twice');

		$validation = $this->ResourceTag->uniqueCombi();
		$this->assertEqual($validation, false, 'It should not be possible to associate a resource and a tag twice');
	}

/**
 * Test Tag Exist Function
 * @return void
 */
	public function testTagExist() {
		$result = $this->ResourceTag->tagExists(null);
		$this->assertEqual($result, false, 'Tag null should not be found');
		$result = $this->ResourceTag->tagExists(array('tag_id' => 'fff00001-c5cd-11e1-a0c5-080027796c4c'));
		$this->assertEqual($result, false, 'Not existing tag should not be found');
		$result = $this->ResourceTag->tagExists(array('tag_id' => 'aaa00001-c5cd-11e1-a0c5-080027796c4c'));
		$this->assertEqual($result, true, 'Facebook tag should be found');
	}

/**
 * Test Resource Exist Function
 * @return void
 */
	public function testResourceExist() {
		$result = $this->ResourceTag->resourceExists(null);
		$this->assertEqual($result, false, 'Empty ressource should not be found');
		$result = $this->ResourceTag->resourceExists(array('resource_id' => 'fff00001-c5cd-11e1-a0c5-080027796c4c'));
		$this->assertEqual($result, false, 'Not existing resource should not be found');
		$result = $this->ResourceTag->resourceExists(array('resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d'));
		$this->assertEqual($result, true, 'Facebook password should be found');
	}

/**
 * Test TagId Validation
 * @return void
 */
	public function testIdValidation() {
		$testcases = array(
			'' => true, '?!#' => false, 'test' => false,
			'aaa00003-c5cd-11e1-a0c5-080027z!6c4c' => false,
			'zzz00003-c5cd-11e1-a0c5-080027796c4c' => false,
			'aaa00003-c5cd-11e1-a0c5-080027796c4c' => true,
		);
		foreach ($testcases as $testcase => $result) {
			$user = array('ResourceTag' => array('id' => $testcase));
			$this->ResourceTag->set($user);
			if($result) $msg = 'validation of the id with ' . $testcase . ' should validate';
			else $msg = 'validation of the id with ' . $testcase . ' should not validate';
			$this->assertEqual($this->ResourceTag->validates(array('fieldList' => array('id'))), $result, $msg);
		}
	}

/**
 * Test TagId Validation
 * @return void
 */
	public function testTagIdValidation() {
		$testcases = array(
			'' => false, '?!#' => false, 'test' => false,
			'aaa00003-c5cd-11e1-a0c5-080027z!6c4c' => false,
			'zzz00003-c5cd-11e1-a0c5-080027796c4c' => false,
			'aaa00003-c5cd-11e1-a0c5-080027796c4c' => true,
		);
		foreach ($testcases as $testcase => $result) {
			$user = array('ResourceTag' => array('tag_id' => $testcase));
			$this->ResourceTag->set($user);
			if($result) $msg = 'validation of the tag_id with ' . $testcase . ' should validate';
			else $msg = 'validation of the tag_id with ' . $testcase . ' should not validate';
			$this->assertEqual($this->ResourceTag->validates(array('fieldList' => array('tag_id'))), $result, $msg);
		}
	}

/**
 * Test ResourceId Validation
 * @return void
 */
	public function testResourceIdValidation() {
		$testcases = array(
			'' => false, '?!#' => false, 'test' => false,
			'aaa00003-c5cd-11e1-a0c5-080027z!6c4c' => false,
			'zzz00003-c5cd-11e1-a0c5-080027796c4c' => false,
			'509bb871-5168-49d4-a676-fb098cebc04d' => true,
		);
		// we test unicity separately
		unset($this->ResourceTag->validate['resource_id']['uniqueCombi']);
		foreach ($testcases as $testcase => $result) {
			$user = array('ResourceTag' => array('resource_id' => $testcase));
			$this->ResourceTag->set($user);
			if($result) $msg = 'validation of the resource_id with ' . $testcase . ' should validate';
			else $msg = 'validation of the resource_id with ' . $testcase . ' should not validate';
			$this->assertEqual($this->ResourceTag->validates(array('fieldList' => array('resource_id'))), $result, $msg);
		}
	}

}

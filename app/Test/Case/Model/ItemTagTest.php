<?php
/**
 * ItemTag Model Test
 *
 * @copyright     Copyright 2012, Passbolt.com
 * @package       app.Test.Case.Model.ItemTagTest
 * @since         version 2.12.11
 * @license       http://www.passbolt.com/license
 */
App::uses('Tag', 'Model');
App::uses('Resource', 'Model');
App::uses('ItemTag', 'Model');
App::uses('User', 'Model');

class ItemTagTest extends CakeTestCase {

	public $fixtures = array(
		'app.tag',
		'app.resource',
		'app.itemsTag',
		'app.groupsUser',
		'app.group',
		'app.user',
		'app.profile',
		'app.role',
		'app.file_storage',
		'app.gpgkey',
		'core.cakeSession'
	);

	public function setUp() {
		parent::setUp();
		$this->ItemTag = ClassRegistry::init('ItemTag');
		$this->ItemTag->useDb = 'test';
		$this->ItemTag->Resource->Behaviors->disable('Permissionable');
	}

/**
 * Test Unicity Validation
 * @return void
 */
	public function testUnicityValidation() {
		$tr = array(
			'ItemTag' => array(
				'tag_id' => 'aaa00003-c5cd-11e1-a0c5-080027796c4c',
				'foreign_model' => 'Resource',
				'foreign_id' => Common::uuid('resource.id.utest1-pwd1')
			)
		);
		$this->ItemTag->create();
		$this->ItemTag->set($tr);
		$save = $this->ItemTag->save($tr);
		$validation = $this->ItemTag->validates(array('fieldList' => array('tag_id', 'foreign_model', 'foreign_id')));
		$this->assertEquals($validation, false, 'It should not be possible to associate a resource and a tag twice');

		$this->ItemTag->set($tr);
		$validation = $this->ItemTag->uniqueCombi(array('foreign_id' => Common::uuid('resource.id.utest1-pwd1')));
		$this->assertEquals($validation, false, 'It should not be possible to associate a resource and a tag twice');
	}

/**
 * Test Tag Exist Function
 * @return void
 */
	public function testTagExist() {
		$result = $this->ItemTag->tagExists(null);
		$this->assertEquals($result, false, 'Tag null should not be found');
		$result = $this->ItemTag->tagExists(array('tag_id' => 'fff00001-c5cd-11e1-a0c5-080027796c4c'));
		$this->assertEquals($result, false, 'Not existing tag should not be found');
		$result = $this->ItemTag->tagExists(array('tag_id' => 'aaa00001-c5cd-11e1-a0c5-080027796c4c'));
		$this->assertEquals($result, true, 'Facebook tag should be found');
	}

/**
 * Test Resource Exist Function
 * @return void
 */
	public function testResourceExist() {
		$this->ItemTag->data['ItemTag']['foreign_model'] = 'Resource';
		$result = $this->ItemTag->itemExists(array('foreign_model' => 'Resource','foreign_id' => ''));
		$this->assertEquals($result, false, 'Empty ressource should not be found');
		$result = $this->ItemTag->itemExists(array('foreign_model' => 'Resource','foreign_id' => 'fff00001-c5cd-11e1-a0c5-080027796c4c'));
		$this->assertEquals($result, false, 'Not existing resource should not be found');
		$result = $this->ItemTag->itemExists(array('foreign_model' => 'Resource', 'foreign_id' => Common::uuid('resource.id.facebook-account')));
		$this->assertEquals($result, true, 'Facebook password should be found');
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
			$itemTag = array('ItemTag' => array('foreign_model' => 'Resource', 'id' => $testcase));
			$this->ItemTag->set($itemTag);
			if($result) $msg = 'validation of the id with ' . $testcase . ' should validate';
			else $msg = 'validation of the id with ' . $testcase . ' should not validate';
			$this->assertEquals($this->ItemTag->validates(array('fieldList' => array('id'))), $result, $msg);
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
			$itemTag = array('ItemTag' => array('foreign_model' => 'Resource', 'tag_id' => $testcase));
			$this->ItemTag->set($itemTag);
			if($result) $msg = 'validation of the tag_id with ' . $testcase . ' should validate';
			else $msg = 'validation of the tag_id with ' . $testcase . ' should not validate';
			$this->assertEquals($this->ItemTag->validates(array('fieldList' => array('tag_id'))), $result, $msg);
		}
	}

/**
 * Test ResourceId Validation
 * @return void
 */
	public function testForeignIdValidation() {
		$testcases = array(
			'' => false, '?!#' => false, 'test' => false,
			'aaa00003-c5cd-11e1-a0c5-080027z!6c4c' => false,
			'zzz00003-c5cd-11e1-a0c5-080027796c4c' => false,
			Common::uuid('resource.id.facebook-account') => true,
		);
		// we test unicity separately
		unset($this->ItemTag->validate['foreign_id']['uniqueCombi']);
		foreach ($testcases as $testcase => $result) {
			$itemTag = array('ItemTag' => array('foreign_model' => 'Resource', 'foreign_id' => $testcase));
			$this->ItemTag->set($itemTag);
			if($result) $msg = 'validation of the resource_id with ' . $testcase . ' should validate';
			else $msg = 'validation of the resource_id with ' . $testcase . ' should not validate';
			$this->assertEquals($this->ItemTag->validates(array('fieldList' => array('foreign_id'))), $result, $msg);
		}
	}

}

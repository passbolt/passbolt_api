<?php
/**
 * Cateogry Model Test
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @package      app.Test.Case.Model.CategoryTest
 * @since        version 2.12.7
 * @license      http://www.passbolt.com/license
 */
App::uses('Category', 'Model');
App::uses('User', 'Model');

class CategoryTest extends CakeTestCase {

	public $autoFixtures = true;

	public $fixtures = array('app.category', 'app.category_type', 'app.user', 'app.role');

	public function setUp() {
		parent::setUp();
		$this->Category = ClassRegistry::init('Category');
		$this->Category->Behaviors->disable('Permissionable');
	}

	public function testAdd() {
		// Test that a category cannot be added if the parent id doesn't exist
		$category = array('Category' => array('name' => 'testAdd', 'parent_id' => 'doesntexist'));
		$this->Category->hasOne = array();
		$this->Category->create();
		$this->assertFalse($this->Category->save($category));

		// Test that a category cannot be added if the name is not valid
		$category = array('Category' => array('name' => 'a'));
		$this->Category->create();
		$this->assertFalse($this->Category->save($category));

		// Test that a category cannot be added if the name is empty
		$category = array('Category' => array('name' => ''));
		$this->Category->create();
		$this->assertFalse($this->Category->save($category));

		// Test that a category is added properly if parameters are correct
		$parent = $this->Category->findByName('cakephp');
		$category = array('Category' => array('name' => 'testAdd', 'parent_id' => $parent['Category']['id']));
		$this->Category->create();
		$result = $this->Category->save($category);
		$this->assertTrue($result['Category']['lft'] == '20');

		// Test that a category is added properly if parameters are correct and without parent_id
		$category = array('Category' => array('name' => 'testAdd1', 'parent_id' => null));
		$this->Category->create();
		$result = $this->Category->save($category);
		$this->assertTrue($result['Category']['lft'] == '37');
	}

	public function testGetPosition() {
		$uvbar = $this->Category->findByName('accounts');
		$position = $this->Category->getPosition($uvbar['Category']['id']);
		$this->assertEquals(1, $position);
		$this->assertFalse($this->Category->getPosition('badid'));
	}

	public function testGetFindConditions() {
		$f = $this->Category->getFindConditions('testoqwidoqdhwqohdowqhid');
		$this->assertEquals($f, array('conditions' => array()), 'testGetFindCondition should return an empty array');
	}

	public function testGetFindFields() {
		$f = $this->Category->getFindFields('testdqwodjqodqodwjqidqjdow');
		$this->assertEquals($f, array('fields' => array()), 'testGetFindFields return an empty array');
	}
}

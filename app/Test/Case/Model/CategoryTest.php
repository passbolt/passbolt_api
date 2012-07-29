<?php
/**
 * Cateogry Model Test
 *
 * @copyright     Copyright 2012, Passbolt.com
 * @package       app.Test.Case.Model.CategoryTest
 * @since         version 2.12.7
 * @license       http://www.passbolt.com/license
 */
App::uses('Category', 'Model');

class CategoryTest extends CakeTestCase {
	public $fixtures = array('app.category');

	public function setUp() {
		parent::setUp();
		$this->Category = ClassRegistry::init('Category');
	}

 /**
   * Test Name Validation
   * @return void
   */
	public function testNameValidation() {
	  $testcases = array(
	    '' => false, '?!#' => true, 'test' => true,
	    'test@test.com' => true, '1test' => true
	  );
	  foreach($testcases as $testcase => $result) {      
	    $category = array('Category' => array('name' => $testcase));
	    $this->Category->set($category);
	    if($result) $msg = 'validation of the category name with '.$testcase.' should validate';
	    else $msg = 'validation of the category name with '.$testcase.' should not validate';
	    $this->assertEqual($this->Category->validates(array('fieldList' => array('name'))), $result, $msg);
	  }
	}
	
	/**
   * Test Parent Validation
   * @return void
   */
	public function testParentValidation() {
	  $testcases = array(
	    '' => true, 'wrongid' => false, '4ff6111b-efb8-4a26-aab4-2184cbdd56cb' => true,
	    '4ff6111b-efb8-4a26-aab4-2184cbdd56aa' => false
	  );
	  foreach($testcases as $testcase => $result) {      
	    $category = array('Category' => array('parent_id' => $testcase));
	    $this->Category->set($category);
	    if($result) $msg = 'validation of the category parent with '.$testcase.' should validate';
	    else $msg = 'validation of the category parent with '.$testcase.' should not validate';
	    $this->assertEqual($this->Category->validates(array('fieldList' => array('parent_id'))), $result, $msg);
	  }
	}

/**
   * Test Categiry type Validation
   * @return void
   */
	public function testCategoryTypeValidation() {
	  $testcases = array(
	    '' => true, 'wrongid' => false, '50152793-52b4-47aa-940d-1358b4e000c3' => true,
	    '4ff6111b-efb8-4a26-aab4-2184cbdd56aa' => false
	  );
	  foreach($testcases as $testcase => $result) {      
	    $category = array('Category' => array('category_type_id' => $testcase));
	    $this->Category->set($category);
	    if($result) $msg = 'validation of the category type with '.$testcase.' should validate';
	    else $msg = 'validation of the category type with '.$testcase.' should not validate';
	    $this->assertEqual($this->Category->validates(array('fieldList' => array('category_type_id'))), $result, $msg);
	  }
	}

	public function testParentExists() {
		// Test in a normal condition if the id is correct	
		$category = $this->Category->findByName('Anjuna');
		$result = $this->Category->parentExists($category['Category']);
		$this->assertEquals(true, $result);
		
		// test if id is null
		$category = $this->Category->findByName('Goa');
		$result = $this->Category->parentExists($category['Category']);
		$this->assertEquals(true, $result);
		
		// test if id doesn't exist
		$category['Category']['parent_id']='keydoesntexist';
		$result = $this->Category->parentExists($category['Category']);
		$this->assertEquals(false, $result);
	}
	
	public function testIsChild(){
		// assert true
		$parent = $this->Category->findByName('Hippies places');
		$child = $this->Category->findByName('Anjuna');
		
		$this->assertEquals(true, $this->Category->isChild($child, $parent));
		
		// assert false
		$child = $this->Category->findByName('Baga');
		$this->assertEquals(false, $this->Category->isChild($child, $parent));
	}
	
	public function testIsLeaf(){
		// assert false
		$leaf = $this->Category->findByName('Anjuna');
		$this->assertEquals(false, $this->Category->isLeaf($leaf));
		
		// assert true
		$leaf = $this->Category->findByName('Play pool table');
		$this->assertEquals(true, $this->Category->isLeaf($leaf));
	}
	
	public function testIsTopLevelElement(){
		$elt = $this->Category->findByName('Anjuna');
		$children = $this->Category->children($elt['Category']['id']);
		$tree = array_merge(array(0=>$elt), $children);
		$this->assertEquals(true, $this->Category->isTopLevelElement($elt, $tree));
		
		$elt = $this->Category->findByName('Curlie\'s');
		$this->assertEquals(false, $this->Category->isTopLevelElement($elt, $tree));
	}
	
	public function testResults2Tree(){
		// Test in normal condition if the tree is returned properly
		// 1) with only one root
		$category = $this->Category->findByName('Anjuna');
		$children = $this->Category->children($category['Category']['id']);
	$fields = array('fields'=>array('fields'=>array('Category.id', 'Category.parent_id', 'Category.name')));
		$tree = array_merge(array(0=>$category), $children);
		$tree = $this->Category->results2Tree($tree, $fields);
		$expected = array(
			'0' => array(
				 'Category' => array(
				'id' => '4ff6111c-8534-4d17-869c-2184cbdd56cb',
				'parent_id' => '4ff6111b-9090-44d2-ba5a-2184cbdd56cb',
				'name' => 'Anjuna',
				'children' => array(
					'0' => array(
						 'Category' => array(
							'id' => '4ff6111c-dac0-4b39-81b7-2184cbdd56cb',
							'parent_id' => '4ff6111c-8534-4d17-869c-2184cbdd56cb',
							'name' => 'UV Bar',
							'children' => array()
							)
						),
					'1' => Array(
						  'Category'=> array(
							'id' => '4ff6111c-4ea0-4232-ae8d-2184cbdd56cb',
							'parent_id' => '4ff6111c-8534-4d17-869c-2184cbdd56cb',
							'name' => 'Curlie\'s',
							'children' => array(
									'0' => array(
										'Category' => array (
											'id' => '4ff6111e-c81c-43cc-b848-2184cbdd56cb',
											'parent_id' => '4ff6111c-4ea0-4232-ae8d-2184cbdd56cb',
											'name' => 'Dance on the beach',
											'children' => array()
											)
										),
									'1' => array (
										  'Category' => array (
											'id' => '4ff6111e-47c8-45f3-8f5c-2184cbdd56cb',
											'parent_id' => '4ff6111c-4ea0-4232-ae8d-2184cbdd56cb',
											'name' => 'Play pool table',
											'children' => array()
											)
										)
								)
							)
						),
					'2' => Array(
						  'Category' => array (
							'id' => '4ff6111d-9e6c-4d71-80ee-2184cbdd56cb',
							'parent_id' => '4ff6111c-8534-4d17-869c-2184cbdd56cb',
							'name' => 'The Hippies',
							'children' => Array()
							)
						)
					)
				)
		 )
			);
		$this->assertEquals($expected, $tree);
		
		//2) Test with several root
		$treechildren = $this->Category->results2Tree($children, $fields);
		$expected = $expected['0']['Category']['children'];
		$this->assertEquals($expected, $treechildren);
		
		//3) test if empty parameter is given
		$treechildren = $this->Category->results2Tree(array());
		$this->assertEquals(array(), $treechildren);
	}

	public function testAdd(){
		// Test that a category cannot be added if the parent id doesn't exist
		$category = array('Category'=>array('name'=>'testAdd', 'parent_id'=>'doesntexist'));
		$this->Category->create(); 
		$this->assertFalse($this->Category->save($category)); 
		
		// Test that a category cannot be added if the name is not valid
		$category = array('Category'=>array('name'=>'a'));
		$this->Category->create(); 
		$this->assertFalse($this->Category->save($category));
		
		// Test that a category cannot be added if the name is empty
		$category = array('Category'=>array('name'=>''));
		$this->Category->create(); 
		$this->assertFalse($this->Category->save($category));  
		
		// Test that a category is added properly if parameters are correct
		$parent = $this->Category->findByName('Anjuna');
		$category = array('Category'=>array('name'=>'testAdd', 'parent_id'=>$parent['Category']['id']));
		$this->Category->create(); 
		$result = $this->Category->save($category);
		$this->assertTrue($result['Category']['lft'] == '14');  
		
		// Test that a category is added properly if parameters are correct and without parent_id
		$category = array('Category'=>array('name'=>'testAdd1'));
		$this->Category->create(); 
		$result = $this->Category->save($category);
		$this->assertTrue($result['Category']['lft'] == '31');  
	}

	public function testGetPosition(){
		$uvbar = $this->Category->findByName('Curlie\'s');
		$position = $this->Category->getPosition($uvbar['Category']['id']);
		$this->assertEquals(2, $position);
		
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

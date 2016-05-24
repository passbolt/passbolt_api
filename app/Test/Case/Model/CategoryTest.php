<?php
/**
 * Category Model Test
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @package      app.Test.Case.Model.CategoryTest
 * @since        version 2.12.7
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('Category', 'Model');
App::uses('User', 'Model');
App::uses('AppTestCase', 'Test');

if (!class_exists('CakeSession')) {
	require CAKE . 'Model/Datasource/CakeSession.php';
}

class CategoryTest extends AppTestCase {

	public $autoFixtures = true;

	public $fixtures = array(
		'app.category',
		'app.category_type',
		'app.user',
		'app.role',
		'app.profile',
		'app.gpgkey',
		'app.file_storage',
		'core.cakeSession'
	);

	public function setUp() {
		parent::setUp();
		$this->Category = ClassRegistry::init('Category');
		$this->Category->Behaviors->disable('Permissionable');
	}

/**
 * Test Name Validation
 *
 * @return void
 */
	public function testNameValidation() {
		$len = 64;
		$testcases = array(
			// Not empty
			'' => false,
			// Email
			'test@test.com' => false,
			// too short
			'sh' => false,
			// too long
			'toolong' . self::randString($len - 6, self::getMask('alphaASCII')) => false,
			// Short but enough
			'sho' => true,
			// Long but not too long
			'long' . self::randString($len - 4, self::getMask('alphaASCII')) => true,
			// Languages
			'ASCII' . self::randString($len - 5, self::getMask('alphaASCII')) => true,
			'ASCIIUPPER' . self::randString($len - 10, self::getMask('alphaASCIIUpper')) => true,
			'ACCENT' . self::randString($len - 6, self::getMask('alphaAccent')) => true,
			'LATIN' . self::randString($len - 5, self::getMask('alphaLatin')) => true,
			'CHINESE' . self::randString($len - 7, self::getMask('alphaChinese')) => true,
			'ARABIC' . self::randString($len - 6, self::getMask('alphaArabic')) => true,
			'RUSSIAN' . self::randString($len - 7, self::getMask('alphaRussian')) => true,
			// Spaces
			'txt with spaces' => true,
			"txt\twith\ttabs" => false,
			"txt\nwith\nnew\nlines" => false,
			// Special characters
			',.-_([)]\'' => true,
			'?!#' => false,
			// Digit accepted
			'0123456789' => true,
			// Html
			'<strong>test</strong>' => false,
		);
		foreach ($testcases as $testcase => $result) {
			$category = array('Category' => array('name' => $testcase));
			$this->Category->set($category);
			if ($result) {
				$msg = 'validation of the category name with ' . $testcase . ' should validate';
			} else {
				$msg = 'validation of the category name with ' . $testcase . ' should not validate';
			}
			$this->assertEquals($this->Category->validates(array('fieldList' => array('name'))), $result, $msg);
		}
	}

/**
 * Test Parent Validation
 *
 * @return void
 */
	public function testParentValidation() {
		$testcases = array(
			'' => true,
			'wrongid' => false,
			Common::uuid('category.id.administration') => true,
			'4ff6111b-efb8-4a26-aab4-2184cbdd56aa' => false
		);
		foreach ($testcases as $testcase => $result) {
			$category = array('Category' => array('parent_id' => $testcase));
			$this->Category->set($category);
			if ($result) {
				$msg = 'validation of the category parent with ' . $testcase . ' should validate';
			} else {
				$msg = 'validation of the category parent with ' . $testcase . ' should not validate';
			}
			$this->assertEquals($this->Category->validates(array('fieldList' => array('parent_id'))), $result, $msg);
		}
	}

/**
 * Test Category type Validation
 *
 * @return void
 */
	public function testCategoryTypeValidation() {
		$testcases = array(
			'' => true,
			'wrongid' => false,
			Common::uuid('category_type.id.ssh') => true,
			'4ff6111b-efb8-4a26-aab4-2184cbdd56aa' => false
		);
		foreach ($testcases as $testcase => $result) {
			$category = array('Category' => array('category_type_id' => $testcase));
			$this->Category->set($category);
			if ($result) {
				$msg = 'validation of the category type with ' . $testcase . ' should validate';
			} else {
				$msg = 'validation of the category type with ' . $testcase . ' should not validate';
			}
			$this->assertEquals($this->Category->validates(array('fieldList' => array('category_type_id'))), $result, $msg);
		}
	}

	public function testParentExists() {
		// Test in a normal condition if the id is correct
		$category = $this->Category->findById(Common::uuid('category.id.cakephp'));
		$result = $this->Category->parentExists($category['Category']);
		$this->assertEquals(true, $result);

		// test if id is null
		$result = $this->Category->parentExists(array('parent_id' => ''));
		$this->assertEquals(true, $result);

		// test if id doesn't exist
		$category['Category']['parent_id'] = 'keydoesntexist';
		$result = $this->Category->parentExists($category['Category']);
		$this->assertEquals(false, $result);
	}

	public function testIsChild() {
		// assert true
		$parent = $this->Category->findById(Common::uuid('category.id.administration'));
		$child = $this->Category->findById(Common::uuid('category.id.accounts'));

		$this->assertEquals(true, $this->Category->isChild($child, $parent));

		// assert false
		$child = $this->Category->findById(Common::uuid('category.id.cakephp'));
		$this->assertEquals(false, $this->Category->isChild($child, $parent));
	}

	public function testIsLeaf() {
		// assert false
		$leaf = $this->Category->findById(Common::uuid('category.id.administration'));
		$this->assertEquals(false, $this->Category->isLeaf($leaf));

		// assert true
		$leaf = $this->Category->findById(Common::uuid('category.id.accounts'));
		$this->assertEquals(true, $this->Category->isLeaf($leaf));
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

		// // Test that a category is added properly if parameters are correct
		// $parent = $this->Category->findByName('cakephp');
		// $category = array('Category' => array('name' => 'testAdd', 'parent_id' => $parent['Category']['id']));
		// $this->Category->create();
		// $result = $this->Category->save($category);
		// $this->assertTrue($result['Category']['lft'] == '20');

		// // Test that a category is added properly if parameters are correct and without parent_id
		// $category = array('Category' => array('name' => 'testAdd1', 'parent_id' => null));
		// $this->Category->create();
		// $result = $this->Category->save($category);
		// $this->assertTrue($result['Category']['lft'] == '37');
	}

	public function testGetPosition() {
		$uvbar = $this->Category->findById(Common::uuid('category.id.accounts'));
		$position = $this->Category->getPosition($uvbar['Category']['id']);
		$this->assertEquals(1, $position);
		$this->assertFalse($this->Category->getPosition('badid'));
	}

	public function testGetFindConditions() {
		$default = ['conditions' => []];
		$this->assertNotEquals($default, Category::getFindConditions('getWithChildren'), 'Find conditions missing for getWithChildren');
		$this->assertNotEquals($default, Category::getFindConditions('getChildren'), 'Find conditions missing for getChildren');
		$this->assertNotEquals($default, Category::getFindConditions('Resource.viewByCategory'), 'Find conditions missing for viewByCategory');
		$this->assertNotEquals($default, Category::getFindConditions('view'), 'Find conditions missing for view');
		$this->assertNotEquals($default, Category::getFindConditions('addResult'), 'Find conditions missing for addResult');
		$this->assertNotEquals($default, Category::getFindConditions('index'), 'Find conditions missing for index');

		$this->assertEquals($default,  Category::getFindConditions('rubish'), 'testGetFindCondition should return an empty array');
	}

	public function testGetFindFields() {
		$default = ['fields' => []];
		$this->assertNotEquals($default, Category::getFindFields('view'), 'Find fields missing for view');
		$this->assertNotEquals($default, Category::getFindFields('getChildren'), 'Find fields missing for getChildren');
		$this->assertNotEquals($default, Category::getFindFields('addResult'), 'Find fields missing for addResult');
		$this->assertNotEquals($default, Category::getFindFields('index'), 'Find fields missing for index');
		$this->assertNotEquals($default, Category::getFindFields('getWithChildren'), 'Find fields missing for getWithChildren');
		$this->assertNotEquals($default, Category::getFindFields('Resource.viewByCategory'), 'Find fields missing for viewByCategory');
		$this->assertNotEquals($default, Category::getFindFields('rename'), 'Find fields missing for rename');
		$this->assertNotEquals($default, Category::getFindFields('add'), 'Find fields missing for add');
		$this->assertNotEquals($default, Category::getFindFields('edit'), 'Find fields missing for edit');

		$this->assertEquals($default, Category::getFindFields('rubish'), 'Find fields should be empty for wrong find');
	}
}

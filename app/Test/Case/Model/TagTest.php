<?php
/**
 * Tag Model Test
 *
 * @copyright	 (c) 2015-present Bolt Softwares Pvt Ltd
 * @license	   http://www.passbolt.com/license
 * @package	   app.Test.Case.Model.TagTest
 * @since		   version 2.12.11
 */
App::uses('Tag', 'Model');
App::uses('AppTestCase', 'Test');

class TagTest extends AppTestCase {

	public $fixtures = array('app.tag',
		'app.user',
		'app.role',
		'app.profile',
		'app.file_storage',
		'app.gpgkey',
		'app.groupsUser',
		'app.group',
		'core.cakeSession'
	);

	public $autoFixtures = true;

/**
 * Setup
 * @return void
 */
	public function setup() {
		parent::setUp();
		$this->Tag = ClassRegistry::init('Tag');
	}

/**
 * Test if the default tags as set in the fixture
 * @return void
 */
	public function testFixtures() {
		$t = $this->Tag->find('first', array('conditions' => array('name' => Common::Uuid())));
		$this->assertEquals(empty($t), true, 'Shouldnt find a tag that does not exist');
		$t = $this->Tag->find('first', array('conditions' => array('name' => 'facebook')));
		$this->assertEquals(is_array($t), true, 'Facebook Tag should be present in the database');
	}

/**
 * Test Content validation
 * @return void
 */
	public function testContent() {
		$len = 64;
		$testcases = array(
			// Not empty
			'' => false,
			// Email are not accepted
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
			'\'"-' => true,
			'?!#,._([)]' => false,
			// Digit accepted
			'0123456789' => true,
			// Html
			'<strong>test</strong>' => false,
		);
		foreach ($testcases as $testcase => $result) {
			$tag = array('Tag' => array('name' => $testcase));
			$this->Tag->set($tag);
			if ($result) {
				$msg = 'name with content "' . $testcase . '" should be allowed';
			} else {
				$msg = 'name with content "' . $testcase . '" should not be allowed';
			}
			$this->assertEquals($this->Tag->validates(array('fieldList' => array('name'))), $result, $msg);
		}
	}
}

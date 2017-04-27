<?php
/**
 * Group Model Test
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @package       app.Test.Case.Model.GroupTest
 * @since         version 2.12.7
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('Group', 'Model');

class GroupTest extends CakeTestCase {

	public $fixtures = array(
		'app.group',
		'app.user',
		'app.role',
		'app.gpgkey',
		'app.profile',
		'app.file_storage',
		'app.groupsUser',
		'core.cakeSession'
	);

	public function setUp() {
		parent::setUp();
		$this->Group = ClassRegistry::init('Group');
	}

/**
 * Test Name Validation
 * @return void
 */
	public function testNameValidation() {
		$testcases = array(
			'' => false,
			'?!#' => false,
			'test' => true,
			'test@test.com' => false,
			'1test' => true
		);

		foreach ($testcases as $testcase => $result) {
			$group = array('Group' => array('name' => $testcase));
			$this->Group->set($group);
			if ($result) {
				$msg = 'validation of the group name with "' . $testcase . '" should validate';
			} else {
				$msg = 'validation of the group name with "' . $testcase . '" should not validate';
			}
			$this->assertEquals($this->Group->validates(array('fieldList' => array('name'))), $result, $msg);
		}
	}

/**
 * Test Name Validation where a similar name already exists.
 * @return void
 */
	public function testNameAlreadyExistValidation() {
		$group = [
			'name' => 'testgroup',
		];

		$this->Group->create();
		$this->Group->save($group);

		$this->Group->create();
		$this->Group->set($group);
		$validates = $this->Group->validates(array('fieldList' => array('name')));
		$this->assertFalse($validates, 'A group with the same name as an existing one shouldn\'t validate');
	}

}


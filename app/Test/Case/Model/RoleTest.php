<?php
/**
 * Role Model Test
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('Role', 'Model');

class RoleTest extends CakeTestCase {

	public $fixtures = array('app.role');

	public $autoFixtures = true;

/**
 * Setup
 * @return void
 */
	public function setup() {
		parent::setUp();
		$this->Role = ClassRegistry::init('Role');
	}

/**
 * Test if the default roles as set in the database
 * @return void
 */
	public function testConstants() {
		$r = $this->Role->find('first', array('conditions' => array('name' => Common::Uuid())));
		$this->assertEquals(empty($r), true, 'Shouldnt find a role that does not exist');
		$r = $this->Role->find('first', array('conditions' => array('name' => Role::ADMIN)));
		$this->assertEquals(is_array($r), true, 'Default admin role should be present in the database');
		$r = $this->Role->find('first', array('conditions' => array('name' => Role::GUEST)));
		$this->assertEquals(is_array($r), true, 'Default guest role should be present in the database');
		$r = $this->Role->find('first', array('conditions' => array('name' => Role::USER)));
		$this->assertEquals(is_array($r), true, 'Default user role should be present in the database');
		$r = $this->Role->find('first', array('conditions' => array('name' => Role::ROOT)));
		$this->assertEquals(is_array($r), true, 'Default root role should be present in the database');
	}

/**
 * Test GetFindFields
 */
	public function testGetFindFields() {
		$default = ['fields' => []];
		$this->assertNotEquals($default, Role::getFindFields('view'), 'Find fields missing for view');
		$this->assertEquals($default, Role::getFindFields('rubish'), 'Find fields should be empty for wrong find');
	}
}

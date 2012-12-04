<?php
/**
 * CategoryType Fixture
 *
 * @copyright   Copyright 2012, Passbolt.com
 * @license     http://www.passbolt.com/license
 * @package     app.Test.Fixture.CategoryTypeFixture
 * @since       version 2.12.9
 */
App::uses('CategoryType', 'Model');

class CategoryTypeFixture extends CakeTestFixture {

	public $useDbConfig = 'test';

	public $import = 'CategoryType';

	public function init() {
		$this->records = array(
			array('id' => '50bda570-9364-4c41-9504-a7c58cebc04d','name' => 'default','description' => NULL),
		  array('id' => '50bda570-e3d4-457e-9015-a7c58cebc04d','name' => 'database','description' => NULL),
		  array('id' => '50bda570-ed2c-455b-aeba-a7c58cebc04d','name' => 'ssh','description' => NULL)
		);
		parent::init();
	}
}

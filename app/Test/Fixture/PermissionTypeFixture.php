<?php
/**
 * Group Fixture
 *
 * @copyright   Copyright 2012, Passbolt.com
 * @license     http://www.passbolt.com/license
 * @package     app.Test.Fixture.PermissionTypeFixture
 * @since       version 2.12.11
 */
App::uses('PermissionType', 'Model');

class PermissionTypeFixture extends CakeTestFixture {

	public $useDbConfig = 'test';

	public $import = 'PermissionType';

	public function init() {
		$this->records = array(
			array('id' => '50bd038f-1100-41a2-95ea-89b38cebc04d','serial' => '8','name' => 'a---','binary' => '1000','_admin' => '1','_update' => '0','_create' => '0','_read' => '0','description' => '','active' => '0'),
			array('id' => '50bd038f-4288-4616-9245-89b38cebc04d','serial' => '7','name' => '-ucr','binary' => '0111','_admin' => '0','_update' => '1','_create' => '1','_read' => '1','description' => '','active' => '1'),
			array('id' => '50bd038f-560c-41db-abde-89b38cebc04d','serial' => '0','name' => '----','binary' => '0000','_admin' => '0','_update' => '0','_create' => '0','_read' => '0','description' => '','active' => '1'),
			array('id' => '50bd038f-57f0-43be-a02b-89b38cebc04d','serial' => '6','name' => '-uc-','binary' => '0110','_admin' => '0','_update' => '1','_create' => '1','_read' => '0','description' => '','active' => '0'),
			array('id' => '50bd038f-7780-48e8-a03e-89b38cebc04d','serial' => '5','name' => '-u-r','binary' => '0101','_admin' => '0','_update' => '1','_create' => '0','_read' => '1','description' => '','active' => '0'),
			array('id' => '50bd038f-7c04-4a03-bf16-89b38cebc04d','serial' => '1','name' => '---r','binary' => '0001','_admin' => '0','_update' => '0','_create' => '0','_read' => '1','description' => '','active' => '1'),
			array('id' => '50bd038f-a9cc-4721-be83-89b38cebc04d','serial' => '2','name' => '--c-','binary' => '0010','_admin' => '0','_update' => '0','_create' => '1','_read' => '0','description' => '','active' => '0'),
			array('id' => '50bd038f-aca0-4cb6-b326-89b38cebc04d','serial' => '15','name' => 'aucr','binary' => '1111','_admin' => '1','_update' => '1','_create' => '1','_read' => '1','description' => '','active' => '1'),
			array('id' => '50bd038f-b1ec-408e-bc13-89b38cebc04d','serial' => '11','name' => 'a-cr','binary' => '1011','_admin' => '1','_update' => '0','_create' => '1','_read' => '1','description' => '','active' => '0'),
			array('id' => '50bd038f-ba88-461c-8218-89b38cebc04d','serial' => '13','name' => 'au-r','binary' => '1101','_admin' => '1','_update' => '1','_create' => '0','_read' => '1','description' => '','active' => '0'),
			array('id' => '50bd038f-c998-4ffd-96b0-89b38cebc04d','serial' => '4','name' => '-u--','binary' => '0100','_admin' => '0','_update' => '1','_create' => '0','_read' => '0','description' => '','active' => '0'),
			array('id' => '50bd038f-ce84-42b4-9715-89b38cebc04d','serial' => '12','name' => 'au--','binary' => '1100','_admin' => '1','_update' => '1','_create' => '0','_read' => '0','description' => '','active' => '0'),
			array('id' => '50bd038f-d0f0-45fa-aec3-89b38cebc04d','serial' => '3','name' => '--cr','binary' => '0011','_admin' => '0','_update' => '0','_create' => '1','_read' => '1','description' => '','active' => '1'),
			array('id' => '50bd038f-d590-4728-952b-89b38cebc04d','serial' => '14','name' => 'auc-','binary' => '1110','_admin' => '1','_update' => '1','_create' => '1','_read' => '0','description' => '','active' => '0'),
			array('id' => '50bd038f-dc08-4b03-a695-89b38cebc04d','serial' => '10','name' => 'a-c-','binary' => '1010','_admin' => '1','_update' => '0','_create' => '1','_read' => '0','description' => '','active' => '0'),
			array('id' => '50bd038f-f9a4-48fd-8d98-89b38cebc04d','serial' => '9','name' => 'a--r','binary' => '1001','_admin' => '1','_update' => '0','_create' => '0','_read' => '1','description' => '','active' => '0')
		);
		parent::init();
	}
}
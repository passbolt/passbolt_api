<?php
/**
 * PermissionsType Fixture
 */
class PermissionsTypeFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'serial' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2, 'key' => 'unique', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 4, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'binary' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 4, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'_admin' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'_update' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'_create' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'_read' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'description' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'serial' => array('column' => 'serial', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '5204e74b-00d0-4d4b-b335-75198cebc04d',
			'serial' => '1',
			'name' => '---r',
			'binary' => '0001',
			'_admin' => 0,
			'_update' => 0,
			'_create' => 0,
			'_read' => 1,
			'description' => 'read only',
			'active' => 1
		),
		array(
			'id' => '5204e74b-055c-4473-8f1b-75198cebc04d',
			'serial' => '7',
			'name' => '-ucr',
			'binary' => '0111',
			'_admin' => 0,
			'_update' => 1,
			'_create' => 1,
			'_read' => 1,
			'description' => 'create, update and read',
			'active' => 1
		),
		array(
			'id' => '5204e74b-13f4-4c8e-9ef7-75198cebc04d',
			'serial' => '8',
			'name' => 'a---',
			'binary' => '1000',
			'_admin' => 1,
			'_update' => 0,
			'_create' => 0,
			'_read' => 0,
			'description' => 'admin only',
			'active' => 0
		),
		array(
			'id' => '5204e74b-28a8-4685-9d06-75198cebc04d',
			'serial' => '9',
			'name' => 'a--r',
			'binary' => '1001',
			'_admin' => 1,
			'_update' => 0,
			'_create' => 0,
			'_read' => 1,
			'description' => 'admin and read',
			'active' => 0
		),
		array(
			'id' => '5204e74b-3b04-4383-ba32-75198cebc04d',
			'serial' => '10',
			'name' => 'a-c-',
			'binary' => '1010',
			'_admin' => 1,
			'_update' => 0,
			'_create' => 1,
			'_read' => 0,
			'description' => 'admin and create',
			'active' => 0
		),
		array(
			'id' => '5204e74b-4e28-4266-a2ba-75198cebc04d',
			'serial' => '11',
			'name' => 'a-cr',
			'binary' => '1011',
			'_admin' => 1,
			'_update' => 0,
			'_create' => 1,
			'_read' => 1,
			'description' => 'admin, read and create',
			'active' => 0
		),
		array(
			'id' => '5204e74b-5fbc-43f5-b64f-75198cebc04d',
			'serial' => '12',
			'name' => 'au--',
			'binary' => '1100',
			'_admin' => 1,
			'_update' => 1,
			'_create' => 0,
			'_read' => 0,
			'description' => 'admin and update',
			'active' => 0
		),
		array(
			'id' => '5204e74b-72e0-4b36-9c8e-75198cebc04d',
			'serial' => '13',
			'name' => 'au-r',
			'binary' => '1101',
			'_admin' => 1,
			'_update' => 1,
			'_create' => 0,
			'_read' => 1,
			'description' => 'admin, read and update',
			'active' => 0
		),
		array(
			'id' => '5204e74b-8474-484e-b52a-75198cebc04d',
			'serial' => '14',
			'name' => 'auc-',
			'binary' => '1110',
			'_admin' => 1,
			'_update' => 1,
			'_create' => 1,
			'_read' => 0,
			'description' => 'admin, create and update',
			'active' => 0
		),
		array(
			'id' => '5204e74b-9734-4548-ac57-75198cebc04d',
			'serial' => '15',
			'name' => 'aucr',
			'binary' => '1111',
			'_admin' => 1,
			'_update' => 1,
			'_create' => 1,
			'_read' => 1,
			'description' => 'all rights',
			'active' => 1
		),
		array(
			'id' => '5204e74b-a8c8-441d-9ba8-75198cebc04d',
			'serial' => '2',
			'name' => '--c-',
			'binary' => '0010',
			'_admin' => 0,
			'_update' => 0,
			'_create' => 1,
			'_read' => 0,
			'description' => 'create only',
			'active' => 0
		),
		array(
			'id' => '5204e74b-bb88-4d4f-8b93-75198cebc04d',
			'serial' => '3',
			'name' => '--cr',
			'binary' => '0011',
			'_admin' => 0,
			'_update' => 0,
			'_create' => 1,
			'_read' => 1,
			'description' => 'create and read',
			'active' => 1
		),
		array(
			'id' => '5204e74b-ce48-46c6-8abc-75198cebc04d',
			'serial' => '4',
			'name' => '-u--',
			'binary' => '0100',
			'_admin' => 0,
			'_update' => 1,
			'_create' => 0,
			'_read' => 0,
			'description' => 'update only',
			'active' => 0
		),
		array(
			'id' => '5204e74b-dfdc-49a9-8602-75198cebc04d',
			'serial' => '5',
			'name' => '-u-r',
			'binary' => '0101',
			'_admin' => 0,
			'_update' => 1,
			'_create' => 0,
			'_read' => 1,
			'description' => 'update and read',
			'active' => 0
		),
		array(
			'id' => '5204e74b-e8fc-46dd-b980-75198cebc04d',
			'serial' => '0',
			'name' => '----',
			'binary' => '0000',
			'_admin' => 0,
			'_update' => 0,
			'_create' => 0,
			'_read' => 0,
			'description' => 'no right',
			'active' => 1
		),
		array(
			'id' => '5204e74b-f29c-4564-9c9c-75198cebc04d',
			'serial' => '6',
			'name' => '-uc-',
			'binary' => '0110',
			'_admin' => 0,
			'_update' => 1,
			'_create' => 1,
			'_read' => 0,
			'description' => 'create and update',
			'active' => 0
		),
	);

}

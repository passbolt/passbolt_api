<?php
/**
 * PermissionsTypeFixture
 *
 */
class PermissionsTypeFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'serial' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'unique'),
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
			'SERIAL' => array('column' => 'serial', 'unique' => 1)
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
			'id' => '50d82026-154c-476d-bc60-6dd4d7a10fce',
			'serial' => '4',
			'name' => '-u--',
			'binary' => '0100',
			'_admin' => 0,
			'_update' => 1,
			'_create' => 0,
			'_read' => 0,
			'description' => '',
			'active' => 0
		),
		array(
			'id' => '50d82026-20ac-4597-ae03-6dd4d7a10fce',
			'serial' => '9',
			'name' => 'a--r',
			'binary' => '1001',
			'_admin' => 1,
			'_update' => 0,
			'_create' => 0,
			'_read' => 1,
			'description' => '',
			'active' => 0
		),
		array(
			'id' => '50d82026-2278-4bd4-ae2f-6dd4d7a10fce',
			'serial' => '8',
			'name' => 'a---',
			'binary' => '1000',
			'_admin' => 1,
			'_update' => 0,
			'_create' => 0,
			'_read' => 0,
			'description' => '',
			'active' => 0
		),
		array(
			'id' => '50d82026-3a24-4355-b04b-6dd4d7a10fce',
			'serial' => '7',
			'name' => '-ucr',
			'binary' => '0111',
			'_admin' => 0,
			'_update' => 1,
			'_create' => 1,
			'_read' => 1,
			'description' => '',
			'active' => 1
		),
		array(
			'id' => '50d82026-44f8-43a8-b3ae-6dd4d7a10fce',
			'serial' => '6',
			'name' => '-uc-',
			'binary' => '0110',
			'_admin' => 0,
			'_update' => 1,
			'_create' => 1,
			'_read' => 0,
			'description' => '',
			'active' => 0
		),
		array(
			'id' => '50d82026-63e8-4545-9798-6dd4d7a10fce',
			'serial' => '1',
			'name' => '---r',
			'binary' => '0001',
			'_admin' => 0,
			'_update' => 0,
			'_create' => 0,
			'_read' => 1,
			'description' => '',
			'active' => 1
		),
		array(
			'id' => '50d82026-6724-48e9-b29d-6dd4d7a10fce',
			'serial' => '10',
			'name' => 'a-c-',
			'binary' => '1010',
			'_admin' => 1,
			'_update' => 0,
			'_create' => 1,
			'_read' => 0,
			'description' => '',
			'active' => 0
		),
		array(
			'id' => '50d82026-81b8-4c74-9f51-6dd4d7a10fce',
			'serial' => '11',
			'name' => 'a-cr',
			'binary' => '1011',
			'_admin' => 1,
			'_update' => 0,
			'_create' => 1,
			'_read' => 1,
			'description' => '',
			'active' => 0
		),
		array(
			'id' => '50d82026-b338-47aa-922f-6dd4d7a10fce',
			'serial' => '2',
			'name' => '--c-',
			'binary' => '0010',
			'_admin' => 0,
			'_update' => 0,
			'_create' => 1,
			'_read' => 0,
			'description' => '',
			'active' => 0
		),
		array(
			'id' => '50d82026-d764-4979-b4ca-6dd4d7a10fce',
			'serial' => '5',
			'name' => '-u-r',
			'binary' => '0101',
			'_admin' => 0,
			'_update' => 1,
			'_create' => 0,
			'_read' => 1,
			'description' => '',
			'active' => 0
		),
		array(
			'id' => '50d82026-ecb4-41a7-a36f-6dd4d7a10fce',
			'serial' => '0',
			'name' => '----',
			'binary' => '0000',
			'_admin' => 0,
			'_update' => 0,
			'_create' => 0,
			'_read' => 0,
			'description' => '',
			'active' => 1
		),
		array(
			'id' => '50d82026-ed80-4198-b2af-6dd4d7a10fce',
			'serial' => '3',
			'name' => '--cr',
			'binary' => '0011',
			'_admin' => 0,
			'_update' => 0,
			'_create' => 1,
			'_read' => 1,
			'description' => '',
			'active' => 1
		),
		array(
			'id' => '50d82027-3e74-4279-9ab5-6dd4d7a10fce',
			'serial' => '13',
			'name' => 'au-r',
			'binary' => '1101',
			'_admin' => 1,
			'_update' => 1,
			'_create' => 0,
			'_read' => 1,
			'description' => '',
			'active' => 0
		),
		array(
			'id' => '50d82027-57a4-4daf-9785-6dd4d7a10fce',
			'serial' => '12',
			'name' => 'au--',
			'binary' => '1100',
			'_admin' => 1,
			'_update' => 1,
			'_create' => 0,
			'_read' => 0,
			'description' => '',
			'active' => 0
		),
		array(
			'id' => '50d82027-ca5c-4d6e-9787-6dd4d7a10fce',
			'serial' => '15',
			'name' => 'aucr',
			'binary' => '1111',
			'_admin' => 1,
			'_update' => 1,
			'_create' => 1,
			'_read' => 1,
			'description' => '',
			'active' => 1
		),
		array(
			'id' => '50d82027-d0c0-4d44-831a-6dd4d7a10fce',
			'serial' => '14',
			'name' => 'auc-',
			'binary' => '1110',
			'_admin' => 1,
			'_update' => 1,
			'_create' => 1,
			'_read' => 0,
			'description' => '',
			'active' => 0
		),
	);

}

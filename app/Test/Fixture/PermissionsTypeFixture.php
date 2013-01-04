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
			'id' => '50e6c317-4ee8-43b8-bedb-1586d7a10fce',
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
			'id' => '50e6c317-5000-497b-8f10-1586d7a10fce',
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
			'id' => '50e6c317-7a6c-4e03-8857-1586d7a10fce',
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
			'id' => '50e6c317-8f98-42d1-ab20-1586d7a10fce',
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
			'id' => '50e6c317-9aa8-460e-ba5b-1586d7a10fce',
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
			'id' => '50e6c318-01c0-4b3b-a839-1586d7a10fce',
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
			'id' => '50e6c318-05a8-47a4-b536-1586d7a10fce',
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
			'id' => '50e6c318-c0fc-4dcc-9b41-1586d7a10fce',
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
			'id' => '50e6c318-c584-4dc3-abe2-1586d7a10fce',
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
			'id' => '50e6c318-c96c-41c8-ad14-1586d7a10fce',
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
			'id' => '50e6c318-d240-43c2-a0df-1586d7a10fce',
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
			'id' => '50e6c318-d2a4-4538-8c6b-1586d7a10fce',
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
			'id' => '50e6c318-d600-4302-b76a-1586d7a10fce',
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
			'id' => '50e6c318-da88-4893-a54d-1586d7a10fce',
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
			'id' => '50e6c318-df10-4c54-a2f0-1586d7a10fce',
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
			'id' => '50e6c318-ee74-4542-b1e6-1586d7a10fce',
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

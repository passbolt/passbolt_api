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
			'id' => '50d79c55-0ab4-4441-bb78-1134d7a10fce',
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
			'id' => '50d79c55-0ac8-47c3-b792-1134d7a10fce',
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
			'id' => '50d79c55-647c-4aab-97a7-1134d7a10fce',
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
			'id' => '50d79c55-66fc-4f19-a3ed-1134d7a10fce',
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
			'id' => '50d79c55-6ca0-4ee7-9c20-1134d7a10fce',
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
			'id' => '50d79c55-7d70-4a9c-885a-1134d7a10fce',
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
			'id' => '50d79c55-8cb8-4893-952c-1134d7a10fce',
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
			'id' => '50d79c55-ad30-47bd-87c5-1134d7a10fce',
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
			'id' => '50d79c55-bea8-4464-9f26-1134d7a10fce',
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
			'id' => '50d79c55-d5a4-4c87-826f-1134d7a10fce',
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
			'id' => '50d79c55-e9ec-4338-ae86-1134d7a10fce',
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
			'id' => '50d79c55-eadc-44ac-b44f-1134d7a10fce',
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
			'id' => '50d79c55-eb90-4b0c-b967-1134d7a10fce',
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
			'id' => '50d79c55-f22c-43f2-a700-1134d7a10fce',
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
			'id' => '50d79c55-fe00-425d-a4e0-1134d7a10fce',
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
		array(
			'id' => '50d79c56-9e50-421b-be60-1134d7a10fce',
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
	);

}

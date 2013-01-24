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
		'serial' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 2, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 4, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'binary' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 4, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'_admin' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'_update' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'_create' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'_read' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'description' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'serial', 'unique' => 1)
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
			'serial' => '',
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
	);

}

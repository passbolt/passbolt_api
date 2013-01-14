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
			'id' => '50f416c1-0320-4133-b0f9-0bd5f9db06b5',
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
			'id' => '50f416c1-0b94-4941-b593-0bd5f9db06b5',
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
			'id' => '50f416c1-113c-4ba1-bf12-0bd5f9db06b5',
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
			'id' => '50f416c1-17c0-46f0-ba9f-0bd5f9db06b5',
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
			'id' => '50f416c1-286c-49aa-9bbd-0bd5f9db06b5',
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
			'id' => '50f416c1-2bf0-4bb3-94e6-0bd5f9db06b5',
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
			'id' => '50f416c1-37d8-4f3a-9261-0bd5f9db06b5',
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
			'id' => '50f416c1-5f60-4795-a9ee-0bd5f9db06b5',
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
			'id' => '50f416c1-6780-4fb9-b415-0bd5f9db06b5',
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
			'id' => '50f416c1-7594-4202-8485-0bd5f9db06b5',
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
			'id' => '50f416c1-8204-4d58-b185-0bd5f9db06b5',
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
			'id' => '50f416c1-9684-4bc6-9cb8-0bd5f9db06b5',
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
			'id' => '50f416c1-c514-4f87-8b6f-0bd5f9db06b5',
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
			'id' => '50f416c1-ce00-4cb8-b1b2-0bd5f9db06b5',
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
			'id' => '50f416c1-d1f4-4f60-a4ef-0bd5f9db06b5',
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
			'id' => '50f416c1-fda4-42cf-9ae3-0bd5f9db06b5',
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
	);

}

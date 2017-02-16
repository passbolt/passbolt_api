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
			'description' => 'read only',
			'active' => 1
		),
		array(
			'id' => '5204e74b-055c-4473-8f1b-75198cebc04d',
			'serial' => '7',
			'name' => '-ucr',
			'description' => 'create, update and read',
			'active' => 1
		),
		array(
			'id' => '5204e74b-9734-4548-ac57-75198cebc04d',
			'serial' => '15',
			'name' => 'aucr',
			'description' => 'all rights',
			'active' => 1
		),
	);

}

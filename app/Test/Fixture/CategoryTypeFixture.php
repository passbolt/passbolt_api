<?php
/**
 * CategoryTypeFixture
 *
 */
class CategoryTypeFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
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
			'id' => '79709255-d7fe-30b7-aadb-b0194a0a53ba',
			'name' => 'default',
			'description' => 'default category type description'
		),
		array(
			'id' => 'b8c2c06b-2f99-31a1-a555-ead66d3e6a7d',
			'name' => 'ssh',
			'description' => 'ssh category type description'
		),
		array(
			'id' => 'bc025999-5432-34a8-a95b-a4aa66eef0b3',
			'name' => 'database',
			'description' => 'database category type description'
		),
	);

}

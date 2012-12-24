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
			'id' => '0234f3a4-c5cd-11e1-a0c5-080027456c4c',
			'name' => 'ssh',
			'description' => 'ssh category type description'
		),
		array(
			'id' => '0234f3a4-c5cd-11e1-a0c5-080027796c4c',
			'name' => 'default',
			'description' => 'default category type description'
		),
		array(
			'id' => '0234f3a4-c5cd-11e1-a0c5-081127796c4c',
			'name' => 'database',
			'description' => 'database category type description'
		),
	);

}

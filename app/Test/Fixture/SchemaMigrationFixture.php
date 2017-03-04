<?php
/**
 * SchemaMigration Fixture
 */
class SchemaMigrationFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'class' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'type' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
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
			'id' => '1',
			'class' => 'InitMigrations',
			'type' => 'Migrations',
			'created' => '2017-03-04 17:46:27'
		),
		array(
			'id' => '2',
			'class' => 'ConvertVersionToClassNames',
			'type' => 'Migrations',
			'created' => '2017-03-04 17:46:27'
		),
		array(
			'id' => '3',
			'class' => 'IncreaseClassNameLength',
			'type' => 'Migrations',
			'created' => '2017-03-04 17:46:27'
		),
		array(
			'id' => '4',
			'class' => 'SettingHashToDefaultNull',
			'type' => 'FileStorage',
			'created' => '2017-03-04 17:46:27'
		),
		array(
			'id' => '5',
			'class' => 'Migration_1_1_0',
			'type' => 'app',
			'created' => '2017-03-04 17:46:27'
		),
		array(
			'id' => '6',
			'class' => 'Migration_1_2_0',
			'type' => 'app',
			'created' => '2017-03-04 17:46:27'
		),
		array(
			'id' => '7',
			'class' => 'Migration_1_3_0',
			'type' => 'app',
			'created' => '2017-03-04 17:46:27'
		),
		array(
			'id' => '8',
			'class' => 'Migration_1_4_0',
			'type' => 'app',
			'created' => '2017-03-04 17:46:27'
		),
	);

}

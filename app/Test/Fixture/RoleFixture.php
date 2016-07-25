<?php
/**
 * Role Fixture
 */
class RoleFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'key' => 'unique', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'name' => array('column' => 'name', 'unique' => 1)
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
			'id' => '23d941d5-3676-3443-afdb-aaf2456f3b49',
			'name' => 'admin',
			'description' => 'Organization administrator',
			'created' => '2012-07-04 13:39:25',
			'modified' => '2012-07-04 13:39:25'
		),
		array(
			'id' => '49aad81e-4f70-3380-a92e-12292597409f',
			'name' => 'guest',
			'description' => 'Non logged in user',
			'created' => '2012-07-04 13:39:25',
			'modified' => '2012-07-04 13:39:25'
		),
		array(
			'id' => '857760a6-4f9d-3f1b-a292-95b630bcf03f',
			'name' => 'root',
			'description' => 'Super Administrator',
			'created' => '2012-07-04 13:39:25',
			'modified' => '2012-07-04 13:39:25'
		),
		array(
			'id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
			'name' => 'user',
			'description' => 'Logged in user',
			'created' => '2012-07-04 13:39:25',
			'modified' => '2012-07-04 13:39:25'
		),
	);

}

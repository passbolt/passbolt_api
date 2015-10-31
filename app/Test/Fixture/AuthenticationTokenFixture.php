<?php
/**
 * AuthenticationTokenFixture
 *
 */
class AuthenticationTokenFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'token' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'user_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'active' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 1, 'unsigned' => false),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'created_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'modified_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
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
			'id' => '56263a24-5714-4afd-90e9-06d9dbeb2d5e',
			'token' => '56a9f9f8-7628-4576-af1b-9e8acb930bc5',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'active' => '1',
			'created' => '2015-10-20 18:27:08',
			'modified' => '2015-10-20 18:27:08',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '56263a24-87f4-42ff-a295-0310dbeb2d5e',
			'token' => '76ce8e7f-4557-47f6-aca2-07e9db7396f1',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'active' => '1',
			'created' => '2015-10-20 18:27:08',
			'modified' => '2015-10-20 18:27:08',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '56263a34-49c8-42f9-a3df-030adbeb2d5e',
			'token' => '552311ff-b06b-4832-a527-dfb3259b5d4e',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'active' => '1',
			'created' => '2015-10-20 18:27:24',
			'modified' => '2015-10-20 18:27:24',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '56263a34-8ab8-448b-93ca-0799dbeb2d5e',
			'token' => 'ba7c82b0-0bc3-4653-accb-2d4a909a9f77',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'active' => '1',
			'created' => '2015-10-20 18:27:24',
			'modified' => '2015-10-20 18:27:24',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '56263a38-075c-4bbc-b34d-0a76dbeb2d5e',
			'token' => '1e87cb83-3914-47d3-abf4-75d04744fe8a',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'active' => '1',
			'created' => '2015-10-20 18:27:28',
			'modified' => '2015-10-20 18:27:28',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '56263a44-67c0-48b1-9b71-06d9dbeb2d5e',
			'token' => '6a5208a8-b32b-4478-abbf-e5eb01771a6f',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'active' => '1',
			'created' => '2015-10-20 18:27:40',
			'modified' => '2015-10-20 18:27:40',
			'created_by' => '',
			'modified_by' => ''
		),
	);

}

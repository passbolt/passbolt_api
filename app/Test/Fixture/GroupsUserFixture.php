<?php
/**
 * GroupsUser Fixture
 */
class GroupsUserFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'group_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'key' => 'index', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'user_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'key' => 'index', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'created_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'user_id' => array('column' => array('user_id', 'group_id'), 'unique' => 0),
			'groups' => array('column' => 'group_id', 'unique' => 0),
			'users' => array('column' => 'user_id', 'unique' => 0)
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
			'id' => '5b25edb9-3b4b-3c05-a1dd-d260be08b4ba',
			'group_id' => '6da9af58-6e84-3a66-a1da-e11454751a45',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'created' => '2016-01-29 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '84da353b-c07a-3aef-a9c1-3185be7e767a',
			'group_id' => '563a4b8f-e7ac-31ff-a3e8-f1b6d3ff222a',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'created' => '2016-01-29 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bb6d0d6f-f38c-3946-a030-8ac15c0f652d',
			'group_id' => '6da9af58-6e84-3a66-a1da-e11454751a45',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'created' => '2016-01-29 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}

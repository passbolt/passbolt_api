<?php
/**
 * GroupsUserFixture
 *
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
			'id' => '53865f1f-230c-448b-b911-2173c0a895dc',
			'group_id' => '10ce2d3a-0468-433b-b59f-3053d7a10fce',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'created' => '2012-12-17 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '53865f1f-2adc-4aa6-8005-2173c0a895dc',
			'group_id' => '20ce3d3a-0468-433b-b59f-3053d7a10fce',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'created' => '2012-12-17 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '53865f1f-2dfc-45f7-85c7-2173c0a895dc',
			'group_id' => '30ce2d3a-0468-4334-b59f-3053d7a10fce',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'created' => '2012-12-17 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '53865f1f-311c-4535-8a72-2173c0a895dc',
			'group_id' => '30ce2d3a-0468-4334-b59f-3053d7a10fce',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'created' => '2012-12-17 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '53865f1f-33d8-4206-a198-2173c0a895dc',
			'group_id' => '40ce2d3a-0468-423b-b59f-3053d7a10fce',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'created' => '2012-12-17 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '53865f1f-3694-4552-b45b-2173c0a895dc',
			'group_id' => '40ce2d3a-0468-423b-b59f-3053d7a10fce',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'created' => '2012-12-17 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '53865f1f-3950-46a7-bce2-2173c0a895dc',
			'group_id' => '40ce2d3a-0468-423b-b59f-3053d7a10fce',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'created' => '2012-12-17 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '53865f1f-3c0c-47c8-a8e6-2173c0a895dc',
			'group_id' => '5ad56042-c5cd-11e1-a0c5-080027796c4c',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'created' => '2012-12-17 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '53865f1f-3ec8-4c41-b813-2173c0a895dc',
			'group_id' => '6bd58742-c5cd-11e1-a0c5-080027796c4c',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'created' => '2012-12-17 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '53865f1f-4184-4b03-b78e-2173c0a895dc',
			'group_id' => '6bd58742-c5cd-11e1-a0c5-080027796c4c',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'created' => '2012-12-17 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '53865fa5-0624-49d5-a802-204fc0a895dc',
			'group_id' => '6bd58742-c5cd-11e1-a0c5-080027796ce7',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'created' => '2012-12-17 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '53865fa5-0624-49d5-a802-215fc0a895dc',
			'group_id' => '8bd56042-d9cd-11e1-a0c5-080027796c4c',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'created' => '2012-12-17 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '53865fa5-1178-4480-b80f-204fc0a895dc',
			'group_id' => '6bd58742-c5cd-11e1-a0c5-080027796ce7',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'created' => '2012-12-17 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}

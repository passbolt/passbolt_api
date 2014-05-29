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
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'created' => '2012-12-17 13:39:25',
			'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '53865f1f-2adc-4aa6-8005-2173c0a895dc',
			'group_id' => '20ce3d3a-0468-433b-b59f-3053d7a10fce',
			'user_id' => '50cdea9c-f214-4549-9807-2f4fd7a10fce',
			'created' => '2012-12-17 13:39:25',
			'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '53865f1f-2dfc-45f7-85c7-2173c0a895dc',
			'group_id' => '30ce2d3a-0468-4334-b59f-3053d7a10fce',
			'user_id' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'created' => '2012-12-17 13:39:25',
			'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '53865f1f-311c-4535-8a72-2173c0a895dc',
			'group_id' => '30ce2d3a-0468-4334-b59f-3053d7a10fce',
			'user_id' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'created' => '2012-12-17 13:39:25',
			'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '53865f1f-33d8-4206-a198-2173c0a895dc',
			'group_id' => '40ce2d3a-0468-423b-b59f-3053d7a10fce',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'created' => '2012-12-17 13:39:25',
			'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '53865f1f-3694-4552-b45b-2173c0a895dc',
			'group_id' => '40ce2d3a-0468-423b-b59f-3053d7a10fce',
			'user_id' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'created' => '2012-12-17 13:39:25',
			'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '53865f1f-3950-46a7-bce2-2173c0a895dc',
			'group_id' => '40ce2d3a-0468-423b-b59f-3053d7a10fce',
			'user_id' => '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce',
			'created' => '2012-12-17 13:39:25',
			'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '53865f1f-3c0c-47c8-a8e6-2173c0a895dc',
			'group_id' => '5ad56042-c5cd-11e1-a0c5-080027796c4c',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'created' => '2012-12-17 13:39:25',
			'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '53865f1f-3ec8-4c41-b813-2173c0a895dc',
			'group_id' => '6bd58742-c5cd-11e1-a0c5-080027796c4c',
			'user_id' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'created' => '2012-12-17 13:39:25',
			'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '53865f1f-4184-4b03-b78e-2173c0a895dc',
			'group_id' => '6bd58742-c5cd-11e1-a0c5-080027796c4c',
			'user_id' => '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce',
			'created' => '2012-12-17 13:39:25',
			'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '53865fa5-0624-49d5-a802-204fc0a895dc',
			'group_id' => '6bd58742-c5cd-11e1-a0c5-080027796ce7',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'created' => '2012-12-17 13:39:25',
			'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '53865fa5-0df4-46e0-9b46-204fc0a895dc',
			'group_id' => '8bd56042-d9cd-11e1-a0c5-080027796c4c',
			'user_id' => 'abcdab9c-4380-adad-b4cc-2f4fd7a10fce',
			'created' => '2012-12-17 13:39:25',
			'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '53865fa5-1178-4480-b80f-204fc0a895dc',
			'group_id' => '6bd58742-c5cd-11e1-a0c5-080027796ce7',
			'user_id' => '533d346d-d378-4acc-affd-1663c0a895dc',
			'created' => '2012-12-17 13:39:25',
			'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		),
	);

}

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
		'is_admin' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
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
			'id' => '2f5c221d-fd31-334e-a0aa-7bebbded624b',
			'group_id' => '7fb692ab-2631-35bb-ab34-7f69b2e7f0a2',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => '5279f96d-4b58-3269-af03-4d9dadfd98ac',
			'group_id' => 'd6b15a4e-4fdf-3026-ac49-5a8de3fc49a0',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => '6cf22149-beb4-371c-aab2-2ac1012187b8',
			'group_id' => '563a4b8f-e7ac-31ff-a3e8-f1b6d3ff222a',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'is_admin' => 0,
			'created' => '2016-01-29 13:39:25',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => '6da4726b-2cc0-3add-a3cd-c0831538a47d',
			'group_id' => '7fb692ab-2631-35bb-ab34-7f69b2e7f0a2',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'is_admin' => 0,
			'created' => '2016-01-29 13:39:25',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => '84da353b-c07a-3aef-a9c1-3185be7e767a',
			'group_id' => '563a4b8f-e7ac-31ff-a3e8-f1b6d3ff222a',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => '8dd9a71c-343d-35e7-aa3f-881442c7ea61',
			'group_id' => '7fb692ab-2631-35bb-ab34-7f69b2e7f0a2',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => '96819396-6c95-3b42-ae24-fbfce90891df',
			'group_id' => '5deebe9f-8e83-354c-a035-4e79353a0957',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => 'b3d64136-0f00-3a69-abe6-bbc2295e96e4',
			'group_id' => '24537609-6db5-31bb-af0d-f7f0494dd184',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c73dd987-b246-3590-a591-690ee120d7eb',
			'group_id' => '7fb692ab-2631-35bb-ab34-7f69b2e7f0a2',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => 'db5918aa-4bd6-38bc-a491-4db9bbca219c',
			'group_id' => '981777cc-aa3a-3b5b-ac1d-86e281c37982',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => 'fe98a06d-be34-359c-a7a0-097c5119e894',
			'group_id' => '7fb692ab-2631-35bb-ab34-7f69b2e7f0a2',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'is_admin' => 0,
			'created' => '2016-01-29 13:39:25',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
	);

}

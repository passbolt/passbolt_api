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
			'id' => '2a2ee556-c1de-3b32-a4b8-af05ec11b02d',
			'group_id' => '1ec15696-e564-3e60-ad76-d73415b73afd',
			'user_id' => 'bfb648f5-9f37-343b-a616-7f3704d13d84',
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => '2f5c221d-fd31-334e-a0aa-7bebbded624b',
			'group_id' => '7fb692ab-2631-35bb-ab34-7f69b2e7f0a2',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => '526a9ce9-f6a9-37f1-aa83-4e3352ad7e1b',
			'group_id' => '0241e630-b161-3a3d-a6f7-f6d8e3cea3c8',
			'user_id' => 'bfb648f5-9f37-343b-a616-7f3704d13d84',
			'is_admin' => 0,
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
			'id' => '53611eaf-8f63-363a-a750-55c0f4e59b03',
			'group_id' => '1ec15696-e564-3e60-ad76-d73415b73afd',
			'user_id' => '47ad29dc-f0c7-3b9e-aae2-9e657397b60b',
			'is_admin' => 0,
			'created' => '2016-01-29 13:39:25',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => '58f75ce3-d146-32a5-a8a4-bfa52247448a',
			'group_id' => '1ec15696-e564-3e60-ad76-d73415b73afd',
			'user_id' => 'be77c78c-b767-3290-ae92-d5e5c590fcf9',
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => '5be1d673-98ed-35d7-a99b-9270f4793db1',
			'group_id' => '5a1e3498-35a0-32dc-ac2e-80dbd85c9017',
			'user_id' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => '64846ede-14ec-3da2-ada9-5e758d71572f',
			'group_id' => '53074209-fd29-3c8e-abaf-e017497f43cf',
			'user_id' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => '655d0e82-8833-3c93-a037-3563093e72f2',
			'group_id' => 'e6d598a1-e050-3237-a4d6-2ba75d65dc3b',
			'user_id' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
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
			'id' => '733788e6-16c4-3557-aa51-19d5aaf49957',
			'group_id' => '1ec15696-e564-3e60-ad76-d73415b73afd',
			'user_id' => '8a53f107-7ffc-3047-ad08-75effc419d21',
			'is_admin' => 0,
			'created' => '2016-01-29 13:39:25',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => '794d9e13-3558-3f6a-aae9-bdc3eacce779',
			'group_id' => '14153251-abcb-3c00-a2d1-b4fdb1423d26',
			'user_id' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
			'is_admin' => 1,
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
			'id' => '89b8ad5a-ad44-37f9-a9c1-3d4ded305389',
			'group_id' => 'fea0c76e-046c-33ab-ab27-809ce35c0cdb',
			'user_id' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
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
			'id' => 'a69f0ef6-1f5c-3570-a51e-90ac5fb08e69',
			'group_id' => '012568d6-9300-385b-a22a-e27d191764eb',
			'user_id' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
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
			'id' => 'cb8ff959-ff48-3152-a940-2648eac445f8',
			'group_id' => '0241e630-b161-3a3d-a6f7-f6d8e3cea3c8',
			'user_id' => '8a53f107-7ffc-3047-ad08-75effc419d21',
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => 'cbdda793-8d58-3c7f-a694-b2f54a4f3042',
			'group_id' => '0241e630-b161-3a3d-a6f7-f6d8e3cea3c8',
			'user_id' => 'be77c78c-b767-3290-ae92-d5e5c590fcf9',
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => 'cc6170c6-ff68-3be6-ac7f-9ccfb5a59775',
			'group_id' => 'ecc19da3-69b8-3c0d-a03a-2b29bfb7a610',
			'user_id' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => 'd47908dd-c690-3f45-ab9b-7140a0e6a24f',
			'group_id' => '640d1f30-0197-3276-a87e-a1ef389ee5fb',
			'user_id' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
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
			'id' => 'e62156ea-9bdd-37fe-a47c-ce9c57fb9d7c',
			'group_id' => '0241e630-b161-3a3d-a6f7-f6d8e3cea3c8',
			'user_id' => '47ad29dc-f0c7-3b9e-aae2-9e657397b60b',
			'is_admin' => 0,
			'created' => '2016-01-29 13:39:25',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => 'ececde02-c246-3cb7-ae41-9754940ccd35',
			'group_id' => 'da776e4a-73b7-3a58-a047-f31614bd15bb',
			'user_id' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => 'f5b8289b-c41b-3dc0-adcd-fbafc747d63f',
			'group_id' => '73c705f1-919d-3916-a5b7-990b3a517d14',
			'user_id' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
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

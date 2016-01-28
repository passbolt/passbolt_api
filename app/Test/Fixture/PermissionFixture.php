<?php
/**
 * PermissionFixture
 *
 */
class PermissionFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'aco' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 30, 'key' => 'index', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'aco_foreign_key' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'index', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'aro' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 30, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'aro_foreign_key' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'key' => 'index', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'type' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'created_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'modified_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'aco_foreign_key' => array('column' => 'aco_foreign_key', 'unique' => 0),
			'aro_foreign_key' => array('column' => 'aro_foreign_key', 'unique' => 0),
			'aco_aro' => array('column' => array('aco', 'aro'), 'unique' => 0),
			'type' => array('column' => 'type', 'unique' => 0)
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
			'id' => '06f7f4fd-2374-3634-a89a-7d6a6d1cc658',
			'aco' => 'Resource',
			'aco_foreign_key' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'aro' => 'User',
			'aro_foreign_key' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'type' => '15',
			'created' => '2016-01-25 19:09:11',
			'modified' => '2016-01-25 19:09:11',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '19d1fd6b-78e3-336d-a510-9d0bb37e545e',
			'aco' => 'Resource',
			'aco_foreign_key' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'aro' => 'User',
			'aro_foreign_key' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'type' => '15',
			'created' => '2016-01-25 19:09:11',
			'modified' => '2016-01-25 19:09:11',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '36708144-357f-3070-aa29-720c714c296f',
			'aco' => 'Resource',
			'aco_foreign_key' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'aro' => 'User',
			'aro_foreign_key' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'type' => '15',
			'created' => '2016-01-25 19:09:11',
			'modified' => '2016-01-25 19:09:11',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4ea50e76-91f7-313c-a9cf-3d28f209aa0a',
			'aco' => 'Resource',
			'aco_foreign_key' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'aro' => 'User',
			'aro_foreign_key' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'type' => '15',
			'created' => '2016-01-25 19:09:11',
			'modified' => '2016-01-25 19:09:11',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50e234af-7768-7890-890d-23a4d7a10fce',
			'aco' => 'Resource',
			'aco_foreign_key' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'aro' => 'Group',
			'aro_foreign_key' => '854dce19-6f1b-39bd-acbb-94d9a39b007f',
			'type' => '0',
			'created' => '2016-01-25 19:09:12',
			'modified' => '2016-01-25 19:09:12',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50e6b4ae-ea4c-4baf-aaf4-23a4d7a10dee',
			'aco' => 'Category',
			'aco_foreign_key' => '44eafde0-f430-3958-aeaa-56916202bad2',
			'aro' => 'User',
			'aro_foreign_key' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'type' => '15',
			'created' => '2016-01-25 19:09:12',
			'modified' => '2016-01-25 19:09:12',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50e6b4ae-ea4c-4baf-aaf4-23a4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '99f11a24-9f57-3d62-af7f-3bd1e47b0096',
			'aro' => 'Group',
			'aro_foreign_key' => '14153251-abcb-3c00-a2d1-b4fdb1423d26',
			'type' => '15',
			'created' => '2016-01-25 19:09:12',
			'modified' => '2016-01-25 19:09:12',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50e6b4af-5fa4-493d-bad0-23a4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '97883247-36a5-3656-affc-5ce8040eb2cc',
			'aro' => 'Group',
			'aro_foreign_key' => '854dce19-6f1b-39bd-acbb-94d9a39b007f',
			'type' => '1',
			'created' => '2016-01-25 19:09:12',
			'modified' => '2016-01-25 19:09:12',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50e6b4af-6d20-4e4e-bbcf-23a4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '2a760295-51f1-3a1e-a4c3-d5a9b227cb6e',
			'aro' => 'Group',
			'aro_foreign_key' => '854dce19-6f1b-39bd-acbb-94d9a39b007f',
			'type' => '0',
			'created' => '2016-01-25 19:09:12',
			'modified' => '2016-01-25 19:09:12',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50e6b4af-7768-45e0-890d-23a4d7a10fce',
			'aco' => 'Resource',
			'aco_foreign_key' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'aro' => 'Group',
			'aro_foreign_key' => '854dce19-6f1b-39bd-acbb-94d9a39b007f',
			'type' => '7',
			'created' => '2016-01-25 19:09:12',
			'modified' => '2016-01-25 19:09:12',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50e6b4af-7768-45e0-900d-7784d7a10fce',
			'aco' => 'Resource',
			'aco_foreign_key' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'aro' => 'User',
			'aro_foreign_key' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'type' => '0',
			'created' => '2016-01-25 19:09:12',
			'modified' => '2016-01-25 19:09:12',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50e6b4af-832c-44bf-8a49-23a4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '2a760295-51f1-3a1e-a4c3-d5a9b227cb6e',
			'aro' => 'Group',
			'aro_foreign_key' => '563a4b8f-e7ac-31ff-a3e8-f1b6d3ff222a',
			'type' => '1',
			'created' => '2016-01-25 19:09:12',
			'modified' => '2016-01-25 19:09:12',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50e6b4af-8824-48f9-89af-23a4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '865fc6b3-e566-3018-a8c3-14cfd1939c09',
			'aro' => 'Group',
			'aro_foreign_key' => '6d79c7f7-80d4-352b-a294-e97b32363c84',
			'type' => '1',
			'created' => '2016-01-25 19:09:12',
			'modified' => '2016-01-25 19:09:12',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50e6b4af-8ab8-4533-a4b4-23a4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => 'b0219d7d-7c67-33c0-af6f-1f95a4e63742',
			'aro' => 'Group',
			'aro_foreign_key' => 'f8ec9e82-5709-346a-a157-65bb7c11b34a',
			'type' => '1',
			'created' => '2016-01-25 19:09:12',
			'modified' => '2016-01-25 19:09:12',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50e6b4af-a490-43f5-9cc9-23a4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '7914592d-6919-3f02-a9c9-e23f92d36237',
			'aro' => 'Group',
			'aro_foreign_key' => 'b9102683-f94d-3c94-a6de-1ff09763046b',
			'type' => '7',
			'created' => '2016-01-25 19:09:12',
			'modified' => '2016-01-25 19:09:12',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50e6b4af-aa58-478c-804d-23a4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '7a7268fa-44cb-30f9-a943-644734657155',
			'aro' => 'User',
			'aro_foreign_key' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'type' => '15',
			'created' => '2016-01-25 19:09:12',
			'modified' => '2016-01-25 19:09:12',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50e6b4af-ad14-4659-a60d-23a4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '207b1b41-3db0-30f8-a8ed-ae05e9c38e72',
			'aro' => 'User',
			'aro_foreign_key' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'type' => '15',
			'created' => '2016-01-25 19:09:12',
			'modified' => '2016-01-25 19:09:12',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50e6b4af-b124-40e3-988e-23a4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '207b1b41-3db0-30f8-a8ed-ae05e9c38e72',
			'aro' => 'Group',
			'aro_foreign_key' => 'bdd0bf4c-a8e3-3d1a-aa79-5d9e9e46edb8',
			'type' => '1',
			'created' => '2016-01-25 19:09:12',
			'modified' => '2016-01-25 19:09:12',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50e6b4af-b598-42f7-b105-23a4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => 'a27bf9d2-84f4-3665-a720-a5e699ce10d0',
			'aro' => 'User',
			'aro_foreign_key' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'type' => '1',
			'created' => '2016-01-25 19:09:12',
			'modified' => '2016-01-25 19:09:12',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50e6b4af-c390-4e5e-a8f8-23a4d7a10fce',
			'aco' => 'Resource',
			'aco_foreign_key' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'aro' => 'User',
			'aro_foreign_key' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'type' => '1',
			'created' => '2016-01-25 19:09:12',
			'modified' => '2016-01-25 19:09:12',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50e6b4af-c390-4f2e-a8f8-23a4d7a10fcc',
			'aco' => 'Category',
			'aco_foreign_key' => '42d58fc0-b65f-30ed-a32d-2ac2d0593ae0',
			'aro' => 'User',
			'aro_foreign_key' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'type' => '3',
			'created' => '2016-01-25 19:09:12',
			'modified' => '2016-01-25 19:09:12',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50e6b4af-d4b0-43d8-947f-23a4d7a10ecb',
			'aco' => 'Category',
			'aco_foreign_key' => 'd022b078-b7ae-37a7-a0c2-46e82d9d630e',
			'aro' => 'Group',
			'aro_foreign_key' => '1f33f57a-b65b-3831-a133-236d2f874ef4',
			'type' => '7',
			'created' => '2016-01-25 19:09:12',
			'modified' => '2016-01-25 19:09:12',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50e6b4af-d4b0-43d8-947f-23a4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => 'e53c5665-060f-3900-a931-fca187d88f63',
			'aro' => 'Group',
			'aro_foreign_key' => '1f33f57a-b65b-3831-a133-236d2f874ef4',
			'type' => '1',
			'created' => '2016-01-25 19:09:12',
			'modified' => '2016-01-25 19:09:12',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50f6b4af-a491-43f5-fac9-23a4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '7914592d-6919-3f02-a9c9-e23f92d36237',
			'aro' => 'User',
			'aro_foreign_key' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'type' => '0',
			'created' => '2016-01-25 19:09:12',
			'modified' => '2016-01-25 19:09:12',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '533d2ecb-3ec8-4437-9ca5-0aafc0a895dc',
			'aco' => 'Category',
			'aco_foreign_key' => '99f11a24-9f57-3d62-af7f-3bd1e47b0096',
			'aro' => 'User',
			'aro_foreign_key' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'type' => '15',
			'created' => '2016-01-25 19:09:12',
			'modified' => '2016-01-25 19:09:12',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '598e8b32-13f9-3962-a0dd-6e3afe3e9fa5',
			'aco' => 'Resource',
			'aco_foreign_key' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'aro' => 'User',
			'aro_foreign_key' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'type' => '15',
			'created' => '2016-01-25 19:09:11',
			'modified' => '2016-01-25 19:09:11',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5b0cd892-e38e-37f5-a036-0a5b8245531f',
			'aco' => 'Resource',
			'aco_foreign_key' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'aro' => 'User',
			'aro_foreign_key' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'type' => '15',
			'created' => '2016-01-25 19:09:11',
			'modified' => '2016-01-25 19:09:11',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '62e845d8-0a5e-341a-a996-5b3ec4e9ce00',
			'aco' => 'Resource',
			'aco_foreign_key' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'aro' => 'User',
			'aro_foreign_key' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'type' => '15',
			'created' => '2016-01-25 19:09:11',
			'modified' => '2016-01-25 19:09:11',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '69a9730f-b224-3994-a061-68f0809bf316',
			'aco' => 'Resource',
			'aco_foreign_key' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'aro' => 'User',
			'aro_foreign_key' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'type' => '15',
			'created' => '2016-01-25 19:09:11',
			'modified' => '2016-01-25 19:09:11',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6fb3cc8b-3b64-3651-a2f8-5ca8e2d3d56c',
			'aco' => 'Resource',
			'aco_foreign_key' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'aro' => 'User',
			'aro_foreign_key' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'type' => '15',
			'created' => '2016-01-25 19:09:11',
			'modified' => '2016-01-25 19:09:11',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a04b51d4-01d5-3ed5-ac68-e11b29d4143d',
			'aco' => 'Resource',
			'aco_foreign_key' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'aro' => 'User',
			'aro_foreign_key' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'type' => '15',
			'created' => '2016-01-25 19:09:11',
			'modified' => '2016-01-25 19:09:11',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b04eb320-2ffd-33ce-a3b4-db8b91837c11',
			'aco' => 'Resource',
			'aco_foreign_key' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'aro' => 'User',
			'aro_foreign_key' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'type' => '15',
			'created' => '2016-01-25 19:09:11',
			'modified' => '2016-01-25 19:09:11',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'be96b691-259e-3ef0-a258-83a601cf75db',
			'aco' => 'Resource',
			'aco_foreign_key' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'aro' => 'User',
			'aro_foreign_key' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'type' => '15',
			'created' => '2016-01-25 19:09:11',
			'modified' => '2016-01-25 19:09:11',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f0f49345-e86f-3a43-a6e4-d4b265c8e0d5',
			'aco' => 'Resource',
			'aco_foreign_key' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'aro' => 'User',
			'aro_foreign_key' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'type' => '15',
			'created' => '2016-01-25 19:09:11',
			'modified' => '2016-01-25 19:09:11',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f67447e7-5027-3851-ada8-1c398667b6f3',
			'aco' => 'Resource',
			'aco_foreign_key' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'aro' => 'User',
			'aro_foreign_key' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'type' => '15',
			'created' => '2016-01-25 19:09:11',
			'modified' => '2016-01-25 19:09:11',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}

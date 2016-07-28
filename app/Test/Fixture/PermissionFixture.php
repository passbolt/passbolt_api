<?php
/**
 * Permission Fixture
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
			'id' => '0738294d-14a5-3a31-a58f-158e3789ea79',
			'aco' => 'Category',
			'aco_foreign_key' => '42d58fc0-b65f-30ed-a32d-2ac2d0593ae0',
			'aro' => 'User',
			'aro_foreign_key' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'type' => '3',
			'created' => '2016-07-28 18:12:36',
			'modified' => '2016-07-28 18:12:36',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
			'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => '08967391-5cea-3851-a159-6a384c9ddce0',
			'aco' => 'Category',
			'aco_foreign_key' => 'a27bf9d2-84f4-3665-a720-a5e699ce10d0',
			'aro' => 'User',
			'aro_foreign_key' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'type' => '1',
			'created' => '2016-07-28 18:12:36',
			'modified' => '2016-07-28 18:12:36',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
			'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => '09a37f1b-993b-3338-a972-c2c25b68eb2a',
			'aco' => 'Resource',
			'aco_foreign_key' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'aro' => 'Group',
			'aro_foreign_key' => '854dce19-6f1b-39bd-acbb-94d9a39b007f',
			'type' => '7',
			'created' => '2016-07-28 18:12:36',
			'modified' => '2016-07-28 18:12:36',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
			'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => '0a552e71-a000-3f16-a3f8-bed186422865',
			'aco' => 'Category',
			'aco_foreign_key' => '97883247-36a5-3656-affc-5ce8040eb2cc',
			'aro' => 'Group',
			'aro_foreign_key' => '854dce19-6f1b-39bd-acbb-94d9a39b007f',
			'type' => '1',
			'created' => '2016-07-28 18:12:36',
			'modified' => '2016-07-28 18:12:36',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
			'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => '127ecc20-3f70-3d31-aca8-faaa2cd3d5fc',
			'aco' => 'Resource',
			'aco_foreign_key' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'aro' => 'User',
			'aro_foreign_key' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'type' => '15',
			'created' => '2016-07-28 18:12:36',
			'modified' => '2016-07-28 18:12:36',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2baf5782-7204-3c4f-ae0c-1e340b035069',
			'aco' => 'Category',
			'aco_foreign_key' => '44eafde0-f430-3958-aeaa-56916202bad2',
			'aro' => 'User',
			'aro_foreign_key' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'type' => '15',
			'created' => '2016-07-28 18:12:36',
			'modified' => '2016-07-28 18:12:36',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
			'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => '3b55713c-352e-353c-a5f1-666f863e47a8',
			'aco' => 'Resource',
			'aco_foreign_key' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'aro' => 'User',
			'aro_foreign_key' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'type' => '15',
			'created' => '2016-07-28 18:12:36',
			'modified' => '2016-07-28 18:12:36',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3e8d7136-2642-306a-adb6-33ad8dbc437e',
			'aco' => 'Category',
			'aco_foreign_key' => 'e53c5665-060f-3900-a931-fca187d88f63',
			'aro' => 'Group',
			'aro_foreign_key' => '1f33f57a-b65b-3831-a133-236d2f874ef4',
			'type' => '1',
			'created' => '2016-07-28 18:12:36',
			'modified' => '2016-07-28 18:12:36',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
			'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => '42168b81-ca4c-3c34-afcb-26c0dd813ace',
			'aco' => 'Category',
			'aco_foreign_key' => '7a7268fa-44cb-30f9-a943-644734657155',
			'aro' => 'User',
			'aro_foreign_key' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'type' => '15',
			'created' => '2016-07-28 18:12:36',
			'modified' => '2016-07-28 18:12:36',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
			'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => '42927bd1-a795-3705-acd8-2f909992f26c',
			'aco' => 'Category',
			'aco_foreign_key' => '865fc6b3-e566-3018-a8c3-14cfd1939c09',
			'aro' => 'Group',
			'aro_foreign_key' => '6d79c7f7-80d4-352b-a294-e97b32363c84',
			'type' => '1',
			'created' => '2016-07-28 18:12:36',
			'modified' => '2016-07-28 18:12:36',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
			'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => '460a3896-b273-36dc-a349-8b2c810b932b',
			'aco' => 'Resource',
			'aco_foreign_key' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'aro' => 'User',
			'aro_foreign_key' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'type' => '15',
			'created' => '2016-07-28 18:12:36',
			'modified' => '2016-07-28 18:12:36',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4c6964f9-6c0e-354b-a105-cb0f73ad6831',
			'aco' => 'Category',
			'aco_foreign_key' => '207b1b41-3db0-30f8-a8ed-ae05e9c38e72',
			'aro' => 'User',
			'aro_foreign_key' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'type' => '15',
			'created' => '2016-07-28 18:12:36',
			'modified' => '2016-07-28 18:12:36',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
			'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => '51b6b7ef-f2d8-3ffc-ab6f-d212eb38d6dc',
			'aco' => 'Resource',
			'aco_foreign_key' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'aro' => 'User',
			'aro_foreign_key' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'type' => '1',
			'created' => '2016-07-28 18:12:36',
			'modified' => '2016-07-28 18:12:36',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
			'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => '536492d7-658f-3e4c-af29-f2da79dc7236',
			'aco' => 'Resource',
			'aco_foreign_key' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'aro' => 'User',
			'aro_foreign_key' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'type' => '15',
			'created' => '2016-07-28 18:12:36',
			'modified' => '2016-07-28 18:12:36',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '590053ae-915a-36c6-a304-e5fde260b319',
			'aco' => 'Resource',
			'aco_foreign_key' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'aro' => 'User',
			'aro_foreign_key' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'type' => '15',
			'created' => '2016-07-28 18:12:36',
			'modified' => '2016-07-28 18:12:36',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '598e8b32-13f9-3962-a0dd-6e3afe3e9fa5',
			'aco' => 'Resource',
			'aco_foreign_key' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'aro' => 'User',
			'aro_foreign_key' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'type' => '15',
			'created' => '2016-07-28 18:12:36',
			'modified' => '2016-07-28 18:12:36',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '60bdbf7f-7e1a-3cf2-a2f6-1e9b2a33d31f',
			'aco' => 'Category',
			'aco_foreign_key' => '99f11a24-9f57-3d62-af7f-3bd1e47b0096',
			'aro' => 'User',
			'aro_foreign_key' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'type' => '15',
			'created' => '2016-07-28 18:12:36',
			'modified' => '2016-07-28 18:12:36',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
			'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => '6263b37b-048b-3ac1-a7d7-36fa1dde22f4',
			'aco' => 'Category',
			'aco_foreign_key' => '2a760295-51f1-3a1e-a4c3-d5a9b227cb6e',
			'aro' => 'Group',
			'aro_foreign_key' => '563a4b8f-e7ac-31ff-a3e8-f1b6d3ff222a',
			'type' => '1',
			'created' => '2016-07-28 18:12:36',
			'modified' => '2016-07-28 18:12:36',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
			'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => '6febb270-24b6-39cc-a618-76cd88446a30',
			'aco' => 'Category',
			'aco_foreign_key' => '207b1b41-3db0-30f8-a8ed-ae05e9c38e72',
			'aro' => 'Group',
			'aro_foreign_key' => 'bdd0bf4c-a8e3-3d1a-aa79-5d9e9e46edb8',
			'type' => '1',
			'created' => '2016-07-28 18:12:36',
			'modified' => '2016-07-28 18:12:36',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
			'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => '72314375-3837-383f-a4a8-81b712b58e65',
			'aco' => 'Resource',
			'aco_foreign_key' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'aro' => 'User',
			'aro_foreign_key' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'type' => '15',
			'created' => '2016-07-28 18:12:36',
			'modified' => '2016-07-28 18:12:36',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7b853468-f7a4-3ec5-abdb-875c2c2a92ba',
			'aco' => 'Resource',
			'aco_foreign_key' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'aro' => 'User',
			'aro_foreign_key' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'type' => '15',
			'created' => '2016-07-28 18:12:36',
			'modified' => '2016-07-28 18:12:36',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a8439553-6501-301a-afb7-9e4657860bab',
			'aco' => 'Resource',
			'aco_foreign_key' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'aro' => 'User',
			'aro_foreign_key' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'type' => '15',
			'created' => '2016-07-28 18:12:36',
			'modified' => '2016-07-28 18:12:36',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a9cd915a-109f-31dd-af14-89b1859d0117',
			'aco' => 'Resource',
			'aco_foreign_key' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'aro' => 'User',
			'aro_foreign_key' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'type' => '0',
			'created' => '2016-07-28 18:12:36',
			'modified' => '2016-07-28 18:12:36',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
			'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => 'b04eb320-2ffd-33ce-a3b4-db8b91837c11',
			'aco' => 'Resource',
			'aco_foreign_key' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'aro' => 'User',
			'aro_foreign_key' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'type' => '15',
			'created' => '2016-07-28 18:12:36',
			'modified' => '2016-07-28 18:12:36',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b2a69ba2-8da0-3d3e-ac39-e4b67d5a597c',
			'aco' => 'Resource',
			'aco_foreign_key' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'aro' => 'User',
			'aro_foreign_key' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'type' => '15',
			'created' => '2016-07-28 18:12:36',
			'modified' => '2016-07-28 18:12:36',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bde82dd5-1510-3681-a8e9-496ed8c45c14',
			'aco' => 'Category',
			'aco_foreign_key' => 'd022b078-b7ae-37a7-a0c2-46e82d9d630e',
			'aro' => 'Group',
			'aro_foreign_key' => '1f33f57a-b65b-3831-a133-236d2f874ef4',
			'type' => '7',
			'created' => '2016-07-28 18:12:36',
			'modified' => '2016-07-28 18:12:36',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
			'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => 'ca6a30a4-f24a-307f-ad19-b7cf188e3c06',
			'aco' => 'Category',
			'aco_foreign_key' => '7914592d-6919-3f02-a9c9-e23f92d36237',
			'aro' => 'Group',
			'aro_foreign_key' => 'b9102683-f94d-3c94-a6de-1ff09763046b',
			'type' => '7',
			'created' => '2016-07-28 18:12:36',
			'modified' => '2016-07-28 18:12:36',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
			'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => 'd211722f-e1f7-38cc-a9ad-6d677ac75a40',
			'aco' => 'Category',
			'aco_foreign_key' => '7914592d-6919-3f02-a9c9-e23f92d36237',
			'aro' => 'User',
			'aro_foreign_key' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'type' => '0',
			'created' => '2016-07-28 18:12:36',
			'modified' => '2016-07-28 18:12:36',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
			'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => 'd2758447-e7db-399c-a62b-3e9b36875bd0',
			'aco' => 'Category',
			'aco_foreign_key' => '99f11a24-9f57-3d62-af7f-3bd1e47b0096',
			'aro' => 'Group',
			'aro_foreign_key' => '14153251-abcb-3c00-a2d1-b4fdb1423d26',
			'type' => '15',
			'created' => '2016-07-28 18:12:36',
			'modified' => '2016-07-28 18:12:36',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
			'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => 'd4778493-43bf-3bec-a280-9a4800138b57',
			'aco' => 'Category',
			'aco_foreign_key' => 'b0219d7d-7c67-33c0-af6f-1f95a4e63742',
			'aro' => 'Group',
			'aro_foreign_key' => 'f8ec9e82-5709-346a-a157-65bb7c11b34a',
			'type' => '1',
			'created' => '2016-07-28 18:12:36',
			'modified' => '2016-07-28 18:12:36',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
			'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => 'de90aea0-147f-397c-ae90-205220bced82',
			'aco' => 'Resource',
			'aco_foreign_key' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'aro' => 'Group',
			'aro_foreign_key' => '854dce19-6f1b-39bd-acbb-94d9a39b007f',
			'type' => '0',
			'created' => '2016-07-28 18:12:36',
			'modified' => '2016-07-28 18:12:36',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
			'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => 'f88bd9a7-2e0d-380b-a08a-c2c8fa62d2c6',
			'aco' => 'Resource',
			'aco_foreign_key' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'aro' => 'User',
			'aro_foreign_key' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'type' => '15',
			'created' => '2016-07-28 18:12:36',
			'modified' => '2016-07-28 18:12:36',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f965fea1-a026-3400-a725-fc42890a7b02',
			'aco' => 'Resource',
			'aco_foreign_key' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'aro' => 'User',
			'aro_foreign_key' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'type' => '15',
			'created' => '2016-07-28 18:12:36',
			'modified' => '2016-07-28 18:12:36',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fef3e9e1-77c7-359d-a2a5-a81702bb1785',
			'aco' => 'Resource',
			'aco_foreign_key' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'aro' => 'User',
			'aro_foreign_key' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'type' => '15',
			'created' => '2016-07-28 18:12:36',
			'modified' => '2016-07-28 18:12:36',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}

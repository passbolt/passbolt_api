<?php
/**
 * CategoriesResource Fixture
 */
class CategoriesResourceFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'category_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'index', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'resource_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'index', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'created_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'category_id' => array('column' => array('category_id', 'resource_id'), 'unique' => 0),
			'categories' => array('column' => 'category_id', 'unique' => 0),
			'resources' => array('column' => 'resource_id', 'unique' => 0)
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
			'id' => '50d77ff8-3fd4-4e62-8159-1b63d7a10fce',
			'category_id' => '2a760295-51f1-3a1e-a4c3-d5a9b227cb6e',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'created' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50d77ff9-beb4-4398-90fd-1b63d7a10fce',
			'category_id' => '4960010a-b8ba-3872-a9f4-ba503051d46c',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'created' => '2012-12-24 03:34:41',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50d77ff9-dc9c-442e-a5e5-1b63d7a10fce',
			'category_id' => '25fedb7f-25ba-381d-a08a-f887663c515c',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'created' => '2012-12-24 03:34:41',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50d77ffa-37e0-4672-b31e-1b63d7a10fce',
			'category_id' => 'defe54fd-8e7b-3867-a6b8-73a2d6d78ce5',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'created' => '2012-12-24 03:34:42',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50d77ffa-5a3c-4423-9c38-1b63d7a10fce',
			'category_id' => '7a7268fa-44cb-30f9-a943-644734657155',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'created' => '2012-12-24 03:34:42',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50d77ffb-46f8-4d9a-8dcb-1b63d7a10fce',
			'category_id' => 'a27bf9d2-84f4-3665-a720-a5e699ce10d0',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'created' => '2012-12-24 03:34:43',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50d77ffb-dbdc-44b9-8110-1b63d7a10fce',
			'category_id' => 'a27bf9d2-84f4-3665-a720-a5e699ce10d0',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'created' => '2012-12-24 03:34:43',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50d77ffb-dd48-4397-9440-1b63d7a10fce',
			'category_id' => '96af42e4-cb07-364b-a3bd-25d8f6b1fafc',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'created' => '2012-12-24 03:34:43',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50d77ffd-24c0-4656-bb48-1b63d7a10fce',
			'category_id' => 'e53c5665-060f-3900-a931-fca187d88f63',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'created' => '2012-12-24 03:34:45',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50d77ffd-8f8c-48c8-ab42-1b63d7a10fce',
			'category_id' => 'd022b078-b7ae-37a7-a0c2-46e82d9d630e',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'created' => '2012-12-24 03:34:45',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50d77ffd-a910-4b9d-afd9-1b63d7a10fce',
			'category_id' => 'e53c5665-060f-3900-a931-fca187d88f63',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'created' => '2012-12-24 03:34:45',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50d77ffd-f31c-44be-b36e-1b63d7a10fce',
			'category_id' => 'e53c5665-060f-3900-a931-fca187d88f63',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'created' => '2012-12-24 03:34:45',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50d88eed-f31c-44be-b36e-1b63d7a10fce',
			'category_id' => '5ae0c7ec-8d69-3464-ab48-a8be30f0768e',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'created' => '2013-01-24 03:34:45',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50d99ef8-3fd4-4e62-8159-1b63d7a10fce',
			'category_id' => '6dc848e0-7e30-3669-a130-865c39f5e078',
			'resource_id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'created' => '2016-02-08 17:38:26',
			'created_by' => ''
		),
		array(
			'id' => '9676dedc-d558-35b3-a8dc-bc5729b4e8eb',
			'category_id' => '7a7268fa-44cb-30f9-a943-644734657155',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'created' => '2012-12-24 03:34:42',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}

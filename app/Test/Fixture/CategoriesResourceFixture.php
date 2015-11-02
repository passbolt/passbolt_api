<?php
/**
 * CategoriesResourceFixture
 *
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
			'category_id' => '50d77ff8-40ec-451a-b96e-1b63d7a10fce',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'created' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50d77ff9-beb4-4398-90fd-1b63d7a10fce',
			'category_id' => '50d77ff8-9084-4f21-bc2f-1b63d7a10fce',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'created' => '2012-12-24 03:34:41',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50d77ff9-dc9c-442e-a5e5-1b63d7a10fce',
			'category_id' => '50d77ff9-42d8-43d5-beee-1b63d7a10fce',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'created' => '2012-12-24 03:34:41',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50d77ffa-37e0-4672-b31e-1b63d7a10fce',
			'category_id' => '50d77ff9-98f0-4378-9b7a-1b63d7a10fce',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'created' => '2012-12-24 03:34:42',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50d77ffa-45fc-423c-a5b1-1b63d7a10fce',
			'category_id' => '50d77ffa-4030-49e1-990d-1b63d7a10fce',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'created' => '2012-12-24 03:34:42',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50d77ffa-5a3c-4423-9c38-1b63d7a10fce',
			'category_id' => '50d77ffa-4030-49e1-990d-1b63d7a10fce',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'created' => '2012-12-24 03:34:42',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50d77ffb-46f8-4d9a-8dcb-1b63d7a10fce',
			'category_id' => '50d77ffa-c25c-4d92-aa35-1b63d7a10fce',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'created' => '2012-12-24 03:34:43',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50d77ffb-dbdc-44b9-8110-1b63d7a10fce',
			'category_id' => '50d77ffa-c25c-4d92-aa35-1b63d7a10fce',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'created' => '2012-12-24 03:34:43',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50d77ffb-dd48-4397-9440-1b63d7a10fce',
			'category_id' => '50d77ffb-8008-42d2-8811-1b63d7a10fce',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'created' => '2012-12-24 03:34:43',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50d77ffd-24c0-4656-bb48-1b63d7a10fce',
			'category_id' => '50d77ffc-0414-49dd-9959-1b63d7a10fce',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'created' => '2012-12-24 03:34:45',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50d77ffd-8f8c-48c8-ab42-1b63d7a10fce',
			'category_id' => '50d77ffd-cf28-460e-b35e-1b63d7a10fce',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'created' => '2012-12-24 03:34:45',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50d77ffd-a910-4b9d-afd9-1b63d7a10fce',
			'category_id' => '50d77ffc-0414-49dd-9959-1b63d7a10fce',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'created' => '2012-12-24 03:34:45',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50d77ffd-f31c-44be-b36e-1b63d7a10fce',
			'category_id' => '50d77ffc-0414-49dd-9959-1b63d7a10fce',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'created' => '2012-12-24 03:34:45',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50d88eed-f31c-44be-b36e-1b63d7a10fce',
			'category_id' => '50d77ffc-8608-422a-8456-1b63d7a10fce',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'created' => '2013-01-24 03:34:45',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50d99ef8-3fd4-4e62-8159-1b63d7a10fce',
			'category_id' => '10d11ff2-5208-4dc2-94d1-1b63d7a10fce',
			'resource_id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'created' => '2015-11-02 09:43:20',
			'created_by' => ''
		),
	);

}

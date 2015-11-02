<?php
/**
 * ResourceFixture
 *
 */
class ResourceFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 64, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'username' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 64, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'expiry_date' => array('type' => 'timestamp', 'null' => true, 'default' => null),
		'uri' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
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
			'id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'name' => 'cpp2-pwd2',
			'username' => 'admin',
			'expiry_date' => null,
			'uri' => 'http://cake.project2.net/',
			'description' => 'cpp2-pwd2 description',
			'deleted' => 0,
			'created' => '2012-12-24 03:34:43',
			'modified' => '2012-12-24 03:34:43',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'name' => 'salesforce account',
			'username' => 'passbolt',
			'expiry_date' => null,
			'uri' => 'https://salesforce.com',
			'description' => 'salesforce account description',
			'deleted' => 0,
			'created' => '2012-12-24 03:34:41',
			'modified' => '2012-12-24 03:34:41',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'name' => 'tetris license',
			'username' => 'passbolt',
			'expiry_date' => null,
			'uri' => 'https://tetris.com',
			'description' => 'tetris license description',
			'deleted' => 0,
			'created' => '2012-12-24 03:34:41',
			'modified' => '2012-12-24 03:34:41',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'name' => 'cpp1-pwd2',
			'username' => 'admin',
			'expiry_date' => null,
			'uri' => 'http://cake.project1.net/',
			'description' => 'cpp1-pwd2 description',
			'deleted' => 0,
			'created' => '2012-12-24 03:34:42',
			'modified' => '2012-12-24 03:34:42',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'name' => 'utest1-pwd1',
			'username' => 'unitTest1',
			'expiry_date' => null,
			'uri' => 'https://unit-test.com',
			'description' => 'description',
			'deleted' => 0,
			'created' => '2015-11-02 13:07:22',
			'modified' => '2015-11-02 13:07:22',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'name' => 'dp1-pwd1',
			'username' => 'admin',
			'expiry_date' => null,
			'uri' => 'http://drupal.project1.net/',
			'description' => 'dp1-pwd1 description',
			'deleted' => 0,
			'created' => '2012-12-24 03:34:43',
			'modified' => '2012-12-24 03:34:43',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'name' => 'cpp2-pwd1',
			'username' => 'admin',
			'expiry_date' => null,
			'uri' => 'http://cake.project2.net/',
			'description' => 'cpp2-pwd1 description',
			'deleted' => 0,
			'created' => '2012-12-24 03:34:43',
			'modified' => '2012-12-24 03:34:43',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'name' => 'bank password',
			'username' => 'passbolt',
			'expiry_date' => null,
			'uri' => 'https://bank.com',
			'description' => 'bank password description',
			'deleted' => 0,
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123'
		),
		array(
			'id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'name' => 'dp2-pwd1',
			'username' => 'admin',
			'expiry_date' => null,
			'uri' => 'http://drupal.project1.net/',
			'description' => 'dp2-pwd1 description',
			'deleted' => 0,
			'created' => '2012-12-24 03:34:43',
			'modified' => '2012-12-24 03:34:43',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'name' => 'facebook account',
			'username' => 'passbolt',
			'expiry_date' => null,
			'uri' => 'https://facebook.com',
			'description' => 'facebook account description',
			'deleted' => 0,
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123'
		),
		array(
			'id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'name' => 'op1-pwd1',
			'username' => 'admin',
			'expiry_date' => null,
			'uri' => 'http://other.project1.net/',
			'description' => 'op1-pwd1 description',
			'deleted' => 0,
			'created' => '2012-12-24 03:34:45',
			'modified' => '2012-12-24 03:34:45',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'name' => 'op1-pwd2',
			'username' => 'admin',
			'expiry_date' => null,
			'uri' => 'http://other.project1.net/',
			'description' => 'op1-pwd2 description',
			'deleted' => 0,
			'created' => '2012-12-24 03:34:45',
			'modified' => '2012-12-24 03:34:45',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'name' => 'cpp1-pwd1',
			'username' => 'admin',
			'expiry_date' => null,
			'uri' => 'http://cake.project1.net/',
			'description' => 'description',
			'deleted' => 0,
			'created' => '2012-12-24 03:34:42',
			'modified' => '2012-12-24 03:34:42',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'name' => 'shared resource',
			'username' => 'admin',
			'expiry_date' => null,
			'uri' => 'http://shared.resource.net/',
			'description' => 'shared resource description',
			'deleted' => 0,
			'created' => '2012-12-24 03:34:45',
			'modified' => '2012-12-24 03:34:45',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}

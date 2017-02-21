<?php
/**
 * Group Fixture
 */
class GroupFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
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
			'id' => '14153251-abcb-3c00-a2d1-b4fdb1423d26',
			'name' => 'management',
			'deleted' => 0,
			'created' => '2012-12-17 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1f33f57a-b65b-3831-a133-236d2f874ef4',
			'name' => 'company a',
			'deleted' => 0,
			'created' => '2012-12-17 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '563a4b8f-e7ac-31ff-a3e8-f1b6d3ff222a',
			'name' => 'accounting dpt',
			'deleted' => 0,
			'created' => '2012-12-17 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6d79c7f7-80d4-352b-a294-e97b32363c84',
			'name' => 'developers drupal',
			'deleted' => 0,
			'created' => '2012-12-17 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '854dce19-6f1b-39bd-acbb-94d9a39b007f',
			'name' => 'human resources',
			'deleted' => 0,
			'created' => '2012-12-17 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9d8c162f-c5a1-3fc8-aadc-d652a7352dbb',
			'name' => 'company b',
			'deleted' => 0,
			'created' => '2012-12-17 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b9102683-f94d-3c94-a6de-1ff09763046b',
			'name' => 'developers team leads',
			'deleted' => 0,
			'created' => '2012-12-17 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bdd0bf4c-a8e3-3d1a-aa79-5d9e9e46edb8',
			'name' => 'freelancers',
			'deleted' => 0,
			'created' => '2012-12-17 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e037cffd-482f-3787-a30c-6929adc74079',
			'name' => 'developers',
			'deleted' => 0,
			'created' => '2012-12-17 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e7c2be86-c882-30ad-a3b2-d7387936c826',
			'name' => 'no privilege',
			'deleted' => 0,
			'created' => '2016-02-02 18:59:05',
			'modified' => '2016-02-02 18:59:05',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f8ec9e82-5709-346a-a157-65bb7c11b34a',
			'name' => 'developers cakephp',
			'deleted' => 0,
			'created' => '2012-12-17 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fa2fcd80-cf04-327b-ae18-8c1c5e81929e',
			'name' => 'Users',
			'deleted' => 0,
			'created' => '2012-12-17 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fa2fcd80-cf04-328a-ae18-8c1c5e81929e',
			'name' => 'deleted group',
			'deleted' => 1,
			'created' => '2012-12-17 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}

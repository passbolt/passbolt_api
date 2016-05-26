<?php
/**
 * Category Fixture
 */
class CategoryFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'parent_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'lft' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => false),
		'rght' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => false),
		'name' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'category_type_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'comment' => 'type id of the category', 'charset' => 'utf8'),
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
			'id' => '207b1b41-3db0-30f8-a8ed-ae05e9c38e72',
			'parent_id' => '7914592d-6919-3f02-a9c9-e23f92d36237',
			'lft' => '29',
			'rght' => '34',
			'name' => 'others',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:44',
			'modified' => '2012-12-24 03:34:44',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '25fedb7f-25ba-381d-a08a-f887663c515c',
			'parent_id' => '97883247-36a5-3656-affc-5ce8040eb2cc',
			'lft' => '9',
			'rght' => '10',
			'name' => 'human resource',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:41',
			'modified' => '2012-12-24 03:34:41',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2a760295-51f1-3a1e-a4c3-d5a9b227cb6e',
			'parent_id' => '97883247-36a5-3656-affc-5ce8040eb2cc',
			'lft' => '5',
			'rght' => '6',
			'name' => 'accounts',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3b7a2fb2-7c5e-353c-a1fa-e543f2a7cdf4',
			'parent_id' => 'b0219d7d-7c67-33c0-af6f-1f95a4e63742',
			'lft' => '20',
			'rght' => '21',
			'name' => 'cp-project3',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:43',
			'modified' => '2012-12-24 03:34:43',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '42d58fc0-b65f-30ed-a32d-2ac2d0593ae0',
			'parent_id' => null,
			'lft' => '39',
			'rght' => '40',
			'name' => 'pv-jean_bartik',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2014-05-06 03:35:39',
			'modified' => '2014-05-06 03:35:39',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '44eafde0-f430-3958-aeaa-56916202bad2',
			'parent_id' => null,
			'lft' => '41',
			'rght' => '46',
			'name' => 'utest',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2016-05-26 12:40:11',
			'modified' => '2016-05-26 12:40:11',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4960010a-b8ba-3872-a9f4-ba503051d46c',
			'parent_id' => '97883247-36a5-3656-affc-5ce8040eb2cc',
			'lft' => '7',
			'rght' => '8',
			'name' => 'marketing',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5ae0c7ec-8d69-3464-ab48-a8be30f0768e',
			'parent_id' => '865fc6b3-e566-3018-a8c3-14cfd1939c09',
			'lft' => '26',
			'rght' => '27',
			'name' => 'd-project2',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:44',
			'modified' => '2012-12-24 03:34:44',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6dc848e0-7e30-3669-a130-865c39f5e078',
			'parent_id' => '44eafde0-f430-3958-aeaa-56916202bad2',
			'lft' => '42',
			'rght' => '43',
			'name' => 'utest1',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2016-05-26 12:40:11',
			'modified' => '2016-05-26 12:40:11',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7914592d-6919-3f02-a9c9-e23f92d36237',
			'parent_id' => '99f11a24-9f57-3d62-af7f-3bd1e47b0096',
			'lft' => '14',
			'rght' => '35',
			'name' => 'projects',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:42',
			'modified' => '2012-12-24 03:34:42',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7a7268fa-44cb-30f9-a943-644734657155',
			'parent_id' => 'b0219d7d-7c67-33c0-af6f-1f95a4e63742',
			'lft' => '16',
			'rght' => '17',
			'name' => 'cp-project1',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:42',
			'modified' => '2012-12-24 03:34:42',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7fc93ce3-d735-3267-aa89-9aeacf04e710',
			'parent_id' => '99f11a24-9f57-3d62-af7f-3bd1e47b0096',
			'lft' => '2',
			'rght' => '3',
			'name' => 'empty',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2014-03-18 03:34:39',
			'modified' => '2014-03-18 03:34:39',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '865fc6b3-e566-3018-a8c3-14cfd1939c09',
			'parent_id' => '7914592d-6919-3f02-a9c9-e23f92d36237',
			'lft' => '23',
			'rght' => '28',
			'name' => 'drupal',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:43',
			'modified' => '2012-12-24 03:34:43',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '96af42e4-cb07-364b-a3bd-25d8f6b1fafc',
			'parent_id' => '865fc6b3-e566-3018-a8c3-14cfd1939c09',
			'lft' => '24',
			'rght' => '25',
			'name' => 'd-project1',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:43',
			'modified' => '2012-12-24 03:34:43',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '97883247-36a5-3656-affc-5ce8040eb2cc',
			'parent_id' => '99f11a24-9f57-3d62-af7f-3bd1e47b0096',
			'lft' => '4',
			'rght' => '13',
			'name' => 'administration',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:39',
			'modified' => '2012-12-24 03:34:39',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '99f11a24-9f57-3d62-af7f-3bd1e47b0096',
			'parent_id' => null,
			'lft' => '1',
			'rght' => '38',
			'name' => 'Bolt Softwares Pvt. Ltd.',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:39',
			'modified' => '2012-12-24 03:34:39',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a13b74a7-4585-3b4b-a49e-44b79c77993b',
			'parent_id' => '44eafde0-f430-3958-aeaa-56916202bad2',
			'lft' => '44',
			'rght' => '45',
			'name' => 'utest2',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2016-05-26 12:40:11',
			'modified' => '2016-05-26 12:40:11',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a27bf9d2-84f4-3665-a720-a5e699ce10d0',
			'parent_id' => 'b0219d7d-7c67-33c0-af6f-1f95a4e63742',
			'lft' => '18',
			'rght' => '19',
			'name' => 'cp-project2',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:42',
			'modified' => '2012-12-24 03:34:42',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'abf3d425-7c97-3ffa-a8a9-fcd95bdc49b0',
			'parent_id' => '99f11a24-9f57-3d62-af7f-3bd1e47b0096',
			'lft' => '36',
			'rght' => '37',
			'name' => 'private',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2014-05-06 03:34:39',
			'modified' => '2014-05-06 03:34:39',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b0219d7d-7c67-33c0-af6f-1f95a4e63742',
			'parent_id' => '7914592d-6919-3f02-a9c9-e23f92d36237',
			'lft' => '15',
			'rght' => '22',
			'name' => 'cakephp',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:42',
			'modified' => '2012-12-24 03:34:42',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd022b078-b7ae-37a7-a0c2-46e82d9d630e',
			'parent_id' => '207b1b41-3db0-30f8-a8ed-ae05e9c38e72',
			'lft' => '32',
			'rght' => '33',
			'name' => 'o-project2',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:45',
			'modified' => '2012-12-24 03:34:45',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'defe54fd-8e7b-3867-a6b8-73a2d6d78ce5',
			'parent_id' => '97883247-36a5-3656-affc-5ce8040eb2cc',
			'lft' => '11',
			'rght' => '12',
			'name' => 'misc',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:41',
			'modified' => '2012-12-24 03:34:41',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e53c5665-060f-3900-a931-fca187d88f63',
			'parent_id' => '207b1b41-3db0-30f8-a8ed-ae05e9c38e72',
			'lft' => '30',
			'rght' => '31',
			'name' => 'o-project1',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:44',
			'modified' => '2012-12-24 03:34:44',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}

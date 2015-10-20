<?php
/**
 * GroupFixture
 *
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
			'id' => '10ce2d3a-0468-433b-b59f-3053d7a10fce',
			'name' => 'management',
			'deleted' => 0,
			'created' => '2012-12-17 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '10d56042-c5cd-11e1-a0c5-080877796c4c',
			'name' => 'Users',
			'deleted' => 0,
			'created' => '2012-12-17 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '20ce3d3a-0468-433b-b59f-3053d7a10fce',
			'name' => 'accounting dpt',
			'deleted' => 0,
			'created' => '2012-12-17 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '30ce2d3a-0468-4334-b59f-3053d7a10fce',
			'name' => 'human resources',
			'deleted' => 0,
			'created' => '2012-12-17 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '40ce2d3a-0468-423b-b59f-3053d7a10fce',
			'name' => 'developers',
			'deleted' => 0,
			'created' => '2012-12-17 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5ad56042-c5cd-11e1-a0c5-080027796c4c',
			'name' => 'developers team leads',
			'deleted' => 0,
			'created' => '2012-12-17 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6bd58742-c5cd-11e1-a0c5-080027796c4c',
			'name' => 'developers drupal',
			'deleted' => 0,
			'created' => '2012-12-17 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6bd58742-c5cd-11e1-a0c5-080027796ce7',
			'name' => 'developers cakephp',
			'deleted' => 0,
			'created' => '2012-12-17 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6bd58742-c5cd-11e1-a0c6-080127896ce7',
			'name' => 'freelancers',
			'deleted' => 0,
			'created' => '2012-12-17 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8bd56042-d9cd-11e1-a0c5-080027796c4c',
			'name' => 'company a',
			'deleted' => 0,
			'created' => '2012-12-17 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9bd56042-c09d-11e1-a0c5-080027796c4c',
			'name' => 'company b',
			'deleted' => 0,
			'created' => '2012-12-17 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}

<?php
/**
 * CategoryFixture
 *
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
		'lft' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'key' => 'index'),
		'rght' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'name' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'category_type_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'comment' => 'type id of the category', 'charset' => 'utf8'),
		'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'created_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'modified_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'lft_rght' => array('column' => array('lft', 'rght'), 'unique' => 1)
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
			'id' => '10d11ff1-5208-4dc2-94d1-1b63d7a10fce',
			'parent_id' => null,
			'lft' => '35',
			'rght' => '40',
			'name' => 'utest',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2013-08-01 12:22:01',
			'modified' => '2013-08-01 12:22:01',
			'created_by' => '',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '10d11ff2-5208-4dc2-94d1-1b63d7a10fce',
			'parent_id' => '10d11ff1-5208-4dc2-94d1-1b63d7a10fce',
			'lft' => '36',
			'rght' => '37',
			'name' => 'utest1',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2013-08-01 12:22:01',
			'modified' => '2013-08-01 12:22:01',
			'created_by' => '',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '10d11ff3-5208-4dc2-94d1-1b63d7a10fce',
			'parent_id' => '10d11ff1-5208-4dc2-94d1-1b63d7a10fce',
			'lft' => '38',
			'rght' => '39',
			'name' => 'utest2',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2013-08-01 12:22:01',
			'modified' => '2013-08-01 12:22:01',
			'created_by' => '',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '50d77ff7-5208-4dc2-94d1-1b63d7a10fce',
			'parent_id' => null,
			'lft' => '1',
			'rght' => '34',
			'name' => 'Bolt Softwares Pvt. Ltd.',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:39',
			'modified' => '2012-12-24 03:34:39',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '50d77ff7-bcac-4c03-8687-1b63d7a10fce',
			'parent_id' => '50d77ff7-5208-4dc2-94d1-1b63d7a10fce',
			'lft' => '2',
			'rght' => '11',
			'name' => 'administration',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:39',
			'modified' => '2012-12-24 03:34:39',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '50d77ff8-40ec-451a-b96e-1b63d7a10fce',
			'parent_id' => '50d77ff7-bcac-4c03-8687-1b63d7a10fce',
			'lft' => '3',
			'rght' => '4',
			'name' => 'accounts',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '50d77ff8-9084-4f21-bc2f-1b63d7a10fce',
			'parent_id' => '50d77ff7-bcac-4c03-8687-1b63d7a10fce',
			'lft' => '5',
			'rght' => '6',
			'name' => 'marketing',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '50d77ff9-42d8-43d5-beee-1b63d7a10fce',
			'parent_id' => '50d77ff7-bcac-4c03-8687-1b63d7a10fce',
			'lft' => '7',
			'rght' => '8',
			'name' => 'human resource',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:41',
			'modified' => '2012-12-24 03:34:41',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '50d77ff9-98f0-4378-9b7a-1b63d7a10fce',
			'parent_id' => '50d77ff7-bcac-4c03-8687-1b63d7a10fce',
			'lft' => '9',
			'rght' => '10',
			'name' => 'misc',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:41',
			'modified' => '2012-12-24 03:34:41',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '50d77ffa-094c-4d4c-9dd7-1b63d7a10fce',
			'parent_id' => '50d77ff7-5208-4dc2-94d1-1b63d7a10fce',
			'lft' => '12',
			'rght' => '33',
			'name' => 'projects',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:42',
			'modified' => '2012-12-24 03:34:42',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '50d77ffa-4030-49e1-990d-1b63d7a10fce',
			'parent_id' => '50d77ffa-5144-4b95-badd-1b63d7a10fce',
			'lft' => '14',
			'rght' => '15',
			'name' => 'cp-project1',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:42',
			'modified' => '2012-12-24 03:34:42',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '50d77ffa-5144-4b95-badd-1b63d7a10fce',
			'parent_id' => '50d77ffa-094c-4d4c-9dd7-1b63d7a10fce',
			'lft' => '13',
			'rght' => '20',
			'name' => 'cakephp',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:42',
			'modified' => '2012-12-24 03:34:42',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '50d77ffa-c25c-4d92-aa35-1b63d7a10fce',
			'parent_id' => '50d77ffa-5144-4b95-badd-1b63d7a10fce',
			'lft' => '16',
			'rght' => '17',
			'name' => 'cp-project2',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:42',
			'modified' => '2012-12-24 03:34:42',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '50d77ffb-8008-42d2-8811-1b63d7a10fce',
			'parent_id' => '50d77ffb-b9a0-415d-ba6a-1b63d7a10fce',
			'lft' => '22',
			'rght' => '23',
			'name' => 'd-project1',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:43',
			'modified' => '2012-12-24 03:34:43',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '50d77ffb-b9a0-415d-ba6a-1b63d7a10fce',
			'parent_id' => '50d77ffa-094c-4d4c-9dd7-1b63d7a10fce',
			'lft' => '21',
			'rght' => '26',
			'name' => 'drupal',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:43',
			'modified' => '2012-12-24 03:34:43',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '50d77ffb-d488-4217-9e2f-1b63d7a10fce',
			'parent_id' => '50d77ffa-5144-4b95-badd-1b63d7a10fce',
			'lft' => '18',
			'rght' => '19',
			'name' => 'cp-project3',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:43',
			'modified' => '2012-12-24 03:34:43',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '50d77ffc-0414-49dd-9959-1b63d7a10fce',
			'parent_id' => '50d77ffc-08ac-42a8-b185-1b63d7a10fce',
			'lft' => '28',
			'rght' => '29',
			'name' => 'o-project1',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:44',
			'modified' => '2012-12-24 03:34:44',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '50d77ffc-08ac-42a8-b185-1b63d7a10fce',
			'parent_id' => '50d77ffa-094c-4d4c-9dd7-1b63d7a10fce',
			'lft' => '27',
			'rght' => '32',
			'name' => 'others',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:44',
			'modified' => '2012-12-24 03:34:44',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '50d77ffc-8608-422a-8456-1b63d7a10fce',
			'parent_id' => '50d77ffb-b9a0-415d-ba6a-1b63d7a10fce',
			'lft' => '24',
			'rght' => '25',
			'name' => 'd-project2',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:44',
			'modified' => '2012-12-24 03:34:44',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '50d77ffd-cf28-460e-b35e-1b63d7a10fce',
			'parent_id' => '50d77ffc-08ac-42a8-b185-1b63d7a10fce',
			'lft' => '30',
			'rght' => '31',
			'name' => 'o-project2',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:45',
			'modified' => '2012-12-24 03:34:45',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
	);

}

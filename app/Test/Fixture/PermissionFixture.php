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
		'type' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
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
			'id' => '50d82027-05a0-4216-9afc-6dd4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '50d77ffc-08ac-42a8-b185-1b63d7a10fce',
			'aro' => 'User',
			'aro_foreign_key' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'type' => '15',
			'created' => '2012-12-24 14:58:07',
			'modified' => '2012-12-24 14:58:07',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50d82027-0e38-4f7a-b71b-6dd4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '50d77ff8-40ec-451a-b96e-1b63d7a10fce',
			'aro' => 'Group',
			'aro_foreign_key' => '20ce3d3a-0468-433b-b59f-3053d7a10fce',
			'type' => '1',
			'created' => '2012-12-24 14:58:07',
			'modified' => '2012-12-24 14:58:07',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50d82027-3900-4a33-8e06-6dd4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '50d77ffa-4030-49e1-990d-1b63d7a10fce',
			'aro' => 'User',
			'aro_foreign_key' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'type' => '15',
			'created' => '2012-12-24 14:58:07',
			'modified' => '2012-12-24 14:58:07',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50d82027-56dc-4693-9450-6dd4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '50d77ff8-40ec-451a-b96e-1b63d7a10fce',
			'aro' => 'Group',
			'aro_foreign_key' => '30ce2d3a-0468-4334-b59f-3053d7a10fce',
			'type' => '0',
			'created' => '2012-12-24 14:58:07',
			'modified' => '2012-12-24 14:58:07',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50d82027-9a38-4c69-9038-6dd4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '50d77ffb-b9a0-415d-ba6a-1b63d7a10fce',
			'aro' => 'Group',
			'aro_foreign_key' => '6bd58742-c5cd-11e1-a0c5-080027796c4c',
			'type' => '1',
			'created' => '2012-12-24 14:58:07',
			'modified' => '2012-12-24 14:58:07',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50d82027-cfa4-4c01-9950-6dd4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '50d77ffa-5144-4b95-badd-1b63d7a10fce',
			'aro' => 'Group',
			'aro_foreign_key' => '6bd58742-c5cd-11e1-a0c5-080027796ce7',
			'type' => '1',
			'created' => '2012-12-24 14:58:07',
			'modified' => '2012-12-24 14:58:07',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50d82027-d518-40e0-82da-6dd4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '50d77ffa-094c-4d4c-9dd7-1b63d7a10fce',
			'aro' => 'Group',
			'aro_foreign_key' => '5ad56042-c5cd-11e1-a0c5-080027796c4c',
			'type' => '7',
			'created' => '2012-12-24 14:58:07',
			'modified' => '2012-12-24 14:58:07',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50d82027-d884-43cb-aa46-6dd4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '50d77ff7-bcac-4c03-8687-1b63d7a10fce',
			'aro' => 'Group',
			'aro_foreign_key' => '30ce2d3a-0468-4334-b59f-3053d7a10fce',
			'type' => '1',
			'created' => '2012-12-24 14:58:07',
			'modified' => '2012-12-24 14:58:07',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50d82027-dc10-472f-8c6e-6dd4d7a10fce',
			'aco' => 'Resource',
			'aco_foreign_key' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'aro' => 'Group',
			'aro_foreign_key' => '30ce2d3a-0468-4334-b59f-3053d7a10fce',
			'type' => '7',
			'created' => '2012-12-24 14:58:07',
			'modified' => '2012-12-24 14:58:07',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50d82027-e0f0-40be-a981-6dd4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '50d77ff7-5208-4dc2-94d1-1b63d7a10fce',
			'aro' => 'Group',
			'aro_foreign_key' => '10ce2d3a-0468-433b-b59f-3053d7a10fce',
			'type' => '15',
			'created' => '2012-12-24 14:58:07',
			'modified' => '2012-12-24 14:58:07',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50d82028-0ac8-4902-a014-6dd4d7a10fce',
			'aco' => 'Resource',
			'aco_foreign_key' => '50d77ffa-7278-41fc-a4bb-1b63d7a10fce',
			'aro' => 'User',
			'aro_foreign_key' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'type' => '1',
			'created' => '2012-12-24 14:58:08',
			'modified' => '2012-12-24 14:58:08',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50d82028-6868-4638-9d94-6dd4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '50d77ffc-0414-49dd-9959-1b63d7a10fce',
			'aro' => 'Group',
			'aro_foreign_key' => '8bd56042-d9cd-11e1-a0c5-080027796c4c',
			'type' => '1',
			'created' => '2012-12-24 14:58:08',
			'modified' => '2012-12-24 14:58:08',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50d82028-6ca8-44f5-bf06-6dd4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '50d77ffa-094c-4d4c-9dd7-1b63d7a10fce',
			'aro' => 'Group',
			'aro_foreign_key' => '7bd56042-c5cd-11e1-c8c5-080027796c4c',
			'type' => '1',
			'created' => '2012-12-24 14:58:08',
			'modified' => '2012-12-24 14:58:08',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50d82028-74c8-4713-9c2d-6dd4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '50d77ffd-cf28-460e-b35e-1b63d7a10fce',
			'aro' => 'Group',
			'aro_foreign_key' => '8bd56042-d9cd-11e1-a0c5-080027796c4c',
			'type' => '7',
			'created' => '2012-12-24 14:58:08',
			'modified' => '2012-12-24 14:58:08',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50d82028-cf80-4216-a202-6dd4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '50d77ffa-c25c-4d92-aa35-1b63d7a10fce',
			'aro' => 'User',
			'aro_foreign_key' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'type' => '1',
			'created' => '2012-12-24 14:58:08',
			'modified' => '2012-12-24 14:58:08',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c'
		),
	);

}

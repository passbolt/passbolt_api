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
			'id' => '50d79c56-138c-4f5b-84b0-1134d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '50d77ffa-5144-4b95-badd-1b63d7a10fce',
			'aro' => 'Group',
			'aro_foreign_key' => '6bd58742-c5cd-11e1-a0c5-080027796ce7',
			'type' => '1',
			'created' => '2012-12-24 05:35:42',
			'modified' => '2012-12-24 05:35:42',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50d79c56-1e7c-4220-93cb-1134d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '50d77ffa-4030-49e1-990d-1b63d7a10fce',
			'aro' => 'User',
			'aro_foreign_key' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'type' => '15',
			'created' => '2012-12-24 05:35:42',
			'modified' => '2012-12-24 05:35:42',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50d79c56-3acc-4d2a-a059-1134d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '50d77ff8-40ec-451a-b96e-1b63d7a10fce',
			'aro' => 'Group',
			'aro_foreign_key' => '20ce3d3a-0468-433b-b59f-3053d7a10fce',
			'type' => '1',
			'created' => '2012-12-24 05:35:42',
			'modified' => '2012-12-24 05:35:42',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50d79c56-3d88-45c3-96c8-1134d7a10fce',
			'aco' => 'Resource',
			'aco_foreign_key' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'aro' => 'Group',
			'aro_foreign_key' => '30ce2d3a-0468-4334-b59f-3053d7a10fce',
			'type' => '7',
			'created' => '2012-12-24 05:35:42',
			'modified' => '2012-12-24 05:35:42',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50d79c56-5608-4662-8cee-1134d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '50d77ffc-08ac-42a8-b185-1b63d7a10fce',
			'aro' => 'User',
			'aro_foreign_key' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'type' => '15',
			'created' => '2012-12-24 05:35:42',
			'modified' => '2012-12-24 05:35:42',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50d79c56-5614-4719-a4d1-1134d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '50d77ff7-5208-4dc2-94d1-1b63d7a10fce',
			'aro' => 'Group',
			'aro_foreign_key' => '10ce2d3a-0468-433b-b59f-3053d7a10fce',
			'type' => '15',
			'created' => '2012-12-24 05:35:42',
			'modified' => '2012-12-24 05:35:42',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50d79c56-751c-4824-92d6-1134d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '50d77ffa-094c-4d4c-9dd7-1b63d7a10fce',
			'aro' => 'Group',
			'aro_foreign_key' => '5ad56042-c5cd-11e1-a0c5-080027796c4c',
			'type' => '7',
			'created' => '2012-12-24 05:35:42',
			'modified' => '2012-12-24 05:35:42',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50d79c56-7a48-4b15-84f4-1134d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '50d77ffa-c25c-4d92-aa35-1b63d7a10fce',
			'aro' => 'User',
			'aro_foreign_key' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'type' => '1',
			'created' => '2012-12-24 05:35:42',
			'modified' => '2012-12-24 05:35:42',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50d79c56-7e84-4a95-b14d-1134d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '50d77ff7-bcac-4c03-8687-1b63d7a10fce',
			'aro' => 'Group',
			'aro_foreign_key' => '30ce2d3a-0468-4334-b59f-3053d7a10fce',
			'type' => '1',
			'created' => '2012-12-24 05:35:42',
			'modified' => '2012-12-24 05:35:42',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50d79c56-aef8-4876-b2b6-1134d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '50d77ff8-40ec-451a-b96e-1b63d7a10fce',
			'aro' => 'Group',
			'aro_foreign_key' => '30ce2d3a-0468-4334-b59f-3053d7a10fce',
			'type' => '0',
			'created' => '2012-12-24 05:35:42',
			'modified' => '2012-12-24 05:35:42',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50d79c56-b624-415a-a352-1134d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '50d77ffa-094c-4d4c-9dd7-1b63d7a10fce',
			'aro' => 'Group',
			'aro_foreign_key' => '7bd56042-c5cd-11e1-c8c5-080027796c4c',
			'type' => '1',
			'created' => '2012-12-24 05:35:42',
			'modified' => '2012-12-24 05:35:42',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50d79c56-c9fc-4a03-97f8-1134d7a10fce',
			'aco' => 'Resource',
			'aco_foreign_key' => '50d77ffa-7278-41fc-a4bb-1b63d7a10fce',
			'aro' => 'User',
			'aro_foreign_key' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'type' => '1',
			'created' => '2012-12-24 05:35:42',
			'modified' => '2012-12-24 05:35:42',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50d79c56-e58c-46b8-b0aa-1134d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '50d77ffb-b9a0-415d-ba6a-1b63d7a10fce',
			'aro' => 'Group',
			'aro_foreign_key' => '6bd58742-c5cd-11e1-a0c5-080027796c4c',
			'type' => '1',
			'created' => '2012-12-24 05:35:42',
			'modified' => '2012-12-24 05:35:42',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50d79c57-8fcc-4d97-bfda-1134d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '50d77ffc-0414-49dd-9959-1b63d7a10fce',
			'aro' => 'Group',
			'aro_foreign_key' => '8bd56042-d9cd-11e1-a0c5-080027796c4c',
			'type' => '1',
			'created' => '2012-12-24 05:35:43',
			'modified' => '2012-12-24 05:35:43',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50d79c57-cf58-4913-9742-1134d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '50d77ffd-cf28-460e-b35e-1b63d7a10fce',
			'aro' => 'Group',
			'aro_foreign_key' => '8bd56042-d9cd-11e1-a0c5-080027796c4c',
			'type' => '7',
			'created' => '2012-12-24 05:35:43',
			'modified' => '2012-12-24 05:35:43',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
	);

}

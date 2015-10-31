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
			'id' => '50e234af-7768-7890-890d-23a4d7a10fce',
			'aco' => 'Resource',
			'aco_foreign_key' => '509bb871-5168-49d4-a676-fb098cebc04d',
			'aro' => 'Group',
			'aro_foreign_key' => '30ce2d3a-0468-4334-b59f-3053d7a10fce',
			'type' => '0',
			'created' => '2015-10-20 18:26:45',
			'modified' => '2015-10-20 18:26:45',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50e6b4ae-ea4c-4baf-aaf4-23a4d7a10dee',
			'aco' => 'Category',
			'aco_foreign_key' => '10d11ff1-5208-4dc2-94d1-1b63d7a10fce',
			'aro' => 'User',
			'aro_foreign_key' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'type' => '15',
			'created' => '2015-10-20 18:26:45',
			'modified' => '2015-10-20 18:26:45',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50e6b4ae-ea4c-4baf-aaf4-23a4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '50d77ff7-5208-4dc2-94d1-1b63d7a10fce',
			'aro' => 'Group',
			'aro_foreign_key' => '10ce2d3a-0468-433b-b59f-3053d7a10fce',
			'type' => '15',
			'created' => '2015-10-20 18:26:45',
			'modified' => '2015-10-20 18:26:45',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50e6b4af-5fa4-493d-bad0-23a4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '50d77ff7-bcac-4c03-8687-1b63d7a10fce',
			'aro' => 'Group',
			'aro_foreign_key' => '30ce2d3a-0468-4334-b59f-3053d7a10fce',
			'type' => '1',
			'created' => '2015-10-20 18:26:45',
			'modified' => '2015-10-20 18:26:45',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50e6b4af-6d20-4e4e-bbcf-23a4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '50d77ff8-40ec-451a-b96e-1b63d7a10fce',
			'aro' => 'Group',
			'aro_foreign_key' => '30ce2d3a-0468-4334-b59f-3053d7a10fce',
			'type' => '0',
			'created' => '2015-10-20 18:26:45',
			'modified' => '2015-10-20 18:26:45',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50e6b4af-7768-45e0-890d-23a4d7a10fce',
			'aco' => 'Resource',
			'aco_foreign_key' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'aro' => 'Group',
			'aro_foreign_key' => '30ce2d3a-0468-4334-b59f-3053d7a10fce',
			'type' => '7',
			'created' => '2015-10-20 18:26:45',
			'modified' => '2015-10-20 18:26:45',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50e6b4af-7768-45e0-900d-7784d7a10fce',
			'aco' => 'Resource',
			'aco_foreign_key' => '50d77ffb-d290-49e4-ac86-1b63d7a10fce',
			'aro' => 'User',
			'aro_foreign_key' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'type' => '0',
			'created' => '2015-10-20 18:26:45',
			'modified' => '2015-10-20 18:26:45',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50e6b4af-832c-44bf-8a49-23a4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '50d77ff8-40ec-451a-b96e-1b63d7a10fce',
			'aro' => 'Group',
			'aro_foreign_key' => '20ce3d3a-0468-433b-b59f-3053d7a10fce',
			'type' => '1',
			'created' => '2015-10-20 18:26:45',
			'modified' => '2015-10-20 18:26:45',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50e6b4af-8824-48f9-89af-23a4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '50d77ffb-b9a0-415d-ba6a-1b63d7a10fce',
			'aro' => 'Group',
			'aro_foreign_key' => '6bd58742-c5cd-11e1-a0c5-080027796c4c',
			'type' => '1',
			'created' => '2015-10-20 18:26:45',
			'modified' => '2015-10-20 18:26:45',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50e6b4af-8ab8-4533-a4b4-23a4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '50d77ffa-5144-4b95-badd-1b63d7a10fce',
			'aro' => 'Group',
			'aro_foreign_key' => '6bd58742-c5cd-11e1-a0c5-080027796ce7',
			'type' => '1',
			'created' => '2015-10-20 18:26:45',
			'modified' => '2015-10-20 18:26:45',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50e6b4af-a490-43f5-9cc9-23a4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '50d77ffa-094c-4d4c-9dd7-1b63d7a10fce',
			'aro' => 'Group',
			'aro_foreign_key' => '5ad56042-c5cd-11e1-a0c5-080027796c4c',
			'type' => '7',
			'created' => '2015-10-20 18:26:45',
			'modified' => '2015-10-20 18:26:45',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50e6b4af-aa58-478c-804d-23a4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '50d77ffa-4030-49e1-990d-1b63d7a10fce',
			'aro' => 'User',
			'aro_foreign_key' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'type' => '15',
			'created' => '2015-10-20 18:26:45',
			'modified' => '2015-10-20 18:26:45',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50e6b4af-ad14-4659-a60d-23a4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '50d77ffc-08ac-42a8-b185-1b63d7a10fce',
			'aro' => 'User',
			'aro_foreign_key' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'type' => '15',
			'created' => '2015-10-20 18:26:45',
			'modified' => '2015-10-20 18:26:45',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50e6b4af-b124-40e3-988e-23a4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '50d77ffc-08ac-42a8-b185-1b63d7a10fce',
			'aro' => 'Group',
			'aro_foreign_key' => '6bd58742-c5cd-11e1-a0c6-080127896ce7',
			'type' => '1',
			'created' => '2015-10-20 18:26:45',
			'modified' => '2015-10-20 18:26:45',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50e6b4af-b598-42f7-b105-23a4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '50d77ffa-c25c-4d92-aa35-1b63d7a10fce',
			'aro' => 'User',
			'aro_foreign_key' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'type' => '1',
			'created' => '2015-10-20 18:26:45',
			'modified' => '2015-10-20 18:26:45',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50e6b4af-c390-4e5e-a8f8-23a4d7a10fce',
			'aco' => 'Resource',
			'aco_foreign_key' => '50d77ffa-7278-41fc-a4bb-1b63d7a10fce',
			'aro' => 'User',
			'aro_foreign_key' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'type' => '1',
			'created' => '2015-10-20 18:26:46',
			'modified' => '2015-10-20 18:26:46',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50e6b4af-c390-4f2e-a8f8-23a4d7a10fcc',
			'aco' => 'Category',
			'aco_foreign_key' => '222d3a7b-fc70-4faa-a19f-1aafc0a800dc',
			'aro' => 'User',
			'aro_foreign_key' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'type' => '3',
			'created' => '2015-10-20 18:26:46',
			'modified' => '2015-10-20 18:26:46',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50e6b4af-d4b0-43d8-947f-23a4d7a10ecb',
			'aco' => 'Category',
			'aco_foreign_key' => '50d77ffd-cf28-460e-b35e-1b63d7a10fce',
			'aro' => 'Group',
			'aro_foreign_key' => '8bd56042-d9cd-11e1-a0c5-080027796c4c',
			'type' => '7',
			'created' => '2015-10-20 18:26:46',
			'modified' => '2015-10-20 18:26:46',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50e6b4af-d4b0-43d8-947f-23a4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '50d77ffc-0414-49dd-9959-1b63d7a10fce',
			'aro' => 'Group',
			'aro_foreign_key' => '8bd56042-d9cd-11e1-a0c5-080027796c4c',
			'type' => '1',
			'created' => '2015-10-20 18:26:46',
			'modified' => '2015-10-20 18:26:46',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50f6b4af-a491-43f5-fac9-23a4d7a10fce',
			'aco' => 'Category',
			'aco_foreign_key' => '50d77ffa-094c-4d4c-9dd7-1b63d7a10fce',
			'aro' => 'User',
			'aro_foreign_key' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'type' => '0',
			'created' => '2015-10-20 18:26:45',
			'modified' => '2015-10-20 18:26:45',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '533d2ecb-3ec8-4437-9ca5-0aafc0a895dc',
			'aco' => 'Category',
			'aco_foreign_key' => '50d77ff7-5208-4dc2-94d1-1b63d7a10fce',
			'aro' => 'User',
			'aro_foreign_key' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'type' => '15',
			'created' => '2015-10-20 18:26:45',
			'modified' => '2015-10-20 18:26:45',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}

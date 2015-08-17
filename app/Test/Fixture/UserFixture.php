<?php
/**
 * UserFixture
 *
 */
class UserFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'role_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'username' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'key' => 'unique', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'password' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 60, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'active' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1, 'unsigned' => false),
		'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'created_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'modified_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'username' => array('column' => 'username', 'unique' => 1)
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
			'id' => '50cdab9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'user@passbolt.com',
			'password' => '$2a$10$T2Od0YmrUOhd0lox7SiCnuit3egCxFy4DmWzYJJOBtWDD.qBFKzke',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-17 13:46:28',
			'modified' => '2015-08-17 13:46:28',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'jean@passbolt.com',
			'password' => '$2a$10$WjaeOxHFQjmOEdl3JZnISuPn2s5.RVXfBrPEpjNCQw6lYK4Nxzqum',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-17 13:46:29',
			'modified' => '2015-08-17 13:46:29',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'carol@passbolt.com',
			'password' => '$2a$10$PlolwgpvZHSYzvJpUbZLFeMSYVoGdsIdHD6Brcp5cxBHMOvXTNvNC',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-17 13:46:28',
			'modified' => '2015-08-17 13:46:28',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'ada@passbolt.com',
			'password' => '$2a$10$3UdXMQbrCjJiNXAkN6oTN.I8j1y/GzeCOZ5Zxw.NnVHT/dvYgdDqO',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-17 13:46:28',
			'modified' => '2015-08-17 13:46:28',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'betty@passbolt.com',
			'password' => '$2a$10$28Ek.Mgz0gIjZMTVWxrrAuG96q7ETDXLvd8WAvfHZKaHqLNYJXmWi',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-17 13:46:28',
			'modified' => '2015-08-17 13:46:28',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'irene@passbolt.com',
			'password' => '$2a$10$jJzWhfF4mkFFncj1rLd9GuIWdLfunPzLV/./qj/rK9gmzIUMuvLny',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-17 13:46:29',
			'modified' => '2015-08-17 13:46:29',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-f214-4549-9807-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'grace@passbolt.com',
			'password' => '$2a$10$2/wwUFrd033A4U8SM49aAOZxZYnBBKQkpAW9SgL8iGZfx/y6GgtOe',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-17 13:46:28',
			'modified' => '2015-08-17 13:46:28',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'marlyn@passbolt.com',
			'password' => '$2a$10$rM5KrXMHZ0EPT1c.teUCI.SkcxFJCUzz.0cstYY70gIBccaSfPRxq',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-17 13:46:29',
			'modified' => '2015-08-17 13:46:29',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'lynne@passbolt.com',
			'password' => '$2a$10$xDcqZrs3XRDQgXrvyWv8HOof4EzCMCGOM.0eOA6TaIUJCGHVAP8xu',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-17 13:46:29',
			'modified' => '2015-08-17 13:46:29',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d346d-d378-4acc-affd-1663c0a895dc',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'frances@passbolt.com',
			'password' => '$2a$10$AyW9QK6nClzfxusijzq1XOP9ZN4ffQadv2A4fqFiBM1MGTBDPROsq',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-17 13:46:28',
			'modified' => '2015-08-17 13:46:28',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d3564-03e8-4963-94a7-178cc0a895dc',
			'role_id' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'guest@passbolt.com',
			'password' => '$2a$10$AYEYFAH8auLK95uUDn0PYuR94ICwTHRqTsKQoC0bnTJyUHFlZ2ng.',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-17 13:46:28',
			'modified' => '2015-08-17 13:46:28',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'hedy@passbolt.com',
			'password' => '$2a$10$kh17fr9gaOaDBA/.ubTPk.Vufxg.ojPXSY4uXC8LqWiGnXHA3FisC',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-17 13:46:28',
			'modified' => '2015-08-17 13:46:28',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d37a0-bc80-4945-9b11-1663c0a895dc',
			'role_id' => '142c1188-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'admin@passbolt.com',
			'password' => '$2a$10$3bkU1yeCgShnsVl8S4tjreXjtX8fDwOAwDR1FwyQOsEqDC4wTh7mS',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-17 13:46:28',
			'modified' => '2015-08-17 13:46:28',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'role_id' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'anonymous@passbolt.com',
			'password' => '$2a$10$QEsUANtVevAZsUq1vF7.cuNIN4DN5SCc2kAxUH1FWP5bGoLmkY3Ie',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-17 13:46:28',
			'modified' => '2015-08-17 13:46:28',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4e',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'edith@passbolt.com',
			'password' => '$2a$10$A/Ld4Zo/3Vz23UeuJuqAl.2zje0JgVP5FqB1naQjwunC3Ltyo8pFO',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-17 13:46:28',
			'modified' => '2015-08-17 13:46:28',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'dame@passbolt.com',
			'password' => '$2a$10$EqTpg0nX9u0S2qHeqdlXLeXUZj9fQawZoM215cg3ZHJnGVAj5Ai0m',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-17 13:46:28',
			'modified' => '2015-08-17 13:46:28',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => 'dada6042-c5cd-11e1-a0c5-080027796c51',
			'role_id' => '142c1340-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'root@passbolt.com',
			'password' => '$2a$10$0t6EEC8GioyVs2rt5Mku8eXFAkGWJN8JPkKZ/3nFSB/Mu1Y3yQTIO',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-17 13:46:28',
			'modified' => '2015-08-17 13:46:28',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => 'eeee6042-c5cd-11e1-a0c5-080027796c51',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'kathleen@passbolt.com',
			'password' => '$2a$10$vyFjFUTF/6ekqhP5QeVMr.MrbbiRshQiwUrf7jPbba12U4ib1pNCK',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-17 13:46:29',
			'modified' => '2015-08-17 13:46:29',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
	);

}

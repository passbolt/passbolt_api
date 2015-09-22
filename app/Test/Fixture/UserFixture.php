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
			'password' => '$2a$10$z6lgZmueBTPp5Rlvw.mDTu2GcF0rFzKlaovLYL1DGTUKX796WYRAu',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-09-22 20:00:05',
			'modified' => '2015-09-22 20:00:05',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'jean@passbolt.com',
			'password' => '$2a$10$3AfKHT/p2N9ScT1QAzt6ZuQC6u79fi94goskGv6vzZqL/NpNZxZ36',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-09-22 20:00:06',
			'modified' => '2015-09-22 20:00:06',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'carol@passbolt.com',
			'password' => '$2a$10$gAwv2P7TNiZkzNQzJNxjm.zz8fA4A.VDuNEbmp4yRe.hL/EtxzOAq',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-09-22 20:00:06',
			'modified' => '2015-09-22 20:00:06',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'ada@passbolt.com',
			'password' => '$2a$10$VqADn3fJkpOGIH5XXsO79.d81IgrO5kPhKpC8so.47EniO69vqBW6',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-09-22 20:00:05',
			'modified' => '2015-09-22 20:00:05',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'betty@passbolt.com',
			'password' => '$2a$10$qVd0hKqgagwXk3GBG5DK9uRGKXmN72fMKaVQYKIEdeiWLgZuyuwYe',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-09-22 20:00:05',
			'modified' => '2015-09-22 20:00:05',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'irene@passbolt.com',
			'password' => '$2a$10$c62O0AAf3Yacb4z8QemlGe4MPIiFpiyU.dgsGkekhHRn4CG/lB0vy',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-09-22 20:00:06',
			'modified' => '2015-09-22 20:00:06',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-f214-4549-9807-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'grace@passbolt.com',
			'password' => '$2a$10$sDWN9ycdzlfXXCOoR2d1Aua50AmUSTDOqWIg06HwLaNBuGSxa9lf6',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-09-22 20:00:06',
			'modified' => '2015-09-22 20:00:06',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'marlyn@passbolt.com',
			'password' => '$2a$10$wzCRItRDX36ubqXxImLCBuIlhNhD3QcFkKIC0U4Se1M/.xZo1E8XG',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-09-22 20:00:06',
			'modified' => '2015-09-22 20:00:06',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'lynne@passbolt.com',
			'password' => '$2a$10$Ai2nPqBxFqwRSPmQgMQoUesMW9IusVEfXwZE6Yg3/kvTTU0sb2Wl.',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-09-22 20:00:06',
			'modified' => '2015-09-22 20:00:06',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d346d-d378-4acc-affd-1663c0a895dc',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'frances@passbolt.com',
			'password' => '$2a$10$hRjxuCF0FUj5ZkyhgTlgpudgnpADVzt71zfVw2dYgLUfO.TSUge5S',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-09-22 20:00:06',
			'modified' => '2015-09-22 20:00:06',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d3564-03e8-4963-94a7-178cc0a895dc',
			'role_id' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'guest@passbolt.com',
			'password' => '$2a$10$ZAhURbpnA9qT7AHfZMG7Ru5ZKFYZczss..vg0ugn7/TbaJOG4VAdW',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-09-22 20:00:05',
			'modified' => '2015-09-22 20:00:05',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'hedy@passbolt.com',
			'password' => '$2a$10$u2k6m1zJ7fRf3ddRPNvz8ekK9Rq3FI3IFLrWf0q419oeSGjxyYoIe',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-09-22 20:00:06',
			'modified' => '2015-09-22 20:00:06',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d37a0-bc80-4945-9b11-1663c0a895dc',
			'role_id' => '142c1188-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'admin@passbolt.com',
			'password' => '$2a$10$7uEg4r7royhJOdJrqyrLyOdSX0P63khaH2eaU6qPlR36YIHqTWcDy',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-09-22 20:00:05',
			'modified' => '2015-09-22 20:00:05',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'role_id' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'anonymous@passbolt.com',
			'password' => '$2a$10$x3/qYf2TGPUi8cfc9ntpiOqRZn70s0U5TSV.W6IaA.jHLNsjpOFOO',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-09-22 20:00:05',
			'modified' => '2015-09-22 20:00:05',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4e',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'edith@passbolt.com',
			'password' => '$2a$10$BAhxYF97rFh6JUqwiXwKBOvM2tJSe64Tk0RVGehIZegH/Wb16kC6m',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-09-22 20:00:06',
			'modified' => '2015-09-22 20:00:06',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'dame@passbolt.com',
			'password' => '$2a$10$U/Leqoc4MreRlYz1.dAG7.sEPeoYV7rDik2G9olVgV08uBSmSAUP.',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-09-22 20:00:06',
			'modified' => '2015-09-22 20:00:06',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => 'dada6042-c5cd-11e1-a0c5-080027796c51',
			'role_id' => '142c1340-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'root@passbolt.com',
			'password' => '$2a$10$dM95uXi4m4y3ZJ7yc8swQeXERlaiTOQ5cYyy.NLV8V/AW0ty6MFI.',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-09-22 20:00:05',
			'modified' => '2015-09-22 20:00:05',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => 'eeee6042-c5cd-11e1-a0c5-080027796c51',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'kathleen@passbolt.com',
			'password' => '$2a$10$U9f9.JYwT1fm7YPQlFBTuu3SCfihswS9GrYCDtcqaUyCvSDOSS5uO',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-09-22 20:00:06',
			'modified' => '2015-09-22 20:00:06',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
	);

}

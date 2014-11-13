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
		'active' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
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
			'password' => '$2a$10$AX0nyfLR9.GhhWnsadNvJ.yZ0EmEVTZChFilUD4cORKR3vtENxCWq',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-11-12 14:25:34',
			'modified' => '2014-11-12 14:25:34',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'jean-rene@test.com',
			'password' => '$2a$10$sbCsBdNpqF.N1cyVZqsjP.OuYmnFgjOq.p1wVA5RWh4syErtyn722',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-11-12 14:25:34',
			'modified' => '2014-11-12 14:25:34',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'cedric@passbolt.com',
			'password' => '$2a$10$shmEC/H6d9.t9SfqDetNMe6jmATGR2FiNqL2pXHE6g0oQJRAwW5oW',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-11-12 14:25:34',
			'modified' => '2014-11-12 14:25:34',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'remy@passbolt.com',
			'password' => '$2a$10$pXZpxrhclIyYDISp0iaRl.HKKgrIMdfbPvrEulk4LHAcv9ZvcWaZq',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-11-12 14:25:34',
			'modified' => '2014-11-12 14:25:34',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'kevin@passbolt.com',
			'password' => '$2a$10$MBIbWCOWN5MqAyo9W6kwd.W.H78W.WUHhfNvEfUxb/KVNCFLJENTu',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-11-12 14:25:34',
			'modified' => '2014-11-12 14:25:34',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'ismail@passbolt.com',
			'password' => '$2a$10$6IlrcBHU4iJLxnOYcZUvC.eSNpeXcyfn5hu/spia0mrpgVYtvjp0.',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-11-12 14:25:33',
			'modified' => '2014-11-12 14:25:33',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-f214-4549-9807-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'aurelie@passbolt.com',
			'password' => '$2a$10$wfgq/N.O9yJReNeP/yZz5.YBckcu.Ayp6S9sZXdoNsRvRDB8R3f2e',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-11-12 14:25:33',
			'modified' => '2014-11-12 14:25:33',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'myriam@passbolt.com',
			'password' => '$2a$10$RXG1MHdLN3VxBn6ilUz5/ujY7f9QAZk2SIA/Xk2eJS/v7O/h6UiB2',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-11-12 14:25:34',
			'modified' => '2014-11-12 14:25:34',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'manager.nogroup@passbolt.com',
			'password' => '$2a$10$kifrroa3g37AUAxqw4EWAO7Ihylh9xCUCE6bUTWJX6667BkFkByMC',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-11-12 14:25:33',
			'modified' => '2014-11-12 14:25:33',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '533d346d-d378-4acc-affd-1663c0a895dc',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'frank@passbolt.com',
			'password' => '$2a$10$sHUlksY4k8sWZxEPtxylrOewZKexVZmDl1izx0ypbA4.gmWxUFDka',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-11-12 14:25:35',
			'modified' => '2014-11-12 14:25:35',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d3564-03e8-4963-94a7-178cc0a895dc',
			'role_id' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'guest@passbolt.com',
			'password' => '$2a$10$c1mnIivKkbXzkq/851X22eOaVrtmocoL8mrG8FqryZYQQ9HDA3arS',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-11-12 14:25:34',
			'modified' => '2014-11-12 14:25:34',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'a-usr1@companya.com',
			'password' => '$2a$10$7kGC48ECtpeCukWyZlLkEetvxzVBORNJ4pOFkLFr6Cfq0aveCFj6S',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-11-12 14:25:35',
			'modified' => '2014-11-12 14:25:35',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d37a0-bc80-4945-9b11-1663c0a895dc',
			'role_id' => '142c1188-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'admin@passbolt.com',
			'password' => '$2a$10$C8YXNvproIVH4wCxY6sAIekmxxRCmvBYFTTJY0n85kCnB15FPDySy',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-11-12 14:25:35',
			'modified' => '2014-11-12 14:25:35',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'role_id' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'anonymous@passbolt.com',
			'password' => '$2a$10$yBxzgNnpOX8jf8aprSvkmeepczYzCTOwIxE3zWL1tS0ATlMx863eO',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-11-12 14:25:33',
			'modified' => '2014-11-12 14:25:33',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4e',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'test@passbolt.com',
			'password' => '$2a$10$6OHOReN4yHN5yyfgCGfZte/Z1fLhvDerQiSURDKLccUQSPq1LVsfK',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-11-12 14:25:33',
			'modified' => '2014-11-12 14:25:33',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'dark.vador@passbolt.com',
			'password' => '$2a$10$3Qko4pt5jObBVdScmwmZPeaiNQthU8jxrThTAiQ0dxMQGyO62B.Yq',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-11-12 14:25:33',
			'modified' => '2014-11-12 14:25:33',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => 'dada6042-c5cd-11e1-a0c5-080027796c51',
			'role_id' => '142c1340-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'root@passbolt.com',
			'password' => '$2a$10$ZOKkjfDcuf0rZeYadrzDxOxSBmwrsXb6R8GrmrIUg.mNmV8hLZpFu',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-11-12 14:25:33',
			'modified' => '2014-11-12 14:25:33',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => 'eeee6042-c5cd-11e1-a0c5-080027796c51',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'utest@passbolt.com',
			'password' => '$2a$10$CSMaA1/n4xvVK/zMyielVORugKTTuLNSGJm8L.JPIs.bMxGgTGBlq',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-11-12 14:25:32',
			'modified' => '2014-11-12 14:25:32',
			'created_by' => '',
			'modified_by' => ''
		),
	);

}

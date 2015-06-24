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
			'password' => '$2a$10$Mw7YSwvhkNe38J6rTGqHU.3sEfijoKde9S6uf6mhCU392B5HlFs0u',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-06-24 15:41:01',
			'modified' => '2015-06-24 15:41:01',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'jean-rene@test.com',
			'password' => '$2a$10$LqwNvtYzwJEbrMnZ90tRw.Eo/6dSh8IOvcWwbExQi0RpWt5R5S3JS',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-06-24 15:41:01',
			'modified' => '2015-06-24 15:41:01',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'cedric@passbolt.com',
			'password' => '$2a$10$E0LyurUQj3J1h1LnB4BUj.TE0v/miiA2IeUXVQFH.cfWKvOk7EB6C',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-06-24 15:41:01',
			'modified' => '2015-06-24 15:41:01',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'remy@passbolt.com',
			'password' => '$2a$10$4G72NZXJVFyoSSmHxSzIY.2anghUk8GIrrrIfm6nDL6F171XRdCSa',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-06-24 15:41:01',
			'modified' => '2015-06-24 15:41:01',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'kevin@passbolt.com',
			'password' => '$2a$10$rmRgIuMZxPsAEdRlmEN55.jzk1Ovd4hWXBf/LijR1efQqOGW9cZ6e',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-06-24 15:41:01',
			'modified' => '2015-06-24 15:41:01',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'ismail@passbolt.com',
			'password' => '$2a$10$TUaPV2iijD/rUsgvIhDyF.DFnTnpLexicTKBQmsIqNCjcyb3shvd2',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-06-24 15:41:01',
			'modified' => '2015-06-24 15:41:01',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-f214-4549-9807-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'aurelie@passbolt.com',
			'password' => '$2a$10$wNUUdBtd8EJF30/kbrVpFO7.xlkp1FPSReKtn4juJShslDrVZo0ly',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-06-24 15:41:01',
			'modified' => '2015-06-24 15:41:01',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'myriam@passbolt.com',
			'password' => '$2a$10$2FmWFmNQmBZHEc2vWTKpp.tzDeoCHYQbs6QNWmqMHqePhLxznxIgy',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-06-24 15:41:01',
			'modified' => '2015-06-24 15:41:01',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'manager.nogroup@passbolt.com',
			'password' => '$2a$10$IizvG7ZrBuMFPmSeRGgDw.Wlu/8357d0DrwU9Y6qvtLq/BuoG2YgC',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-06-24 15:41:00',
			'modified' => '2015-06-24 15:41:00',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '533d346d-d378-4acc-affd-1663c0a895dc',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'frank@passbolt.com',
			'password' => '$2a$10$HePJ2slP4KaR2JOp9wEZ..L5NsoV5YZImRjNI1BEdNOvSPX5iRDCW',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-06-24 15:41:01',
			'modified' => '2015-06-24 15:41:01',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d3564-03e8-4963-94a7-178cc0a895dc',
			'role_id' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'guest@passbolt.com',
			'password' => '$2a$10$dLaGAhkuF2OGEC1ps7GMx.IBWU91..Fm08p3cntEo41P/GILVuBj2',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-06-24 15:41:01',
			'modified' => '2015-06-24 15:41:01',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'a-usr1@companya.com',
			'password' => '$2a$10$iHfL2G.u9od1oO4HTnjPN.5qjHp8mXYD8ah.sDhCrWz/e48lxa1By',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-06-24 15:41:01',
			'modified' => '2015-06-24 15:41:01',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d37a0-bc80-4945-9b11-1663c0a895dc',
			'role_id' => '142c1188-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'admin@passbolt.com',
			'password' => '$2a$10$WRXaGQQ/Gzn5JAxfqtt.Quby4dtpkeKPgh99PwG4VJRHzWzvbvKrO',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-06-24 15:41:01',
			'modified' => '2015-06-24 15:41:01',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'role_id' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'anonymous@passbolt.com',
			'password' => '$2a$10$i/fxWi9ci4pmrV9caLofhOijgNXmfpLiSBwk1ebQRoKBXxT/8.jdW',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-06-24 15:41:00',
			'modified' => '2015-06-24 15:41:00',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4e',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'test@passbolt.com',
			'password' => '$2a$10$./mXXH0papnSQG9U99MPtOyk8S6Ryg5Ba8wTeHZP9lowvGPIT07.m',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-06-24 15:41:00',
			'modified' => '2015-06-24 15:41:00',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'darth.vader@passbolt.com',
			'password' => '$2a$10$UZYn3WMtZlAU8WT/Pf/SdeOlNreTO/Fd6zrNpJWyZOdJ8m.yN34ty',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-06-24 15:41:00',
			'modified' => '2015-06-24 15:41:00',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => 'dada6042-c5cd-11e1-a0c5-080027796c51',
			'role_id' => '142c1340-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'root@passbolt.com',
			'password' => '$2a$10$tdWdu5dP7vrLroO96aUkD.CHJND7x2fZalxIdH3.VzVVUumIcyvyy',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-06-24 15:41:00',
			'modified' => '2015-06-24 15:41:00',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => 'eeee6042-c5cd-11e1-a0c5-080027796c51',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'utest@passbolt.com',
			'password' => '$2a$10$bE.95oiO7BN1ZrmhNLuyAeR7Elf2E3OyQGIBwqC.Go48HNxNgK/SS',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-06-24 15:41:00',
			'modified' => '2015-06-24 15:41:00',
			'created_by' => '',
			'modified_by' => ''
		),
	);

}

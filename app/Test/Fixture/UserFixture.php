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
			'password' => '$2a$10$9xpDGIOPM/TA8C3z2/HR.u0tdsVeFPf/nNUv1nE6o57C/cCDiqixq',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-07-01 13:39:54',
			'modified' => '2015-07-01 13:39:54',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'jean-rene@test.com',
			'password' => '$2a$10$O1DdzEVSngTZ52MhQmQWVOhiyD6qAugyav2bIaH.KrzkQcLUoaTM6',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-07-01 13:39:55',
			'modified' => '2015-07-01 13:39:55',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'cedric@passbolt.com',
			'password' => '$2a$10$MyonQdKQrY2eVXrXo9eWHu2/dZSPaEEDZVCFE4mUqADvrkh1zGiga',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-07-01 13:39:55',
			'modified' => '2015-07-01 13:39:55',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'ada@passbolt.com',
			'password' => '$2a$10$CjnXbH5RWBHSPbI09a1PEewx9n0NM2lYV4QCZzFm1q9vpInU8GBLG',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-07-01 13:39:54',
			'modified' => '2015-07-01 13:39:54',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'kevin@passbolt.com',
			'password' => '$2a$10$RQaEFruIZu9.Lj4xV112i.avuWbsHQVKzaGwa5LrandAtOUpltwjC',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-07-01 13:39:55',
			'modified' => '2015-07-01 13:39:55',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'ismail@passbolt.com',
			'password' => '$2a$10$wzDWXF2GWU2iIYvcJzUVtev9atWOGL1pNSYPtiQRCjNXcWy9yGGCW',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-07-01 13:39:54',
			'modified' => '2015-07-01 13:39:54',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-f214-4549-9807-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'aurelie@passbolt.com',
			'password' => '$2a$10$tRRZUKsyQKQ4xXy/4QmbZ.o1DaE3ZJ6lMbPsjlcCn.0w9vzX6INt6',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-07-01 13:39:54',
			'modified' => '2015-07-01 13:39:54',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'myriam@passbolt.com',
			'password' => '$2a$10$e3cIUF3.BSfc6FVQeJxZRuglHK0uHaDFDN76b5HbSQzJpSlBIQfuC',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-07-01 13:39:54',
			'modified' => '2015-07-01 13:39:54',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'manager.nogroup@passbolt.com',
			'password' => '$2a$10$5AccLyiWno37Cb5q/UBYfOadOp8k.gwH1CcWFP8A9E8dDvwyIKV/6',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-07-01 13:39:54',
			'modified' => '2015-07-01 13:39:54',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d346d-d378-4acc-affd-1663c0a895dc',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'frank@passbolt.com',
			'password' => '$2a$10$m6s40Ovu11RIvjz4h/gyyeZdUF/ED3.nH.QuYuApXF6TQyA2TCG.e',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-07-01 13:39:55',
			'modified' => '2015-07-01 13:39:55',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d3564-03e8-4963-94a7-178cc0a895dc',
			'role_id' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'guest@passbolt.com',
			'password' => '$2a$10$JvlKB8T4DSe4aqJZZwfPHeFea4w..lo2t4Bzb7/CRZvAgHf.vEvhS',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-07-01 13:39:54',
			'modified' => '2015-07-01 13:39:54',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'a-usr1@companya.com',
			'password' => '$2a$10$t/DANbdN.x5C1xsJENTA/OvbjEAFnI9aDufH.GHEAjQghG7tJ/M9m',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-07-01 13:39:55',
			'modified' => '2015-07-01 13:39:55',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d37a0-bc80-4945-9b11-1663c0a895dc',
			'role_id' => '142c1188-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'admin@passbolt.com',
			'password' => '$2a$10$t2hHz0Yw0UNQvC6jKRW2y.wwB/hwuC27CyYJ069uh..Ev8mBJy2wC',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-07-01 13:39:54',
			'modified' => '2015-07-01 13:39:54',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'role_id' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'anonymous@passbolt.com',
			'password' => '$2a$10$YiUWkNyPe8H3a8A0zKLC7ebbJJSCvVrBT/2RGH/P0eMDWiV5Iujni',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-07-01 13:39:54',
			'modified' => '2015-07-01 13:39:54',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4e',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'test@passbolt.com',
			'password' => '$2a$10$bcaXR/slbkkf/n0.qnkNEeqPmAPaP84wBJ112/Dm.LkhtpvF0ENJC',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-07-01 13:39:54',
			'modified' => '2015-07-01 13:39:54',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'darth.vader@passbolt.com',
			'password' => '$2a$10$69NrxQO0ic7l4rWD3N04rOtrTZoy.6yy2MZqGgpCORu8EZ8DFTxke',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-07-01 13:39:54',
			'modified' => '2015-07-01 13:39:54',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => 'dada6042-c5cd-11e1-a0c5-080027796c51',
			'role_id' => '142c1340-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'root@passbolt.com',
			'password' => '$2a$10$dfmRq0G.nyfwQ8MG6CzCA.DBhecV2kkwaD/mZqk19.lVdRTVxefzq',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-07-01 13:39:54',
			'modified' => '2015-07-01 13:39:54',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => 'eeee6042-c5cd-11e1-a0c5-080027796c51',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'utest@passbolt.com',
			'password' => '$2a$10$.ZlPZSjOsob24jzXLUcFmOUd/cfzCxJjjADUbi1zHIBWBWCc8b4Ra',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-07-01 13:39:54',
			'modified' => '2015-07-01 13:39:54',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
	);

}

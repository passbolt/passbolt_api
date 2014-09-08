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
			'password' => '$2a$10$Rb81LocQfX0HjHw9yaAntO8xvu7Eo4YBblEZ8MzjEKYujJKSHw5.e',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-09-07 00:40:57',
			'modified' => '2014-09-07 00:40:57',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'jean-rene@test.com',
			'password' => '$2a$10$.pJE4o0rKSHlclQ964wFZOLPk5UF.AOpOWXWsG2e8FvbP5DKm9zx.',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-09-07 00:40:57',
			'modified' => '2014-09-07 00:40:57',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'cedric@passbolt.com',
			'password' => '$2a$10$OEzCgYByqBNYx88EufU05uHt/Rea4xko8rzgaphVcTSB/KI9PmsLK',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-09-07 00:40:57',
			'modified' => '2014-09-07 00:40:57',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'remy@passbolt.com',
			'password' => '$2a$10$jxNAusCH5bhUm9eygTjqqOKKbraMOoCVLPNSl7b2MGKG13sQcNpRe',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-09-07 00:40:57',
			'modified' => '2014-09-07 00:40:57',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'kevin@passbolt.com',
			'password' => '$2a$10$3OvnYJToayaDlBz3sOAz6.aaSzMKs3NELRHYr4cwBxdryiSwt8Fjy',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-09-07 00:40:57',
			'modified' => '2014-09-07 00:40:57',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'ismail@passbolt.com',
			'password' => '$2a$10$h9Edn/W5ggRSePvd1B9Hc.EI19dBSPu9IXM9BfMgBC6RQ6q/zdXs2',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-09-07 00:40:56',
			'modified' => '2014-09-07 00:40:56',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-f214-4549-9807-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'aurelie@passbolt.com',
			'password' => '$2a$10$YPk/pbKfZmil6Q3SJABOL.omOqpdqiZSQ1yw3hDyL4HhyFfldynLu',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-09-07 00:40:56',
			'modified' => '2014-09-07 00:40:56',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'myriam@passbolt.com',
			'password' => '$2a$10$zpinh7SGYL09ajZVHj9NhuxQYMsGgDMFkgp5iav2lecgaWpckJ/RW',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-09-07 00:40:57',
			'modified' => '2014-09-07 00:40:57',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'manager.nogroup@passbolt.com',
			'password' => '$2a$10$aZSIbjQO3tIbf7WJzdFqC.APhYlozVMK1voOOiU9QRrByu/iUGTii',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-09-07 00:40:56',
			'modified' => '2014-09-07 00:40:56',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '533d346d-d378-4acc-affd-1663c0a895dc',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'frank@passbolt.com',
			'password' => '$2a$10$XlNJhaJgHWCDDQKWcVM5DOD/gllf5aclw56QbW1RpKDgBc7.FyEnu',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-09-07 00:40:58',
			'modified' => '2014-09-07 00:40:58',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d3564-03e8-4963-94a7-178cc0a895dc',
			'role_id' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'guest@passbolt.com',
			'password' => '$2a$10$MZnDjtqnqOs8FBHrdzdhJOGv3V8Jl6xRmJW2jllJjzxYng/lIsN46',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-09-07 00:40:57',
			'modified' => '2014-09-07 00:40:57',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'a-usr1@companya.com',
			'password' => '$2a$10$uJ2kpogppsScmwWQfrdS2uYqK9QHdQAvD7hW/vIC0GSelMa/ObGsW',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-09-07 00:40:58',
			'modified' => '2014-09-07 00:40:58',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d37a0-bc80-4945-9b11-1663c0a895dc',
			'role_id' => '142c1188-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'admin@passbolt.com',
			'password' => '$2a$10$3sSuYr5ZrV6o11K0CHtFz.li4JTcN5oapAAhQYhEErEhc8mOF/.1m',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-09-07 00:40:58',
			'modified' => '2014-09-07 00:40:58',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'role_id' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'anonymous@passbolt.com',
			'password' => '$2a$10$sn3GFAK5KJibT0uTXh3Zyu32/GNu9NXi1S82Vz.k1IleMZB4s/cu6',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-09-07 00:40:56',
			'modified' => '2014-09-07 00:40:56',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4e',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'test@passbolt.com',
			'password' => '$2a$10$pYpFBDDG7gVy8MDZh/RI2.DCGsnbxUzP9UJEuILsYH7b7KsUj.xmu',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-09-07 00:40:56',
			'modified' => '2014-09-07 00:40:56',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'dark.vador@passbolt.com',
			'password' => '$2a$10$rZCMdKYzjQAnvUfe/VkJSuA3laz/oA7sVs9jB1/LElam3yFCg/YFy',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-09-07 00:40:56',
			'modified' => '2014-09-07 00:40:56',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => 'dada6042-c5cd-11e1-a0c5-080027796c51',
			'role_id' => '142c1340-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'root@passbolt.com',
			'password' => '$2a$10$u1o.a51UZVeYYDrIOXTe.uOA65a6V.FvtdvIJDSM58MW4udqEsUnK',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-09-07 00:40:56',
			'modified' => '2014-09-07 00:40:56',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => 'eeee6042-c5cd-11e1-a0c5-080027796c51',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'utest@passbolt.com',
			'password' => '$2a$10$qwMxAx0eoV5SEmyYJ2/YSeE/DYsuQZpU.i07ZfrWvlf6/9l1jic2a',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-09-07 00:40:55',
			'modified' => '2014-09-07 00:40:55',
			'created_by' => '',
			'modified_by' => ''
		),
	);

}

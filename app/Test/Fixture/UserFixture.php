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
			'password' => '$2a$10$MQv6vyUtvUXvzaUOeSXjE.IaqmP5A2w6p0j58jX9d1ZtS1MkXWqW2',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-06-19 17:02:19',
			'modified' => '2015-06-19 17:02:19',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'jean-rene@test.com',
			'password' => '$2a$10$4NyBrZ.KTuM7X1Kt32WLwePiK5HRF2pCLo3aAE6iRYm0ciDKKT4Fi',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-06-19 17:02:19',
			'modified' => '2015-06-19 17:02:19',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'cedric@passbolt.com',
			'password' => '$2a$10$q3OsZU/n5yjaHuO2wsSJmOBxEpC7SaAMhNN0Os7u5Fzc7zknFiCKC',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-06-19 17:02:19',
			'modified' => '2015-06-19 17:02:19',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'remy@passbolt.com',
			'password' => '$2a$10$t/uzNHXHR4QiWeComjZ1qeNudSmMkCBpt1HGx36GhtLFw8bKLTwLK',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-06-19 17:02:19',
			'modified' => '2015-06-19 17:02:19',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'kevin@passbolt.com',
			'password' => '$2a$10$NNcGUXoRBaD9BE3qDyEW3OufFr7hLvvMkXSGxVyS1XBzQ5liikRw2',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-06-19 17:02:19',
			'modified' => '2015-06-19 17:02:19',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'ismail@passbolt.com',
			'password' => '$2a$10$eGaaGUv6WLM26gaThh.Tf.TzcvMm1IHOjyMOPvBDrLzpifDj7m5le',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-06-19 17:02:19',
			'modified' => '2015-06-19 17:02:19',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-f214-4549-9807-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'aurelie@passbolt.com',
			'password' => '$2a$10$fX53vaLqdaflQ4Titj.4u.lZJ4eRZdMHmluW22ziyIGNagHMEzOQW',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-06-19 17:02:19',
			'modified' => '2015-06-19 17:02:19',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'myriam@passbolt.com',
			'password' => '$2a$10$eEMSQYdEMCVWuFfLZQOSD.xjV7Yf7I63UTlcg1m7kF7ty5e/UECca',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-06-19 17:02:19',
			'modified' => '2015-06-19 17:02:19',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'manager.nogroup@passbolt.com',
			'password' => '$2a$10$1VJaU7Aa9oYGciFY2lPAqejJEGhONZJCl6PuplGGdpRcDJt8jwOp.',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-06-19 17:02:18',
			'modified' => '2015-06-19 17:02:18',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '533d346d-d378-4acc-affd-1663c0a895dc',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'frank@passbolt.com',
			'password' => '$2a$10$SU8XrCNn/nwPOsn2Tlza9eSYMmLLyfFG7OfVW2gZ0Z0u5TtSWkvPi',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-06-19 17:02:20',
			'modified' => '2015-06-19 17:02:20',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d3564-03e8-4963-94a7-178cc0a895dc',
			'role_id' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'guest@passbolt.com',
			'password' => '$2a$10$7.62zd2JE63h0.AGWvZTH.dKwhpD5WA8/Gs6MEL6b/qzDu4aQls.m',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-06-19 17:02:19',
			'modified' => '2015-06-19 17:02:19',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'a-usr1@companya.com',
			'password' => '$2a$10$zOZIGGbGfCBT51Uuj3glEuTosXK2OiyCbVPsHZoRv2QZXnt2KF84y',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-06-19 17:02:19',
			'modified' => '2015-06-19 17:02:19',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d37a0-bc80-4945-9b11-1663c0a895dc',
			'role_id' => '142c1188-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'admin@passbolt.com',
			'password' => '$2a$10$CGsgWqK0W9G30JvGflmSpev71zIsl4snyE8EK9unQbwp6j9FDkbk2',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-06-19 17:02:19',
			'modified' => '2015-06-19 17:02:19',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'role_id' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'anonymous@passbolt.com',
			'password' => '$2a$10$j/KltCPOC8yzwyiRHuaKQOS2i2ZtM0ohl0h8DFvAPloBmOCAQTdMm',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-06-19 17:02:18',
			'modified' => '2015-06-19 17:02:18',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4e',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'test@passbolt.com',
			'password' => '$2a$10$uy5g/V6xf2R0yugMTezaH.OoujBlYnTrXjBkgJe.LLlXjzHaI9nBq',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-06-19 17:02:19',
			'modified' => '2015-06-19 17:02:19',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'darth.vader@passbolt.com',
			'password' => '$2a$10$Yt1vtMIKbjERbb.JrAEdXeHfNhvg4eQV.B0ElA8obFcuBfYYPN/i2',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-06-19 17:02:18',
			'modified' => '2015-06-19 17:02:18',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => 'dada6042-c5cd-11e1-a0c5-080027796c51',
			'role_id' => '142c1340-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'root@passbolt.com',
			'password' => '$2a$10$yj2SpCXxtaDaMpUHF8Cy9OGd8jLErBFT8D9bzrDXVxqiHnqqbZKrC',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-06-19 17:02:18',
			'modified' => '2015-06-19 17:02:18',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => 'eeee6042-c5cd-11e1-a0c5-080027796c51',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'utest@passbolt.com',
			'password' => '$2a$10$1.gM9ACghjhB6IEd24fzXu3jvZ1/7Wc7yMNMsS0RpLNagZB.UwjYO',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-06-19 17:02:18',
			'modified' => '2015-06-19 17:02:18',
			'created_by' => '',
			'modified_by' => ''
		),
	);

}

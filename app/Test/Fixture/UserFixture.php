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
			'password' => '$2a$10$EzQNP27qhzNDbu9SPKQd6es460ZispzyMdKZ26aQfDd2kNCuZXf12',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-11-14 14:28:36',
			'modified' => '2014-11-14 14:28:36',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'jean-rene@test.com',
			'password' => '$2a$10$cc6dKGMKGKFbehGStSwjDe57t8PAwkZeIhg2EbhZxXyfiu4/gInf6',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-11-14 14:28:36',
			'modified' => '2014-11-14 14:28:36',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'cedric@passbolt.com',
			'password' => '$2a$10$GBV7TH.WVpenlS/tBelQmeEH/DKuI7q2ij3KoVIS.3lgIH.gOmh.G',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-11-14 14:28:36',
			'modified' => '2014-11-14 14:28:36',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'remy@passbolt.com',
			'password' => '$2a$10$196w432keMOSrdGt4Uzct.Q0T7UNASFxxo/HEJ1GY/lvFagIAwBaK',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-11-14 14:28:36',
			'modified' => '2014-11-14 14:28:36',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'kevin@passbolt.com',
			'password' => '$2a$10$a.GP4jr76mfhd1dXx8/2cuUTNE2oT3EoSyszibj2zW.WAioTtJ46G',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-11-14 14:28:36',
			'modified' => '2014-11-14 14:28:36',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'ismail@passbolt.com',
			'password' => '$2a$10$q4/82vboB9CbaQ6kdGRu/uNfizxXz6hNjdN4P3ZwG9WQ0xtl4Fk4y',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-11-14 14:28:36',
			'modified' => '2014-11-14 14:28:36',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-f214-4549-9807-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'aurelie@passbolt.com',
			'password' => '$2a$10$ZLAxAkjtgNPfF5YIBbxcvun0lgc.8851j/MnT/CXbOs.uv6gi.8PK',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-11-14 14:28:35',
			'modified' => '2014-11-14 14:28:35',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'myriam@passbolt.com',
			'password' => '$2a$10$S995FYYShK0o.1Oi.6.W.udY1zAYjPYB9MCJoJQUVOApm025ZDZA.',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-11-14 14:28:36',
			'modified' => '2014-11-14 14:28:36',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'manager.nogroup@passbolt.com',
			'password' => '$2a$10$e7VGBNZRFl5Qx5qQWhhXt.QFMJfT0lUvhUkD65Y2mox1SCn6tw4H.',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-11-14 14:28:35',
			'modified' => '2014-11-14 14:28:35',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '533d346d-d378-4acc-affd-1663c0a895dc',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'frank@passbolt.com',
			'password' => '$2a$10$NEIoWYLSueYjWNqtia51PeRiYetK6iRLO0bAv7NJ9xeCHzhnB/lQ2',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-11-14 14:28:37',
			'modified' => '2014-11-14 14:28:37',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d3564-03e8-4963-94a7-178cc0a895dc',
			'role_id' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'guest@passbolt.com',
			'password' => '$2a$10$4MaEbOnQ4/isxCzeqwihi.3NQsiLse5NSJTtFmZDW0YDTCz7pVfbS',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-11-14 14:28:36',
			'modified' => '2014-11-14 14:28:36',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'a-usr1@companya.com',
			'password' => '$2a$10$Yg2Z0tYGOZ7wzXQhToXSgOI3Shrn/.GBM1Ps1EtNNN4JWbXAIdUNa',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-11-14 14:28:37',
			'modified' => '2014-11-14 14:28:37',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d37a0-bc80-4945-9b11-1663c0a895dc',
			'role_id' => '142c1188-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'admin@passbolt.com',
			'password' => '$2a$10$HqibiBHhtgFhA0lin.FT9eiT800gWF5K.EewR.ELcdPTl1HDdsxfa',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-11-14 14:28:37',
			'modified' => '2014-11-14 14:28:37',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'role_id' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'anonymous@passbolt.com',
			'password' => '$2a$10$twQCv1/aJl.NFp7gNBva.uk6/78SPN11mgd4waV7niRtL1H1dREii',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-11-14 14:28:35',
			'modified' => '2014-11-14 14:28:35',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4e',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'test@passbolt.com',
			'password' => '$2a$10$NyZU26BWsUrrAn26fteKP.PBz.1yjAILbVvvhQHMzY5mTkPbBATCu',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-11-14 14:28:35',
			'modified' => '2014-11-14 14:28:35',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'darth.vader@passbolt.com',
			'password' => '$2a$10$BU03hd.o31UDGbIJwViXYObHMuhO7fHrOBk1qc597X6Byk3EEMS/W',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-11-14 14:28:35',
			'modified' => '2014-11-14 14:28:35',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => 'dada6042-c5cd-11e1-a0c5-080027796c51',
			'role_id' => '142c1340-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'root@passbolt.com',
			'password' => '$2a$10$tJHGIj6lKBv/ZwrA1x.ioOuH2.tUwpnY115nhoLXJ/Ur3jqR3dHkO',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-11-14 14:28:35',
			'modified' => '2014-11-14 14:28:35',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => 'eeee6042-c5cd-11e1-a0c5-080027796c51',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'utest@passbolt.com',
			'password' => '$2a$10$ZalC1xh1zpER5Mcs1hXD1uDnjcRgOI6ea590jEcZx410RASjMKlzO',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-11-14 14:28:35',
			'modified' => '2014-11-14 14:28:35',
			'created_by' => '',
			'modified_by' => ''
		),
	);

}

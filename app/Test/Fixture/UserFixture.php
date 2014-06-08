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
			'password' => '$2a$10$1NeqPWmTORNCzFnAScUJ8.zyC/00n81aWQIWHeV/OQazATwcfDkwG',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-05-08 05:50:12',
			'modified' => '2014-05-08 05:50:12',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'jean-rene@test.com',
			'password' => '$2a$10$ZblQpsF9v9F2ewhQZPk9QOVpZGbawn.2cfqQZ7wtAc4tlANuVa9AW',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-05-08 05:50:12',
			'modified' => '2014-05-08 05:50:12',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'cedric@passbolt.com',
			'password' => '$2a$10$hwaJuyiBVCBlZo2JmTfGVum28b/9ZHEiL8URg9nSG2xEtOfKIVboe',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-05-08 05:50:12',
			'modified' => '2014-05-08 05:50:12',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'remy@passbolt.com',
			'password' => '$2a$10$b0NJ2vff3YGHmG8gPtOdoOZ/3hlexDryO.FMV.gOAUIyPW1v3y.wW',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-05-08 05:50:12',
			'modified' => '2014-05-08 05:50:12',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'kevin@passbolt.com',
			'password' => '$2a$10$EBk.GN88ihaZEvEF/gumEunC4PPsYxLpTkuLobibuHBNFaooojjp2',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-05-08 05:50:12',
			'modified' => '2014-05-08 05:50:12',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'ismail@passbolt.com',
			'password' => '$2a$10$YpN2uiz9FKf1qsTByJ4hCucaI.bDedt8fAUFzMixkK4/NWmfexo/G',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-05-08 05:50:12',
			'modified' => '2014-05-08 05:50:12',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-f214-4549-9807-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'aurelie@passbolt.com',
			'password' => '$2a$10$Tn0cD9QuAZFWbzRxD0DAwe0wZAzXnujVsRGf68vq9yUDV1mIwaHvq',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-05-08 05:50:12',
			'modified' => '2014-05-08 05:50:12',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'myriam@passbolt.com',
			'password' => '$2a$10$OhkGSJYfYHoQkaV1mXCxZOKDB/3MZBJXkM6BOqNZwAb6UkXdOYDKi',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-05-08 05:50:12',
			'modified' => '2014-05-08 05:50:12',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'manager.nogroup@passbolt.com',
			'password' => '$2a$10$360m4D6rWZ.OVja9yFEsX.W4XWcxwhOGC/4m7UA6LDWzG.bZzotR2',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-05-08 05:50:11',
			'modified' => '2014-05-08 05:50:11',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => '533d346d-d378-4acc-affd-1663c0a895dc',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'frank@passbolt.com',
			'password' => '$2a$10$dN7.Bh4czDadzlaE6GijsOaF9sZHhBAwlbHApb2lUWifEaFg0.oRm',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-05-08 05:50:13',
			'modified' => '2014-05-08 05:50:13',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d3564-03e8-4963-94a7-178cc0a895dc',
			'role_id' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'guest@passbolt.com',
			'password' => '$2a$10$6/2UsHhfmWDvDDB3pUskxe39syo2cz7Z/gYRFGNgTr2GQVR4IIJne',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-05-08 05:50:13',
			'modified' => '2014-05-08 05:50:13',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d37a0-bc80-4945-9b11-1663c0a895dc',
			'role_id' => '142c1188-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'admin@passbolt.com',
			'password' => '$2a$10$5yUGc1x561PiTZ3BmMNiY.jJth7DfIeN6Dxet/si9K2H7y02hMKci',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-05-08 05:50:13',
			'modified' => '2014-05-08 05:50:13',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => 'abcdab9c-4380-adad-b4cc-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'a-usr1@companya.com',
			'password' => '$2a$10$VRowYIorhk.ERMY6AvQt..3MT9dqopPYNBoeZj00N1zkyc31/G4WK',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-05-08 05:50:13',
			'modified' => '2014-05-08 05:50:13',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'role_id' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'anonymous@passbolt.com',
			'password' => '$2a$10$nW0UlRZg3jU3rpCRtOEM4OABsUmmETDsL/tXgYetEkQVEIMQ/1DeS',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-05-08 05:50:11',
			'modified' => '2014-05-08 05:50:11',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4e',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'test@passbolt.com',
			'password' => '$2a$10$WFaKw6sRinQ53IuncdWU/OaAwm6oWriRbp2NJt0DFINt1jli9PBnK',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-05-08 05:50:11',
			'modified' => '2014-05-08 05:50:11',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'dark.vador@passbolt.com',
			'password' => '$2a$10$L8Q0/ip97oCoXdzsjt5O8e5ZfHKbutDYJ1iiMuSMbcOGIr4KSxCXG',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-05-08 05:50:11',
			'modified' => '2014-05-08 05:50:11',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => 'dada6042-c5cd-11e1-a0c5-080027796c51',
			'role_id' => '142c1340-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'root@passbolt.com',
			'password' => '$2a$10$pc9YzhJqab5OaLgEVTXCPuNlVltNgxqDGPa7cqWEsYLmzgVbHFH.a',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-05-08 05:50:11',
			'modified' => '2014-05-08 05:50:11',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => 'eeee6042-c5cd-11e1-a0c5-080027796c51',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'utest@passbolt.com',
			'password' => '$2a$10$Cw06Wy/hvOeB3plRdAKGlOaocBtuIIE/inacVlHoPyZTLFYuEOTqS',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-05-08 05:50:11',
			'modified' => '2014-05-08 05:50:11',
			'created_by' => '',
			'modified_by' => ''
		),
	);

}

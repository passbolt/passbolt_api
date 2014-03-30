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
			'password' => '$2a$04$79b58df64165554f7ec93ux9U4IVP4nQUr491cMKGJnLqaFR28Jda',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-03-18 06:28:43',
			'modified' => '2014-03-18 06:28:43',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdab9c-4380-adad-b4cc-2f4fd7a10fce',
			'role_id' => '142c1188-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'admin@passbolt.com',
			'password' => '$2a$04$79b58df64165554f7ec93ux9U4IVP4nQUr491cMKGJnLqaFR28Jda',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-03-18 06:28:43',
			'modified' => '2014-03-18 06:28:43',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdab9c-4380-fafa-b4cc-2f4fd7a10fce',
			'role_id' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'guest@passbolt.com',
			'password' => '$2a$04$79b58df64165554f7ec93ux9U4IVP4nQUr491cMKGJnLqaFR28Jda',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-03-18 06:28:43',
			'modified' => '2014-03-18 06:28:43',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'jean-rene@test.com',
			'password' => '$2a$04$79b58df64165554f7ec93ux9U4IVP4nQUr491cMKGJnLqaFR28Jda',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-03-18 06:28:43',
			'modified' => '2014-03-18 06:28:43',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'cedric@passbolt.com',
			'password' => '$2a$04$79b58df64165554f7ec93ux9U4IVP4nQUr491cMKGJnLqaFR28Jda',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-03-18 06:28:43',
			'modified' => '2014-03-18 06:28:43',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'remy@passbolt.com',
			'password' => '$2a$04$79b58df64165554f7ec93ux9U4IVP4nQUr491cMKGJnLqaFR28Jda',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-03-18 06:28:43',
			'modified' => '2014-03-18 06:28:43',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'kevin@passbolt.com',
			'password' => '$2a$04$79b58df64165554f7ec93ux9U4IVP4nQUr491cMKGJnLqaFR28Jda',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-03-18 06:28:43',
			'modified' => '2014-03-18 06:28:43',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'ismail@passbolt.com',
			'password' => '$2a$04$79b58df64165554f7ec93ux9U4IVP4nQUr491cMKGJnLqaFR28Jda',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-03-18 06:28:43',
			'modified' => '2014-03-18 06:28:43',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-f214-4549-9807-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'aurelie@passbolt.com',
			'password' => '$2a$04$79b58df64165554f7ec93ux9U4IVP4nQUr491cMKGJnLqaFR28Jda',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-03-18 06:28:43',
			'modified' => '2014-03-18 06:28:43',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'myriam@passbolt.com',
			'password' => '$2a$04$79b58df64165554f7ec93ux9U4IVP4nQUr491cMKGJnLqaFR28Jda',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-03-18 06:28:43',
			'modified' => '2014-03-18 06:28:43',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => 'abcd6042-c5cd-efef-a0c5-080027796c4c',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'manager.nogroup@passbolt.com',
			'password' => '$2a$04$79b58df64165554f7ec93u40w4eEgwRJDM.pKQui7rRLkgvVYry8G',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-03-18 06:28:43',
			'modified' => '2014-03-18 06:28:43',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => 'abcdab9c-4380-adad-b4cc-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'a-usr1@companya.com',
			'password' => '$2a$04$79b58df64165554f7ec93ux9U4IVP4nQUr491cMKGJnLqaFR28Jda',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-03-18 06:28:43',
			'modified' => '2014-03-18 06:28:43',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'role_id' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'anonymous@passbolt.com',
			'password' => '$2a$04$79b58df64165554f7ec93ujxNGKAH26vrXfyhnrDdzoKPamfKtyLO',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-03-18 06:28:43',
			'modified' => '2014-03-18 06:28:43',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4e',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'test@passbolt.com',
			'password' => '$2a$04$79b58df64165554f7ec93ux9U4IVP4nQUr491cMKGJnLqaFR28Jda',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-03-18 06:28:43',
			'modified' => '2014-03-18 06:28:43',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'dark.vador@passbolt.com',
			'password' => '$2a$04$79b58df64165554f7ec93uRhvp4LzyYR6kbXJcuiCVQcQEbRZ3ycm',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-03-18 06:28:43',
			'modified' => '2014-03-18 06:28:43',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => 'dada6042-c5cd-11e1-a0c5-080027796c51',
			'role_id' => '142c1340-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'root@passbolt.com',
			'password' => '$2a$04$79b58df64165554f7ec93urgzH5Gj3aDSDISd8AtPi9qEf86z.Yqi',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-03-18 06:28:43',
			'modified' => '2014-03-18 06:28:43',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => 'eeee6042-c5cd-11e1-a0c5-080027796c51',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'utest@passbolt.com',
			'password' => '$2a$04$79b58df64165554f7ec93urgzH5Gj3aDSDISd8AtPi9qEf86z.Yqi',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-03-18 06:28:43',
			'modified' => '2014-03-18 06:28:43',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => 'fafaab9c-4380-adad-b4cc-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'frank@passbolt.com',
			'password' => '$2a$04$79b58df64165554f7ec93ux9U4IVP4nQUr491cMKGJnLqaFR28Jda',
			'active' => '1',
			'deleted' => 0,
			'created' => '2014-03-18 06:28:43',
			'modified' => '2014-03-18 06:28:43',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
	);

}

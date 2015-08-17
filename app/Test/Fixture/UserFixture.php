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
			'password' => '$2a$10$LOsrO8u8iNKaCTy4UcYgueRX4VedHN7iUo.SEzufdsFa18chh8Jqe',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-14 14:08:40',
			'modified' => '2015-08-14 14:08:40',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'jean@passbolt.com',
			'password' => '$2a$10$1kCA0kYRRtGQOzl8fLJrSe0eOhDxQtXN6N5wpMSU9e/kbdC3.1F1q',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-14 14:08:41',
			'modified' => '2015-08-14 14:08:41',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'carol@passbolt.com',
			'password' => '$2a$10$J9.RUQ6uflerhiYfjv.NYO7IJ.414JAHnlUPixemSPa3sIEn0PC8i',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-14 14:08:40',
			'modified' => '2015-08-14 14:08:40',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'ada@passbolt.com',
			'password' => '$2a$10$udIPn12L1qJV7BX4TfTaBOzODUUwvxxfPWYTG3dkDRGJ3rZd.h87y',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-14 14:08:40',
			'modified' => '2015-08-14 14:08:40',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'betty@passbolt.com',
			'password' => '$2a$10$4A5hN5Thpjh0eZmHkDFuBuscsJ1vjpw1NFcfeJGqs9v3R08E37SXC',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-14 14:08:40',
			'modified' => '2015-08-14 14:08:40',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'irene@passbolt.com',
			'password' => '$2a$10$YiAAyB4aVXsqqTeJ2TJ.o./8GUzg6x9ow34Nl9cBOHbXJZ1z.as2O',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-14 14:08:41',
			'modified' => '2015-08-14 14:08:41',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-f214-4549-9807-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'grace@passbolt.com',
			'password' => '$2a$10$ObccdHYuhh3hp276HqxK4uRG2ONWjz2D/LmymlGfTiav7iWqeRJQe',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-14 14:08:41',
			'modified' => '2015-08-14 14:08:41',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'marlyn@passbolt.com',
			'password' => '$2a$10$poAYjEx8l1WRgOTNvpCzieP6fEcDsYrZjsa2D8toFTShN3cNZAPTW',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-14 14:08:41',
			'modified' => '2015-08-14 14:08:41',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'lynne@passbolt.com',
			'password' => '$2a$10$46H8CwfGdS7EN5reJpxdYuIVibAHXDLPaO2ZN8v3.n3X3E9BiT0he',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-14 14:08:41',
			'modified' => '2015-08-14 14:08:41',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d346d-d378-4acc-affd-1663c0a895dc',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'frances@passbolt.com',
			'password' => '$2a$10$YmbW.gvK6jJDJRSeHGEWW.Mt/iEYNDF7Ku2RkKDuRS29wg1EFWFs6',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-14 14:08:41',
			'modified' => '2015-08-14 14:08:41',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d3564-03e8-4963-94a7-178cc0a895dc',
			'role_id' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'guest@passbolt.com',
			'password' => '$2a$10$pXIvUDoKt/20KExstBpSIOH1MeMjcxkO6B1.DC8MNUzbf1Djllr4m',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-14 14:08:40',
			'modified' => '2015-08-14 14:08:40',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'hedy@passbolt.com',
			'password' => '$2a$10$zvIr/IRL6g08u3gRwgx0AeAB/v1lp7ybLfQXA68vpwFhCWcZMQHTO',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-14 14:08:41',
			'modified' => '2015-08-14 14:08:41',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d37a0-bc80-4945-9b11-1663c0a895dc',
			'role_id' => '142c1188-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'admin@passbolt.com',
			'password' => '$2a$10$SliOrVbGwW4umlQtiOSu6eSZCcmLTAsNfGYJITgssVlNGPH4dEaMW',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-14 14:08:40',
			'modified' => '2015-08-14 14:08:40',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'role_id' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'anonymous@passbolt.com',
			'password' => '$2a$10$h536PXWpOt8CvjnEBrJfc.zdvtAq3Ia0XfjU8Hn9xQ4f4qAoWvUfK',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-14 14:08:40',
			'modified' => '2015-08-14 14:08:40',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4e',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'edith@passbolt.com',
			'password' => '$2a$10$UikexKwjtuHTwuBRdZI2XOoymdxrKfLwEvjwn0swjCDZ4B4uK8ABS',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-14 14:08:40',
			'modified' => '2015-08-14 14:08:40',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'dame@passbolt.com',
			'password' => '$2a$10$ExKzPUG7mxyuEUxw4ziqs.zIRfJjtLCUinEI7ImVwznzmgp5CEW2y',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-14 14:08:40',
			'modified' => '2015-08-14 14:08:40',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => 'dada6042-c5cd-11e1-a0c5-080027796c51',
			'role_id' => '142c1340-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'root@passbolt.com',
			'password' => '$2a$10$qD7flE0dYWxmL4ZQ9MMXz.idY07XHOhBqpZSIAa.kInPXkwBJsyeq',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-14 14:08:40',
			'modified' => '2015-08-14 14:08:40',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => 'eeee6042-c5cd-11e1-a0c5-080027796c51',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'kathleen@passbolt.com',
			'password' => '$2a$10$i01Hcze08JXmE5SBNZSZaOnWeORxFcxDawt/MXvDYBDPYO0/8i9te',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-14 14:08:41',
			'modified' => '2015-08-14 14:08:41',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
	);

}

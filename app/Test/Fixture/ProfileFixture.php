<?php
/**
 * Profile Fixture
 */
class ProfileFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'user_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'gender' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 1, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'date_of_birth' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'title' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 16, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'first_name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'last_name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'timezone' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 64, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'locale' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 5, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
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
			'id' => '03d07407-0615-366a-abe7-5a34075e642b',
			'user_id' => '51925e16-17a8-38f8-a923-6b1f75337d2a',
			'gender' => 'f',
			'date_of_birth' => '1924-01-01 00:00:00',
			'title' => 'Ms',
			'first_name' => 'Ruth',
			'last_name' => 'Teitelbaum',
			'timezone' => null,
			'locale' => null,
			'created' => '2017-03-04 17:46:29',
			'modified' => '2017-03-04 17:46:29'
		),
		array(
			'id' => '04fe48d8-b2ef-3cf2-a012-3be3a550630e',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'gender' => 'f',
			'date_of_birth' => '1922-01-01 00:00:00',
			'title' => 'Ms',
			'first_name' => 'Marlyn',
			'last_name' => 'Wescoff',
			'timezone' => null,
			'locale' => null,
			'created' => '2017-03-04 17:46:29',
			'modified' => '2017-03-04 17:46:29'
		),
		array(
			'id' => '10d65711-c0ef-3854-a7e8-cad27e798df0',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'gender' => 'f',
			'date_of_birth' => '1924-12-27 00:00:00',
			'title' => 'Ms',
			'first_name' => 'Jean',
			'last_name' => 'Bartik',
			'timezone' => null,
			'locale' => null,
			'created' => '2017-03-04 17:46:29',
			'modified' => '2017-03-04 17:46:29'
		),
		array(
			'id' => '1c60d6e5-a083-3802-a987-3ef3844747f2',
			'user_id' => '8a53f107-7ffc-3047-ad08-75effc419d21',
			'gender' => 'f',
			'date_of_birth' => '1924-02-21 00:00:00',
			'title' => 'Ms',
			'first_name' => 'Ursula',
			'last_name' => 'Martin',
			'timezone' => null,
			'locale' => null,
			'created' => '2017-03-04 17:46:29',
			'modified' => '2017-03-04 17:46:29'
		),
		array(
			'id' => '21f5dab2-7386-333e-a3f6-4f2c8fc69f42',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'gender' => 'f',
			'date_of_birth' => '1921-02-12 00:00:00',
			'title' => 'Ms',
			'first_name' => 'Kathleen',
			'last_name' => 'Antonelli',
			'timezone' => null,
			'locale' => null,
			'created' => '2017-03-04 17:46:29',
			'modified' => '2017-03-04 17:46:29'
		),
		array(
			'id' => '22cd59b4-8244-3489-a96b-6869ad83a9f9',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'gender' => 'f',
			'date_of_birth' => '1961-06-30 00:00:00',
			'title' => 'Ms',
			'first_name' => 'Lynne',
			'last_name' => 'Jolitz',
			'timezone' => null,
			'locale' => null,
			'created' => '2017-03-04 17:46:29',
			'modified' => '2017-03-04 17:46:29'
		),
		array(
			'id' => '2d5c4096-8101-3e70-a336-f8583467c96e',
			'user_id' => 'be77c78c-b767-3290-ae92-d5e5c590fcf9',
			'gender' => 'f',
			'date_of_birth' => '1958-12-01 00:00:00',
			'title' => 'Ms',
			'first_name' => 'Ping',
			'last_name' => 'Fu',
			'timezone' => null,
			'locale' => null,
			'created' => '2017-03-04 17:46:29',
			'modified' => '2017-03-04 17:46:29'
		),
		array(
			'id' => '34d8f698-73a7-339f-a63e-43a7a0ee58e0',
			'user_id' => 'bfb648f5-9f37-343b-a616-7f3704d13d84',
			'gender' => 'f',
			'date_of_birth' => '1924-02-21 00:00:00',
			'title' => 'Ms',
			'first_name' => 'Thelma',
			'last_name' => 'Estrin',
			'timezone' => null,
			'locale' => null,
			'created' => '2017-03-04 17:46:29',
			'modified' => '2017-03-04 17:46:29'
		),
		array(
			'id' => '4db7a1c2-e0af-399a-a1c0-c854bb1ee23e',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'gender' => 'f',
			'date_of_birth' => '1980-12-14 00:00:00',
			'title' => 'Ms',
			'first_name' => 'Hedy',
			'last_name' => 'Lamarr',
			'timezone' => null,
			'locale' => null,
			'created' => '2017-03-04 17:46:29',
			'modified' => '2017-03-04 17:46:29'
		),
		array(
			'id' => '513ba7da-0a15-34d7-ad87-c726f7f479a0',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'gender' => 'f',
			'date_of_birth' => '1986-07-14 00:00:00',
			'title' => 'Ms',
			'first_name' => 'Nancy',
			'last_name' => 'Leveson',
			'timezone' => null,
			'locale' => null,
			'created' => '2017-03-04 17:46:29',
			'modified' => '2017-03-04 17:46:29'
		),
		array(
			'id' => '7c834943-e54a-35eb-a995-52743a14bab3',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'gender' => 'f',
			'date_of_birth' => '1906-12-09 00:00:00',
			'title' => 'Ms',
			'first_name' => 'Grace',
			'last_name' => 'Hopper',
			'timezone' => null,
			'locale' => null,
			'created' => '2017-03-04 17:46:29',
			'modified' => '2017-03-04 17:46:29'
		),
		array(
			'id' => '8b2c51b6-175c-3284-a956-a87cba11ee56',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'gender' => 'f',
			'date_of_birth' => '1955-01-01 00:00:00',
			'title' => 'Ms',
			'first_name' => 'Carol',
			'last_name' => 'Shaw',
			'timezone' => null,
			'locale' => null,
			'created' => '2017-03-04 17:46:29',
			'modified' => '2017-03-04 17:46:29'
		),
		array(
			'id' => '934c513f-7a75-3415-a537-b318eda7a561',
			'user_id' => '1df25f7c-acf6-30fd-abb0-39e6a7462328',
			'gender' => 'f',
			'date_of_birth' => '1850-02-10 00:00:00',
			'title' => 'Ms',
			'first_name' => 'Sofia',
			'last_name' => 'Kovalevskaya',
			'timezone' => null,
			'locale' => null,
			'created' => '2017-03-04 17:46:29',
			'modified' => '2017-03-04 17:46:29'
		),
		array(
			'id' => '95aa284e-c91b-37da-ae1a-55051f2d1c60',
			'user_id' => '47ad29dc-f0c7-3b9e-aae2-9e657397b60b',
			'gender' => 'f',
			'date_of_birth' => '1966-01-01 00:00:00',
			'title' => 'Ms',
			'first_name' => 'Wang',
			'last_name' => 'Xiaoyun',
			'timezone' => null,
			'locale' => null,
			'created' => '2017-03-04 17:46:29',
			'modified' => '2017-03-04 17:46:29'
		),
		array(
			'id' => '9ad3a7b1-b6a2-34b5-aa79-7c9b1bf9bc18',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'gender' => 'f',
			'date_of_birth' => '1932-08-04 00:00:00',
			'title' => 'Ms',
			'first_name' => 'Frances',
			'last_name' => 'Allen',
			'timezone' => null,
			'locale' => null,
			'created' => '2017-03-04 17:46:29',
			'modified' => '2017-03-04 17:46:29'
		),
		array(
			'id' => 'c6cad8b7-b78d-30bc-aca7-aa3576e6bdfc',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'gender' => 'm',
			'date_of_birth' => '1980-12-10 00:00:00',
			'title' => 'Mr',
			'first_name' => 'Anonymous',
			'last_name' => 'User',
			'timezone' => null,
			'locale' => null,
			'created' => '2017-03-04 17:46:29',
			'modified' => '2017-03-04 17:46:29'
		),
		array(
			'id' => 'ce21824c-3137-3cbd-acbe-15bacf6bfac3',
			'user_id' => 'debaf643-1874-3466-abdd-ae64ed4e2ced',
			'gender' => 'f',
			'date_of_birth' => '1949-12-19 00:00:00',
			'title' => 'Ms',
			'first_name' => 'Orna',
			'last_name' => 'Berry',
			'timezone' => null,
			'locale' => null,
			'created' => '2017-03-04 17:46:29',
			'modified' => '2017-03-04 17:46:29'
		),
		array(
			'id' => 'cf4e8613-2811-3c04-a47d-d6d011721594',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'gender' => 'f',
			'date_of_birth' => '1815-12-10 00:00:00',
			'title' => 'Ms',
			'first_name' => 'Ada',
			'last_name' => 'Lovelace',
			'timezone' => null,
			'locale' => null,
			'created' => '2017-03-04 17:46:29',
			'modified' => '2017-03-04 17:46:29'
		),
		array(
			'id' => 'd34a0cc6-515f-34bb-a960-29b18036e03a',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'gender' => 'f',
			'date_of_birth' => '1933-09-16 00:00:00',
			'title' => 'Ms',
			'first_name' => 'Dame Steve',
			'last_name' => 'Shirley',
			'timezone' => null,
			'locale' => null,
			'created' => '2017-03-04 17:46:29',
			'modified' => '2017-03-04 17:46:29'
		),
		array(
			'id' => 'da270c4f-7e0a-3fcd-a551-4f6285c1bf0f',
			'user_id' => 'f0c51a43-35e3-321d-af6f-4fc48a460cb3',
			'gender' => 'm',
			'date_of_birth' => '1980-12-12 00:00:00',
			'title' => 'Mr',
			'first_name' => 'Default',
			'last_name' => 'User',
			'timezone' => null,
			'locale' => null,
			'created' => '2017-03-04 17:46:29',
			'modified' => '2017-03-04 17:46:29'
		),
		array(
			'id' => 'e0f3dafb-bc17-3c13-a982-bf43e8cf67c9',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'gender' => 'f',
			'date_of_birth' => '1917-03-07 00:00:00',
			'title' => 'Ms',
			'first_name' => 'Betty',
			'last_name' => 'Holberton',
			'timezone' => null,
			'locale' => null,
			'created' => '2017-03-04 17:46:29',
			'modified' => '2017-03-04 17:46:29'
		),
		array(
			'id' => 'f24cf31c-7156-33b7-a4c3-65ad06ec11da',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'gender' => 'f',
			'date_of_birth' => '1980-12-14 00:00:00',
			'title' => 'Ms',
			'first_name' => 'Irene',
			'last_name' => 'Greif',
			'timezone' => null,
			'locale' => null,
			'created' => '2017-03-04 17:46:29',
			'modified' => '2017-03-04 17:46:29'
		),
		array(
			'id' => 'f68fbc49-4c66-37da-abf7-df995e825372',
			'user_id' => '22e38bf3-6505-34c9-a6aa-55a906f18476',
			'gender' => 'm',
			'date_of_birth' => '1980-12-14 00:00:00',
			'title' => 'Mr',
			'first_name' => 'Root',
			'last_name' => 'User',
			'timezone' => null,
			'locale' => null,
			'created' => '2017-03-04 17:46:29',
			'modified' => '2017-03-04 17:46:29'
		),
		array(
			'id' => 'fc7f0002-f379-30f3-a680-bb8de2f0fa09',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'gender' => 'f',
			'date_of_birth' => '1983-10-29 00:00:00',
			'title' => 'Ms',
			'first_name' => 'Edith',
			'last_name' => 'Clarke',
			'timezone' => null,
			'locale' => null,
			'created' => '2017-03-04 17:46:29',
			'modified' => '2017-03-04 17:46:29'
		),
		array(
			'id' => 'fd3e940e-5d9b-3efc-aeb4-f027a1a6ad37',
			'user_id' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
			'gender' => 'm',
			'date_of_birth' => '1980-12-13 00:00:00',
			'title' => 'Mr',
			'first_name' => 'Admin',
			'last_name' => 'User',
			'timezone' => null,
			'locale' => null,
			'created' => '2017-03-04 17:46:29',
			'modified' => '2017-03-04 17:46:29'
		),
	);

}

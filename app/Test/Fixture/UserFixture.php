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
			'id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
			'username' => 'frances@passbolt.com',
			'password' => '$2a$10$mBYlG4ViT4tdTZyfsE2R7OHULZ1z05OMrQX5H7J/NZyS416ob/TLy',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-11-01 08:10:13',
			'modified' => '2015-11-01 08:10:13',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
			'username' => 'kathleen@passbolt.com',
			'password' => '$2a$10$nIIOl5Mx0KCj8M0jzeY.BOulwRf1MM9piO/88TKoKNHtsKkAHqwvG',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-11-01 08:10:13',
			'modified' => '2015-11-01 08:10:13',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '22e38bf3-6505-34c9-a6aa-55a906f18476',
			'role_id' => '857760a6-4f9d-3f1b-a292-95b630bcf03f',
			'username' => 'root@passbolt.com',
			'password' => '$2a$10$nCCuBTnD4JlvHsNJtazoDuYPeRyokIgF9OHs9likZ8XekGEH2UEJ2',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-11-01 08:10:12',
			'modified' => '2015-11-01 08:10:12',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
			'username' => 'carol@passbolt.com',
			'password' => '$2a$10$ZW19Ix5adr7Z2gbxA4nWvuN3PARkmWiGhwIUNI.C9TBOVzHQH87Fu',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-11-01 08:10:13',
			'modified' => '2015-11-01 08:10:13',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
			'role_id' => '23d941d5-3676-3443-afdb-aaf2456f3b49',
			'username' => 'admin@passbolt.com',
			'password' => '$2a$10$5YzsF7xL4YAIJTIXjXRxa.TsE7VVbxYoaXs4o3WS09Yw7HCSRagnW',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-11-01 08:10:12',
			'modified' => '2015-11-01 08:10:12',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
			'username' => 'hedy@passbolt.com',
			'password' => '$2a$10$TrHfsgZ39TzGmSVi0ELT7ex5Jo.nETyHafF8jTDnqS8BqTfplq426',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-11-01 08:10:13',
			'modified' => '2015-11-01 08:10:13',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
			'username' => 'jean@passbolt.com',
			'password' => '$2a$10$8EaWM3ex5FjJMOy2mvZjiO2cVQ8mYqWU6hLvhv83XrT6TI0Co7Abe',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-11-01 08:10:13',
			'modified' => '2015-11-01 08:10:13',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8a89ad9e-e1d3-30aa-a011-2818b447779c',
			'role_id' => '49aad81e-4f70-3380-a92e-12292597409f',
			'username' => 'guest@passbolt.com',
			'password' => '$2a$10$3TME.uWUmr576BX3S16J9u0UW8DOPHhCR9WHxqJTrN0Rlv/rXTIgy',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-11-01 08:10:12',
			'modified' => '2015-11-01 08:10:12',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
			'username' => 'marlyn@passbolt.com',
			'password' => '$2a$10$XYQdSjeBJH3y2PWjOlzurODBcTBfMwTHIFNPsAp.cwdvLJIVqx6dy',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-11-01 08:10:13',
			'modified' => '2015-11-01 08:10:13',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
			'username' => 'irene@passbolt.com',
			'password' => '$2a$10$Ea6jEH5suAtvRgRIx6LyNe00Mq6QgVsZUWifgji7YLvdJpQ34CLI6',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-11-01 08:10:13',
			'modified' => '2015-11-01 08:10:13',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'role_id' => '49aad81e-4f70-3380-a92e-12292597409f',
			'username' => 'anonymous@passbolt.com',
			'password' => '$2a$10$mZ0srFORLJR5RTyzP87l5eTWDKXAK/w2CdX4oo3ZJVZTpGnpsrWze',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-11-01 08:10:12',
			'modified' => '2015-11-01 08:10:12',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
			'username' => 'grace@passbolt.com',
			'password' => '$2a$10$7NJxedaJCWLpmiPQSrsyQei7XPoOElLMbXm/DER/wfUufX5vjX7OK',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-11-01 08:10:13',
			'modified' => '2015-11-01 08:10:13',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
			'username' => 'edith@passbolt.com',
			'password' => '$2a$10$yCKi47aNCN3f30wK0UEMJ.LHpNfwm.kSP1UBKhatqzAF0pPFzAaCS',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-11-01 08:10:13',
			'modified' => '2015-11-01 08:10:13',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
			'username' => 'ada@passbolt.com',
			'password' => '$2a$10$uJ7DOAlWikyOhUIgwpKU1uecQkENlDUeHCjzL1f5hByJYQxV4ASuK',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-11-01 08:10:12',
			'modified' => '2015-11-01 08:10:12',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
			'username' => 'betty@passbolt.com',
			'password' => '$2a$10$dIDHg2HTLpKcRv8rwH8WpeZMuDSHiFQV5s8K0x1fzQ.FPpikRaN2y',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-11-01 08:10:12',
			'modified' => '2015-11-01 08:10:12',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f0c51a43-35e3-321d-af6f-4fc48a460cb3',
			'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
			'username' => 'user@passbolt.com',
			'password' => '$2a$10$qqbqoa.iaNXwARJuw2BTS.5XY2OsvtJrKBssOL8afyq6B6OWrL.mu',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-11-01 08:10:12',
			'modified' => '2015-11-01 08:10:12',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
			'username' => 'lynne@passbolt.com',
			'password' => '$2a$10$Bnh6UKygQ52ujVLnAV/Lpe9rGdKnFYZcehhVng7JEJSJzU/Vt0bcW',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-11-01 08:10:13',
			'modified' => '2015-11-01 08:10:13',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
			'username' => 'dame@passbolt.com',
			'password' => '$2a$10$pDVv8cDswG.EPN89OSNC/OxhnsPISMqFaeGzpDk1V7IuUYe8j8HCG',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-11-01 08:10:13',
			'modified' => '2015-11-01 08:10:13',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}

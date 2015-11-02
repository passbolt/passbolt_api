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
			'password' => '$2a$10$/8d6xOQDTjzcaM6OhBeA5.bHEXb68v4GD.w7GRsqL7r.0ien.0gmS',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-11-02 09:43:17',
			'modified' => '2015-11-02 09:43:17',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
			'username' => 'kathleen@passbolt.com',
			'password' => '$2a$10$jWvHiQdnsEmNYqk0ylWIe.imZROpNb.LSvCE71kWTQ52DXu.qsHj.',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-11-02 09:43:18',
			'modified' => '2015-11-02 09:43:18',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '22e38bf3-6505-34c9-a6aa-55a906f18476',
			'role_id' => '857760a6-4f9d-3f1b-a292-95b630bcf03f',
			'username' => 'root@passbolt.com',
			'password' => '$2a$10$G3nwhHcqVL9ht73uwF4H/eSAxhZ3tzP5b7xMPvcLA63GFueiwYQwK',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-11-02 09:43:17',
			'modified' => '2015-11-02 09:43:17',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
			'username' => 'carol@passbolt.com',
			'password' => '$2a$10$r7KUcxvdNLrmA5DnTIA91.TeI42cGpjQ0TTFcF5dOmCpI1bXme.0S',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-11-02 09:43:17',
			'modified' => '2015-11-02 09:43:17',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
			'role_id' => '23d941d5-3676-3443-afdb-aaf2456f3b49',
			'username' => 'admin@passbolt.com',
			'password' => '$2a$10$YCzsEoWWpgCKo1qxxh5teewd1EQzQKTChZFtfo4li9RNNfXU.xY9C',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-11-02 09:43:17',
			'modified' => '2015-11-02 09:43:17',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
			'username' => 'hedy@passbolt.com',
			'password' => '$2a$10$VX6kjCa90YMU//LkhUilkeEH7HgQT/fQ1cmabNiueCuWPh7fNy2NK',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-11-02 09:43:17',
			'modified' => '2015-11-02 09:43:17',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
			'username' => 'jean@passbolt.com',
			'password' => '$2a$10$kM6wSBmOcFavoV5Qd/pqOuBUG.VbrGYZtFSMYDpasxN0gEnN1uosi',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-11-02 09:43:18',
			'modified' => '2015-11-02 09:43:18',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8a89ad9e-e1d3-30aa-a011-2818b447779c',
			'role_id' => '49aad81e-4f70-3380-a92e-12292597409f',
			'username' => 'guest@passbolt.com',
			'password' => '$2a$10$189ddUfE82qRDnOudLE/dO/KjnCxOfcIpZMpFoz5Q7YIaaByv6y0a',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-11-02 09:43:17',
			'modified' => '2015-11-02 09:43:17',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
			'username' => 'marlyn@passbolt.com',
			'password' => '$2a$10$EDo4ejGygw1A08OwxTSbZODslERpYsW3.It1EiOUvW35.WKCgGO3y',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-11-02 09:43:18',
			'modified' => '2015-11-02 09:43:18',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
			'username' => 'irene@passbolt.com',
			'password' => '$2a$10$LGfym8V06t4Rp5qtKT3MYe0of13XMRirxJbdSLTTLG7Nshdqb9KwG',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-11-02 09:43:17',
			'modified' => '2015-11-02 09:43:17',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'role_id' => '49aad81e-4f70-3380-a92e-12292597409f',
			'username' => 'anonymous@passbolt.com',
			'password' => '$2a$10$/qK9TJbkfLOXVRcxpJwmY.XcNiC001SRkRVfWCFHZ/7x9SCtzBmbK',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-11-02 09:43:16',
			'modified' => '2015-11-02 09:43:16',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
			'username' => 'grace@passbolt.com',
			'password' => '$2a$10$2824LTM4OHGUxqjQgFhuS.DiNNxEaY0gQ8GpqYle4cR0FLM0Y77j2',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-11-02 09:43:17',
			'modified' => '2015-11-02 09:43:17',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
			'username' => 'edith@passbolt.com',
			'password' => '$2a$10$Flmmv7sqFDiRSwaDQNMG4ud93FuYrNOE0w.3qyZJ1veSaxaq9u.ka',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-11-02 09:43:17',
			'modified' => '2015-11-02 09:43:17',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
			'username' => 'ada@passbolt.com',
			'password' => '$2a$10$RxM2tffSQLKJALwvrDZMY.Y1TlbuOji/mCB03Mxm2csfntoba1Cf.',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-11-02 09:43:17',
			'modified' => '2015-11-02 09:43:17',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
			'username' => 'betty@passbolt.com',
			'password' => '$2a$10$352CIfHYRPJ7MgZv9OsH7em7cC4VoJJ2vlO5NSnvvhzYQP.r5dUnG',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-11-02 09:43:17',
			'modified' => '2015-11-02 09:43:17',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f0c51a43-35e3-321d-af6f-4fc48a460cb3',
			'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
			'username' => 'user@passbolt.com',
			'password' => '$2a$10$0DGwI/bHGe3B1RXJ1r3ykeg5f3nBTEQdyDIl1VVa2mZYyQqjgTfrm',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-11-02 09:43:17',
			'modified' => '2015-11-02 09:43:17',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
			'username' => 'lynne@passbolt.com',
			'password' => '$2a$10$t/OuXsmZ/IMIqis2DqwuN.CdBJ6X/R3D/TcE6c0XpkQqQb/qOViVC',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-11-02 09:43:18',
			'modified' => '2015-11-02 09:43:18',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'role_id' => 'd1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc',
			'username' => 'dame@passbolt.com',
			'password' => '$2a$10$dGJwFTu2sQq58WmUWKM4.OjOeuOp78iZaayQjlHkbyeuClv5Oe/j.',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-11-02 09:43:17',
			'modified' => '2015-11-02 09:43:17',
			'created_by' => '',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}

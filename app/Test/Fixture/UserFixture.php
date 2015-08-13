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
			'password' => '$2a$10$QkPdD21gIAskEJriZPDWlOfd2Qvdwjuu5mek9X9L7yXBoxQluEEgK',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-09 15:19:26',
			'modified' => '2015-08-09 15:19:26',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'jean@passbolt.com',
			'password' => '$2a$10$82KL1k5/wntRzV0MZm0uee1bzFNeXi9AwKBtJjYdebPXWNAE/EWCu',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-09 15:19:27',
			'modified' => '2015-08-09 15:19:27',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'carol@passbolt.com',
			'password' => '$2a$10$nXZ7Q6B2/bcuBeBT9bubXu7KNTshYPQfHJmMr4XCPBVTtd6OtAVcm',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-09 15:19:26',
			'modified' => '2015-08-09 15:19:26',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'ada@passbolt.com',
			'password' => '$2a$10$28RJz9nazOw/kVIpDqqDZ.URc2P/78OyUpwTIIGUAWVYhAOol4s8S',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-09 15:19:26',
			'modified' => '2015-08-09 15:19:26',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'betty@passbolt.com',
			'password' => '$2a$10$0S2npECrMiUEqdvO0rtfn.lI1.PQZFDIMziLTR6cLAWXP1Cp0/s7i',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-09 15:19:26',
			'modified' => '2015-08-09 15:19:26',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'irene@passbolt.com',
			'password' => '$2a$10$rj70.3/6aTSOPblI.K/C9.DNDYvtiexre5WcYdVU4hNtFLh03ucJe',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-09 15:19:27',
			'modified' => '2015-08-09 15:19:27',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-f214-4549-9807-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'grace@passbolt.com',
			'password' => '$2a$10$sbMm9Fc50DZ9MBbhXwJQsOFIh3qQZ8k0nTtBO6IPAhi02L2HLp6pW',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-09 15:19:27',
			'modified' => '2015-08-09 15:19:27',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'marlyn@passbolt.com',
			'password' => '$2a$10$ijiYPulbwpnq0qe06Xw2P.FC0llbQ13ekwEtRn8eFVxJcfqV/nWDS',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-09 15:19:27',
			'modified' => '2015-08-09 15:19:27',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'lynne@passbolt.com',
			'password' => '$2a$10$C5wh5ClFFV5p2Z8Uv.ofi.SGD/VfEqW4VJY7qn41Q0ddZWtSMPb8u',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-09 15:19:27',
			'modified' => '2015-08-09 15:19:27',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d346d-d378-4acc-affd-1663c0a895dc',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'frances@passbolt.com',
			'password' => '$2a$10$DiKqQILd.kT8chw2dg/6buhXNSFv3yv3Cw1kpAnXNzcCrZNgFykc.',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-09 15:19:27',
			'modified' => '2015-08-09 15:19:27',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d3564-03e8-4963-94a7-178cc0a895dc',
			'role_id' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'guest@passbolt.com',
			'password' => '$2a$10$aWxcEod8wl.aBTySvGnSduXr6xmBZIz0zcIqRvrCJ.R110r7kMDFG',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-09 15:19:26',
			'modified' => '2015-08-09 15:19:26',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'hedy@passbolt.com',
			'password' => '$2a$10$InU27/eQYD2QxT/5Id3cg.a5tyxiwwn9PRFOoZ1ncscdVbCyoWSUm',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-09 15:19:27',
			'modified' => '2015-08-09 15:19:27',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d37a0-bc80-4945-9b11-1663c0a895dc',
			'role_id' => '142c1188-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'admin@passbolt.com',
			'password' => '$2a$10$6wQidf6iN0b5co7/aC6EXetGQ0HWgcWZ.t9.unlWv4.Jz3wyJFCYq',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-09 15:19:26',
			'modified' => '2015-08-09 15:19:26',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'role_id' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'anonymous@passbolt.com',
			'password' => '$2a$10$J7CJ9r2Cq1M.iTFkNzW2eOYXFCVyitbu69NAfk5SuwY9qT2xr5BT.',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-09 15:19:26',
			'modified' => '2015-08-09 15:19:26',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4e',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'edith@passbolt.com',
			'password' => '$2a$10$KYNDYM9eI.L8Tp98V5mnUOtvsXH386KtlJjrm.Gbqf/EITF.UNnDa',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-09 15:19:26',
			'modified' => '2015-08-09 15:19:26',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'dame@passbolt.com',
			'password' => '$2a$10$TjEcv4q3pknN/hTkkQu2xu65RxQuKH7rhX88BDlQygpcP2/xhDfuG',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-09 15:19:26',
			'modified' => '2015-08-09 15:19:26',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => 'dada6042-c5cd-11e1-a0c5-080027796c51',
			'role_id' => '142c1340-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'root@passbolt.com',
			'password' => '$2a$10$QpAh6m7w7ORloohTjF3N7edIFLQGJW0QyVL20Wvzd56YofgQ1MDwO',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-09 15:19:26',
			'modified' => '2015-08-09 15:19:26',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => 'eeee6042-c5cd-11e1-a0c5-080027796c51',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'kathleen@passbolt.com',
			'password' => '$2a$10$CmV7kHhz.bYMe.3IBtZqE.B.4NH3FbwhaCyql2IM1SMO7VDAhbH56',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-08-09 15:19:27',
			'modified' => '2015-08-09 15:19:27',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
	);

}

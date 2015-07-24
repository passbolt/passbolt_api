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
			'password' => '$2a$10$.0tLvY8etLzLV7ym5QbKF.sJnBk7HMO4fdtzjS66W66udHnHJ5OjW',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-07-03 13:00:16',
			'modified' => '2015-07-03 13:00:16',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'jean@passbolt.com',
			'password' => '$2a$10$OuotGlWyTZFevqMjU84mAObwuN61GuvMJ7be.2xpFJTe9lSFkvFWi',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-07-03 13:00:17',
			'modified' => '2015-07-03 13:00:17',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'carol@passbolt.com',
			'password' => '$2a$10$CsyJJzboRjS5Ryu.VBflfe1GYlZV5/Gux8P7gBHewRxXVqzSVrnyO',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-07-03 13:00:17',
			'modified' => '2015-07-03 13:00:17',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'ada@passbolt.com',
			'password' => '$2a$10$QMgxCTLVEZuHHh6MZQ9yQ.U/PZgtPB33cHKhC83tdaJkgVzK8/TjC',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-07-03 13:00:17',
			'modified' => '2015-07-03 13:00:17',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'betty@passbolt.com',
			'password' => '$2a$10$0ZKjiWcQrT370j0okqRQ4ObGIgiyWzDM1AgKCPHfP9RF/kaTXTJ52',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-07-03 13:00:17',
			'modified' => '2015-07-03 13:00:17',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'irene@passbolt.com',
			'password' => '$2a$10$OupXBbkx/7Hm7fY5seJ6b.2lLBWjsHQh2d8jfvIytsfQeRBpWG3tu',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-07-03 13:00:17',
			'modified' => '2015-07-03 13:00:17',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-f214-4549-9807-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'grace@passbolt.com',
			'password' => '$2a$10$b3kHzpKeNNB11QpHgbFxuO1l1gEidhV48SH0ulKm8INaZPt5QhPoO',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-07-03 13:00:17',
			'modified' => '2015-07-03 13:00:17',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'marlyn@passbolt.com',
			'password' => '$2a$10$V7VsMk2zpInNvCnf/cn.6e/3t9pu8Hw51LyNSQl1Okd/U2RYicMU6',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-07-03 13:00:18',
			'modified' => '2015-07-03 13:00:18',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'lynne@passbolt.com',
			'password' => '$2a$10$sJVGZZrrFHwqJcbso41CzeG7eJTKWwWinwG3i41DtrRSP0cFq4PRq',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-07-03 13:00:18',
			'modified' => '2015-07-03 13:00:18',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d346d-d378-4acc-affd-1663c0a895dc',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'frances@passbolt.com',
			'password' => '$2a$10$Md5OlxvJO81S4Z9t2bzdre4jZ5NTiHv8qew5MzRluLNr/U6x55bG2',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-07-03 13:00:17',
			'modified' => '2015-07-03 13:00:17',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d3564-03e8-4963-94a7-178cc0a895dc',
			'role_id' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'guest@passbolt.com',
			'password' => '$2a$10$Ui/5UyGmKLlqssCEsiJDAOY.C5KgDqdv.Se40s/5m1xCuuKmWyipW',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-07-03 13:00:16',
			'modified' => '2015-07-03 13:00:16',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'hedy@passbolt.com',
			'password' => '$2a$10$ut8fRtdu8Y.yPmA024f7Le.K6q39Jv4QAidSZ2nlghp/.kOEKmOzy',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-07-03 13:00:17',
			'modified' => '2015-07-03 13:00:17',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '533d37a0-bc80-4945-9b11-1663c0a895dc',
			'role_id' => '142c1188-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'admin@passbolt.com',
			'password' => '$2a$10$of2tZArCYqV7GPXv14KE7eNY1TlRmH1ir9oI5kIDvRGtWZh/naDmC',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-07-03 13:00:17',
			'modified' => '2015-07-03 13:00:17',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'role_id' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'anonymous@passbolt.com',
			'password' => '$2a$10$Mr1vhp/.EblHiDgy.5Bz.OunIZMUP6KuiWoO9P4mxn7IJXVlKJsle',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-07-03 13:00:16',
			'modified' => '2015-07-03 13:00:16',
			'created_by' => '',
			'modified_by' => ''
		),
		array(
			'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4e',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'edith@passbolt.com',
			'password' => '$2a$10$TlM2wKCebtfyvrYK4SUhOez3KS6GjCk/sh3YUFmwKwmJRhZzhPSAi',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-07-03 13:00:17',
			'modified' => '2015-07-03 13:00:17',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'dame@passbolt.com',
			'password' => '$2a$10$6twtuOFEUBlfLab/PCjCpe3/DDI44JzmWaHholmAS9H70iaVmTbsy',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-07-03 13:00:17',
			'modified' => '2015-07-03 13:00:17',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => 'dada6042-c5cd-11e1-a0c5-080027796c51',
			'role_id' => '142c1340-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'root@passbolt.com',
			'password' => '$2a$10$W/pYebM/TsOlBjUKVSEk/eSZoTKSUEkAxVF50XY1oExKV3YyqrNcK',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-07-03 13:00:17',
			'modified' => '2015-07-03 13:00:17',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => 'eeee6042-c5cd-11e1-a0c5-080027796c51',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'kathleen@passbolt.com',
			'password' => '$2a$10$gRQZhqjIjjXC7q1MheEqtO2dnCffs5tfLi1Oeuo5RxS4vTnTxkQP.',
			'active' => '1',
			'deleted' => 0,
			'created' => '2015-07-03 13:00:18',
			'modified' => '2015-07-03 13:00:18',
			'created_by' => '',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
	);

}

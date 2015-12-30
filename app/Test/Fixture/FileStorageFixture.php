<?php
/**
 * FileStorageFixture
 *
 */
class FileStorageFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'file_storage';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'user_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'foreign_key' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'model' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 64, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'filename' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'filesize' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 16, 'unsigned' => false),
		'mime_type' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 32, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'extension' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 5, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'hash' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 64, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'path' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'adapter' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 32, 'collate' => 'latin1_swedish_ci', 'comment' => 'Gaufrette Storage Adapter Class', 'charset' => 'latin1'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '568420b5-06f4-40d3-9d2b-4a58ac110001',
			'user_id' => null,
			'foreign_key' => 'e0f3dafb-bc17-3c13-a982-bf43e8cf67c9',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '115942',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/59/19/31/568420b506f440d39d2b4a58ac110001/',
			'adapter' => 'Local',
			'created' => '2015-12-30 18:21:41',
			'modified' => '2015-12-30 18:21:41'
		),
		array(
			'id' => '568420b5-0898-48b4-95a4-4a58ac110001',
			'user_id' => null,
			'foreign_key' => 'cf4e8613-2811-3c04-a47d-d6d011721594',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '170049',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/43/04/80/568420b5089848b495a44a58ac110001/',
			'adapter' => 'Local',
			'created' => '2015-12-30 18:21:41',
			'modified' => '2015-12-30 18:21:41'
		),
		array(
			'id' => '568420b5-257c-45ae-9bdd-4a58ac110001',
			'user_id' => null,
			'foreign_key' => 'fc7f0002-f379-30f3-a680-bb8de2f0fa09',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '20462',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/90/41/10/568420b5257c45ae9bdd4a58ac110001/',
			'adapter' => 'Local',
			'created' => '2015-12-30 18:21:41',
			'modified' => '2015-12-30 18:21:41'
		),
		array(
			'id' => '568420b5-6e28-4977-9842-4a58ac110001',
			'user_id' => null,
			'foreign_key' => '9ad3a7b1-b6a2-34b5-aa79-7c9b1bf9bc18',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '283883',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/48/53/27/568420b56e28497798424a58ac110001/',
			'adapter' => 'Local',
			'created' => '2015-12-30 18:21:40',
			'modified' => '2015-12-30 18:21:40'
		),
		array(
			'id' => '568420b5-fe18-423c-bb4e-4a58ac110001',
			'user_id' => null,
			'foreign_key' => '21f5dab2-7386-333e-a3f6-4f2c8fc69f42',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '53376',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/28/78/61/568420b5fe18423cbb4e4a58ac110001/',
			'adapter' => 'Local',
			'created' => '2015-12-30 18:21:41',
			'modified' => '2015-12-30 18:21:41'
		),
		array(
			'id' => '568420b6-5cf0-471f-8027-4a58ac110001',
			'user_id' => null,
			'foreign_key' => 'd34a0cc6-515f-34bb-a960-29b18036e03a',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '20676',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/95/72/61/568420b65cf0471f80274a58ac110001/',
			'adapter' => 'Local',
			'created' => '2015-12-30 18:21:42',
			'modified' => '2015-12-30 18:21:42'
		),
		array(
			'id' => '568420b6-af0c-4f90-a43c-4a58ac110001',
			'user_id' => null,
			'foreign_key' => '8b2c51b6-175c-3284-a956-a87cba11ee56',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '733439',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/84/69/45/568420b6af0c4f90a43c4a58ac110001/',
			'adapter' => 'Local',
			'created' => '2015-12-30 18:21:41',
			'modified' => '2015-12-30 18:21:41'
		),
	);

}

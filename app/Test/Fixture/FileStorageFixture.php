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
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_bin', 'charset' => 'utf8'),
		'user_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'utf8_bin', 'charset' => 'utf8'),
		'foreign_key' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'utf8_bin', 'charset' => 'utf8'),
		'model' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 64, 'collate' => 'utf8_bin', 'charset' => 'utf8'),
		'filename' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_bin', 'charset' => 'utf8'),
		'filesize' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 16, 'unsigned' => false),
		'mime_type' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 32, 'collate' => 'utf8_bin', 'charset' => 'utf8'),
		'extension' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 5, 'collate' => 'utf8_bin', 'charset' => 'utf8'),
		'hash' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 64, 'collate' => 'utf8_bin', 'charset' => 'utf8'),
		'path' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_bin', 'charset' => 'utf8'),
		'adapter' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 32, 'collate' => 'utf8_bin', 'comment' => 'Gaufrette Storage Adapter Class', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_bin', 'engine' => 'InnoDB')
	);
/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '5635caef-5f84-46b4-a8ec-5f9f9ee8d42c',
			'user_id' => null,
			'foreign_key' => '8b2c51b6-175c-3284-a956-a87cba11ee56',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '733439',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/66/95/73/5635caef5f8446b4a8ec5f9f9ee8d42c/',
			'adapter' => 'Local',
			'created' => '2015-11-01 08:18:55',
			'modified' => '2015-11-01 08:18:55'
		),
		array(
			'id' => '5635caef-649c-48b3-a2bb-5f9f9ee8d42c',
			'user_id' => null,
			'foreign_key' => 'cf4e8613-2811-3c04-a47d-d6d011721594',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '170049',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/36/54/54/5635caef649c48b3a2bb5f9f9ee8d42c/',
			'adapter' => 'Local',
			'created' => '2015-11-01 08:18:55',
			'modified' => '2015-11-01 08:18:55'
		),
		array(
			'id' => '5635caef-7668-408b-9ef3-5f9f9ee8d42c',
			'user_id' => null,
			'foreign_key' => 'e0f3dafb-bc17-3c13-a982-bf43e8cf67c9',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '115942',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/78/59/07/5635caef7668408b9ef35f9f9ee8d42c/',
			'adapter' => 'Local',
			'created' => '2015-11-01 08:18:55',
			'modified' => '2015-11-01 08:18:55'
		),
		array(
			'id' => '5635caf0-2a68-4229-bf1e-5f9f9ee8d42c',
			'user_id' => null,
			'foreign_key' => 'fc7f0002-f379-30f3-a680-bb8de2f0fa09',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '20462',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/83/81/26/5635caf02a684229bf1e5f9f9ee8d42c/',
			'adapter' => 'Local',
			'created' => '2015-11-01 08:18:56',
			'modified' => '2015-11-01 08:18:56'
		),
		array(
			'id' => '5635caf0-80dc-4d75-94ce-5f9f9ee8d42c',
			'user_id' => null,
			'foreign_key' => '21f5dab2-7386-333e-a3f6-4f2c8fc69f42',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '53376',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/55/19/33/5635caf080dc4d7594ce5f9f9ee8d42c/',
			'adapter' => 'Local',
			'created' => '2015-11-01 08:18:56',
			'modified' => '2015-11-01 08:18:56'
		),
		array(
			'id' => '5635caf0-8194-4f62-ae10-5f9f9ee8d42c',
			'user_id' => null,
			'foreign_key' => 'd34a0cc6-515f-34bb-a960-29b18036e03a',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '20676',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/80/99/78/5635caf081944f62ae105f9f9ee8d42c/',
			'adapter' => 'Local',
			'created' => '2015-11-01 08:18:56',
			'modified' => '2015-11-01 08:18:56'
		),
		array(
			'id' => '5635caf0-d814-4647-80d9-5f9f9ee8d42c',
			'user_id' => null,
			'foreign_key' => '9ad3a7b1-b6a2-34b5-aa79-7c9b1bf9bc18',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '283883',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/79/04/51/5635caf0d814464780d95f9f9ee8d42c/',
			'adapter' => 'Local',
			'created' => '2015-11-01 08:18:56',
			'modified' => '2015-11-01 08:18:56'
		),
	);

}

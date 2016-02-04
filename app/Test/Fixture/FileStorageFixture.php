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
			'id' => '56b2a10d-edb4-4c3f-b05d-3768ac110001',
			'user_id' => null,
			'foreign_key' => '9ad3a7b1-b6a2-34b5-aa79-7c9b1bf9bc18',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '283883',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/82/13/14/56b2a10dedb44c3fb05d3768ac110001/',
			'adapter' => 'Local',
			'created' => '2016-02-04 00:53:33',
			'modified' => '2016-02-04 00:53:33'
		),
		array(
			'id' => '56b2a10e-3bd0-4bd7-afee-3768ac110001',
			'user_id' => null,
			'foreign_key' => '21f5dab2-7386-333e-a3f6-4f2c8fc69f42',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '53376',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/25/55/98/56b2a10e3bd04bd7afee3768ac110001/',
			'adapter' => 'Local',
			'created' => '2016-02-04 00:53:34',
			'modified' => '2016-02-04 00:53:34'
		),
		array(
			'id' => '56b2a10e-e0d0-477a-ad19-3768ac110001',
			'user_id' => null,
			'foreign_key' => 'e0f3dafb-bc17-3c13-a982-bf43e8cf67c9',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '115942',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/42/27/47/56b2a10ee0d0477aad193768ac110001/',
			'adapter' => 'Local',
			'created' => '2016-02-04 00:53:34',
			'modified' => '2016-02-04 00:53:34'
		),
		array(
			'id' => '56b2a10e-ef6c-4f02-ab2c-3768ac110001',
			'user_id' => null,
			'foreign_key' => 'fc7f0002-f379-30f3-a680-bb8de2f0fa09',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '20462',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/97/73/33/56b2a10eef6c4f02ab2c3768ac110001/',
			'adapter' => 'Local',
			'created' => '2016-02-04 00:53:34',
			'modified' => '2016-02-04 00:53:34'
		),
		array(
			'id' => '56b2a10f-2d08-4844-892b-3768ac110001',
			'user_id' => null,
			'foreign_key' => '8b2c51b6-175c-3284-a956-a87cba11ee56',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '733439',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/97/76/66/56b2a10f2d084844892b3768ac110001/',
			'adapter' => 'Local',
			'created' => '2016-02-04 00:53:35',
			'modified' => '2016-02-04 00:53:35'
		),
		array(
			'id' => '56b2a10f-3a40-473c-b625-3768ac110001',
			'user_id' => null,
			'foreign_key' => 'cf4e8613-2811-3c04-a47d-d6d011721594',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '170049',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/95/53/66/56b2a10f3a40473cb6253768ac110001/',
			'adapter' => 'Local',
			'created' => '2016-02-04 00:53:35',
			'modified' => '2016-02-04 00:53:35'
		),
		array(
			'id' => '56b2a10f-6fb0-400c-a019-3768ac110001',
			'user_id' => null,
			'foreign_key' => 'd34a0cc6-515f-34bb-a960-29b18036e03a',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '20676',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/42/64/44/56b2a10f6fb0400ca0193768ac110001/',
			'adapter' => 'Local',
			'created' => '2016-02-04 00:53:35',
			'modified' => '2016-02-04 00:53:35'
		),
	);

}

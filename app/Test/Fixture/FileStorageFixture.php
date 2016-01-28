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
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'user_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'foreign_key' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'model' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 64, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'filename' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'filesize' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 16, 'unsigned' => false),
		'mime_type' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 32, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'extension' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 5, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'hash' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 64, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'path' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'adapter' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 32, 'collate' => 'utf8_unicode_ci', 'comment' => 'Gaufrette Storage Adapter Class', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
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
			'id' => '56aa02ca-7e04-44e4-98d3-42d3dbeb2d5e',
			'user_id' => null,
			'foreign_key' => 'fc7f0002-f379-30f3-a680-bb8de2f0fa09',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '20462',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/39/61/03/56aa02ca7e0444e498d342d3dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2016-01-28 17:30:10',
			'modified' => '2016-01-28 17:30:10'
		),
		array(
			'id' => '56aa02ca-93f4-4236-9e09-42d3dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '9ad3a7b1-b6a2-34b5-aa79-7c9b1bf9bc18',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '283883',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/02/80/75/56aa02ca93f442369e0942d3dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2016-01-28 17:30:09',
			'modified' => '2016-01-28 17:30:09'
		),
		array(
			'id' => '56aa02ca-d6fc-421e-aeb2-42d3dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '21f5dab2-7386-333e-a3f6-4f2c8fc69f42',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '53376',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/64/42/58/56aa02cad6fc421eaeb242d3dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2016-01-28 17:30:10',
			'modified' => '2016-01-28 17:30:10'
		),
		array(
			'id' => '56aa02cb-0198-42c1-a9ae-42d3dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '8b2c51b6-175c-3284-a956-a87cba11ee56',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '733439',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/25/95/32/56aa02cb019842c1a9ae42d3dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2016-01-28 17:30:11',
			'modified' => '2016-01-28 17:30:11'
		),
		array(
			'id' => '56aa02cb-2620-4886-8d59-42d3dbeb2d5e',
			'user_id' => null,
			'foreign_key' => 'd34a0cc6-515f-34bb-a960-29b18036e03a',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '20676',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/63/69/49/56aa02cb262048868d5942d3dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2016-01-28 17:30:11',
			'modified' => '2016-01-28 17:30:11'
		),
		array(
			'id' => '56aa02cb-be3c-4a43-8152-42d3dbeb2d5e',
			'user_id' => null,
			'foreign_key' => 'e0f3dafb-bc17-3c13-a982-bf43e8cf67c9',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '115942',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/99/69/72/56aa02cbbe3c4a43815242d3dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2016-01-28 17:30:11',
			'modified' => '2016-01-28 17:30:11'
		),
		array(
			'id' => '56aa02cb-eaf8-4675-8094-42d3dbeb2d5e',
			'user_id' => null,
			'foreign_key' => 'cf4e8613-2811-3c04-a47d-d6d011721594',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '170049',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/86/48/49/56aa02cbeaf84675809442d3dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2016-01-28 17:30:11',
			'modified' => '2016-01-28 17:30:11'
		),
	);

}

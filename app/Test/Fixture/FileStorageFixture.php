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
			'id' => '56263a0a-9418-4ee5-8463-1235dbeb2d5e',
			'user_id' => null,
			'foreign_key' => 'cf4e8613-2811-3c04-a47d-d6d011721594',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '170049',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/20/22/10/56263a0a94184ee584631235dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-10-20 18:26:42',
			'modified' => '2015-10-20 18:26:42'
		),
		array(
			'id' => '56263a0b-1668-46be-aece-1235dbeb2d5e',
			'user_id' => null,
			'foreign_key' => 'e0f3dafb-bc17-3c13-a982-bf43e8cf67c9',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '115942',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/74/14/61/56263a0b166846beaece1235dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-10-20 18:26:43',
			'modified' => '2015-10-20 18:26:43'
		),
		array(
			'id' => '56263a0b-58a4-4f9f-a7a2-1235dbeb2d5e',
			'user_id' => null,
			'foreign_key' => 'd34a0cc6-515f-34bb-a960-29b18036e03a',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '20676',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/61/76/06/56263a0b58a44f9fa7a21235dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-10-20 18:26:43',
			'modified' => '2015-10-20 18:26:43'
		),
		array(
			'id' => '56263a0b-6284-45e7-88af-1235dbeb2d5e',
			'user_id' => null,
			'foreign_key' => 'fc7f0002-f379-30f3-a680-bb8de2f0fa09',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '20462',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/79/84/00/56263a0b628445e788af1235dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-10-20 18:26:43',
			'modified' => '2015-10-20 18:26:43'
		),
		array(
			'id' => '56263a0b-a260-47d3-be2a-1235dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '8b2c51b6-175c-3284-a956-a87cba11ee56',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '733439',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/96/55/53/56263a0ba26047d3be2a1235dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-10-20 18:26:43',
			'modified' => '2015-10-20 18:26:43'
		),
		array(
			'id' => '56263a0c-e934-467d-ab79-1235dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '9ad3a7b1-b6a2-34b5-aa79-7c9b1bf9bc18',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '283883',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/40/11/77/56263a0ce934467dab791235dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-10-20 18:26:44',
			'modified' => '2015-10-20 18:26:44'
		),
		array(
			'id' => '56263a0c-f6c4-4eff-997e-1235dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '21f5dab2-7386-333e-a3f6-4f2c8fc69f42',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '53376',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/70/57/17/56263a0cf6c44eff997e1235dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-10-20 18:26:44',
			'modified' => '2015-10-20 18:26:44'
		),
	);

}

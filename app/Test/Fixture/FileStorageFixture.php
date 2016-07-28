<?php
/**
 * FileStorage Fixture
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
		'model' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 128, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'filename' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'filesize' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 16, 'unsigned' => false),
		'mime_type' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 128, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'extension' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 5, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'hash' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 64, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'path' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
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
			'id' => '5799f5e3-1784-49e9-a12d-23b0f72375ee',
			'user_id' => null,
			'foreign_key' => '9ad3a7b1-b6a2-34b5-aa79-7c9b1bf9bc18',
			'model' => 'ProfileAvatar',
			'filename' => null,
			'filesize' => '283883',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/79/30/05/5799f5e3178449e9a12d23b0f72375ee/',
			'adapter' => 'Local',
			'created' => '2016-07-28 17:39:06',
			'modified' => '2016-07-28 17:39:06'
		),
		array(
			'id' => '5799f5e3-2558-4851-b15e-23b0f72375ee',
			'user_id' => null,
			'foreign_key' => '21f5dab2-7386-333e-a3f6-4f2c8fc69f42',
			'model' => 'ProfileAvatar',
			'filename' => null,
			'filesize' => '53376',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/34/48/99/5799f5e325584851b15e23b0f72375ee/',
			'adapter' => 'Local',
			'created' => '2016-07-28 17:39:07',
			'modified' => '2016-07-28 17:39:07'
		),
		array(
			'id' => '5799f5e3-8df0-4f64-9497-23b0f72375ee',
			'user_id' => null,
			'foreign_key' => 'cf4e8613-2811-3c04-a47d-d6d011721594',
			'model' => 'ProfileAvatar',
			'filename' => null,
			'filesize' => '170049',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/40/35/13/5799f5e38df04f64949723b0f72375ee/',
			'adapter' => 'Local',
			'created' => '2016-07-28 17:39:07',
			'modified' => '2016-07-28 17:39:07'
		),
		array(
			'id' => '5799f5e3-92f4-4fa0-84a6-23b0f72375ee',
			'user_id' => null,
			'foreign_key' => 'fc7f0002-f379-30f3-a680-bb8de2f0fa09',
			'model' => 'ProfileAvatar',
			'filename' => null,
			'filesize' => '20462',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/78/69/41/5799f5e392f44fa084a623b0f72375ee/',
			'adapter' => 'Local',
			'created' => '2016-07-28 17:39:07',
			'modified' => '2016-07-28 17:39:07'
		),
		array(
			'id' => '5799f5e3-9a64-4999-8699-23b0f72375ee',
			'user_id' => null,
			'foreign_key' => 'e0f3dafb-bc17-3c13-a982-bf43e8cf67c9',
			'model' => 'ProfileAvatar',
			'filename' => null,
			'filesize' => '115942',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/43/89/43/5799f5e39a644999869923b0f72375ee/',
			'adapter' => 'Local',
			'created' => '2016-07-28 17:39:07',
			'modified' => '2016-07-28 17:39:07'
		),
		array(
			'id' => '5799f5e3-edc0-4a36-9bd5-23b0f72375ee',
			'user_id' => null,
			'foreign_key' => '8b2c51b6-175c-3284-a956-a87cba11ee56',
			'model' => 'ProfileAvatar',
			'filename' => null,
			'filesize' => '733439',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/92/12/12/5799f5e3edc04a369bd523b0f72375ee/',
			'adapter' => 'Local',
			'created' => '2016-07-28 17:39:07',
			'modified' => '2016-07-28 17:39:07'
		),
		array(
			'id' => '5799f5e4-ca24-4152-9452-23b0f72375ee',
			'user_id' => null,
			'foreign_key' => 'd34a0cc6-515f-34bb-a960-29b18036e03a',
			'model' => 'ProfileAvatar',
			'filename' => null,
			'filesize' => '20676',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/97/17/89/5799f5e4ca244152945223b0f72375ee/',
			'adapter' => 'Local',
			'created' => '2016-07-28 17:39:08',
			'modified' => '2016-07-28 17:39:08'
		),
	);

}

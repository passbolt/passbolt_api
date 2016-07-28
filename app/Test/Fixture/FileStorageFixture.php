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
			'id' => '5799fdba-3f48-4e8f-9d88-29bbf72375ee',
			'user_id' => null,
			'foreign_key' => '21f5dab2-7386-333e-a3f6-4f2c8fc69f42',
			'model' => 'ProfileAvatar',
			'filename' => null,
			'filesize' => '53376',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/49/71/40/5799fdba3f484e8f9d8829bbf72375ee/',
			'adapter' => 'Local',
			'created' => '2016-07-28 18:12:34',
			'modified' => '2016-07-28 18:12:34'
		),
		array(
			'id' => '5799fdba-e5d0-48db-a41c-29bbf72375ee',
			'user_id' => null,
			'foreign_key' => '9ad3a7b1-b6a2-34b5-aa79-7c9b1bf9bc18',
			'model' => 'ProfileAvatar',
			'filename' => null,
			'filesize' => '283883',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/91/77/80/5799fdbae5d048dba41c29bbf72375ee/',
			'adapter' => 'Local',
			'created' => '2016-07-28 18:12:34',
			'modified' => '2016-07-28 18:12:34'
		),
		array(
			'id' => '5799fdbb-17b0-48e8-b9df-29bbf72375ee',
			'user_id' => null,
			'foreign_key' => '8b2c51b6-175c-3284-a956-a87cba11ee56',
			'model' => 'ProfileAvatar',
			'filename' => null,
			'filesize' => '733439',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/30/72/99/5799fdbb17b048e8b9df29bbf72375ee/',
			'adapter' => 'Local',
			'created' => '2016-07-28 18:12:35',
			'modified' => '2016-07-28 18:12:35'
		),
		array(
			'id' => '5799fdbb-76e4-48a8-a301-29bbf72375ee',
			'user_id' => null,
			'foreign_key' => 'e0f3dafb-bc17-3c13-a982-bf43e8cf67c9',
			'model' => 'ProfileAvatar',
			'filename' => null,
			'filesize' => '115942',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/64/51/19/5799fdbb76e448a8a30129bbf72375ee/',
			'adapter' => 'Local',
			'created' => '2016-07-28 18:12:35',
			'modified' => '2016-07-28 18:12:35'
		),
		array(
			'id' => '5799fdbb-8460-4da1-a9a1-29bbf72375ee',
			'user_id' => null,
			'foreign_key' => 'cf4e8613-2811-3c04-a47d-d6d011721594',
			'model' => 'ProfileAvatar',
			'filename' => null,
			'filesize' => '170049',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/60/14/15/5799fdbb84604da1a9a129bbf72375ee/',
			'adapter' => 'Local',
			'created' => '2016-07-28 18:12:35',
			'modified' => '2016-07-28 18:12:35'
		),
		array(
			'id' => '5799fdbb-9b94-4f75-ab9a-29bbf72375ee',
			'user_id' => null,
			'foreign_key' => 'd34a0cc6-515f-34bb-a960-29b18036e03a',
			'model' => 'ProfileAvatar',
			'filename' => null,
			'filesize' => '20676',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/31/15/27/5799fdbb9b944f75ab9a29bbf72375ee/',
			'adapter' => 'Local',
			'created' => '2016-07-28 18:12:35',
			'modified' => '2016-07-28 18:12:35'
		),
		array(
			'id' => '5799fdbb-e170-4262-9953-29bbf72375ee',
			'user_id' => null,
			'foreign_key' => 'fc7f0002-f379-30f3-a680-bb8de2f0fa09',
			'model' => 'ProfileAvatar',
			'filename' => null,
			'filesize' => '20462',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/54/43/82/5799fdbbe1704262995329bbf72375ee/',
			'adapter' => 'Local',
			'created' => '2016-07-28 18:12:35',
			'modified' => '2016-07-28 18:12:35'
		),
	);

}

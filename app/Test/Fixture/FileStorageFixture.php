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
			'id' => '5635c8e6-049c-49c4-b284-59629ee8d42c',
			'user_id' => null,
			'foreign_key' => 'cf4e8613-2811-3c04-a47d-d6d011721594',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '170049',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/73/16/14/5635c8e6049c49c4b28459629ee8d42c/',
			'adapter' => 'Local',
			'created' => '2015-11-01 08:10:14',
			'modified' => '2015-11-01 08:10:14'
		),
		array(
			'id' => '5635c8e6-7258-48e0-8b52-59629ee8d42c',
			'user_id' => null,
			'foreign_key' => 'e0f3dafb-bc17-3c13-a982-bf43e8cf67c9',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '115942',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/52/42/14/5635c8e6725848e08b5259629ee8d42c/',
			'adapter' => 'Local',
			'created' => '2015-11-01 08:10:14',
			'modified' => '2015-11-01 08:10:14'
		),
		array(
			'id' => '5635c8e6-b0c0-40bd-9eee-59629ee8d42c',
			'user_id' => null,
			'foreign_key' => '8b2c51b6-175c-3284-a956-a87cba11ee56',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '733439',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/95/33/71/5635c8e6b0c040bd9eee59629ee8d42c/',
			'adapter' => 'Local',
			'created' => '2015-11-01 08:10:14',
			'modified' => '2015-11-01 08:10:14'
		),
		array(
			'id' => '5635c8e7-5be0-432e-92aa-59629ee8d42c',
			'user_id' => null,
			'foreign_key' => '21f5dab2-7386-333e-a3f6-4f2c8fc69f42',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '53376',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/26/82/88/5635c8e75be0432e92aa59629ee8d42c/',
			'adapter' => 'Local',
			'created' => '2015-11-01 08:10:15',
			'modified' => '2015-11-01 08:10:15'
		),
		array(
			'id' => '5635c8e7-6ca8-4ee3-9ae9-59629ee8d42c',
			'user_id' => null,
			'foreign_key' => 'd34a0cc6-515f-34bb-a960-29b18036e03a',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '20676',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/32/75/70/5635c8e76ca84ee39ae959629ee8d42c/',
			'adapter' => 'Local',
			'created' => '2015-11-01 08:10:15',
			'modified' => '2015-11-01 08:10:15'
		),
		array(
			'id' => '5635c8e7-9c04-4016-89d3-59629ee8d42c',
			'user_id' => null,
			'foreign_key' => 'fc7f0002-f379-30f3-a680-bb8de2f0fa09',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '20462',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/92/07/97/5635c8e79c04401689d359629ee8d42c/',
			'adapter' => 'Local',
			'created' => '2015-11-01 08:10:15',
			'modified' => '2015-11-01 08:10:15'
		),
		array(
			'id' => '5635c8e7-c85c-4da5-941c-59629ee8d42c',
			'user_id' => null,
			'foreign_key' => '9ad3a7b1-b6a2-34b5-aa79-7c9b1bf9bc18',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '283883',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/67/28/02/5635c8e7c85c4da5941c59629ee8d42c/',
			'adapter' => 'Local',
			'created' => '2015-11-01 08:10:15',
			'modified' => '2015-11-01 08:10:15'
		),
	);

}

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
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'user_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'foreign_key' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'model' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 128, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'filename' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'filesize' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 16, 'unsigned' => false),
		'mime_type' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 128, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'extension' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 5, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'hash' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 64, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'path' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
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
			'id' => '5992fc3e-5904-4e1e-bf41-025aac110002',
			'user_id' => null,
			'foreign_key' => '8b2c51b6-175c-3284-a956-a87cba11ee56',
			'model' => 'ProfileAvatar',
			'filename' => null,
			'filesize' => '733439',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/57/08/18/5992fc3e59044e1ebf41025aac110002/',
			'adapter' => 'Local',
			'created' => '2017-08-15 13:50:54',
			'modified' => '2017-08-15 13:50:54'
		),
		array(
			'id' => '5992fc3e-9b94-4663-b0ac-025aac110002',
			'user_id' => null,
			'foreign_key' => '21f5dab2-7386-333e-a3f6-4f2c8fc69f42',
			'model' => 'ProfileAvatar',
			'filename' => null,
			'filesize' => '53376',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/91/92/73/5992fc3e9b944663b0ac025aac110002/',
			'adapter' => 'Local',
			'created' => '2017-08-15 13:50:54',
			'modified' => '2017-08-15 13:50:54'
		),
		array(
			'id' => '5992fc3e-fc08-41eb-a5ed-025aac110002',
			'user_id' => null,
			'foreign_key' => '9ad3a7b1-b6a2-34b5-aa79-7c9b1bf9bc18',
			'model' => 'ProfileAvatar',
			'filename' => null,
			'filesize' => '283883',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/70/91/61/5992fc3efc0841eba5ed025aac110002/',
			'adapter' => 'Local',
			'created' => '2017-08-15 13:50:54',
			'modified' => '2017-08-15 13:50:54'
		),
		array(
			'id' => '5992fc3f-23e8-4e4c-834f-025aac110002',
			'user_id' => null,
			'foreign_key' => 'd34a0cc6-515f-34bb-a960-29b18036e03a',
			'model' => 'ProfileAvatar',
			'filename' => null,
			'filesize' => '20676',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/74/21/71/5992fc3f23e84e4c834f025aac110002/',
			'adapter' => 'Local',
			'created' => '2017-08-15 13:50:55',
			'modified' => '2017-08-15 13:50:55'
		),
		array(
			'id' => '5992fc3f-4cec-443f-a99d-025aac110002',
			'user_id' => null,
			'foreign_key' => 'cf4e8613-2811-3c04-a47d-d6d011721594',
			'model' => 'ProfileAvatar',
			'filename' => null,
			'filesize' => '170049',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/12/90/35/5992fc3f4cec443fa99d025aac110002/',
			'adapter' => 'Local',
			'created' => '2017-08-15 13:50:55',
			'modified' => '2017-08-15 13:50:55'
		),
		array(
			'id' => '5992fc3f-7bac-4ca3-ab41-025aac110002',
			'user_id' => null,
			'foreign_key' => 'fc7f0002-f379-30f3-a680-bb8de2f0fa09',
			'model' => 'ProfileAvatar',
			'filename' => null,
			'filesize' => '20462',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/38/29/60/5992fc3f7bac4ca3ab41025aac110002/',
			'adapter' => 'Local',
			'created' => '2017-08-15 13:50:55',
			'modified' => '2017-08-15 13:50:55'
		),
		array(
			'id' => '5992fc3f-8dac-4dad-8f4c-025aac110002',
			'user_id' => null,
			'foreign_key' => 'e0f3dafb-bc17-3c13-a982-bf43e8cf67c9',
			'model' => 'ProfileAvatar',
			'filename' => null,
			'filesize' => '115942',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/67/92/03/5992fc3f8dac4dad8f4c025aac110002/',
			'adapter' => 'Local',
			'created' => '2017-08-15 13:50:55',
			'modified' => '2017-08-15 13:50:55'
		),
	);

}

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
			'id' => '55d1c996-6e50-4be6-9471-4741c0a80111',
			'user_id' => null,
			'foreign_key' => '528c2dab-cccj-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '115942',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/46/02/15/55d1c9966e504be694714741c0a80111/',
			'adapter' => 'Local',
			'created' => '2015-08-17 13:46:30',
			'modified' => '2015-08-17 13:46:30'
		),
		array(
			'id' => '55d1c996-9408-44d7-8a54-4741c0a80111',
			'user_id' => null,
			'foreign_key' => '528c2dab-ccci-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '170049',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/64/53/80/55d1c996940844d78a544741c0a80111/',
			'adapter' => 'Local',
			'created' => '2015-08-17 13:46:30',
			'modified' => '2015-08-17 13:46:30'
		),
		array(
			'id' => '55d1c997-9f70-4480-a54a-4741c0a80111',
			'user_id' => null,
			'foreign_key' => '528c2dab-ccck-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '733439',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/23/70/31/55d1c9979f704480a54a4741c0a80111/',
			'adapter' => 'Local',
			'created' => '2015-08-17 13:46:31',
			'modified' => '2015-08-17 13:46:31'
		),
		array(
			'id' => '55d1c997-a8ec-432a-b312-4741c0a80111',
			'user_id' => null,
			'foreign_key' => '528c2dab-cccb-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '20676',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/41/37/25/55d1c997a8ec432ab3124741c0a80111/',
			'adapter' => 'Local',
			'created' => '2015-08-17 13:46:31',
			'modified' => '2015-08-17 13:46:31'
		),
		array(
			'id' => '55d1c997-b3bc-41cd-85f3-4741c0a80111',
			'user_id' => null,
			'foreign_key' => '528c2dab-ccce-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '20462',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/32/26/29/55d1c997b3bc41cd85f34741c0a80111/',
			'adapter' => 'Local',
			'created' => '2015-08-17 13:46:31',
			'modified' => '2015-08-17 13:46:31'
		),
		array(
			'id' => '55d1c997-eaa0-45c1-85cb-4741c0a80111',
			'user_id' => null,
			'foreign_key' => '528c2dab-cccp-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '283883',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/78/31/13/55d1c997eaa045c185cb4741c0a80111/',
			'adapter' => 'Local',
			'created' => '2015-08-17 13:46:31',
			'modified' => '2015-08-17 13:46:31'
		),
		array(
			'id' => '55d1c997-f638-414c-b9d6-4741c0a80111',
			'user_id' => null,
			'foreign_key' => '528c2dab-c358-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '53376',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/91/52/18/55d1c997f638414cb9d64741c0a80111/',
			'adapter' => 'Local',
			'created' => '2015-08-17 13:46:31',
			'modified' => '2015-08-17 13:46:31'
		),
	);

}

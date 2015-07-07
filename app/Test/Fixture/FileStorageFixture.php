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
			'id' => '55966b44-c8c0-4936-b03a-19b0dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-ccci-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '170049',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/45/13/15/55966b44c8c04936b03a19b0dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-07-03 13:00:20',
			'modified' => '2015-07-03 13:00:20'
		),
		array(
			'id' => '55966b45-3818-4805-8199-19b0dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-ccck-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '733439',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/62/32/58/55966b4538184805819919b0dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-07-03 13:00:21',
			'modified' => '2015-07-03 13:00:21'
		),
		array(
			'id' => '55966b45-6274-47fb-bde3-19b0dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-c358-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '53376',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/74/78/39/55966b45627447fbbde319b0dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-07-03 13:00:21',
			'modified' => '2015-07-03 13:00:21'
		),
		array(
			'id' => '55966b45-bc44-4221-bfcf-19b0dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-ccce-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '20462',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/97/76/27/55966b45bc444221bfcf19b0dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-07-03 13:00:21',
			'modified' => '2015-07-03 13:00:21'
		),
		array(
			'id' => '55966b45-f418-4d2b-a34e-19b0dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-cccb-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '20676',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/45/90/85/55966b45f4184d2ba34e19b0dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-07-03 13:00:21',
			'modified' => '2015-07-03 13:00:21'
		),
		array(
			'id' => '55966b45-f680-4ce9-b21f-19b0dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-cccp-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '283883',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/27/51/80/55966b45f6804ce9b21f19b0dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-07-03 13:00:21',
			'modified' => '2015-07-03 13:00:21'
		),
		array(
			'id' => '55966b45-f71c-4db0-a219-19b0dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-cccj-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '115942',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/13/59/25/55966b45f71c4db0a21919b0dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-07-03 13:00:21',
			'modified' => '2015-07-03 13:00:21'
		),
	);

}

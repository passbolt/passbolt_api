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
			'id' => '5593d18d-3c20-4ec3-b5ff-1cf3dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-ccci-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '170049',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/00/83/05/5593d18d3c204ec3b5ff1cf3dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-07-01 13:39:57',
			'modified' => '2015-07-01 13:39:57'
		),
		array(
			'id' => '5593d18e-10d4-4d7a-80bf-1cf3dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-cccg-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '306074',
			'mime_type' => 'image/jpeg',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/97/76/74/5593d18e10d44d7a80bf1cf3dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-07-01 13:39:58',
			'modified' => '2015-07-01 13:39:58'
		),
		array(
			'id' => '5593d18e-8b30-4de0-93d0-1cf3dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-ccch-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '231304',
			'mime_type' => 'image/jpeg',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/51/48/41/5593d18e8b304de093d01cf3dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-07-01 13:39:58',
			'modified' => '2015-07-01 13:39:58'
		),
		array(
			'id' => '5593d18e-b2d8-4204-9576-1cf3dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-cccb-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '18344',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/70/66/91/5593d18eb2d8420495761cf3dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-07-01 13:39:58',
			'modified' => '2015-07-01 13:39:58'
		),
		array(
			'id' => '5593d18e-b3d4-423f-9d9f-1cf3dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-cccj-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '67341',
			'mime_type' => 'image/jpeg',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/12/13/88/5593d18eb3d4423f9d9f1cf3dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-07-01 13:39:58',
			'modified' => '2015-07-01 13:39:58'
		),
		array(
			'id' => '5593d18f-6e60-4786-8040-1cf3dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-ccck-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '86324',
			'mime_type' => 'image/jpeg',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/77/22/89/5593d18f6e60478680401cf3dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-07-01 13:39:59',
			'modified' => '2015-07-01 13:39:59'
		),
	);

}

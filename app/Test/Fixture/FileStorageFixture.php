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
			'id' => '55cdda4b-1dc0-47ec-aeca-0e13dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-ccck-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '733439',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/57/05/15/55cdda4b1dc047ecaeca0e13dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-08-14 14:08:43',
			'modified' => '2015-08-14 14:08:43'
		),
		array(
			'id' => '55cdda4b-7204-47f5-8839-0e13dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-cccb-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '20676',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/08/78/29/55cdda4b720447f588390e13dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-08-14 14:08:43',
			'modified' => '2015-08-14 14:08:43'
		),
		array(
			'id' => '55cdda4b-e218-4469-8423-0e13dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-cccj-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '115942',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/58/29/50/55cdda4be218446984230e13dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-08-14 14:08:43',
			'modified' => '2015-08-14 14:08:43'
		),
		array(
			'id' => '55cdda4b-f714-47ef-a8e7-0e13dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-ccci-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '170049',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/02/21/68/55cdda4bf71447efa8e70e13dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-08-14 14:08:43',
			'modified' => '2015-08-14 14:08:43'
		),
		array(
			'id' => '55cdda4c-23dc-4a40-95bd-0e13dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-cccp-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '283883',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/22/42/93/55cdda4c23dc4a4095bd0e13dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-08-14 14:08:44',
			'modified' => '2015-08-14 14:08:44'
		),
		array(
			'id' => '55cdda4c-372c-44ad-9709-0e13dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-ccce-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '20462',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/03/16/49/55cdda4c372c44ad97090e13dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-08-14 14:08:44',
			'modified' => '2015-08-14 14:08:44'
		),
		array(
			'id' => '55cdda4c-42a8-4196-8752-0e13dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-c358-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '53376',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/18/76/67/55cdda4c42a8419687520e13dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-08-14 14:08:44',
			'modified' => '2015-08-14 14:08:44'
		),
	);

}

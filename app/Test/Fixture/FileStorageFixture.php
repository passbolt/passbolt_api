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
		'filesize' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 16),
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
			'id' => '55842eff-4d38-4944-878c-192edbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-cccb-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '18344',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/25/69/73/55842eff4d384944878c192edbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-06-19 17:02:23',
			'modified' => '2015-06-19 17:02:23'
		),
		array(
			'id' => '55842eff-8e58-4c38-9a52-192edbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-cccg-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '306074',
			'mime_type' => 'image/jpeg',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/93/34/61/55842eff8e584c389a52192edbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-06-19 17:02:23',
			'modified' => '2015-06-19 17:02:23'
		),
		array(
			'id' => '55842eff-b990-4747-8b15-192edbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-ccch-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '231304',
			'mime_type' => 'image/jpeg',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/97/94/49/55842effb99047478b15192edbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-06-19 17:02:23',
			'modified' => '2015-06-19 17:02:23'
		),
		array(
			'id' => '55842eff-f4b0-40e3-aa3c-192edbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-ccci-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '59026',
			'mime_type' => 'image/jpeg',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/82/91/47/55842efff4b040e3aa3c192edbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-06-19 17:02:23',
			'modified' => '2015-06-19 17:02:23'
		),
		array(
			'id' => '55842f00-0174-4165-9863-192edbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-cccj-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '67341',
			'mime_type' => 'image/jpeg',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/24/47/10/55842f00017441659863192edbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-06-19 17:02:24',
			'modified' => '2015-06-19 17:02:24'
		),
		array(
			'id' => '55842f00-e430-4bb4-8549-192edbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-ccck-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '86324',
			'mime_type' => 'image/jpeg',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/60/47/42/55842f00e4304bb48549192edbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-06-19 17:02:24',
			'modified' => '2015-06-19 17:02:24'
		),
	);

}

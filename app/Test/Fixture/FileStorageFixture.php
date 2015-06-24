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
			'id' => '558ab370-45f4-4e55-8ba0-2146dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-ccch-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '231304',
			'mime_type' => 'image/jpeg',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/14/36/59/558ab37045f44e558ba02146dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-06-24 15:41:04',
			'modified' => '2015-06-24 15:41:04'
		),
		array(
			'id' => '558ab370-72d8-4959-add0-2146dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-ccci-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '59026',
			'mime_type' => 'image/jpeg',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/00/61/66/558ab37072d84959add02146dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-06-24 15:41:04',
			'modified' => '2015-06-24 15:41:04'
		),
		array(
			'id' => '558ab370-9638-4df8-9120-2146dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-cccb-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '18344',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/54/19/09/558ab37096384df891202146dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-06-24 15:41:04',
			'modified' => '2015-06-24 15:41:04'
		),
		array(
			'id' => '558ab371-0cc0-4e43-89dd-2146dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-ccck-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '86324',
			'mime_type' => 'image/jpeg',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/81/71/16/558ab3710cc04e4389dd2146dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-06-24 15:41:05',
			'modified' => '2015-06-24 15:41:05'
		),
		array(
			'id' => '558ab371-b13c-402f-bc3f-2146dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-cccj-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '67341',
			'mime_type' => 'image/jpeg',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/93/98/98/558ab371b13c402fbc3f2146dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-06-24 15:41:05',
			'modified' => '2015-06-24 15:41:05'
		),
		array(
			'id' => '558ab371-c5c0-49d3-b104-2146dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-cccg-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '306074',
			'mime_type' => 'image/jpeg',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/14/88/36/558ab371c5c049d3b1042146dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-06-24 15:41:05',
			'modified' => '2015-06-24 15:41:05'
		),
	);

}

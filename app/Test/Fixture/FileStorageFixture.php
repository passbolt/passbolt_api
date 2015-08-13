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
			'id' => '55c75361-7d00-40e4-9c53-5939dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-cccj-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '115942',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/28/97/20/55c753617d0040e49c535939dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-08-09 15:19:29',
			'modified' => '2015-08-09 15:19:29'
		),
		array(
			'id' => '55c75361-7fd4-4549-a46c-5939dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-ccci-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '170049',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/80/75/23/55c753617fd44549a46c5939dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-08-09 15:19:29',
			'modified' => '2015-08-09 15:19:29'
		),
		array(
			'id' => '55c75361-9098-4305-9b57-5939dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-ccce-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '20462',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/84/02/82/55c75361909843059b575939dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-08-09 15:19:29',
			'modified' => '2015-08-09 15:19:29'
		),
		array(
			'id' => '55c75361-bd08-4c4d-a37e-5939dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-cccp-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '283883',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/57/39/53/55c75361bd084c4da37e5939dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-08-09 15:19:29',
			'modified' => '2015-08-09 15:19:29'
		),
		array(
			'id' => '55c75361-e194-48f2-81f5-5939dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-ccck-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '733439',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/09/82/15/55c75361e19448f281f55939dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-08-09 15:19:29',
			'modified' => '2015-08-09 15:19:29'
		),
		array(
			'id' => '55c75361-e440-41f9-b172-5939dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-cccb-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '20676',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/65/64/52/55c75361e44041f9b1725939dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-08-09 15:19:29',
			'modified' => '2015-08-09 15:19:29'
		),
		array(
			'id' => '55c75362-8370-4705-8ac2-5939dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-c358-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '53376',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/53/59/35/55c75362837047058ac25939dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-08-09 15:19:30',
			'modified' => '2015-08-09 15:19:30'
		),
	);

}

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
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'user_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'foreign_key' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'model' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 64, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'filename' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'filesize' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 16, 'unsigned' => false),
		'mime_type' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 32, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'extension' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 5, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'hash' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 64, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'path' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'adapter' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 32, 'collate' => 'utf8_general_ci', 'comment' => 'Gaufrette Storage Adapter Class', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '560165ef-7be8-4b35-b0e2-2464dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-ccci-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '170049',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/63/85/48/560165ef7be84b35b0e22464dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-09-22 20:00:07',
			'modified' => '2015-09-22 20:00:07'
		),
		array(
			'id' => '560165ef-a418-46d6-9ce9-2464dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-cccj-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '115942',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/74/46/92/560165efa41846d69ce92464dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-09-22 20:00:07',
			'modified' => '2015-09-22 20:00:07'
		),
		array(
			'id' => '560165f0-413c-40b5-a6eb-2464dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-cccb-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '20676',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/22/24/63/560165f0413c40b5a6eb2464dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-09-22 20:00:08',
			'modified' => '2015-09-22 20:00:08'
		),
		array(
			'id' => '560165f0-6814-440f-b872-2464dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-cccp-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '283883',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/80/51/33/560165f06814440fb8722464dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-09-22 20:00:08',
			'modified' => '2015-09-22 20:00:08'
		),
		array(
			'id' => '560165f0-9fe8-469d-8baa-2464dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-ccck-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '733439',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/85/54/10/560165f09fe8469d8baa2464dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-09-22 20:00:08',
			'modified' => '2015-09-22 20:00:08'
		),
		array(
			'id' => '560165f0-b65c-4eac-9a45-2464dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-c358-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '53376',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/74/32/18/560165f0b65c4eac9a452464dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-09-22 20:00:08',
			'modified' => '2015-09-22 20:00:08'
		),
		array(
			'id' => '560165f0-e430-40b6-b99b-2464dbeb2d5e',
			'user_id' => null,
			'foreign_key' => '528c2dab-ccce-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '20462',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/51/06/62/560165f0e43040b6b99b2464dbeb2d5e/',
			'adapter' => 'Local',
			'created' => '2015-09-22 20:00:08',
			'modified' => '2015-09-22 20:00:08'
		),
	);

}

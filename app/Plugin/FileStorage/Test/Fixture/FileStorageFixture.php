<?php
/**
 * File Storage Fixture
 *
 * @author Florian Krämer
 * @copyright 2012 Florian Krämer
 * @license MIT
 */
class FileStorageFixture extends CakeTestFixture {

/**
 * Model name
 *
 * @var string $model
 */
	public $name = 'FileStorage';

/**
 * Table name
 *
 * @var string $useTable
 */
	public $table = 'file_storage';

/**
 * Fields definition
 *
 * @var array $fields
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary'),
		'user_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 36),
		'foreign_key' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 36),
		'model' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 64),
		'filename' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'filesize' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 16),
		'mime_type' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 32),
		'extension' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 5),
		'hash' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 64),
		'path' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'adapter' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 32, 'comment' => 'Gaufrette Storage Adapter Class'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 'file-storage-1',
			'user_id' => 'user-1',
			'foreign_key' => 'item-1',
			'model' => 'Item',
			'filename' => 'cake.icon.png',
			'filesize' => '',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => '',
			'path' => '',
			'adapter' => 'Local',
			'created' => '2012-01-01 12:00:00',
			'modified' => '2012-01-01 12:00:00',
		),
		array(
			'id' => 'file-storage-2',
			'user_id' => 'user-1',
			'foreign_key' => 'item-1',
			'model' => 'Item',
			'filename' => 'titus-bienebek-bridle.jpg',
			'filesize' => '',
			'mime_type' => 'image/jpg',
			'extension' => 'jpg',
			'hash' => '',
			'path' => '',
			'adapter' => 'Local',
			'created' => '2012-01-01 12:00:00',
			'modified' => '2012-01-01 12:00:00',
		),
	);
}
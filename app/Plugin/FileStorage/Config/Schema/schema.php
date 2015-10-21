<?php 
/**
 * FileStorage
 *
 * @author Florian Kr�mer
 * @copyright 2012 Florian Kr�mer
 * @license MIT
 */
class FileStorageSchema extends CakeSchema {

/**
 * Name
 *
 * @var string
 */
	public $name = 'FileStorage';

/**
 * Before callback
 *
 * @param array $event
 * @return boolean
 */
	public function before($event = array()) {
		return true;
	}

/**
 * After callback
 *
 * @param array $event
 * @return boolean
 */
	public function after($event = array()) {
		return true;
	}

/**
 * Schema for file storage table
 *
 * @var array
 */
	public $file_storage = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary'),
		'user_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36),
		'foreign_key' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36),
		'model' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 64),
		'filename' => array('type' => 'string', 'null' => false, 'default' => null),
		'filesize' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 16),
		'mime_type' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 32),
		'extension' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 5),
		'hash' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 64),
		'path' => array('type' => 'string', 'null' => false, 'default' => null),
		'adapter' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 32, 'comment' => 'Gaufrette Storage Adapter Class'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
	);

}

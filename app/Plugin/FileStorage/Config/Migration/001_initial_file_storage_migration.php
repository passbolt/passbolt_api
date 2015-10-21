<?php
/**
 * FileStorage
 *
 * @author Florian Krämer
 * @copyright 2012 Florian Krämer
 * @license MIT
 */
class InitialFileStorageMigration extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = '';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'down' => array(
			'drop_table' => array(
				'file_storage'
			),
		),
		'up' => array(
			'create_table' => array(
				'file_storage' => array(
					'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary'),
					'user_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36),
					'foreign_key' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36),
					'model' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 64),
					'filename' => array('type' => 'string', 'null' => false, 'default' => null),
					'filesize' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 16),
					'mime_type' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 32),
					'extension' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 5),
					'hash' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 64),
					'path' => array('type' => 'string', 'null' => false, 'default' => null),
					'adapter' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 32, 'comment' => 'Storage Adapter Config Name'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1)
					)
				)
			)
		)
	);

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function before($direction) {
		if (Configure::read('FileStorage.schema.useIntegers') === true) {
			$this->migration['up']['create_table']['file_storage']['id']['type'] = 'integer';
			$this->migration['up']['create_table']['file_storage']['id']['length'] = 10;
			$this->migration['up']['create_table']['file_storage']['foreign_key']['type'] = 'integer';
			$this->migration['up']['create_table']['file_storage']['foreign_key']['length'] = 10;
		}
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function after($direction) {
		return true;
	}

}

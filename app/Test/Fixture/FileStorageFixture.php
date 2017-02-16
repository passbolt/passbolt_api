<?php
/**
 * FileStorage Fixture
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
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'user_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'foreign_key' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'model' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 128, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'filename' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'filesize' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 16, 'unsigned' => false),
		'mime_type' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 128, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'extension' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 5, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'hash' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 64, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'path' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'adapter' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 32, 'collate' => 'latin1_swedish_ci', 'comment' => 'Gaufrette Storage Adapter Class', 'charset' => 'latin1'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '58a5ba4d-8848-45a7-ade6-0d9dac110002',
			'user_id' => null,
			'foreign_key' => '9ad3a7b1-b6a2-34b5-aa79-7c9b1bf9bc18',
			'model' => 'ProfileAvatar',
			'filename' => null,
			'filesize' => '283883',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/80/87/19/58a5ba4d884845a7ade60d9dac110002/',
			'adapter' => 'Local',
			'created' => '2017-02-16 14:42:20',
			'modified' => '2017-02-16 14:42:20'
		),
		array(
			'id' => '58a5ba50-0c58-4e62-af1c-0d9dac110002',
			'user_id' => null,
			'foreign_key' => '21f5dab2-7386-333e-a3f6-4f2c8fc69f42',
			'model' => 'ProfileAvatar',
			'filename' => null,
			'filesize' => '53376',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/05/73/53/58a5ba500c584e62af1c0d9dac110002/',
			'adapter' => 'Local',
			'created' => '2017-02-16 14:42:24',
			'modified' => '2017-02-16 14:42:24'
		),
		array(
			'id' => '58a5ba52-417c-48fe-9d07-0d9dac110002',
			'user_id' => null,
			'foreign_key' => 'fc7f0002-f379-30f3-a680-bb8de2f0fa09',
			'model' => 'ProfileAvatar',
			'filename' => null,
			'filesize' => '20462',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/42/12/71/58a5ba52417c48fe9d070d9dac110002/',
			'adapter' => 'Local',
			'created' => '2017-02-16 14:42:26',
			'modified' => '2017-02-16 14:42:26'
		),
		array(
			'id' => '58a5ba54-543c-49d9-aaf3-0d9dac110002',
			'user_id' => null,
			'foreign_key' => 'e0f3dafb-bc17-3c13-a982-bf43e8cf67c9',
			'model' => 'ProfileAvatar',
			'filename' => null,
			'filesize' => '115942',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/62/65/59/58a5ba54543c49d9aaf30d9dac110002/',
			'adapter' => 'Local',
			'created' => '2017-02-16 14:42:27',
			'modified' => '2017-02-16 14:42:27'
		),
		array(
			'id' => '58a5ba55-b1d4-49c9-be8f-0d9dac110002',
			'user_id' => null,
			'foreign_key' => 'cf4e8613-2811-3c04-a47d-d6d011721594',
			'model' => 'ProfileAvatar',
			'filename' => null,
			'filesize' => '170049',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/57/25/75/58a5ba55b1d449c9be8f0d9dac110002/',
			'adapter' => 'Local',
			'created' => '2017-02-16 14:42:29',
			'modified' => '2017-02-16 14:42:29'
		),
		array(
			'id' => '58a5ba56-7490-4043-a90e-0d9dac110002',
			'user_id' => null,
			'foreign_key' => '8b2c51b6-175c-3284-a956-a87cba11ee56',
			'model' => 'ProfileAvatar',
			'filename' => null,
			'filesize' => '733439',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/64/80/99/58a5ba5674904043a90e0d9dac110002/',
			'adapter' => 'Local',
			'created' => '2017-02-16 14:42:30',
			'modified' => '2017-02-16 14:42:30'
		),
		array(
			'id' => '58a5ba58-28b0-4f1d-8920-0d9dac110002',
			'user_id' => null,
			'foreign_key' => 'd34a0cc6-515f-34bb-a960-29b18036e03a',
			'model' => 'ProfileAvatar',
			'filename' => null,
			'filesize' => '20676',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/07/01/06/58a5ba5828b04f1d89200d9dac110002/',
			'adapter' => 'Local',
			'created' => '2017-02-16 14:42:32',
			'modified' => '2017-02-16 14:42:32'
		),
	);

}

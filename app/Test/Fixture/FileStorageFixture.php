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
			'id' => '58bae6a5-4220-47f2-8e8d-1842ac110003',
			'user_id' => null,
			'foreign_key' => 'e0f3dafb-bc17-3c13-a982-bf43e8cf67c9',
			'model' => 'ProfileAvatar',
			'filename' => null,
			'filesize' => '115942',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/04/35/17/58bae6a5422047f28e8d1842ac110003/',
			'adapter' => 'Local',
			'created' => '2017-03-04 16:09:09',
			'modified' => '2017-03-04 16:09:09'
		),
		array(
			'id' => '58bae6a5-57cc-486f-95ab-1842ac110003',
			'user_id' => null,
			'foreign_key' => 'cf4e8613-2811-3c04-a47d-d6d011721594',
			'model' => 'ProfileAvatar',
			'filename' => null,
			'filesize' => '170049',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/71/93/19/58bae6a557cc486f95ab1842ac110003/',
			'adapter' => 'Local',
			'created' => '2017-03-04 16:09:09',
			'modified' => '2017-03-04 16:09:09'
		),
		array(
			'id' => '58bae6a5-60a0-4da5-9f52-1842ac110003',
			'user_id' => null,
			'foreign_key' => 'fc7f0002-f379-30f3-a680-bb8de2f0fa09',
			'model' => 'ProfileAvatar',
			'filename' => null,
			'filesize' => '20462',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/27/92/53/58bae6a560a04da59f521842ac110003/',
			'adapter' => 'Local',
			'created' => '2017-03-04 16:09:09',
			'modified' => '2017-03-04 16:09:09'
		),
		array(
			'id' => '58bae6a5-d18c-4ded-bfba-1842ac110003',
			'user_id' => null,
			'foreign_key' => '21f5dab2-7386-333e-a3f6-4f2c8fc69f42',
			'model' => 'ProfileAvatar',
			'filename' => null,
			'filesize' => '53376',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/87/52/82/58bae6a5d18c4dedbfba1842ac110003/',
			'adapter' => 'Local',
			'created' => '2017-03-04 16:09:09',
			'modified' => '2017-03-04 16:09:09'
		),
		array(
			'id' => '58bae6a5-e2ec-41e5-a073-1842ac110003',
			'user_id' => null,
			'foreign_key' => '9ad3a7b1-b6a2-34b5-aa79-7c9b1bf9bc18',
			'model' => 'ProfileAvatar',
			'filename' => null,
			'filesize' => '283883',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/37/95/19/58bae6a5e2ec41e5a0731842ac110003/',
			'adapter' => 'Local',
			'created' => '2017-03-04 16:09:09',
			'modified' => '2017-03-04 16:09:09'
		),
		array(
			'id' => '58bae6a6-3644-4680-b8c0-1842ac110003',
			'user_id' => null,
			'foreign_key' => 'd34a0cc6-515f-34bb-a960-29b18036e03a',
			'model' => 'ProfileAvatar',
			'filename' => null,
			'filesize' => '20676',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/11/10/13/58bae6a636444680b8c01842ac110003/',
			'adapter' => 'Local',
			'created' => '2017-03-04 16:09:10',
			'modified' => '2017-03-04 16:09:10'
		),
		array(
			'id' => '58bae6a6-c36c-42ca-a15d-1842ac110003',
			'user_id' => null,
			'foreign_key' => '8b2c51b6-175c-3284-a956-a87cba11ee56',
			'model' => 'ProfileAvatar',
			'filename' => null,
			'filesize' => '733439',
			'mime_type' => 'image/png',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/94/34/20/58bae6a6c36c42caa15d1842ac110003/',
			'adapter' => 'Local',
			'created' => '2017-03-04 16:09:10',
			'modified' => '2017-03-04 16:09:10'
		),
	);

}

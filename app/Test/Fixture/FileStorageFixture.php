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
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'user_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'foreign_key' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'model' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 64, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'filename' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'filesize' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 16),
		'mime_type' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 32, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'extension' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 5, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'hash' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 64, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'path' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
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
			'id' => '5465c443-0f30-4a26-9c36-2e98c26b1ae0',
			'user_id' => null,
			'foreign_key' => '528c2dab-ccci-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '59026',
			'mime_type' => 'image/jpeg',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/33/80/93/5465c4430f304a269c362e98c26b1ae0/',
			'adapter' => 'Local',
			'created' => '2014-11-14 14:28:43',
			'modified' => '2014-11-14 14:28:43'
		),
		array(
			'id' => '5465c444-62cc-4296-9eac-2e98c26b1ae0',
			'user_id' => null,
			'foreign_key' => '528c2dab-ccch-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '231304',
			'mime_type' => 'image/jpeg',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/15/39/05/5465c44462cc42969eac2e98c26b1ae0/',
			'adapter' => 'Local',
			'created' => '2014-11-14 14:28:44',
			'modified' => '2014-11-14 14:28:44'
		),
		array(
			'id' => '5465c445-273c-4811-8708-2e98c26b1ae0',
			'user_id' => null,
			'foreign_key' => '528c2dab-cccg-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '306074',
			'mime_type' => 'image/jpeg',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/63/25/27/5465c445273c481187082e98c26b1ae0/',
			'adapter' => 'Local',
			'created' => '2014-11-14 14:28:45',
			'modified' => '2014-11-14 14:28:45'
		),
		array(
			'id' => '5465c447-567c-4424-bd99-2e98c26b1ae0',
			'user_id' => null,
			'foreign_key' => '528c2dab-cccj-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '67341',
			'mime_type' => 'image/jpeg',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/70/67/25/5465c447567c4424bd992e98c26b1ae0/',
			'adapter' => 'Local',
			'created' => '2014-11-14 14:28:47',
			'modified' => '2014-11-14 14:28:47'
		),
		array(
			'id' => '5465c447-e6ec-4475-b3ed-2e98c26b1ae0',
			'user_id' => null,
			'foreign_key' => '528c2dab-ccck-416d-802b-71668cebc04d',
			'model' => 'ProfileAvatar',
			'filename' => '',
			'filesize' => '86324',
			'mime_type' => 'image/jpeg',
			'extension' => 'png',
			'hash' => null,
			'path' => 'images/ProfileAvatar/11/61/46/5465c447e6ec4475b3ed2e98c26b1ae0/',
			'adapter' => 'Local',
			'created' => '2014-11-14 14:28:47',
			'modified' => '2014-11-14 14:28:47'
		),
	);

}

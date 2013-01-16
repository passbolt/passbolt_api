<?php
/**
 * AuthenticationBlacklistFixture
 *
 */
class AuthenticationBlacklistFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'ip' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 33, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'expiry' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
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
		/*array(
			'id' => '50f402e8-a638-47ea-acc9-f2718cebc04d',
			'ip' => 'Lorem ipsum dolor sit amet',
			'expiry' => '2013-01-14 14:06:48',
			'created' => '2013-01-14 14:06:48',
			'modified' => '2013-01-14 14:06:48'
		),*/
	);

}

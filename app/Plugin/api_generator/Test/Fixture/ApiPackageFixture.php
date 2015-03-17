<?php
/**
 * ApiPackage Fixture
 *
 * PHP 5.2+
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2008-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2008-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org
 * @package       api_generator
 * @subpackage    api_generator.tests.fixtures
 * @since         ApiGenerator 0.5
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 **/
class ApiPackageFixture extends CakeTestFixture {
	var $name = 'ApiPackage';
	var $fields = array(
		'id' => array('type' => 'string', 'default' => NULL, 'length' => 36, 'null' => false, 'key' => 'primary'),
		'parent_id' => array('type' => 'string', 'default' => NULL, 'length' => 36),
		'path' => array('type' => 'string', 'null' => false, 'length' => 500),
		'name' => array('type' => 'string', 'length' => 255, 'null' => false),
		'slug' => array('type' => 'string', 'length' => 255, 'null' => false),
		'lft' => array('type' => 'integer'),
		'rght' => array('type' => 'integer'),
		'created' => array('type' => 'datetime'),
		'modified' => array('type' => 'datetime')
	);

var $records = array(
	array(
		'id' => 1,
		'parent_id' => null,
		'path' => 'cake',
		'name' => 'cake',
		'slug' => 'cake',
		'lft' => 1,
		'rght' => 10,
		'created' => '2009-01-01 12:00:00',
		'modified' => '2009-01-01 12:00:00'
	),
	array(
		'id' => 2,
		'parent_id' => 1,
		'path' => 'cake/controller',
		'name' => 'controller',
		'slug' => 'controller',
		'lft' => 2,
		'rght' => 5,
		'created' => '2009-01-01 12:00:00',
		'modified' => '2009-01-01 12:00:00'
	),
	array(
		'id' => 3,
		'parent_id' => 2,
		'path' => 'cake/controller/component',
		'name' => 'component',
		'slug' => 'component',
		'lft' => 3,
		'rght' => 4,
		'created' => '2009-01-01 12:00:00',
		'modified' => '2009-01-01 12:00:00'
	),
	array(
		'id' => 4,
		'parent_id' => 1,
		'path' => 'cake/model',
		'name' => 'model',
		'slug' => 'model',
		'lft' => 6,
		'rght' => 9,
		'created' => '2009-01-01 12:00:00',
		'modified' => '2009-01-01 12:00:00'
	),
	array(
		'id' => 5,
		'parent_id' => 4,
		'path' => 'cake/model/behavior',
		'name' => 'behavior',
		'slug' => 'behavior',
		'lft' => 7,
		'rght' => 8,
		'created' => '2009-01-01 12:00:00',
		'modified' => '2009-01-01 12:00:00'
	)
);
}

<?php
/**
 * Api Generator Schema file.
 *
 * Schema file for Api Generator.
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
 * @subpackage    api_generator.config.sql
 * @since         ApiGenerator 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 **/
/**
 * ApiGenerator Plugin Schema
 *
 * @package cake.api_generator.config
 */
class ApiGeneratorSchema extends CakeSchema {
/**
 * api_classes table definition.
 *
 * @var array
 */
	public $api_classes = array(
		'id' => array('type' => 'string', 'default' => NULL, 'length' => 36, 'null' => false, 'key' => 'primary'),
		'api_package_id' => array('type' => 'string', 'default' => NULL, 'length' => 36, 'null' => true, 'key' => 'index'),
		'name' => array('type' => 'string', 'length' => 200, 'null' => false),
		'slug' => array('type' => 'string', 'length' => 200, 'null' => false),
		'file_name' => array('type' => 'text'),
		'method_index' => array('type' => 'text'),
		'property_index' => array('type' => 'text'),
		'flags' => array('type' => 'integer', 'default' => 0, 'length' => 5),
		'coverage_cache' => array('type' => 'float', 'length' => '4,4'),
		'created' => array('type' => 'datetime'),
		'modified' => array('type' => 'datetime'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => true),
			'api_package_id' => array('column' => 'api_package_id', 'unique' => false),
		)
	);
/**
 * api_packages table definition
 *
 * @var array_change_key_case
 **/
	public $api_packages = array(
		'id' => array('type' => 'string', 'default' => NULL, 'length' => 36, 'null' => false, 'key' => 'primary'),
		'parent_id' => array('type' => 'string', 'default' => NULL, 'length' => 36, 'null' => true, 'key' => 'index'),
		'path' => array('type' => 'string', 'null' => false, 'length' => 500, 'key' => 'index'),
		'name' => array('type' => 'string', 'length' => 255, 'null' => false),
		'slug' => array('type' => 'string', 'length' => 255, 'null' => false),
		'lft' => array('type' => 'integer'),
		'rght' => array('type' => 'integer'),
		'created' => array('type' => 'datetime'),
		'modified' => array('type' => 'datetime'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => true),
			'parent_id' => array('column' => 'parent_id', 'unique' => false),
			'path' => array('column' => 'path', 'unique' => false),
		)
	);
}
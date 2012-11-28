<?php
/**
 * DocumentorFactory Create documentor objects
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
 * @subpackage    api_generator.vendors
 * @since         ApiGenerator 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 **/
/**
 * DocumentorFactory provides factory methods and common methods for
 * reflection parsing
 */
class DocumentorFactory {
/**
 * Reflector classMappings
 *
 * @var array
 **/
	protected static $_reflectorMap = array(
		'class' => 'ClassDocumentor',
		'function' => 'FunctionDocumentor',
	);
/**
 * Get the correct reflector type for the requested object
 *
 * @param string $type The type of reflector needed
 * @param string $name The name of the function/class being reflected
 * @return object constructed reflector type.
 * @throws Exception
 **/
	public static function getReflector($type, $name = null) {
		if ($name === null) {
			$name = $type;
			$type = 'class';
		}
		if (!isset(self::$_reflectorMap[$type])) {
			throw new Exception('Missing reflector mapping type');
		}
		if (!class_exists(self::$_reflectorMap[$type])) {
			$reflectorName = 'ApiGenerator.' . self::$_reflectorMap[$type];
			App::import('Lib', $reflectorName);
		}
		return new self::$_reflectorMap[$type]($name);
	}
}
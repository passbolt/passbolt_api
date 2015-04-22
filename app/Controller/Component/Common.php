<?php
/**
 * Common Component
 * This class serves as a space for functions (mostly static) 
 * that need to be globally available within this application.
 *
 * @copyright		 copyright 2012 Passbolt.com
 * @package			 app.Controller.Common
 * @since				 version 2.12.7
 * @license			 http://www.passbolt.com/license
 */
class Common extends Object {

/**
 * Instanciate and return the reference to a model object
 * @param string name $model
 * @return model $ModelObj
 */
	public static function getModel($model,$create=false) {
		if (ClassRegistry::isKeySet($model) && !$create) {
			$ModelObj = ClassRegistry::getObject($model);
		} else {
			$ModelObj = ClassRegistry::init($model);
		}
		return $ModelObj;
	}

/**
 * Return a UUID - ref. String::uuid();
 * @param string seed, used to create deterministic UUID
 * @return uuid
 */
	public static function uuid($seed=null) {
		if (isset($seed)) {
			$pattern = '/^(.{8})(.{4})(.{4})(.{4})(.{12})$/';
			$replacement = '${1}-${2}-${3}-${4}-${5}';
			$string = md5($seed);
			return preg_replace($pattern,$replacement,$string);
		}
		return String::uuid();
	}

/**
 * Indicates if a given string is a UUID
 * @param string $str
 * @return boolean
 */
	public static function isUuid($str) {
		return is_string($str) && preg_match('/^[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[0-5][a-fA-F0-9]{3}-[089aAbB][a-fA-F0-9]{3}-[a-fA-F0-9]{12}$/', $str);
	}

/**
 * Generate a random string.
 * @param int $length length of the string.
 * @return string the random string
 */
	public static function randomString($length) {
		$mask = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$';
		$rdStr = substr(
			str_shuffle($mask) ,
			0,
			$length
		);
		return $rdStr;
	}

	/**
	 * Format a list of invalid fields to be returned to the client.
	 *
	 * @param $model
	 * @param $invalidFields
	 *
	 * @return array
	 */
	public static function formatInvalidFields($model, $invalidFields) {
		// Format invalid fields.
		// Add 'User' index in the array.
		$finalInvalidFields = array();
		$i = 0;
		foreach($invalidFields as $key => $if) {
			$finalInvalidFields[$i++][$model][$key] = $if;
		}
		return $finalInvalidFields;
	}

}

<?php
/**
 * Common Component
 * This class serves as a space for convenience functions (mostly static)
 * that need to be globally available within this application.
 *
 * @copyright 	(c) 2015-present Passbolt.com
 * @licence			GNU Public Licence v3 - www.gnu.org/licenses/gpl-3.0.en.html
 */
class Common extends Object {

/**
 * Instanciate and return the reference to a model object
 *
 * @param string $model name
 * @param bool $create init the model if not found in the class registry
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
 *
 * @param string $seed, used to create deterministic UUID
 * @return string UUID
 */
	public static function uuid($seed = null) {
		$pattern = '/^(.{8})(.{4})(.{1})(.{3})(.{1})(.{3})(.{12})$/';
		if (isset($seed)) {
			$string = substr(sha1($seed),0,32);
			$replacement = '${1}-${2}-3${4}-a${6}-${7}'; // v5
		} else {
			$string = bin2hex(openssl_random_pseudo_bytes(16));
			$replacement = '${1}-${2}-4${4}-a${6}-${7}'; // v4
		}
		return preg_replace($pattern,$replacement,$string);
	}

/**
 * Return true if a given string is a UUID
 *
 * @param string $str
 * @return boolean
 */
	public static function isUuid($str) {
		return is_string($str) && preg_match('/^[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[0-5][a-fA-F0-9]{3}-[089aAbB][a-fA-F0-9]{3}-[a-fA-F0-9]{12}$/', $str);
	}

/**
 * Generate a random string.
 *
 * @param integer $length length of the string.
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
 * @return array
 */
	public static function formatInvalidFields($model, $invalidFields) {
		// Add 'User' index in the array.
		$finalInvalidFields = array();
		$i = 0;
		foreach($invalidFields as $key => $if) {
			$finalInvalidFields[$i++][$model][$key] = $if;
		}
		return $finalInvalidFields;
	}

}

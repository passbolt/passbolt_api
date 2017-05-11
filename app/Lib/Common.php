<?php
/**
 * Common Component
 * This class serves as a space for convenience functions (mostly static)
 * that need to be globally available within this application.
 *
 * @copyright (c) 2015-2016 Bolt Softwares Pvt Ltd
 *                2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

class Common extends CakeObject {

/**
 * Instanciate and return the reference to a model object
 *
 * @param string $model name
 * @param bool $create init the model if not found in the class registry
 * @return model $ModelObj
 */
	public static function getModel($model, $create = false) {
		if (ClassRegistry::isKeySet($model) && !$create) {
			$ModelObj = ClassRegistry::getObject($model);
		} else {
			$ModelObj = ClassRegistry::init($model);
		}
		return $ModelObj;
	}

/**
 * Return a UUID v4 or v5
 *
 * @param string $seed used to create UUID v5
 * @return string UUID
 */
	public static function uuid($seed = null) {
		$pattern = '/^(.{8})(.{4})(.{1})(.{3})(.{1})(.{3})(.{12})$/';

		if (isset($seed)) {
			$string = substr(sha1($seed), 0, 32);
			$replacement = '${1}-${2}-3${4}-a${6}-${7}'; // v5
		} else {
			$string = bin2hex(openssl_random_pseudo_bytes(16));
			$replacement = '${1}-${2}-4${4}-a${6}-${7}'; // v4
		}
		return preg_replace($pattern, $replacement, $string);
	}

/**
 * Return true if a given string is a UUID
 *
 * @param string $str the UUID to be checked
 * @return bool true if str is a UUID
 */
	public static function isUuid($str) {
		$uuidRegex = '/^[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[0-5][a-fA-F0-9]{3}-[089aAbB][a-fA-F0-9]{3}-[a-fA-F0-9]{12}$/';
		return (is_string($str) && preg_match($uuidRegex, $str) === 1);
	}

/**
 * Return true if execution context is CLI
 */
	public static function isCli() {
		return (php_sapi_name() === "cli");
	}

/**
 * Return true if valid timestamp
 *
 * @param string $timestamp unixtimestamp
 * @return bool true if unix timestamp
 */
	public static function isTimestamp($timestamp) {
		return ((string) (int) $timestamp === $timestamp) && ($timestamp <= PHP_INT_MAX) && ($timestamp >= ~PHP_INT_MAX);
	}

/**
 * Return true if valid Json url
 */
 	public static function isJsonUrl($url) {
 		$baseUrl = explode('?', $url);
		return (preg_match('/(.json){1,}$/', $baseUrl[0]) === 1);
 	}

/**
 * Normalize string to boolean if it looks like one
 * 'TRUE', 'True', 'true', '1' becomes true
 * 'FALSE', 'False', 'false', '0' becomes false
 *
 * @param string $str the string to normalize
 * @return boolean|string if original string is not bool
 */
 	public static function normalizeIfBoolean($str) {
		if((strtolower($str) === 'true' || $str === '1')) {
			return true;
		} elseif ((strtolower($str) === 'false' || $str === '0')) {
			return false;
		} else {
			return $str;
		}
 	}
}

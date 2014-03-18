<?php
/**
 * Validation Class. Used for validation of model data.
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Model.Utility.AppValidation
 * @since        version 2.13.9
 */

App::uses('Validation', 'Utility');

/**
 * Offers different validation methods.
 */
class AppValidation extends Validation {

/**
 * Get the regex to validate url.
 * @return string
 */
	public static function getVadalidationUrlRegex() {
		Validation::_populateIp();
		$validChars = '([' . preg_quote('!"$&\'()*+,-.@_:;=~[]') . '\/0-9a-z\p{L}\p{N}]|(%[0-9a-f]{2}))';
		$regex = '/^(?:(?:https?|ftps?|sftp|file|news|gopher):\/\/)' . (!empty($strict) ? '' : '?') .
			'(?:' . Validation::$_pattern['IPv4'] . '|\[' . Validation::$_pattern['IPv6'] . '\]|' . Validation::$_pattern['hostname'] . ')(?::[1-9][0-9]{0,4})?' .
			'(?:\/?|\/' . $validChars . '*)?' .
			'(?:\?' . $validChars . '*)?' .
			'(?:#' . $validChars . '*)?$/iu';
		return $regex;
	}

}
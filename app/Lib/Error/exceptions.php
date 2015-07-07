<?php
/**
 * Exceptions file. Contains the various exceptions our app will throw.
 *
 * @copyright    copyright 2012 Passbolt.com
 * @package      app.Exceptions
 * @since        version 2.13.9
 * @license      http://www.passbolt.com/license
 */
class ValidationException extends Exception {
	public $invalidFields = null;

	public function __construct($message, array $invalidFields) {
		parent::__construct($message);
		$this->invalidFields = $invalidFields;
	}

	public function getInvalidFields() {
		return $this->invalidFields;
	}
}
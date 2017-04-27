<?php
/**
 * Exceptions file. Contains the various exceptions our app will throw.
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class ValidationException extends Exception {

	public $invalidFields = null;

/**
 * Construct the exception.
 *
 * @param string $message the Exception message to throw.
 * @param array $invalidFields fields missing validation and associated error messages
 */
	public function __construct($message, array $invalidFields = null) {
		parent::__construct($message);
		$this->invalidFields = $invalidFields;
	}

/**
 * Getter for validation error details
 *
 * @return array|null  fields missing validation and associated error messages
 */
	public function getInvalidFields() {
		return $this->invalidFields;
	}
}
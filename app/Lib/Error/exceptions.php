<?php
/**
 * Exceptions file. Contains the various exceptions our app will throw.
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
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
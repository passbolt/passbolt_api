<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
namespace App\Error\Exception;

use Cake\Core\Exception\Exception;

/**
 * Exception raised when a validation rule is not satisfied in a Controller.
 * Compared to ValidationException the second constructor parameter can be anything
 * e.g. a custom error message format, not necessarily an entity
 */
class CustomValidationException extends Exception
{

    /**
     * {@inheritDoc}
     */
    protected $_defaultCode = 400;

    /**
     * The validation errros.
     *
     * @var array|null
     */
    protected $_errors = null;

    /**
     * The table that throw the validation exception.
     *
     * @var \Cake\ORM\Table
     */
    protected $_table = null;

    /**
     * Constructor.
     *
     * @param string $message The error message
     * @param mixed|null $errors The validation errors.
     * @param \Cake\ORM\Table $table The table that is the source of the validation errors.
     * @param int $code The code of the error, is also the HTTP status code for the error.
     * @param \Exception|null $previous the previous exception.
     */
    public function __construct($message, $errors = null, $table = null, $code = null, $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->_errors = $errors;
        $this->_table = $table;
    }

    /**
     * Get the validation errors
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->_errors;
    }

    /**
     * Get the table
     *
     * @return \Cake\ORM\Table
     */
    public function getTable()
    {
        return $this->_table;
    }
}

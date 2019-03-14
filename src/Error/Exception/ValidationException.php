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
use Cake\ORM\Entity;
use Cake\ORM\Table;

/**
 * Exception raised when a validation rule is not satisfied in a Controller.
 */
class ValidationException extends Exception
{

    /**
     * {@inheritDoc}
     */
    protected $_defaultCode = 400;

    /**
     * The validated entity.
     *
     * @var array|null
     */
    protected $_entity = null;

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
     * @param Entity|null $entity The validation errors.
     * @param \Cake\ORM\Table $table The table that is the source of the validation errors.
     * @param int $code The code of the error, is also the HTTP status code for the error.
     * @param \Exception|null $previous the previous exception.
     */
    public function __construct(string $message, Entity $entity = null, Table $table = null, int $code = null, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->_entity = $entity;
        $this->_table = $table;
    }

    /**
     * Get the validation errors
     *
     * @return array
     */
    public function getEntity()
    {
        return $this->_entity;
    }

    /**
     * Get the validation errors
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->_entity->getErrors();
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

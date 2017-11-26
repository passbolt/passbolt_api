<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
namespace App\Error\Exception;

use Cake\Core\Exception\Exception;

/**
 * Exception raised when a validation rule is not satisfied in a Controller.
 */
class ArgumentCountErrorException extends Exception
{

    /**
     * {@inheritDoc}
     */
    protected $_defaultCode = 400;

    /**
     * Mixed value to use as response body
     *
     * @var mixed|null
     */
    protected $_body = null;

    /**
     * Constructor.
     *
     * @param string $message The error message
     * @param mixed|null $body The body of the error.
     * @param int $code The code of the error, is also the HTTP status code for the error.
     * @param \Exception|null $previous the previous exception.
     */
    public function __construct($message, $body = null, $code = null, $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->_body = $body;
    }

    /**
     * Get the passed in body
     *
     * @return array
     */
    public function getBody()
    {
        return $this->_body;
    }
}

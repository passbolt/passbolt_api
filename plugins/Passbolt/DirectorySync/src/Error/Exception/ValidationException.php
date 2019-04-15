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
namespace Passbolt\DirectorySync\Error\Exception;

use Cake\Core\Exception\Exception;
use LdapTools\Object\LdapObject;

/**
 * Exception raised when a validation rule is not satisfied in a Controller.
 */
class ValidationException extends Exception
{

    /**
     * The validated entity.
     *
     * @var array|null
     */
    protected $_ldapObject = null;

    /**
     * ValidationException constructor.
     *
     * @param string $message
     * @param LdapObject|null $ldapObject
     * @param null $code
     * @param null $previous
     */
    public function __construct(string $message, LdapObject $ldapObject = null, $code = null, $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->_ldapObject = $ldapObject;
    }

    /**
     * Get the validation errors
     *
     * @return array
     */
    public function getLdapObject()
    {
        return $this->_ldapObject;
    }
}

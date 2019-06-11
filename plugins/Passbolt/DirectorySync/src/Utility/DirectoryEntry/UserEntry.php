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
 * @since         2.2.0
 */
namespace Passbolt\DirectorySync\Utility\DirectoryEntry;

use Cake\Validation\Validation;
use LdapTools\Object\LdapObject;
use LdapTools\Object\LdapObjectType;

/**
 * Class UserEntry
 * @package Passbolt\DirectorySync\Utility\DirectoryEntry
 */
class UserEntry extends DirectoryEntry
{
    /**
     * User object
     * @var array
     */
    public $user;

    /**
     * Object type (user).
     * @var string
     */
    public $type = LdapObjectType::USER;

    /**
     * Build user entry from ldap object.
     * @param LdapObject $ldapObject ldap object.
     * @param array $mappingRules mapping rules.
     *
     * @return $this|DirectoryEntry directory entry.
     * @throws \Exception
     */
    public function buildFromLdapObject(LdapObject $ldapObject, array $mappingRules)
    {
        parent::buildFromLdapObject($ldapObject, $mappingRules);
        $this->user = [
            'username' => $this->getFieldValue('username'),
            'profile' => [
                'first_name' => $this->getFieldValue('firstname'),
                'last_name' => $this->getFieldValue('lastname'),
            ]
        ];
        $this->validate();

        return $this;
    }

    /**
     * Return the corresponding userEntry from a given ldapObject.
     * @param LdapObject $ldapObject ldap object.
     * @param array $mappingRules mapping rules.
     *
     * @return UserEntry user entry.
     * @throws \Exception
     */
    public static function fromLdapObject(LdapObject $ldapObject, array $mappingRules)
    {
        $userEntry = new UserEntry([]);
        $userEntry->buildFromLdapObject($ldapObject, $mappingRules);

        return $userEntry;
    }

    /**
     * Build user entry from array.
     * @param array $data data
     *
     * @return DirectoryEntry
     */
    public function buildFromArray(array $data)
    {
        parent::buildFromArray($data);
        if (!empty($data)) {
            $this->user = $data['user'];
        }
        $this->validate();

        return $this;
    }

    /**
     * Return a user entry from an array.
     * @param array $data data
     *
     * @return UserEntry the user entry.
     */
    public static function fromArray(array $data)
    {
        $userEntry = new UserEntry($data);

        return $userEntry;
    }

    /**
     * Validate user entry.
     * @return bool
     */
    protected function _validate()
    {
        parent::_validate();

        if (empty($this->user['username'])) {
            $this->_addError('email', 'user email could not be retrieved');
        } elseif (!Validation::email($this->user['username'], false)) {
            $this->_addError('email', 'user email does not seem to have a valid email format');
        }
        if (empty($this->user['profile']['first_name'])) {
            $this->_addError('first_name', 'user first name could not be retrieved');
        }
        if (empty($this->user['profile']['last_name'])) {
            $this->_addError('last_name', 'user last name could not be retrieved');
        }

        return !$this->hasErrors();
    }

    /**
     * Validate User entry.
     */
    public function validate()
    {
        return $this->_validate();
    }

    /**
     * Convert a user entry into a simple array.
     * @return array
     */
    public function toArray()
    {
        $extraData = [
            'user' => $this->user,
        ];

        return array_merge(parent::toArray(), $extraData);
    }
}

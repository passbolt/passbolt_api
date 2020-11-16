<?php
declare(strict_types=1);

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

use ArrayAccess;
use Cake\I18n\FrozenTime;
use LdapTools\Object\LdapObject;
use LdapTools\Object\LdapObjectType;
use LdapTools\Utilities\LdapUtilities;

abstract class DirectoryEntry implements ArrayAccess
{
    /**
     * id
     *
     * @var string
     */
    public $id;

    /**
     * DN (directory name)
     *
     * @var string
     */
    public $dn;

    /**
     * created date.
     *
     * @var string.
     */
    public $created;

    /**
     * modified date.
     *
     * @var string.
     */
    public $modified;

    /**
     * Object type.
     *
     * @var null
     */
    public $type = null;

    /**
     * Corresponding ldap object.
     *
     * @var null
     */
    private $ldapObject = null;

    /**
     * Mapping rules.
     *
     * @var null
     */
    private $mappingRules = null;

    /**
     * Validation errors.
     *
     * @var
     */
    private $errors = [];

    /**
     * DirectoryEntry constructor.
     *
     * @param array|null $data data
     */
    public function __construct(?array $data = [])
    {
        if (!empty($data)) {
            $this->buildFromArray($data);
        }
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value)
    {
        if (!is_null($offset)) {
            $this->{$offset} = $value;
        }
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset)
    {
        return isset($this->{$offset});
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset)
    {
        unset($this->{$offset});
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        return $this->{$offset} ?? null;
    }

    /**
     * Check if entry is a group.
     *
     * @return bool true or false
     */
    public function isGroup()
    {
        return $this->type === LdapObjectType::GROUP;
    }

    /**
     * Check if entry is a user.
     *
     * @return bool true or false
     */
    public function isUser()
    {
        return $this->type === LdapObjectType::USER;
    }

    /**
     * Get field value.
     *
     * @param string $fieldName field name
     * @return mixed field value
     * @throws \Exception if the corresponding field name cannot be found.
     */
    public function getFieldValue(string $fieldName)
    {
        return self::getLdapObjectFieldValue($this->ldapObject, $fieldName, $this->mappingRules);
    }

    /**
     * Get ldap object field value.
     *
     * @param \LdapTools\Object\LdapObject $ldapObject ldap object.
     * @param string $fieldName field name.
     * @param array $mappingRules mapping rules.
     * @return mixed field value
     * @throws \Exception
     */
    public static function getLdapObjectFieldValue(LdapObject $ldapObject, string $fieldName, array $mappingRules)
    {
        $mappingRules = $mappingRules[$ldapObject->getType()];
        if (!isset($mappingRules[$fieldName])) {
            throw new \Exception('There is no mapping rule associated for the field: ' . $fieldName);
        }

        $fieldEquivalent = $mappingRules[$fieldName];
        $call = 'get' . ucfirst($fieldEquivalent);

        return $ldapObject->{$call}();
    }

    /**
     * Build entry from array.
     *
     * @param array $data array of data
     * @return $this
     */
    public function buildFromArray(array $data)
    {
        $this->ldapObject = null;
        $this->mappingRules = null;

        $this->id = $data['id'];
        $this->dn = $data['directory_name'];
        $this->created = $data['directory_created'];
        $this->modified = $data['directory_modified'];

        return $this;
    }

    /**
     * Build entry from a ldap object.
     *
     * @param \LdapTools\Object\LdapObject $ldapObject ldap object
     * @param array $mappingRules mapping rules
     * @return $this
     * @throws \Exception
     */
    public function buildFromLdapObject(LdapObject $ldapObject, array $mappingRules)
    {
        $this->ldapObject = $ldapObject;
        $this->mappingRules = $mappingRules;

        $this->id = $this->getFieldValue('id');
        $this->dn = $ldapObject->getDn();

        $created = $this->getFieldValue('created');
        if (!empty($created)) {
            $this->created = new FrozenTime($created);
        }

        $modified = $this->getFieldValue('modified');
        if (!empty($modified)) {
            $this->modified = new FrozenTime($modified);
        }

        return $this;
    }

    /**
     * Validate a DirectoryEntry object and populate errors accordingly.
     *
     * @return bool
     */
    protected function _validate()
    {
        $this->errors = [];

        if (empty($this->id)) {
            $this->_addError('id', 'id could not be retrieved');
        } elseif (!LdapUtilities::isValidGuid($this->id)) {
            $this->_addError('id', 'id does not match the expected Guid format');
        }

        if (empty($this->dn)) {
            $this->_addError('dn', 'dn could not be retrieved');
        } elseif (!LdapUtilities::isValidLdapObjectDn($this->dn)) {
            $this->_addError('dn', 'dn does not match the expected DN format');
        }

        if (empty($this->created)) {
            $this->_addError('created', 'created could not be retrieved');
        }
        if (empty($this->modified)) {
            $this->_addError('modified', 'modified could not be retrieved');
        }

        return $this->hasErrors();
    }

    /**
     * Add a validation error in the list of errors.
     *
     * @param string $field field name
     * @param string $errorMsg error message
     * @return void
     */
    protected function _addError(string $field, string $errorMsg)
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }

        $this->errors[$field][] = $errorMsg;
    }

    /**
     * Return validation errors. an empty array if none.
     *
     * @return array validation errors.
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * Return errors as a single string.
     *
     * @return string validation errors as a single string.
     */
    public function getErrorsAsString()
    {
        $str = '';
        foreach ($this->errors as $field => $errors) {
            foreach ($errors as $errorMsg) {
                $str .= "$field: $errorMsg\n";
            }
        }

        return $str;
    }

    /**
     * Check if Directory entry has validation errors.
     *
     * @return bool true if errors, false otherwise.
     */
    public function hasErrors()
    {
        return !empty($this->errors());
    }

    /**
     * Transform entry to a simple array.
     *
     * @return array
     */
    public function toArray()
    {
        $res = [
            'type' => $this->type,
            'id' => $this->id,
            'directory_name' => $this->dn,
            'directory_created' => $this->created,
            'directory_modified' => $this->modified,
        ];

        if ($this->hasErrors()) {
            $res['errors'] = $this->errors();
        }

        return $res;
    }

    /**
     * Get entry type.
     *
     * @return mixed|null entry type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Build from array.
     *
     * @param array $data data
     * @return mixed DirectoryEntry
     */
    abstract public static function fromArray(array $data);

    /**
     * Build from ldap object.
     *
     * @param \LdapTools\Object\LdapObject $ldapObject ldap object.
     * @param array $mappingRules mapping rules.
     * @return mixed DirectoryEntry
     */
    abstract public static function fromLdapObject(LdapObject $ldapObject, array $mappingRules);
}

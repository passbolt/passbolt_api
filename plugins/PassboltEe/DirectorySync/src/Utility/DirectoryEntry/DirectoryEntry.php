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
use LdapRecord\Models\Entry;
use LdapRecord\Utilities;
use Passbolt\DirectorySync\Utility\DirectoryInterface;

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
     * @var string|\Cake\I18n\FrozenTime
     */
    public $created;

    /**
     * modified date.
     *
     * @var string|\Cake\I18n\FrozenTime
     */
    public $modified;

    /**
     * Object type.
     *
     * @var string|null
     */
    public $type = null;

    /**
     * Corresponding ldap object.
     *
     * @var \LdapRecord\Models\Entry|null
     */
    private $ldapObject = null;

    /**
     * Mapping rules.
     *
     * @var array|null
     */
    private $mappingRules = null;

    /**
     * Validation errors.
     *
     * @var array
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
     * @param mixed $offset Offset
     * @param mixed $value Value
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        if (!is_null($offset)) {
            $this->{$offset} = $value;
        }
    }

    /**
     * @param mixed $offset Offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return isset($this->{$offset});
    }

    /**
     * @param mixed $offset Offset
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->{$offset});
    }

    #[\ReturnTypeWillChange]

    /**
     * @param mixed $offset Offset
     * @return mixed|null // not strict for 7.3 compatibility
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
    public function isGroup(): bool
    {
        return $this->type === DirectoryInterface::ENTRY_TYPE_GROUP;
    }

    /**
     * Check if entry is a user.
     *
     * @return bool true or false
     */
    public function isUser(): bool
    {
        return $this->type === DirectoryInterface::ENTRY_TYPE_USER;
    }

    /**
     * Get field value.
     *
     * @param string $fieldName field name
     * @param bool $first Returns first attribute found
     * @return mixed field value
     * @throws \Exception if the corresponding field name cannot be found.
     */
    public function getFieldValue(string $fieldName, bool $first = true)
    {
        return self::getLdapObjectFieldValue($this->ldapObject, $fieldName, $this->mappingRules, $first);
    }

    /**
     * Get ldap object field value.
     *
     * @param \LdapRecord\Models\Entry $ldapObject ldap object.
     * @param string $fieldName field name.
     * @param array $mappingRules mapping rules.
     * @param bool $first Returns first attribute found
     * @return mixed field
     * @throws \Exception
     */
    public static function getLdapObjectFieldValue(
        Entry $ldapObject,
        string $fieldName,
        array $mappingRules,
        bool $first = true
    ) {
        /** @var string $type */
        $type = $ldapObject->getFirstAttribute('objectType');
        $mappingRules = $mappingRules[$type];
        if (!isset($mappingRules[$fieldName])) {
            throw new \Exception('There is no mapping rule associated for the field: ' . $fieldName);
        }

        $fieldEquivalent = $mappingRules[$fieldName];

        return $first ?
            $ldapObject->getFirstAttribute(ucfirst($fieldEquivalent)) :
            $ldapObject->getAttribute(ucfirst($fieldEquivalent));
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
     * @param \LdapRecord\Models\Entry $ldapObject ldap object
     * @param array $mappingRules mapping rules
     * @return $this
     * @throws \Exception
     */
    public function buildFromLdapObject(Entry $ldapObject, array $mappingRules)
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
    protected function _validate(): bool
    {
        $this->errors = [];

        if (empty($this->id)) {
            $this->_addError('id', 'id could not be retrieved');
        } elseif (!Utilities::isValidGuid($this->id)) {
            $this->_addError('id', 'id does not match the expected Guid format');
        }

        if (empty($this->dn)) {
            $this->_addError('dn', 'dn could not be retrieved');
        } elseif (!self::isValidDn($this->dn)) {
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
     * Validate DN
     *
     * @param string $dn Distinguished name
     * @return bool
     */
    public static function isValidDn(string $dn): bool
    {
        return ($pieces = Utilities::explodeDn($dn)) && count($pieces) >= 2;
    }

    /**
     * Add a validation error in the list of errors.
     *
     * @param string $field field name
     * @param string $errorMsg error message
     * @return void
     */
    protected function _addError(string $field, string $errorMsg): void
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
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * Return errors as a single string.
     *
     * @return string validation errors as a single string.
     */
    public function getErrorsAsString(): string
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
    public function hasErrors(): bool
    {
        return !empty($this->errors());
    }

    /**
     * Transform entry to a simple array.
     *
     * @return array
     */
    public function toArray(): array
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
     * @return string|null entry type
     */
    public function getType(): ?string
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
     * @param \LdapRecord\Models\Entry $ldapObject ldap object.
     * @param array $mappingRules mapping rules.
     * @return mixed DirectoryEntry
     */
    abstract public static function fromLdapObject(Entry $ldapObject, array $mappingRules);
}

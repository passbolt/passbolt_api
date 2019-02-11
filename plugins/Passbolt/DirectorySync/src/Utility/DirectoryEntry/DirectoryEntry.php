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

use ArrayAccess;
use Cake\I18n\FrozenTime;
use LdapTools\Object\LdapObject;
use LdapTools\Object\LdapObjectType;

abstract class DirectoryEntry implements ArrayAccess
{
    /**
     * id
     * @var string
     */
    public $id;

    /**
     * DN (directory name)
     * @var string
     */
    public $dn;

    /**
     * created date.
     * @var string.
     */
    public $created;

    /**
     * modified date.
     * @var string.
     */
    public $modified;

    /**
     * Object type.
     * @var null
     */
    public $type = null;

    /**
     * Corresponding ldap object.
     * @var null
     */
    private $ldapObject = null;

    /**
     * Mapping rules.
     * @var null
     */
    private $mappingRules = null;

    /**
     * DirectoryEntry constructor.
     *
     * @param array $data data
     */
    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $this->buildFromArray($data);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        if (!is_null($offset)) {
            $this->{$offset} = $value;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->{$offset});
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->{$offset});
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return isset($this->{$offset}) ? $this->{$offset} : null;
    }

    /**
     * Check if entry is a group.
     * @return bool true or false
     */
    public function isGroup()
    {
        return $this->type === LdapObjectType::GROUP;
    }

    /**
     * Check if entry is a user.
     * @return bool true or false
     */
    public function isUser()
    {
        return $this->type === LdapObjectType::USER;
    }

    /**
     * Get field value.
     * @param string $fieldName field name
     *
     * @return mixed field value
     * @throws \Exception if the corresponding field name cannot be found.
     */
    public function getFieldValue(string $fieldName)
    {
        return self::getLdapObjectFieldValue($this->ldapObject, $fieldName, $this->mappingRules);
    }

    /**
     * Get ldap object field value.
     * @param LdapObject $ldapObject ldap object.
     * @param string $fieldName field name.
     * @param array $mappingRules mapping rules.
     *
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
     * @param array $data array of data
     *
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
     * @param LdapObject $ldapObject ldap object
     * @param array $mappingRules mapping rules
     *
     * @return $this
     * @throws \Exception
     */
    public function buildFromLdapObject(LdapObject $ldapObject, array $mappingRules)
    {
        $this->ldapObject = $ldapObject;
        $this->mappingRules = $mappingRules;

        $this->id = $this->getFieldValue('id');
        $this->dn = $ldapObject->getDn();
        $this->created = new FrozenTime($this->getFieldValue('created'));
        $this->modified = new FrozenTime($this->getFieldValue('modified'));

        return $this;
    }

    /**
     * Transform entry to a simple array.
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'directory_name' => $this->dn,
            'directory_created' => $this->created,
            'directory_modified' => $this->modified,
        ];
    }

    /**
     * Get entry type.
     * @return mixed|null entry type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Build from array.
     * @param array $data data
     *
     * @return mixed DirectoryEntry
     */
    abstract public static function fromArray(array $data);

    /**
     * Build from ldap object.
     * @param LdapObject $ldapObject ldap object.
     * @param array $mappingRules mapping rules.
     *
     * @return mixed DirectoryEntry
     */
    abstract public static function fromLdapObject(LdapObject $ldapObject, array $mappingRules);
}

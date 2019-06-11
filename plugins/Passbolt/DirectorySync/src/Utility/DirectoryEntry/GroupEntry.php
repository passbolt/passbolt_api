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

use LdapTools\Utilities\LdapUtilities;
use LdapTools\Object\LdapObject;
use LdapTools\Object\LdapObjectType;

/**
 * Class GroupEntry
 * @package Passbolt\DirectorySync\Utility\DirectoryEntry
 */
class GroupEntry extends DirectoryEntry
{
    /**
     * Group.
     * @var array
     */
    public $group;

    /**
     * Type of object (group).
     * @var string
     */
    public $type = LdapObjectType::GROUP;

    /**
     * Build a groupEntry from a ldap object.
     * @param LdapObject $ldapObject ldap object.
     * @param array $mappingRules mapping rules
     *
     * @return $this|DirectoryEntry directory entry
     * @throws \Exception
     */
    public function buildFromLdapObject(LdapObject $ldapObject, array $mappingRules)
    {
        parent::buildFromLdapObject($ldapObject, $mappingRules);
        $this->group = [
            'name' => $this->getFieldValue('name'),
            'members' => $this->getFieldValue('users'),
            // groups and users can't be retrieved from the ldap object.
            // these values will be populated afterwards by DirectoryResults.
            'groups' => [],
            'users' => [],
        ];
        $this->validate();

        return $this;
    }

    /**
     * Return a groupEntry from a ldap object.
     * @param LdapObject $ldapObject ldap object.
     * @param array $mappingRules mapping rules.
     *
     * @return GroupEntry group entry
     * @throws \Exception
     */
    public static function fromLdapObject(LdapObject $ldapObject, array $mappingRules)
    {
        $groupEntry = new GroupEntry([]);
        $groupEntry->buildFromLdapObject($ldapObject, $mappingRules);

        return $groupEntry;
    }

    /**
     * Build a group Entry from array.
     * @param array $data array
     *
     * @return $this|DirectoryEntry the GroupEntry
     */
    public function buildFromArray(array $data)
    {
        parent::buildFromArray($data);

        // If groups and users are note provided, we leave them empty.
        // They will be populated later by DirectoryResults.
        $groups = isset($data['group']['groups']) ? $data['group']['groups'] : [];
        $users = isset($data['group']['users']) ? $data['group']['users'] : [];
        $groupsUsers = array_merge($groups, $users);
        $members = isset($data['group']['members']) ? $data['group']['members'] : [];

        $this->group = [
            'name' => isset($data['group']['name']) ? $data['group']['name'] : '',
            'members' => !empty($members) ? $members : $groupsUsers,
            'groups' => $groups,
            'users' => $users,
        ];
        $this->validate();

        return $this;
    }

    /**
     * Return a GroupEntry from an array of data.
     * @param array $data array of data.
     *
     * @return GroupEntry the group entry
     */
    public static function fromArray(array $data)
    {
        $groupEntry = new GroupEntry($data);

        return $groupEntry;
    }

    /**
     * Validate Group entry.
     */
    public function validate()
    {
        return $this->_validate();
    }

    /**
     * Validate group entry.
     * @return bool
     */
    protected function _validate()
    {
        parent::_validate();

        if (empty($this->group['name'])) {
            $this->_addError('name', 'group name could not be retrieved');
        }

        if (isset($this->group['members']) && !empty($this->group['members'])) {
            foreach ($this->group['members'] as $groupMember) {
                if (!LdapUtilities::isValidLdapObjectDn($groupMember)) {
                    $this->_addError('members', 'a group member does not match the expected DN format');
                }
            }
        }

        return !$this->hasErrors();
    }

    /**
     * Transforms the Group entry into an array.
     * @return array
     */
    public function toArray()
    {
        $extraData = [
            'group' => $this->group,
        ];

        // Transform children into arrays too.
        if (isset($extraData['group']['groups']) && !empty($extraData['group']['groups'])) {
            foreach ($extraData['group']['groups'] as $key => $group) {
                if (is_object($group)) {
                    $extraData['group']['groups'][$key] = $group->toArray();
                }
            }
        }

        if (isset($extraData['group']['users']) && !empty($extraData['group']['users'])) {
            foreach ($extraData['group']['users'] as $key => $user) {
                if (is_object($user)) {
                    $extraData['group']['users'][$key] = $user->toArray();
                }
            }
        }

        return array_merge(parent::toArray(), $extraData);
    }

    /**
     * Set group members.
     * @param array $members group members
     * @return void
     */
    public function setGroupMembers(array $members)
    {
        $this->group['members'] = $members;
    }

    /**
     * Set children groups.
     * Usually this function is called after a calculation has been done to deduct who the children are.
     * @param array $groups list of children groups.
     * @return void
     */
    public function setGroupGroups(array $groups)
    {
        $this->group['groups'] = $groups;
    }

    /**
     * Set children Groups users.
     * Usually this function is called after a calculation has been done to deduct who the children are.
     * @param array $users the children groups users
     * @return void
     */
    public function setGroupUsers(array $users)
    {
        $this->group['users'] = $users;
    }

    /**
     * Get group members.
     * @return mixed
     */
    public function getGroupMembers()
    {
        return $this->group['members'];
    }

    /**
     * Get groups groups.
     * @return mixed
     */
    public function getGroupGroups()
    {
        return $this->group['groups'];
    }

    /**
     * Get groups users.
     * @return mixed
     */
    public function getGroupUsers()
    {
        return $this->group['users'];
    }
}

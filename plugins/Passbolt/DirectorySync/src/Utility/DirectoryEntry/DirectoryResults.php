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

use LdapTools\Object\LdapObjectCollection;
use LdapTools\Object\LdapObjectType;

/**
 * Class DirectoryResults
 * @package Passbolt\DirectorySync\Utility\DirectoryEntry
 */
class DirectoryResults
{
    /**
     * Raw ldap groups as returned by ldap directory.
     * @var array
     */
    private $ldapGroups;

    /**
     * Raw ldap users as returned by ldap directory.
     * @var array
     */
    private $ldapUsers;

    /**
     * mapping rules
     * @var array
     */
    private $mappingRules;

    /**
     * Groups
     * @var array
     */
    private $groups;

    /**
     * Users
     * @var array
     */
    private $users;

    /**
     * DirectoryResults constructor.
     *
     * @param array $mappingRules mapping rules
     */
    public function __construct(array $mappingRules)
    {
        $this->mappingRules = $mappingRules;
        $this->ldapUsers = [];
        $this->ldapGroups = [];
        $this->users = [];
        $this->groups = [];
    }

    /**
     * Initialize results with results returned from ldap directory.
     * @param LdapObjectCollection $ldapUsers ldap users
     * @param LdapObjectCollection $ldapGroups ldap groups
     * @return void
     */
    public function initializeWithLdapResults(LdapObjectCollection $ldapUsers, LdapObjectCollection $ldapGroups)
    {
        $this->ldapGroups = $ldapGroups;
        $this->ldapUsers = $ldapUsers;

        $this->_populateUsers();
        $this->_populateGroups();
        $this->_populateAllGroupsUsersDetails();
    }

    /**
     * Initialize results with list of entries.
     * @param array $userEntries user entries
     * @param array $groupEntries group entries
     * @return void
     */
    public function initializeWithEntries(array $userEntries = [], array $groupEntries = [])
    {
        $this->users = [];
        $this->groups = [];

        foreach ($userEntries as $userEntry) {
            if (is_array($userEntry)) {
                $userEntry = UserEntry::fromArray($userEntry);
            }
            $this->users[$userEntry->dn] = $userEntry;
        }

        foreach ($groupEntries as $groupEntry) {
            if (is_array($groupEntry)) {
                $groupEntry = GroupEntry::fromArray($groupEntry);
            }
            $this->groups[$groupEntry->dn] = $groupEntry;
        }

        $this->_populateAllGroupsUsersDetails();
    }

    /**
     * Populate groups list from Ldap results.
     * @return void
     * @throws \Exception
     */
    private function _populateGroups()
    {
        foreach ($this->ldapGroups as $ldapGroup) {
            if (!isset($this->groups[$ldapGroup->getDn()])) {
                $this->groups[$ldapGroup->getDn()] = GroupEntry::fromLdapObject($ldapGroup, $this->mappingRules);
            }
        }
    }

    /**
     * Populate users from Ldap results.
     * @return void
     * @throws \Exception
     */
    private function _populateUsers()
    {
        foreach ($this->ldapUsers as $ldapUser) {
            if (!isset($this->users[$ldapUser->getDn()])) {
                $this->users[$ldapUser->getDn()] = UserEntry::fromLdapObject($ldapUser, $this->mappingRules);
            }
        }
    }

    /**
     * Populate Group users details for all groups.
     * @return void
     */
    private function _populateAllGroupsUsersDetails()
    {
        foreach ($this->groups as $key => $group) {
            $this->groups[$key] = $this->_populateGroupGroupsUserDetails($group);
        }
    }

    /**
     * Populate Group users details for the given group.
     * @param GroupEntry $group group
     *
     * @return GroupEntry returned group populated with groups users
     */
    private function _populateGroupGroupsUserDetails(GroupEntry $group)
    {
        if (empty($group['group']['members'])) {
            return $group;
        }

        $groups = [];
        $users = [];
        foreach ($group['group']['members'] as $memberDn) {
            if ($this->isGroup($memberDn)) {
                $groups[] = $memberDn;
            } elseif ($this->isUser($memberDn)) {
                $users[] = $memberDn;
            }
        }

        $group->setGroupUsers($users);
        $group->setGroupGroups($groups);

        return $group;
    }

    /**
     * Get users.
     * @return array
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Get groups.
     * @return array
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Check if results are populated (at least users or groups should be available).
     * @return bool true or false
     */
    public function isEmpty()
    {
        return (empty($this->users) && empty($this->groups));
    }

    /**
     * Check if a directory name is a group.
     * @param string $memberDN the directory name
     *
     * @return bool
     */
    public function isGroup(string $memberDN)
    {
        return isset($this->groups[$memberDN]);
    }

    /**
     * Check if a directory name is a group.
     * @param string $memberDN the directory name
     *
     * @return bool
     */
    public function isUser(string $memberDN)
    {
        return isset($this->users[$memberDN]);
    }

    /**
     * Get recursively the group members for a given group and type.
     * @param string $objectType type of object to be returned (Group or User).
     * @param GroupEntry $group the group to work on.
     * @param array $membersList members list
     *
     * @return array
     */
    private function _getGroupMembersRecursive(string $objectType, GroupEntry $group, array &$membersList)
    {
        if ($objectType == LdapObjectType::GROUP) {
            $members = $group['group']['groups'];
        } else {
            $members = $group['group']['users'];
        }

        foreach ($members as $memberDn) {
            if ($objectType == LdapObjectType::GROUP) {
                $member = $this->groups[$memberDn];
            } else {
                $member = $this->users[$memberDn];
            }
            if (empty($membersList) || !in_array($member, $membersList)) {
                $membersList[] = $member;
            }
        }

        $childGroups = $group['group']['groups'];
        foreach ($childGroups as $childGroupDn) {
            $childGroup = $this->groups[$childGroupDn];
            $this->_getGroupMembersRecursive($objectType, $childGroup, $membersList);
        }

        return $membersList;
    }

    /**
     * Get members of provided parent group recursively.
     * Used when the filter fromGroup is set by users.
     *
     * @param string $objectType type of member (user or group)
     * @param string $groupName name of the group
     *
     * @return DirectoryResults the list of members
     *
     * @throws \Exception
     */
    public function getRecursivelyFromParentGroup(string $objectType, string $groupName)
    {
        $group = $this->lookupGroupByGroupName($groupName);

        if (!$group) {
            throw new \Exception('Could not retrieve the group matching name ' . $groupName);
        }

        $groupsList = [];
        $members = $this->_getGroupMembersRecursive($objectType, $group, $groupsList);

        $directoryResults = new DirectoryResults($this->mappingRules);
        if ($objectType == LdapObjectType::USER) {
            $directoryResults->initializeWithEntries($members, []);
        } elseif ($objectType == LdapObjectType::GROUP) {
            // Retrieve the list of all the users that are part of the returned groups.
            $groupUsers = $this->_getUsersForGroups($members);
            $directoryResults->initializeWithEntries($groupUsers, $members);
        }

        return $directoryResults;
    }

    /**
     * Get all groups without parents.
     * @return array list of groups.
     */
    private function _getRootGroups()
    {
        $allChildren = [];
        foreach ($this->groups as $group) {
            $allChildren = array_merge($allChildren, $group->group['groups']);
        }

        $rootGroups = array_diff(array_keys($this->groups), $allChildren);
        $res = [];
        foreach ($rootGroups as $rootGroupDn) {
            $res[$rootGroupDn] = $this->groups[$rootGroupDn];
        }

        return $res;
    }

    /**
     * Get all users without parents.
     * @return array list of users.
     */
    private function _getRootUsers()
    {
        $allChildren = [];
        foreach ($this->groups as $group) {
            $allChildren = array_merge($allChildren, $group->group['users']);
        }

        $rootUsers = array_diff(array_keys($this->users), $allChildren);
        $res = [];
        foreach ($rootUsers as $rootUser) {
            $res[$rootUser] = $this->users[$rootUser];
        }

        return $res;
    }

    /**
     * Build the recursive tree of children for a given group.
     * @param GroupEntry $group group
     *
     * @return GroupEntry $group GroupEntry populated with groups and users recursively.
     */
    private function _getChildrenRecursive(GroupEntry $group)
    {
        $groups = [];
        $users = [];
        $g = clone $group;

        foreach ($g['group']['groups'] as $key => $groupDn) {
            $groupObj = $this->groups[$groupDn];
            $groups[$groupDn] = $this->_getChildrenRecursive($groupObj);
        }
        foreach ($g['group']['users'] as $userDn) {
            $users[$userDn] = $this->users[$userDn];
        }

        $g->group['groups'] = $groups;
        $g->group['users'] = $users;

        return $g;
    }

    /**
     * Get nested tree of groups and users.
     * @return array nested tree.
     */
    public function getTree()
    {
        $orphans = $this->_getRootGroups();
        $res = [];
        foreach ($orphans as $orphanDn => $orphan) {
            $res[$orphanDn] = $this->_getChildrenRecursive($orphan);
        }

        $rootUsers = $this->_getRootUsers();
        $res = array_merge($res, $rootUsers);

        return $res;
    }

    /**
     * Get flattened list of children for a given group.
     * @param GroupEntry $group the group
     * @param array $flatTree flat tree array
     * @param int $level the current level
     * @return void
     */
    private function _getFlattenedChildrenRecursive(GroupEntry &$group, array &$flatTree, int $level)
    {
        $group->level = $level;
        $flatTree[] = $group;
        foreach ($group['group']['groups'] as $g) {
            $this->_getFlattenedChildrenRecursive($g, $flatTree, ++ $level);
        }
    }

    /**
     * Get flattened tree representation of tree.
     * @return array the flattened tree.
     */
    public function getFlattenedTree()
    {
        $flatTree = [];
        $level = 0;
        $tree = $this->getTree();
        foreach ($tree as $entity) {
            if ($entity->isUser()) {
                $entity->level = $level;
                $flatTree[] = $entity;
            }
        }
        foreach ($tree as $entity) {
            if ($entity->isGroup()) {
                $this->_getFlattenedChildrenRecursive($entity, $flatTree, $level);
            }
        }

        return $flatTree;
    }

    /**
     * Get users for a list of groups.
     * @param array $groups a list of GroupEntry
     *
     * @return array a list of User entries.
     */
    private function _getUsersForGroups(array $groups)
    {
        $users = [];
        foreach ($groups as $group) {
            $groupUsers = $this->_getUsersForGroup($group);
            $users = array_merge($users, $groupUsers);
        }

        return $users;
    }

    /**
     * Get users for a given group.
     * @param GroupEntry $group group entry
     *
     * @return array a list of UserEntry users.
     */
    private function _getUsersForGroup(GroupEntry $group)
    {
        $users = [];
        foreach ($group['group']['users'] as $userDn) {
            $users[$userDn] = $this->users[$userDn];
        }

        return $users;
    }

    /**
     * Lookup for a group by its name.
     * @param string $name group name
     *
     * @return array|null the corresponding GroupEntry
     */
    public function lookupGroupByGroupName(string $name)
    {
        foreach ($this->groups as $group) {
            if ($group['group']['name'] === $name) {
                return $group;
            }
        }

        return null;
    }

    /**
     * Return the list of users in a simple array format.
     * @return array array of users.
     */
    public function getUsersAsArray()
    {
        $results = [];
        foreach ($this->users as $user) {
            $results[] = $user->toArray();
        }

        return $results;
    }

    /**
     * Return the list of groups in a simple array format.
     * @return array array of groups.
     */
    public function getGroupsAsArray()
    {
        $results = [];
        foreach ($this->groups as $group) {
            $results[] = $group->toArray();
        }

        return $results;
    }
}

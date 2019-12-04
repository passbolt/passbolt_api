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

use App\Utility\UuidFactory;
use LdapTools\Object\LdapObject;
use LdapTools\Object\LdapObjectCollection;
use LdapTools\Object\LdapObjectType;
use Passbolt\DirectorySync\Utility\DirectoryOrgSettings;

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
     * Directory settings
     */
    private $directorySettings;

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
     * Invalid users which will be ignored.
     * @var
     */
    private $invalidUsers;

    /**
     * Invalid groups which will be ignored.
     * @var
     */
    private $invalidGroups;

    /**
     * DirectoryResults constructor.
     *
     * @param array $mappingRules mapping rules
     * @param DirectoryOrgSettings $settings settings. If null, will retrieve settings through standard way.
     * @return void
     */
    public function __construct(array $mappingRules, DirectoryOrgSettings $settings = null)
    {
        $this->mappingRules = $mappingRules;
        $this->directorySettings = $settings !== null ? $settings : DirectoryOrgSettings::get();
        $this->ldapUsers = [];
        $this->ldapGroups = [];
        $this->users = [];
        $this->groups = [];
        $this->invalidUsers = [];
        $this->invalidGroups = [];
    }

    /**
     * Initialize results with results returned from ldap directory.
     * @param LdapObjectCollection $ldapUsers ldap users
     * @param LdapObjectCollection $ldapGroups ldap groups
     * @return void
     * @throws \Exception
     */
    public function initializeWithLdapResults(LdapObjectCollection $ldapUsers, LdapObjectCollection $ldapGroups)
    {
        $this->ldapGroups = $ldapGroups;
        $this->ldapUsers = $ldapUsers;

        // Transform ldap users and groups according to config. (add emails or ids if missing).
        $this->transformLdapUsers();
        $this->transformLdapGroups();

        // Populate users and groups with their corresponding entries.
        $this->_populateUsers();
        $this->_populateGroups();

        // Process users and groups relationships.
        $this->_populateAllGroupsUsersDetails();
    }

    /**
     * Initialize results with list of entries.
     * This function is mainly available for testing.
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
     * Transform ldap users.
     * @return void
     */
    public function transformLdapUsers()
    {
        foreach ($this->ldapUsers as $ldapUser) {
            $this->transformLdapUser($ldapUser);
        }
    }

    /**
     * Transform ldap groups.
     * @return void
     */
    public function transformLdapGroups()
    {
        foreach ($this->ldapGroups as $ldapGroup) {
            $this->transformLdapGroup($ldapGroup);
        }
    }

    /**
     * Transform one ldap group.
     * Add a uuid if not present (based on cn)
     * @param LdapObject $ldapGroup ldap group
     *
     * @return LdapObject ldap object
     */
    public function transformLdapGroup(LdapObject $ldapGroup)
    {
        $this->_transformId($ldapGroup);

        return $ldapGroup;
    }

    /**
     * Transform one ldap user.
     * Add a uuid if not present (based on cn)
     * Build an email if defined in the configuration.
     * @param LdapObject $ldapUser ldap user
     *
     * @return LdapObject ldap object
     */
    public function transformLdapUser(LdapObject $ldapUser)
    {
        $this->_transformId($ldapUser);
        $this->_transformEmail($ldapUser);

        return $ldapUser;
    }

    /**
     * Transform ldap object email based on provided configuration.
     * @param LdapObject $ldapUser ldap user
     * @return void
     */
    protected function _transformEmail(LdapObject $ldapUser)
    {
        $emailAttribute = $this->mappingRules[LdapObjectType::USER]['username'];
        $useEmailPrefixSuffix = $this->directorySettings->getUseEmailPrefixSuffix();

        if (!$ldapUser->has($emailAttribute) && $useEmailPrefixSuffix) {
            $emailPrefix = $this->directorySettings->getEmailPrefix();
            $prefix = $ldapUser->get($emailPrefix);
            $suffix = $this->directorySettings->getEmailSuffix();
            $ldapUser->set($emailAttribute, $prefix . $suffix);
        }
    }

    /**
     * Adds a uuid to the ldap object if not present and if a dn exists.
     * @param LdapObject $ldapObject ldap object
     *
     * @return LdapObject ldap object
     */
    protected function _transformId(LdapObject $ldapObject)
    {
        $idAttribute = $this->mappingRules[$ldapObject->getType()]['id'];
        if (!$ldapObject->has($idAttribute) && $ldapObject->has('dn')) {
            $ldapObject->add($idAttribute, UuidFactory::uuid($ldapObject->getDn()));
        }

        return $ldapObject;
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
                $groupEntry = GroupEntry::fromLdapObject($ldapGroup, $this->mappingRules);
                if (!empty($ldapGroup->getDn())) {
                    $this->groups[$ldapGroup->getDn()] = $groupEntry;
                } else {
                    $this->invalidGroups[] = $groupEntry;
                }
            }
        }
    }

    /**
     * Get invalid groups.
     * Invalid groups are groups that do not match the expected format and that will be ignored.
     * @return array
     */
    public function getInvalidGroups()
    {
        $invalidGroups = [];
        foreach ($this->groups as $group) {
            if ($group->hasErrors()) {
                $invalidGroups[] = $group;
            }
        }

        return array_merge($this->invalidGroups, $invalidGroups);
    }

    /**
     * get invalid users.
     * Invalid users are users that do not match the expected format and that will be ignored.
     * @return array
     */
    public function getInvalidUsers()
    {
        $invalidUsers = [];
        foreach ($this->users as $user) {
            if ($user->hasErrors()) {
                $invalidUsers[] = $user;
            }
        }

        return array_merge($this->invalidUsers, $invalidUsers);
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
                $userEntry = UserEntry::fromLdapObject($ldapUser, $this->mappingRules);
                if (!empty($ldapUser->getDn())) {
                    $this->users[$ldapUser->getDn()] = $userEntry;
                } else {
                    $this->invalidUsers[] = $userEntry;
                }
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
     * @param bool $validOnly whether to return only valid users (without validation errors)
     * @return array
     */
    public function getUsers(bool $validOnly = false)
    {
        if (!$validOnly) {
            return $this->users;
        }

        $users = [];
        foreach ($this->users as $key => $user) {
            if (!$user->hasErrors()) {
                $users[$key] = $user;
            }
        }

        return $users;
    }

    /**
     * Get groups.
     * @param bool $validOnly whether to return only valid groups (without validation errors)
     * @return array
     */
    public function getGroups(bool $validOnly = false)
    {
        if (!$validOnly) {
            return $this->groups;
        }

        $groups = [];
        foreach ($this->groups as $key => $group) {
            if (!$group->hasErrors()) {
                $groups[$key] = $group;
            }
        }

        return $groups;
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
        $g = clone $group;
        $g->level = $level;
        $flatTree[] = $g;

        foreach ($group['group']['users'] as $u) {
            $user = clone $u;
            $user->level = $level;
            $flatTree[] = $user;
        }

        foreach ($group['group']['groups'] as $g) {
            $this->_getFlattenedChildrenRecursive($g, $flatTree, ++$level);
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
                $flatTree[] = clone $entity;
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
     * Lookup for a group by its case insensitive name.
     * @param string $name group name
     *
     * @return array|null the corresponding GroupEntry
     */
    public function lookupGroupByGroupName(string $name)
    {
        foreach ($this->groups as $group) {
            if (strtolower($group['group']['name']) === strtolower($name)) {
                return $group;
            }
        }

        return null;
    }

    /**
     * Return the list of valid users in a simple array format.
     * @param bool $validOnly whether to return only valid users (without validation errors)
     * @return array array of users.
     */
    public function getUsersAsArray(bool $validOnly = false)
    {
        $results = [];
        $validUsers = $this->getUsers($validOnly);
        foreach ($validUsers as $user) {
            $results[] = $user->toArray();
        }

        return $results;
    }

    /**
     * Return the list of valid groups in a simple array format.
     * @param bool $validOnly whether to return only valid groups (without validation errors)
     * @return array array of groups.
     */
    public function getGroupsAsArray(bool $validOnly = false)
    {
        $results = [];
        $validGroups = $this->getGroups($validOnly);
        foreach ($validGroups as $group) {
            $results[] = $group->toArray();
        }

        return $results;
    }
}

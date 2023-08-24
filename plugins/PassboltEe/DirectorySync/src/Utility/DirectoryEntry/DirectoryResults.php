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

use App\Utility\UuidFactory;
use LdapRecord\Models\Collection;
use LdapRecord\Models\Entry;
use Passbolt\DirectorySync\Utility\DirectoryInterface;
use Passbolt\DirectorySync\Utility\DirectoryOrgSettings;

/**
 * Class DirectoryResults
 *
 * @package Passbolt\DirectorySync\Utility\DirectoryEntry
 */
class DirectoryResults
{
    /**
     * Raw ldap groups as returned by ldap directory.
     *
     * @var array|\LdapRecord\Models\Collection
     */
    private $ldapGroups;

    /**
     * Raw ldap users as returned by ldap directory.
     *
     * @var array|\LdapRecord\Models\Collection
     */
    private $ldapUsers;

    /**
     * mapping rules
     *
     * @var array
     */
    private $mappingRules;

    /**
     * Directory settings
     *
     * @var \Passbolt\DirectorySync\Utility\DirectoryOrgSettings
     */
    private $directorySettings;

    /**
     * Groups
     *
     * @var \Passbolt\DirectorySync\Utility\DirectoryEntry\GroupEntry[]
     */
    private $groups;

    /**
     * @var \Passbolt\DirectorySync\Utility\DirectoryEntry\UserCollection
     */
    public $userCollection;

    /**
     * Invalid users which will be ignored.
     *
     * @var array
     */
    private $invalidUsers;

    /**
     * Invalid groups which will be ignored.
     *
     * @var array
     */
    private $invalidGroups;

    /**
     * DirectoryResults constructor.
     *
     * @param array $mappingRules mapping rules
     * @param \Passbolt\DirectorySync\Utility\DirectoryOrgSettings $settings settings. If null, will retrieve settings through standard way.
     * @return void
     */
    public function __construct(array $mappingRules, ?DirectoryOrgSettings $settings = null)
    {
        $this->mappingRules = $mappingRules;
        $this->directorySettings = $settings ?? DirectoryOrgSettings::get();
        $this->ldapUsers = [];
        $this->ldapGroups = [];
        $this->userCollection = new UserCollection();
        $this->groups = [];
        $this->invalidUsers = [];
        $this->invalidGroups = [];
    }

    /**
     * Initialize results with results returned from ldap directory.
     *
     * @param \LdapRecord\Models\Collection $ldapUsers ldap users
     * @param \LdapRecord\Models\Collection $ldapGroups ldap groups
     * @return void
     * @throws \Exception
     */
    public function initializeWithLdapResults(Collection $ldapUsers, Collection $ldapGroups)
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
     *
     * @param array $userEntries user entries
     * @param array $groupEntries group entries
     * @return void
     */
    public function initializeWithEntries(array $userEntries = [], array $groupEntries = [])
    {
        $this->userCollection = new UserCollection();
        $this->groups = [];

        foreach ($userEntries as $userEntry) {
            if (is_array($userEntry)) {
                $userEntry = UserEntry::fromArray($userEntry);
            }
            $this->userCollection->add($userEntry->dn, $userEntry);
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
     *
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
     *
     * @return void
     */
    public function transformLdapGroups()
    {
        /** @var \LdapRecord\Models\Entry $ldapGroup */
        foreach ($this->ldapGroups as $ldapGroup) {
            $this->transformLdapGroup($ldapGroup);
        }
    }

    /**
     * Transform one ldap group.
     * Add a uuid if not present (based on cn)
     *
     * @param \LdapRecord\Models\Entry $ldapGroup ldap group
     * @return \LdapRecord\Models\Entry ldap object
     */
    public function transformLdapGroup(Entry $ldapGroup)
    {
        return $this->_transformId($ldapGroup);
    }

    /**
     * Transform one ldap user.
     * Add a uuid if not present (based on cn)
     * Build an email if defined in the configuration.
     *
     * @param \LdapRecord\Models\Entry $ldapUser ldap user
     * @return \LdapRecord\Models\Entry ldap object
     */
    public function transformLdapUser(Entry $ldapUser)
    {
        $ldapUser = $this->_transformId($ldapUser);

        return $this->_transformEmail($ldapUser);
    }

    /**
     * Transform ldap object email based on provided configuration.
     *
     * @param \LdapRecord\Models\Entry $ldapUser ldap user
     * @return \LdapRecord\Models\Entry
     * @throws \RuntimeException A mapping rule for username attribute could not be found for the directory type
     */
    protected function _transformEmail(Entry $ldapUser): Entry
    {
        /** @var string $directoryType */
        $directoryType = $ldapUser->getFirstAttribute('directoryType');
        $mappingRules = $this->mappingRules[$directoryType] ?? [];
        $emailAttribute = $mappingRules[DirectoryInterface::ENTRY_TYPE_USER]['username'] ?? null;
        if (!$emailAttribute) {
            throw new \RuntimeException(
                __('A mapping rule for username attribute could not be found for directory type: {0}', $directoryType)
            );
        }
        $useEmailPrefixSuffix = $this->directorySettings->getUseEmailPrefixSuffix();

        if (!$ldapUser->hasAttribute($emailAttribute) && $useEmailPrefixSuffix) {
            $emailPrefix = $this->directorySettings->getEmailPrefix();
            $prefix = $ldapUser->getFirstAttribute($emailPrefix);
            $suffix = $this->directorySettings->getEmailSuffix();
            $ldapUser->setAttribute($emailAttribute, $prefix . $suffix);
        }

        return $ldapUser;
    }

    /**
     * Adds a uuid to the ldap object if not present and if a dn exists.
     *
     * @param \LdapRecord\Models\Entry $ldapObject ldap object
     * @return \LdapRecord\Models\Entry ldap object
     * @throws \RuntimeException A mapping rule for ID attribute could not be found for the directory type
     */
    protected function _transformId(Entry $ldapObject): Entry
    {
        /** @var string $type */
        $type = $ldapObject->getFirstAttribute('objectType');
        /** @var string $directoryType */
        $directoryType = $ldapObject->getFirstAttribute('directoryType');
        $idAttribute = $this->mappingRules[$directoryType][$type]['id'] ?? null;
        if (!$idAttribute) {
            throw new \RuntimeException(
                __('A mapping rule for ID attribute could not be found for directory type: {0}', $directoryType)
            );
        }
        $dn = $ldapObject->getDn();
        if (!$ldapObject->hasAttribute($idAttribute) && $dn) {
            /** @psalm-suppress InvalidArgument it takes args, not an array */
            $ldapObject->setAttribute($idAttribute, UuidFactory::uuid($dn));
        } else {
            $ldapObject->setAttribute($idAttribute, $ldapObject->getConvertedGuid());
        }

        return $ldapObject;
    }

    /**
     * Populate groups list from Ldap results.
     *
     * @return void
     * @throws \Exception
     */
    private function _populateGroups()
    {
        /** @var \LdapRecord\Models\Entry $ldapGroup */
        foreach ($this->ldapGroups as $ldapGroup) {
            if (!isset($this->groups[$ldapGroup->getDn()])) {
                /** @var string $directoryType */
                $directoryType = $ldapGroup->getFirstAttribute('directoryType');
                $mappingRules = $this->mappingRules[$ldapGroup->getFirstAttribute('directoryType')] ?? null;
                if (!$mappingRules) {
                    throw new \RuntimeException(
                        __('Mapping rules could not be found for directory type: {0}', $directoryType)
                    );
                }
                $groupEntry = GroupEntry::fromLdapObject($ldapGroup, $mappingRules);
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
     *
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
     *
     * @return array
     */
    public function getInvalidUsers()
    {
        $invalidUsers = [];
        foreach ($this->userCollection->getAll() as $user) {
            if ($user->hasErrors()) {
                $invalidUsers[] = $user;
            }
        }

        return array_merge($this->invalidUsers, $invalidUsers);
    }

    /**
     * Populate users from Ldap results.
     *
     * @return void
     * @throws \RuntimeException If mapping rules could not be found for the directory type
     */
    private function _populateUsers(): void
    {
        foreach ($this->ldapUsers as $ldapUser) {
            if (!$this->userCollection->has($ldapUser->getDn())) {
                /** @var string $directoryType */
                $directoryType = $ldapUser->getFirstAttribute('directoryType');
                $mappingRules = $this->mappingRules[$directoryType] ?? null;
                if (!$mappingRules) {
                    throw new \RuntimeException(
                        __('Mapping rules could not be found for directory type: {0}', $directoryType)
                    );
                }
                $userEntry = UserEntry::fromLdapObject($ldapUser, $mappingRules);
                if (!empty($ldapUser->getDn())) {
                    $this->userCollection->add($ldapUser->getDn(), $userEntry);
                } else {
                    $this->invalidUsers[] = $userEntry;
                }
            }
        }
    }

    /**
     * Populate Group users details for all groups.
     *
     * @return void
     */
    private function _populateAllGroupsUsersDetails(): void
    {
        foreach ($this->groups as $key => $group) {
            $this->groups[$key] = $this->_populateGroupGroupsUserDetails($group);
        }
    }

    /**
     * Populate Group users details for the given group.
     *
     * @param \Passbolt\DirectorySync\Utility\DirectoryEntry\GroupEntry $group group
     * @return \Passbolt\DirectorySync\Utility\DirectoryEntry\GroupEntry returned group populated with groups users
     */
    private function _populateGroupGroupsUserDetails(GroupEntry $group): GroupEntry
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
     *
     * @param bool $validOnly whether to return only valid users (without validation errors)
     * @return array
     */
    public function getUsers(?bool $validOnly = false): array
    {
        if (!$validOnly) {
            return $this->userCollection->getAll();
        }

        $users = [];
        foreach ($this->userCollection->getAll() as $key => $user) {
            if (!$user->hasErrors()) {
                $users[$key] = $user;
            }
        }

        return $users;
    }

    /**
     * Get groups.
     *
     * @param bool $validOnly whether to return only valid groups (without validation errors)
     * @return array
     */
    public function getGroups(?bool $validOnly = false): array
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
     *
     * @return bool true or false
     */
    public function isEmpty(): bool
    {
        return $this->userCollection->isEmpty() && empty($this->groups);
    }

    /**
     * Check if a directory name is a group.
     *
     * @param string $memberDN the directory name
     * @return bool
     */
    public function isGroup(string $memberDN): bool
    {
        return isset($this->groups[$memberDN]);
    }

    /**
     * Check if a directory name is a group.
     *
     * @param string $memberDN the directory name
     * @return bool
     */
    public function isUser(string $memberDN): bool
    {
        return $this->userCollection->has($memberDN);
    }

    /**
     * Get recursively the group members for a given group and type.
     *
     * @param string $objectType type of object to be returned (Group or User).
     * @param \Passbolt\DirectorySync\Utility\DirectoryEntry\GroupEntry $group the group to work on.
     * @param array $membersList members list
     * @return array
     */
    private function _getGroupMembersRecursive(string $objectType, GroupEntry $group, array &$membersList): array
    {
        if ($objectType === DirectoryInterface::ENTRY_TYPE_GROUP) {
            $members = $group['group']['groups'];
        } else {
            $members = $group['group']['users'];
        }

        foreach ($members as $memberDn) {
            if ($objectType === DirectoryInterface::ENTRY_TYPE_GROUP) {
                $member = $this->groups[$memberDn];
            } else {
                $member = $this->userCollection->get($memberDn);
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
     * @return \Passbolt\DirectorySync\Utility\DirectoryEntry\DirectoryResults the list of members
     * @throws \Exception
     */
    public function getRecursivelyFromParentGroup(string $objectType, string $groupName): DirectoryResults
    {
        $group = $this->lookupGroupByGroupName($groupName);

        if (!$group) {
            throw new \Exception('Could not retrieve the group matching name ' . $groupName);
        }

        $groupsList = [];
        $members = $this->_getGroupMembersRecursive($objectType, $group, $groupsList);

        $directoryResults = new DirectoryResults($this->mappingRules);
        if ($objectType === DirectoryInterface::ENTRY_TYPE_USER) {
            $directoryResults->initializeWithEntries($members, []);
        } elseif ($objectType === DirectoryInterface::ENTRY_TYPE_GROUP) {
            // Retrieve the list of all the users that are part of the returned groups.
            $groupUsers = $this->_getUsersForGroups($members);
            $directoryResults->initializeWithEntries($groupUsers, $members);
        }

        return $directoryResults;
    }

    /**
     * Get all groups without parents.
     *
     * @return array list of groups.
     */
    private function _getRootGroups(): array
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
     *
     * @return array list of users.
     */
    private function _getRootUsers(): array
    {
        $allChildren = [];
        foreach ($this->groups as $group) {
            $allChildren = array_merge($allChildren, $group->group['users']);
        }

        $allChildren = array_map(function ($value) {
            return $this->userCollection->transformOffset($value);
        }, $allChildren);

        $rootUsers = array_diff(array_keys($this->userCollection->getAll()), $allChildren);
        $res = [];
        foreach ($rootUsers as $rootUser) {
            $res[$rootUser] = $this->userCollection->get($rootUser);
        }

        return $res;
    }

    /**
     * Build the recursive tree of children for a given group.
     *
     * @param \Passbolt\DirectorySync\Utility\DirectoryEntry\GroupEntry $group group
     * @return \Passbolt\DirectorySync\Utility\DirectoryEntry\GroupEntry $group GroupEntry populated with groups and users recursively.
     */
    private function _getChildrenRecursive(GroupEntry $group): GroupEntry
    {
        $groups = [];
        $users = [];
        $g = clone $group;

        foreach ($g['group']['groups'] as $key => $groupDn) {
            $groupObj = $this->groups[$groupDn];
            $groups[$groupDn] = $this->_getChildrenRecursive($groupObj);
        }
        foreach ($g['group']['users'] as $userDn) {
            $users[$userDn] = $this->userCollection->get($userDn);
        }

        $g->group['groups'] = $groups;
        $g->group['users'] = $users;

        return $g;
    }

    /**
     * Get nested tree of groups and users.
     *
     * @return array nested tree.
     */
    public function getTree(): array
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
     *
     * @param \Passbolt\DirectorySync\Utility\DirectoryEntry\GroupEntry $group the group
     * @param array $flatTree flat tree array
     * @param int $level the current level
     * @return void
     */
    private function _getFlattenedChildrenRecursive(GroupEntry &$group, array &$flatTree, int $level): void
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
     *
     * @return array the flattened tree.
     */
    public function getFlattenedTree(): array
    {
        $flatTree = [];
        $level = 0;
        $tree = $this->getTree();

        /** @var \Passbolt\DirectorySync\Utility\DirectoryEntry\UserEntry $entity */
        foreach ($tree as $entity) {
            if ($entity->isUser()) {
                $entity->level = $level;
                $flatTree[] = clone $entity;
            }
        }

        /** @var \Passbolt\DirectorySync\Utility\DirectoryEntry\GroupEntry $entity */
        foreach ($tree as $entity) {
            if ($entity->isGroup()) {
                $this->_getFlattenedChildrenRecursive($entity, $flatTree, $level);
            }
        }

        return $flatTree;
    }

    /**
     * Get users for a list of groups.
     *
     * @param array $groups a list of GroupEntry
     * @return array a list of User entries.
     */
    private function _getUsersForGroups(array $groups): array
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
     *
     * @param \Passbolt\DirectorySync\Utility\DirectoryEntry\GroupEntry $group group entry
     * @return array a list of UserEntry users.
     */
    private function _getUsersForGroup(GroupEntry $group): array
    {
        $users = [];
        foreach ($group['group']['users'] as $userDn) {
            $users[$userDn] = $this->userCollection->get($userDn);
        }

        return $users;
    }

    /**
     * Lookup for a group by its case insensitive name.
     *
     * @param string $name group name
     * @return \Passbolt\DirectorySync\Utility\DirectoryEntry\GroupEntry|null the corresponding GroupEntry
     */
    public function lookupGroupByGroupName(string $name): ?GroupEntry
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
     *
     * @param bool $validOnly whether to return only valid users (without validation errors)
     * @return array array of users.
     */
    public function getUsersAsArray(?bool $validOnly = false): array
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
     *
     * @param bool $validOnly whether to return only valid groups (without validation errors)
     * @return array array of groups.
     */
    public function getGroupsAsArray(?bool $validOnly = false): array
    {
        $results = [];
        $validGroups = $this->getGroups($validOnly);
        foreach ($validGroups as $group) {
            $results[] = $group->toArray();
        }

        return $results;
    }
}

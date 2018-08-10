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
 * @since         2.2.0
 */
namespace Passbolt\DirectorySync\Actions\Traits;

use App\Error\Exception\ValidationException;
use App\Model\Entity\Group;
use App\Model\Entity\Role;
use App\Utility\UserAccessControl;
use Cake\Core\Configure;
use App\Model\Table\GroupsUsersTable;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use LdapTools\Query\LdapQueryBuilder;
use Passbolt\DirectorySync\Model\Entity\DirectoryEntry;
use Passbolt\DirectorySync\Utility\ActionReport;
use Passbolt\DirectorySync\Utility\ErrorReport;
use Passbolt\DirectorySync\Utility\SyncAction;

trait GroupUsersSyncTrait {

    protected function findGroupWithGroupUsers(Group $existingGroup) {
        $group = $this->Groups
            ->find()
            ->where(['id' => $existingGroup->id])
            ->contain(['GroupsUsers'])
            ->first();
        return $group;
    }

    protected function findDirectoryRelationsByEntryId(string $entryId) {
        // TODO: directory relations should not be based on groupUserIds. It should be retrieved
        // TODO: separately based on group id.
        // Retrieve existing relations.
        $directoryRelations = $this->DirectoryRelations
            ->find()
            ->where(['parent_key' => $entryId])
            ->contain(['GroupDirectoryEntry', 'UserDirectoryEntry', 'GroupUser'])
            ->all()
            ->toArray();
        $directoryRelations = Hash::combine($directoryRelations, '{n}.id', '{n}');
        return $directoryRelations;
    }

    /**
     * @param array $groupUsersDn
     *
     * @return mixed
     */
    protected function findDirectoryEntriesForGroupUsers(array $groupUsersDn) {
        if (empty($groupUsersDn)) {
            return [];
        }

        $directoryGroupUserEntries = $this->DirectoryEntries
            ->find()
            ->where(['directory_name IN' => $groupUsersDn, 'foreign_model' => self::USERS])
            ->all()
            ->toArray();

        return $directoryGroupUserEntries;
    }

    protected function retrieveUsersToAdd(array $data, Group $group) {
        $group = $this->findGroupWithGroupUsers($group);
        $dbUserIdsInGroup = Hash::extract($group['groups_users'], '{n}.user_id');

        if (!empty($data['group']['users'])) {
            $directoryGroupUserEntries = $this->findDirectoryEntriesForGroupUsers($data['group']['users']);
            $directoryGroupUserEntriesByDn = Hash::combine($directoryGroupUserEntries, '{n}.directory_name', '{n}');
        }

        $toAdd = [];

        // Calculate users to add.
        // We add users that are in group data and not in directoryRelations.
        foreach($data['group']['users'] as $userDn) {
            if (!isset($directoryGroupUserEntriesByDn[$userDn])) {
                // If a DN was returned by the directory, but cannot be resolved with our entries, we notify the admin.
                // TODO: send ignore report.
                continue;
            }

            // The user should be added only if it doesn't already belong to the existing group_users in db.
            $userId = $directoryGroupUserEntriesByDn[$userDn]->foreign_key;
            if (!in_array($userId, $dbUserIdsInGroup)) {

                // If user already has a relation, send ignore report.
                $drExists = $this->DirectoryRelations->exists([
                    'parent_key' => $data['id'],
                    'child_key' => $directoryGroupUserEntriesByDn[$userDn]->id
                ]);
                if ($drExists) {
                    $this->addReport(new ActionReport(self::GROUPS_USERS, self::CREATE, self::IGNORE, $directoryGroupUserEntriesByDn[$userDn]));
                    continue;
                }

                // If group user and relation do not exist. Add it.
                $toAdd[] = $userId;
            }
        }

        return $toAdd;
    }

    protected function retrieveUsersToRemove(array $data) {
        $directoryRelations = $this->findDirectoryRelationsByEntryId($data['id']);

        // Retrieve directory entries matching the group users returned by the directory.
        $directoryGroupUserEntryIds = [];
        if (!empty($data['group']['users'])) {
            $directoryGroupUserEntries = $this->findDirectoryEntriesForGroupUsers($data['group']['users']);
            $directoryGroupUserEntryIds = Hash::extract($directoryGroupUserEntries, '{n}.id');
        }

        $toRemove = [];

        // Calculate groupUsers to remove.
        // We remove group users that are in directoryRelations but not in group data
        foreach($directoryRelations as $directoryRelation) {
            if (!in_array($directoryRelation['user_directory_entry']['id'], $directoryGroupUserEntryIds)) {
                $toRemove[] = $directoryRelation['id'];
            }
        }

        return $toRemove;
    }

    public function handleGroupUsersDeleted(DirectoryEntry $entry) {
        return $this->DirectoryRelations->deleteAll(['parent_key' => $entry->id]);
    }

    public function handleGroupCreateDefaultGroupUser(array $group) {
        // Add default group manager
        $group['groups_users'][] = [
            'user_id' => $this->defaultGroupAdmin->id,
            'is_admin' => true,
        ];

        return $group;
    }

    public function handleGroupUsersAfterGroupCreate($data, $group) {
        $toAdd = $this->retrieveUsersToAdd($data, $group);
        $this->addGroupUsers($group, $toAdd);
    }

    protected function addGroupUsers($group, $userIdsToAdd) {
        $GroupUsers = TableRegistry::getTableLocator()->get('GroupsUsers');
        $Groups = TableRegistry::getTableLocator()->get('Groups');

        foreach($userIdsToAdd as $userId) {
            try {
                $newGroupUsers = $GroupUsers->patchEntitiesWithChanges(
                    $group['groups_users'],
                    [['user_id' => $userId, 'is_admin' => false]],
                    $group->id,
                    ['allowedOperations' => ['add' => true]]
                );
                $group->groups_users = $newGroupUsers;
                $group->setDirty('groups_users', true);
            } catch (ValidationException $e) {
                $this->addReport(new ErrorReport(self::GROUPS_USERS, self::CREATE, $group));
            }

            $saveResult = $Groups->save($group);
            if (!$saveResult) {
                $this->addReport(new ActionReport(self::GROUPS_USERS, self::CREATE, self::IGNORE, $group));
                continue;
            }

            // Add relations for each new group.
            foreach($saveResult->groups_users as $groupUser) {
                if ($groupUser->user_id == $userId) {
                    $this->DirectoryRelations->createFromGroupUser($groupUser);
                    $this->addReport(new ActionReport(self::GROUPS_USERS, self::CREATE, self::SUCCESS, $groupUser));
                    break;
                }
            }
        }
    }

    protected function removeGroupUsers(Group $group, array $groupUserIdsToRemove)
    {
        $GroupUsers = TableRegistry::getTableLocator()->get('GroupsUsers');
        $Groups = TableRegistry::getTableLocator()->get('Groups');

        foreach($groupUserIdsToRemove as $groupUserId) {
            // If corresponding groupUser does not exist, cleanup the relation.
            if (!$this->GroupsUsers->exists(['id' => $groupUserId])){
                $directoryRelation = $this->DirectoryRelations->get($groupUserId);
                $this->DirectoryRelations->delete($directoryRelation);
                continue;
            }

            try {
                $newGroupUsers = $GroupUsers->patchEntitiesWithChanges(
                    $group->groups_users,
                    [['id' => $groupUserId, 'delete' => true]],
                    $group->id,
                    ['allowedOperations' => ['delete' => true]]
                );
                $group->groups_users = $newGroupUsers;
                $group->setDirty('groups_users', true);
            } catch (ValidationException $e) {
                $this->addReport(new ActionReport(self::GROUPS_USERS, self::CREATE, self::IGNORE, $group));
                continue;
            }

            $saveResult = $Groups->save($group);
            if (!$saveResult) {
                $this->addReport(new ErrorReport(self::GROUPS_USERS, self::DELETE, $group));
                continue;
            }

            // Delete relation
            $directoryRelation = $this->DirectoryRelations->get($groupUserId);
            $this->DirectoryRelations->delete($directoryRelation);

            // Send report.
            $this->addReport(new ActionReport(self::GROUPS_USERS, self::DELETE, self::SUCCESS, $group));
        }
    }

    public function syncGroupUsers(Group $group, array $toSync) {
        foreach($toSync as $groupUser) {
            $directoryRelation = $this->DirectoryRelations->createFromGroupUser($groupUser);
            $this->addReport(new ActionReport(self::GROUPS_USERS, self::CREATE, self::SUCCESS, $directoryRelation));
        }
    }

    public function retrieveUsersToSync(array $data, Group $group) {
        $toSync = [];

        // Look for users to sync.
        // Users that are in data and have a correspondence in GroupUsers
        $directoryGroupUserEntries = $this->findDirectoryEntriesForGroupUsers($data['group']['users']);
        $directoryGroupUserEntriesByDn = Hash::combine($directoryGroupUserEntries, '{n}.directory_name', '{n}');
        foreach($data['group']['users'] as $userDn) {
            if (isset($directoryGroupUserEntriesByDn[$userDn])) {
                $groupUser = $group->hasUser(['id' => $directoryGroupUserEntriesByDn[$userDn]->foreign_key]);
                if ($groupUser) {
                    // Check if there is a corresponding relation.
                    $relation = $this->DirectoryRelations->lookupByGroupUser($groupUser);
                    if (!empty($relation)) {
                        continue;
                    }

                    // Check if groupUser was created after.
                    if ($groupUser->created->gt($data['directory_modified'])) {
                        $toSync[] = $groupUser;
                    } else {
                        // Send ignore report.
                        $this->addReport(new ActionReport(self::GROUPS_USERS, self::CREATE, self::IGNORE, $directoryGroupUserEntriesByDn[$userDn]));
                    }
                }
            }
        }

        return $toSync;
    }

    public function handleGroupUsersEdit(array $data, DirectoryEntry $entry, Group $existingGroup) {
        if (!isset($entry) || $entry->status !== DirectoryEntry::STATUS_SUCCESS) {
            return;
        }

        $group = $this->findGroupWithGroupUsers($existingGroup);
        $toAdd = $this->retrieveUsersToAdd($data, $existingGroup);
        $toRemove = $this->retrieveUsersToRemove($data);
        $toSync = $this->retrieveUsersToSync($data, $group);

        if (!empty($toAdd)) {
            // Check if group has access to passwords already.
            // If not, we can freely add users.
            $this->addGroupUsers($group, $toAdd);

            // Else, we need to send notifications.
        }
        if (!empty($toRemove)) {
            $this->removeGroupUsers($group, $toRemove);
        }
        if (!empty($toSync)) {
            $this->syncGroupUsers($group, $toSync);
        }
    }
}
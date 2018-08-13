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
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\DirectorySync\Model\Entity\DirectoryEntry;
use Passbolt\DirectorySync\Utility\ActionReport;
use Passbolt\DirectorySync\Utility\Alias;
use Passbolt\DirectorySync\Utility\SyncError;

trait GroupUsersSyncTrait {

    public function handleGroupUsersAfterGroupCreate($data, $group) {
        if (!isset($group->groups_users)) {
            $group = $this->findGroupWithGroupUsers($group);
        }
        $toAdd = $this->retrieveUsersToAdd($data, $group);
        $this->addGroupUsers($group, $toAdd);
    }

    public function handleGroupUsersDeleted(DirectoryEntry $entry) {
        return $this->DirectoryRelations->deleteAll(['parent_key' => $entry->id]);
    }

    public function handleGroupUsersEdit(array $data, DirectoryEntry $entry, Group $group) {
        if (!isset($group->groups_users)) {
            $group = $this->findGroupWithGroupUsers($group);
        }
        $toAdd = $this->retrieveUsersToAdd($data, $group);
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

    protected function findGroupWithGroupUsers(Group $existingGroup) {
        $group = $this->Groups
            ->find()
            ->where(['id' => $existingGroup->id])
            ->contain(['GroupsUsers', 'GroupsUsers.Users'])
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
     * @param array $GroupsUsersDn
     *
     * @return mixed
     */
    protected function findDirectoryEntriesForGroupUsers(array $GroupsUsersDn) {
        if (empty($GroupsUsersDn)) {
            return [];
        }

        $directoryGroupUserEntries = $this->DirectoryEntries
            ->find()
            ->where(['directory_name IN' => $GroupsUsersDn, 'foreign_model' => Alias::MODEL_USERS])
            ->all()
            ->toArray();

        return $directoryGroupUserEntries;
    }

    protected function retrieveUsersToAdd(array $data, Group $group) {
        $toAdd = [];
        if (empty($data['group']['users'])) {
            return $toAdd;
        }

        $dbUserIdsInGroup = Hash::extract($group->groups_users, '{n}.user_id');
        $directoryGroupUserEntries = $this->findDirectoryEntriesForGroupUsers($data['group']['users']);
        $directoryGroupUserEntriesByDn = Hash::combine($directoryGroupUserEntries, '{n}.directory_name', '{n}');

        // Calculate users to add.
        // We add users that are in group data and not in directoryRelations.
        foreach($data['group']['users'] as $userDn) {
            if (!isset($directoryGroupUserEntriesByDn[$userDn])) {
                // If a DN was returned by the directory, but cannot be resolved with our entries, we notify the admin.
//                $this->addReportItem(new ActionReport(
//                    __('The user {0} could not be added to group {1} because there is no matching directory entry in passbolt.', $userDn, $group->name),
//                    Alias::MODEL_GROUPS_USERS, Alias::ACTION_CREATE, Alias::STATUS_IGNORE, $userDn));
                continue;
            }

            // The user should be added only if it doesn't already belong to the existing group_users in db.
            $userId = $directoryGroupUserEntriesByDn[$userDn]->foreign_key;
            if (in_array($userId, $dbUserIdsInGroup)) {
                continue;
            }

            // If user already has a relation, send ignore report.
            $drExists = $this->DirectoryRelations->exists([
                'parent_key' => $data['id'],
                'child_key' => $directoryGroupUserEntriesByDn[$userDn]->id
            ]);
            if ($drExists) {
                $this->addReportItem(new ActionReport(
                    __('The user {0} is already a member of the group {1}', $userDn, $group->name),
                    Alias::MODEL_GROUPS_USERS, Alias::ACTION_CREATE, Alias::STATUS_IGNORE, $directoryGroupUserEntriesByDn[$userDn]));
                continue;
            }

            // If group user and relation do not exist. Add it.
            $toAdd[] = $userId;
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

    protected function addGroupUsers($group, $userIdsToAdd) {
        $GroupsUsers = TableRegistry::getTableLocator()->get('GroupsUsers');
        $Groups = TableRegistry::getTableLocator()->get('Groups');

        foreach($userIdsToAdd as $userId) {
            try {
                $newGroupUsers = $GroupsUsers->patchEntitiesWithChanges(
                    $group['groups_users'],
                    [['user_id' => $userId, 'is_admin' => false]],
                    $group->id,
                    ['allowedOperations' => ['add' => true]]
                );
                $group->groups_users = $newGroupUsers;
                $group->setDirty('groups_users', true);
            } catch (ValidationException $exception) {
                $error = new SyncError($group, $exception);
                $this->addReportItem(new ActionReport(
                    __('The user {0} could not be added to the group {1} because of a validation error.', $userId, $group->name),
                    Alias::MODEL_GROUPS_USERS, Alias::ACTION_CREATE, Alias::STATUS_ERROR, $error));
            }

            $saveResult = $Groups->save($group);
            if (!$saveResult) {
                $this->addReportItem(new ActionReport(
                    __('The user {0} could not be added to the group {1} because of a validation error.', $userId, $group->name),
                     Alias::MODEL_GROUPS_USERS, Alias::ACTION_CREATE, Alias::STATUS_IGNORE, $group));
                continue;
            }

            // Add relations for each new group.
            foreach($saveResult->groups_users as $groupUser) {
                if ($groupUser->user_id == $userId) {
                    $this->DirectoryRelations->createFromGroupUser($groupUser);
                    $this->addReportItem(new ActionReport(
                        __('The user {0} was successfully added to the group {1}.', $userId, $group->name),
                        Alias::MODEL_GROUPS_USERS, Alias::ACTION_CREATE, Alias::STATUS_SUCCESS, $groupUser));
                    break;
                }
            }
        }
    }

    protected function removeGroupUsers(Group $group, array $groupUserIdsToRemove)
    {
        $GroupsUsers = TableRegistry::getTableLocator()->get('GroupsUsers');
        $Groups = TableRegistry::getTableLocator()->get('Groups');

        foreach($groupUserIdsToRemove as $groupUserId) {
            // If corresponding groupUser does not exist, cleanup the relation.
            if (!$GroupsUsers->exists(['id' => $groupUserId])){
                $directoryRelation = $this->DirectoryRelations->get($groupUserId);
                $this->DirectoryRelations->delete($directoryRelation);
                continue;
            }

            try {
                $newGroupUsers = $GroupsUsers->patchEntitiesWithChanges(
                    $group->groups_users,
                    [['id' => $groupUserId, 'delete' => true]],
                    $group->id,
                    ['allowedOperations' => ['delete' => true]]
                );
                $group->groups_users = $newGroupUsers;
                $group->setDirty('groups_users', true);
            } catch (ValidationException $exception) {
                $error = new SyncError($group, $exception);
                $this->addReportItem(new ActionReport(
                    __('The user {0} could not be removed from the group {1} because some validation issues.', $groupUserId, $group->name),
                    Alias::MODEL_GROUPS_USERS, Alias::ACTION_CREATE, Alias::STATUS_IGNORE, $error));
                continue;
            }

            $saveResult = $Groups->save($group);
            if (!$saveResult) {
                $this->addReportItem(new ActionReport(
                    __('The user {0} could not be removed from the group {1} because some permissions need to be transferred first.', $groupUserId, $group->name),
                     Alias::MODEL_GROUPS_USERS, Alias::ACTION_DELETE, Alias::STATUS_ERROR, $group));
                continue;
            }

            // Delete relation
            $directoryRelation = $this->DirectoryRelations->get($groupUserId);
            $this->DirectoryRelations->delete($directoryRelation);

            // Send report.
            $this->addReportItem(new ActionReport(
                __('The user {0} was successfully removed from the group {1}.', $groupUserId, $group->name),
                 Alias::MODEL_GROUPS_USERS, Alias::ACTION_DELETE, Alias::STATUS_SUCCESS, $group));
        }
    }

    public function syncGroupUsers(Group $group, array $toSync) {
        foreach($toSync as $groupUser) {
            $directoryRelation = $this->DirectoryRelations->createFromGroupUser($groupUser);
            $this->addReportItem(new ActionReport(
                __('The user {0} was successfully synced with the group {1}.', $groupUser->user->username, $group->name),
                Alias::MODEL_GROUPS_USERS, Alias::ACTION_CREATE, Alias::STATUS_SUCCESS, $directoryRelation));
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
                        $this->addReportItem(new ActionReport('TODO',
                            Alias::MODEL_GROUPS_USERS, Alias::ACTION_CREATE, Alias::STATUS_IGNORE, $directoryGroupUserEntriesByDn[$userDn]));
                    }
                }
            }
        }

        return $toSync;
    }

}
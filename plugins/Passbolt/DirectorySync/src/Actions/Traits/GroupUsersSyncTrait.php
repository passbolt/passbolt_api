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

    protected function findGroupWithGroupUsers($existingGroup) {
        $group = $this->Groups
            ->find()
            ->where(['id' => $existingGroup->id])
            ->contain(['GroupsUsers'])
            ->first();
        return $group;
    }

    protected function findDirectoryRelationsByEntryId($entryId) {
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

    protected function findDirectoryEntriesForGroupUsers($groupUsersDn) {
        $directoryGroupUserEntries = $this->DirectoryEntries
            ->find()
            ->where(['directory_name IN' => $groupUsersDn, 'foreign_model' => self::USERS])
            ->all()
            ->toArray();

        return $directoryGroupUserEntries;
    }

    protected function retrieveUsersToAdd($data, $existingGroup) {
        $group = $this->findGroupWithGroupUsers($existingGroup);
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
                $toAdd[] = $userId;
            }
        }

        return $toAdd;
    }

    protected function retrieveUsersToRemove($data) {
        $directoryRelations = $this->findDirectoryRelationsByEntryId($data['id']);

        // Retrieve directory entries matching the group users returned by the directory.
        $directoryGroupUserEntryIds = [];
        if (!empty($data['group']['users'])) {
            $directoryGroupUserEntries = $this->findDirectoryEntriesForGroupUsers($data['group']['users']);
            $directoryGroupUserEntryIds = Hash::extract($directoryGroupUserEntries, '{n}.id');
        }

        $toRemove = [];

        // Calculate groupUsers to remove.
        // We remove groupUsers that are in directoryRelations but not in group data
        foreach($directoryRelations as $directoryRelation) {
            if (!in_array($directoryRelation['group_directory_entry']['id'], $directoryGroupUserEntryIds)) {
                $toRemove[] = $directoryRelation['id'];
            }
        }

        return $toRemove;
    }

    public function handleGroupUsersDeleted($entry) {
        return $this->DirectoryRelations->deleteAll(['parent_key' => $entry->id]);
    }

    public function handleGroupCreateDefaultGroupUser($group) {
        // Add default group manager
        $group['groups_users'][] = [
            'user_id' => $this->defaultGroupAdmin->id,
            'is_admin' => true,
        ];

        return $group;
    }

    public function handleGroupUsersAfterGroupCreate($data, $group) {
        // TODO: remove default group manager from list of users if already present.

        //var_dump($data['group']['users']);
        $toAdd = $this->retrieveUsersToAdd($data, $group);
        $this->addGroupUsers($group, $toAdd);

//        foreach($group->groups_users as $group_user) {
//            $this->DirectoryRelations->createFromGroupUser($group_user);
//        }
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

    protected function removeGroupUsers($group, $userIdsToRemove)
    {
        $GroupUsers = TableRegistry::getTableLocator()->get('GroupsUsers');
        $Groups = TableRegistry::getTableLocator()->get('Groups');

        foreach($userIdsToRemove as $groupUserId) {
            try {
                $newGroupUsers = $GroupUsers->patchEntitiesWithChanges(
                    $group['groups_users'],
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

    public function handleGroupUsersEdit($data, $entry, $existingGroup) {
        if ($entry->status !== DirectoryEntry::STATUS_SUCCESS) {
            return;
        }

        $group = $this->findGroupWithGroupUsers($existingGroup);
        $toAdd = $this->retrieveUsersToAdd($data, $existingGroup);
        $toRemove = $this->retrieveUsersToRemove($data);

        // First we add, then we remove.
        if (!empty($toAdd)) {
            // Check if group has access to passwords already.
            // If not, we can freely add users.
            $this->addGroupUsers($group, $toAdd);

            // Else, we need to send notifications.
        }

        if (!empty($toRemove)) {
            $this->removeGroupUsers($group, $toRemove);
        }
    }

}
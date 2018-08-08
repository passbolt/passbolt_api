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

trait GroupUsersSyncTrait {

    protected function findGroupUsersForGroup($existingGroup) {
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
        $group = $this->findGroupUsersForGroup($existingGroup);
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

    public function handleGroupUsersEdit($data, $entry, $existingGroup) {
        if ($entry->status !== DirectoryEntry::STATUS_SUCCESS) {
            return;
        }

        $GroupUsers = TableRegistry::getTableLocator()->get('GroupsUsers');
        $Groups = TableRegistry::getTableLocator()->get('Groups');

        $group = $this->findGroupUsersForGroup($existingGroup);
        $groupUsers = $group['groups_users'];


        // TODO: cleanup of directoryRelations.

        $toAdd = $this->retrieveUsersToAdd($data, $existingGroup);
        $toRemove = $this->retrieveUsersToRemove($data);

        // Important: First we add, then we remove.

        // Check if group has access to passwords already.
        // If not, we can freely add users.

        // If group has access to passwords, we simply send notifications to the admin.
        // TODO: how not to send the email notification several times?

        // Delete members one at a time.
        foreach($toRemove as $groupUserId) {
            try {
                $newGroupUsers = $GroupUsers->patchEntitiesWithChanges(
                    $groupUsers,
                    [['id' => $groupUserId, 'delete' => true]],
                    $existingGroup->id,
                    ['allowedOperations' => ['delete' => true]]
                );
                $existingGroup->groups_users = $newGroupUsers;
                $existingGroup->setDirty('groups_users', true);
            } catch (ValidationException $e) {
                var_dump('validation error');
            }

            $saveResult = $Groups->save($existingGroup);
            if (!$saveResult) {
                var_dump('Could not save');
                // TODO: check different  possible errors and customize error message.
                $this->addReport(new ErrorReport(self::GROUPS_USERS, self::DELETE, $existingGroup));
                continue;
            }

            // Delete relation
            $directoryRelation = $this->DirectoryRelations->get($groupUserId);
            $this->DirectoryRelations->delete($directoryRelation);

            // Send report.
            $this->addReport(new ActionReport(self::GROUPS_USERS, self::DELETE, self::SUCCESS, $existingGroup));
        }

    }

}
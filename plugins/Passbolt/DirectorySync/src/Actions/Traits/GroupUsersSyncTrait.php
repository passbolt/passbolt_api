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
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\DirectorySync\Actions\Reports\ActionReport;
use Passbolt\DirectorySync\Model\Entity\DirectoryEntry;
use Passbolt\DirectorySync\Utility\Alias;
use Passbolt\DirectorySync\Utility\SyncError;

trait GroupUsersSyncTrait
{

    /**
     * Handle groupUsers once a group is created.
     * @param array $data directory data
     * @param Group $group group
     * @return void
     */
    public function handleGroupUsersAfterGroupCreate(array $data, Group $group)
    {
        if (!isset($group->groups_users)) {
            $group = $this->findGroupWithGroupUsers($group);
        }
        $toAdd = $this->retrieveUsersToAdd($data, $group);
        $this->addGroupUsers($group, $toAdd);
    }

    /**
     * Handle groupUsers that are deleted.
     * @param DirectoryEntry $entry entries to be deleted.
     *
     * @return mixed
     */
    public function handleGroupUsersDeleted(DirectoryEntry $entry)
    {
        return $this->DirectoryRelations->deleteAll(['parent_key' => $entry->id]);
    }

    /**
     * Handle groupUsers when they are edited for an existing group.
     * @param array $data directory data
     * @param DirectoryEntry $entry directory entry
     * @param Group $group group to edit
     * @return void
     */
    public function handleGroupUsersEdit(array $data, DirectoryEntry $entry, Group $group)
    {
        if (!isset($group->groups_users)) {
            $group = $this->findGroupWithGroupUsers($group);
        }
        $toAdd = $this->retrieveUsersToAdd($data, $group);
        $toRemove = $this->retrieveUsersToRemove($data);
        $toSync = $this->retrieveUsersToSync($data, $group);

        $Resources = TableRegistry::getTableLocator()->get('Resources');

        if (!empty($toAdd)) {
            // Check if group has access to passwords already.
            $accessibleResources = $Resources->findAllByGroupAccess($group->id)->count();
            if ($accessibleResources === 0) {
                // If no password is shared with this group already, we can proceed.
                $this->addGroupUsers($group, $toAdd);
            } else {
                // Else, we need to send notifications.
                $this->requestAddGroupUsers($group, $toAdd);
            }
        }
        if (!empty($toRemove)) {
            $this->removeGroupUsers($group, $toRemove);
        }
        if (!empty($toSync)) {
            $this->syncGroupUsers($group, $toSync);
        }
    }

    /**
     * Request to add users into the group.
     * @param Group $group groups
     * @param array $userIdsToAdd list of user ids to add
     * @return void
     */
    public function requestAddGroupUsers(Group $group, array $userIdsToAdd)
    {
        foreach ($userIdsToAdd as $userId) {
            $u = $this->Users->get($userId);
            $this->addReportItem(new ActionReport(
                __('A request to add user {0} in group {1} was sent to the group manager.', $u->username, $group->name),
                Alias::MODEL_GROUPS_USERS,
                Alias::ACTION_CREATE,
                Alias::STATUS_SUCCESS,
                $u
            ));
        }

        // Send notification if not in dry-run mode.
        if (!$this->isDryRun()) {
            $accessControl = new UserAccessControl(Role::ADMIN, $this->defaultAdmin->id);
            $groupUsers = [];
            // Build group_users entity for the call.
            foreach ($userIdsToAdd as $userId) {
                $groupUsers[] = $this->GroupsUsers->buildEntity(['group_id' => $group->id, 'user_id' => $userId]);
            }
            $eventData = ['groupUsers' => $groupUsers, 'group' => $group, 'requester' => $accessControl];
            $event = new Event('Model.Groups.requestGroupUsers.success', $this, $eventData);
            $this->getEventManager()->dispatch($event);
        }
    }

    /**
     * Retrieve a group with its groupUsers.
     * @param Group $existingGroup existing group
     *
     * @return mixed
     */
    protected function findGroupWithGroupUsers(Group $existingGroup)
    {
        $group = $this->Groups
            ->find()
            ->where(['id' => $existingGroup->id])
            ->contain(['GroupsUsers', 'GroupsUsers.Users'])
            ->first();

        return $group;
    }

    /**
     * Check if a DN belongs to the groups returned by the directory.
     * @param string $dn DN
     *
     * @return bool
     */
    protected function groupExistsInDirectory(string $dn)
    {
        $groupDns = Hash::extract($this->directoryData, '{n}.directory_name');

        return in_array($dn, $groupDns);
    }

    /**
     * Find the directory relations corresponding to an entry.
     * @param string $entryId entry id
     *
     * @return array
     */
    protected function findDirectoryRelationsByEntryId(string $entryId)
    {
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
     * Find directory entries corresponding to a groupUser
     * @param array $GroupsUsersDn user directory name
     *
     * @return mixed
     */
    protected function findDirectoryEntriesForGroupUsers(array $GroupsUsersDn)
    {
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

    /**
     * Retrieve the list of users to add.
     * @param array $data directory data
     * @param Group $group group
     *
     * @return array
     */
    protected function retrieveUsersToAdd(array $data, Group $group)
    {
        $toAdd = [];
        if (empty($data['group']['users'])) {
            return $toAdd;
        }

        $dbUserIdsInGroup = Hash::extract($group->groups_users, '{n}.user_id');
        $directoryGroupUserEntries = $this->findDirectoryEntriesForGroupUsers($data['group']['users']);
        $directoryGroupUserEntriesByDn = Hash::combine($directoryGroupUserEntries, '{n}.directory_name', '{n}');

        // Calculate users to add.
        // We add users that are in group data and not in directoryRelations.
        foreach ($data['group']['users'] as $userDn) {
            // If the group member is a group, we do not process.
            if ($this->groupExistsInDirectory($userDn)) {
                continue;
            }

            if (!isset($directoryGroupUserEntriesByDn[$userDn])) {
                // If a DN was returned by the directory, but cannot be resolved with our entries, we notify the admin.
                $this->addReportItem(new ActionReport(
                    __('The user {0} could not be added to group {1} because there is no matching directory entry in passbolt.', $userDn, $group->name),
                    Alias::MODEL_GROUPS_USERS,
                    Alias::ACTION_CREATE,
                    Alias::STATUS_IGNORE,
                    [$userDn]
                ));
                continue;
            }

            // The user should be added only if it doesn't already belong to the existing group_users in db.
            $userId = $directoryGroupUserEntriesByDn[$userDn]->foreign_key;
            if ($userId === null) {
                // The user has been deleted and the group user entry now points to nothing.
                continue;
            } elseif (in_array($userId, $dbUserIdsInGroup)) {
                // Do nothing. It is taken care of by syncGroupUsers.
                continue;
            }

            // If user already has a relation, send ignore report.
            $drExists = $this->DirectoryRelations->exists([
                'parent_key' => $data['id'],
                'child_key' => $directoryGroupUserEntriesByDn[$userDn]->id
            ]);
            if ($drExists) {
                $u = $this->Users->get($userId);
                $this->addReportItem(new ActionReport(
                    __('The user {0} could not be added to the group {1} because the membership has been removed in passbolt', $u->username, $group->name),
                    Alias::MODEL_GROUPS_USERS,
                    Alias::ACTION_CREATE,
                    Alias::STATUS_IGNORE,
                    $directoryGroupUserEntriesByDn[$userDn]
                ));
                continue;
            }

            // If group user and relation do not exist. Add it.
            $toAdd[] = $userId;
        }

        return $toAdd;
    }

    /**
     * Retrieve the list of users to remove.
     * @param array $data data
     *
     * @return array
     */
    protected function retrieveUsersToRemove(array $data)
    {
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
        foreach ($directoryRelations as $directoryRelation) {
            if (!in_array($directoryRelation['user_directory_entry']['id'], $directoryGroupUserEntryIds)) {
                $toRemove[] = $directoryRelation['id'];
            }
        }

        return $toRemove;
    }

    /**
     * Add Group users
     * @param Group $group group where to ad the group users.
     * @param array $userIdsToAdd list of user ids to be added.
     * @return void
     */
    protected function addGroupUsers(Group $group, array $userIdsToAdd)
    {
        foreach ($userIdsToAdd as $userId) {
            $u = $this->Users->get($userId);
            try {
                $newGroupUsers = $this->GroupsUsers->patchEntitiesWithChanges(
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
                    __('The user {0} could not be added to the group {1} because of a validation error.', $u->username, $group->name),
                    Alias::MODEL_GROUPS_USERS,
                    Alias::ACTION_CREATE,
                    Alias::STATUS_ERROR,
                    $error
                ));
            } catch (\Exception $exception) {
                $error = new SyncError($group, $exception);
                $this->addReportItem(new ActionReport(
                    __('The user {0} could not be added to the group {1} because of an internal error.', $u->username, $group->name),
                    Alias::MODEL_GROUPS_USERS,
                    Alias::ACTION_CREATE,
                    Alias::STATUS_ERROR,
                    $error
                ));
            }

            $saveResult = $this->Groups->save($group);
            if (!$saveResult) {
                $errors = $group->getErrors();
                $isNotActive = isset($errors['groups_users'][1]['user_id']['user_is_active']);
                $isDeleted = isset($errors['groups_users'][1]['user_id']['user_is_not_soft_deleted']);
                if ($isNotActive && $isDeleted || $isDeleted) {
                    $msg = __('The user {0} could not be added to group {1} because it is deleted.', $u->username, $group->name);
                } elseif ($isNotActive) {
                    $msg = __('The user {0} could not be added to group {1} because it is not active yet.', $u->username, $group->name);
                } else {
                    $msg = __('The user {0} could not be added to the group {1} because some validation issues.', $u->username, $group->name);
                }
                $this->addReportItem(new ActionReport(
                    $msg,
                    Alias::MODEL_GROUPS_USERS,
                    Alias::ACTION_CREATE,
                    Alias::STATUS_IGNORE,
                    $group
                ));
                continue;
            }

            // Add relations for each new group.
            foreach ($saveResult->groups_users as $groupUser) {
                if ($groupUser->user_id == $userId) {
                    $this->DirectoryRelations->createFromGroupUser($groupUser);
                    $this->addReportItem(new ActionReport(
                        __('The user {0} was successfully added to the group {1}.', $u->username, $group->name),
                        Alias::MODEL_GROUPS_USERS,
                        Alias::ACTION_CREATE,
                        Alias::STATUS_SUCCESS,
                        $group
                    ));
                    break;
                }
            }
        }
    }

    /**
     * Remove groupUsers for a given group.
     * @param Group $group group where to remove the groupUsers
     * @param array $groupUserIdsToRemove list of groupUsers Ids
     * @return void
     */
    protected function removeGroupUsers(Group $group, array $groupUserIdsToRemove)
    {
        foreach ($groupUserIdsToRemove as $groupUserId) {
            // If corresponding groupUser does not exist, cleanup the relation.
            if (!$this->GroupsUsers->exists(['id' => $groupUserId])) {
                $directoryRelation = $this->DirectoryRelations->get($groupUserId);
                $this->DirectoryRelations->delete($directoryRelation);
                continue;
            }

            try {
                $newGroupUsers = $this->GroupsUsers->patchEntitiesWithChanges(
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
                    Alias::MODEL_GROUPS_USERS,
                    Alias::ACTION_CREATE,
                    Alias::STATUS_IGNORE,
                    $error
                ));
                continue;
            }

            $saveResult = $this->Groups->save($group);
            if (!$saveResult) {
                $gp = $this->GroupsUsers->findById($groupUserId)->contain(['Users', 'Groups'])->first();
                $errors = $group->getErrors();
                if (isset($errors['groups_users']['at_least_one_admin'])) {
                    $msg = __('The user {0} could not be removed from the group {1} because it is the only group manager.', $gp->user->username, $group->name);
                } else {
                    $msg = __('The user {0} could not be removed from the group {1} because some validation issues.', $gp->user->username, $group->name);
                }
                $error = new SyncError($group, new \Exception($msg));
                $this->addReportItem(new ActionReport($msg, Alias::MODEL_GROUPS_USERS, Alias::ACTION_DELETE, Alias::STATUS_ERROR, $error));
                continue;
            }

            // Delete relation
            $directoryRelation = $this->DirectoryRelations->get($groupUserId);
            $this->DirectoryRelations->delete($directoryRelation);

            // Send report.
            $this->addReportItem(new ActionReport(
                __('The user {0} was successfully removed from the group {1}.', $groupUserId, $group->name),
                Alias::MODEL_GROUPS_USERS,
                Alias::ACTION_DELETE,
                Alias::STATUS_SUCCESS,
                $group
            ));
        }
    }

    /**
     * Sync Group Users.
     * @param Group $group group
     * @param array $toSync list of groupuserIds to be synced.
     * @return void
     */
    public function syncGroupUsers(Group $group, array $toSync)
    {
        foreach ($toSync as $groupUser) {
            $directoryRelation = $this->DirectoryRelations->createFromGroupUser($groupUser);
            $this->addReportItem(new ActionReport(
                __('The user {0} was successfully synced with the group {1}.', $groupUser->user->username, $group->name),
                Alias::MODEL_GROUPS_USERS,
                Alias::ACTION_CREATE,
                Alias::STATUS_SYNC,
                $directoryRelation
            ));
        }
    }

    /**
     * Retrieve list of users to sync.
     * @param array $data directory data
     * @param Group $group group
     *
     * @return array
     */
    public function retrieveUsersToSync(array $data, Group $group)
    {
        $toSync = [];

        // Look for users to sync.
        // Users that are in data and have a correspondence in GroupUsers
        $directoryGroupUserEntries = $this->findDirectoryEntriesForGroupUsers($data['group']['users']);
        $directoryGroupUserEntriesByDn = Hash::combine($directoryGroupUserEntries, '{n}.directory_name', '{n}');
        foreach ($data['group']['users'] as $userDn) {
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
                        $this->addReportItem(new ActionReport(
                            __('The user {0} was not synced with existing membership for group {1} because the membership was created before.', $groupUser->user->username, $group->name),
                            Alias::MODEL_GROUPS_USERS,
                            Alias::ACTION_CREATE,
                            Alias::STATUS_IGNORE,
                            $directoryGroupUserEntriesByDn[$userDn]
                        ));
                    }
                }
            }
        }

        return $toSync;
    }
}

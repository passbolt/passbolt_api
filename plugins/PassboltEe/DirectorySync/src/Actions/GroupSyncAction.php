<?php
declare(strict_types=1);

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
namespace Passbolt\DirectorySync\Actions;

use App\Error\Exception\ValidationException;
use App\Model\Entity\Group;
use App\Model\Entity\GroupsUser;
use App\Model\Entity\Role;
use App\Model\Table\GroupsTable;
use App\Model\Table\GroupsUsersTable;
use App\Service\Groups\GroupsUpdateService;
use App\Service\GroupsUsers\GroupsUsersAddService;
use App\Service\GroupsUsers\GroupsUsersDeleteService;
use App\Service\Resources\ResourcesExpireResourcesFallbackServiceService;
use App\Utility\UserAccessControl;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Event\EventDispatcherTrait;
use Cake\ORM\Entity;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\DirectorySync\Actions\Reports\ActionReport;
use Passbolt\DirectorySync\Model\Entity\DirectoryEntry;
use Passbolt\DirectorySync\Utility\Alias;
use Passbolt\DirectorySync\Utility\DirectoryEntry\UserCollection;
use Passbolt\DirectorySync\Utility\SyncError;

class GroupSyncAction extends SyncAction
{
    use EventDispatcherTrait;

    private GroupsTable $Groups;
    private GroupsUsersTable $GroupsUsers;

    /**
     * @var array|mixed
     */
    private $defaultGroupAdmin;

    /**
     * @inheritDoc
     */
    protected function getEntityType(): string
    {
        return Alias::MODEL_GROUPS;
    }

    /**
     * @inheritDoc
     */
    protected function getEntityName(Entity $entity): string
    {
        return $entity->get('name');
    }

    /**
     * @inheritDoc
     */
    protected function getNameFromData(array $data): string
    {
        return $data['group']['name'] ?? 'undefined';
    }

    /**
     * @inheritDoc
     */
    protected function getEntityFromData(array $data): ?Entity
    {
        return $this->getGroupFromData($data['group']['name'] ?? '');
    }

    /**
     * BeforeExecute.
     *
     * @return void
     */
    protected function beforeExecute(): void
    {
        parent::beforeExecute();
        $this->Groups = $this->getTable();
        /** @phpstan-ignore-next-line */
        $this->GroupsUsers = $this->fetchTable('GroupsUsers');
        $this->defaultGroupAdmin = $this->getDefaultGroupAdmin();
        if (empty($this->defaultGroupAdmin)) {
            $this->defaultGroupAdmin = $this->defaultAdmin;
        }
    }

    /**
     * Get group from data.
     *
     * @param string $groupName group name
     * @return ?\App\Model\Entity\Group
     */
    private function getGroupFromData(string $groupName): ?Group
    {
        // If not group already associated, find if there is a corresponding group in the database.
        /** @var ?\App\Model\Entity\Group $existingGroup */
        $existingGroup = $this->Groups->find()
            ->select(['id', 'name', 'deleted', 'created', 'modified'])
            ->where(['name' => $groupName])
            ->order(['Groups.modified' => 'DESC'])
            ->first();

        return $existingGroup;
    }

    /**
     * Get default group administrator
     *
     * @return array|\Cake\Datasource\EntityInterface|mixed|null
     */
    private function getDefaultGroupAdmin()
    {
        $groupAdmin = $this->directoryOrgSettings->getDefaultGroupAdminUser();
        if (!empty($groupAdmin)) {
            // Get groupAdmin from database.
            $groupAdmin = $this->Users->find()
                ->where([
                    'Users.deleted' => false,
                    'Users.active' => true,
                    'Users.username' => $groupAdmin,
                ])
                ->first();
            if (!empty($groupAdmin)) {
                return $groupAdmin;
            }
        }

        // If can't find corresponding config user, return first admin.
        return $this->Users->findFirstAdmin();
    }

    /**
     * @inheritDoc
     */
    protected function createEntity(array $data, DirectoryEntry $entry): Entity
    {
        $accessControl = new UserAccessControl(Role::ADMIN, $this->defaultAdmin->get('id'));
        // Define default admin for group.
        $data['group']['groups_users'][] = [
            'user_id' => $this->defaultGroupAdmin->get('id'),
            'is_admin' => true,
        ];
        // Create.
        $entity = $this->Groups->create($data['group'], $accessControl);
        $this->DirectoryEntries->updateForeignKey($entry, $entity->id);

        return $entity;
    }

    /**
     * @inheritDoc
     */
    protected function handleSuccessfulDelete(DirectoryEntry $entry): void
    {
        parent::handleSuccessfulDelete($entry);
        $this->handleGroupUsersDeleted($entry);
    }

    /**
     * Handle groupUsers that are deleted.
     *
     * @param \Passbolt\DirectorySync\Model\Entity\DirectoryEntry $entry entries to be deleted.
     * @return void
     */
    private function handleGroupUsersDeleted(DirectoryEntry $entry): void
    {
        $this->DirectoryRelations->deleteAll(['parent_key' => $entry->id]);
    }

    /**
     * @inheritDoc
     */
    protected function handleAddNew(array $data, ?DirectoryEntry $entry = null): ?Entity
    {
        $group = parent::handleAddNew($data, $entry);
        if ($group instanceof Group) {
            $this->handleGroupUsersAfterGroupCreate($data, $group);
        }

        return $group;
    }

    /**
     * @inheritDoc
     */
    protected function handleAddDeleted(array $data, DirectoryEntry $entry, Entity $existingEntity): ?Entity
    {
        $group = parent::handleAddDeleted($data, $entry, $existingEntity);
        if ($group instanceof Group) {
            $this->handleGroupUsersAfterGroupCreate($data, $group);
        }

        return $group;
    }

    /**
     * @param array $data data
     * @param \App\Model\Entity\Group $existingEntity existing entity
     * @return void
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    protected function handleUpdate(array $data, Entity $existingEntity): void
    {
        //We need to process the group users and rename it if needed.
        $this->handleUpdateGroup($data, $existingEntity);
        $this->handleGroupUsersEdit($data, $existingEntity);
    }

    /**
     * Handle update group.
     *
     * @param array $data data
     * @param \App\Model\Entity\Group $existingGroup Group
     * @return void
     */
    private function handleUpdateGroup(array $data, Group $existingGroup): void
    {
        $groupName = $this->getNameFromData($data);
        if ($groupName === 'undefined' || strtolower($groupName) === strtolower($existingGroup->name)) {
            return;
        }
        $this->updateGroup($existingGroup, $data);
    }

    /**
     * Handle groupUsers when they are edited for an existing group.
     *
     * @param array $data directory data
     * @param \App\Model\Entity\Group $group group to edit
     * @return void
     */
    private function handleGroupUsersEdit(array $data, Entity $group): void
    {
        if (!isset($group->groups_users)) {
            $group = $this->findGroupWithGroupUsers($group);
        }
        $toAdd = $this->retrieveUsersToAdd($data, $group);
        $toRemove = $this->retrieveUsersToRemove($data);
        $toSync = $this->retrieveUsersToSync($data, $group);

        if (!empty($toAdd)) {
            // Check if group has access to passwords already.
            /** @var \App\Model\Table\ResourcesTable $Resources */
            $Resources = TableRegistry::getTableLocator()->get('Resources');
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
     * Retrieve a group with its groupUsers.
     *
     * @param \App\Model\Entity\Group $existingGroup existing group
     * @return mixed
     */
    private function findGroupWithGroupUsers(Group $existingGroup)
    {
        $group = $this->Groups
            ->find()
            ->where(['id' => $existingGroup->id])
            ->contain(['GroupsUsers', 'GroupsUsers.Users'])
            ->first();

        return $group;
    }

    /**
     * Retrieve the list of users to add.
     *
     * @param array $data directory data
     * @param \App\Model\Entity\Group $group group
     * @return array
     */
    private function retrieveUsersToAdd(array $data, Group $group): array
    {
        $toAdd = [];
        if (empty($data['group']['users'])) {
            return $toAdd;
        }

        $dbUserIdsInGroup = Hash::extract($group->groups_users, '{n}.user_id');
        $directoryGroupUserEntries = $this->findDirectoryEntriesForGroupUsers($data['group']['users']);

        // Calculate users to add.
        // We add users that are in group data and not in directoryRelations.
        foreach ($data['group']['users'] as $userDn) {
            // If the group member is a group, we do not process.
            if ($this->groupExistsInDirectory($userDn)) {
                continue;
            }

            $directoryGroupUserEntry = $this->lookupDnInDirectoryEntries($userDn, $directoryGroupUserEntries);
            if ($directoryGroupUserEntry === null) {
                // If a DN was returned by the directory, but cannot be resolved with our entries, we notify the admin.
                $this->addReportItem(new ActionReport(
                    __('The user {0} could not be added to group {1} because there is no matching directory entry in passbolt.', $userDn, $group->name),//phpcs:ignore
                    Alias::MODEL_GROUPS_USERS,
                    Alias::ACTION_CREATE,
                    Alias::STATUS_IGNORE,
                    [$userDn]
                ));
                continue;
            }

            // The user should be added only if it doesn't already belong to the existing group_users in db.
            $userId = $directoryGroupUserEntry->foreign_key;
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
                'child_key' => $directoryGroupUserEntry->id,
            ]);
            if ($drExists) {
                $u = $this->Users->get($userId);
                $this->addReportItem(new ActionReport(
                    __('The user {0} could not be added to the group {1} because the membership has been removed in passbolt', $u->username, $group->name),//phpcs:ignore
                    Alias::MODEL_GROUPS_USERS,
                    Alias::ACTION_CREATE,
                    Alias::STATUS_IGNORE,
                    $directoryGroupUserEntry
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
     *
     * @param array $data data
     * @return array
     */
    private function retrieveUsersToRemove(array $data): array
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
            if (
                !isset($directoryRelation['user_directory_entry']['id']) ||
                !in_array($directoryRelation['user_directory_entry']['id'], $directoryGroupUserEntryIds)
            ) {
                $toRemove[] = $directoryRelation['id'];
            }
        }

        return $toRemove;
    }

    /**
     * Retrieve list of users to sync.
     *
     * @param array $data directory data
     * @param \App\Model\Entity\Group $group group
     * @return array
     */
    private function retrieveUsersToSync(array $data, Group $group): array
    {
        $toSync = [];

        // Look for users to sync.
        // Users that are in data and have a correspondence in GroupUsers
        $directoryGroupUserEntries = $this->findDirectoryEntriesForGroupUsers($data['group']['users']);

        foreach ($data['group']['users'] as $userDn) {
            $directoryGroupUserEntry = $this->lookupDnInDirectoryEntries($userDn, $directoryGroupUserEntries);
            if ($directoryGroupUserEntry === null) {
                continue;
            }

            $groupUser = $group->hasUser(['id' => $directoryGroupUserEntry->foreign_key]);
            if ($groupUser instanceof GroupsUser) {
                // Check if there is a corresponding relation.
                $relation = $this->DirectoryRelations->lookupByGroupUser($groupUser);
                if (!empty($relation)) {
                    continue;
                }

                // Check if groupUser was created after.
                if ($groupUser->created->greaterThan($data['directory_modified'])) {
                    $toSync[] = $groupUser;
                } else {
                    // Send ignore report.
                    $this->addReportItem(new ActionReport(
                        __('The user {0} was not synced with existing membership for group {1} because the membership was created before.', $groupUser->user->username, $group->name),//phpcs:ignore
                        Alias::MODEL_GROUPS_USERS,
                        Alias::ACTION_CREATE,
                        Alias::STATUS_IGNORE,
                        $directoryGroupUserEntry
                    ));
                }
            }
        }

        return $toSync;
    }

    /**
     * Add Group users
     *
     * @param \App\Model\Entity\Group $group group where to ad the group users.
     * @param array $userIdsToAdd list of user ids to be added.
     * @return void
     */
    private function addGroupUsers(Group $group, array $userIdsToAdd): void
    {
        $uac = new UserAccessControl(Role::ADMIN, $this->defaultAdmin->get('id'));
        $groupsUsersAddService = new GroupsUsersAddService();

        foreach ($userIdsToAdd as $userId) {
            $user = $this->Users->get($userId);
            $groupUserData = [
                'group_id' => $group->id,
                'user_id' => $userId,
                'is_admin' => false,
            ];

            try {
                /** @var \App\Model\Entity\GroupsUser|null $groupUser */
                $groupUser = $groupsUsersAddService
                        ->add($uac, $groupUserData)
                        ->getAddedEntities(GroupsUser::class)[0] ?? null;
                if (is_null($groupUser)) {
                    throw new \Exception('A GroupUser entity should be present in the DTO');
                }
                $this->DirectoryRelations->createFromGroupUser($groupUser);
                $this->addReportItem(new ActionReport(
                    __('The user {0} was successfully added to the group {1}.', $user->username, $group->name),
                    Alias::MODEL_GROUPS_USERS,
                    Alias::ACTION_CREATE,
                    Alias::STATUS_SUCCESS,
                    $group
                ));
            } catch (ValidationException $exception) {
                $errors = $exception->getErrors();
                $isNotActive = !empty(Hash::extract($errors, 'user_id.user_is_active'));
                $isDeleted = !empty(Hash::extract($errors, 'user_id.user_is_not_soft_deleted'));
                if (($isNotActive && $isDeleted) || $isDeleted) {
                    $msg = __('The user {0} could not be added to the group {1} because their account was priorly deleted in passbolt.', $user->username, $group->name);//phpcs:ignore
                } elseif ($isNotActive) {
                    $msg = __('The user {0} could not be added to the group {1} because they have not yet activated their account.', $user->username, $group->name);//phpcs:ignore
                } else {
                    $msg = __('The user {0} could not be added to the group {1} because of validation issues.', $user->username, $group->name);//phpcs:ignore
                }
                $this->addReportItem(new ActionReport(
                    $msg,
                    Alias::MODEL_GROUPS_USERS,
                    Alias::ACTION_CREATE,
                    Alias::STATUS_IGNORE,
                    $group
                ));
            } catch (\Exception $exception) {
                $error = new SyncError($group, $exception);
                $this->addReportItem(new ActionReport(
                    __('The user {0} could not be added to the group {1} because of an internal error.', $user->username, $group->name),//phpcs:ignore
                    Alias::MODEL_GROUPS_USERS,
                    Alias::ACTION_CREATE,
                    Alias::STATUS_ERROR,
                    $error
                ));

                continue;
            }
        }
    }

    /**
     * Request to add users into the group.
     *
     * @param \App\Model\Entity\Group $group groups
     * @param array $userIdsToAdd list of user ids to add
     * @return void
     */
    private function requestAddGroupUsers(Group $group, array $userIdsToAdd): void
    {
        $groupUsers = [];
        foreach ($userIdsToAdd as $userId) {
            $u = $this->Users->get($userId);

            // If users are deleted or active, we just ignore the entry.
            if ($u->deleted) {
                $msg = __('The user {0} could not be added to the group {1} because their account was priorly deleted in passbolt.', $u->username, $group->name); //phpcs:ignore
                $this->addReportItem(new ActionReport(
                    $msg,
                    Alias::MODEL_GROUPS_USERS,
                    Alias::ACTION_CREATE,
                    Alias::STATUS_IGNORE,
                    $group
                ));

                continue;
            } elseif (!$u->active) {
                $msg = __('The user {0} could not be added to the group {1} because they have not yet activated their account.', $u->username, $group->name);//phpcs:ignore
                $this->addReportItem(new ActionReport(
                    $msg,
                    Alias::MODEL_GROUPS_USERS,
                    Alias::ACTION_CREATE,
                    Alias::STATUS_IGNORE,
                    $group
                ));

                continue;
            } else {
                $groupUsers[] = $this->GroupsUsers->buildEntity(['group_id' => $group->id, 'user_id' => $userId]);
                $this->addReportItem(new ActionReport(
                    __('The user {0} cannot be added to the group {1} automatically. An email request was sent to the group manager(s) to do it manually.', $u->username, $group->name),//phpcs:ignore
                    Alias::MODEL_GROUPS_USERS,
                    Alias::ACTION_CREATE,
                    Alias::STATUS_WARNING,
                    $u
                ));
            }
        }

        // Send notification if group users are required to be added, and job not in dry-run mode.
        if (!empty($groupUsers) && !$this->isDryRun()) {
            $accessControl = new UserAccessControl(Role::ADMIN, $this->defaultAdmin->get('id'));
            $eventData = ['groupUsers' => $groupUsers, 'group' => $group, 'requester' => $accessControl];
            $event = new Event('Model.Groups.requestGroupUsers.success', $this, $eventData);
            $this->getEventManager()->dispatch($event);
        }
    }

    /**
     * Remove groupUsers for a given group.
     *
     * @param \App\Model\Entity\Group $group group where to remove the groupUsers
     * @param array $groupUserIdsToRemove list of groupUsers Ids
     * @return void
     */
    private function removeGroupUsers(Group $group, array $groupUserIdsToRemove)
    {
        $uac = new UserAccessControl(Role::ADMIN, $this->defaultAdmin->get('id'));
        $groupUserDeleteService = new GroupsUsersDeleteService();

        foreach ($groupUserIdsToRemove as $groupUserId) {
            // If corresponding groupUser does not exist, cleanup the relation.
            if (!$this->GroupsUsers->exists(['id' => $groupUserId])) {
                $directoryRelation = $this->DirectoryRelations->get($groupUserId);
                $this->DirectoryRelations->delete($directoryRelation);
                continue;
            }

            /** @var \App\Model\Entity\GroupsUser|null $groupUserToDelete */
            $groupUserToDelete = $this->GroupsUsers->findById($groupUserId)->contain(['Users'])->first();
            $username = $groupUserToDelete->get('user')->username;
            try {
                $entitiesChanges = $groupUserDeleteService->delete($uac, $groupUserToDelete->id);
                // Delete relation
                $directoryRelation = $this->DirectoryRelations->get($groupUserId);
                $this->DirectoryRelations->delete($directoryRelation);
                // Send report.
                $this->addReportItem(new ActionReport(
                    __('The user {0} was successfully removed from the group {1}.', $username, $group->name),
                    Alias::MODEL_GROUPS_USERS,
                    Alias::ACTION_DELETE,
                    Alias::STATUS_SUCCESS,
                    $group
                ));
                // Notify users
                $event = new Event(GroupsUpdateService::UPDATE_SUCCESS_EVENT_NAME, $this, [
                    'group' => $group,
                    'entitiesChanges' => $entitiesChanges,
                    'userId' => $uac->getId(),
                ]);
                $this->GroupsUsers->Groups->getEventManager()->dispatch($event);
            } catch (ValidationException $exception) {
                $errors = $exception->getEntity()->getErrors();
                if (isset($errors['is_admin']['at_least_one_group_manager'])) {
                    $msg = __('The user {0} could not be removed from the group {1} because it is the only group manager.', $username, $group->name);//phpcs:ignore
                } else {
                    $msg = __('The user {0} could not be removed from the group {1} because some validation issues.', $username, $group->name);//phpcs:ignore
                }
                $error = new SyncError($group, $exception);
                $this->addReportItem(new ActionReport($msg, Alias::MODEL_GROUPS_USERS, Alias::ACTION_DELETE, Alias::STATUS_ERROR, $error));//phpcs:ignore
            } catch (\Exception $exception) {
                $error = new SyncError($group, $exception);
                $this->addReportItem(new ActionReport(
                    __('The user {0} could not be removed from the group {1} because of an internal error.', $username, $group->name),//phpcs:ignore
                    Alias::MODEL_GROUPS_USERS,
                    Alias::ACTION_DELETE,
                    Alias::STATUS_ERROR,
                    $error
                ));
            }
        }
    }

    /**
     * Sync Group Users.
     *
     * @param \App\Model\Entity\Group $group group
     * @param array $toSync list of groupuserIds to be synced.
     * @return void
     */
    private function syncGroupUsers(Group $group, array $toSync)
    {
        foreach ($toSync as $groupUser) {
            $directoryRelation = $this->DirectoryRelations->createFromGroupUser($groupUser);
            $this->addReportItem(new ActionReport(
                __('The user {0} was successfully synced with the group {1}.', $groupUser->user->username, $group->name),//phpcs:ignore
                Alias::MODEL_GROUPS_USERS,
                Alias::ACTION_CREATE,
                Alias::STATUS_SYNC,
                $directoryRelation
            ));
        }
    }

    /**
     * Find directory entries corresponding to a groupUser
     *
     * @param array $groupsUsersDn user directory name
     * @return array
     */
    private function findDirectoryEntriesForGroupUsers(array $groupsUsersDn): array
    {
        if (empty($groupsUsersDn)) {
            return [];
        }

        if (!Configure::read('passbolt.plugins.directorySync.caseSensitiveFilters')) {
            // Do work to make query check case-insensitive
            foreach ($groupsUsersDn as $k => $value) {
                $groupsUsersDn[$k] = strtolower($value);
            }

            $whereDirectoryNameColumn = 'LOWER(directory_name)';
        } else {
            $whereDirectoryNameColumn = 'directory_name';
        }

        return $this->DirectoryEntries
            ->find()
            ->where(["{$whereDirectoryNameColumn} IN" => $groupsUsersDn, 'foreign_model' => Alias::MODEL_USERS])
            ->all()
            ->toArray();
    }

    /**
     * Check if a DN belongs to the groups returned by the directory.
     *
     * @param string $dn DN
     * @return bool
     */
    private function groupExistsInDirectory(string $dn): bool
    {
        $groupDns = Hash::extract($this->directoryData, '{n}.directory_name');

        return in_array($dn, $groupDns);
    }

    /**
     * @param string $dn Dn to check.
     * @param \Passbolt\DirectorySync\Model\Entity\DirectoryEntry[] $directoryEntries Directory entry entities.
     * @return \Passbolt\DirectorySync\Model\Entity\DirectoryEntry|null Returns entity if exists, `null` otherwise.
     */
    private function lookupDnInDirectoryEntries(string $dn, array $directoryEntries): ?DirectoryEntry
    {
        $dn = (new UserCollection())->transformOffset($dn);

        foreach ($directoryEntries as $directoryEntry) {
            $directoryName = (new UserCollection())->transformOffset($directoryEntry->directory_name);

            if ($directoryName === $dn) {
                return $directoryEntry;
            }
        }

        return null;
    }

    /**
     * Find the directory relations corresponding to an entry.
     *
     * @param string $entryId entry id
     * @return array
     */
    private function findDirectoryRelationsByEntryId(string $entryId)
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
     * Handle groupUsers once a group is created.
     *
     * @param array $data directory data
     * @param \App\Model\Entity\Group $group group
     * @return void
     */
    private function handleGroupUsersAfterGroupCreate(array $data, Group $group)
    {
        if (!isset($group->groups_users)) {
            $group = $this->findGroupWithGroupUsers($group);
        }
        $toAdd = $this->retrieveUsersToAdd($data, $group);
        $this->addGroupUsers($group, $toAdd);
    }

    /**
     * Update group
     *
     * @param \App\Model\Entity\Group $existingGroup Group
     * @param array $data data
     * @return void
     */
    private function updateGroup(Group $existingGroup, array $data): void
    {
        $uac = new UserAccessControl(Role::ADMIN, $this->defaultAdmin->get('id'));
        // Password expiry is not active in LDAP as of now
        $groupsUpdateService = new GroupsUpdateService(
            new ResourcesExpireResourcesFallbackServiceService()
        );
        $groupName = $this->getNameFromData($data);
        $changes = [
            'name' => $groupName,
        ];
        try {
            $entity = $groupsUpdateService->update($uac, $existingGroup->id, $changes);
            // Send report.
            $this->addReportItem(new ActionReport(
                __('The group {0} has been successfully renamed to {1}.', $existingGroup->name, $groupName),
                Alias::MODEL_GROUPS,
                Alias::ACTION_UPDATE,
                Alias::STATUS_SUCCESS,
                $entity
            ));
        } catch (\Exception $exception) {
            $error = new SyncError($existingGroup, $exception);
            $this->addReportItem(new ActionReport(
                __('The group {0} could not be renamed to {1}.', $existingGroup->name, $groupName),
                Alias::MODEL_GROUPS,
                Alias::ACTION_UPDATE,
                Alias::STATUS_ERROR,
                $error
            ));
        }
    }

    /**
     * @inheritDoc
     */
    protected function getTable(): Table
    {
        return TableRegistry::getTableLocator()->get('Groups');
    }
}

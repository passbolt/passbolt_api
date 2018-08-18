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
namespace Passbolt\DirectorySync\Actions;

use Cake\Core\Configure;
use Cake\Network\Exception\InternalErrorException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\DirectorySync\Actions\Traits\GroupSyncAddTrait;
use Passbolt\DirectorySync\Actions\Traits\GroupSyncDeleteTrait;
use Passbolt\DirectorySync\Actions\Traits\GroupUsersSyncTrait;
use Passbolt\DirectorySync\Actions\Traits\SyncDeleteTrait;
use Passbolt\DirectorySync\Actions\Traits\SyncTrait;
use Passbolt\DirectorySync\Utility\Alias;
use Passbolt\DirectorySync\Utility\SyncAction;

class GroupSyncAction extends SyncAction
{
    use SyncTrait;
    use SyncDeleteTrait;
    use GroupSyncAddTrait;
    use GroupUsersSyncTrait;

    /**
     * @var string entityType
     */
    const ENTITY_TYPE = Alias::MODEL_GROUPS;

    /**
     * @var \Cake\ORM\Table
     */
    public $Groups;

    /**
     * @var array|mixed
     */
    public $groupsToIgnore;

    /**
     * @var array|mixed
     */
    public $defaultGroupAdmin;

    /**
     * GroupSyncAction constructor.
     *
     * @throws \Exception
     */
    public function __construct() {
        parent::__construct();
    }

    public function beforeExecute()
    {
        parent::beforeExecute();
        $this->initialize(self::ENTITY_TYPE);
        $this->defaultGroupAdmin = $this->getDefaultGroupAdmin();
        if (empty($this->defaultGroupAdmin)) {
            $this->defaultGroupAdmin = $this->defaultAdmin;
        }
    }

    /**
     * Execute groups sync.
     * - Delete all groups that can be deleted
     * - Create all groups that can be created
     * - Generate report
     *
     * @return \Passbolt\DirectorySync\Utility\ActionReportCollection
     */
    public function execute() {
        $this->beforeExecute();
        $this->processEntriesToDelete();
        if (Configure::read('passbolt.plugins.directorySync.jobs.groups.create')) {
            $this->processEntriesToCreate();
        }
        $this->afterExecute();
        return $this->getSummary();
    }

//    /**
//     * Handle the group deletion job
//     *
//     * Find all the directory entries that have been deleted and try to delete the associated groups
//     * If they are not already deleted, or marked as to be ignored
//     *
//     * @return void
//     */
//    function processEntriesToDelete()
//    {
//        $entriesId = Hash::extract($this->directoryData, '{n}.id');
//        $this->DirectoryIgnore->cleanupHardDeletedGroups();
//        $entries = $this->DirectoryEntries->lookupEntriesForDeletion(Alias::MODEL_GROUPS, $entriesId);
//        $this->DirectoryIgnore->cleanupHardDeletedDirectoryEntries($entriesId);
//        $this->DirectoryRelations->cleanupHardDeletedUserGroups($entriesId);
//
//        foreach ($entries as $entry) {
//            // The directory entry or user is marked as to be ignored
//            if (in_array($entry->id, $this->entriesToIgnore)) {
//                $this->handleDeletedIgnoredEntry($entry);
//                continue;
//            }
//
//            // The directory entry or user is marked as to be ignored
//            if (isset($entry->group) && in_array($entry->group->id, $this->groupsToIgnore)) {
//                $this->handleDeletedIgnoredGroup($entry);
//                continue;
//            }
//
//            // The group was already hard or soft deleted
//            if ($entry->group === null || $entry->group->deleted) {
//                $this->handleDeletedEntry($entry);
//                continue;
//            }
//
//            try {
//                if (!$this->Groups->softDelete($entry->group)) {
//                    // The group cannot be deleted (for example: it is the sole owner of shared passwords)
//                    $this->handleNotPossibleDelete($entry);
//                } else {
//                    // Group was deleted
//                    $this->handleSuccessfulDelete($entry);
//                    $this->handleGroupUsersDeleted($entry);
//                }
//            } catch(InternalErrorException $exception) {
//                $this->handleInternalErrorDelete($entry, $exception);
//            }
//        }
//    }

    /**
     * Handle the group creation job
     *
     * @return void
     */
    public function processEntriesToCreate()
    {
        foreach($this->directoryData as $i => $data) {
            // Find and patch or create directory entries
            $entry = $this->DirectoryEntries->updateOrCreate($data, Alias::MODEL_GROUPS);
            if ($entry === false) {
                continue;
            }
            if (!isset($entry->group)) {
                $existingGroup = $this->getGroupFromData($data);
            } else {
                $existingGroup = $entry->group;
            }

            // If directory entry or user are marked as to be ignored
            $ignoreEntry = in_array($data['id'], $this->entriesToIgnore);
            $ignoreGroup = (isset($existingGroup) && in_array($existingGroup->id, $this->groupsToIgnore));
            if($ignoreEntry || $ignoreGroup) {
                $this->handleAddIgnore($data, $entry, $existingGroup, $ignoreGroup);
                continue;
            }

            if (!isset($existingGroup)) {
                $this->handleAddNew($data, $entry);
                continue;
            }

            if (isset($existingGroup) && $existingGroup->deleted) {
                $this->handleAddDeleted($data, $entry, $existingGroup);
                continue;
            }

            $this->handleAddExist($data, $entry, $existingGroup);
        }
    }

    /**
     * Get group from data.
     *
     * @param array $data
     * @return array|\Cake\Datasource\EntityInterface|null
     */
    public function getGroupFromData(array $data) {
        // If not group already associated, find if there is a corresponding group in the database.
        $existingGroup = $this->Groups->find()
            ->select(['id', 'name', 'deleted', 'created', 'modified'])
            ->where(['name' => $data['group']['name']])
            ->order(['Groups.modified' => 'DESC'])
            ->first();
        if (!isset($existingGroup) || empty($existingGroup)) {
            $existingGroup = null;
        }
        return $existingGroup;
    }

    /**
     * Get default group administrator
     *
     * @return array|\Cake\Datasource\EntityInterface|mixed|null
     */
    public function getDefaultGroupAdmin() {
        $groupAdmin = Configure::read('passbolt.plugins.directorySync.defaultGroupAdminUser');
        if (!empty($groupAdmin)) {
            // Get groupAdmin from database.
            $groupAdmin = $this->Users->find()
                ->where([
                    'Users.deleted' => false,
                    'Users.active' => true,
                    'Users.username' => $groupAdmin
                ])
                ->first();
            if (!empty($groupAdmin)) {
                return $groupAdmin;
            }
        }

        // If can't find corresponding config user, return first admin.
        return $this->Users->findFirstAdmin();
    }
}
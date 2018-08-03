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

use App\Model\Entity\Group;
use Cake\Core\Configure;
use Cake\Datasource\RulesChecker;
use Cake\Network\Exception\InternalErrorException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\DirectorySync\Actions\Traits\GroupSyncAddTrait;
use Passbolt\DirectorySync\Actions\Traits\GroupSyncDeleteTrait;
use Passbolt\DirectorySync\Model\Entity\DirectoryEntry;
use Passbolt\DirectorySync\Utility\ActionReport;
use Passbolt\DirectorySync\Utility\SyncAction;


class GroupSyncAction extends SyncAction
{
    use GroupSyncDeleteTrait;
    use GroupSyncAddTrait;

    /**
     * @var \Cake\ORM\Table
     */
    public $Groups;

    /**
     * @var \Cake\ORM\Table
     */
    public $Users;

    /**
     * @var array|mixed
     */
    public $DirectoryIgnore;

    /**
     * @var array|mixed
     */
    public $directoryData;

    /**
     * @var array|mixed
     */
    public $groupIdsToIgnore;

    /**
     * @var array|mixed
     */
    public $directoryIdsToIgnore;

    /**
     * @var array|mixed
     */
    public $defaultAdmin;

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
        $this->Groups = TableRegistry::getTableLocator()->get('Groups');
        $this->Users = TableRegistry::getTableLocator()->get('Users');
    }

    public function beforeExecute()
    {
        // Find all groups to ignore.
        $this->groupIdsToIgnore = Hash::extract($this->DirectoryIgnore->find()
          ->select(['id'])
          ->where(['foreign_model' => SyncAction::GROUPS])
          ->all()
          ->toArray(), '{n}.id');

        // Find all the entries to ignore.
        $this->directoryIdsToIgnore = Hash::extract($this->DirectoryIgnore->find()
          ->select(['id'])
          ->where(['foreign_model' => SyncAction::DIRECTORY_ENTRIES])
          ->all()
          ->toArray(), '{n}.id');

        $this->defaultAdmin = $this->getDefaultAdmin();
        if (empty($this->defaultAdmin)) {
            throw new \Exception('Configuration issue. A default admin user cannot be found.');
        }

        $this->defaultGroupAdmin = $this->getDefaultGroupAdmin();
        if (empty($this->defaultGroupAdmin)) {
            throw new \Exception('Configuration issue. A default group admin user cannot be found.');
        }

        $this->directoryData = $this->directory->getGroups();
    }

    /**
     * Execute groups sync.
     */
    public function execute() {
        $this->beforeExecute();
        if (Configure::read('passbolt.plugins.directorySync.jobs.groups.delete')) {
            $this->processEntriesToDelete();
        }
        if (Configure::read('passbolt.plugins.directorySync.jobs.groups.create')) {
            $this->processEntriesToCreate();
        }
        return $this->summary;
    }

    /**
     * Handle the user deletion job
     *
     * Find all the directory entries that have been deleted and try to delete the associated users
     * If they are not already deleted, or marked as to be ignored
     *
     * @return void
     */
    function processEntriesToDelete()
    {
        $entriesId = Hash::extract($this->directoryData, '{n}.id');
        $entries = $this->DirectoryEntries->lookupEntriesForDeletion(self::GROUPS, $entriesId);
        $this->DirectoryIgnore->cleanupHardDeletedDirectoryEntries($entriesId);
        $this->DirectoryIgnore->cleanupHardDeletedGroups();

        foreach ($entries as $entry) {
            // The directory entry or user is marked as to be ignored
            if (in_array($entry->id, $this->directoryIdsToIgnore) || ($entry->group !== null && in_array($entry->group->id, $this->groupIdsToIgnore))) {
                $this->handleDeletedIgnoredEntry($entry);
                continue;
            }

            // The group was already hard or soft deleted
            if ($entry->group === null || $entry->group->deleted) {
                $this->handleDeletedEntry($entry);
                continue;
            }

            // The group cannot be deleted (example: it is the sole owner of some passwords).
            if (!$this->Groups->checkRules($entry->group, RulesChecker::DELETE)) {
                $this->handleNotPossibleDelete($entry);
                continue;
            }

            // Group can be deleted
            try {
                if (!$this->Groups->softDelete($entry->group)) {
                    $this->handleErrorDelete($entry);
                } else {
                    $this->handleSuccessfulDelete($entry);
                }
            } catch(InternalErrorException $exception) {
                // TODO discuss format ErrorReport() ?
                $this->handleErrorDelete($entry);
            }
        }
    }

    public function processEntriesToCreate() {
        if (!isset($this->directoryData) || empty($this->directoryData)) {
            // Directory is empty nothing to do
            return;
        }

        foreach($this->directoryData as $i => $data) {
            // If similar group can be retrieved.
            $entry = $this->getEntryFromData($data);
            $existingGroup = $this->getGroupFromData($data, $entry);

            if(isset($existingGroup) && $this->isGroupIgnored($existingGroup)) {
                $this->handleAddIgnore($data, $entry, $existingGroup);
                continue;
            }

            if($this->isDirectoryEntryIgnored($data['id'])) {
                $this->handleAddIgnore($data, $entry, $existingGroup);
                continue;
            }

            if (isset($existingGroup) && $existingGroup->deleted == true) {
                $this->handleAddDeleted($data, $entry, $existingGroup);
                continue;
            }

            if (isset($existingGroup) && ((isset($entry) && ($entry->status == SELF::ERROR)) || !isset($entry))) {
                $this->DirectoryEntries->updateStatusOrCreate($data, self::SUCCESS, self::GROUPS, $existingGroup, $entry);
                $this->addReport(new ActionReport(self::GROUPS, self::CREATE, SELF::SYNC, $existingGroup));
                continue;
            }

//            if (!isset($existingGroup) && isset($entry) && $entry->status == SELF::SUCCESS) {
//                $this->DirectoryEntries->updateStatusOrCreate($data, self::ERROR, self::GROUPS, $existingGroup, $entry);
//                $this->addReport(new ActionReport(self::GROUPS, self::CREATE, SELF::ERROR, $entry));
//            }

            if (!isset($existingGroup)) {
                $this->handleAdd($data, $entry);
                continue;
            }
        }
    }

    private function isDirectoryEntryIgnored(string $id) {
        return in_array($id, $this->directoryIdsToIgnore);
    }

    private function isGroupIgnored(Group $group) {
        return in_array($group->id, $this->groupIdsToIgnore);
    }

    /**
     * Get group from data.
     * @param array $data
     * @param DirectoryEntry|null $entry
     *
     * @return array|\Cake\Datasource\EntityInterface|null
     */
    public function getGroupFromData(array $data, DirectoryEntry $entry = null) {
        // We first check if there is a group associated to the entry.
        if (isset($entry)) {
            if (!empty($entry->group)) {
                return $entry->group;
            }
        }

        // If not group already associated, find if there is a corresponding group in the database.
        $existingGroup = $this->Groups->find()
            ->select(['id', 'deleted', 'created', 'modified'])
            ->where(['name' => $data['group']['name']])
            ->order(['Groups.modified' => 'DESC'])
            ->first();
        if (!isset($existingGroup) || empty($existingGroup)) {
            $existingGroup = null;
        }
        return $existingGroup;
    }

    /**
     * @param $data
     * @return array|\Cake\Datasource\EntityInterface|null
     */
    protected function getEntryFromData(array $data)
    {
        $entry = null;
        try {
            $entry = $this->DirectoryEntries->get($data['id'], ['contain' => ['Groups']]);
        } catch(\Exception $exception) {
        }
        return $entry;
    }

    /**
     * Create directory entry for given directory data and group.
     *
     * @param array $directoryData
     * @param Group|null $group
     * @param string $status
     *
     * @return mixed
     */
    public function createDirectoryEntry(array $directoryData, Group $group = null, string $status = DirectoryEntry::STATUS_SUCCESS) {
        $directoryEntry = [
            'id' => $directoryData['id'],
            'foreign_model' => 'Groups',
            'foreign_key' => ($group == null ? null : $group->id),
            'directory_name' => $directoryData['directory_name'],
            'directory_created' => $directoryData['directory_created'],
            'directory_modified' => $directoryData['directory_modified'],
            'status' => $status,
        ];
        return $this->DirectoryEntries->create($directoryEntry);
    }
}
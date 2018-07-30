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

use App\Error\Exception\ValidationException;
use App\Model\Entity\Group;
use App\Model\Entity\Role;
use App\Utility\UserAccessControl;
use Cake\Core\Configure;
use Cake\Datasource\RulesChecker;
use Cake\Network\Exception\InternalErrorException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\DirectorySync\Actions\Traits\GroupSyncDeleteTrait;
use Passbolt\DirectorySync\Model\Entity\DirectoryEntry;
use Passbolt\DirectorySync\Model\Entity\DirectoryIgnore;
use Passbolt\DirectorySync\Model\Table\DirectoryIgnoreTable;
use Passbolt\DirectorySync\Utility\ActionReport;
use Passbolt\DirectorySync\Utility\ErrorReport;
use Passbolt\DirectorySync\Utility\SyncAction;


class GroupSyncAction extends SyncAction
{
    use GroupSyncDeleteTrait;

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
          ->where(['foreign_model' => SyncAction::DIRECTORY_ENTRY])
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

        foreach ($entries as $entry) {
            // The directory entry or user is marked as to be ignored
            if (in_array($entry->id, $this->directoryIdsToIgnore) || ($entry->group !== null && in_array($entry->group->id, $this->groupIdsToIgnore))) {
                $this->handleDeletedIgnoredEntry($entry);
                continue;
            }

            // The user was already hard or soft deleted
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
                    $this->handleSuccessfulDelete($entry);
                } else {
                    $this->handleErrorDelete($entry);
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
            $existingGroup = $this->getGroupFromData($data);
            $entry = $this->getEntryFromData($data);

            // TODO: case where entry exists and is associated to a group, but the actual group returned by getGroupFromData is different.

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

    public function handleAddIgnore($data, $entry, $existingGroup) {
        if (isset($entry)) {
            $this->DirectoryEntries->delete($entry);
        }
        if (isset($existingGroup) && !$this->isGroupIgnored($existingGroup)) {
            $this->DirectoryIgnore->create(['id' => $existingGroup->id, 'foreign_model' => self::GROUPS]);
        }
        $this->addReport(new ActionReport(self::GROUPS, self::CREATE, SELF::IGNORE, isset($existingGroup) ? $existingGroup : $data));
    }

    public function handleAddDeleted($data, $entry, $existingGroup) {
        // Check if ignored.
        if ($data['directory_created']->lt($existingGroup->modified)) {
            $this->handleAddIgnore($data, $entry, $existingGroup);
        } else {
            $this->handleAdd($data, $entry);
        }
    }

    public function handleAdd($data, $entry) {
        if ($this->isDirectoryEntryIgnored($data['id'])) {
            if (isset($entry)) { $this->DirectoryEntries->delete($entry); }
            // TODO: cannot add this report because no entry exists anymore.
            //$this->addReport(new ActionReport(self::GROUPS, self::CREATE, SELF::IGNORE, $data));
            return;
        }

        $g = $this->createGroup($data['group']);
        if ($g)  {
            $this->DirectoryEntries->updateStatusOrCreate($data, self::SUCCESS, self::GROUPS, $g, $entry);
        } else {
            $this->DirectoryEntries->updateStatusOrCreate($data, self::ERROR, self::GROUPS, null, $entry);
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
     * @param $data
     *
     * @return array|\Cake\Datasource\EntityInterface|null
     */
    public function getGroupFromData(array $data) {
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
     * Create a group and log report.
     * @param $group
     *
     * @return bool
     */
    public function createGroup($group) {
        $group = $this->manageGroupUsers($group);
        try {
            $g = $this->Groups->create($group, new UserAccessControl(Role::ADMIN, $this->defaultAdmin->id));
            $this->addReport(new ActionReport(self::GROUPS, self::CREATE, self::SUCCESS, $g));
            return $g;
        } catch(ValidationException $exception) {
            $this->addReport(new ErrorReport(self::GROUPS, self::CREATE, $exception));
            return false;
        } catch (\Exception $exception) {
            $this->addReport(new ErrorReport(self::GROUPS, self::CREATE, $exception));
            return false;
        }
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

    // Dummy function for now. Will be improved later.
    public function manageGroupUsers($group) {
        $group['groups_users'][] = [
            'user_id' => $this->defaultGroupAdmin->id,
            'is_admin' => true,
        ];
        return $group;
    }

    /**
     * Get default group administrator
     * @return array|\Cake\Datasource\EntityInterface|mixed|null
     */
    public function getDefaultGroupAdmin() {
        $groupAdmin = Configure::read('passbolt.plugins.directorySync.defaultGroupAdminUser');
        if (!empty($groupAdmin)) {
            // Get groupAdmin from database.
            $groupAdmin =
                $this->Users->find()
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

    /**
     * Get default admin.
     * @return array|\Cake\Datasource\EntityInterface|mixed|null
     */
    public function getDefaultAdmin() {
        $defaultUser = Configure::read('passbolt.plugins.directorySync.defaultUser');
        if (!empty($defaultUser)) {
            // Get default user from database.
            $defaultUser =
                $this->Users->find()
                    ->where([
                        'Users.deleted' => false,
                        'Users.active' => true,
                        'Users.username' => $defaultUser,
                        'Users.role_id' => $this->Users->Roles->getIdByName(Role::ADMIN),
                    ])
                    ->first();
            if (!empty($defaultUser)) {
                return $defaultUser;
            }
        }

        // If can't find corresponding config user, return first admin.
        return $this->Users->findFirstAdmin();
    }
}
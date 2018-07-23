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
use Cake\ORM\TableRegistry;
use Cake\ORM\RulesChecker;
use Cake\Utility\Hash;
use Passbolt\DirectorySync\Model\Entity\DirectoryEntry;

class UserSyncAction extends SyncAction
{
    /**
     * @var \Cake\ORM\Table
     */
    public $Users;

    /**
     * @var array|mixed
     */
    public $directoryData;

    /**
     * UserSyncAction constructor.
     *
     * @throws \Exception
     */
    public function __construct() {
        parent::__construct();
        $this->Users = TableRegistry::getTableLocator()->get('Users');
        $this->DirectoryIgnore = TableRegistry::getTableLocator()->get('DirectoryIgnore');
        $this->directoryData = $this->directory->getUsers();

        // users to ignore
        $this->usersToIgnore = Hash::extract($this->DirectoryIgnore->find()
            ->select(['id'])
            ->where(['foreign_model' => 'User'])
            ->all()
            ->toArray(), '{n}.id');
    }

    /**
     *
     */
    public function execute() {
        if (Configure::read('passbolt.plugins.directorySync.jobs.users.delete')) {
            $this->processDeletedEntries();
        }
        return;

        // Find all the entries to ignore
        $ignoredDirectoryEntryIds = Hash::extract($this->DirectoryIgnore->find()
            ->select(['id'])
            ->where(['foreign_model' => 'DirectoryEntry'])
            ->all()
            ->toArray(), '{n}.id');

        // Find all the users to ignore


        // Make list of entries that are not ignored
        $notIgnoredEntries = [];
        foreach ($directoryIds as $i => $id) {
            if (!in_array($id, $ignoredDirectoryEntryIds)) {
                $notIgnoredEntries[] = $id;
            }
        }

        // Find all the entries that are and are not already synced
        $syncedEntries = $this->DirectoryEntries->find()
            ->select(['id', 'status'])
            ->where(['DirectoryEntries.id IN' => $notIgnoredEntries])
            ->contain(['Users'])
            ->all()
            ->toArray();
        $syncedEntriesId = Hash::extract($syncedEntries, '{n}.id');
        $notSyncedEntriesId = [];
        foreach ($syncedEntriesId as $i => $id) {
            if(!in_array($id, $syncedEntries)) {
                $notSyncedEntriesId[] = $id;
            }
        }

        foreach ($directoryEntries as $i => $entry) {
            if (in_array($ignoredDirectoryEntryIds, $entry['id'])) {
                // Nothing to do

            }
            if (in_array($syncedEntriesId, $entry['id'])) {

            }
        }
    }

    function processDeletedEntries()
    {
        // Find all the directory entries previously stored
        // that are not in the directory anymore
        $query = $this->DirectoryEntries->find()
            ->select()
            ->contain(['Users', 'DirectoryIgnore']);
        if (!empty($this->entries)) {
            $directoryIds = Hash::extract($this->directoryData, '{n}.id');
            $query = $query->where(['DirectoryEntries.id NOT IN' => $directoryIds]);
        }
        $result = $query->all();
        foreach ($result as $entry) {
            if (isset($entry->directory_ignore)) {
                // The directory entry is marked as to be ignored
                $this->DirectoryEntries->delete($entry);
                continue;
            }
            if ($entry->user === null) {
                // The user was hard deleted in passbolt and ldap
                $this->DirectoryEntries->delete($entry);
                continue;
            }
            if (in_array($entry->user->id, $this->usersToIgnore)) {
                // The user was marked as to be ignored
                $this->DirectoryEntries->delete($entry);
                continue;
            }
            if ($entry->user->deleted) {
                // The user is already deleted
                $this->DirectoryEntries->delete($entry);
                continue;
            }
            if (!$this->Users->checkRules($entry->user, RulesChecker::DELETE)) {
                // The user cannot be deleted
                // if the last try was already an error
                if ($entry->status === DirectoryEntry::STATUS_ERROR) {
                    // Do not retry more
                    $this->DirectoryEntries->delete($entry);
                } else {
                    $this->DirectoryEntries->updateStatus($entry, DirectoryEntry::STATUS_ERROR);
                }
                // TODO tell admin to handle delete manually
                continue;
            }

            // User can be deleted
            $success = $this->Users->softDelete($entry->user);
            if (!$success) {
                // TODO tell admin to handle delete manually
                $this->DirectoryEntries->updateStatus($entry, DirectoryEntry::STATUS_ERROR);
            } else {
                // TODO Tell admin user was deleted
                $this->DirectoryEntries->delete($entry);
            }

        }
    }
}
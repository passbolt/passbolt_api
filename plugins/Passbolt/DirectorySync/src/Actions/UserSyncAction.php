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
use Passbolt\DirectorySync\Utility\ActionReport;
use Passbolt\DirectorySync\Utility\SyncAction;

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

    }

    /**
     * Things to do after the constructor and before the sync job
     */
    public function beforeExecute()
    {
        $this->entriesToIgnore = Hash::extract($this->DirectoryIgnore->find()
            ->select(['id'])
            ->where(['foreign_model' => 'DirectoryEntry'])
            ->all()
            ->toArray(), '{n}.id');

        $this->usersToIgnore = Hash::extract($this->DirectoryIgnore->find()
            ->select(['id'])
            ->where(['foreign_model' => 'User'])
            ->all()
            ->toArray(), '{n}.id');
    }

    /**
     * Perform a user sync
     * - Delete all users that can be deleted
     * - Create all users that can be created
     * - Generate report for admin
     */
    public function execute() {
        $this->beforeExecute();
        if (Configure::read('passbolt.plugins.directorySync.jobs.users.delete')) {
            $this->processEntriesToDelete();
        }
        if (Configure::read('passbolt.plugins.directorySync.jobs.users.create')) {
            $this->processEntriesToCreate();
        }
        return $this->getSummary();
    }

    function processEntriesToDelete()
    {
        // Find all the directory entries previously stored
        // that are not in the directory anymore
        $query = $this->DirectoryEntries->find()
            ->select()
            ->contain(['Users']);
        if (!empty($this->entries)) {
            $directoryIds = Hash::extract($this->directoryData, '{n}.id');
            $query = $query->where(['DirectoryEntries.id NOT IN' => $directoryIds]);
        }
        $result = $query->all();
        foreach ($result as $entry) {
            if (in_array($entry->id, $this->entriesToIgnore) ||
                $entry->user === null || in_array($entry->user->id, $this->usersToIgnore) || $entry->user->deleted) {
                // The directory entry is marked as to be ignored or
                // The user was already hard deleted or
                // The user was marked as to be ignored or
                // The user was already soft deleted
                $this->DirectoryEntries->delete($entry);
                $this->addReport(new ActionReport(
                    self::USERS, self::DELETE, self::IGNORE,
                    $entry
                ));
                continue;
            }
            if (!$this->Users->checkRules($entry->user, RulesChecker::DELETE)) {
                // The user cannot be deleted
                if ($entry->status === self::ERROR) {
                    // if the last try was already an error
                    // Do not retry more
                    $this->DirectoryEntries->delete($entry);
                } else {
                    // Mark entry as error to allow future retry
                    $this->DirectoryEntries->updateStatus($entry, self::ERROR);
                }
                $this->addReport(new ActionReport(
                    self::USERS, self::DELETE, self::ERROR,
                    $entry
                ));
                continue;
            }

            // User can be deleted
            $success = $this->Users->softDelete($entry->user);
            if (!$success) {
                $this->DirectoryEntries->updateStatus($entry, self::ERROR);
                $this->addReport(new ActionReport(
                    self::USERS, self::DELETE, self::ERROR,
                    $entry
                ));
            } else {
                $this->DirectoryEntries->delete($entry);
                $this->addReport(new ActionReport(
                    self::USERS, self::DELETE, self::SUCCESS,
                    $entry
                ));
            }

        }
    }

    function processEntriesToCreate()
    {
        if (empty($this->directoryData)) {
            // Directory is empty, nothing to add
            return;
        }
        foreach ($this->directoryData as $entry) {
            // If the entry is marked as to be ignored
            if (in_array($entry->id, $this->entriesToIgnore)) {
                // Remove directory entry if entry should be ignored
                // Keep reference in ignore table
                $this->DirectoryEntries->delete($entry);
                $this->addReport(new ActionReport(
                    self::USERS, self::CREATE, self::IGNORE,
                    $entry
                ));
                continue;
            }

            // Find if directory entries exist
            $entryExist = $this->DirectoryEntries->find()
                ->select()
                ->contain(['Users'])
                ->where(['DirectoryEntries.id IN' => $entry->id])
                ->all()
                ->toArray();

            // the directory entry is not already present in passbolt
            if (!isset($entryExist) || empty($entryExist)) {
                // check if the user already exist in passbolt
                $userExist = $this->Users->find()
                    ->select(['id', 'active', 'deleted'])
                    ->where(['username' => $entry->username])
                    ->all()
                    ->toArray();

                if (!isset($userExist) || empty($userExist)) {

                }
             }
        }
    }
}
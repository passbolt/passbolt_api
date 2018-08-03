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
use Cake\ORM\RulesChecker;
use Cake\Utility\Hash;
use Passbolt\DirectorySync\Actions\Traits\UserSyncAddTrait;
use Passbolt\DirectorySync\Actions\Traits\UserSyncDeleteTrait;
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

    use UserSyncDeleteTrait;
    use UserSyncAddTrait;

    /**
     * UserSyncAction constructor.
     *
     * @throws \Exception
     */
    public function __construct() {
        parent::__construct();
        $this->Users = TableRegistry::getTableLocator()->get('Users');
    }

    /**
     * Things to do after the constructor and before the sync job
     */
    public function beforeExecute()
    {
        $this->entriesToIgnore = Hash::extract($this->DirectoryIgnore->find()
            ->select(['id'])
            ->where(['foreign_model' => SyncAction::DIRECTORY_ENTRIES])
            ->all()
            ->toArray(), '{n}.id');

        $this->usersToIgnore = Hash::extract($this->DirectoryIgnore->find()
            ->select(['id'])
            ->where(['foreign_model' => SyncAction::USERS])
            ->all()
            ->toArray(), '{n}.id');
        $this->directoryData = $this->directory->getUsers();
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
        $this->DirectoryIgnore->cleanupHardDeletedUsers();
        $entries = $this->DirectoryEntries->lookupEntriesForDeletion(self::USERS, $entriesId);
        $this->DirectoryIgnore->cleanupHardDeletedDirectoryEntries($entriesId);

        foreach ($entries as $entry) {
            // The directory entry or user is marked as to be ignored
            if (in_array($entry->id, $this->entriesToIgnore) || ($entry->user !== null && in_array($entry->user->id, $this->usersToIgnore))) {
                $this->handleDeletedIgnoredEntry($entry);
                continue;
            }

            // The user was already hard or soft deleted
            if ($entry->user === null || $entry->user->deleted) {
                $this->handleDeletedEntry($entry);
                continue;
            }

            // The user cannot be deleted (for example: it is the sole owner of shared passwords)
            if (!$this->Users->checkRules($entry->user, RulesChecker::DELETE)) {
                $this->handleNotPossibleDelete($entry);
                continue;
            }

            // User can be deleted
            try {
                if (!$this->Users->softDelete($entry->user)) {
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

    /**
     * Handle the user creation job
     *
     * @return void
     */
    function processEntriesToCreate()
    {
        foreach ($this->directoryData as $data) {
            // Try to find directory entries and user
            $entry = $this->getEntryFromData($data);
            if (!isset($entry->user)) {
                $existingUser = $this->getUserFromData($data);
            } else {
                $existingUser = $entry->user;
            }

            // If directory entry or user are marked as to be ignored
            $ignoreEntry = in_array($data['id'], $this->entriesToIgnore);
            $ignoreUser = (isset($existingUser) && in_array($existingUser->id, $this->usersToIgnore));
            if ($ignoreEntry || $ignoreUser) {
                $this->handleAddIgnore($data, $entry, $existingUser, $ignoreUser, $ignoreEntry);
                continue;
            }

            // If the user does not exist
            // Or it was deleted and then created again in the directory
            if (!isset($existingUser)) {
                $this->handleAddNew($data, $entry, $existingUser);
                continue;
            }

            // If the user exist but is already deleted
            if (isset($existingUser) && $existingUser->deleted) {
                $this->handleAddExistDeleted($data, $entry, $existingUser);
                continue;
            }

            // If the user already exist and is not deleted
            $this->handleAddExist($data, $entry, $existingUser);
        }
    }

    /**
     * @param $data
     * @return array|\Cake\Datasource\EntityInterface|null
     */
    protected function getUserFromData($data) {

        $existingUser = $this->Users->find()
            ->select(['id', 'active', 'deleted', 'created', 'modified'])
            ->where(['username' => $data['user']['username']])
            ->order(['Users.modified' => 'DESC'])
            ->first();
        if (!isset($existingUser) || empty($existingUser)) {
            $existingUser = null;
        }
        return $existingUser;
    }

    /**
     * @param $data
     * @return array|\Cake\Datasource\EntityInterface|null
     */
    protected function getEntryFromData($data)
    {
        $entry = null;
        try {
            $entry = $this->DirectoryEntries->get($data['id'], ['contain' => ['Users']]);
        } catch(\Exception $exception) {
        }
        return $entry;
    }
}
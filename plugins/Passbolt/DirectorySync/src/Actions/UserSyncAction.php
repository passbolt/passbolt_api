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
use Cake\Utility\Hash;
use Passbolt\DirectorySync\Actions\Traits\GroupUsersSyncTrait;
use Passbolt\DirectorySync\Actions\Traits\SyncTrait;
use Passbolt\DirectorySync\Actions\Traits\UserSyncAddTrait;
use Passbolt\DirectorySync\Utility\Alias;
use Passbolt\DirectorySync\Utility\SyncAction;

class UserSyncAction extends SyncAction
{
    use SyncTrait;
    use UserSyncAddTrait;
    use GroupUsersSyncTrait;

    /**
     * @var string entityType
     */
    const ENTITY_TYPE = Alias::MODEL_USERS;

    /**
     * UserSyncAction constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Things to do after the constructor and before the sync job
     */
    public function beforeExecute()
    {
        parent::beforeExecute();
        $this->initialize(self::ENTITY_TYPE);
    }

    /**
     * Perform a user sync
     * - Delete all users that can be deleted
     * - Create all users that can be created
     * - Generate report
     *
     * @return \Passbolt\DirectorySync\Utility\ActionReportCollection
     */
    public function execute()
    {
        $this->beforeExecute();
        $this->processEntriesToDelete();

        if (Configure::read('passbolt.plugins.directorySync.jobs.users.create')) {
            $this->processEntriesToCreate();
        }

        $this->afterExecute();
        return $this->getSummary();
    }

    /**
     * Handle the user creation job
     *
     * @return void
     */
    function processEntriesToCreate()
    {
        foreach ($this->directoryData as $data) {
            // Find and patch or create directory entries
            $entry = $this->DirectoryEntries->updateOrCreate($data, Alias::MODEL_USERS);
            if ($entry === false) {
                continue;
            }
            if (!isset($entry->user)) {
                $existingUser = $this->getUserFromData($data);
            } else {
                $existingUser = $entry->user;
            }

            // If directory entry or user are marked as to be ignored
            $ignoreEntry = in_array($data['id'], $this->entriesToIgnore);
            $ignoreUser = (isset($existingUser) && in_array($existingUser->id, $this->entitiesToIgnore));
            if ($ignoreEntry || $ignoreUser) {
                $this->handleAddIgnore($data, $entry, $existingUser, $ignoreUser);
                continue;
            }

            // If the user does not exist
            // Or it was deleted and then created again in the directory
            if (!isset($existingUser)) {
                $this->handleAddNew($data, $entry);
                continue;
            }

            // If the user exist but is already deleted
            if (isset($existingUser) && $existingUser->deleted) {
                $this->handleAddDeleted($data, $entry, $existingUser);
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
    protected function getUserFromData($data)
    {
        $existingUser = $this->Users->find()
            ->select(['id', 'username', 'active', 'deleted', 'created', 'modified'])
            ->where(['username' => $data['user']['username']])
            ->order(['Users.modified' => 'DESC'])
            ->first();
        if (!isset($existingUser) || empty($existingUser)) {
            $existingUser = null;
        }
        return $existingUser;
    }
}
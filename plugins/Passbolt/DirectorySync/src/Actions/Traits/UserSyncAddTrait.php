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

use App\Model\Entity\User;
use Cake\ORM\Entity;
use Passbolt\DirectorySync\Utility\ActionReport;
use Passbolt\DirectorySync\Model\Entity\DirectoryEntry;
use App\Error\Exception\ValidationException;
use Cake\Network\Exception\InternalErrorException;
use Passbolt\DirectorySync\Utility\SyncAction;
use Twig\Error\SyntaxError;

trait UserSyncAddTrait {

    /**
     * @param array $data
     * @param DirectoryEntry|null $entry
     */
    function handleAddIgnore(array $data, DirectoryEntry $entry = null)
    {
        if (isset($entry)) {
            // Delete dir entry if any, no need to keep ignored entries
            $this->DirectoryEntries->delete($entry);
            $this->DirectoryIgnore->create(['id' => $data['id'], 'foreign_model' => 'DirectoryEntry']);
        }
        $this->addReport(new ActionReport(self::USERS, self::CREATE, self::IGNORE, $data));
    }

    /**
     * @param array $data
     * @param DirectoryEntry|null $entry
     */
    function handleAddNew(array $data, DirectoryEntry $entry = null)
    {
        $user = null;
        $reportData = $data;
        try {
            $user = $this->Users->register($data['user']);
            $status = self::SUCCESS;
            $reportData = $user;
        } catch(ValidationException $exception) {
            $reportData = $exception->getEntity();
            $status = self::ERROR;
        } catch (InternalErrorException $exception) {
            $status = self::ERROR;
        }

        $this->DirectoryEntries->updateStatusOrCreate($data, $status, self::USERS, $user, $entry);
        $this->addReport(new ActionReport(self::USERS, self::CREATE, $status, $reportData));
    }

    /**
     * @param array $data
     * @param DirectoryEntry|null $entry
     */
    function handleAddExistDeleted(array $data, DirectoryEntry $entry = null, User $existingUser)
    {
        // if the user was created in ldap and then deleted in passbolt
        // do not try to recreate
        if ($data['directory_created']->lte($existingUser->modified)) {
            if (isset($entry)) {
                $this->DirectoryEntries->delete($entry);
            }
            $this->DirectoryIgnore->create(['id' => $existingUser->id, 'foreign_model' => self::USERS]);
            $this->addReport(new ActionReport(self::USERS, self::CREATE, self::IGNORE, $existingUser));
            return;
        }

        // if the user was delete in passbolt and then created in ldap
        // try to recreate
        $user = null;
        $reportData = $data;
        try {
            $user = $this->Users->register($data['user']);
            $status = self::SUCCESS;
            $reportData = $user;
        } catch(ValidationException $exception) {
            $reportData = $exception->getEntity();
            $status = self::ERROR;
        } catch (InternalErrorException $exception) {
            $status = self::ERROR;
        }
        // if it doesn't work, then ignore user
        if ($status === self::ERROR) {
            $status = self::IGNORE;
            if (isset($entry)) {
                $this->DirectoryEntries->delete($entry);
            }
            $this->DirectoryIgnore->create(['id' => $existingUser->id, 'foreign_model' => self::USERS]);
        } else {
            $this->DirectoryEntries->updateStatusOrCreate($data, $status, self::USERS, $user, $entry);
        }

        $this->addReport(new ActionReport(self::USERS, self::CREATE, $status, $reportData));
    }

    /**
     * @param array $data
     * @param DirectoryEntry|null $entry
     * @param User $existingUser
     */
    function handleAddExist(array $data, DirectoryEntry $entry = null, User $existingUser)
    {
        if (isset($entry) && (!isset($entry->foreign_key) || ($entry->foreign_key !== $existingUser->id))) {
            $this->DirectoryEntries->updateForeignKey($entry, $existingUser->id);
        }
        $this->addReport(new ActionReport(self::USERS, self::CREATE, self::SYNC, $data));
        $this->DirectoryEntries->updateStatusOrCreate($data, self::SUCCESS, self::USERS, $existingUser, $entry);
    }

    /**
     * @param array $data
     * @param DirectoryEntry $entry
     * @param User $existingUser
     */
    function handleAddDeleted(array $data, DirectoryEntry $entry = null, User $existingUser)
    {
        if (!isset($entry)) {
            $this->DirectoryEntries->updateStatusOrCreate($data, self::ERROR, self::USERS, $existingUser, $entry);
            $this->addReport(new ActionReport(self::USERS, self::CREATE, self::ERROR, $data));
        } else {
            $this->DirectoryEntries->delete($entry);
            $this->DirectoryIgnore->create(['id' => $existingUser->id, 'foreign_model' => self::USERS]);
            $this->addReport(new ActionReport(self::USERS, self::CREATE, self::IGNORE, $data));
        }
    }
}
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
use Passbolt\DirectorySync\Utility\ActionReport;
use Passbolt\DirectorySync\Model\Entity\DirectoryEntry;
use App\Error\Exception\ValidationException;
use Cake\Network\Exception\InternalErrorException;

trait UserSyncAddTrait {

    /**
     * @param array $data
     * @param DirectoryEntry|null $entry
     */
    function handleAddIgnore(array $data, DirectoryEntry $entry = null)
    {
        // Delete dir entry if any, no need to keep ignored entries
        if (isset($entry)) {
            $this->DirectoryEntries->delete($entry);
        }
        $this->addReport(new ActionReport(self::USERS, self::CREATE, self::IGNORE, $data));
    }

    /**
     * @param array $data
     * @param DirectoryEntry|null $entry
     */
    function handleAdd(array $data, DirectoryEntry $entry = null)
    {
        try {
            $user = $this->Users->register($data);
            $status = self::SUCCESS;
        } catch(ValidationException $exception) {
            $status = self::ERROR;
        } catch (InternalErrorException $exception) {
            $data = $exception; // TODO discuss format ErrorReport() ?
        }
        if (isset($entry) && $status === self::ERROR && $entry->status === self::ERROR) {
            // Second error in 2 sync, delete sync entry and ignore user
            $this->DirectoryEntries->delete($entry);
            $this->DirectoryIgnore->create(['id' => $user->id, 'model' => self::USERS]);
            $status = self::IGNORE;
        } else {
            $this->DirectoryEntries->updateStatusOrCreate($data, $status, self::USERS, $entry);
        }
        $this->addReport(new ActionReport(self::USERS, self::CREATE, $status, $data));
    }

    /**
     * @param array $data
     * @param DirectoryEntry|null $entry
     * @param User $existingUser
     */
    function handleAddExist(array $data, DirectoryEntry $entry = null, User $existingUser)
    {
        // Link the records
        if (isset($entry) && !isset($entry->user_id) || ($entry->user_id !== $existingUser->id)) {
            $this->DirectoryEntries->updateUserId($entry, $existingUser->id);
        }
        $this->DirectoryEntries->updateStatusOrCreate($data, self::SUCCESS, self::USERS, $entry);
        $this->addReport(new ActionReport(self::USERS, self::CREATE, self::SYNC, $data));
    }

    /**
     * @param DirectoryEntry $entry
     */
    function handleAddDeleted(DirectoryEntry $entry, User $existingUser)
    {
        $this->DirectoryEntries->delete($entry);
        $this->DirectoryIgnore->create(['id' => $existingUser->id, 'model' => self::USERS]);
    }
}
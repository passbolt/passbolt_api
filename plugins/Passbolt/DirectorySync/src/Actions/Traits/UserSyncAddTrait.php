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

use App\Error\Exception\ValidationException;
use App\Model\Entity\Role;
use App\Model\Entity\User;
use App\Utility\UserAccessControl;
use Cake\Network\Exception\InternalErrorException;
use Passbolt\DirectorySync\Model\Entity\DirectoryEntry;
use Passbolt\DirectorySync\Utility\ActionReport;
use Passbolt\DirectorySync\Utility\SyncError;
use Passbolt\DirectorySync\Utility\Alias;

trait UserSyncAddTrait {

    /**
     * @param array $data
     * @param DirectoryEntry|null $entry
     * @param User|null $existingUser
     * @param bool $ignoreUser
     */
    function handleAddIgnore(array $data, DirectoryEntry $entry = null, User $existingUser = null, bool $ignoreUser)
    {
        // do not overly report ignored record when there is nothing to do
        if ((isset($existingUser) && isset($entry->user) && !$existingUser->deleted)) {
            return;
        }
        if ($ignoreUser) {
            $msg = __('The user {0} was not synced because the passbolt user is marked to as be ignored.', $existingUser->username);
            $reportData = $this->DirectoryIgnore->get($existingUser->id);
        } else {
            $msg = __('The user {0} was not synced because the directory user is marked to as be ignored.', $data['user']['username']);
            $reportData = $this->DirectoryIgnore->get($existingUser->id);
        }
        $this->addReportItem(new ActionReport($msg,Alias::MODEL_USERS, Alias::ACTION_CREATE, Alias::STATUS_IGNORE, $reportData));
    }

    /**
     * @param array $data
     * @param DirectoryEntry|null $entry
     */
    function handleAddNew(array $data, DirectoryEntry $entry = null)
    {
        $status = Alias::STATUS_ERROR;
        $reportData = null;
        try {
            $accessControl = new UserAccessControl(Role::ADMIN, $this->defaultAdmin->id);
            $reportData = $user = $this->Users->register($data['user'], $accessControl);
            $this->DirectoryEntries->updateForeignKey($entry, $user->id);
            $status = Alias::STATUS_SUCCESS;
            $msg = __('The user {0} was successfully added to passbolt.', $data['user']['username']);
        } catch(ValidationException $exception) {
            $reportData = new SyncError($entry, $exception);
            $username = isset($data['user']['username']) ? $data['user']['username'] : 'undefined';
            $msg = __('The user {0} could not be added because of data validation issues.', $username);
        } catch (InternalErrorException $exception) {
            $reportData = new SyncError($entry, $exception);
            $msg = __('The user {0} could not be added because of an internal error. Please try again later.',
                $data['user']['username']);
        }
        $this->addReportItem(new ActionReport($msg, Alias::MODEL_USERS, Alias::ACTION_CREATE, $status, $reportData));
    }

    /**
     * @param array $data
     * @param DirectoryEntry|null $entry
     * @param User $existingUser
     */
    function handleAddDeleted(array $data, DirectoryEntry $entry = null, User $existingUser)
    {
        // if the user was created in ldap and then deleted in passbolt
        // do not try to recreate
        $status = Alias::STATUS_ERROR;
        if ($data['directory_created']->lt($existingUser->modified)) {
            $reportData = new SyncError($entry, null);
            $msg = __('The previously deleted user {0} was not re-added to passbolt.', $existingUser->username);
        } else {
            // if the user was delete in passbolt and then created in ldap
            // try to recreate
            $user = null;
            try {
                $accessControl = new UserAccessControl(Role::ADMIN, $this->defaultAdmin->id);
                $reportData = $user = $this->Users->register($data['user'], $accessControl);
                $this->DirectoryEntries->updateForeignKey($entry, $user->id);
                $status = Alias::STATUS_SUCCESS;
                $msg = __('The previously deleted user {0} was re-added to passbolt.', $existingUser->username);
            } catch(ValidationException $exception) {
                $reportData = new SyncError($entry, $exception);
                $msg = __('The deleted user {0} could not be re-added to passbolt because of validation errors.', $existingUser->username);
            } catch (InternalErrorException $exception) {
                $reportData = new SyncError($entry, $exception);
                $msg = __('The deleted user {0} could not be re-added to passbolt because of an internal error.', $existingUser->username);
            }
        }
        $this->addReportItem(new ActionReport($msg, Alias::MODEL_USERS, Alias::ACTION_CREATE, $status, $reportData));
    }

    /**
     * @param array $data
     * @param DirectoryEntry|null $entry
     * @param User $existingUser
     */
    function handleAddExist(array $data, DirectoryEntry $entry = null, User $existingUser)
    {
        // do not overly report already successfully synced users
        if (isset($entry) && !isset($entry->foreign_key)) {
            $this->DirectoryEntries->updateForeignKey($entry, $existingUser->id);
            $this->addReportItem(new ActionReport(
                __('The user {0} was mapped with an existing user in passbolt.', $existingUser->username),
                Alias::MODEL_USERS, Alias::ACTION_CREATE, Alias::STATUS_SYNC, $existingUser));
        }
    }
}
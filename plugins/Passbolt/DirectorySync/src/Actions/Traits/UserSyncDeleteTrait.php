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
use Cake\Network\Exception\InternalErrorException;
use Passbolt\DirectorySync\Model\Entity\DirectoryEntry;
use Passbolt\DirectorySync\Utility\ActionReport;
use Passbolt\DirectorySync\Utility\Alias;
use Passbolt\DirectorySync\Utility\SyncError;

trait UserSyncDeleteTrait {

    /**
     * @param DirectoryEntry $entry
     */
    protected function handleDeletedIgnoredEntry(DirectoryEntry $entry)
    {
        $this->DirectoryEntries->delete($entry);
    }

    /**
     * @param DirectoryEntry $entry
     */
    protected function handleDeletedIgnoredUser(DirectoryEntry $entry)
    {
        $this->DirectoryEntries->delete($entry);
        if (!$entry->user->deleted) {
            $reportData = $this->DirectoryIgnore->get($entry->user->id);
            $this->addReportItem(new ActionReport(
                __('The passbolt user {0} was not deleted because it is marked as to be ignored.',
                    $entry->user->username),
                Alias::MODEL_USERS, Alias::ACTION_DELETE, Alias::STATUS_IGNORE, $reportData));
        }
    }

    /**
     * @param DirectoryEntry $entry
     */
    protected function handleDeletedEntry(DirectoryEntry $entry)
    {
        $this->DirectoryEntries->delete($entry);
        if (isset($entry->user) && $entry->user->deleted) {
            $this->addReportItem(new ActionReport(
                __('The directory user {0} was already deleted in passbolt.', $entry->user->username),
                Alias::MODEL_USERS, Alias::ACTION_DELETE, Alias::STATUS_SYNC, $entry->user));
        }
    }

    /**
     * @param DirectoryEntry $entry
     */
    protected function handleNotPossibleDelete(DirectoryEntry $entry)
    {
        $errors = $entry->user->getErrors();
        if (isset($errors['id']['soleOwnerOfSharedResource'])) {
            $msg = __('The user {0} could not be deleted because they are the only owner of one or more passwords.',
                $entry->user->username);
        } else {
            $msg = __('The user {0} could not be deleted because they are the only manager of one or more groups.',
                $entry->user->username);
        }
        $data = new SyncError($entry, new ValidationException($msg, $entry->user));
        $this->addReportItem(new ActionReport($msg,Alias::MODEL_USERS, Alias::ACTION_DELETE, Alias::STATUS_ERROR, $data));
    }

    /**
     * @param DirectoryEntry $entry
     */
    protected function handleSuccessfulDelete(DirectoryEntry $entry)
    {
        $this->DirectoryEntries->delete($entry);
        $this->addReportItem(new ActionReport(
            __('The user {0} was successfully deleted.', $entry->user->username),
            Alias::MODEL_USERS, Alias::ACTION_DELETE, Alias::STATUS_SUCCESS, $entry->user));
    }

    /**
     * @param DirectoryEntry $entry
     * @param InternalErrorException $exception
     */
    protected function handleInternalErrorDelete(DirectoryEntry $entry, InternalErrorException $exception)
    {
        $data = new SyncError($entry, $exception);
        $this->addReportItem(new ActionReport(
            __('The user {0} could not be deleted because of an internal error. Please try again later.',
                $entry->user->username),
            Alias::MODEL_USERS, Alias::ACTION_DELETE, Alias::STATUS_ERROR, $data));
    }
}
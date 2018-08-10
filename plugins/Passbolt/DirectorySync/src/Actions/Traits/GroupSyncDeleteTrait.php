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

trait GroupSyncDeleteTrait {

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
    protected function handleDeletedIgnoredGroup(DirectoryEntry $entry)
    {
        $this->DirectoryEntries->delete($entry);
        if (!$entry->group->deleted) {
            $reportData = $this->DirectoryIgnore->get($entry->group->id);
            $this->addReport(new ActionReport(
                __('The passbolt group {0} was not deleted because it is marked as to be ignored.',
                    $entry->group->name),
                Alias::MODEL_GROUPS, Alias::ACTION_DELETE, Alias::STATUS_IGNORE, $reportData));
        }
    }

    /**
     * @param DirectoryEntry $entry
     */
    protected function handleDeletedEntry(DirectoryEntry $entry)
    {
        $this->DirectoryEntries->delete($entry);
        if (isset($entry->group) && $entry->group->deleted) {
            $this->addReport(new ActionReport(
                __('The directory user {0} was already deleted in passbolt.', $entry->group->name),
                Alias::MODEL_GROUPS, Alias::ACTION_DELETE, Alias::STATUS_SYNC, $entry->group));
        }
    }

    /**
     * @param DirectoryEntry $entry
     */
    protected function handleNotPossibleDelete(DirectoryEntry $entry)
    {
        $errors = $entry->group->getErrors();
        if (isset($errors['id']['soleOwnerOfSharedResource'])) {
            $msg = __('The group {0} could not be deleted because they are the only owner of one or more passwords.',
                $entry->group->name);
        }
        $data = new SyncError($entry, new ValidationException($msg, $entry->group));
        $this->addReport(new ActionReport($msg,Alias::MODEL_GROUPS, Alias::ACTION_DELETE, Alias::STATUS_ERROR, $data));
    }

    /**
     * @param DirectoryEntry $entry
     */
    protected function handleSuccessfulDelete(DirectoryEntry $entry)
    {
        $this->DirectoryEntries->delete($entry);
        $this->addReport(new ActionReport(
            __('The group {0} was successfully deleted.', $entry->group->name),
            Alias::MODEL_GROUPS, Alias::ACTION_DELETE, Alias::STATUS_SUCCESS, $entry->group));
    }

    /**
     * @param DirectoryEntry $entry
     * @param InternalErrorException $exception
     */
    protected function handleInternalErrorDelete(DirectoryEntry $entry, InternalErrorException $exception)
    {
        $data = new SyncError($entry, $exception);
        $this->addReport(new ActionReport(
            __('The group {0} could not be deleted because of an internal error. Please try again later.',
                $entry->group->name),
            Alias::MODEL_GROUPS, Alias::ACTION_DELETE, Alias::STATUS_ERROR, $data));
    }
}
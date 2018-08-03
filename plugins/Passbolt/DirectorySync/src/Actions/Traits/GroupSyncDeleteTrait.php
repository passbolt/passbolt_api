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
use Passbolt\DirectorySync\Utility\ActionReport;
use Passbolt\DirectorySync\Model\Entity\DirectoryEntry;
use Passbolt\DirectorySync\Utility\SyncAction;

trait GroupSyncDeleteTrait {

    /**
     * @param DirectoryEntry $entry
     */
    protected function handleDeletedIgnoredEntry(DirectoryEntry $entry)
    {
        $this->DirectoryEntries->delete($entry);
        if (isset($entry->group) && !empty($entry->group) && $entry->group->deleted == false) {
            $this->addReport(new ActionReport(self::GROUPS, self::DELETE, self::IGNORE, $entry));
        }
    }

    /**
     * @param DirectoryEntry $entry
     */
    protected function handleDeletedEntry(DirectoryEntry $entry)
    {
        $this->DirectoryEntries->delete($entry);
        if (isset($entry->group) && $entry->group->deleted && $entry->status !== self::SUCCESS) {
            $this->addReport(new ActionReport(self::GROUPS, self::DELETE, self::SYNC, $entry));
        }
    }

    /**
     * @param DirectoryEntry $entry
     */
    protected function handleNotPossibleDelete(DirectoryEntry $entry)
    {
        if ($entry->status !== self::ERROR) {
            $this->DirectoryEntries->updateStatus($entry, self::ERROR);
        }
        $this->addReport(new ActionReport(self::GROUPS, self::DELETE, self::ERROR, $entry->group));
    }

    /**
     * @param DirectoryEntry $entry
     */
    protected function handleSuccessfulDelete(DirectoryEntry $entry)
    {
        $this->DirectoryEntries->delete($entry);
        $this->addReport(new ActionReport(self::GROUPS, self::DELETE, self::SUCCESS, $entry->group));
    }

    /**
     * @param DirectoryEntry $entry
     */
    protected function handleErrorDelete(DirectoryEntry $entry)
    {
        $this->DirectoryEntries->updateStatus($entry, self::ERROR);
        $this->addReport(new ActionReport(self::GROUPS, self::DELETE, self::ERROR, $entry));
    }
}
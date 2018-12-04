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
use Cake\Utility\Inflector;
use Passbolt\DirectorySync\Actions\Reports\ActionReport;
use Passbolt\DirectorySync\Model\Entity\DirectoryEntry;
use Passbolt\DirectorySync\Utility\Alias;
use Passbolt\DirectorySync\Utility\SyncError;

trait SyncDeleteTrait
{
    /**
     * Handle ignored entries.
     * @param DirectoryEntry $entry entry
     * @return void
     */
    protected function handleDeletedIgnoredEntry(DirectoryEntry $entry)
    {
        $entity = $entry->getAssociatedEntity();
        $this->DirectoryEntries->delete($entry);
        if (!empty($entry->foreign_key) && isset($entity) && $entity->deleted == false) {
            $this->addReportItem(new ActionReport(
                __(
                    'The directory {0} {1} was not deleted because it is ignored.',
                    Inflector::singularize(strtolower(self::ENTITY_TYPE)),
                    $this->getEntityName($entity)
                ),
                self::ENTITY_TYPE,
                Alias::ACTION_DELETE,
                Alias::STATUS_IGNORE,
                $entry
            ));
        }
    }

    /**
     * Handle ignored entities.
     * @param DirectoryEntry $entry entry
     * @return void
     */
    protected function handleDeletedIgnoredEntity(DirectoryEntry $entry)
    {
        $entity = $entry->getAssociatedEntity();
        $this->DirectoryEntries->delete($entry);
        if (!$entity->deleted) {
            $reportData = $this->DirectoryIgnore->get($entity->id);
            $this->addReportItem(new ActionReport(
                __(
                    'The passbolt {0} {1} was not deleted because it is marked as to be ignored.',
                    Inflector::singularize(strtolower(self::ENTITY_TYPE)),
                    $this->getEntityName($entity)
                ),
                self::ENTITY_TYPE,
                Alias::ACTION_DELETE,
                Alias::STATUS_IGNORE,
                $reportData
            ));
        }
    }

    /**
     * Handle deleted entity.
     * @param DirectoryEntry $entry entry
     * @return void
     */
    protected function handleDeletedEntry(DirectoryEntry $entry)
    {
        $entity = $entry->getAssociatedEntity();
        $this->DirectoryEntries->delete($entry);
        if (isset($entity) && $entity->deleted) {
            $this->addReportItem(new ActionReport(
                __(
                    'The directory {0} {1} was already deleted in passbolt.',
                    Inflector::singularize(strtolower(self::ENTITY_TYPE)),
                    $this->getEntityName($entity)
                ),
                self::ENTITY_TYPE,
                Alias::ACTION_DELETE,
                Alias::STATUS_SYNC,
                $entity
            ));
        }
    }

    /**
     * Handle delete when it's not possible to delete.
     * @param DirectoryEntry $entry entry
     * @return void
     */
    protected function handleNotPossibleDelete(DirectoryEntry $entry)
    {
        $entity = $entry->getAssociatedEntity();
        $errors = $entity->getErrors();
        if (isset($errors['id']['soleOwnerOfSharedResource'])) {
            $msg = __(
                'The user {0} could not be deleted because they are the only owner of one or more passwords.',
                $this->getEntityName($entity)
            );
        } else {
            $msg = __(
                'The {0} {1} could not be deleted because they are the only manager of one or more groups.',
                Inflector::singularize(strtolower(self::ENTITY_TYPE)),
                $this->getEntityName($entity)
            );
        }
        $data = new SyncError($entry, new ValidationException($msg, $entity));
        $this->addReportItem(new ActionReport($msg, self::ENTITY_TYPE, Alias::ACTION_DELETE, Alias::STATUS_ERROR, $data));
    }

    /**
     * Handle a successful delete.
     * @param DirectoryEntry $entry entry
     * @return void
     */
    protected function handleSuccessfulDelete(DirectoryEntry $entry)
    {
        $entity = $entry->getAssociatedEntity();
        $this->DirectoryEntries->delete($entry);
        $this->addReportItem(new ActionReport(
            __(
                'The {0} {1} was successfully deleted.',
                Inflector::singularize(strtolower(self::ENTITY_TYPE)),
                $this->getEntityName($entity)
            ),
            self::ENTITY_TYPE,
            Alias::ACTION_DELETE,
            Alias::STATUS_SUCCESS,
            $entity
        ));
    }

    /**
     * Handle an internal error delete.
     * @param DirectoryEntry $entry entry
     * @param InternalErrorException $exception exception
     * @return void
     */
    protected function handleInternalErrorDelete(DirectoryEntry $entry, InternalErrorException $exception)
    {
        $entity = $entry->getAssociatedEntity();
        $data = new SyncError($entry, $exception);
        $this->addReportItem(new ActionReport(
            __(
                'The {0} {1} could not be deleted because of an internal error. Please try again later.',
                Inflector::singularize(strtolower(self::ENTITY_TYPE)),
                $this->getEntityName($entity)
            ),
            self::ENTITY_TYPE,
            Alias::ACTION_DELETE,
            Alias::STATUS_ERROR,
            $data
        ));
    }
}

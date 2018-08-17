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

use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use Passbolt\DirectorySync\Utility\Alias;
use Passbolt\DirectorySync\Model\Entity\DirectoryEntry;
use Cake\Core\Configure;
use Cake\Network\Exception\InternalErrorException;
use Passbolt\DirectorySync\Utility\ActionReport;
use Passbolt\DirectorySync\Utility\SyncError;
use App\Error\Exception\ValidationException;

trait SyncTrait {

    /**
     * @var array|mixed
     */
    public $entitiesToIgnore;

    /**
     * @var array|mixed
     */
    public $entriesToIgnore;


    function initialize($modelType = Alias::MODEL_USERS)
    {
        $this->entityType = Alias::MODEL_USERS;

        $this->entriesToIgnore = Hash::extract($this->DirectoryIgnore->find()
            ->select(['id'])
            ->where(['foreign_model' => Alias::MODEL_DIRECTORY_ENTRIES])
            ->all()
            ->toArray(), '{n}.id');

        $this->entitiesToIgnore = Hash::extract($this->DirectoryIgnore->find()
            ->select(['id'])
            ->where(['foreign_model' => $modelType])
            ->all()
            ->toArray(), '{n}.id');

        $this->directoryData = $this->directory->{'get'.Alias::MODEL_GROUPS}();
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
        if (!Configure::read('passbolt.plugins.directorySync.jobs.' . strtolower(self::ENTITY_TYPE) . '.delete')) {
            return;
        }

        $entriesId = Hash::extract($this->directoryData, '{n}.id');
        $this->DirectoryIgnore->cleanupHardDeletedEntities(SELF::ENTITY_TYPE);
        $entries = $this->DirectoryEntries->lookupEntriesForDeletion(SELF::ENTITY_TYPE, $entriesId);
        $this->DirectoryIgnore->cleanupHardDeletedDirectoryEntries($entriesId);
        $this->DirectoryRelations->cleanupHardDeletedUserGroups($entriesId);

        foreach ($entries as $entry) {
            $entity = $entry->getAssociatedEntity();

            // The directory entry is marked as to be ignored
            if (in_array($entry->id, $this->entriesToIgnore)) {
                $this->handleDeletedIgnoredEntry($entry);
                continue;
            }

            // The entity is marked as to be ignored
            if (isset($entity) && in_array($entity->id, $this->entitiesToIgnore)) {
                $this->handleDeletedIgnoredEntity($entry);
                continue;
            }

            // The user was already hard or soft deleted
            if (!isset($entity) || $entity->deleted) {
                $this->handleDeletedEntry($entry);
                continue;
            }

            try {
                if (!$this->{self::ENTITY_TYPE}->softDelete($entity)) {
                    // The entity cannot be deleted (for example: it is the sole owner of shared passwords)
                    $this->handleNotPossibleDelete($entry);
                } else {
                    // Entity was deleted
                    $this->handleSuccessfulDelete($entry);
                    //$this->handleGroupUsersDeleted($entry);
                }
            } catch (InternalErrorException $exception) {
                // The entity cannot be deleted (for example: database service is down)
                $this->handleInternalErrorDelete($entry, $exception);
            }
        }
    }

    /**
     * @param DirectoryEntry $entry
     */
    protected function handleDeletedIgnoredEntry(DirectoryEntry $entry)
    {
        $entity = $entry->getAssociatedEntity();
        $this->DirectoryEntries->delete($entry);
        if (!empty($entry->foreign_key) && isset($entity) && $entity->deleted == false) {
            $this->addReportItem(new ActionReport(
                __('The directory {0} {1} was not deleted because it is ignored.',
                    Inflector::singularize(strtolower(self::ENTITY_TYPE)),
                    $this->getEntityName($entity)),
                self::ENTITY_TYPE, Alias::ACTION_DELETE, Alias::STATUS_IGNORE, $entry));
        }
    }

    /**
     * @param DirectoryEntry $entry
     */
    protected function handleDeletedIgnoredEntity(DirectoryEntry $entry)
    {
        $entity = $entry->getAssociatedEntity();
        $this->DirectoryEntries->delete($entry);
        if (!$entity->deleted) {
            $reportData = $this->DirectoryIgnore->get($entity->id);
            $this->addReportItem(new ActionReport(
                __('The passbolt {0} {1} was not deleted because it is marked as to be ignored.',
                    Inflector::singularize(strtolower(self::ENTITY_TYPE)),
                    $this->getEntityName($entity)),
                self::ENTITY_TYPE, Alias::ACTION_DELETE, Alias::STATUS_IGNORE, $reportData));
        }
    }

    /**
     * @param DirectoryEntry $entry
     */
    protected function handleDeletedEntry(DirectoryEntry $entry)
    {
        $entity = $entry->getAssociatedEntity();
        $this->DirectoryEntries->delete($entry);
        if (isset($entity) && $entity->deleted) {
            $this->addReportItem(new ActionReport(
                __('The directory {0} {1} was already deleted in passbolt.',
                    Inflector::singularize(strtolower(self::ENTITY_TYPE)),
                    $this->getEntityName($entity)),
                self::ENTITY_TYPE, Alias::ACTION_DELETE, Alias::STATUS_SYNC, $entity));
        }
    }

    /**
     * @param DirectoryEntry $entry
     */
    protected function handleNotPossibleDelete(DirectoryEntry $entry)
    {
        $entity = $entry->getAssociatedEntity();
        $errors = $entity->getErrors();
        if (isset($errors['id']['soleOwnerOfSharedResource'])) {
            $msg = __('The user {0} could not be deleted because they are the only owner of one or more passwords.',
                $this->getEntityName($entity));
        } else {
            $msg = __('The {0} {1} could not be deleted because they are the only manager of one or more groups.',
                Inflector::singularize(strtolower(self::ENTITY_TYPE)),
                $this->getEntityName($entity));
        }
        $data = new SyncError($entry, new ValidationException($msg, $entity));
        $this->addReportItem(new ActionReport($msg, self::ENTITY_TYPE, Alias::ACTION_DELETE, Alias::STATUS_ERROR, $data));
    }

    /**
     * @param DirectoryEntry $entry
     */
    protected function handleSuccessfulDelete(DirectoryEntry $entry)
    {
        $entity = $entry->getAssociatedEntity();
        $this->DirectoryEntries->delete($entry);
        $this->addReportItem(new ActionReport(
            __('The {0} {1} was successfully deleted.',
                Inflector::singularize(strtolower(self::ENTITY_TYPE)),
                $this->getEntityName($entity)),
            self::ENTITY_TYPE, Alias::ACTION_DELETE, Alias::STATUS_SUCCESS, $entity));
    }

    /**
     * @param DirectoryEntry $entry
     * @param InternalErrorException $exception
     */
    protected function handleInternalErrorDelete(DirectoryEntry $entry, InternalErrorException $exception)
    {
        $entity = $entry->getAssociatedEntity();
        $data = new SyncError($entry, $exception);
        $this->addReportItem(new ActionReport(
            __('The {0} {1} could not be deleted because of an internal error. Please try again later.',
                Inflector::singularize(strtolower(self::ENTITY_TYPE)),
                $this->getEntityName($entity)),
            self::ENTITY_TYPE, Alias::ACTION_DELETE, Alias::STATUS_ERROR, $data));
    }

    protected function getEntityName($entity) {
        if (self::ENTITY_TYPE == Alias::MODEL_GROUPS) {
            return $entity->name;
        }
        return $entity->username;
    }
}
<?php
declare(strict_types=1);

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

use Cake\Http\Exception\InternalErrorException;
use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;
use Cake\Utility\Hash;
use Passbolt\DirectorySync\Utility\Alias;

trait SyncTrait
{
    /**
     * Store entities (users or groups) to ignore.
     *
     * @var array|mixed
     */
    public $entitiesToIgnore;

    /**
     * Store directoryEntries to ignore.
     *
     * @var array|mixed
     */
    public $entriesToIgnore;

    /**
     * Initialize data to perform the job.
     *
     * @param string $modelType type of model
     * @return void
     */
    public function initialize($modelType = Alias::MODEL_USERS)
    {
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

        $this->directoryData = $this->directory->{'get' . $modelType}();
        $this->formatDirectoryData();
    }

    /**
     * Format directoryData to be compatible with the expected format.
     *
     * Mainly modify the dates format to the expected format FrozenTime.
     *
     * @return void
     */
    public function formatDirectoryData()
    {
        foreach ($this->directoryData as $key => $data) {
            if (get_class($data['directory_created']) !== 'FrozenTime') {
                $this->directoryData[$key]['directory_created'] = new FrozenTime($data['directory_created']);
            }
            if (get_class($data['directory_modified']) !== 'FrozenTime') {
                $this->directoryData[$key]['directory_modified'] = new FrozenTime($data['directory_modified']);
            }
        }
    }

    /**
     * Handle the user/group deletion job
     *
     * Find all the directory entries that have been deleted and try to delete the associated users
     * If they are not already deleted, or marked as to be ignored
     *
     * @return void
     */
    public function processEntriesToDelete()
    {
        if (!$this->directoryOrgSettings->isSyncOperationEnabled(strtolower(self::ENTITY_TYPE), 'delete')) {
            return;
        }

        $entriesId = Hash::extract($this->directoryData, '{n}.id');
        $this->DirectoryIgnore->cleanupHardDeletedEntities(self::ENTITY_TYPE);
        $entries = $this->DirectoryEntries->lookupEntriesForDeletion(self::ENTITY_TYPE, $entriesId);
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

            // The entity was already hard or soft deleted
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
                    if (self::ENTITY_TYPE == Alias::MODEL_GROUPS) {
                        $this->handleGroupUsersDeleted($entry);
                    }
                }
            } catch (InternalErrorException $exception) {
                // The entity cannot be deleted (for example: database service is down)
                $this->handleInternalErrorDelete($entry, $exception);
            }
        }
    }

    /**
     * Handle the user/group creation job
     *
     * @return void
     */
    public function processEntriesToCreate()
    {
        foreach ($this->directoryData as $data) {
            // Find and patch (in case directory_name has changed), or create directory entries.
            $entry = $this->DirectoryEntries->updateOrCreate($data, self::ENTITY_TYPE);
            $associatedEntity = $entry->getAssociatedEntity();
            if ($entry === false) {
                continue;
            }
            if (!isset($associatedEntity)) {
                $existingEntity = $this->getEntityFromData($data);
            } else {
                $existingEntity = $associatedEntity;
            }

            // If directory entry or entity are marked as to be ignored
            $ignoreEntry = in_array($data['id'], $this->entriesToIgnore);
            $ignoreUser = (isset($existingEntity) && in_array($existingEntity->id, $this->entitiesToIgnore));
            if ($ignoreEntry || $ignoreUser) {
                $this->handleAddIgnore($data, $entry, $existingEntity, $ignoreUser);
                continue;
            }

            // If the entity does not exist
            // Or it was deleted and then created again in the directory
            if (!isset($existingEntity)) {
                $this->handleAddNew($data, $entry);
                continue;
            }

            // If the entity exist but is already deleted
            if (isset($existingEntity) && $existingEntity->deleted) {
                $this->handleAddDeleted($data, $entry, $existingEntity);
                continue;
            }

            // If the entity already exist and is not deleted, we update.
            $this->handleAddExist($data, $entry, $existingEntity);
        }
    }

    /**
     * Get entity name.
     * For a user it will return the username
     * For a group it will return the group name
     *
     * @param \Cake\ORM\Entity $entity entity
     * @return mixed
     */
    protected function getEntityName(Entity $entity)
    {
        if (self::ENTITY_TYPE == Alias::MODEL_GROUPS) {
            return $entity->name;
        }

        return $entity->username;
    }

    /**
     * Get name of group or user from directory data.
     * For a user it will return the username
     * For a group it will return the group name
     *
     * @param array $data data
     * @return mixed
     */
    protected function getNameFromData(array $data)
    {
        if (self::ENTITY_TYPE == Alias::MODEL_GROUPS) {
            return $data['group']['name'] ?? 'undefined';
        }

        return $data['user']['username'] ?? 'undefined';
    }

    /**
     * Get entity from data.
     *
     * @param array $data data
     * @return mixed
     */
    protected function getEntityFromData(array $data)
    {
        if (self::ENTITY_TYPE == Alias::MODEL_GROUPS) {
            return $this->getGroupFromData($data);
        }

        return $this->getUserFromData($data);
    }
}

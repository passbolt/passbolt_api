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

use Cake\ORM\Entity;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use Passbolt\DirectorySync\Utility\Alias;
use Passbolt\DirectorySync\Model\Entity\DirectoryEntry;
use Cake\Core\Configure;
use Cake\Network\Exception\InternalErrorException;
use Passbolt\DirectorySync\Utility\ActionReport;
use Passbolt\DirectorySync\Utility\SyncError;
use App\Error\Exception\ValidationException;
use App\Model\Entity\User;
use App\Model\Entity\Group;
use App\Utility\UserAccessControl;
use App\Model\Entity\Role;

trait SyncAddTrait {
    /**
     * @param array $data
     * @param DirectoryEntry|null $entry
     * @param Entity $existingEntity (User or Group)
     */
    function handleAddExist(array $data, DirectoryEntry $entry = null, Entity $existingEntity)
    {
        // Do not overly report already successfully synced entities
        if (isset($entry) && !isset($entry->foreign_key)) {
            // If entity in directory was created before the entity in the db, we update the field and send report.
            if ($data['directory_created']->lte($existingEntity->created)) {
                $this->DirectoryEntries->updateForeignKey($entry, $existingEntity->id);
                $this->addReportItem(new ActionReport(
                    __('The {0} {1} was mapped with an existing {0} in passbolt.',
                        Inflector::singularize(strtolower(self::ENTITY_TYPE)),
                        $this->getEntityName($existingEntity)),
                    self::ENTITY_TYPE, Alias::ACTION_CREATE, Alias::STATUS_SYNC, $existingEntity));
            } else {
                // Else, if entity in directory was created after entity in db. We don't sync. There is an overlapse.
                // Later on, we'll introduce a mechanism to fix this manually.
                $msg =  __('The {0} {1} could not be mapped with an existing {0} in passbolt because it was created after.',
                    Inflector::singularize(strtolower(self::ENTITY_TYPE)),
                    $this->getEntityName($existingEntity));
                $reportData = new SyncError($entry, new \Exception($msg));
                $this->addReportItem(new ActionReport(
                    $msg,
                    self::ENTITY_TYPE, Alias::ACTION_CREATE, Alias::STATUS_ERROR, $reportData));
            }
        }

        // If it's a group. We need to process the group users.
        if (self::ENTITY_TYPE == Alias::MODEL_GROUPS) {
            $this->handleGroupUsersEdit($data, $entry, $existingEntity);
        }
    }

    /**
     * @param array $data
     * @param DirectoryEntry|null $entry
     * @param Entity|null $existingEntity
     * @param bool $ignoreEntity
     */
    function handleAddIgnore(array $data, DirectoryEntry $entry = null, Entity $existingEntity = null, bool $ignoreEntity)
    {
        $associatedEntity = $entry->getAssociatedEntity();
        // do not overly report ignored record when there is nothing to do
        if ((isset($existingEntity) && isset($associatedEntity) && !$existingEntity->deleted)) {
            return;
        }
        if ($ignoreEntity) {
            $msg = __('The {0} {1} was not synced because the passbolt {0} is marked as to be ignored.',
                Inflector::singularize(strtolower(self::ENTITY_TYPE)),
                $this->getEntityName($existingEntity));
            $reportData = $this->DirectoryIgnore->get($existingEntity->id);
        } else {
            $msg = __('The {0} {1} was not synced because the directory {0} is marked as to be ignored.',
                Inflector::singularize(strtolower(self::ENTITY_TYPE)),
                $this->getNameFromData($data));
            $reportData = $this->DirectoryIgnore->get($entry->id);
        }
        $this->addReportItem(new ActionReport($msg, self::ENTITY_TYPE, Alias::ACTION_CREATE, Alias::STATUS_IGNORE, $reportData));
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
            $reportData = $entity = $this->createEntity($data, $entry);
            $status = Alias::STATUS_SUCCESS;
            $msg = __('The {0} {1} was successfully added to passbolt.',
                Inflector::singularize(strtolower(self::ENTITY_TYPE)),
                $this->getNameFromData($data));
        } catch(ValidationException $exception) {
            $this->DirectoryEntries->updateForeignKey($entry, null);
            $reportData = new SyncError($entry, $exception);
            $msg = __('The {0} {1} could not be added because of data validation issues.',
                Inflector::singularize(strtolower(self::ENTITY_TYPE)),
                $this->getNameFromData($data));
        } catch (InternalErrorException $exception) {
            $this->DirectoryEntries->updateForeignKey($entry, null);
            $reportData = new SyncError($entry, $exception);
            $msg = __('The {0} {1} could not be added because of an internal error. Please try again later.',
                Inflector::singularize(strtolower(self::ENTITY_TYPE)),
                $this->getNameFromData($data));
        }
        $this->addReportItem(new ActionReport($msg, self::ENTITY_TYPE, Alias::ACTION_CREATE, $status, $reportData));

        if ($status == Alias::STATUS_SUCCESS && self::ENTITY_TYPE == Alias::MODEL_GROUPS) {
            $this->handleGroupUsersAfterGroupCreate($data, $entity);
        }
    }

    /**
     * @param array $data
     * @param DirectoryEntry|null $entry
     * @param Entity $existingEntity
     */
    function handleAddDeleted(array $data, DirectoryEntry $entry = null, Entity $existingEntity)
    {
        // if the entity was created in ldap and then deleted in passbolt
        // do not try to recreate
        $status = Alias::STATUS_ERROR;
        if ($data['directory_created']->lt($existingEntity->modified)) {
            $this->DirectoryEntries->updateForeignKey($entry, null);
            $reportData = new SyncError($entry, null);
            $msg = __('The previously deleted {0} {1} was not re-added to passbolt.',
                Inflector::singularize(strtolower(self::ENTITY_TYPE)),
                $this->getEntityName($existingEntity));
        } else {
            // if the entity was delete in passbolt and then created in ldap
            // try to recreate
            $entity = null;
            try {
                $reportData = $entity = $this->createEntity($data, $entry);
                $status = Alias::STATUS_SUCCESS;
                $msg = __('The previously deleted {0} {1} was re-added to passbolt.',
                    Inflector::singularize(strtolower(self::ENTITY_TYPE)),
                    $this->getEntityName($existingEntity));
            } catch(ValidationException $exception) {
                $reportData = new SyncError($entry, $exception);
                $msg = __('The deleted {0} {1} could not be re-added to passbolt because of validation errors.',
                    Inflector::singularize(strtolower(self::ENTITY_TYPE)),
                    $this->getEntityName($existingEntity));
            } catch (InternalErrorException $exception) {
                $reportData = new SyncError($entry, $exception);
                $msg = __('The deleted {0} {1} could not be re-added to passbolt because of an internal error.',
                    Inflector::singularize(strtolower(self::ENTITY_TYPE)),
                    $this->getEntityName($existingEntity));
            }
        }
        $this->addReportItem(new ActionReport($msg, self::ENTITY_TYPE, Alias::ACTION_CREATE, $status, $reportData));

        if ($status == Alias::STATUS_SUCCESS && self::ENTITY_TYPE == Alias::MODEL_GROUPS) {
            $this->handleGroupUsersAfterGroupCreate($data, $entity);
        }
    }

    public function createEntity(array $data, DirectoryEntry $entry) {
        $accessControl = new UserAccessControl(Role::ADMIN, $this->defaultAdmin->id);
        if (self::ENTITY_TYPE == Alias::MODEL_GROUPS) {
            // Define default admin for group.
            $data['group']['groups_users'][] = [
                'user_id' => $this->defaultGroupAdmin->id,
                'is_admin' => true,
            ];
            // Create.
            $entity = $this->Groups->create($data['group'], $accessControl);
        } else {
            $entity = $this->Users->register($data['user'], $accessControl);
        }

        $this->DirectoryEntries->updateForeignKey($entry, $entity->id);

        return $entity;
    }
}
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
namespace Passbolt\DirectorySync\Actions;

use App\Error\Exception\ValidationException;
use App\Model\Dto\EntitiesChangesDto;
use App\Model\Entity\Role;
use App\Model\Entity\Secret;
use App\Model\Table\UsersTable;
use App\Service\Resources\ResourcesExpireResourcesServiceInterface;
use Cake\Http\Exception\InternalErrorException;
use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Table;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use Cake\Validation\Validation;
use Passbolt\DirectorySync\Actions\Reports\ActionReport;
use Passbolt\DirectorySync\Actions\Reports\ActionReportCollection;
use Passbolt\DirectorySync\Model\Entity\DirectoryEntry;
use Passbolt\DirectorySync\Model\Entity\DirectoryReport;
use Passbolt\DirectorySync\Model\Table\DirectoryEntriesTable;
use Passbolt\DirectorySync\Model\Table\DirectoryIgnoreTable;
use Passbolt\DirectorySync\Model\Table\DirectoryRelationsTable;
use Passbolt\DirectorySync\Model\Table\DirectoryReportsTable;
use Passbolt\DirectorySync\Utility\Alias;
use Passbolt\DirectorySync\Utility\DirectoryFactory;
use Passbolt\DirectorySync\Utility\DirectoryInterface;
use Passbolt\DirectorySync\Utility\DirectoryOrgSettings;
use Passbolt\DirectorySync\Utility\SyncError;

/**
 * Directory factory class
 *
 * @package App\Utility
 */
abstract class SyncAction
{
    use LocatorAwareTrait;

    private ?string $parentId;
    /**
     * @var array|\Cake\Datasource\EntityInterface|mixed|null
     */
    protected $defaultAdmin;

    protected DirectoryOrgSettings $directoryOrgSettings;
    /**
     * @var \Passbolt\DirectorySync\Test\Utility\TestDirectory|\Passbolt\DirectorySync\Utility\LdapDirectory
     */
    private $directory;
    /**
     * @var array|mixed
     */
    protected $directoryData;

    private bool $dryRun = false;

    private bool $isPartOfAllSync = false;

    private ActionReportCollection $summary;

    private DirectoryReport $report;

    protected UsersTable $Users;

    public DirectoryEntriesTable $DirectoryEntries;

    private DirectoryIgnoreTable $DirectoryIgnore;

    public DirectoryRelationsTable $DirectoryRelations;

    private DirectoryReportsTable $DirectoryReports;

    protected ResourcesExpireResourcesServiceInterface $resourcesExpireResourcesService;

    protected EntitiesChangesDto $entitiesChangesDto;

    /**
     * Store entities (users or groups) to ignore.
     *
     * @var array|mixed
     */
    private $entitiesToIgnore;

    /**
     * Store directoryEntries to ignore.
     *
     * @var array|mixed
     */
    private $entriesToIgnore;

    /**
     * Get the entity type being synchronized, Users or Groups
     *
     * @return string
     */
    abstract protected function getEntityType(): string;

    /**
     * Get entity name.
     * For a user it will return the username
     * For a group it will return the group name
     *
     * @param \Cake\ORM\Entity $entity entity
     * @return string
     */
    abstract protected function getEntityName(Entity $entity): string;

    /**
     * Create entity
     *
     * @param array $data data
     * @param \Passbolt\DirectorySync\Model\Entity\DirectoryEntry $entry entry
     * @return \App\Model\Entity\User|\App\Model\Entity\Group
     */
    abstract protected function createEntity(array $data, DirectoryEntry $entry): Entity;

    /**
     * Get name of group or user from directory data.
     * For a user it will return the username
     * For a group it will return the group name
     *
     * @param array $data data
     * @return string
     */
    abstract protected function getNameFromData(array $data): string;

    /**
     * @return \App\Model\Table\UsersTable|\App\Model\Table\GroupsTable
     */
    abstract protected function getTable(): Table;

    /**
     * Get entity from data.
     *
     * @param array $data data
     * @return \App\Model\Entity\User|\App\Model\Entity\Group|null
     */
    abstract protected function getEntityFromData(array $data): ?Entity;

    /**
     * @param array $data data
     * @param \App\Model\Entity\User|\App\Model\Entity\Group $existingEntity existing entity
     * @return void
     */
    abstract protected function handleUpdate(array $data, Entity $existingEntity): void;

    /**
     * SyncAction constructor.
     *
     * @param \App\Service\Resources\ResourcesExpireResourcesServiceInterface $resourcesExpireResourcesService expire resource service
     * @param string|null $parentId parent id
     * @throws \Exception if no directory configuration is present
     */
    public function __construct(
        ResourcesExpireResourcesServiceInterface $resourcesExpireResourcesService,
        ?string $parentId = null
    ) {
        $this->directoryOrgSettings = DirectoryOrgSettings::get();
        $this->directory = DirectoryFactory::get($this->directoryOrgSettings);
        $this->resourcesExpireResourcesService = $resourcesExpireResourcesService;

        /** @phpstan-ignore-next-line */
        $this->DirectoryEntries = $this->fetchTable('Passbolt/DirectorySync.DirectoryEntries');
        /** @phpstan-ignore-next-line */
        $this->DirectoryIgnore = $this->fetchTable('Passbolt/DirectorySync.DirectoryIgnore');
        /** @phpstan-ignore-next-line */
        $this->DirectoryRelations = $this->fetchTable('Passbolt/DirectorySync.DirectoryRelations');
        /** @phpstan-ignore-next-line */
        $this->DirectoryReports = $this->fetchTable('Passbolt/DirectorySync.DirectoryReports');
        /** @phpstan-ignore-next-line */
        $this->Users = $this->fetchTable('Users');
        $this->summary = new ActionReportCollection();
        $this->defaultAdmin = $this->getDefaultAdmin();
        if (empty($this->defaultAdmin)) {
            throw new \Exception('Configuration issue. A default admin user cannot be found.');
        }
        if (isset($parentId) && !Validation::uuid($parentId)) {
            throw new \Exception('The parent task identifier should be a valid UUID.');
        }
        $this->parentId = $parentId;
    }

    /**
     * Execute sync.
     * - Delete all entities that can be deleted
     * - Create all entities that can be created
     * - Generate report
     *
     * @return \Passbolt\DirectorySync\Actions\Reports\ActionReportCollection
     */
    public function execute(): ActionReportCollection
    {
        if ($this->isDryRun()) {
            $conn = $this->Users->getConnection();
            $conn->begin();
            $conn->transactional(function () {
                $this->_execute();
            });
            $conn->rollback();
        } else {
            $this->_execute();
        }

        return $this->getSummary();
    }

    /**
     * Execute sync.
     *
     * @return void
     */
    protected function _execute(): void
    {
        $this->beforeExecute();
        $this->processEntriesToDelete();
        $this->processEntriesToCreate();
        $this->afterExecute();
    }

    /**
     * Initialize data to perform the job.
     *
     * @return void
     */
    protected function initialize(): void
    {
        $modelType = $this->getEntityType();

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
        $this->entitiesChangesDto = new EntitiesChangesDto();
    }

    /**
     * Format directoryData to be compatible with the expected format.
     *
     * Mainly modify the dates format to the expected format FrozenTime.
     *
     * @return void
     */
    private function formatDirectoryData()
    {
        foreach ($this->directoryData as $key => $data) {
            if (get_class($data['directory_created']) !== FrozenTime::class) {
                $this->directoryData[$key]['directory_created'] = new FrozenTime($data['directory_created']);
            }
            if (get_class($data['directory_modified']) !== FrozenTime::class) {
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
    private function processEntriesToDelete(): void
    {
        if (!$this->directoryOrgSettings->isSyncOperationEnabled($this->getEntityType(), 'delete')) {
            return;
        }

        $entriesId = Hash::extract($this->directoryData, '{n}.id');
        $this->DirectoryIgnore->cleanupHardDeletedEntities($this->getEntityType());
        $entries = $this->DirectoryEntries->lookupEntriesForDeletion($this->getEntityType(), $entriesId);
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

            // The entity was already hard or soft deleted or disabled
            if (!isset($entity) || $this->isDeletedOrDisabled($entity)) {
                $this->handleDeletedEntry($entry);
                continue;
            }

            try {
                $entitiesChanges = $this->deleteOrDisableEntity($entity);
                if (!$entitiesChanges) {
                    // The entity cannot be deleted (for example: it is the sole owner of shared passwords)
                    $this->handleNotPossibleDelete($entry);
                } else {
                    // Entity was deleted
                    $this->handleSuccessfulDelete($entry, $entitiesChanges);
                }
            } catch (InternalErrorException $exception) {
                // The entity cannot be deleted (for example: database service is down)
                $this->handleInternalErrorDelete($entry, $exception);
            }
        }
    }

    /**
     * Handle ignored entities.
     *
     * @param \Passbolt\DirectorySync\Model\Entity\DirectoryEntry $entry entry
     * @return void
     */
    private function handleDeletedIgnoredEntity(DirectoryEntry $entry)
    {
        $entity = $entry->getAssociatedEntity();
        $this->DirectoryEntries->delete($entry);
        if (!$entity->deleted) {
            $reportData = $this->DirectoryIgnore->get($entity->id);
            $this->addReportItem(new ActionReport(
                __(
                    'The passbolt {0} {1} was not deleted because it is marked as to be ignored.',
                    $this->getSingularLoweredEntityType(),
                    $this->getEntityName($entity)
                ),
                $this->getEntityType(),
                Alias::ACTION_DELETE,
                Alias::STATUS_IGNORE,
                $reportData
            ));
        }
    }

    /**
     * Handle ignored entries.
     *
     * @param \Passbolt\DirectorySync\Model\Entity\DirectoryEntry $entry entry
     * @return void
     */
    private function handleDeletedIgnoredEntry(DirectoryEntry $entry): void
    {
        $entity = $entry->getAssociatedEntity();
        $this->DirectoryEntries->delete($entry);
        if (!empty($entry->foreign_key) && isset($entity) && $entity->deleted == false) {
            $this->addReportItem(new ActionReport(
                __(
                    'The directory {0} {1} was not deleted because it is ignored.',
                    $this->getSingularLoweredEntityType(),
                    $this->getEntityName($entity)
                ),
                $this->getEntityType(),
                Alias::ACTION_DELETE,
                Alias::STATUS_IGNORE,
                $entry
            ));
        }
    }

    /**
     * Handle deleted entity.
     *
     * @param \Passbolt\DirectorySync\Model\Entity\DirectoryEntry $entry entry
     * @return void
     */
    private function handleDeletedEntry(DirectoryEntry $entry): void
    {
        $entity = $entry->getAssociatedEntity();
        $this->DirectoryEntries->delete($entry);
        if (!isset($entity)) {
            return;
        }
        if ($this->directoryOrgSettings->isDeleteUserBehaviorDisable()) {
            $msg = __(
                'The directory {0} {1} was already suspended in passbolt.',
                $this->getSingularLoweredEntityType(),
                $this->getEntityName($entity)
            );
        } else {
            $msg = __(
                'The directory {0} {1} was already deleted in passbolt.',
                $this->getSingularLoweredEntityType(),
                $this->getEntityName($entity)
            );
        }
        if ($this->isDeletedOrDisabled($entity)) {
            $this->addReportItem(new ActionReport(
                $msg,
                $this->getEntityType(),
                Alias::ACTION_DELETE,
                Alias::STATUS_SYNC,
                $entity
            ));
        }
    }

    /**
     * Handle delete when it's not possible to delete.
     *
     * @param \Passbolt\DirectorySync\Model\Entity\DirectoryEntry $entry entry
     * @return void
     */
    private function handleNotPossibleDelete(DirectoryEntry $entry)
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
                $this->getSingularLoweredEntityType(),
                $this->getEntityName($entity)
            );
        }
        $data = new SyncError($entry, new ValidationException($msg, $entity));
        $r = new ActionReport($msg, $this->getEntityType(), Alias::ACTION_DELETE, Alias::STATUS_ERROR, $data);
        $this->addReportItem($r);
    }

    /**
     * Handle a successful delete.
     *
     * @param \Passbolt\DirectorySync\Model\Entity\DirectoryEntry $entry entry
     * @param \App\Model\Dto\EntitiesChangesDto $entitiesChangesDto entity changes
     * @return void
     */
    protected function handleSuccessfulDelete(DirectoryEntry $entry, EntitiesChangesDto $entitiesChangesDto): void
    {
        $entity = $entry->getAssociatedEntity();
        if ($this->directoryOrgSettings->isDeleteUserBehaviorDisable()) {
            $msg = __(
                'The {0} {1} was successfully suspended.',
                $this->getSingularLoweredEntityType(),
                $this->getEntityName($entity)
            );
        } else {
            $msg = __(
                'The {0} {1} was successfully deleted.',
                $this->getSingularLoweredEntityType(),
                $this->getEntityName($entity)
            );
        }
        $this->DirectoryEntries->delete($entry);
        $this->addReportItem(new ActionReport(
            $msg,
            $this->getEntityType(),
            Alias::ACTION_DELETE,
            Alias::STATUS_SUCCESS,
            $entity
        ));
        $this->entitiesChangesDto->merge($entitiesChangesDto);
    }

    /**
     * Handle an internal error delete.
     *
     * @param \Passbolt\DirectorySync\Model\Entity\DirectoryEntry $entry entry
     * @param \Cake\Http\Exception\InternalErrorException $exception exception
     * @return void
     */
    private function handleInternalErrorDelete(DirectoryEntry $entry, InternalErrorException $exception)
    {
        $entity = $entry->getAssociatedEntity();
        if ($this->directoryOrgSettings->isDeleteUserBehaviorDisable()) {
            $msg = __(
                'The {0} {1} could not be suspended because of an internal error. Please try again later.',
                $this->getSingularLoweredEntityType(),
                $this->getEntityName($entity)
            );
        } else {
            $msg = __(
                'The {0} {1} could not be deleted because of an internal error. Please try again later.',
                $this->getSingularLoweredEntityType(),
                $this->getEntityName($entity)
            );
        }
        $data = new SyncError($entry, $exception);
        $this->addReportItem(new ActionReport(
            $msg,
            $this->getEntityType(),
            Alias::ACTION_DELETE,
            Alias::STATUS_ERROR,
            $data
        ));
    }

    /**
     * Handle the user/group creation job
     *
     * @return void
     */
    private function processEntriesToCreate()
    {
        $isSyncOperationOnCreateEnabled = $this->directoryOrgSettings
            ->isSyncOperationEnabled($this->getEntityType(), 'create');

        foreach ($this->directoryData as $data) {
            // Find and patch (in case directory_name has changed), or create directory entries.
            $entry = $this->DirectoryEntries->updateOrCreate($data, $this->getEntityType());
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
                if ($isSyncOperationOnCreateEnabled) {
                    $this->handleAddIgnore($data, $entry, $existingEntity, $ignoreUser);
                }
                continue;
            }

            // If the entity does not exist
            // Or it was deleted and then created again in the directory
            if (!isset($existingEntity)) {
                if ($isSyncOperationOnCreateEnabled) {
                    $this->handleAddNew($data, $entry);
                }
                continue;
            }

            // If the entity exist but is already deleted or disabled
            if ($this->isDeletedOrDisabled($existingEntity)) {
                if ($isSyncOperationOnCreateEnabled) {
                    $this->handleAddDeleted($data, $entry, $existingEntity);
                }
                continue;
            }

            // If the entity already exist and is not deleted, we update.
            $this->handleAddExist($data, $entry, $existingEntity);
        }
    }

    /**
     * Handle add ignore
     *
     * @param array $data data
     * @param \Passbolt\DirectorySync\Model\Entity\DirectoryEntry|null $entry entry
     * @param \Cake\ORM\Entity|null $existingEntity existingEntity
     * @param bool $ignoreEntity ignoreEntity
     * @return void
     */
    private function handleAddIgnore(
        array $data,
        ?DirectoryEntry $entry,
        ?Entity $existingEntity,
        bool $ignoreEntity
    ): void {
        $associatedEntity = $entry->getAssociatedEntity();
        // do not overly report ignored record when there is nothing to do
        if ((isset($existingEntity) && isset($associatedEntity) && !$existingEntity->get('deleted'))) {
            return;
        }
        if ($ignoreEntity) {
            $msg = __(
                'The {0} {1} was not synced because the passbolt {0} is marked as to be ignored.',
                $this->getSingularLoweredEntityType(),
                $this->getEntityName($existingEntity)
            );
            $reportData = $this->DirectoryIgnore->get($existingEntity->id);
        } else {
            $msg = __(
                'The {0} {1} was not synced because the directory {0} is marked as to be ignored.',
                $this->getSingularLoweredEntityType(),
                $this->getNameFromData($data)
            );
            $reportData = $this->DirectoryIgnore->get($entry->id);
        }
        $r = new ActionReport($msg, $this->getEntityType(), Alias::ACTION_CREATE, Alias::STATUS_IGNORE, $reportData);
        $this->addReportItem($r);
    }

    /**
     * Handle add new
     *
     * @param array $data data
     * @param \Passbolt\DirectorySync\Model\Entity\DirectoryEntry|null $entry entry
     * @return \App\Model\Entity\User|\App\Model\Entity\Group|null
     */
    protected function handleAddNew(array $data, ?DirectoryEntry $entry = null): ?Entity
    {
        $status = Alias::STATUS_ERROR;
        $reportData = null;
        $entity = null;
        try {
            $reportData = $entity = $this->createEntity($data, $entry);
            $status = Alias::STATUS_SUCCESS;
            $msg = __(
                'The {0} {1} was successfully added to passbolt.',
                $this->getSingularLoweredEntityType(),
                $this->getNameFromData($data)
            );
        } catch (ValidationException $exception) {
            $this->DirectoryEntries->updateForeignKey($entry, null);
            $reportData = new SyncError($entry, $exception);
            $msg = __(
                'The {0} {1} could not be added because of data validation issues.',
                $this->getSingularLoweredEntityType(),
                $this->getNameFromData($data)
            );
        } catch (InternalErrorException $exception) {
            $this->DirectoryEntries->updateForeignKey($entry, null);
            $reportData = new SyncError($entry, $exception);
            $msg = __(
                'The {0} {1} could not be added because of an internal error. Please try again later.',
                $this->getSingularLoweredEntityType(),
                $this->getNameFromData($data)
            );
        }
        $this->addReportItem(
            new ActionReport($msg, $this->getEntityType(), Alias::ACTION_CREATE, $status, $reportData)
        );

        return $entity;
    }

    /**
     * Handle add deleted
     *
     * @param array $data data
     * @param \Passbolt\DirectorySync\Model\Entity\DirectoryEntry $entry entry
     * @param \App\Model\Entity\User|\App\Model\Entity\Group $existingEntity existingEntity
     * @return \App\Model\Entity\User|\App\Model\Entity\Group|null
     */
    protected function handleAddDeleted(array $data, DirectoryEntry $entry, Entity $existingEntity): ?Entity
    {
        // if the entity was created in ldap and then deleted/disabled in passbolt
        // do not try to recreate
        $status = Alias::STATUS_ERROR;
        $entity = null;
        $existingEntityIsDisabledAndNotDeleted = $this->isDeletedOrDisabled($existingEntity) &&
            !$existingEntity->isDeleted();
        if ($data['directory_created']->lessThan($existingEntity->get('modified'))) {
            $this->DirectoryEntries->updateForeignKey($entry, null);
            $reportData = new SyncError($entry, null);
            if ($existingEntityIsDisabledAndNotDeleted) {
                $msg = __(
                    'The previously suspended {0} {1} was not unsuspended.',
                    $this->getSingularLoweredEntityType(),
                    $this->getEntityName($existingEntity)
                );
            } else {
                $msg = __(
                    'The previously deleted {0} {1} was not re-added to passbolt.',
                    $this->getSingularLoweredEntityType(),
                    $this->getEntityName($existingEntity)
                );
            }
        } elseif ($existingEntityIsDisabledAndNotDeleted) {
            // Case when entity was suspended because it was deleted in ldap previously, now it is created again with same DN in ldap so we enable (un-suspend) it
            $this->getTable()->updateAll([
                'disabled' => null,
                'modified' => FrozenTime::now(),
            ], ['id' => $existingEntity->id]);
            $reportData = $this->getTable()->get($existingEntity->id);
            $status = Alias::STATUS_SUCCESS;
            $msg = __(
                'The previously suspended {0} {1} was unsuspended.',
                $this->getSingularLoweredEntityType(),
                $this->getEntityName($existingEntity)
            );
        } else {
            // if the entity was deleted in passbolt and then created in ldap
            // try to recreate
            try {
                $reportData = $entity = $this->createEntity($data, $entry);
                $status = Alias::STATUS_SUCCESS;
                $msg = __(
                    'The previously deleted {0} {1} was re-added to passbolt.',
                    $this->getSingularLoweredEntityType(),
                    $this->getEntityName($existingEntity)
                );
            } catch (ValidationException $exception) {
                $reportData = new SyncError($entry, $exception);
                $msg = __(
                    'The deleted {0} {1} could not be re-added to passbolt because of validation errors.',
                    $this->getSingularLoweredEntityType(),
                    $this->getEntityName($existingEntity)
                );
            } catch (InternalErrorException $exception) {
                $reportData = new SyncError($entry, $exception);
                $msg = __(
                    'The deleted {0} {1} could not be re-added to passbolt because of an internal error.',
                    $this->getSingularLoweredEntityType(),
                    $this->getEntityName($existingEntity)
                );
            }
        }
        $this->addReportItem(
            new ActionReport($msg, $this->getEntityType(), Alias::ACTION_CREATE, $status, $reportData)
        );

        return $entity;
    }

    /**
     * Handle add exist.
     *
     * @param array $data data
     * @param \Passbolt\DirectorySync\Model\Entity\DirectoryEntry|null $entry entry
     * @param \App\Model\Entity\User|\App\Model\Entity\Group $existingEntity (User or Group)
     * @return void
     */
    private function handleAddExist(array $data, ?DirectoryEntry $entry, Entity $existingEntity)
    {
        // Do not overly report already successfully synced entities
        if (isset($entry) && !isset($entry->foreign_key)) {
            // If entity in directory was created before the entity in the db, we update the field and send report.
            if ($data['directory_created']->lessthanOrEquals($existingEntity->get('created'))) {
                $this->DirectoryEntries->updateForeignKey($entry, $existingEntity->id);
                $this->addReportItem(new ActionReport(
                    __(
                        'The {0} {1} was mapped with an existing {0} in passbolt.',
                        $this->getSingularLoweredEntityType(),
                        $this->getEntityName($existingEntity)
                    ),
                    $this->getEntityType(),
                    Alias::ACTION_CREATE,
                    Alias::STATUS_SYNC,
                    $existingEntity
                ));
            } else {
                // Else, if entity in directory was created after entity in db. We don't sync. There is an overlap.
                // Later on, we'll introduce a mechanism to fix this manually.
                $msg = __(
                    'The {0} {1} could not be mapped with an existing {0} in passbolt because it was created after.',
                    $this->getSingularLoweredEntityType(),
                    $this->getEntityName($existingEntity)
                );
                $reportData = new SyncError($entry, new \Exception($msg));
                $this->addReportItem(new ActionReport(
                    $msg,
                    $this->getEntityType(),
                    Alias::ACTION_CREATE,
                    Alias::STATUS_ERROR,
                    $reportData
                ));

                return;
            }
        }

        if ($this->directoryOrgSettings->isSyncOperationEnabled($this->getEntityType(), 'update')) {
            $this->handleUpdate($data, $existingEntity);
        }
    }

    /**
     * Things to do after the constructor and before the sync job
     *
     * @return void
     */
    protected function beforeExecute(): void
    {
        $this->report = $this->DirectoryReports->create($this->parentId);
        $this->initialize();
    }

    /**
     * @return void
     */
    private function afterExecute(): void
    {
        $this->report->status = DirectoryReport::STATUS_DONE;
        $this->DirectoryReports->save($this->report);
        if (!$this->isDryRun() && !$this->isPartOfAllSync()) {
            $deletedSecrets = $this->entitiesChangesDto->getDeletedEntities(Secret::class);
            $this->resourcesExpireResourcesService->expireResourcesForSecrets($deletedSecrets);
        }
    }

    /**
     * Get directory.
     *
     * @return \Passbolt\DirectorySync\Utility\DirectoryInterface
     */
    public function getDirectory(): DirectoryInterface
    {
        return $this->directory;
    }

    /**
     * Report back on a sync action
     *
     * @param \Passbolt\DirectorySync\Actions\Reports\ActionReport $reportItem report item
     * @return void
     */
    public function addReportItem(ActionReport $reportItem): void
    {
        $this->summary->add($reportItem);
        $this->DirectoryReports->DirectoryReportsItems->create($this->report->id, $reportItem);
    }

    /**
     * Get the summary of all reports
     *
     * @return \Passbolt\DirectorySync\Actions\Reports\ActionReportCollection
     */
    public function getSummary(): ActionReportCollection
    {
        return $this->summary;
    }

    /**
     * Get default admin.
     *
     * @return array|\Cake\Datasource\EntityInterface|mixed|null
     */
    private function getDefaultAdmin()
    {
        $defaultUser = $this->directoryOrgSettings->getDefaultUser();
        if (!empty($defaultUser)) {
            // Get default user from database.
            $defaultUser = $this->Users->findByUsernameCaseAware($defaultUser)
                ->find('notDisabled')
                ->where([
                    'Users.deleted' => false,
                    'Users.active' => true,
                    'Users.role_id' => $this->Users->Roles->getIdByName(Role::ADMIN),
                ])
                ->first();
            if (!empty($defaultUser)) {
                return $defaultUser;
            }
        }

        // If can't find corresponding config user, return first admin.
        return $this->Users->findFirstAdmin();
    }

    /**
     * Set Dryrun
     *
     * @param bool $dryRun dry run value
     * @return self
     */
    public function setDryRun(bool $dryRun): self
    {
        $this->dryRun = $dryRun;

        return $this;
    }

    /**
     * Get dryRun
     *
     * @return bool
     */
    protected function isDryRun(): bool
    {
        return $this->dryRun;
    }

    /**
     * If the present action is part of a global sync, we will want to avoid
     * certain actions (e.g. password expiry), that will be performed by the AllSyncAction class
     *
     * @return self
     */
    public function setAsPartOfAllSync(): self
    {
        $this->isPartOfAllSync = true;

        return $this;
    }

    /**
     * @return bool
     */
    protected function isPartOfAllSync(): bool
    {
        return $this->isPartOfAllSync;
    }

    /**
     * Convenient method to display the entity type in a readable manner, e.g. in error messages
     *
     * @return string
     */
    private function getSingularLoweredEntityType(): string
    {
        return Inflector::singularize(strtolower($this->getEntityType()));
    }

    /**
     * @return \App\Model\Dto\EntitiesChangesDto
     */
    public function getEntitiesChangesDto(): EntitiesChangesDto
    {
        return $this->entitiesChangesDto;
    }

    /**
     * Per default, user and groups are soft deleted when handling the entry deletion.
     * It is possible to change the behavior for users in the organization settings, in order
     * to disable the users instead of deleting them.
     * This way, the associated resources, permissions, secrets
     * are not deleted and can be easily recovered in the future in case of error in the sync.
     *
     * @param \App\Model\Entity\Group|\App\Model\Entity\User $entity group or user to delete or disable
     * @return \App\Model\Dto\EntitiesChangesDto|bool The list of entities changes, false if a validation error occurred.
     */
    protected function deleteOrDisableEntity(Entity $entity)
    {
        return $this->getTable()->softDelete($entity);
    }

    /**
     * Users can be soft-deleted, or considered as deleted when disabled
     *
     * @param \App\Model\Entity\User|\App\Model\Entity\Group $entity group or user to sync
     * @return bool
     */
    protected function isDeletedOrDisabled(Entity $entity): bool
    {
        return $entity->isDeleted();
    }
}

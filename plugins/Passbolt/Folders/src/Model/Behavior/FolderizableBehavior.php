<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.13.0
 */

namespace Passbolt\Folders\Model\Behavior;

use App\Model\Event\TableFindIndexBefore;
use Cake\Collection\CollectionInterface;
use Cake\Core\InstanceConfigTrait;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use UnexpectedValueException;

/**
 * Decorate a Table class to add the "folder_parent_id" property on its entities.
 *
 * @method \Passbolt\Folders\Model\Table\FoldersTable getTable()
 */
class FolderizableBehavior extends Behavior
{
    use InstanceConfigTrait;

    /**
     * Name of the finder to use with Query::find()
     *
     * @see Query::find()
     */
    public const FINDER_NAME = 'folder_parent';

    /**
     * Name of the property added to entities.
     */
    public const FOLDER_PARENT_ID_PROPERTY = 'folder_parent_id';

    /**
     * Name of the personal virtual field added to entity.
     */
    public const PERSONAL_PROPERTY = 'personal';

    /**
     * Default config
     *
     * These are merged with user-provided config when the behavior is used.
     *
     * events - an event-name array.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'implementedFinders' => [
            /** @uses findFolderParentId() */
            self::FINDER_NAME => 'findFolderParentId', // Make the finder available with the name "folder_parent"
        ],
        'implementedMethods' => [
            /** @uses formatResults */
            'formatResults' => 'formatResults', // containFolderParentId is available as mixin method for table using this behavior
        ],
        'events' => [
            'Model.afterSave' => ['new'],
            'Model.beforeFind' => ['always'],
            TableFindIndexBefore::EVENT_NAME => ['always'],
        ],
    ];

    /**
     * @var \Passbolt\Folders\Model\Table\FoldersRelationsTable
     */
    private $foldersRelationsTable;

    /**
     * List of the events for which the behavior must be triggered.
     *
     * The implemented events of this behavior depend on configuration
     *
     * @return array
     * @uses handleEvent()
     */
    public function implementedEvents()
    {
        return array_fill_keys(array_keys($this->_config['events']), 'handleEvent');
    }

    /**
     * @param array $config Config
     * @return void
     */
    public function initialize(array $config)
    {
        $this->foldersRelationsTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.FoldersRelations');
        parent::initialize($config);
    }

    /**
     * Finder method
     *
     * @param \Cake\ORM\Query $query The target query.
     * @param array $options Options
     * @return \Cake\ORM\Query
     * @see $_defaultConfig
     */
    public function findFolderParentId(Query $query, array $options)
    {
        return $this->formatResults($query, $options['user_id']);
    }

    /**
     * Format a query result and associate to each item its folder parent id and its personal status.
     *
     * @param \Cake\ORM\Query $query The target query.
     * @param string $userId The user id for whom the request has been executed.
     * @return \Cake\ORM\Query
     */
    public function formatResults(Query $query, string $userId)
    {
        return $query->formatResults(function (CollectionInterface $results) use ($userId) {
            $itemsIds = $results->extract('id')->toArray();
            $itemsFolderParentIdsHash = $this->getItemsFolderParentIdHash($itemsIds, $userId);
            $itemsUsageHash = $this->getItemsUsageHash($itemsIds);

            return $results->map(function (EntityInterface $entity) use ($itemsFolderParentIdsHash, $itemsUsageHash) {
                if (array_key_exists($entity->id, $itemsFolderParentIdsHash)) {
                    $folderParentId = $itemsFolderParentIdsHash[$entity->id];
                    $entity = $this->addFolderParentIdProperty($entity, $folderParentId);
                }
                if (array_key_exists($entity->id, $itemsUsageHash)) {
                    $isPersonal = $itemsUsageHash[$entity->id] === 1;
                    $entity = $this->addPersonalStatusProperty($entity, $isPersonal);
                }

                return $entity;
            });
        });
    }

    /**
     * Retrieve the folder parent ids of a list of items
     *
     * @param array $itemsIds The target item ids
     * @param string $userId The target user id
     * @return array
     * [
     *   ITEM_ID => FOLDER_PARENT_ID,
     *   ...
     * ]
     */
    private function getItemsFolderParentIdHash(array $itemsIds, string $userId)
    {
        $hash = [];

        if (!empty($itemsIds)) {
            $hash = $this->foldersRelationsTable->find()
                ->where([
                    'foreign_id IN' => $itemsIds,
                    'user_id' => $userId,
                ])
                ->select(['foreign_id', 'folder_parent_id'])
                ->disableHydration()
                ->combine('foreign_id', 'folder_parent_id')
                ->toArray();
        }

        return $hash;
    }

    /**
     * Retrieve the usage of a list of items
     *
     * @param array $itemsIds The target item ids
     * @return array
     * [
     *   ITEM_ID => integer,
     *   ...
     * ]
     */
    private function getItemsUsageHash(array $itemsIds)
    {
        $hash = [];

        if (!empty($itemsIds)) {
            $itemsPersonalStatusQuery = $this->foldersRelationsTable->find();

            $hash = $itemsPersonalStatusQuery->where(['foreign_id IN' => $itemsIds])
                ->select([
                    'foreign_id',
                    'count' => $itemsPersonalStatusQuery->func()->count('*'),
                ])
                ->group('foreign_id')
                ->disableHydration()
                ->combine('foreign_id', 'count')
                ->toArray();
        }

        return $hash;
    }

    /**
     * Add the folder_parent_id property to an entity
     *
     * @param \Cake\Datasource\EntityInterface $entity The target entity
     * @param string|null $folderParentId The folder parent id
     * @return \Cake\Datasource\EntityInterface
     */
    private function addFolderParentIdProperty(EntityInterface $entity, ?string $folderParentId = null)
    {
        $entity->setVirtual([self::FOLDER_PARENT_ID_PROPERTY], true);
        $entity->set(self::FOLDER_PARENT_ID_PROPERTY, $folderParentId);

        return $entity;
    }

    /**
     * Add the personal status property to an entity
     *
     * @param \Cake\Datasource\EntityInterface $entity The target entity
     * @param bool $isPersonal The status
     * @return \Cake\Datasource\EntityInterface
     */
    private function addPersonalStatusProperty(EntityInterface $entity, bool $isPersonal)
    {
        $entity->setVirtual([self::PERSONAL_PROPERTY], true);
        $entity->set(self::PERSONAL_PROPERTY, $isPersonal);

        return $entity;
    }

    /**
     * There is only one event handler which is called when one of the events declared in implementedEvents method is triggered.
     *
     * @param \Cake\Event\Event $event Event
     * @param mixed $data Data associated to the event
     * @param mixed $options Options associated to the event
     * @return void
     * @see implementedEvents()
     */
    public function handleEvent(Event $event, $data, $options)
    {
        switch ($event->getName()) {
            case TableFindIndexBefore::EVENT_NAME:
                $this->formatResults($data, $options->getUserId());
                break;
            case 'Model.afterSave':
                $this->handleAfterSave($data);
                break;
        }
    }

    /**
     * Handle after save.
     *
     * @param \Cake\Datasource\EntityInterface $entity The target entity
     * @return void
     */
    private function handleAfterSave(EntityInterface $entity)
    {
        $events = $this->_config['events'];
        $new = $entity->isNew() !== false;
        foreach ($events['Model.afterSave'] as $field => $when) {
            if (!in_array($when, ['always', 'new', 'existing'])) {
                $msg = __(
                    'When should be one of "always", "new" or "existing". The passed value "{0}" is invalid',
                    $when
                );
                throw new UnexpectedValueException($msg);
            }
            if ($when === 'always' || ($when === 'new' && $new) || ($when === 'existing' && !$new)) {
                // When the entity is a new entity, we use the created_by property.
                $folderParentId = $this->foldersRelationsTable
                    ->getItemFolderParentIdInUserTree($entity->created_by, $entity->id);
                $isPersonal = $this->foldersRelationsTable->isItemPersonal($entity->id);
                $this->addPersonalStatusProperty($entity, $isPersonal);
                $this->addFolderParentIdProperty($entity, $folderParentId);
            }
        }
    }
}

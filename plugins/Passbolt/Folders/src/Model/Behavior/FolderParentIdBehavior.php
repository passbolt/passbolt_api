<?php
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
 * @since         2.14.0
 */

namespace Passbolt\Folders\Model\Behavior;

use App\Model\Entity\Resource;
use App\Model\Event\TableFindIndexBefore;
use App\Model\Table\Dto\FindIndexOptions;
use Cake\Collection\CollectionInterface;
use Cake\Core\InstanceConfigTrait;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use InvalidArgumentException;
use Passbolt\Folders\Model\Entity\Folder;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Model\Table\FoldersRelationsTable;
use Passbolt\Folders\Model\Table\FoldersTable;
use UnexpectedValueException;

/**
 * Decorate a Table class to add the "folder_parent_id" property on its entities.
 *
 * @method FoldersTable getTable()
 */
class FolderParentIdBehavior extends Behavior
{
    use InstanceConfigTrait;

    /**
     * Name of the finder to use with Query::find()
     * @see Query::find()
     */
    const FINDER_NAME = 'folder_parent';

    /**
     * Name of the property added to entities.
     */
    const FOLDER_PARENT_ID_PROPERTY = 'folder_parent_id';

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
            /** @uses containFolderParentIdByUserId */
            'containFolderParentIdByUserId' => 'containFolderParentIdByUserId'  // containFolderParentId is available as mixin method for table using this behavior
        ],
        'events' => [
            'Model.afterSave' => ['new'],
            'Model.beforeFind' => ['always'],
            TableFindIndexBefore::EVENT_NAME => ['always'],
        ],
    ];

    /**
     * @var FoldersRelationsTable
     */
    private $foldersRelationsTable;

    /**
     * List of the events for which the behavior must be triggered.
     *
     * The implemented events of this behavior depend on configuration
     *
     * @uses handleEvent()
     * @return array
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
        if (isset($config['events'])) {
            $this->setConfig('events', $config['events'], false);
        }

        $this->foldersRelationsTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.FoldersRelations');

        parent::initialize($config);
    }

    /**
     * Finder method
     * @param Query $query Query
     * @param array $options Options
     * @return Query
     * @see $_defaultConfig
     */
    public function findFolderParentId(Query $query, array $options)
    {
        return $this->containFolderParentIdByUserId($query, $options['user_id']);
    }

    /**
     * There is only one event handler which is called when one of the events declared in implementedEvents method is triggered.
     *
     * @param Event $event Event
     * @param mixed $data Data associated to the event
     * @param mixed $options Options associated to the event
     * @return void
     * @see implementedEvents()
     */
    public function handleEvent(Event $event, $data, $options)
    {
        $events = $this->_config['events'];
        $eventName = $event->getName();
        switch ($eventName) {
            case TableFindIndexBefore::EVENT_NAME:
                /**
                 * @var Query $data
                 * @var FindIndexOptions $options
                 */
                $this->containFolderParentIdByUserId($data, $options->getUserId());
                break;
            case 'Model.beforeFind':
                break;
            case 'Model.afterSave':
                /** @var EntityInterface $entity */
                $entity = $data;
                $new = $entity->isNew() !== false;
                foreach ($events[$eventName] as $field => $when) {
                    if (!in_array($when, ['always', 'new', 'existing'])) {
                        throw new UnexpectedValueException(
                            sprintf('When should be one of "always", "new" or "existing". The passed value "%s" is invalid', $when)
                        );
                    }
                    if ($when === 'always' || ($when === 'new' && $new) || ($when === 'existing' && !$new)) {
                        // When the entity is a new entity, we use the created_by property.
                        $this->mapFolderParentIdFieldToEntity($entity, $entity->created_by);
                    }
                }
                break;
        }
    }

    /**
     * Apply the FolderParentId property mapper to each item of the collection.
     * @param Query $query Query
     * @param string $userId User ID to use to retrieve the folder parent id
     * @return Query
     */
    public function containFolderParentIdByUserId(Query $query, string $userId)
    {
        return $query->formatResults(function (CollectionInterface $results) use ($userId) {
            return $results->map(function (EntityInterface $entity) use ($userId) {
                return $this->mapFolderParentIdFieldToEntity($entity, $userId);
            });
        });
    }

    /**
     * @param EntityInterface $entity Entity to which map folder_parent_id property
     * @param string $userId The user id to retrieve the folder_parent_id for.
     * @return EntityInterface
     */
    private function mapFolderParentIdFieldToEntity(EntityInterface $entity, string $userId)
    {
        if (!$entity instanceof Resource && !$entity instanceof Folder) {
            throw new InvalidArgumentException('This entity can not have a folder_parent_id property.');
        }

        if (!$entity->id) {
            throw new InvalidArgumentException('The entity must have an ID.');
        }

        /** @var FoldersRelation $folderRelation */
        $folderRelation = $this->foldersRelationsTable->findUserFolderRelation($userId, $entity->id)->first();

        $entity->setVirtual([self::FOLDER_PARENT_ID_PROPERTY], true);
        $entity->set(self::FOLDER_PARENT_ID_PROPERTY, $folderRelation ? $folderRelation->folder_parent_id : null);

        return $entity;
    }
}

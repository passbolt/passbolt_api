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
use InvalidArgumentException;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Model\Table\FoldersTable;
use Passbolt\Folders\Service\PermissionsCreateService;
use UnexpectedValueException;

/**
 * Decorate a Table class to add the "folder_parent_id" property on its entities.
 *
 * @method FoldersTable getTable()
 */
class ContainFolderParentIdBehavior extends Behavior
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
     * Name of the personal virtual field added to entity.
     */
    const PERSONAL_PROPERTY = 'personal';

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
            'containFolderParentIdByUserId' => 'containFolderParentIdByUserId', // containFolderParentId is available as mixin method for table using this behavior
        ],
        'events' => [
            'Model.afterSave' => ['new'],
            'Model.beforeFind' => ['always'],
            TableFindIndexBefore::EVENT_NAME => ['always'],
        ],
    ];

    /**
     * @var PermissionsCreateService
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
        switch ($event->getName()) {
            case TableFindIndexBefore::EVENT_NAME:
                $this->containFolderParentIdByUserId($data, $options->getUserId());
                break;
            case 'Model.afterSave':
                $this->handleAfterSave($data);
                break;
        }
    }

    /**
     * Handle after save.
     * @param EntityInterface $entity The target entity
     * @return void
     */
    private function handleAfterSave(EntityInterface $entity)
    {
        $events = $this->_config['events'];
        $new = $entity->isNew() !== false;
        foreach ($events['Model.afterSave'] as $field => $when) {
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
        if (!$entity->id) {
            throw new InvalidArgumentException('The entity must have an ID.');
        }

        // retrieve and set the entity folder_parent_id field
        /** @var FoldersRelation $folderRelation */
        $folderRelation = $this->foldersRelationsTable->findUserFolderRelation($userId, $entity->id)->first();

        $entity->setVirtual([self::FOLDER_PARENT_ID_PROPERTY], true);
        $entity->set(self::FOLDER_PARENT_ID_PROPERTY, $folderRelation ? $folderRelation->folder_parent_id : null);

        // retrieve and set the entity personal field
        $personal = $this->foldersRelationsTable->isItemPersonal($entity->id);

        $entity->setVirtual([self::PERSONAL_PROPERTY], true);
        $entity->set(self::PERSONAL_PROPERTY, $personal);

        return $entity;
    }
}

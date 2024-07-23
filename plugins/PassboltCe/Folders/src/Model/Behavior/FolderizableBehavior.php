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
use App\Utility\Application\FeaturePluginAwareTrait;
use Cake\Collection\CollectionInterface;
use Cake\Core\Configure;
use Cake\Core\InstanceConfigTrait;
use Cake\Database\Expression\IdentifierExpression;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\ResultSetInterface;
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
    use FeaturePluginAwareTrait;
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
     * @var array<string, mixed>
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
     * @return array<string, mixed>
     * @uses handleEvent()
     */
    public function implementedEvents(): array
    {
        return array_fill_keys(array_keys($this->_config['events']), 'handleEvent');
    }

    /**
     * @param array $config Config
     * @return void
     */
    public function initialize(array $config): void
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
    public function findFolderParentId(Query $query, array $options): Query
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
    public function formatResults(Query $query, string $userId): Query
    {
        $this->table()->hasOne('FolderParentId')
            ->setClassName('Passbolt/Folders.FoldersRelations')
            ->setForeignKey('foreign_id')
            ->setConditions([
                'FolderParentId.user_id' => $userId,
                $query->expr()->isNotNull('FolderParentId.folder_parent_id'),
            ]);
        $query->contain('FolderParentId', function (Query $q) {
            return $q->select([
                'folder_parent_id' => 'FolderParentId.folder_parent_id',
            ]);
        });

        // Get the alias of the current table
        $foreignId = new IdentifierExpression($this->table()->aliasField('id'));
        // Count the number of folder relations for each entry (resource or folder, depending on the table being queried)
        // If the entry has only one folder relation, it is then known as personal. The personal property is set to 1.
        // If it has more than one folder relation, it is not personal, the property is set to 0
        // Else, the property is NULL
        $countSubQuery = $this->foldersRelationsTable->selectQuery();
        $personal = $query->expr()->case()
            ->when($query->expr()->eq('COUNT(*)', 1, 'integer'))
            ->then($query->newExpr('TRUE'), 'boolean')
            ->when($query->expr()->gt('COUNT(*)', 1, 'integer'))
            ->then($query->expr('FALSE'), 'boolean');
        $countSubQuery->select([
            self::PERSONAL_PROPERTY => $personal,
        ])->where(['FoldersRelations.foreign_id' => $foreignId]);
        $selectTypeMap = $query->getSelectTypeMap();
        $selectTypeMap->addDefaults([self::PERSONAL_PROPERTY => 'boolean']);

        return $query->selectAlso([self::PERSONAL_PROPERTY => $countSubQuery]);
    }

    /**
     * @param array $entity entity on which the personal property should be unset if null
     * @return array
     * @deprecated in v4.10 remove this method once the null value is allowed for the field personal. As of now, this is not supported by the browser extension.
     */
    public static function unsetPersonalPropertyIfNull(array $entity): array
    {
        if (!Configure::read('passbolt.plugins.folders.enabled')) {
            return $entity;
        }
        if (array_key_exists(self::PERSONAL_PROPERTY, $entity) && is_null($entity[self::PERSONAL_PROPERTY])) {
            unset($entity[self::PERSONAL_PROPERTY]);
        }

        return $entity;
    }

    /**
     * @param \Cake\Datasource\ResultSetInterface $entities Entities on which the personal property should be unset if null
     * @return \Cake\Collection\CollectionInterface
     * @deprecated in v4.10 remove this method once the null value is allowed for the field personal. As of now, this is not supported by the browser extension.
     */
    public static function unsetPersonalPropertyIfNullOnResultSet(ResultSetInterface $entities): CollectionInterface
    {
        return $entities->map(function ($row) {
            return self::unsetPersonalPropertyIfNull($row);
        });
    }

    /**
     * Add the folder_parent_id property to an entity
     *
     * @param array|\Cake\Datasource\EntityInterface $entity The target entity
     * @param string|null $folderParentId The folder parent id
     * @return array|\Cake\Datasource\EntityInterface
     */
    private function addFolderParentIdProperty($entity, ?string $folderParentId = null)
    {
        if ($entity instanceof EntityInterface) {
            $entity->setVirtual([self::FOLDER_PARENT_ID_PROPERTY], true);
            $entity->set(self::FOLDER_PARENT_ID_PROPERTY, $folderParentId);
        } else {
            $entity[self::FOLDER_PARENT_ID_PROPERTY] = $folderParentId;
        }

        return $entity;
    }

    /**
     * Add the personal status property to an entity
     *
     * @param array|\Cake\Datasource\EntityInterface $entity The target entity
     * @param bool $isPersonal The status
     * @return array|\Cake\Datasource\EntityInterface
     */
    private function addPersonalStatusProperty($entity, bool $isPersonal)
    {
        if ($entity instanceof EntityInterface) {
            $entity->setVirtual([self::PERSONAL_PROPERTY], true);
            $entity->set(self::PERSONAL_PROPERTY, $isPersonal);
        } else {
            $entity[self::PERSONAL_PROPERTY] = $isPersonal;
        }

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
                $msg = 'When should be one of "always", "new" or "existing". The passed value "{0}" is invalid';
                throw new UnexpectedValueException($msg);
            }
            if ($when === 'always' || ($when === 'new' && $new) || ($when === 'existing' && !$new)) {
                // When the entity is a new entity, we use the created_by property.
                $folderParentId = $this->foldersRelationsTable
                    ->getItemFolderParentIdInUserTree($entity->get('created_by'), $entity->get('id'));
                $isPersonal = $this->foldersRelationsTable->isItemPersonal($entity->get('id'));
                $this->addPersonalStatusProperty($entity, $isPersonal);
                $this->addFolderParentIdProperty($entity, $folderParentId);
            }
        }
    }
}
